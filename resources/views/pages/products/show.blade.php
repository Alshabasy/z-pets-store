@extends('layouts.app')

@section('meta_title', $product->name . ' — ' . \App\Models\Setting::getValue('store_name', 'Z-Pets Store'))
@section('meta_description', $product->short_description ?? \Illuminate\Support\Str::limit(strip_tags($product->description), 150))
@section('meta_image', asset('storage/' . ($product->mainImage->path ?? 'images/placeholder.jpg')))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @php
        $breadcrumbs = [['label' => 'Home', 'url' => route('home')]];
        if($product->category->parent) {
            $breadcrumbs[] = ['label' => $product->category->parent->name, 'url' => route('category.show', $product->category->parent->slug)];
        }
        $breadcrumbs[] = ['label' => $product->category->name, 'url' => route('category.show', $product->category->slug)];
        $breadcrumbs[] = ['label' => $product->name, 'url' => null];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-12">
        <div class="flex flex-col md:flex-row">
            
            <!-- LEFT - IMAGE GALLERY -->
            <div class="w-full md:w-1/2 p-6 md:p-8 bg-gray-50 flex flex-col items-center animate-on-scroll">
                <div class="w-full aspect-[4/3] rounded-xl shadow-md overflow-hidden bg-white mb-4 relative">
                    <img id="main-image" loading="eager" src="{{ asset('storage/' . ($product->mainImage->path ?? 'images/placeholder.jpg')) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-opacity duration-300">
                    
                    @if($product->sale_price)
                        <span class="absolute top-4 left-4 bg-amber-600 text-white text-sm font-bold px-3 py-1 rounded-md shadow-sm">
                            -{{ $product->sale_badge }}%
                        </span>
                    @endif
                </div>

                @if($product->images->count() > 1)
                    <div class="flex gap-2 overflow-x-auto w-full pb-2 snap-x">
                        @foreach($product->images as $image)
                            <button onclick="swapImage('{{ asset('storage/' . $image->path) }}')" class="flex-shrink-0 w-20 h-20 rounded-md border-2 border-transparent hover:border-brand-green focus:border-brand-green overflow-hidden transition-colors snap-center focus:outline-none">
                                <img loading="lazy" src="{{ asset('storage/' . $image->path) }}" alt="{{ $product->name }} thumbnail" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- RIGHT - PRODUCT DETAILS -->
            <div class="w-full md:w-1/2 p-6 md:p-10 flex flex-col animate-on-scroll animate-delay-2">
                
                @if($product->tags->count() > 0)
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($product->tags as $tag)
                            <span class="text-xs font-bold text-white px-2 py-1 rounded-full shadow-sm" style="background-color: {{ $tag->color ?? '#2D6A2D' }}">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                
                <div class="flex items-center gap-4 mb-6">
                    @if($product->in_stock)
                        <span class="inline-flex items-center text-sm font-bold text-brand-green bg-green-50 px-2 py-1 rounded-md">
                            <i class="fa-solid fa-check mr-1.5"></i>
                            In Stock
                        </span>
                    @else
                        <span class="inline-flex items-center text-sm font-bold text-red-600 bg-red-50 px-2 py-1 rounded-md">
                            <i class="fa-solid fa-xmark mr-1.5"></i>
                            Out of Stock
                        </span>
                    @endif
                </div>

                <div class="mb-6 flex items-end gap-3">
                    @if($product->sale_price)
                        <span class="text-3xl font-bold text-brand-green">EGP {{ number_format($product->sale_price, 2) }}</span>
                        <span class="text-xl text-gray-400 line-through pb-1">EGP {{ number_format($product->price, 2) }}</span>
                    @else
                        <span class="text-3xl font-bold text-brand-green">EGP {{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                @if($product->short_description)
                    <p class="text-gray-500 text-base italic mb-8 border-l-4 border-gray-200 pl-4 leading-relaxed">{{ $product->short_description }}</p>
                @endif

                <div class="mt-auto border-t border-gray-100 pt-8 flex flex-col gap-4">
                    @if($product->in_stock)
                        <label class="form-label">Quantity</label>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="flex items-center border border-gray-200 rounded-xl bg-white overflow-hidden w-32 h-12 shadow-sm">
                                <button onclick="decrementQty()" class="w-10 h-full text-gray-500 hover:bg-gray-50 flex items-center justify-center focus:outline-none transition-colors border-r border-gray-100">
                                    <i class="fa-solid fa-minus text-xs"></i>
                                </button>
                                <input type="number" id="qty-input" value="1" min="1" max="10" class="w-full h-full text-center font-bold text-gray-900 border-none focus:ring-0 p-0 text-lg" readonly>
                                <button onclick="incrementQty()" class="w-10 h-full text-gray-500 hover:bg-gray-50 flex items-center justify-center focus:outline-none transition-colors border-l border-gray-100">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </button>
                            </div>
                            
                            <button id="add-to-cart-btn" class="btn btn-primary btn-lg flex-1">
                                <i class="fa-solid fa-cart-plus mr-2 text-xl"></i>
                                Add to Cart
                            </button>
                        </div>
                    @else
                        <div class="bg-red-50 text-red-600 p-4 rounded-xl flex items-center border border-red-100">
                            <i class="fa-solid fa-circle-exclamation text-xl mr-3"></i>
                            <span class="font-bold">Out of Stock — Check Back Soon</span>
                        </div>
                    @endif
                </div>

            </div>
        </div>
        
        <!-- Full Description -->
        <div class="border-t border-gray-100 p-6 md:p-10 bg-white">
            <h3 class="section-heading">
                <i class="fa-solid fa-circle-info text-brand-green"></i>
                Product Details
            </h3>
            <div class="prose prose-brand max-w-none text-gray-600 leading-relaxed">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>
    </div>

    <!-- RELATED PRODUCTS -->
    @if($relatedProducts->count() > 0)
        <div class="mt-16 mb-8">
            <h2 class="section-heading animate-on-scroll">You might also like</h2>
            <div class="flex overflow-x-auto pb-4 gap-4 md:gap-6 snap-x">
                @foreach($relatedProducts as $index => $related)
                        <div class="w-full max-w-[12rem] sm:max-w-[16rem] flex-shrink-0 snap-start h-auto animate-on-scroll animate-delay-{{ ($index % 4) + 1 }}">
                        <x-product-card :product="$related" />
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- TOAST NOTIFICATION -->
<div id="toast-notification" class="fixed bottom-4 right-4 bg-gray-900 text-white px-6 py-4 rounded-lg shadow-xl transform transition-all duration-300 translate-y-24 opacity-0 flex items-center z-50">
    <svg class="w-6 h-6 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <span id="toast-message" class="font-medium">Added to cart!</span>
</div>

@push('scripts')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Product",
  "name": "{{ e($product->name) }}",
  "description": "{{ e(\Illuminate\Support\Str::limit(strip_tags($product->description), 200)) }}",
  "image": "{{ $product->mainImage ? asset('storage/'.$product->mainImage->path) : '' }}",
  "offers": {
    "@@type": "Offer",
    "price": "{{ $product->sale_price ?? $product->price }}",
    "priceCurrency": "EGP",
    "availability": "{{ $product->in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
  }
}
</script>
<script>
    // Image Swap Logic
    function swapImage(src) {
        const mainImg = document.getElementById('main-image');
        mainImg.style.opacity = '0.7';
        setTimeout(() => {
            mainImg.src = src;
            mainImg.style.opacity = '1';
        }, 150);
    }

    // Quantity Logic
    const qtyInput = document.getElementById('qty-input');
    function incrementQty() {
        if(qtyInput && !qtyInput.disabled) {
            let val = parseInt(qtyInput.value) || 1;
            if(val < 10) qtyInput.value = val + 1;
        }
    }
    
    function decrementQty() {
        if(qtyInput && !qtyInput.disabled) {
            let val = parseInt(qtyInput.value) || 1;
            if(val > 1) qtyInput.value = val - 1;
        }
    }

    // Add to Cart AJAX
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    if(addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<svg class="animate-spin w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>';
            this.disabled = true;

            const qty = parseInt(document.getElementById('qty-input').value) || 1;

            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    product_id: {{ $product->id }}, 
                    qty: qty 
                })
            })
            .then(response => response.json())
            .then(data => {
                this.innerHTML = originalText;
                this.disabled = false;

                if(data.success) {
                    // Update Cart Count
                    const badge = document.getElementById('cart-count-badge');
                    if(badge) {
                        badge.textContent = data.count;
                        badge.classList.remove('hidden');
                        badge.classList.add('scale-125');
                        setTimeout(() => badge.classList.remove('scale-125'), 200);
                    }

                    // Show Toast
                    const toast = document.getElementById('toast-notification');
                    const toastMsg = document.getElementById('toast-message');
                    if(data.message) toastMsg.textContent = data.message;
                    
                    toast.classList.remove('translate-y-24', 'opacity-0');
                    toast.classList.add('translate-y-0', 'opacity-100');
                    
                    setTimeout(() => {
                        toast.classList.remove('translate-y-0', 'opacity-100');
                        toast.classList.add('translate-y-24', 'opacity-0');
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    }
</script>
@endpush
@endsection
