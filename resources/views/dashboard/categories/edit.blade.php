@extends('layouts.dashboard')

@section('page-title', 'Edit Category: ' . $category->name)

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-10 max-w-4xl mx-auto animate-on-scroll">
    <form action="{{ route('dashboard.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="name" class="form-label">Category Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" class="form-input @error('name') border-red-500 @enderror" required onkeyup="document.getElementById('slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '')">
                    @error('name') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="slug" class="form-label">Slug <span class="text-red-500">*</span></label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" class="form-input @error('slug') border-red-500 @enderror" required>
                    @error('slug') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="parent_id" class="form-label">Parent Category</label>
                <select id="parent_id" name="parent_id" class="form-input @error('parent_id') border-red-500 @enderror">
                    <option value="">-- None (Top Level) --</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>
                    @endforeach
                </select>
                @error('parent_id') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" rows="4" class="form-input !rounded-2xl @error('description') border-red-500 @enderror">{{ old('description', $category->description) }}</textarea>
                @error('description') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="form-label">Category Image</label>
                    <div class="flex items-start gap-4">
                        @if($category->image)
                            <div class="relative w-24 h-24 rounded-xl overflow-hidden border-2 border-gray-100 flex-shrink-0 group">
                                <img src="{{ asset('storage/' . $category->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fa-solid fa-image text-white"></i>
                                </div>
                            </div>
                        @endif
                        <div class="flex-grow">
                            <input type="file" name="image" accept="image/*" class="w-full text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-black file:bg-green-50 file:text-brand-green hover:file:bg-green-100 cursor-pointer">
                            <p class="mt-2 text-[10px] text-gray-400 font-black uppercase tracking-widest">Leave empty to keep current image.</p>
                        </div>
                    </div>
                    @error('image') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="sort_order" class="form-label">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0" class="form-input @error('sort_order') border-red-500 @enderror">
                    <div class="mt-6">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="is_active" name="is_active" value="1" class="sr-only peer" @checked(old('is_active', $category->is_active))>
                            <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-green"></div>
                            <span class="ml-3 text-sm font-black text-gray-900 uppercase tracking-widest">Visible on store</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-8 border-t-2 border-gray-100">
                <a href="{{ route('dashboard.categories.index') }}" class="btn btn-outline btn-lg">
                    <i class="fa-solid fa-xmark mr-2"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Save Category
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
