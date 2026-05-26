@extends('layouts.app')
@section('title', 'Order Confirmed — Z-Pets Store')
@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-16">
  <div class="max-w-2xl w-full">

    {{-- Success Card --}}
    <div class="bg-white rounded-2xl shadow-lg border border-green-100 overflow-hidden">

      {{-- Green Header --}}
      <div class="bg-green-700 px-8 py-10 text-center">
        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center
                    mx-auto mb-4 shadow-md">
          <span class="text-green-700 text-4xl">✓</span>
        </div>
        <h1 class="text-3xl font-bold text-white">Order Placed Successfully!</h1>
        <p class="text-green-200 mt-2 text-lg">Thank you, {{ $order->customer_name }}! 🎉</p>
      </div>

      <div class="px-8 py-6">

        {{-- Order Meta --}}
        <div class="flex justify-between items-center mb-6 pb-6 border-b border-gray-100">
          <div>
            <p class="text-xs text-gray-400 uppercase tracking-wide">Order Number</p>
            <p class="text-xl font-bold text-green-700">{{ $order->order_number }}</p>
          </div>
          <div class="text-right">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Date</p>
            <p class="text-sm font-medium text-gray-700">
              {{ $order->created_at->format('d M Y, h:i A') }}
            </p>
          </div>
        </div>

        {{-- Order Items --}}
        <div class="mb-6">
          <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">
            Your Items
          </h3>
          <div class="space-y-2">
            @php
              $items = is_array($order->items_snapshot) 
                ? $order->items_snapshot 
                : json_decode($order->items_snapshot, true);
            @endphp
            @foreach($items as $item)
            <div class="flex justify-between text-sm py-2 border-b border-gray-50">
              <span class="text-gray-700">
                {{ $item['name'] }}
                <span class="text-gray-400">× {{ $item['qty'] ?? 1 }}</span>
              </span>
              <span class="font-medium text-gray-900">
                EGP {{ number_format($item['price'] * ($item['qty'] ?? 1), 2) }}
              </span>
            </div>
            @endforeach
          </div>
          <div class="flex justify-between items-center mt-4 pt-3 border-t border-gray-200">
            <span class="text-base font-bold text-gray-900">Total</span>
            <span class="text-xl font-bold text-green-700">
              EGP {{ number_format($order->total, 2) }}
            </span>
          </div>
        </div>

        {{-- Download PDF Button --}}
        @if($pdfUrl)
        <a href="{{ $pdfUrl }}" download class="btn btn-primary btn-lg w-full mb-4">
          <i class="fa-solid fa-file-pdf mr-2"></i> Download Your Receipt (PDF)
        </a>
        @endif

        {{-- Next Steps --}}
        <div class="bg-green-50 border border-green-100 rounded-xl p-5 mb-6">
          <h3 class="text-sm font-semibold text-green-800 mb-3 uppercase tracking-widest text-[10px] font-black">What happens next?</h3>
          <ul class="space-y-2 text-sm text-green-700 font-medium">
            <li class="flex items-center"><i class="fa-solid fa-circle-check mr-2 text-[10px]"></i> Your order has been received and saved.</li>
            <li class="flex items-start"><i class="fa-solid fa-phone mr-2 text-[10px] mt-1"></i> <div>We will call you at <strong>{{ $order->customer_phone }}</strong> within 24 hours to confirm delivery details.</div></li>
            <li class="flex items-center"><i class="fa-solid fa-truck mr-2 text-[10px]"></i> {{ \App\Models\Setting::getValue('delivery_note', '') }}</li>
          </ul>
        </div>

        {{-- Continue Shopping --}}
        <a href="{{ route('home') }}" class="btn btn-outline btn-lg w-full">
          <i class="fa-solid fa-bag-shopping mr-2"></i> Continue Shopping
        </a>

      </div>
    </div>

  </div>
</div>
@endsection
