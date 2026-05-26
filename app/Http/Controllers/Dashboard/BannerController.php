<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->get();
        return view('dashboard.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'      => 'nullable|string|max:255',
            'image'      => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
            'link_url'   => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = isset($data['sort_order'])
            ? (int) $data['sort_order'] : 0;
        
        $data['image'] = $request->file('image')->store('banners', 'public');

        Banner::create($data);

        Cache::forget('home.banners');

        return redirect()->route('dashboard.banners.index')
            ->with('success', 'Banner created successfully.');
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        
        $data = $request->validate([
            'title'      => 'nullable|string|max:255',
            'image'      => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'link_url'   => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = isset($data['sort_order'])
            ? (int) $data['sort_order'] : $banner->sort_order;

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        Cache::forget('home.banners');

        return redirect()->route('dashboard.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        
        $banner->delete();

        Cache::forget('home.banners');
        
        return redirect()->route('dashboard.banners.index')
            ->with('success', 'Banner deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:banners,id'
        ]);

        $banners = Banner::whereIn('id', $request->ids)->get();
        foreach ($banners as $banner) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->delete();
        }

        Cache::forget('home.banners');

        return response()->json([
            'success' => true,
            'deleted' => $banners->count(),
        ]);
    }
}
