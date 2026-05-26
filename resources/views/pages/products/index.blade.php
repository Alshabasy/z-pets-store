@extends('layouts.app')

@section('meta_title', 'All Products — ' . \App\Models\Setting::getValue('store_name', 'Z-Pets Store'))
@section('meta_description', 'Browse all pet products at Z-Pets Store — food, toys, accessories and more.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'All Products', 'url' => null]
    ]" />

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 animate-on-scroll">
        <h1 class="section-heading !mb-0 flex-1 min-w-0">All Products</h1>
        <button id="mobile-filter-toggle" class="lg:hidden text-brand-green font-semibold flex items-center">
            <i class="fa-solid fa-filter mr-1.5"></i>
            Filters
        </button>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <aside id="filter-sidebar" class="w-full lg:w-1/4 hidden lg:block bg-white p-6 rounded-xl shadow-sm h-fit">
            <form action="{{ route('products.index') }}" method="GET" id="filter-form">
                <!-- Preserve sorting if present -->
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Categories</h3>
                <ul class="space-y-2 mb-6">
                    @foreach($allCategories as $cat)
                        <li><a href="{{ route('category.show', $cat->slug) }}" class="text-gray-600 hover:text-brand-green transition-colors text-sm font-medium">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>

                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Filters</h3>
                
                <div class="form-group">
                    <label class="form-label">Price Range (EGP)</label>
                    <div class="flex flex-wrap items-center gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="form-input text-sm">
                        <span class="text-gray-500">-</span>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="form-input text-sm">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }} class="rounded border-gray-300 text-brand-green shadow-sm focus:border-brand-green focus:ring-brand-green focus:ring-opacity-50">
                        <span class="ml-2 text-sm font-medium text-gray-700">In Stock Only</span>
                    </label>
                </div>

                @if($tags->count() > 0)
                <div class="form-group">
                    <label class="form-label">Tags</label>
                    <div class="space-y-2">
                        @foreach($tags as $tag)
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="tag" value="{{ $tag->slug }}" {{ request('tag') == $tag->slug ? 'checked' : '' }} class="border-gray-300 text-brand-green shadow-sm focus:border-brand-green focus:ring-brand-green focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <button type="submit" class="btn btn-primary w-full">
                    Apply Filters
                </button>
                <a href="{{ route('products.index') }}" class="block w-full text-center text-gray-500 hover:text-gray-700 text-xs mt-3 font-medium">Clear Filters</a>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="w-full lg:w-3/4">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 bg-white p-4 rounded-xl shadow-sm animate-on-scroll">
                <span class="text-gray-600 text-sm mb-4 sm:mb-0">Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products</span>
                
                <div class="flex items-center">
                    <label for="sort" class="text-sm font-medium text-gray-700 mr-2">Sort By:</label>
                    <select id="sort" class="form-input !py-1.5 !px-3 !w-auto !inline-block text-sm" onchange="window.location.href=this.value">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort') == 'newest' || !request('sort') ? 'selected' : '' }}>Newest</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_asc']) }}" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                    </select>
                </div>
            </div>

            @if($products->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                    @foreach($products as $product)
                        <div class="animate-on-scroll">
                            <x-product-card :product="$product" />
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm p-12 text-center animate-on-scroll">
                    <i class="fa-solid fa-box-open text-5xl text-gray-200 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No products found</h3>
                    <p class="text-gray-500">Try adjusting your filters or search criteria.</p>
                </div>
            @endif
        </main>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('mobile-filter-toggle').addEventListener('click', function() {
        var sidebar = document.getElementById('filter-sidebar');
        sidebar.classList.toggle('hidden');
    });
</script>
@endpush
@endsection
