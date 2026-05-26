@props(['product'])

<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden flex flex-col h-full border border-gray-100 {{ !$product->in_stock ? 'opacity-75' : '' }}">
    <a href="{{ route('products.show', $product->slug) }}" class="block relative aspect-[4/3] bg-gray-100 overflow-hidden group">
        <img loading="lazy" src="{{ asset('storage/' . ($product->mainImage->path ?? 'images/placeholder.jpg')) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105 {{ !$product->in_stock ? 'grayscale' : '' }}">

        {{-- Out of Stock overlay --}}
        @if(!$product->in_stock)
            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                <span class="bg-red-600 text-white text-sm font-bold px-3 py-1.5 rounded-lg shadow-lg tracking-wide uppercase">Out of Stock</span>
            </div>
        @endif

        <div class="absolute top-2 left-2 flex flex-col gap-2">
            @if($product->sale_price && $product->in_stock)
                <span class="bg-amber-600 text-white text-xs font-bold px-2 py-1 rounded-md shadow-sm">
                    -{{ $product->sale_badge }}%
                </span>
            @endif
        </div>
    </a>
    
    <div class="p-4 flex flex-col flex-1">
        <a href="{{ route('category.show', $product->category->slug) }}" class="text-xs font-semibold text-brand-green uppercase tracking-wider mb-1 hover:underline">
            {{ $product->category->name }}
        </a>
        
        <a href="{{ route('products.show', $product->slug) }}" class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 hover:text-brand-green transition-colors">
            {{ $product->name }}
        </a>
        
        <div class="mt-auto pt-4 flex items-center justify-between">
            <div class="flex flex-col">
                @if($product->sale_price)
                    <span class="text-sm text-gray-500 line-through">EGP {{ number_format($product->price, 2) }}</span>
                    <span class="text-lg font-bold text-brand-green">EGP {{ number_format($product->sale_price, 2) }}</span>
                @else
                    <span class="text-lg font-bold text-gray-900">EGP {{ number_format($product->price, 2) }}</span>
                @endif
            </div>
            
            <button 
                onclick="addToCart({{ $product->id }})" 
                class="w-10 h-10 bg-brand-green hover:bg-brand-green-dark text-white rounded-xl flex items-center justify-center transition-all hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 {{ !$product->in_stock ? 'opacity-50 cursor-not-allowed' : '' }}"
                {{ !$product->in_stock ? 'disabled' : '' }}
                title="Add to Cart"
            >
                <i class="fa-solid fa-cart-plus"></i>
            </button>
        </div>
    </div>
</div>

@once
@stack('scripts')
<script>
    if (typeof window.addToCart !== 'function') {
        window.addToCart = function(productId) {
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId, qty: 1 })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    let badge = document.getElementById('cart-count-badge');
                    if(badge) {
                        badge.textContent = data.count;
                        badge.classList.remove('hidden');
                        
                        // Small animation
                        badge.classList.add('scale-125');
                        setTimeout(() => badge.classList.remove('scale-125'), 200);
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        };
    }
</script>
@endonce
