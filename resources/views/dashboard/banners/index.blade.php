@extends('layouts.dashboard')

@section('page-title', 'Banners')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gray-50/30">
        <div class="dash-section-heading !mb-0 flex-1 min-w-0">
            <i class="fa-solid fa-images"></i>
            Homepage Banners
        </div>
        <button type="button" onclick="openModal('add-banner-modal')" class="btn btn-primary">
            <i class="fa-solid fa-plus mr-2"></i>
            Add Banner
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    <th class="px-6 py-4 w-10">
                        <input type="checkbox" id="select-all-banners" class="form-checkbox rounded text-brand-green focus:ring-brand-green cursor-pointer">
                    </th>
                    <th class="px-6 py-4">Image</th>
                    <th class="px-6 py-4">Title / Link</th>
                    <th class="px-6 py-4">Sort Order</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($banners as $banner)
                    <tr class="hover:bg-gray-50/50 transition-colors" data-row-id="{{ $banner->id }}">
                        <td class="px-6 py-4 whitespace-nowrap w-10">
                            <input type="checkbox" value="{{ $banner->id }}" class="banner-checkbox form-checkbox rounded text-brand-green focus:ring-brand-green cursor-pointer">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-40 h-20 flex-shrink-0 bg-gray-100 rounded-xl border-2 border-gray-50 overflow-hidden shadow-sm group relative">
                                <img src="{{ asset('storage/' . $banner->image) }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <i class="fa-solid fa-magnifying-glass-plus text-white text-xl"></i>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-black text-gray-900 mb-1">{{ $banner->title ?? 'Untitled Banner' }}</div>
                            @if($banner->link_url)
                                <a href="{{ $banner->link_url }}" target="_blank" class="text-[11px] font-bold text-blue-500 hover:text-blue-700 break-all flex items-center">
                                    <i class="fa-solid fa-link mr-1.5 opacity-50"></i>
                                    {{ Str::limit($banner->link_url, 40) }}
                                </a>
                            @else
                                <div class="text-[10px] font-black text-gray-300 uppercase tracking-widest">No target link</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('dashboard.banners.update', $banner->id) }}" method="POST" class="flex items-center w-24">
                                @csrf
                                @method('PUT')
                                @if($banner->title)<input type="hidden" name="title" value="{{ $banner->title }}">@endif
                                @if($banner->link_url)<input type="hidden" name="link_url" value="{{ $banner->link_url }}">@endif
                                <input type="hidden" name="is_active" value="{{ $banner->is_active ? 1 : 0 }}">
                                <input type="number" name="sort_order" value="{{ $banner->sort_order }}" class="form-input !py-1 !px-3 !text-sm !font-black !text-center" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('dashboard.banners.update', $banner->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                @if($banner->title)<input type="hidden" name="title" value="{{ $banner->title }}">@endif
                                @if($banner->link_url)<input type="hidden" name="link_url" value="{{ $banner->link_url }}">@endif
                                <input type="hidden" name="sort_order" value="{{ $banner->sort_order }}">
                                <input type="hidden" name="is_active" value="0">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" @checked($banner->is_active) onchange="this.form.submit()">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-green"></div>
                                </label>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button"
                                        class="btn btn-outline btn-sm edit-banner-btn"
                                        data-id="{{ $banner->id }}"
                                        data-title="{{ e($banner->title ?? '') }}"
                                        data-link="{{ e($banner->link_url ?? '') }}"
                                        data-sort="{{ $banner->sort_order }}"
                                        data-active="{{ $banner->is_active ? '1' : '0' }}"
                                        data-image="{{ asset('storage/' . $banner->image) }}">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </button>
                                <form action="{{ route('dashboard.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this banner?');" class="inline-block">
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
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                                    <i class="fa-solid fa-image text-3xl text-gray-200"></i>
                                </div>
                                <h3 class="text-xl font-black text-gray-900 mb-2">No banners found</h3>
                                <p class="text-gray-400 max-w-xs mx-auto font-medium">Add some banners to showcase products or offers on the homepage.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Bulk Action Bar --}}
