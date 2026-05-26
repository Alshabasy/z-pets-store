@extends('layouts.dashboard')

@section('page-title', 'Edit Product: ' . $product->name)

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-10 max-w-5xl mx-auto animate-on-scroll">
    <form action="{{ route('dashboard.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="space-y-12">
            <!-- Basic Info -->
            <section>
                <div class="dash-section-heading">
                    <i class="fa-solid fa-circle-info"></i>
                    Basic Information
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="name" class="form-label">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="form-input @error('name') border-red-500 @enderror" required onkeyup="document.getElementById('slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '')">
                        @error('name') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label for="slug" class="form-label">Slug <span class="text-red-500">*</span></label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $product->slug) }}" class="form-input @error('slug') border-red-500 @enderror" required>
                        @error('slug') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="category_id" class="form-label">Category <span class="text-red-500">*</span></label>
                        <select id="category_id" name="category_id" class="form-input @error('category_id') border-red-500 @enderror" required>
                            <option value="">Select a Category...</option>
                            @php
                                $parents = $categories->whereNull('parent_id');
                            @endphp
                            @foreach($parents as $parent)
                                <optgroup label="{{ $parent->name }}" class="font-black text-gray-900 uppercase tracking-widest text-[10px] bg-gray-50 py-2">
                                    <option value="{{ $parent->id }}" @selected(old('category_id', $product->category_id) == $parent->id)>{{ $parent->name }}</option>
                                    @foreach($categories->where('parent_id', $parent->id) as $child)
                                        <option value="{{ $child->id }}" @selected(old('category_id', $product->category_id) == $child->id)>— {{ $child->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <!-- Description -->
            <section>
                <div class="dash-section-heading">
                    <i class="fa-solid fa-align-left"></i>
                    Product Content
                </div>
                
                <div class="space-y-8">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="short_description" class="form-label !mb-0">Short Description</label>
                            <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest" id="char-counter">{{ strlen(old('short_description', $product->short_description ?? '')) }} / 500</span>
                        </div>
                        <textarea id="short_description" name="short_description" rows="3" maxlength="500" class="form-input !rounded-2xl @error('short_description') border-red-500 @enderror" onkeyup="document.getElementById('char-counter').innerText = this.value.length + ' / 500'">{{ old('short_description', $product->short_description) }}</textarea>
                        @error('short_description') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label for="description" class="form-label">Full Description <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="10" class="form-input !rounded-2xl @error('description') border-red-500 @enderror" required>{{ old('description', $product->description) }}</textarea>
                        @error('description') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <!-- Pricing & Inventory -->
            <section>
                <div class="dash-section-heading">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    Pricing & Status
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <label for="price" class="form-label">Regular Price (EGP) <span class="text-red-500">*</span></label>
                        <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" class="form-input @error('price') border-red-500 @enderror" required>
                        @error('price') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label for="sale_price" class="form-label">Sale Price (EGP)</label>
                        <input type="number" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0" class="form-input @error('sale_price') border-red-500 @enderror">
                        @error('sale_price') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50/50 p-6 rounded-2xl border-2 border-gray-50">
                    <label class="relative inline-flex items-center cursor-pointer group">
                        <input type="checkbox" id="in_stock" name="in_stock" value="1" class="sr-only peer" @checked(old('in_stock', $product->in_stock))>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-green"></div>
                        <span class="ml-3 text-xs font-black text-gray-500 uppercase tracking-widest group-hover:text-gray-900 transition-colors">In Stock</span>
                    </label>
                    
                    <label class="relative inline-flex items-center cursor-pointer group">
                        <input type="checkbox" id="is_active" name="is_active" value="1" class="sr-only peer" @checked(old('is_active', $product->is_active))>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-green"></div>
                        <span class="ml-3 text-xs font-black text-gray-500 uppercase tracking-widest group-hover:text-gray-900 transition-colors">Active (Visible)</span>
                    </label>
                    
                    <label class="relative inline-flex items-center cursor-pointer group">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1" class="sr-only peer" @checked(old('is_featured', $product->is_featured))>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-green"></div>
                        <span class="ml-3 text-xs font-black text-gray-500 uppercase tracking-widest group-hover:text-gray-900 transition-colors">Featured (Home)</span>
                    </label>
                </div>
            </section>

            <!-- Images -->
            <section>
                <div class="dash-section-heading">
                    <i class="fa-solid fa-camera"></i>
                    Product Gallery
                </div>
                
                @if($product->images->count() > 0)
                    <div class="mb-8 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                        @foreach($product->images as $image)
                            <div class="relative group rounded-2xl overflow-hidden border-2 border-gray-100 shadow-sm transition-all hover:border-brand-green/30" id="image-{{ $image->id }}">
                                <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-32 object-cover transition-transform group-hover:scale-110 duration-500">
                                @if($image->is_main)
                                    <div class="absolute top-2 left-2 bg-brand-green text-white text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded-lg shadow-lg">Main</div>
                                @endif
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                    <button type="button" onclick="deleteImage({{ $product->id }}, {{ $image->id }})" class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors shadow-xl">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                
                <div class="bg-gray-50/50 border-2 border-dashed border-gray-200 rounded-2xl p-8 transition-colors hover:border-brand-green text-center group">
                    <i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-200 group-hover:text-brand-green transition-colors mb-4 block"></i>
                    <div class="flex flex-col items-center">
                        <label for="images-upload" class="cursor-pointer">
                            <span class="btn btn-outline !py-2 !px-6 !text-xs !font-black uppercase tracking-widest">Upload New Images</span>
                            <input id="images-upload" type="file" name="images[]" multiple accept="image/*" class="sr-only">
                        </label>
                        <p class="mt-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">PNG, JPG, WEBP up to 2MB each.</p>
                    </div>
                    @error('images') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                    @error('images.*') <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                </div>
            </section>

            <!-- Tags -->
            @if($tags->count() > 0)
                <section>
                    <div class="dash-section-heading">
                        <i class="fa-solid fa-tags"></i>
                        Product Tags
                    </div>
                    
                    <div class="flex flex-wrap gap-4 bg-gray-50/30 p-6 rounded-2xl border-2 border-gray-50">
                        @php
                            $productTagIds = old('tags', $product->tags->pluck('id')->toArray());
                        @endphp
                        @foreach($tags as $tag)
                            <label class="relative inline-flex items-center cursor-pointer group">
                                <input type="checkbox" id="tag_{{ $tag->id }}" name="tags[]" value="{{ $tag->id }}" class="sr-only peer" @checked(in_array($tag->id, $productTagIds))>
                                <div class="px-4 py-2 rounded-xl border-2 border-gray-100 bg-white peer-checked:border-brand-green peer-checked:bg-green-50 transition-all flex items-center group-hover:border-gray-300">
                                    <span class="w-2 h-2 rounded-full mr-2.5 transition-transform group-hover:scale-125" style="background-color: {{ $tag->color }}"></span>
                                    <span class="text-xs font-black text-gray-400 peer-checked:text-brand-green uppercase tracking-widest transition-colors">{{ $tag->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </section>
            @endif

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

@push('scripts')
<script>
    function deleteImage(productId, imageId) {
        if(!confirm('Are you sure you want to delete this image?')) return;
        
        fetch(`/dashboard/products/${productId}/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById(`image-${imageId}`).remove();
                // Optionally reload to reflect new 'Main' label if needed
            } else {
                alert('Failed to delete image.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred.');
        });
    }
</script>
@endpush
@endsection
