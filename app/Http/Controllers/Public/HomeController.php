<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Cache::remember('home.banners', 3600, function () {
            return Banner::where('is_active', true)->orderBy('sort_order')->get();
        });

        $mainCategories = Cache::remember('home.main_categories', 3600, function () {
            return Category::whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->withCount('products')
                ->get();
        });

        $featuredProducts = Cache::remember('home.featured_products', 1800, function () {
            return Product::with(['category', 'mainImage', 'tags'])
                ->where('is_featured', true)
                ->where('is_active', true)
                ->latest()
                ->take(8)
                ->get();
        });

        $newArrivals = Product::with(['category', 'mainImage'])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $storeName = Setting::getValue('store_name', 'Z-Pets Store');

        return view('pages.home', compact(
            'banners',
            'mainCategories',
            'featuredProducts',
            'newArrivals',
            'storeName'
        ));
    }
}
