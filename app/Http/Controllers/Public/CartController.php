<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Cart;

class CartController extends Controller
{
    public function index()
    {
        return view('pages.cart.index', [
            'items' => Cart::items(),
            'total' => Cart::total(),
        ]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'integer|min:1|max:10',
        ]);

        $qty = $validated['qty'] ?? 1;
        $product = \App\Models\Product::findOrFail($validated['product_id']);
        Cart::add($product, $qty);

        return response()->json([
            'success' => true,
            'count' => Cart::count(),
            'message' => 'Added to cart!',
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string',
            'qty' => 'required|integer',
        ]);

        Cart::update($validated['slug'], $validated['qty']);

        return redirect()->back()->with('success', 'Cart updated successfully.');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string',
        ]);

        Cart::remove($validated['slug']);

        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        Cart::clear();

        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }
}