<div id="banners-bulk-bar"
     class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50
            flex items-center gap-4
            bg-gray-900 text-white px-6 py-3.5 rounded-2xl shadow-2xl
            transition-all duration-300 translate-y-20 opacity-0">
    <span class="text-sm font-semibold whitespace-nowrap">
        <span id="banners-bulk-count">0</span> selected
    </span>
    <div class="w-px h-5 bg-white/20"></div>
    <button id="banners-bulk-delete"
            class="flex items-center gap-2 text-sm font-bold text-red-400 hover:text-red-300 transition-colors whitespace-nowrap">
        <i class="fa-solid fa-trash"></i> Delete Selected
    </button>
    <div class="w-px h-5 bg-white/20"></div>
    <button onclick="document.getElementById('select-all-banners').checked = false; document.getElementById('select-all-banners').dispatchEvent(new Event('change'));"
            class="text-sm font-medium text-gray-400 hover:text-white transition-colors whitespace-nowrap">
        Cancel
    </button>
</div>

<!-- Add Banner Modal -->
<div id="add-banner-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  {{-- Backdrop --}}
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
       onclick="closeModal('add-banner-modal')"></div>

  {{-- Modal Panel --}}
  <div class="relative z-10 w-full max-w-lg bg-white rounded-2xl shadow-2xl
              overflow-hidden max-h-[90vh] overflow-y-auto">
    <form action="{{ route('dashboard.banners.store') }}"
          method="POST" enctype="multipart/form-data">
      @csrf

      {{-- Header --}}
      <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div class="flex items-center gap-2 font-black text-gray-900 uppercase
                    tracking-widest text-sm">
          <i class="fa-solid fa-circle-plus text-brand-green"></i>
          Add New Banner
        </div>
        <button type="button" onclick="closeModal('add-banner-modal')"
                class="w-8 h-8 flex items-center justify-center rounded-lg
                       text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      {{-- Body --}}
      <div class="px-6 py-6 space-y-5">

        {{-- Image Upload --}}
        <div>
          <label class="form-label">
            Banner Image <span class="text-red-500">*</span>
          </label>
          <label for="add-banner-image"
                 class="flex flex-col items-center justify-center w-full h-40
                        border-2 border-dashed border-gray-200 rounded-xl
                        cursor-pointer hover:border-brand-green hover:bg-green-50/30
                        transition-all group">
            <div id="add-preview-wrap" class="hidden w-full h-full relative">
              <img id="add-preview-img"
                   class="w-full h-full object-contain rounded-xl" src="" alt="">
              <div class="absolute inset-0 bg-black/30 flex items-center
                          justify-center opacity-0 group-hover:opacity-100
                          transition rounded-xl">
                <span class="text-white text-xs font-bold">Change Image</span>
              </div>
            </div>
            <div id="add-upload-prompt" class="flex flex-col items-center gap-2">
              <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-300
                        group-hover:text-brand-green transition-colors"></i>
              <span class="text-sm font-semibold text-gray-500
                           group-hover:text-brand-green transition-colors">
                Click to upload
              </span>
              <span class="text-xs text-gray-400">PNG, JPG, WEBP — max 2MB</span>
            </div>
            <input id="add-banner-image" name="image" type="file"
                   class="sr-only" required accept="image/*"
                   onchange="previewImage(this,'add-preview-img','add-preview-wrap','add-upload-prompt')">
          </label>
        </div>

        {{-- Title --}}
        <div>
          <label class="form-label">Title <span class="text-gray-400 font-normal">(optional)</span></label>
          <input type="text" name="title" class="form-input"
                 placeholder="e.g. Summer Sale — Up to 50% Off">
        </div>

        {{-- Link URL --}}
        <div>
          <label class="form-label">Link URL <span class="text-gray-400 font-normal">(optional)</span></label>
          <input type="url" name="link_url" class="form-input"
                 placeholder="https://zpets.com/category/dogs">
        </div>

        {{-- Sort + Active --}}
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" value="0" min="0"
                   class="form-input">
          </div>
          <div class="flex items-end pb-1">
            <label class="flex items-center gap-3 cursor-pointer">
              <div class="relative">
                <input type="checkbox" name="is_active" value="1"
                       checked class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 rounded-full peer
                            peer-checked:bg-brand-green transition-colors
                            after:content-[''] after:absolute after:top-[2px]
                            after:left-[2px] after:bg-white after:rounded-full
                            after:h-5 after:w-5 after:transition-all
                            peer-checked:after:translate-x-full"></div>
              </div>
              <span class="text-sm font-semibold text-gray-600">Active</span>
            </label>
          </div>
        </div>

      </div>

      {{-- Footer --}}
      <div class="px-6 py-4 bg-gray-50 border-t border-gray-100
                  flex flex-col-reverse sm:flex-row gap-3 sm:justify-end">
        <button type="button" onclick="closeModal('add-banner-modal')"
                class="btn btn-outline w-full sm:w-auto">
          Cancel
        </button>
        <button type="submit" class="btn btn-primary w-full sm:w-auto">
          <i class="fa-solid fa-floppy-disk mr-2"></i> Save Banner
        </button>
      </div>

    </form>
  </div>
