<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('meta_title', \App\Models\Setting::getValue('store_name', 'Z-Pets Store') . ' — Egypt\'s Pet Store')</title>
    <meta name="description" content="@yield('meta_description', 'Shop pet food, toys, accessories and more at Z-Pets Store.')">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('meta_title', \App\Models\Setting::getValue('store_name', 'Z-Pets Store') . ' — Egypt\'s Pet Store')">
    <meta property="og:description" content="@yield('meta_description', 'Shop pet food, toys, accessories and more at Z-Pets Store.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    @hasSection('meta_image')
        <meta property="og:image" content="@yield('meta_image')">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('meta_title', \App\Models\Setting::getValue('store_name', 'Z-Pets Store') . ' — Egypt\'s Pet Store')">
    <meta name="twitter:description" content="@yield('meta_description', 'Shop pet food, toys, accessories and more.')">
    @hasSection('meta_image')
    <meta name="twitter:image" content="@yield('meta_image')">
    @endif

    @php
        $schemaAddress = \App\Models\Setting::getValue('shop_address', 'Cairo, Egypt');
        $schemaPhone   = \App\Models\Setting::getValue('whatsapp_number', '');
        $schemaName    = \App\Models\Setting::getValue('store_name', 'Z-Pets Store');
    @endphp
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "PetStore",
      "name": "{{ e($schemaName) }}",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "{{ e($schemaAddress) }}",
        "addressCountry": "EG"
      },
      "telephone": "+{{ e($schemaPhone) }}",
      "url": "{{ url('/') }}"
    }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-white text-gray-900">

    <!-- ── TOPBAR (delivery note) ── -->
    @php
        $deliveryNote = \App\Models\Setting::getValue('delivery_note', 'Free delivery on orders over 2000 EGP');
        $storeName    = \App\Models\Setting::getValue('store_name', 'Z-Pets Store');
    @endphp
    <div class="bg-brand-green-dark text-white text-center py-2 text-xs sm:text-sm font-medium tracking-wide">
        🚚 {{ $deliveryNote }}
    </div>

    <!-- ── MAIN NAVBAR ── -->
    <header class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">

          {{-- LOGO --}}
          <a href="{{ route('home') }}"
             class="flex items-center gap-2 text-brand-green-dark font-extrabold
                    text-xl tracking-tight shrink-0 hover:opacity-90 transition-opacity">
            <span class="text-2xl leading-none">🐾</span>
            <span class="hidden sm:inline">{{ $storeName }}</span>
          </a>

          {{-- DESKTOP NAV --}}
          @php
            $allNavCats  = $navCategories ?? collect();
          @endphp

          <nav class="hidden lg:flex items-center gap-1 text-sm font-medium
                      text-gray-700 flex-1 justify-center">

            {{-- Fixed link 1: Home --}}
            <a href="{{ route('home') }}"
               class="flex items-center gap-1.5 px-3 py-2 rounded-lg
                      transition-all duration-150 hover:bg-green-50 hover:text-brand-green
                      {{ request()->routeIs('home') ? 'text-brand-green bg-green-50 font-semibold' : '' }}">
              <i class="fa-solid fa-house text-xs"></i> Home
            </a>

            {{-- Fixed link 2: All Products --}}
            <a href="{{ route('products.index') }}"
               class="flex items-center gap-1.5 px-3 py-2 rounded-lg
                      transition-all duration-150 hover:bg-green-50 hover:text-brand-green
                      {{ request()->routeIs('products.*') ? 'text-brand-green bg-green-50 font-semibold' : '' }}">
              <i class="fa-solid fa-store text-xs"></i> All Products
            </a>

            {{-- Fixed link 3: Categories dropdown --}}
            <div class="relative" id="cats-dropdown-wrapper">
              <button id="cats-dropdown-btn"
                      type="button"
                      class="flex items-center gap-1.5 px-3 py-2 rounded-lg
                             transition-all duration-150 hover:bg-green-50 hover:text-brand-green
                             font-medium
                             {{ request()->is('category/*') ? 'text-brand-green bg-green-50 font-semibold' : 'text-gray-700' }}">
                <i class="fa-solid fa-paw text-xs"></i>
                Categories
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200"
                   id="cats-chevron"></i>
              </button>
              <div id="cats-dropdown-menu"
                   class="absolute top-full left-0 mt-2 w-52 bg-white rounded-xl
                          shadow-xl border border-gray-100 py-2 hidden z-50">
                @forelse($allNavCats as $navCat)
                <a href="{{ route('category.show', $navCat->slug) }}"
                   class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700
                          hover:bg-green-50 hover:text-brand-green transition-colors
                          {{ request()->is('category/'.$navCat->slug.'*') ? 'text-brand-green bg-green-50 font-semibold' : '' }}">
                  <i class="fa-solid fa-paw text-xs text-brand-green/60"></i>
                  {{ $navCat->name }}
                </a>
                @empty
                <p class="px-4 py-3 text-xs text-gray-400 italic">No categories yet.</p>
                @endforelse
              </div>
            </div>

          </nav>

          {{-- RIGHT: SEARCH + CART + HAMBURGER --}}
          <div class="flex items-center gap-2 shrink-0">

            {{-- Search (desktop) --}}
            <form action="{{ route('search') }}" method="GET"
                  class="hidden lg:flex items-center relative">
              <i class="fa-solid fa-magnifying-glass absolute left-3 text-gray-400 text-xs pointer-events-none"></i>
              <input type="text" name="q" value="{{ request('q') }}"
                     placeholder="Search products..."
                     class="text-sm pl-8 pr-4 py-2 border border-gray-200 rounded-full
                            focus:outline-none focus:border-brand-green focus:ring-2
                            focus:ring-brand-green/20 w-40 lg:w-52 transition-all
                            bg-gray-50 focus:bg-white">
            </form>

            {{-- Cart --}}
            <a href="{{ route('cart.index') }}"
               class="relative flex items-center gap-1.5 px-3 py-2 rounded-lg
                      text-gray-700 hover:bg-green-50 hover:text-brand-green
                      transition-all duration-150 font-medium text-sm">
              <i class="fa-solid fa-cart-shopping text-base"></i>
              <span class="hidden sm:inline">Cart</span>
              @php $cartCount = \App\Helpers\Cart::count(); @endphp
              <span id="cart-count-badge"
                    class="absolute -top-1 -right-1 bg-brand-green text-white text-xs
                           font-bold rounded-full min-w-[18px] h-[18px] flex items-center
                           justify-center px-1 {{ $cartCount === 0 ? 'hidden' : '' }}">
                {{ $cartCount ?: '' }}
              </span>
            </a>

            {{-- Mobile Hamburger --}}
            <button id="mobile-menu-btn"
                    class="lg:hidden flex items-center justify-center w-9 h-9 rounded-lg
                           text-gray-700 hover:bg-green-50 hover:text-brand-green
                           transition-all duration-150">
              <i class="fa-solid fa-bars text-base" id="hamburger-icon"></i>
            </button>

          </div>
        </div>
      </div>

      {{-- MOBILE MENU --}}
      <div id="mobile-menu"
           class="hidden lg:hidden border-t border-gray-100 bg-white
                  px-4 py-4 space-y-1 shadow-md">

        <a href="{{ route('home') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                  text-gray-700 hover:bg-green-50 hover:text-brand-green transition-colors
                  {{ request()->routeIs('home') ? 'bg-green-50 text-brand-green font-semibold' : '' }}">
          <i class="fa-solid fa-house w-4 text-center text-brand-green"></i> Home
        </a>

        <a href="{{ route('products.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                  text-gray-700 hover:bg-green-50 hover:text-brand-green transition-colors
                  {{ request()->routeIs('products.*') ? 'bg-green-50 text-brand-green font-semibold' : '' }}">
          <i class="fa-solid fa-store w-4 text-center text-brand-green"></i> All Products
        </a>

        @foreach($allNavCats as $navCat)
        <a href="{{ route('category.show', $navCat->slug) }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                  text-gray-700 hover:bg-green-50 hover:text-brand-green transition-colors
                  {{ request()->is('category/'.$navCat->slug.'*') ? 'bg-green-50 text-brand-green font-semibold' : '' }}">
          <i class="fa-solid fa-paw w-4 text-center text-brand-green"></i>
          {{ $navCat->name }}
        </a>
        @endforeach

        {{-- Mobile Search --}}
        <form action="{{ route('search') }}" method="GET"
              class="flex items-center border border-gray-200 rounded-xl overflow-hidden
                     mt-3 bg-gray-50">
          <i class="fa-solid fa-magnifying-glass px-3 text-gray-400 text-sm"></i>
          <input type="text" name="q" value="{{ request('q') }}"
                 placeholder="Search products..."
                 class="flex-1 text-sm px-2 py-3 bg-transparent
                        focus:ring-0 focus:outline-none">
          <button type="submit"
                  class="px-4 py-3 bg-brand-green text-white text-sm hover:bg-brand-green-dark
                         transition-colors font-medium">
            Search
          </button>
        </form>
      </div>
    </header>

    <!-- ── FLASH MESSAGES ── -->
    @if(session('success'))
        <div id="flash-msg" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-50 border-l-4 border-brand-green p-4 rounded-r-lg flex items-center justify-between shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-brand-green mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-green-800 font-medium">{{ session('success') }}</p>
                </div>
                <button onclick="document.getElementById('flash-msg').style.display='none'" class="text-green-600 hover:text-green-800 ml-4">✕</button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div id="flash-msg-err" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg flex items-center justify-between shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-red-800 font-medium">{{ session('error') }}</p>
                </div>
                <button onclick="document.getElementById('flash-msg-err').style.display='none'" class="text-red-600 hover:text-red-800 ml-4">✕</button>
            </div>
        </div>
    @endif

    <!-- ── PAGE CONTENT ── -->
    <main>
        @yield('content')
    </main>

    <!-- ── FOOTER ── -->
    @php
        $shopAddress     = \App\Models\Setting::getValue('shop_address', '');
        $socialFacebook  = \App\Models\Setting::getValue('social_facebook', '');
        $socialInstagram = \App\Models\Setting::getValue('social_instagram', '');
        $socialTiktok    = \App\Models\Setting::getValue('social_tiktok', '');
    @endphp

    <footer class="bg-brand-green-dark text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10">

                {{-- Brand + Social --}}
                <div class="sm:col-span-2 lg:col-span-1">
                    <h3 class="text-xl font-bold mb-3 flex items-center gap-2">
                        🐾 {{ $storeName }}
                    </h3>
                    <p class="text-gray-300 text-sm leading-relaxed mb-5">
                        {{ \App\Models\Setting::getValue('store_tagline', "Egypt's Favourite Pet Store") }}
                    </p>

                    {{-- Address --}}
                    @if($shopAddress)
                    <div class="flex items-start gap-2 text-sm text-gray-300 mb-5">
                        <i class="fa-solid fa-location-dot text-brand-green-light mt-0.5 shrink-0"></i>
                        <span>{{ $shopAddress }}</span>
                    </div>
                    @endif

                    {{-- Social Icons --}}
                    @if($socialFacebook || $socialInstagram || $socialTiktok)
                    <div class="flex items-center gap-3">
                        @if($socialFacebook)
                        <a href="{{ $socialFacebook }}" target="_blank" rel="noopener noreferrer"
                           title="Follow us on Facebook"
                           class="w-9 h-9 rounded-lg bg-white/10 hover:bg-blue-600
                                  flex items-center justify-center transition-all duration-200
                                  hover:scale-110">
                            <i class="fa-brands fa-facebook text-white text-base"></i>
                        </a>
                        @endif
                        @if($socialInstagram)
                        <a href="{{ $socialInstagram }}" target="_blank" rel="noopener noreferrer"
                           title="Follow us on Instagram"
                           class="w-9 h-9 rounded-lg bg-white/10 hover:bg-pink-600
                                  flex items-center justify-center transition-all duration-200
                                  hover:scale-110">
                            <i class="fa-brands fa-instagram text-white text-base"></i>
                        </a>
                        @endif
                        @if($socialTiktok)
                        <a href="{{ $socialTiktok }}" target="_blank" rel="noopener noreferrer"
                           title="Follow us on TikTok"
                           class="w-9 h-9 rounded-lg bg-white/10 hover:bg-gray-900
                                  flex items-center justify-center transition-all duration-200
                                  hover:scale-110">
                            <i class="fa-brands fa-tiktok text-white text-base"></i>
                        </a>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="font-bold text-white mb-4 uppercase tracking-wider text-xs">
                        Quick Links
                    </h4>
                    <ul class="space-y-2.5 text-sm text-gray-300">
                        <li>
                            <a href="{{ route('home') }}"
                               class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-chevron-right text-xs text-brand-green-light"></i>
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index') }}"
                               class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-chevron-right text-xs text-brand-green-light"></i>
                                All Products
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('cart.index') }}"
                               class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-chevron-right text-xs text-brand-green-light"></i>
                                My Cart
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('search') }}"
                               class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-chevron-right text-xs text-brand-green-light"></i>
                                Search
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Categories --}}
                <div>
                    <h4 class="font-bold text-white mb-4 uppercase tracking-wider text-xs">
                        Categories
                    </h4>
                    <ul class="space-y-2.5 text-sm text-gray-300">
                        @forelse($navCategories ?? [] as $navCat)
                        <li>
                            <a href="{{ route('category.show', $navCat->slug) }}"
                               class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-paw text-xs text-brand-green-light"></i>
                                {{ $navCat->name }}
                            </a>
                        </li>
                        @empty
                        <li class="text-gray-500 text-xs">No categories yet.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="font-bold text-white mb-4 uppercase tracking-wider text-xs">
                        Contact Us
                    </h4>
                    @php $whatsapp = \App\Models\Setting::getValue('whatsapp_number', ''); @endphp
                    @if($whatsapp)
                    <a href="https://wa.me/{{ $whatsapp }}" target="_blank"
                       class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-400
                              text-white font-bold px-4 py-2.5 rounded-xl transition-all
                              duration-200 text-sm hover:scale-105 mb-4">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                        Chat on WhatsApp
                    </a>
                    @endif
                    @if($shopAddress)
                    <p class="text-xs text-gray-400 mt-3 leading-relaxed">
                        <i class="fa-solid fa-location-dot mr-1 text-brand-green-light"></i>
                        {{ $shopAddress }}
                    </p>
                    @endif
                </div>

            </div>

            {{-- Bottom bar --}}
            <div class="border-t border-white/10 mt-10 pt-6
                        flex flex-col sm:flex-row items-center
                        justify-between gap-4 text-sm text-gray-400">
                <span>© {{ date('Y') }} {{ $storeName }}. All rights reserved.</span>
                @if($socialFacebook || $socialInstagram || $socialTiktok)
                <div class="flex items-center gap-3">
                    @if($socialFacebook)
                    <a href="{{ $socialFacebook }}" target="_blank"
                       class="hover:text-white transition-colors">
                        <i class="fa-brands fa-facebook"></i>
                    </a>
                    @endif
                    @if($socialInstagram)
                    <a href="{{ $socialInstagram }}" target="_blank"
                       class="hover:text-white transition-colors">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    @endif
                    @if($socialTiktok)
                    <a href="{{ $socialTiktok }}" target="_blank"
                       class="hover:text-white transition-colors">
                        <i class="fa-brands fa-tiktok"></i>
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </footer>

    <script>
      // Mobile menu
      const mobileMenuBtn = document.getElementById('mobile-menu-btn');
      const mobileMenu    = document.getElementById('mobile-menu');
      const hamburgerIcon = document.getElementById('hamburger-icon');

      mobileMenuBtn?.addEventListener('click', function () {
        const isOpen = !mobileMenu.classList.contains('hidden');
        mobileMenu.classList.toggle('hidden');
        hamburgerIcon.className = isOpen
          ? 'fa-solid fa-bars text-base'
          : 'fa-solid fa-xmark text-base';
      });

      // Categories dropdown
      const catsBtn     = document.getElementById('cats-dropdown-btn');
      const catsMenu    = document.getElementById('cats-dropdown-menu');
      const catsChevron = document.getElementById('cats-chevron');

      catsBtn?.addEventListener('click', function (e) {
        e.stopPropagation();
        const isOpen = !catsMenu.classList.contains('hidden');
        catsMenu.classList.toggle('hidden');
        if (catsChevron) {
          catsChevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
        }
      });

      document.addEventListener('click', function () {
        catsMenu?.classList.add('hidden');
        if (catsChevron) catsChevron.style.transform = 'rotate(0deg)';
      });
    </script>

    @stack('scripts')

    <script>
    (function(){
      document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
      });
      document.addEventListener('keydown', function(e) {
        if (e.keyCode === 123) { e.preventDefault(); return false; }
        if (e.ctrlKey && e.shiftKey && [73, 74, 67].includes(e.keyCode)) {
          e.preventDefault(); return false;
        }
        if (e.ctrlKey && e.keyCode === 85) {
          e.preventDefault(); return false;
        }
      });
      setInterval(function() { console.clear(); }, 1000);
    })();
    </script>
</body>
</html>
