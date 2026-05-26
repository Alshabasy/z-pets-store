<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_flow()
    {
        // Seed the database to ensure we have products and settings
        $this->artisan('db:seed');

        $product = Product::first() ?? Product::factory()->create([
            'category_id' => Category::factory()->create()->id,
            'is_active' => true,
            'in_stock' => true,
        ]);

        // 1. Visit cart to ensure session initializes (and CSRF token in session)
        $this->get('/cart')->assertStatus(200);

        // 2. Add product to cart
        $this->post('/cart/add', [
            'product_id' => $product->id,
            'qty' => 2,
        ], [
            'X-CSRF-TOKEN' => csrf_token(),
            'Accept' => 'application/json',
        ])->assertStatus(200);

        // 3. Visit checkout page
        $this->get('/checkout')
            ->assertStatus(200)
            ->assertSee($product->name);

        // 4. Submit checkout form
        $response = $this->post('/checkout', [
            'customer_name' => 'John Doe',
            'customer_phone' => '01012345678',
            'notes' => 'Please deliver in the evening',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('checkout.confirm'));

        // 5. Verify database
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'John Doe',
            'customer_phone' => '01012345678',
            'status' => 'new',
        ]);

        $order = Order::first();
        $this->assertNotNull($order);

        // 6. Visit confirm page
        $responseConfirm = $this->get('/checkout/confirm');
        $responseConfirm->assertStatus(200);
        $responseConfirm->assertSee('John Doe');
        $responseConfirm->assertSee('Order Placed Successfully');
    }
}
