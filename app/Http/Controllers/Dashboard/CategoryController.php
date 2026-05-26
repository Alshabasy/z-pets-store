<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')
            ->withCount(['products', 'children as subcategories_count'])
            ->orderBy('sort_order')
            ->paginate(20);
            
        return view('dashboard.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('dashboard.categories.create', compact('parentCategories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        
        $slug = Str::slug($data['slug'] ?? $data['name']);
        $originalSlug = $slug;
        $counter = 2;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        $data['slug'] = $slug;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        Cache::forget('home.main_categories');
        Cache::forget('nav.categories');

        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::with('parent')->findOrFail($id);
        
        $parentCategories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->where('id', '!=', $id)
            ->orderBy('name')
            ->get();
            
        return view('dashboard.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->validated();
        
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = isset($data['sort_order'])
            ? (int) $data['sort_order'] : $category->sort_order;
        
        if ($data['slug'] !== $category->slug) {
            $slug = Str::slug($data['slug']);
            $originalSlug = $slug;
            $counter = 2;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        Cache::forget('home.main_categories');
        Cache::forget('nav.categories');

        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        if ($category->products()->count() > 0) {
            return redirect()->route('dashboard.categories.index')
                ->with('error', 'Cannot delete category because it has products. Reassign or delete products first.');
        }
        
        if ($category->children()->count() > 0) {
            return redirect()->route('dashboard.categories.index')
                ->with('error', 'Cannot delete category because it has subcategories. Reassign or delete subcategories first.');
        }
        
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();

        Cache::forget('home.main_categories');
        Cache::forget('nav.categories');
        
        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id'
        ]);

        $categories = Category::whereIn('id', $request->ids)->withCount(['products', 'children'])->get();
        
        foreach ($categories as $category) {
            if ($category->products_count > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete "' . $category->name . '" because it has active products assigned.'
                ], 422);
            }
            if ($category->children_count > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete "' . $category->name . '" because it has subcategories assigned.'
                ], 422);
            }
        }

        foreach ($categories as $category) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $category->delete();
        }

        Cache::forget('home.main_categories');
        Cache::forget('nav.categories');

        return response()->json([
            'success' => true,
            'deleted' => $categories->count(),
        ]);
    }
}
