<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Setting;
use App\Helpers\Cart;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    public function index()
    {
        if (Cart::count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Add some products first!');
        }
        $items = Cart::items();
        $total = Cart::total();
        return view('pages.checkout.index', compact('items', 'total'));
    }

    public function store(StoreOrderRequest $request)
    {
        // Guard: empty cart
        if (Cart::count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        // Guard: out-of-stock check
        $cartItems = Cart::items();
        $outOfStock = [];
        foreach ($cartItems as $item) {
            $product = \App\Models\Product::find($item['id']);
            if (!$product || !$product->in_stock) {
                $outOfStock[] = $item['name'];
            }
        }
        if (!empty($outOfStock)) {
            return redirect()->route('cart.index')
                ->with('error', 'These items are out of stock: '
                    . implode(', ', $outOfStock)
                    . '. Please remove them and try again.');
        }

        // Build items snapshot
        $itemsSnapshot = array_map(fn($item) => [
            'name'  => $item['name'],
            'qty'   => $item['quantity'],
            'price' => (float) $item['price'],
        ], array_values($cartItems));

        // Save order to database
        $order = Order::create([
            'order_number'   => Order::generateOrderNumber(),
            'customer_name'  => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'notes'          => $request->notes,
            'items_snapshot' => $itemsSnapshot,
            'subtotal'       => Cart::subtotal(),
            'total'          => Cart::total(),
            'status'         => 'new',
        ]);

        // Generate PDF receipt
        $storeName = Setting::getValue('store_name', 'Z-Pets Store');
        $pdf = Pdf::loadView('pdf.order-receipt', [
            'order'     => $order,
            'items'     => $itemsSnapshot,
            'storeName' => $storeName,
        ])->setPaper('a4', 'portrait');

        // Save PDF to storage/app/public/orders/
        $pdfFileName = $order->order_number . '.pdf';
        $pdfPath     = 'orders/' . $pdfFileName;
        Storage::disk('public')->put($pdfPath, $pdf->output());

        // Save pdf_path to order
        $order->update(['pdf_path' => $pdfPath]);

        // Build public PDF URL (for WhatsApp message link)
        $pdfUrl = url('storage/' . $pdfPath);

        // Build WhatsApp notification message for seller
        $itemLines = '';
        foreach ($itemsSnapshot as $item) {
            $lineTotal  = number_format($item['price'] * $item['qty'], 2);
            $itemLines .= "  • {$item['qty']}x {$item['name']} — EGP {$lineTotal}\n";
        }
        $whatsappMessage = "🐾 *New Order — {$storeName}*\n\n"
            . "*Order:* {$order->order_number}\n"
            . "*Date:* " . $order->created_at->format('d M Y, h:i A') . "\n\n"
            . "*Customer:* {$order->customer_name}\n"
            . "*Phone:* {$order->customer_phone}\n\n"
            . "*Items:*\n{$itemLines}\n"
            . "*Total: EGP " . number_format($order->total, 2) . "*\n\n"
            . ($order->notes ? "*Notes:* {$order->notes}\n\n" : '')
            . "📄 *Receipt PDF:* {$pdfUrl}";

        $sellerPhone   = Setting::getValue('whatsapp_number', '201000000000');
        $whatsappUrl   = 'https://wa.me/' . $sellerPhone
            . '?text=' . rawurlencode($whatsappMessage);

        // Clear cart
        Cart::clear();

        // Store data in session for confirm page
        session([
            'last_order_id'  => $order->id,
            'whatsapp_url'   => $whatsappUrl,
        ]);

        return redirect()->route('checkout.confirm');
    }

    public function confirm()
    {
        $orderId = session('last_order_id');
        if (!$orderId) {
            return redirect()->route('home');
        }
        $order = Order::find($orderId);
        if (!$order) {
            return redirect()->route('home');
        }
        $pdfUrl      = $order->pdf_path ? asset('storage/' . $order->pdf_path) : null;
        $whatsappUrl = session('whatsapp_url');
        return view('pages.checkout.confirm', compact('order', 'pdfUrl', 'whatsappUrl'));
    }
}
