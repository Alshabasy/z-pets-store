@extends('layouts.app')

@section('meta_title', 'My Cart — ' . \App\Models\Setting::getValue('store_name', 'Z-Pets Store'))
@section('meta_description', 'Review your cart and proceed to checkout at Z-Pets Store.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-bold text-gray-900 mb-8 animate-on-scroll">Your Cart</h1>

    @if(count($items) > 0)
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left: Cart Items -->
            <div class="w-full lg:w-2/3 animate-on-scroll">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-sm font-bold text-gray-700 uppercase tracking-wider">
                                <th class="p-4 sm:p-6">Product</th>
                                <th class="p-4 sm:p-6 hidden sm:table-cell">Price</th>
                                <th class="p-4 sm:p-6 text-center">Quantity</th>
                                <th class="p-4 sm:p-6 hidden md:table-cell">Total</th>
                                <th class="p-4 sm:p-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($items as $slug => $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-4 sm:p-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-20 h-20 flex-shrink-0 bg-white rounded-xl border border-gray-100 overflow-hidden hidden sm:block shadow-sm">
                                                <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <a href="{{ route('products.show', $slug) }}" class="font-bold text-gray-900 hover:text-brand-green transition-colors text-base sm:text-lg block">{{ $item['name'] }}</a>
                                                <span class="text-sm text-gray-500 hidden sm:block">EGP {{ number_format($item['price'], 2) }} each</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 sm:p-6 hidden sm:table-cell">
                                        <span class="font-bold text-gray-900">EGP {{ number_format($item['price'], 2) }}</span>
                                    </td>
                                    <td class="p-4 sm:p-6">
                                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center border border-gray-200 rounded-xl bg-white overflow-hidden w-28 sm:w-32 h-10 mx-auto shadow-sm">
                                            @csrf
                                            <input type="hidden" name="slug" value="{{ $slug }}">
                                            <button type="submit" onclick="this.nextElementSibling.value = Math.max(1, parseInt(this.nextElementSibling.value) - 1);" class="w-8 sm:w-10 h-full text-gray-500 hover:bg-gray-50 flex items-center justify-center transition-colors border-r border-gray-50">
                                                <i class="fa-solid fa-minus text-xs"></i>
                                            </button>
                                            <input type="number" name="qty" value="{{ $item['quantity'] }}" min="1" max="10" class="w-full h-full text-center font-bold text-gray-900 border-none focus:ring-0 p-0 text-sm sm:text-base" onchange="this.form.submit()">
                                            <button type="submit" onclick="this.previousElementSibling.value = Math.min(10, parseInt(this.previousElementSibling.value) + 1);" class="w-8 sm:w-10 h-full text-gray-500 hover:bg-gray-50 flex items-center justify-center transition-colors border-l border-gray-50">
                                                <i class="fa-solid fa-plus text-xs"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="p-4 sm:p-6 hidden md:table-cell font-black text-brand-green">
                                        EGP {{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </td>
                                    <td class="p-4 sm:p-6 text-right">
                                        <form action="{{ route('cart.remove') }}" method="POST" onsubmit="return confirm('Remove {{ addslashes($item['name']) }} from cart?');">
                                            @csrf
                                            <input type="hidden" name="slug" value="{{ $slug }}">
                                            <button type="submit" class="text-red-500 hover:text-white p-2.5 bg-red-50 hover:bg-red-600 rounded-xl transition-all" title="Remove">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    <div class="p-4 sm:p-6 bg-gray-50/50 border-t border-gray-100 flex justify-between items-center">
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your cart?');">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-sm text-red-600 border-red-100 hover:bg-red-50">
                                <i class="fa-solid fa-trash-can mr-2"></i> Clear Cart
                            </button>
                        </form>
                        <a href="{{ route('products.index') }}" class="text-sm font-black text-brand-green hover:text-brand-green-dark transition-colors flex items-center uppercase tracking-widest">
                            <i class="fa-solid fa-arrow-left mr-2"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="w-full lg:w-1/3 animate-on-scroll animate-delay-2">
                <div class="bg-white rounded-2xl shadow-sm p-6 lg:p-8 sticky top-24 border border-gray-50">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-50 pb-4">Order Summary</h2>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center text-gray-500">
                            <span class="font-medium text-sm">Subtotal</span>
                            <span class="font-bold text-gray-900">EGP {{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-500">
                            <span class="font-medium text-sm">Delivery</span>
                            <span class="font-bold text-gray-900 text-right">Calculated via WhatsApp</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 pt-4 mb-8">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span class="text-3xl font-black text-brand-green tracking-tight">EGP {{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    @if($deliveryNote = \App\Models\Setting::getValue('delivery_note'))
                        <div class="bg-brand-green/5 text-brand-green-dark p-4 rounded-xl text-sm mb-6 flex items-start border border-brand-green/10">
                            <i class="fa-solid fa-circle-info mr-3 flex-shrink-0 mt-0.5 text-brand-green"></i>
                            <p class="font-medium leading-relaxed">{{ $deliveryNote }}</p>
                        </div>
                    @endif

                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg w-full">
                        <i class="fa-solid fa-cart-shopping mr-2"></i>
                        Proceed to Order
                        <i class="fa-solid fa-arrow-right ml-auto"></i>
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-3xl shadow-sm p-12 lg:p-24 text-center max-w-2xl mx-auto mt-8 animate-on-scroll">
            <div class="w-24 h-24 bg-brand-green/10 rounded-full flex items-center justify-center mx-auto mb-8">
                <i class="fa-solid fa-cart-shopping text-4xl text-brand-green"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Your cart is empty</h2>
            <p class="text-lg text-gray-500 mb-8 max-w-sm mx-auto">Looks like you haven't added anything yet. Explore our products and treat your pet today!</p>
            <a href="{{ route('home') }}" class="btn btn-primary px-12 py-4">
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection
