@extends('layouts.dashboard')

@section('page-title', 'Products')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-on-scroll">
    <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/30">
        <form action="{{ route('dashboard.products.index') }}" method="GET" class="w-full sm:w-1/2 md:w-1/3">
            <div class="relative group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="form-input !pl-10 group-focus-within:border-brand-green transition-all">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-300 group-focus-within:text-brand-green transition-colors"></i>
                </div>
            </div>
        </form>
        <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus mr-2"></i>
            Add Product
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    <th class="px-6 py-4 w-10">
                        <input type="checkbox" id="select-all-products" class="form-checkbox rounded text-brand-green focus:ring-brand-green cursor-pointer">
                    </th>
                    <th class="px-6 py-4">Product Details</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4">Inventory</th>
                    <th class="px-6 py-4">Visibility</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 transition-colors" data-row-id="{{ $product->id }}">
                        <td class="px-6 py-4 whitespace-nowrap w-10">
                            <input type="checkbox" value="{{ $product->id }}" class="product-checkbox form-checkbox rounded text-brand-green focus:ring-brand-green cursor-pointer">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-14 h-14 flex-shrink-0 bg-gray-100 rounded-xl border-2 border-gray-50 overflow-hidden shadow-sm group">
                                    @if($product->mainImage)
                                        <img src="{{ asset('storage/' . $product->mainImage->path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-200">
                                            <i class="fa-solid fa-image text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-black text-gray-900 leading-tight mb-0.5">{{ $product->name }}</div>
                                    <div class="text-[10px] text-gray-400 font-black uppercase tracking-widest">SKU: #{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs font-black text-gray-500 uppercase tracking-widest bg-gray-100 px-2.5 py-1 rounded-lg">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-black text-brand-green">EGP {{ number_format($product->price, 2) }}</div>
                            @if($product->sale_price)
                                <div class="text-[9px] text-amber-500 font-black uppercase tracking-widest line-through opacity-60">Was: EGP {{ number_format($product->price, 2) }}</div>
                                <div class="text-[10px] text-amber-600 font-black uppercase tracking-widest">Sale: EGP {{ number_format($product->sale_price, 2) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->in_stock)
                                <span class="px-3 py-1 inline-flex text-[10px] font-black uppercase tracking-widest rounded-full bg-green-100 text-green-700">
                                    <i class="fa-solid fa-check-circle mr-1.5 mt-0.5"></i>
                                    In Stock
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-[10px] font-black uppercase tracking-widest rounded-full bg-red-100 text-red-700">
                                    <i class="fa-solid fa-circle-xmark mr-1.5 mt-0.5"></i>
                                    Out of Stock
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-1.5">
                                @if($product->is_active)
                                    <span class="px-2 py-0.5 inline-flex text-[9px] font-black uppercase tracking-widest rounded bg-blue-50 text-blue-600 border border-blue-100">Visible</span>
                                @else
                                    <span class="px-2 py-0.5 inline-flex text-[9px] font-black uppercase tracking-widest rounded bg-gray-50 text-gray-400 border border-gray-100">Hidden</span>
                                @endif
                                @if($product->is_featured)
                                    <span class="px-2 py-0.5 inline-flex text-[9px] font-black uppercase tracking-widest rounded bg-purple-50 text-purple-600 border border-purple-100">
                                        <i class="fa-solid fa-star mr-1"></i>
                                        Featured
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('dashboard.products.edit', $product->id) }}" class="btn btn-outline btn-sm">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </a>
                                <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete {{ $product->name }}? This cannot be undone.');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                                    <i class="fa-solid fa-box-open text-3xl text-gray-200"></i>
                                </div>
                                <h3 class="text-xl font-black text-gray-900 mb-2">No products found</h3>
                                <p class="text-gray-400 max-w-xs mx-auto font-medium">Start adding products to your store to see them here.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($products->hasPages())
        <div class="p-6 border-t border-gray-200 bg-gray-50/30">
            {{ $products->links() }}
        </div>
    @endif
</div>

{{-- Bulk Action Bar --}}
<div id="products-bulk-bar"
     class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50
            flex items-center gap-4
            bg-gray-900 text-white px-6 py-3.5 rounded-2xl shadow-2xl
            transition-all duration-300 translate-y-20 opacity-0">
    <span class="text-sm font-semibold whitespace-nowrap">
        <span id="products-bulk-count">0</span> selected
    </span>
    <div class="w-px h-5 bg-white/20"></div>
    <button id="products-bulk-delete"
            class="flex items-center gap-2 text-sm font-bold text-red-400 hover:text-red-300 transition-colors whitespace-nowrap">
        <i class="fa-solid fa-trash"></i> Delete Selected
    </button>
    <div class="w-px h-5 bg-white/20"></div>
    <button onclick="document.getElementById('select-all-products').checked = false; document.getElementById('select-all-products').dispatchEvent(new Event('change'));"
            class="text-sm font-medium text-gray-400 hover:text-white transition-colors whitespace-nowrap">
        Cancel
    </button>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        initBulkSelect({
            selectAllId:        'select-all-products',
            checkboxClass:      'product-checkbox',
            bulkBarId:          'products-bulk-bar',
            countId:            'products-bulk-count',
            deleteButtonId:     'products-bulk-delete',
            bulkDestroyUrl:     '{{ route("dashboard.products.bulk-destroy") }}',
            successRedirectUrl: '{{ route("dashboard.products.index") }}',
            entityName:         'products',
        });
    });
</script>
@endpush
@endsection
