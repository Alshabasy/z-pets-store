<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q', '');
        
        if (empty($q) || strlen($q) < 2) {
            $categories = Category::whereNull('parent_id')->where('is_active', true)->take(4)->get();
            return view('pages.products.search', [
                'products' => collect(),
                'query' => $q,
                'count' => 0,
                'categories' => $categories
            ]);
        }
        
        $products = Product::with(['mainImage', 'category'])
            ->where('is_active', true)
            ->where(function($query) use ($q) {
                $query->where('name', 'LIKE', "%{$q}%")
                      ->orWhere('short_description', 'LIKE', "%{$q}%")
                      ->orWhereHas('category', fn($q2) => $q2->where('name', 'LIKE', "%{$q}%"));
            })
            ->orderByRaw('in_stock DESC')
            ->paginate(16)
            ->withQueryString();
            
        $categories = Category::whereNull('parent_id')->where('is_active', true)->take(4)->get();

        return view('pages.products.search', [
            'products' => $products,
            'query' => $q,
            'count' => $products->total(),
            'categories' => $categories
        ]);
    }
}