</div>

<!-- Edit Banner Modal -->
<div id="edit-banner-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  {{-- Backdrop --}}
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
       onclick="closeModal('edit-banner-modal')"></div>

  {{-- Modal Panel --}}
  <div class="relative z-10 w-full max-w-lg bg-white rounded-2xl shadow-2xl
              overflow-hidden max-h-[90vh] overflow-y-auto">
    <form id="edit-banner-form" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      {{-- Header --}}
      <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div class="flex items-center gap-2 font-black text-gray-900 uppercase
                    tracking-widest text-sm">
          <i class="fa-solid fa-pen-to-square text-brand-green"></i>
          Edit Banner
        </div>
        <button type="button" onclick="closeModal('edit-banner-modal')"
                class="w-8 h-8 flex items-center justify-center rounded-lg
                       text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      {{-- Body --}}
      <div class="px-6 py-6 space-y-5">

        {{-- Current image preview + replace --}}
        <div>
          <label class="form-label">
            Replace Image
            <span class="text-gray-400 font-normal">(leave empty to keep current)</span>
          </label>
          <div id="edit-current-preview"
               class="w-full h-36 rounded-xl border-2 border-gray-100 overflow-hidden
                      bg-gray-50 mb-3 hidden">
            <img id="edit-current-img" src="" alt="Current banner"
                 class="w-full h-full object-contain">
          </div>
          <label for="edit-banner-image"
                 class="flex items-center gap-3 px-4 py-3 border-2 border-dashed
                        border-gray-200 rounded-xl cursor-pointer
                        hover:border-brand-green hover:bg-green-50/30 transition group">
            <i class="fa-solid fa-arrow-up-from-bracket text-gray-400
                      group-hover:text-brand-green transition"></i>
            <span class="text-sm font-semibold text-gray-500
                         group-hover:text-brand-green transition"
                  id="edit-file-label">Choose new image...</span>
            <input id="edit-banner-image" name="image" type="file"
                   class="sr-only" accept="image/*"
                   onchange="updateFileLabel(this,'edit-file-label');previewImage(this,'edit-current-img','edit-current-preview',null)">
          </label>
        </div>

        {{-- Title --}}
        <div>
          <label class="form-label">Title</label>
          <input type="text" id="edit-title" name="title" class="form-input"
                 placeholder="Banner title">
        </div>

        {{-- Link URL --}}
        <div>
          <label class="form-label">Link URL</label>
          <input type="url" id="edit-link" name="link_url" class="form-input"
                 placeholder="https://...">
        </div>

        {{-- Sort + Active --}}
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="form-label">Sort Order</label>
            <input type="number" id="edit-sort" name="sort_order" min="0"
                   class="form-input">
          </div>
          <div class="flex items-end pb-1">
            <label class="flex items-center gap-3 cursor-pointer">
              <div class="relative">
                <input type="checkbox" id="edit-active" name="is_active"
                       value="1" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 rounded-full peer
                            peer-checked:bg-brand-green transition-colors
                            after:content-[''] after:absolute after:top-[2px]
                            after:left-[2px] after:bg-white after:rounded-full
                            after:h-5 after:w-5 after:transition-all
                            peer-checked:after:translate-x-full"></div>
              </div>
              <span class="text-sm font-semibold text-gray-600">Active</span>
            </label>
          </div>
        </div>

      </div>

      {{-- Footer --}}
      <div class="px-6 py-4 bg-gray-50 border-t border-gray-100
                  flex flex-col-reverse sm:flex-row gap-3 sm:justify-end">
        <button type="button" onclick="closeModal('edit-banner-modal')"
                class="btn btn-outline w-full sm:w-auto">
          Cancel
        </button>
        <button type="submit" class="btn btn-primary w-full sm:w-auto">
          <i class="fa-solid fa-floppy-disk mr-2"></i> Update Banner
        </button>
      </div>

    </form>
  </div>
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
    var icon  = isError ? 'fa-circle-exclamation' : 'fa-circle-check';
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

  function openModal(id) {
    var el = document.getElementById(id);
    if (!el) { console.error('Modal not found:', id); return; }
    el.classList.remove('hidden');
    el.setAttribute('style',
      'display:flex !important;align-items:center;justify-content:center;'
    );
    document.body.style.overflow = 'hidden';
  }

  function closeModal(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.removeAttribute('style');
    el.classList.add('hidden');
    document.body.style.overflow = '';
  }

  window.openModal  = openModal;
  window.closeModal = closeModal;

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeModal('add-banner-modal');
      closeModal('edit-banner-modal');
    }
  });

  var addBtn = document.querySelector('[onclick="openModal(\'add-banner-modal\')"]');
  if (addBtn) {
    addBtn.removeAttribute('onclick');
    addBtn.addEventListener('click', function() {
      openModal('add-banner-modal');
    });
  }

  function previewImage(input, imgId, wrapId, promptId) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
      var img  = document.getElementById(imgId);
      var wrap = document.getElementById(wrapId);
      if (img)  img.src = e.target.result;
      if (wrap) {
        wrap.classList.remove('hidden');
        wrap.style.display = 'block';
      }
      if (promptId) {
        var prompt = document.getElementById(promptId);
        if (prompt) prompt.style.display = 'none';
      }
    };
    reader.readAsDataURL(input.files[0]);
  }
  window.previewImage = previewImage;

  function updateFileLabel(input, labelId) {
    var label = document.getElementById(labelId);
    if (label && input.files && input.files[0]) {
      label.textContent = input.files[0].name;
    }
  }
  window.updateFileLabel = updateFileLabel;

  function openEditModal(id, title, link, sort, active, imageUrl) {
    var form = document.getElementById('edit-banner-form');
    if (!form) { console.error('edit-banner-form not found'); return; }

    form.action = '/dashboard/banners/' + id;

    var titleEl  = document.getElementById('edit-title');
    var linkEl   = document.getElementById('edit-link');
    var sortEl   = document.getElementById('edit-sort');
    var activeEl = document.getElementById('edit-active');

    if (titleEl)  titleEl.value   = title  || '';
    if (linkEl)   linkEl.value    = link   || '';
    if (sortEl)   sortEl.value    = sort   || 0;
    if (activeEl) activeEl.checked = (active === true || active === 'true');

    var currentImg  = document.getElementById('edit-current-img');
    var currentWrap = document.getElementById('edit-current-preview');
    if (imageUrl && currentImg && currentWrap) {
      currentImg.src = imageUrl;
      currentWrap.classList.remove('hidden');
      currentWrap.style.display = 'block';
    } else if (currentWrap) {
      currentWrap.classList.add('hidden');
      currentWrap.style.display = 'none';
    }

    var fileLabel = document.getElementById('edit-file-label');
    if (fileLabel) fileLabel.textContent = 'Choose new image...';

    var fileInput = document.getElementById('edit-banner-image');
    if (fileInput) fileInput.value = '';

    openModal('edit-banner-modal');
  }
  window.openEditModal = openEditModal;

  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.edit-banner-btn');
    if (!btn) return;
    openEditModal(
      parseInt(btn.getAttribute('data-id'), 10),
      btn.getAttribute('data-title') || '',
      btn.getAttribute('data-link') || '',
      parseInt(btn.getAttribute('data-sort'), 10) || 0,
      btn.getAttribute('data-active') === '1',
      btn.getAttribute('data-image') || ''
    );
  });

  var selectAll = document.getElementById('select-all-banners');
  var bulkBar   = document.getElementById('banners-bulk-bar');
  var bulkCount = document.getElementById('banners-bulk-count');
  var bulkDel   = document.getElementById('banners-bulk-delete');

  function getCheckedBannerIds() {
    return Array.from(
      document.querySelectorAll('.banner-checkbox:checked')
    ).map(function(cb) { return parseInt(cb.value); });
  }

  function updateBulkBar() {
    var count = getCheckedBannerIds().length;
    if (bulkCount) bulkCount.textContent = count;
    if (bulkBar) {
      bulkBar.style.cssText = count > 0
        ? 'opacity:1;transform:translateY(0);transition:all 0.3s ease;'
        : 'opacity:0;transform:translateY(80px);transition:all 0.3s ease;';
    }
    if (selectAll) {
      var all = document.querySelectorAll('.banner-checkbox');
      selectAll.indeterminate = count > 0 && count < all.length;
      selectAll.checked = all.length > 0 && count === all.length;
    }
  }

  if (selectAll) {
    selectAll.addEventListener('change', function() {
      document.querySelectorAll('.banner-checkbox')
        .forEach(function(cb) { cb.checked = selectAll.checked; });
      updateBulkBar();
    });
  }

  document.addEventListener('change', function(e) {
    if (e.target.classList.contains('banner-checkbox')) updateBulkBar();
  });

  if (bulkDel) {
    bulkDel.addEventListener('click', function() {
      var ids = getCheckedBannerIds();
      if (!ids.length) return;
      if (!confirm(
        'Delete ' + ids.length + ' selected banner(s)?\n' +
        'This action cannot be undone.'
      )) return;

      fetch('{{ route("dashboard.banners.bulk-destroy") }}', {
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
            var row = document.querySelector('[data-row-id="' + id + '"]');
            if (row) row.remove();
          });
          if (selectAll) selectAll.checked = false;
          updateBulkBar();
          showToast(data.deleted + ' banner(s) deleted.');
        } else {
          showToast(data.message || 'Bulk delete failed.', true);
        }
      })
      .catch(function(err) {
        console.error('bannerBulkDestroy error:', err);
        showToast('Error: ' + err.message, true);
      });
    });
  }

  document.querySelectorAll('[onclick*="select-all-banners"]')
    .forEach(function(btn) {
      btn.removeAttribute('onclick');
      btn.addEventListener('click', function() {
        document.querySelectorAll('.banner-checkbox')
          .forEach(function(cb) { cb.checked = false; });
        if (selectAll) selectAll.checked = false;
        updateBulkBar();
      });
    });

})();
</script>
@endpush
@endsection
