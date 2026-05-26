@extends('layouts.app')
@section('meta_title', 'Checkout — ' . \App\Models\Setting::getValue('store_name', 'Z-Pets Store'))
@section('meta_description', 'Complete your order and send it via WhatsApp at Z-Pets Store.')
@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
  <h1 class="text-3xl font-bold text-gray-900 mb-8 animate-on-scroll">Complete Your Order</h1>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    {{-- LEFT: Customer Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 animate-on-scroll">
      <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
        <i class="fa-solid fa-user-pen mr-2.5 text-brand-green"></i>
        Your Details
      </h2>
      <form method="POST" action="{{ route('checkout.store') }}">
        @csrf

        <div class="form-group">
          <label class="form-label">
            Full Name <span class="text-red-500">*</span>
          </label>
          <input type="text" name="customer_name"
                 value="{{ old('customer_name') }}"
                 placeholder="e.g. Ahmed Mohamed"
                 class="form-input @error('customer_name') border-red-400 @enderror">
          @error('customer_name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="form-group">
          <label class="form-label">
            Phone Number <span class="text-red-500">*</span>
          </label>
          <input type="tel" name="customer_phone"
                 value="{{ old('customer_phone') }}"
                 placeholder="+201XXXXXXXXX"
                 class="form-input @error('customer_phone') border-red-400 @enderror">
          @error('customer_phone')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="form-group">
          <label class="form-label">
            Notes <span class="text-gray-400 font-normal">(optional)</span>
          </label>
          <textarea name="notes" rows="3"
                    placeholder="Any special instructions for your order?"
                    class="form-input">{{ old('notes') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-full">
          <i class="fa-brands fa-whatsapp mr-2 text-xl"></i>
          Send Order via WhatsApp
        </button>

        <p class="text-center text-xs text-gray-400 mt-4 flex items-center justify-center gap-1.5">
          <i class="fa-solid fa-file-pdf"></i>
          Your receipt PDF will be ready after placing the order.
        </p>
      </form>
    </div>

    {{-- RIGHT: Order Summary --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 animate-on-scroll animate-delay-2">
      <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
        <i class="fa-solid fa-receipt mr-2.5 text-brand-green"></i>
        Order Summary
      </h2>
      <div class="space-y-3 mb-6">
        @foreach($items as $item)
        <div class="flex justify-between items-center py-2 border-b border-gray-50">
          <div>
            <p class="text-sm font-bold text-gray-900">{{ $item['name'] }}</p>
            <p class="text-xs text-gray-400">Qty: {{ $item['quantity'] }}</p>
          </div>
          <p class="text-sm font-bold text-gray-900">
            EGP {{ number_format($item['price'] * $item['quantity'], 2) }}
          </p>
        </div>
        @endforeach
      </div>
      <div class="flex justify-between items-center pt-2">
        <span class="text-lg font-bold text-gray-900 uppercase tracking-tight">Total</span>
        <span class="text-2xl font-black text-brand-green">
          EGP {{ number_format($total, 2) }}
        </span>
      </div>
      
      @if($deliveryNote = \App\Models\Setting::getValue('delivery_note', ''))
        <div class="mt-8 pt-6 border-t border-gray-50 text-center">
            <p class="text-xs font-medium text-gray-500 leading-relaxed italic">
                <i class="fa-solid fa-truck text-brand-green/40 mr-1"></i>
                {{ $deliveryNote }}
            </p>
        </div>
      @endif
    </div>

  </div>
</div>
@endsection
