<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'SettingSeeder']);
});

test('guest cannot access dashboard', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('regular user cannot access dashboard', function () {
    $user = User::factory()->create(['role' => 'customer']);
    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertStatus(403);
});

test('admin can access dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($admin)->get('/dashboard');
    $response->assertStatus(200);
});

test('admin can access products dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($admin)->get('/dashboard/products');
    $response->assertStatus(200);
});

test('admin can access orders dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($admin)->get('/dashboard/orders');
    $response->assertStatus(200);
});

test('admin can access settings dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($admin)->get('/dashboard/settings');
    $response->assertStatus(200);
});

test('guest redirects to login from any dashboard route', function () {
    $routes = [
        '/dashboard',
        '/dashboard/products',
        '/dashboard/orders',
        '/dashboard/categories',
        '/dashboard/settings',
        '/dashboard/banners',
    ];
    foreach ($routes as $route) {
        $this->get($route)->assertRedirect('/login');
    }
});
