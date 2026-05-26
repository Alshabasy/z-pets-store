<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['mainImage', 'category', 'tags'])->where('is_active', true);
        
        // Filters
        if ($request->filled('min_price')) {
            $query->whereRaw(
                'COALESCE(sale_price, price) >= ?',
                [(float) $request->min_price]
            );
        }
        if ($request->filled('max_price')) {
            $query->whereRaw(
                'COALESCE(sale_price, price) <= ?',
                [(float) $request->max_price]
            );
        }
        if ($request->boolean('in_stock')) {
            $query->where('in_stock', true);
        }
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }
        
        // Sorting
        $query->orderByRaw('in_stock DESC');
        
        $sort = $request->get('sort');
        if ($sort === 'price_asc') {
            $query->orderByRaw('COALESCE(sale_price, price) ASC');
        } elseif ($sort === 'price_desc') {
            $query->orderByRaw('COALESCE(sale_price, price) DESC');
        } elseif ($sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } else {
            $query->latest();
        }
        
        $products = $query->paginate(16)->withQueryString();
        
        $tags = Tag::all();
        $allCategories = Category::whereNull('parent_id')->where('is_active', true)->get();
        
        return view('pages.products.index', compact('products', 'tags', 'allCategories'));
    }

    public function show($slug)
    {
        $product = Product::with(['category.parent', 'images', 'mainImage', 'tags'])->where('slug', $slug)->firstOrFail();
        
        $relatedProducts = Product::with(['mainImage', 'category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();
            
        return view('pages.products.show', compact('product', 'relatedProducts'));
    }
}
