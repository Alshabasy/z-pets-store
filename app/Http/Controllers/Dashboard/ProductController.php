<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use App\Models\ProductImage;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'mainImage'])->latest();
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $products = $query->paginate(20)->withQueryString();
        
        return view('dashboard.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)
            ->with('parent')
            ->orderBy('name')
            ->get();
            
        $tags = Tag::all();
        
        return view('dashboard.products.create', compact('categories', 'tags'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        
        $data['in_stock'] = $request->boolean('in_stock');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        
        $slug = Str::slug($data['slug'] ?? $data['name']);
        $originalSlug = $slug;
        $counter = 2;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        $data['slug'] = $slug;

        $product = Product::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products/' . $product->id, 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_main' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        if ($request->has('tags')) {
            $product->tags()->sync($request->tags);
        }

        Cache::forget('home.featured_products');

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::with(['images', 'tags', 'category'])->findOrFail($id);
        
        $categories = Category::where('is_active', true)
            ->with('parent')
            ->orderBy('name')
            ->get();
            
        $tags = Tag::all();
        
        return view('dashboard.products.edit', compact('product', 'categories', 'tags'));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validated();
        
        $data['in_stock'] = $request->boolean('in_stock');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        
        if ($data['slug'] !== $product->slug) {
            $slug = Str::slug($data['slug']);
            $originalSlug = $slug;
            $counter = 2;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }

        $product->update($data);

        if ($request->hasFile('images')) {
            $existingCount = $product->images()->count();
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products/' . $product->id, 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_main' => ($existingCount === 0 && $index === 0),
                    'sort_order' => $existingCount + $index,
                ]);
            }
        }

        if ($request->has('tags')) {
            $product->tags()->sync($request->tags);
        } else {
            $product->tags()->sync([]);
        }

        Cache::forget('home.featured_products');

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        
        $product->delete();

        Cache::forget('home.featured_products');
        
        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function destroyImage($productId, $imageId)
    {
        $product = Product::findOrFail($productId);
        $image = ProductImage::where('product_id', $productId)->findOrFail($imageId);
        
        Storage::disk('public')->delete($image->path);
        $wasMain = $image->is_main;
        $image->delete();
        
        if ($wasMain) {
            $nextImage = ProductImage::where('product_id', $productId)->orderBy('sort_order')->first();
            if ($nextImage) {
                $nextImage->update(['is_main' => true]);
            }
        }
        
        return response()->json(['success' => true]);
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id'
        ]);

        $products = Product::whereIn('id', $request->ids)->with('images')->get();
        foreach ($products as $product) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->path);
            }
            $product->delete();
        }

        Cache::forget('home.featured_products');

        return response()->json([
            'success' => true,
            'deleted' => $products->count(),
        ]);
    }
}
