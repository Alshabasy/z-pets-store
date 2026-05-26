@extends('layouts.dashboard')

@section('page-title', 'Add New Product')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8 max-w-4xl mx-auto">
    <form action="{{ route('dashboard.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="space-y-8">
            <!-- Basic Info -->
            <div>
                <div class="dash-section-heading">
                    <i class="fa-solid fa-circle-info"></i>
                    Basic Information
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="form-label">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input @error('name') border-red-500 @enderror" required onkeyup="document.getElementById('slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '')">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label for="slug" class="form-label">Slug <span class="text-red-500">*</span></label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug') }}" class="form-input @error('slug') border-red-500 @enderror" required>
                        @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="category_id" class="form-label">Category <span class="text-red-500">*</span></label>
                        <select id="category_id" name="category_id" class="form-input @error('category_id') border-red-500 @enderror" required>
                            <option value="">Select a Category...</option>
                            @php
                                $parents = $categories->whereNull('parent_id');
                            @endphp
                            @foreach($parents as $parent)
                                <optgroup label="{{ $parent->name }}">
                                    <option value="{{ $parent->id }}" @selected(old('category_id') == $parent->id)>{{ $parent->name }}</option>
                                    @foreach($categories->where('parent_id', $parent->id) as $child)
                                        <option value="{{ $child->id }}" @selected(old('category_id') == $child->id)>— {{ $child->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <div class="dash-section-heading">
                    <i class="fa-solid fa-align-left"></i>
                    Description
                </div>
                
                <div class="space-y-6">
                    <div class="form-group">
                        <div class="flex justify-between items-center mb-1.5">
                            <label for="short_description" class="form-label !mb-0">Short Description</label>
                            <span class="text-xs text-gray-400" id="char-counter">{{ strlen(old('short_description', '')) }} / 500</span>
                        </div>
                        <textarea id="short_description" name="short_description" rows="3" maxlength="500" class="form-input @error('short_description') border-red-500 @enderror" onkeyup="document.getElementById('char-counter').innerText = this.value.length + ' / 500'">{{ old('short_description') }}</textarea>
                        @error('short_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Full Description <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="8" class="form-input @error('description') border-red-500 @enderror" required>{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing & Inventory -->
            <div>
                <div class="dash-section-heading">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    Pricing & Inventory
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="price" class="form-label">Regular Price (EGP) <span class="text-red-500">*</span></label>
                        <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" class="form-input @error('price') border-red-500 @enderror" required>
                        @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="sale_price" class="form-label">Sale Price (EGP) <span class="text-xs text-gray-400 font-normal ml-1">(leave empty for no sale)</span></label>
                        <input type="number" id="sale_price" name="sale_price" value="{{ old('sale_price') }}" step="0.01" min="0" class="form-input @error('sale_price') border-red-500 @enderror">
                        @error('sale_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div>
                <div class="dash-section-heading">
                    <i class="fa-solid fa-images"></i>
                    Product Images
                </div>
                
                <div class="form-group">
                    <label class="form-label">Upload Images</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="form-input !p-2 text-gray-500 file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-brand-green hover:file:bg-green-100">
                    <p class="mt-2 text-xs text-gray-500 font-medium">You can select multiple images. The first image will be used as the main product image. Allowed formats: JPEG, JPG, PNG, WEBP. Max size: 2MB.</p>
                    @error('images') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    @error('images.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Settings -->
            <div>
                <div class="dash-section-heading">
                    <i class="fa-solid fa-sliders"></i>
                    Settings & Visibility
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="flex items-center cursor-pointer">
                        <input type="checkbox" id="in_stock" name="in_stock" value="1" class="w-5 h-5 text-brand-green border-gray-300 rounded focus:ring-brand-green cursor-pointer" @checked(old('in_stock', true))>
                        <label for="in_stock" class="ml-2 block text-sm font-bold text-gray-700 cursor-pointer">In Stock</label>
                    </div>
                    
                    <div class="flex items-center cursor-pointer">
                        <input type="checkbox" id="is_active" name="is_active" value="1" class="w-5 h-5 text-brand-green border-gray-300 rounded focus:ring-brand-green cursor-pointer" @checked(old('is_active', true))>
                        <label for="is_active" class="ml-2 block text-sm font-bold text-gray-700 cursor-pointer">Active (Visible)</label>
                    </div>
                    
                    <div class="flex items-center cursor-pointer">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1" class="w-5 h-5 text-brand-green border-gray-300 rounded focus:ring-brand-green cursor-pointer" @checked(old('is_featured'))>
                        <label for="is_featured" class="ml-2 block text-sm font-bold text-gray-700 cursor-pointer">Featured (Homepage)</label>
                    </div>
                </div>

                @if($tags->count() > 0)
                    <div class="mt-8">
                        <label class="form-label !mb-4">Tags</label>
                        <div class="flex flex-wrap gap-x-6 gap-y-3">
                            @foreach($tags as $tag)
                                <div class="flex items-center cursor-pointer">
                                    <input type="checkbox" id="tag_{{ $tag->id }}" name="tags[]" value="{{ $tag->id }}" class="w-4 h-4 text-brand-green border-gray-300 rounded focus:ring-brand-green cursor-pointer" @checked(in_array($tag->id, old('tags', [])))>
                                    <label for="tag_{{ $tag->id }}" class="ml-2 flex items-center text-sm font-medium text-gray-700 cursor-pointer">
                                        <span class="w-2.5 h-2.5 rounded-full mr-1.5" style="background-color: {{ $tag->color }}"></span>
                                        {{ $tag->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-4 border-t border-gray-200 pt-8 animate-on-scroll">
                <a href="{{ route('dashboard.products.index') }}" class="btn btn-outline btn-lg">
                    <i class="fa-solid fa-xmark mr-2"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    {{ isset($product) ? 'Update Product' : 'Create Product' }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
