<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);
});

test('homepage loads with acceptable query count', function () {
    DB::enableQueryLog();
    $response = $this->get('/');
    $queries = DB::getQueryLog();
    DB::disableQueryLog();

    $response->assertStatus(200);
    expect(count($queries))->toBeLessThanOrEqual(40);
});

test('product listing loads with acceptable query count', function () {
    DB::enableQueryLog();
    $response = $this->get('/products');
    $queries = DB::getQueryLog();
    DB::disableQueryLog();

    $response->assertStatus(200);
    expect(count($queries))->toBeLessThanOrEqual(30);
});

test('product detail page loads with acceptable query count', function () {
    $product = Product::with('category')->where('is_active', true)->first();
    if (! $product) {
        return;
    }

    DB::enableQueryLog();
    $response = $this->get('/products/'.$product->slug);
    $queries = DB::getQueryLog();
    DB::disableQueryLog();

    $response->assertStatus(200);
    expect(count($queries))->toBeLessThanOrEqual(30);
});

test('dashboard overview loads with acceptable query count', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    DB::enableQueryLog();
    $response = $this->actingAs($admin)->get('/dashboard');
    $queries = DB::getQueryLog();
    DB::disableQueryLog();

    $response->assertStatus(200);
    expect(count($queries))->toBeLessThanOrEqual(20);
});
