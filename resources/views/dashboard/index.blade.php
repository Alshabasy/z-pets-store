@extends('layouts.dashboard')

@section('page-title', 'Overview')

@section('content')
    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        
        <!-- Total Products -->
        <div class="bg-white rounded-xl shadow-sm border-2 border-gray-50 p-6 flex items-center hover:border-brand-green/20 transition-all group animate-on-scroll">
            <div class="p-4 rounded-xl bg-green-50 text-brand-green mr-5 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-box-open text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Products</p>
                <p class="text-3xl font-black text-gray-900 leading-none">{{ $stats['total_products'] }}</p>
            </div>
        </div>

        <!-- Total Categories -->
        <div class="bg-white rounded-xl shadow-sm border-2 border-gray-50 p-6 flex items-center hover:border-blue-500/20 transition-all group animate-on-scroll" style="animation-delay: 0.1s">
            <div class="p-4 rounded-xl bg-blue-50 text-blue-500 mr-5 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-tags text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Categories</p>
                <p class="text-3xl font-black text-gray-900 leading-none">{{ $stats['total_categories'] }}</p>
            </div>
        </div>

        <!-- New Orders -->
        <div class="bg-white rounded-xl shadow-sm border-2 border-gray-50 p-6 flex items-center hover:border-amber-500/20 transition-all group animate-on-scroll" style="animation-delay: 0.2s">
            <div class="p-4 rounded-xl {{ $stats['new_orders'] > 0 ? 'bg-amber-100 text-amber-600' : 'bg-gray-50 text-gray-400' }} mr-5 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-bell text-2xl {{ $stats['new_orders'] > 0 ? 'animate-bounce' : '' }}"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">New Orders</p>
                <p class="text-3xl font-black {{ $stats['new_orders'] > 0 ? 'text-amber-600' : 'text-gray-900' }} leading-none">{{ $stats['new_orders'] }}</p>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-xl shadow-sm border-2 border-gray-50 p-6 flex items-center hover:border-purple-500/20 transition-all group animate-on-scroll" style="animation-delay: 0.3s">
            <div class="p-4 rounded-xl bg-purple-50 text-purple-500 mr-5 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-bag-shopping text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Lifetime Orders</p>
                <p class="text-3xl font-black text-gray-900 leading-none">{{ $stats['total_orders'] }}</p>
            </div>
        </div>

    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-on-scroll" style="animation-delay: 0.4s">
        <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gray-50/50">
            <div class="dash-section-heading !mb-0">
                <i class="fa-solid fa-clock-rotate-left"></i>
                Recent Orders
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Items</th>
                        <th class="px-6 py-4 text-right">Total</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-gray-900">
                                #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $order->customer_name }}</div>
                                <div class="text-[11px] font-bold text-gray-400">{{ $order->customer_phone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 px-2 py-0.5 rounded text-[10px] font-black text-gray-500">
                                    {{ count($order->items_snapshot ?? []) }} {{ str('item')->plural(count($order->items_snapshot ?? [])) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-brand-green text-right">
                                EGP {{ number_format($order->total, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-[10px] uppercase font-black px-3 py-1 rounded-full
                                    {{ $order->status === 'new' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $order->status === 'seen' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $order->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                                ">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 font-medium">
                                {{ $order->created_at->format('M d, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('dashboard.orders.index') }}#order-{{ $order->id }}" class="btn btn-outline !py-1.5 !px-4 text-[10px] font-black uppercase tracking-widest">
                                    <i class="fa-solid fa-eye mr-1.5"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-receipt text-2xl text-gray-200"></i>
                                    </div>
                                    <h3 class="text-base font-black text-gray-900 uppercase tracking-widest">No orders yet</h3>
                                    <p class="text-gray-400 text-sm font-medium">Orders from customers will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-center">
            <a href="{{ route('dashboard.orders.index') }}" class="btn btn-outline !py-2.5 !px-8 text-[11px] font-black uppercase tracking-[0.1em]">
                <i class="fa-solid fa-list mr-2"></i> View All Orders
            </a>
        </div>
    </div>
@endsection
