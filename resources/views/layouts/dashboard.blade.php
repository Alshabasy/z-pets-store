<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'Z-Pets Store') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Backdrop -->
        <div id="sidebar-backdrop" class="fixed inset-0 z-20 bg-gray-900 bg-opacity-50 lg:hidden hidden"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 max-w-[85vw] bg-brand-green-dark text-white transition-transform duration-300 -translate-x-full lg:static lg:translate-x-0 flex flex-col shadow-xl">
            <!-- Logo Area -->
            <div class="flex items-center justify-between h-16 bg-brand-green-dark border-b border-white/10 shrink-0 px-4">
                <a href="{{ route('dashboard.index') }}" class="text-xl font-bold tracking-wider flex items-center gap-2">
                    <span class="text-2xl">🐾</span> Z-Pets Admin
                </a>
                <button id="close-sidebar-btn" class="lg:hidden text-gray-300 hover:text-white">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('dashboard.index') ? 'bg-brand-green border-l-4 border-white font-medium text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-chart-line w-5 text-center mr-3"></i>
                    Dashboard
                </a>

                <a href="{{ route('dashboard.products.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('dashboard.products.*') && !request()->routeIs('dashboard.products.create') ? 'bg-brand-green border-l-4 border-white font-medium text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-box-open w-5 text-center mr-3"></i>
                    Products
                </a>

                <a href="{{ route('dashboard.products.create') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('dashboard.products.create') ? 'bg-brand-green border-l-4 border-white font-medium text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-circle-plus w-5 text-center mr-3"></i>
                    Add Product
                </a>

                <a href="{{ route('dashboard.categories.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('dashboard.categories.*') ? 'bg-brand-green border-l-4 border-white font-medium text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-tags w-5 text-center mr-3"></i>
                    Categories
                </a>

                <a href="{{ route('dashboard.banners.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('dashboard.banners.*') ? 'bg-brand-green border-l-4 border-white font-medium text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-image w-5 text-center mr-3"></i>
                    Banners
                </a>

                <a href="{{ route('dashboard.orders.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('dashboard.orders.*') ? 'bg-brand-green border-l-4 border-white font-medium text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center">
                        <i class="fa-solid fa-bag-shopping w-5 text-center mr-3"></i>
                        Orders
                    </div>
                    @if($newOrdersCount > 0)
                        <span class="bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $newOrdersCount }}</span>
                    @endif
                </a>

                <a href="{{ route('dashboard.settings.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('dashboard.settings.*') ? 'bg-brand-green border-l-4 border-white font-medium text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-gear w-5 text-center mr-3"></i>
                    Settings
                </a>
            </nav>

            <!-- Bottom User Profile -->
            <div class="p-4 border-t border-white/10 shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center overflow-hidden">
                        <div class="w-8 h-8 rounded-full bg-white text-brand-green-dark flex items-center justify-center font-bold text-sm shrink-0">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="ml-3 truncate">
                            <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-gray-400 truncate">Admin</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Log Out"
                                class="text-gray-400 hover:text-white transition-colors p-1">
                          <i class="fa-solid fa-right-from-bracket text-base"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden bg-gray-50">
            <!-- Topbar -->
            <header class="h-16 sm:h-20 bg-white shadow-sm border-b border-gray-100 flex items-center justify-between px-4 sm:px-6 lg:px-10 shrink-0 z-10">
                <div class="flex items-center min-w-0">
                    <button id="open-sidebar-btn" class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-brand-green bg-gray-50 rounded-xl lg:hidden mr-4 transition-colors shrink-0">
                        <i class="fa-solid fa-bars-staggered text-lg"></i>
                    </button>
                    <div class="min-w-0">
                        <h1 class="text-lg sm:text-2xl font-black text-gray-900 uppercase tracking-widest leading-none truncate max-w-[180px] sm:max-w-none">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-[10px] font-black text-gray-300 uppercase tracking-[0.2em] mt-1 hidden sm:block">Z-Pets Admin Control Panel</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-outline btn-sm">
                        <i class="fa-solid fa-arrow-up-right-from-square mr-2"></i>
                        Live Store
                    </a>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 overflow-auto p-3 sm:p-5 lg:p-8">
                @if (session('success'))
                    <div id="flash-success" class="mb-10 bg-green-50 border-2 border-brand-green/20 p-5 rounded-2xl shadow-xl shadow-brand-green/5 animate-on-scroll">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-brand-green rounded-xl flex items-center justify-center mr-4">
                                    <i class="fa-solid fa-check text-white"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-brand-green uppercase tracking-widest">Success</h4>
                                    <p class="text-sm text-gray-600 font-medium">{{ session('success') }}</p>
                                </div>
                            </div>
                            <button onclick="document.getElementById('flash-success').style.display='none'" class="w-8 h-8 flex items-center justify-center text-brand-green/40 hover:text-brand-green hover:bg-brand-green/5 rounded-lg transition-all">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div id="flash-error" class="mb-10 bg-red-50 border-2 border-red-100 p-5 rounded-2xl shadow-xl shadow-red-500/5 animate-on-scroll">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fa-solid fa-circle-exclamation text-white"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-red-600 uppercase tracking-widest">Error</h4>
                                    <p class="text-sm text-gray-600 font-medium">{{ session('error') }}</p>
                                </div>
                            </div>
                            <button onclick="document.getElementById('flash-error').style.display='none'" class="w-8 h-8 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const openBtn = document.getElementById('open-sidebar-btn');
            const closeBtn = document.getElementById('close-sidebar-btn');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }

            openBtn.addEventListener('click', openSidebar);
            closeBtn.addEventListener('click', closeSidebar);
            backdrop.addEventListener('click', closeSidebar);
        });
    </script>
    @stack('scripts')
</body>
</html>
