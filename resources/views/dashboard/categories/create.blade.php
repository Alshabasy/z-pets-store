@extends('layouts.dashboard')

@section('page-title', 'Add New Category')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-10 max-w-4xl mx-auto animate-on-scroll">
    <form action="{{ route('dashboard.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="name" class="form-label">Category Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input @error('name') border-red-500 @enderror" required onkeyup="document.getElementById('slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '')" placeholder="e.g. Dog Food">
                    @error('name') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="slug" class="form-label">Slug <span class="text-red-500">*</span></label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" class="form-input @error('slug') border-red-500 @enderror" required placeholder="dog-food">
                    @error('slug') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="parent_id" class="form-label">Parent Category</label>
                <select id="parent_id" name="parent_id" class="form-input @error('parent_id') border-red-500 @enderror">
                    <option value="">-- None (Top Level) --</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>{{ $parent->name }}</option>
                    @endforeach
                </select>
                <p class="mt-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Leave as none to create a primary category.</p>
                @error('parent_id') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" rows="4" class="form-input !rounded-2xl @error('description') border-red-500 @enderror" placeholder="Describe this category for customers and SEO...">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="form-label">Category Image</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-xl hover:border-brand-green transition-colors bg-gray-50/50 group">
                        <div class="space-y-2 text-center">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-300 group-hover:text-brand-green transition-colors mb-2"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="category-upload" class="relative cursor-pointer bg-white rounded-md font-black text-brand-green hover:text-brand-green-dark focus-within:outline-none">
                                    <span>Upload a file</span>
                                    <input id="category-upload" name="image" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">PNG, JPG up to 2MB</p>
                        </div>
                    </div>
                    @error('image') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="sort_order" class="form-label">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="form-input @error('sort_order') border-red-500 @enderror">
                    <div class="mt-6">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="is_active" name="is_active" value="1" class="sr-only peer" @checked(old('is_active', true))>
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
