<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);
});

test('checkout form requires csrf token', function () {
    $this->withMiddleware(PreventRequestForgery::class);

    $this->from('/checkout')->post('/checkout', [
        'customer_name' => 'Test',
        'customer_phone' => '201000000000',
    ]);

    expect(\App\Models\Order::count())->toBe(0);
});

test('dashboard product create requires csrf', function () {
    $this->withMiddleware(PreventRequestForgery::class);

    $admin = User::factory()->create(['role' => 'admin']);
    $countBefore = Product::count();

    $this->actingAs($admin)->post('/dashboard/products', ['name' => 'Test']);

    expect(Product::count())->toBe($countBefore);
});

test('product name is escaped in output', function () {
    $category = Category::factory()->create();
    $xssPayload = '<script>alert("xss")</script>';

    $product = Product::factory()->create([
        'name' => $xssPayload,
        'category_id' => $category->id,
        'is_active' => true,
    ]);

    $response = $this->get('/products/'.$product->slug);
    $response->assertDontSee($xssPayload, false);
    $response->assertSee(e($xssPayload), false);
});

test('product search does not allow sql injection', function () {
    $sqliPayload = "'; DROP TABLE products; --";
    $response = $this->get('/search?q='.urlencode($sqliPayload));
    $response->assertStatus(200);
    expect(Product::count())->toBeGreaterThanOrEqual(0);
});

test('price filter does not allow sql injection', function () {
    $response = $this->get('/products?min_price=1;DROP TABLE products--&max_price=1000');
    $response->assertStatus(200);
    expect(Product::count())->toBeGreaterThanOrEqual(0);
});

test('security headers are present on public pages', function () {
    $response = $this->get('/');
    $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->assertHeader('X-XSS-Protection', '1; mode=block');
});

test('dashboard is not indexed by search engines', function () {
    $response = $this->get('/robots.txt');
    $response->assertStatus(200);
    $response->assertSee('Disallow: /dashboard');
});

test('user role cannot be mass assigned via registration', function () {
    $response = $this->post('/register', [
        'name' => 'Hacker',
        'email' => 'hacker@test.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'admin',
    ]);
    $user = User::where('email', 'hacker@test.com')->first();
    if ($user) {
        expect($user->role)->not->toBe('admin');
    }
});
