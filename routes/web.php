<?php

use Illuminate\Support\Facades\Route;

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// Public routes
Route::get('/robots.txt', function () {
    $path = public_path('robots.txt');

    return response(file_get_contents($path), 200, ['Content-Type' => 'text/plain']);
});

Route::get('/sitemap.xml', function () {
    $path = public_path('sitemap.xml');
    if (! file_exists($path)) {
        \Illuminate\Support\Facades\Artisan::call('sitemap:generate');
    }

    return response(file_get_contents($path), 200, ['Content-Type' => 'application/xml']);
});

Route::get('/', [App\Http\Controllers\Public\HomeController::class, 'index'])
    ->name('home');

Route::get('/products', [App\Http\Controllers\Public\ProductController::class, 'index'])
    ->name('products.index');
Route::get('/products/{slug}', [App\Http\Controllers\Public\ProductController::class, 'show'])
    ->name('products.show');

Route::get('/category/{slug}', [App\Http\Controllers\Public\CategoryController::class, 'show'])
    ->name('category.show');

Route::get('/search', [App\Http\Controllers\Public\SearchController::class, 'index'])
    ->name('search');

Route::get('/cart', [App\Http\Controllers\Public\CartController::class, 'index'])
    ->name('cart.index');
Route::post('/cart/add', [App\Http\Controllers\Public\CartController::class, 'add'])
    ->name('cart.add');
Route::post('/cart/update', [App\Http\Controllers\Public\CartController::class, 'update'])
    ->name('cart.update');
Route::post('/cart/remove', [App\Http\Controllers\Public\CartController::class, 'remove'])
    ->name('cart.remove');
Route::post('/cart/clear', [App\Http\Controllers\Public\CartController::class, 'clear'])
    ->name('cart.clear');

Route::get('/checkout', [App\Http\Controllers\Public\CheckoutController::class, 'index'])
    ->name('checkout.index');
Route::post('/checkout', [App\Http\Controllers\Public\CheckoutController::class, 'store'])
    ->name('checkout.store');
Route::get('/checkout/confirm', [App\Http\Controllers\Public\CheckoutController::class, 'confirm'])
    ->name('checkout.confirm');

// Admin routes
require __DIR__.'/admin.php';

// Profile routes (if needed, though not in the requested Step 3 list, I'll keep them if they don't conflict)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});
