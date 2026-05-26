<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;

class CategoryController extends Controller
{
    public function show(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $subcategories = Category::where('parent_id', $category->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        
        $categoryIds = [$category->id, ...$subcategories->pluck('id')->toArray()];
        
        $query = Product::with(['mainImage', 'category', 'tags'])
            ->whereIn('category_id', $categoryIds)
            ->where('is_active', true);
            
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
        
        return view('pages.categories.show', compact('category', 'subcategories', 'products', 'tags'));
    }
}
