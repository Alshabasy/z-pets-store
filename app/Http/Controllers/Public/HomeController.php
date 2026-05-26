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
        $banners = \App\Models\Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $mainCategories = \App\Models\Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->withCount('products')
            ->get();

        $featuredProducts = \App\Models\Product::with([
                'category', 'mainImage', 'tags'
            ])
            ->where('is_featured', true)
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $newArrivals = \App\Models\Product::with(['category', 'mainImage'])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $storeName    = \App\Models\Setting::getValue('store_name', config('app.name'));
        $storeTagline = \App\Models\Setting::getValue('store_tagline', '');

        return view('pages.home', compact(
            'banners', 'mainCategories', 'featuredProducts',
            'newArrivals', 'storeName', 'storeTagline'
        ));
    }
}
