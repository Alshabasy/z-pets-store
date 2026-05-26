@extends('layouts.dashboard')

@section('page-title', 'Orders')

@section('content')
<div class="space-y-6">
    <!-- Filter Tabs -->
    <div class="bg-white p-3 sm:p-4 rounded-xl shadow-sm border border-gray-200 flex flex-wrap gap-2 overflow-x-auto">
        <a href="{{ route('dashboard.orders.index') }}" class="px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition-all {{ !request('status') ? 'bg-brand-green text-white shadow-md' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
            <i class="fa-solid fa-list-ul mr-2"></i>
            All Orders <span class="ml-1 opacity-60" data-count-all>({{ $counts['all'] }})</span>
        </a>
        <a href="{{ route('dashboard.orders.index', ['status' => 'new']) }}" class="px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition-all flex items-center {{ request('status') === 'new' ? 'bg-brand-green text-white shadow-md' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
            <i class="fa-solid fa-star mr-2 {{ request('status') === 'new' ? 'text-amber-300' : 'text-amber-500' }}"></i>
            New 
            @if($counts['new'] > 0)
                <span class="ml-2 bg-amber-500 text-white text-[10px] px-2 py-0.5 rounded-full font-black">{{ $counts['new'] }}</span>
            @endif
        </a>
        <a href="{{ route('dashboard.orders.index', ['status' => 'seen']) }}" class="px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition-all {{ request('status') === 'seen' ? 'bg-brand-green text-white shadow-md' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
            <i class="fa-solid fa-eye mr-2"></i>
            Seen <span class="ml-1 opacity-60">({{ $counts['seen'] }})</span>
        </a>
        <a href="{{ route('dashboard.orders.index', ['status' => 'confirmed']) }}" class="px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition-all {{ request('status') === 'confirmed' ? 'bg-brand-green text-white shadow-md' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
            <i class="fa-solid fa-check-double mr-2"></i>
            Confirmed <span class="ml-1 opacity-60">({{ $counts['confirmed'] }})</span>
        </a>
        <a href="{{ route('dashboard.orders.index', ['status' => 'completed']) }}" class="px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition-all {{ request('status') === 'completed' ? 'bg-brand-green text-white shadow-md' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
            <i class="fa-solid fa-circle-check mr-2"></i>
            Completed <span class="ml-1 opacity-60">({{ $counts['completed'] }})</span>
        </a>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="px-6 py-4 w-10">
                            <input type="checkbox" id="select-all" class="form-checkbox rounded text-brand-green focus:ring-brand-green cursor-pointer">
                        </th>
                        <th class="px-6 py-4">#ID</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Items</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        @php
                            $items = is_array($order->items_snapshot) ? $order->items_snapshot : (json_decode($order->items_snapshot, true) ?? []);
                            $itemCount = count($items);
                        @endphp
                        <!-- Main Row -->
                        <tr class="hover:bg-gray-50/50 transition-colors group"
                            data-main-row="{{ $order->id }}">
                            <td class="px-6 py-4 whitespace-nowrap w-10">
                                <input type="checkbox" value="{{ $order->id }}" class="order-checkbox form-checkbox rounded text-brand-green focus:ring-brand-green cursor-pointer">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-gray-900">
                                #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900 leading-tight">{{ $order->customer_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-600">
                                        {{ $order->customer_phone }}
                                    </span>
                                    <button type="button"
                                            title="Copy phone number"
                                            class="copy-phone-btn w-6 h-6 flex items-center justify-center text-gray-300
                                                   hover:text-brand-green transition-colors rounded"
                                            data-phone="{{ e($order->customer_phone) }}">
                                        <i class="fa-regular fa-copy text-xs"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    @foreach(array_slice($items, 0, 2) as $lineItem)
                                    <div class="text-xs text-gray-600 leading-tight">
                                        <span class="font-semibold text-gray-800">
                                            {{ $lineItem['qty'] ?? 1 }}×
                                        </span>
                                        {{ \Illuminate\Support\Str::limit($lineItem['name'] ?? '', 22) }}
                                    </div>
                                    @endforeach
                                    @if($itemCount > 2)
                                    <div class="text-xs text-gray-400 italic">
                                        +{{ $itemCount - 2 }} more item{{ $itemCount - 2 > 1 ? 's' : '' }}
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-brand-green">
                                EGP {{ number_format($order->total, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select id="status-{{ $order->id }}" class="order-status-select text-[10px] uppercase font-black rounded-full border-0 focus:ring-2 focus:ring-offset-1 focus:ring-brand-green py-1 px-4 cursor-pointer
                                    data-id="{{ $order->id }}"
                                    {{ $order->status === 'new' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $order->status === 'seen' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $order->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                                ">
                                    <option value="new" class="bg-white text-gray-900" @selected($order->status === 'new')>New</option>
                                    <option value="seen" class="bg-white text-gray-900" @selected($order->status === 'seen')>Seen</option>
                                    <option value="confirmed" class="bg-white text-gray-900" @selected($order->status === 'confirmed')>Confirmed</option>
                                    <option value="completed" class="bg-white text-gray-900" @selected($order->status === 'completed')>Completed</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 font-medium">
                                {{ $order->created_at->format('M d, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" class="btn btn-outline btn-sm toggle-order-btn" data-id="{{ $order->id }}">
                                        <i class="fa-solid fa-eye mr-1.5"></i>
                                        View
                                    </button>
                                    <button type="button"
                                            class="btn btn-danger btn-sm delete-order-btn"
                                            data-id="{{ $order->id }}"
                                            data-name="{{ e($order->customer_name) }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Expanded Details Row -->
                        <tr id="details-{{ $order->id }}" class="hidden bg-gray-50 border-t border-gray-100">
                            <td colspan="9" class="p-0">
                                <div class="p-6 md:p-8" id="order-{{ $order->id }}">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                                        <!-- Customer Details -->
                                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center">
                                                <i class="fa-solid fa-id-card mr-2 text-brand-green"></i>
                                                Customer
                                            </h4>
                                            <div class="space-y-2">
                                                <p class="text-sm font-bold text-gray-800">{{ $order->customer_name }}</p>
                                                <p class="text-xs font-medium text-gray-500">{{ $order->customer_phone }}</p>
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" class="inline-flex items-center mt-3 text-xs text-brand-green hover:text-white font-bold bg-green-50 hover:bg-brand-green px-4 py-2 rounded-lg transition-all border border-brand-green/10">
                                                    <i class="fa-brands fa-whatsapp mr-2 text-base"></i>
                                                    Message
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Notes -->
                                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center">
                                                <i class="fa-solid fa-comment-dots mr-2 text-brand-green"></i>
                                                Notes
                                            </h4>
                                            <p class="text-sm text-gray-600 italic leading-relaxed">{{ $order->notes ?: 'No instructions provided.' }}</p>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center">
                                                <i class="fa-solid fa-bolt mr-2 text-brand-green"></i>
                                                Quick Actions
                                            </h4>
                                            <div class="flex flex-col gap-2">
                                                @if($order->pdf_path)
                                                    <a href="{{ asset('storage/' . $order->pdf_path) }}" target="_blank" class="btn btn-sm text-[11px] font-black uppercase tracking-widest w-full justify-center" style="background:#1B4D1B;color:white;">
                                                        <i class="fa-solid fa-file-pdf mr-2"></i> Receipt
                                                    </a>
                                                @endif
                                                @if(isset($order->whatsapp_notify_url))
                                                    <a href="{{ $order->whatsapp_notify_url }}" target="_blank" class="btn btn-sm text-[11px] font-black uppercase tracking-widest w-full justify-center" style="background:#25D366;color:white;">
                                                        <i class="fa-brands fa-whatsapp mr-2"></i> Notify
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Summary -->
                                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-xs font-bold text-gray-400">Subtotal:</span>
                                                <span class="text-sm font-bold text-gray-800">EGP {{ number_format($order->subtotal, 2) }}</span>
                                            </div>
                                            <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                                                <span class="text-sm font-black text-gray-900 uppercase">Total:</span>
                                                <span class="text-xl font-black text-brand-green">EGP {{ number_format($order->total, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                        <div class="overflow-x-auto">
                                        <table class="w-full text-left border-collapse">
                                            <thead class="bg-gray-50/80 border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                                <tr>
                                                    <th class="px-6 py-3">Product Name</th>
                                                    <th class="px-6 py-3 text-center">Qty</th>
                                                    <th class="px-6 py-3 text-right">Unit Price</th>
                                                    <th class="px-6 py-3 text-right">Line Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-50">
                                                @foreach($items as $item)
                                                    <tr>
                                                        <td class="px-6 py-3 text-sm font-bold text-gray-900">
                                                            <div class="flex items-center">
                                                                @if(isset($item['image']) && $item['image'])
                                                                    <img src="{{ asset($item['image']) }}" class="w-10 h-10 rounded-lg object-cover mr-4 border border-gray-100 shadow-sm">
                                                                @else
                                                                    <div class="w-10 h-10 rounded-lg bg-gray-50 mr-4 border border-gray-100 flex items-center justify-center text-gray-300">
                                                                        <i class="fa-solid fa-box text-sm"></i>
                                                                    </div>
                                                                @endif
                                                                {{ $item['name'] ?? 'Unknown Item' }}
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-3 text-sm font-black text-gray-900 text-center">{{ $item['qty'] ?? 1 }}</td>
                                                        <td class="px-6 py-3 text-sm font-medium text-gray-500 text-right">EGP {{ number_format($item['price'] ?? 0, 2) }}</td>
                                                        <td class="px-6 py-3 text-sm font-black text-brand-green text-right">EGP {{ number_format(($item['price'] ?? 0) * ($item['qty'] ?? 1), 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                                        <i class="fa-solid fa-box-open text-3xl text-gray-200"></i>
                                    </div>
                                    <h3 class="text-xl font-black text-gray-900 mb-2">No orders yet</h3>
                                    <p class="text-gray-400 max-w-xs mx-auto font-medium">When customers place orders, they will appear here with all their details.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
            <div class="p-6 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Bulk Action Bar --}}
<div id="orders-bulk-bar"
     class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50
            flex items-center gap-4
            bg-gray-900 text-white px-6 py-3.5 rounded-2xl shadow-2xl
            transition-all duration-300 translate-y-20 opacity-0">
    <span class="text-sm font-semibold whitespace-nowrap">
        <span id="orders-bulk-count">0</span> selected
    </span>
    <div class="w-px h-5 bg-white/20"></div>
    <button id="orders-bulk-delete"
            class="flex items-center gap-2 text-sm font-bold text-red-400 hover:text-red-300 transition-colors whitespace-nowrap">
        <i class="fa-solid fa-trash"></i> Delete Selected
    </button>
    <div class="w-px h-5 bg-white/20"></div>
    <button onclick="document.getElementById('select-all').checked = false; document.getElementById('select-all').dispatchEvent(new Event('change'));"
            class="text-sm font-medium text-gray-400 hover:text-white transition-colors whitespace-nowrap">
        Cancel
    </button>
</div>

@push('scripts')
<script>
(function() {
  'use strict';

  function csrfToken() {
    var m = document.querySelector('meta[name="csrf-token"]');
    if (!m) { console.error('CSRF meta tag missing!'); return ''; }
    return m.getAttribute('content');
  }

  function showToast(msg, isError) {
    var toast = document.createElement('div');
    toast.style.cssText =
      'position:fixed;bottom:24px;right:24px;z-index:99999;' +
      'display:flex;align-items:center;gap:12px;' +
      'background:' + (isError ? '#7f1d1d' : '#111827') + ';' +
      'color:#fff;padding:12px 20px;border-radius:12px;' +
      'box-shadow:0 10px 40px rgba(0,0,0,0.35);' +
      'font-size:14px;font-weight:600;font-family:inherit;' +
      'opacity:0;transform:translateY(16px);' +
      'transition:opacity 0.3s ease,transform 0.3s ease;';
    var icon = isError ? 'fa-circle-exclamation' : 'fa-circle-check';
    var color = isError ? '#fca5a5' : '#4ade80';
    toast.innerHTML =
      '<i class="fa-solid ' + icon + '" style="color:' + color + ';font-size:16px;"></i>' +
      '<span>' + msg + '</span>';
    document.body.appendChild(toast);
    requestAnimationFrame(function() {
      toast.style.opacity = '1';
      toast.style.transform = 'translateY(0)';
    });
    setTimeout(function() {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(16px)';
      setTimeout(function() { toast.remove(); }, 320);
    }, 3500);
  }

  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.toggle-order-btn');
    if (!btn) return;
    var id  = btn.getAttribute('data-id');
    var row = document.getElementById('details-' + id);
    if (row) row.classList.toggle('hidden');
  });

  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.delete-order-btn');
    if (!btn) return;
    var id   = btn.getAttribute('data-id');
    var name = btn.getAttribute('data-name');
    if (!confirm(
      'Delete order from "' + name + '"?\n\n' +
      'This will permanently remove the order and its PDF receipt.\n' +
      'This action cannot be undone.'
    )) return;

    fetch('/dashboard/orders/' + id, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken(),
        'Accept': 'application/json'
      }
    })
    .then(function(r) {
      if (r.status === 403) throw new Error('403 Forbidden — CSRF token missing or expired. Please refresh the page.');
      if (r.status === 404) throw new Error('404 — Order not found.');
      if (!r.ok) throw new Error('Server error ' + r.status);
      return r.json();
    })
    .then(function(data) {
      if (data.success) {
        var mainRow    = document.querySelector('[data-main-row="' + id + '"]');
        var detailsRow = document.getElementById('details-' + id);
        if (mainRow)    mainRow.remove();
        if (detailsRow) detailsRow.remove();
        document.querySelectorAll('[data-count-all]').forEach(function(el) {
          if (data.new_total_count !== undefined)
            el.textContent = '(' + data.new_total_count + ')';
        });
        showToast('Order deleted successfully.');
      } else {
        showToast(data.message || 'Failed to delete order.', true);
      }
    })
    .catch(function(err) {
      console.error('deleteOrder error:', err);
      showToast(err.message, true);
    });
  });

  document.addEventListener('change', function(e) {
    var sel = e.target.closest('.order-status-select');
    if (!sel) return;
    var id     = sel.getAttribute('data-id');
    var status = sel.value;

    fetch('/dashboard/orders/' + id + '/status', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken(),
        'Accept': 'application/json'
      },
      body: JSON.stringify({ status: status })
    })
    .then(function(r) {
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return r.json();
    })
    .then(function(data) {
      if (data.success) {
        sel.className = sel.className
          .replace(/bg-\w+-\d+/g, '')
          .replace(/text-\w+-\d+/g, '')
          .trim();
        var colors = {
          new:       ['bg-amber-100', 'text-amber-800'],
          seen:      ['bg-blue-100',  'text-blue-800'],
          confirmed: ['bg-green-100', 'text-green-800'],
          completed: ['bg-gray-100',  'text-gray-800'],
        };
        if (colors[status]) {
          sel.classList.add(colors[status][0], colors[status][1]);
        }
        showToast('Status updated to ' + status + '.');
      } else {
        showToast('Failed to update status.', true);
      }
    })
    .catch(function(err) {
      console.error('updateStatus error:', err);
      showToast('Error updating status: ' + err.message, true);
    });
  });

  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.copy-phone-btn');
    if (!btn) return;
    var phone = btn.getAttribute('data-phone');
    var icon  = btn.querySelector('i');
    navigator.clipboard.writeText(phone).then(function() {
      if (icon) icon.className = 'fa-solid fa-check text-xs';
      btn.classList.add('text-brand-green');
      setTimeout(function() {
        if (icon) icon.className = 'fa-regular fa-copy text-xs';
        btn.classList.remove('text-brand-green');
      }, 2000);
    }).catch(function() {
      showToast('Could not copy phone number.', true);
    });
  });

  var selectAll = document.getElementById('select-all');
  var bulkBar   = document.getElementById('orders-bulk-bar');
  var bulkCount = document.getElementById('orders-bulk-count');
  var bulkDel   = document.getElementById('orders-bulk-delete');

  function getCheckedOrderIds() {
    return Array.from(
      document.querySelectorAll('.order-checkbox:checked')
    ).map(function(cb) { return parseInt(cb.value); });
  }

  function updateBulkBar() {
    var count = getCheckedOrderIds().length;
    if (bulkCount) bulkCount.textContent = count;
    if (bulkBar) {
      bulkBar.style.cssText = count > 0
        ? 'opacity:1;transform:translateY(0);transition:all 0.3s ease;'
        : 'opacity:0;transform:translateY(80px);transition:all 0.3s ease;';
    }
    if (selectAll) {
      var all = document.querySelectorAll('.order-checkbox');
      selectAll.indeterminate = count > 0 && count < all.length;
      selectAll.checked = all.length > 0 && count === all.length;
    }
  }

  if (selectAll) {
    selectAll.addEventListener('change', function() {
      document.querySelectorAll('.order-checkbox')
        .forEach(function(cb) { cb.checked = selectAll.checked; });
      updateBulkBar();
    });
  }

  document.addEventListener('change', function(e) {
    if (e.target.classList.contains('order-checkbox')) updateBulkBar();
  });

  if (bulkDel) {
    bulkDel.addEventListener('click', function() {
      var ids = getCheckedOrderIds();
      if (!ids.length) return;
      if (!confirm(
        'Delete ' + ids.length + ' selected order(s)?\n' +
        'This will also delete their PDF receipts.\n' +
        'This action cannot be undone.'
      )) return;

      fetch('{{ route("dashboard.orders.bulk-destroy") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken(),
          'Accept': 'application/json'
        },
        body: JSON.stringify({ ids: ids })
      })
      .then(function(r) {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
      })
      .then(function(data) {
        if (data.success) {
          ids.forEach(function(id) {
            var row     = document.querySelector('[data-main-row="' + id + '"]');
            var details = document.getElementById('details-' + id);
            if (row)     row.remove();
            if (details) details.remove();
          });
          if (selectAll) selectAll.checked = false;
          updateBulkBar();
          document.querySelectorAll('[data-count-all]').forEach(function(el) {
            if (data.new_total_count !== undefined)
              el.textContent = '(' + data.new_total_count + ')';
          });
          showToast(data.deleted + ' order(s) deleted successfully.');
        } else {
          showToast(data.message || 'Bulk delete failed.', true);
        }
      })
      .catch(function(err) {
        console.error('bulkDestroy error:', err);
        showToast('Error: ' + err.message, true);
      });
    });
  }

  var cancelBulk = document.querySelector(
    '[onclick*="select-all"][onclick*="dispatchEvent"]'
  );
  if (cancelBulk) {
    cancelBulk.removeAttribute('onclick');
    cancelBulk.addEventListener('click', function() {
      document.querySelectorAll('.order-checkbox')
        .forEach(function(cb) { cb.checked = false; });
      if (selectAll) selectAll.checked = false;
      updateBulkBar();
    });
  }

})();
</script>
@endpush
@endsection
