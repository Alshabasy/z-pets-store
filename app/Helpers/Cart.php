<?php

namespace App\Helpers;

use App\Models\Product;

class Cart
{
    /**
     * Add a product to the cart session.
     * 
     * @param Product $product
     * @param int $qty
     * @return void
     */
    public static function add(Product $product, int $qty = 1): void
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->slug])) {
            $cart[$product->slug]['quantity'] += $qty;
        } else {
            // Get main image path
            $imagePath = $product->mainImage ? $product->mainImage->path : 'images/placeholder.jpg';
            
            $cart[$product->slug] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => (float) ($product->sale_price ?? $product->price),
                'quantity' => $qty,
                'image'    => 'storage/' . $imagePath,
                'slug'     => $product->slug,
            ];
        }

        session()->put('cart', $cart);
    }

    /**
     * Remove an item from the cart session by slug.
     * 
     * @param string $slug
     * @return void
     */
    public static function remove(string $slug): void
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$slug])) {
            unset($cart[$slug]);
            session()->put('cart', $cart);
        }
    }

    /**
     * Update the quantity of an item in the cart session.
     * 
     * @param string $slug
     * @param int $qty
     * @return void
     */
    public static function update(string $slug, int $qty): void
    {
        if ($qty <= 0) {
            static::remove($slug);
            return;
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$slug])) {
            $cart[$slug]['quantity'] = $qty;
            session()->put('cart', $cart);
        }
    }

    /**
     * Clear the cart session.
     * 
     * @return void
     */
    public static function clear(): void
    {
        session()->forget('cart');
    }

    /**
     * Get all items in the cart session.
     * 
     * @return array
     */
    public static function items(): array
    {
        return session()->get('cart', []);
    }

    /**
     * Get the total count of items in the cart.
     * 
     * @return int
     */
    public static function count(): int
    {
        $cart = static::items();
        $count = 0;
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    /**
     * Get the subtotal of the cart.
     * 
     * @return float
     */
    public static function subtotal(): float
    {
        $cart = static::items();
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return (float) $subtotal;
    }

    /**
     * Get the total of the cart (same as subtotal).
     * 
     * @return float
     */
    public static function total(): float
    {
        return static::subtotal();
    }
}
