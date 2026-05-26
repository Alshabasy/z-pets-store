@extends('layouts.dashboard')

@section('page-title', 'Categories')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-on-scroll">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gray-50/30">
        <div class="dash-section-heading !mb-0 flex-1 min-w-0">
            <i class="fa-solid fa-folder-tree"></i>
            Store Categories
        </div>
        <a href="{{ route('dashboard.categories.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus mr-2"></i>
            Add Category
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    <th class="px-6 py-4 w-10">
                        <input type="checkbox" id="select-all-categories" class="form-checkbox rounded text-brand-green focus:ring-brand-green cursor-pointer">
                    </th>
                    <th class="px-6 py-4">Image</th>
                    <th class="px-6 py-4">Name & Slug</th>
                    <th class="px-6 py-4">Parent</th>
                    <th class="px-6 py-4 text-center">Subs</th>
                    <th class="px-6 py-4 text-center">Products</th>
                    <th class="px-6 py-4">Order</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $category)
                    <tr class="hover:bg-gray-50/50 transition-colors" data-row-id="{{ $category->id }}">
                        <td class="px-6 py-4 whitespace-nowrap w-10">
                            <input type="checkbox" value="{{ $category->id }}" class="category-checkbox form-checkbox rounded text-brand-green focus:ring-brand-green cursor-pointer">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-14 h-14 flex-shrink-0 bg-gray-100 rounded-xl border-2 border-gray-50 overflow-hidden shadow-sm group">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-200">
                                        <i class="fa-solid fa-folder text-xl"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-black text-gray-900 leading-tight mb-0.5">{{ $category->name }}</div>
                            <div class="text-[10px] text-gray-400 font-black uppercase tracking-widest">/{{ $category->slug }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($category->parent)
                                <span class="text-[10px] font-black text-blue-500 uppercase tracking-widest bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100">
                                    {{ $category->parent->name }}
                                </span>
                            @else
                                <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Top Level</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-xs font-black text-gray-900">{{ $category->subcategories_count }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-xs font-black text-gray-900">{{ $category->products_count }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('dashboard.categories.update', $category->id) }}" method="POST" class="flex items-center w-20">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $category->name }}">
                                <input type="hidden" name="slug" value="{{ $category->slug }}">
                                <input type="hidden" name="is_active" value="{{ $category->is_active ? 1 : 0 }}">
                                <input type="number" name="sort_order" value="{{ $category->sort_order }}" class="form-input !py-1 !px-2 !text-[11px] !font-black !text-center" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($category->is_active)
                                <span class="px-3 py-1 inline-flex text-[10px] font-black uppercase tracking-widest rounded-full bg-green-100 text-green-700">Active</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-[10px] font-black uppercase tracking-widest rounded-full bg-gray-100 text-gray-500">Hidden</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('dashboard.categories.edit', $category->id) }}" class="btn btn-outline btn-sm">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </a>
                                @if($category->products_count > 0 || $category->subcategories_count > 0)
                                    <button type="button" onclick="alert('Cannot delete category because it has products or subcategories. Reassign them first.');" class="btn btn-outline btn-sm border-gray-100 text-gray-300 cursor-not-allowed" title="Has products or subcategories">
                                        <i class="fa-solid fa-trash mr-1"></i> Delete
                                    </button>
                                @else
                                    <form action="{{ route('dashboard.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa-solid fa-trash mr-1"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                                    <i class="fa-solid fa-folder-open text-3xl text-gray-200"></i>
                                </div>
                                <h3 class="text-xl font-black text-gray-900 mb-2">No categories found</h3>
                                <p class="text-gray-400 max-w-xs mx-auto font-medium">Group your products by creating categories here.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($categories->hasPages())
        <div class="p-6 border-t border-gray-200 bg-gray-50/30">
            {{ $categories->links() }}
        </div>
    @endif
</div>

{{-- Bulk Action Bar --}}
<div id="categories-bulk-bar"
     class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50
            flex items-center gap-4
            bg-gray-900 text-white px-6 py-3.5 rounded-2xl shadow-2xl
            transition-all duration-300 translate-y-20 opacity-0">
    <span class="text-sm font-semibold whitespace-nowrap">
        <span id="categories-bulk-count">0</span> selected
    </span>
    <div class="w-px h-5 bg-white/20"></div>
    <button id="categories-bulk-delete"
            class="flex items-center gap-2 text-sm font-bold text-red-400 hover:text-red-300 transition-colors whitespace-nowrap">
        <i class="fa-solid fa-trash"></i> Delete Selected
    </button>
    <div class="w-px h-5 bg-white/20"></div>
    <button onclick="document.getElementById('select-all-categories').checked = false; document.getElementById('select-all-categories').dispatchEvent(new Event('change'));"
            class="text-sm font-medium text-gray-400 hover:text-white transition-colors whitespace-nowrap">
        Cancel
    </button>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        initBulkSelect({
            selectAllId:        'select-all-categories',
            checkboxClass:      'category-checkbox',
            bulkBarId:          'categories-bulk-bar',
            countId:            'categories-bulk-count',
            deleteButtonId:     'categories-bulk-delete',
            bulkDestroyUrl:     '{{ route("dashboard.categories.bulk-destroy") }}',
            successRedirectUrl: '{{ route("dashboard.categories.index") }}',
            entityName:         'categories',
        });
    });
</script>
@endpush
@endsection
