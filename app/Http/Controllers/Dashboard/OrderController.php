<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(25)->withQueryString();

        $orders->getCollection()->transform(function ($order) {
            $storeName    = \App\Models\Setting::getValue('store_name', 'Z-Pets Store');
            $sellerPhone  = \App\Models\Setting::getValue('whatsapp_number', '201000000000');
            $pdfUrl       = $order->pdf_path ? url('storage/' . $order->pdf_path) : null;
            $items        = is_array($order->items_snapshot)
                              ? $order->items_snapshot
                              : json_decode($order->items_snapshot, true);
            $itemLines    = '';
            foreach ($items as $item) {
                $qty = (int) ($item['qty'] ?? 1);
                $lineTotal   = number_format($item['price'] * $qty, 2);
                $itemLines  .= "  • {$qty}x {$item['name']} — EGP {$lineTotal}\n";
            }
            $msg = "🐾 *Order — {$storeName}*\n\n"
                 . "*Order:* {$order->order_number}\n"
                 . "*Customer:* {$order->customer_name}\n"
                 . "*Phone:* {$order->customer_phone}\n\n"
                 . "*Items:*\n{$itemLines}\n"
                 . "*Total: EGP " . number_format($order->total, 2) . "*\n\n"
                 . ($pdfUrl ? "📄 *Receipt:* {$pdfUrl}" : '');
            $order->whatsapp_notify_url = 'https://wa.me/' . $sellerPhone
                . '?text=' . rawurlencode($msg);
            return $order;
        });

        $counts = [
            'all' => Order::count(),
            'new' => Order::where('status', 'new')->count(),
            'seen' => Order::where('status', 'seen')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'completed' => Order::where('status', 'completed')->count(),
        ];

        return view('dashboard.orders.index', compact('orders', 'counts'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:new,seen,confirmed,completed'
        ]);

        // $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        Cache::forget('dashboard.stats');

        return response()->json([
            'success' => true,
            'status' => $order->status
        ]);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        if ($order->pdf_path) {
            Storage::disk('public')->delete($order->pdf_path);
        }

        $order->delete();

        Cache::forget('dashboard.stats');

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully.',
            'new_total_count' => Order::count(),
        ]);
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:orders,id'
        ]);

        $orders = Order::whereIn('id', $request->ids)->get();
        foreach ($orders as $order) {
            if ($order->pdf_path) {
                Storage::disk('public')->delete($order->pdf_path);
            }
            $order->delete();
        }

        Cache::forget('dashboard.stats');

        return response()->json([
            'success' => true,
            'deleted' => $orders->count(),
            'new_total_count' => Order::count(),
        ]);
    }
}
