<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);
});

test('homepage has required meta tags', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
    $response->assertSee('<meta name="description"', false);
    $response->assertSee('<meta property="og:title"', false);
    $response->assertSee('<meta property="og:description"', false);
    $response->assertSee('<link rel="canonical"', false);
});

test('product page has correct title and meta description', function () {
    $product = Product::where('is_active', true)->first();
    if (! $product) {
        return;
    }

    $response = $this->get('/products/'.$product->slug);
    $response->assertStatus(200);
    $response->assertSee($product->name);
    $response->assertSee('<link rel="canonical"', false);
});

test('sitemap is accessible and valid xml', function () {
    $this->artisan('sitemap:generate');
    $response = $this->get('/sitemap.xml');
    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/xml');
    $response->assertSee('<urlset', false);
    $response->assertSee('<loc>', false);
});

test('robots.txt disallows dashboard', function () {
    $response = $this->get('/robots.txt');
    $response->assertStatus(200);
    $response->assertSee('Disallow: /dashboard');
});

test('product images have alt attributes', function () {
    $product = Product::where('is_active', true)->first();
    if (! $product) {
        return;
    }

    $response = $this->get('/products/'.$product->slug);
    $content = $response->getContent();
    preg_match_all('/<img(?![^>]*\balt\s*=)[^>]*>/i', $content, $matches);
    expect(count($matches[0]))->toBe(0);
});
