@extends('layouts.app')

@section('meta_title', 'Search: ' . request('q', '') . ' — ' . \App\Models\Setting::getValue('store_name', 'Z-Pets Store'))
@section('meta_description', 'Search results for pet products at Z-Pets Store.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-10 text-center max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Search Z-Pets Store</h1>
        
        <form action="{{ route('search') }}" method="GET" class="relative">
            <input type="text" name="q" value="{{ $query }}" placeholder="Search for products, categories..." class="w-full pl-12 pr-4 py-4 rounded-full border-2 border-brand-green/20 focus:border-brand-green focus:ring-0 text-lg shadow-sm" minlength="2" required autofocus>
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button type="submit" class="absolute inset-y-2 right-2 bg-brand-green hover:bg-brand-green-dark text-white px-6 py-2 rounded-full font-semibold transition-colors">
                Search
            </button>
        </form>
    </div>

    @if($query && strlen($query) >= 2)
        <div class="mb-6 flex justify-between items-center border-b border-gray-200 pb-4">
            <h2 class="text-xl font-medium text-gray-700">
                Found <span class="font-bold text-brand-green">{{ $count }}</span> results for "<span class="font-bold text-gray-900">{{ $query }}</span>"
            </h2>
        </div>

        @if($count > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm p-12 text-center mb-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No results found for "{{ $query }}"</h3>
                <p class="text-gray-500 mb-6">Check your spelling or try using different keywords.</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-white border-2 border-brand-green text-brand-green hover:bg-brand-green hover:text-white font-bold py-2 px-6 rounded-lg transition-colors">
                    Browse all products
                </a>
            </div>
        @endif
    @else
        @if(!$query)
            <div class="text-center py-8">
                <p class="text-gray-500 text-lg">Enter a search term above to find what you're looking for.</p>
            </div>
        @elseif(strlen($query) < 2)
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-8 text-amber-800 text-center">
                Please enter at least 2 characters to search.
            </div>
        @endif
    @endif

    <!-- Suggested Categories (shown when no results or no query) -->
    @if(!$query || $count == 0)
        <div class="mt-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">Popular Categories</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($categories as $category)
                    <x-category-card :category="$category" />
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
