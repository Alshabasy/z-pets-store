@extends('layouts.dashboard')

@section('page-title', 'Store Settings')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8 max-w-4xl mx-auto">
    <form action="{{ route('dashboard.settings.update') }}" method="POST">
        @csrf
        
        <div class="space-y-10">
            <!-- General Settings -->
            <div class="animate-on-scroll">
                <div class="dash-section-heading">
                    <i class="fa-solid fa-store"></i>
                    Store Identity
                </div>
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="store_name" class="form-label">Store Name <span class="text-red-500">*</span></label>
                        <input type="text" id="store_name" name="store_name" value="{{ old('store_name', $settings['store_name'] ?? '') }}" class="form-input @error('store_name') border-red-500 @enderror" required>
                        @error('store_name') <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label for="store_tagline" class="form-label">Store Tagline</label>
                        <input type="text" id="store_tagline" name="store_tagline" value="{{ old('store_tagline', $settings['store_tagline'] ?? '') }}" class="form-input @error('store_tagline') border-red-500 @enderror">
                        <p class="mt-2 text-[10px] text-gray-400 font-bold uppercase tracking-wider">Displayed in the footer and search results.</p>
                        @error('store_tagline') <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Contact & Ordering -->
            <div class="animate-on-scroll">
                <div class="dash-section-heading">
                    <i class="fa-brands fa-whatsapp"></i>
                    WhatsApp Ordering
                </div>
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="whatsapp_number" class="form-label">WhatsApp Number <span class="text-red-500">*</span></label>
                        <div class="flex">
                            <span class="inline-flex items-center px-4 rounded-l-xl border-2 border-r-0 border-gray-100 bg-gray-50 text-gray-400">
                                <i class="fa-solid fa-phone-flip text-sm"></i>
                            </span>
                            <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}" class="form-input !rounded-l-none @error('whatsapp_number') border-red-500 @enderror" placeholder="201XXXXXXXXX" required>
                        </div>
                        <p class="mt-2 text-[10px] text-gray-400 font-bold uppercase tracking-wider">Format: country code + number (e.g., 201000000000).</p>
                        @error('whatsapp_number') <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
                        
                        <div class="mt-4 p-4 bg-green-50 rounded-xl border-2 border-green-100 flex items-center">
                            <i class="fa-solid fa-link mr-3 text-green-500"></i>
                            <div class="flex-1">
                                <span class="text-[10px] font-black text-green-800 uppercase tracking-widest block mb-1">Direct Link Preview</span>
                                <a href="https://wa.me/{{ $settings['whatsapp_number'] ?? '' }}" target="_blank" class="text-sm font-bold text-green-700 hover:underline break-all">https://wa.me/{{ $settings['whatsapp_number'] ?? '' }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery -->
            <div class="animate-on-scroll">
                <div class="dash-section-heading">
                    <i class="fa-solid fa-truck-fast"></i>
                    Delivery & Shipping
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="free_delivery_above" class="form-label">Free Delivery Threshold</label>
                        <div class="flex">
                            <input type="number" id="free_delivery_above" name="free_delivery_above" value="{{ old('free_delivery_above', $settings['free_delivery_above'] ?? '') }}" class="form-input !rounded-r-none @error('free_delivery_above') border-red-500 @enderror">
                            <span class="inline-flex items-center px-5 rounded-r-xl border-2 border-l-0 border-gray-100 bg-gray-50 text-gray-400 text-xs font-black">
                                EGP
                            </span>
                        </div>
                        @error('free_delivery_above') <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="delivery_note" class="form-label">Delivery Note</label>
                        <input type="text" id="delivery_note" name="delivery_note" value="{{ old('delivery_note', $settings['delivery_note'] ?? '') }}" class="form-input @error('delivery_note') border-red-500 @enderror">
                        <p class="mt-2 text-[10px] text-gray-400 font-bold uppercase tracking-wider">Shows in global announcement bars and checkout.</p>
                        @error('delivery_note') <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- ── SHOP ADDRESS ── --}}
            <div class="animate-on-scroll">
                <div class="dash-section-heading">
                    <i class="fa-solid fa-location-dot"></i>
                    Shop Address
                </div>
                <div>
                    <label for="shop_address" class="form-label">Physical Address</label>
                    <textarea id="shop_address" name="shop_address" rows="3"
                              class="form-input @error('shop_address') border-red-500 @enderror"
                              placeholder="e.g. 15 El-Nile Street, Maadi, Cairo, Egypt">{{ old('shop_address', $settings['shop_address'] ?? '') }}</textarea>
                    <p class="mt-2 text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                        Displayed in the footer. Leave empty to hide.
                    </p>
                    @error('shop_address')
                        <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ── SOCIAL MEDIA ── --}}
            <div class="animate-on-scroll">
                <div class="dash-section-heading">
                    <i class="fa-solid fa-share-nodes"></i>
                    Social Media Links
                </div>
                <p class="text-xs text-gray-400 font-medium mb-6 -mt-2">
                    Paste the full URL of your page. Leave empty to hide the icon in the footer.
                </p>
                <div class="grid grid-cols-1 gap-5">

                    {{-- Facebook --}}
                    <div>
                        <label for="social_facebook" class="form-label flex items-center gap-2">
                            <i class="fa-brands fa-facebook text-blue-600 text-lg"></i>
                            Facebook Page URL
                        </label>
                        <div class="flex">
                            <span class="inline-flex items-center px-4 rounded-l-xl border-2
                                         border-r-0 border-gray-100 bg-gray-50 text-blue-500">
                                <i class="fa-brands fa-facebook text-base"></i>
                            </span>
                            <input type="url" id="social_facebook" name="social_facebook"
                                   value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}"
                                   class="form-input !rounded-l-none
                                          @error('social_facebook') border-red-500 @enderror"
                                   placeholder="https://facebook.com/yourpage">
                        </div>
                        @error('social_facebook')
                            <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Instagram --}}
                    <div>
                        <label for="social_instagram" class="form-label flex items-center gap-2">
                            <i class="fa-brands fa-instagram text-pink-600 text-lg"></i>
                            Instagram Profile URL
                        </label>
                        <div class="flex">
                            <span class="inline-flex items-center px-4 rounded-l-xl border-2
                                         border-r-0 border-gray-100 bg-gray-50 text-pink-500">
                                <i class="fa-brands fa-instagram text-base"></i>
                            </span>
                            <input type="url" id="social_instagram" name="social_instagram"
                                   value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}"
                                   class="form-input !rounded-l-none
                                          @error('social_instagram') border-red-500 @enderror"
                                   placeholder="https://instagram.com/yourprofile">
                        </div>
                        @error('social_instagram')
                            <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- TikTok --}}
                    <div>
                        <label for="social_tiktok" class="form-label flex items-center gap-2">
                            <i class="fa-brands fa-tiktok text-gray-900 text-lg"></i>
                            TikTok Profile URL
                        </label>
                        <div class="flex">
                            <span class="inline-flex items-center px-4 rounded-l-xl border-2
                                         border-r-0 border-gray-100 bg-gray-50 text-gray-700">
                                <i class="fa-brands fa-tiktok text-base"></i>
                            </span>
                            <input type="url" id="social_tiktok" name="social_tiktok"
                                   value="{{ old('social_tiktok', $settings['social_tiktok'] ?? '') }}"
                                   class="form-input !rounded-l-none
                                          @error('social_tiktok') border-red-500 @enderror"
                                   placeholder="https://tiktok.com/@yourprofile">
                        </div>
                        @error('social_tiktok')
                            <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end border-t-2 border-gray-100 pt-8 animate-on-scroll">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Save Changes
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
