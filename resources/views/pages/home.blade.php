@extends('layouts.app')

@section('meta_title', $storeName . ' — Egypt\'s Pet Store')
@section('meta_description', \App\Models\Setting::getValue('store_tagline', 'Egypt\'s Favourite Pet Store') . ' — Shop pet food, toys, accessories and more.')
@section('title', $storeName . ' - Home')

@section('content')

<!-- Section 1 — HERO BANNER SLIDER -->
@if($banners->count() > 0)
    <div class="relative w-full overflow-hidden group" id="hero-slider">
        <div class="flex transition-transform duration-700 ease-in-out" id="slider-track">
            @foreach($banners as $index => $banner)
                <div class="min-w-full relative flex-shrink-0" style="aspect-ratio: 16/5;">
                    <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title ?? config('app.name') . ' banner' }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}" class="absolute inset-0 w-full h-full" style="object-fit: cover; object-position: center;">
                    @if($banner->title)
                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                            <h2 class="text-white text-3xl md:text-5xl font-bold text-center tracking-wide drop-shadow-lg px-4">
                                {{ $banner->title }}
                            </h2>
                        </div>
                    @endif
                    @if($banner->link_url)
                        <a href="{{ $banner->link_url }}" class="absolute inset-0 z-10"></a>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Dot Navigation -->
        @if($banners->count() > 1)
            <div class="absolute bottom-3 left-0 right-0 flex justify-center space-x-2 z-20">
                @foreach($banners as $index => $banner)
                    <button class="w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-colors focus:outline-none slider-dot" data-index="{{ $index }}" aria-label="Go to slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.getElementById('slider-track');
            const dots = document.querySelectorAll('.slider-dot');
            const totalSlides = {{ $banners->count() }};
            let currentIndex = 0;
            let slideInterval;

            if (totalSlides <= 1) return;

            // Initialize first dot
            if(dots.length > 0) dots[0].classList.replace('bg-white/50', 'bg-white');

            function goToSlide(index) {
                currentIndex = index;
                const offset = -currentIndex * 100;
                track.style.transform = `translateX(${offset}%)`;
                
                dots.forEach(dot => {
                    dot.classList.replace('bg-white', 'bg-white/50');
                });
                dots[currentIndex].classList.replace('bg-white/50', 'bg-white');
            }

            function nextSlide() {
                let nextIndex = (currentIndex + 1) % totalSlides;
                goToSlide(nextIndex);
            }

            dots.forEach(dot => {
                dot.addEventListener('click', function() {
                    clearInterval(slideInterval);
                    goToSlide(parseInt(this.getAttribute('data-index')));
                    startSlider();
                });
            });

            function startSlider() {
                slideInterval = setInterval(nextSlide, 4000);
            }

            startSlider();
        });
    </script>
    @endpush
@else
    <div class="w-full bg-brand-green-dark h-64 md:h-96 flex flex-col items-center justify-center text-center px-4">
        <h1 class="text-white text-4xl md:text-6xl font-bold tracking-tight mb-4">{{ $storeName }}</h1>
        <p class="text-brand-green-light text-lg md:text-2xl font-medium">{{ \App\Models\Setting::getValue('store_tagline') }}</p>
    </div>
@endif

<!-- Section 2 — PET CATEGORY QUICK-LINKS -->
@if($mainCategories->count() > 0)
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10 animate-on-scroll flex justify-center">
                <h2 class="section-heading !mb-0">Shop by Pet</h2>
            </div>
            
            <div class="flex overflow-x-auto pb-4 gap-6 snap-x justify-start md:justify-center">
                @foreach($mainCategories as $index => $category)
                    <div class="flex-shrink-0 w-40 md:w-48 snap-center animate-on-scroll animate-delay-{{ ($index % 4) + 1 }}">
                        <x-category-card :category="$category" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!-- Section 3 — FEATURED PRODUCTS -->
@if($featuredProducts->count() > 0)
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-10 animate-on-scroll">
                <h2 class="section-heading !mb-0 flex-1 min-w-0">Our Picks for Your Pet</h2>
                <a href="{{ route('products.index') }}" class="text-brand-green font-semibold hover:text-brand-green-dark transition-colors flex items-center shrink-0">
                    See All
                    <i class="fa-solid fa-chevron-right ml-1.5 text-xs"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($featuredProducts as $index => $product)
                    <div class="animate-on-scroll animate-delay-{{ ($index % 4) + 1 }}">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!-- Section 4 — NEW ARRIVALS -->
@if($newArrivals->count() > 0)
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-10 animate-on-scroll">
                <h2 class="section-heading !mb-0 flex-1 min-w-0">New Arrivals</h2>
                <a href="{{ route('products.index') }}" class="text-brand-green font-semibold hover:text-brand-green-dark transition-colors flex items-center shrink-0">
                    View All
                    <i class="fa-solid fa-chevron-right ml-1.5 text-xs"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($newArrivals as $index => $product)
                    <div class="animate-on-scroll animate-delay-{{ ($index % 4) + 1 }}">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!-- Section 5 — DELIVERY BANNER -->
@php
    $freeDeliveryAbove = \App\Models\Setting::getValue('free_delivery_above', '2000');
@endphp
<section class="py-12 bg-brand-green animate-on-scroll">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl md:text-4xl font-bold text-white flex flex-col md:flex-row items-center justify-center gap-4">
            <i class="fa-solid fa-truck-fast"></i>
            Free Delivery on orders over {{ $freeDeliveryAbove }} EGP (Cairo & Giza)
        </h2>
    </div>
</section>

@endsection
