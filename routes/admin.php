<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'isAdmin'])->prefix('dashboard')->name('dashboard.')->group(function () {

    Route::get('/', [App\Http\Controllers\Dashboard\DashboardController::class, 'index'])
        ->name('index');

    // Products
    Route::post('/products/bulk-destroy', [App\Http\Controllers\Dashboard\ProductController::class, 'bulkDestroy'])
        ->name('products.bulk-destroy');
    Route::get('/products', [App\Http\Controllers\Dashboard\ProductController::class, 'index'])
        ->name('products.index');
    Route::get('/products/create', [App\Http\Controllers\Dashboard\ProductController::class, 'create'])
        ->name('products.create');
    Route::post('/products', [App\Http\Controllers\Dashboard\ProductController::class, 'store'])
        ->name('products.store');
    Route::get('/products/{id}/edit', [App\Http\Controllers\Dashboard\ProductController::class, 'edit'])
        ->name('products.edit');
    Route::put('/products/{id}', [App\Http\Controllers\Dashboard\ProductController::class, 'update'])
        ->name('products.update');
    Route::delete('/products/{id}', [App\Http\Controllers\Dashboard\ProductController::class, 'destroy'])
        ->name('products.destroy');
    Route::delete('/products/{id}/images/{imageId}', [App\Http\Controllers\Dashboard\ProductController::class, 'destroyImage'])
        ->name('products.images.destroy');

    // Categories
    Route::post('/categories/bulk-destroy', [App\Http\Controllers\Dashboard\CategoryController::class, 'bulkDestroy'])
        ->name('categories.bulk-destroy');
    Route::get('/categories', [App\Http\Controllers\Dashboard\CategoryController::class, 'index'])
        ->name('categories.index');
    Route::get('/categories/create', [App\Http\Controllers\Dashboard\CategoryController::class, 'create'])
        ->name('categories.create');
    Route::post('/categories', [App\Http\Controllers\Dashboard\CategoryController::class, 'store'])
        ->name('categories.store');
    Route::get('/categories/{id}/edit', [App\Http\Controllers\Dashboard\CategoryController::class, 'edit'])
        ->name('categories.edit');
    Route::put('/categories/{id}', [App\Http\Controllers\Dashboard\CategoryController::class, 'update'])
        ->name('categories.update');
    Route::delete('/categories/{id}', [App\Http\Controllers\Dashboard\CategoryController::class, 'destroy'])
        ->name('categories.destroy');

    // Banners
    Route::post('/banners/bulk-destroy', [App\Http\Controllers\Dashboard\BannerController::class, 'bulkDestroy'])
        ->name('banners.bulk-destroy');
    Route::get('/banners', [App\Http\Controllers\Dashboard\BannerController::class, 'index'])
        ->name('banners.index');
    Route::post('/banners', [App\Http\Controllers\Dashboard\BannerController::class, 'store'])
        ->name('banners.store');
    Route::put('/banners/{id}', [App\Http\Controllers\Dashboard\BannerController::class, 'update'])
        ->name('banners.update');
    Route::delete('/banners/{id}', [App\Http\Controllers\Dashboard\BannerController::class, 'destroy'])
        ->name('banners.destroy');

    // Orders
    Route::post('/orders/bulk-destroy', [App\Http\Controllers\Dashboard\OrderController::class, 'bulkDestroy'])
        ->name('orders.bulk-destroy');
    Route::get('/orders', [App\Http\Controllers\Dashboard\OrderController::class, 'index'])
        ->name('orders.index');
    Route::put('/orders/{order}/status', [App\Http\Controllers\Dashboard\OrderController::class, 'updateStatus'])
        ->name('orders.updateStatus');
    Route::delete('/orders/{id}', [App\Http\Controllers\Dashboard\OrderController::class, 'destroy'])
        ->name('orders.destroy');

    // Settings
    Route::get('/settings', [App\Http\Controllers\Dashboard\SettingController::class, 'index'])
        ->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\Dashboard\SettingController::class, 'update'])
        ->name('settings.update');

});
