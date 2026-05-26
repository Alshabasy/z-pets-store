<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Login — Z-Pets Store</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
  @vite(['resources/css/app.css'])
</head>
<body class="antialiased min-h-screen flex"
      style="font-family:'Plus Jakarta Sans',sans-serif;">

  {{-- LEFT PANEL (decorative — hidden on mobile) --}}
  <div class="hidden lg:flex lg:w-1/2 xl:w-3/5 bg-brand-green-dark
              flex-col items-center justify-center relative overflow-hidden p-12">

    {{-- Background pattern --}}
    <div class="absolute inset-0 opacity-10"
         style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0);
                background-size: 32px 32px;"></div>

    {{-- Large decorative paw --}}
    <div class="absolute -bottom-16 -right-16 text-white/5 text-[320px] leading-none
                select-none pointer-events-none">🐾</div>

    {{-- Content --}}
    <div class="relative z-10 text-center max-w-md">
      <div class="text-7xl mb-6 animate-bounce" style="animation-duration:3s;">🐾</div>
      <h1 class="text-4xl xl:text-5xl font-black text-white mb-4 leading-tight">
        Z-Pets Store
      </h1>
      <p class="text-green-200 text-lg font-medium leading-relaxed">
        Admin Control Panel — Manage your store, products, orders and settings from one place.
      </p>

      {{-- Feature pills --}}
      <div class="flex flex-wrap justify-center gap-3 mt-10">
        <span class="flex items-center gap-2 bg-white/10 text-white/90 text-sm
                     font-semibold px-4 py-2 rounded-full backdrop-blur-sm">
          <i class="fa-solid fa-box-open text-xs"></i> Products
        </span>
        <span class="flex items-center gap-2 bg-white/10 text-white/90 text-sm
                     font-semibold px-4 py-2 rounded-full backdrop-blur-sm">
          <i class="fa-solid fa-bag-shopping text-xs"></i> Orders
        </span>
        <span class="flex items-center gap-2 bg-white/10 text-white/90 text-sm
                     font-semibold px-4 py-2 rounded-full backdrop-blur-sm">
          <i class="fa-solid fa-chart-line text-xs"></i> Analytics
        </span>
        <span class="flex items-center gap-2 bg-white/10 text-white/90 text-sm
                     font-semibold px-4 py-2 rounded-full backdrop-blur-sm">
          <i class="fa-solid fa-gear text-xs"></i> Settings
        </span>
      </div>
    </div>
  </div>

  {{-- RIGHT PANEL (login form) --}}
  <div class="w-full lg:w-1/2 xl:w-2/5 flex items-center justify-center
              bg-gray-50 px-6 py-12 sm:px-12">
    <div class="w-full max-w-md">

      {{-- Mobile logo (shown only on mobile) --}}
      <div class="lg:hidden text-center mb-8">
        <div class="text-5xl mb-3">🐾</div>
        <h1 class="text-2xl font-black text-brand-green-dark">Z-Pets Store</h1>
        <p class="text-gray-500 text-sm mt-1">Admin Control Panel</p>
      </div>

      {{-- Card --}}
      <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 sm:p-10">

        <div class="mb-8">
          <h2 class="text-2xl font-black text-gray-900">Welcome back</h2>
          <p class="text-gray-500 text-sm mt-1 font-medium">
            Sign in to manage your store
          </p>
        </div>

        {{-- Session status --}}
        @if (session('status'))
          <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl
                      text-sm text-green-700 font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-green-500"></i>
            {{ session('status') }}
          </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
          @csrf

          {{-- Email --}}
          <div>
            <label for="email" class="form-label">
              Email Address
            </label>
            <div class="relative">
              <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2
                         text-gray-400 text-sm pointer-events-none"></i>
              <input id="email" type="email" name="email"
                     value="{{ old('email') }}"
                     required autofocus autocomplete="username"
                     placeholder="admin@zpets.com"
                     class="form-input !pl-10
                            @error('email') border-red-400 focus:border-red-500
                            focus:ring-red-500/20 @enderror">
            </div>
            @error('email')
              <p class="mt-1.5 text-xs text-red-600 font-semibold flex items-center gap-1">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
              </p>
            @enderror
          </div>

          {{-- Password --}}
          <div>
            <label for="password" class="form-label">
              Password
            </label>
            <div class="relative">
              <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2
                         text-gray-400 text-sm pointer-events-none"></i>
              <input id="password" type="password" name="password"
                     required autocomplete="current-password"
                     placeholder="••••••••"
                     class="form-input !pl-10 !pr-12
                            @error('password') border-red-400 @enderror">
              <button type="button" id="toggle-password"
                      class="absolute right-3.5 top-1/2 -translate-y-1/2
                             text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-eye text-sm" id="pwd-eye-icon"></i>
              </button>
            </div>
            @error('password')
              <p class="mt-1.5 text-xs text-red-600 font-semibold flex items-center gap-1">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
              </p>
            @enderror
          </div>

          {{-- Remember me --}}
          <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
              <input id="remember_me" type="checkbox" name="remember"
                     class="w-4 h-4 rounded border-gray-300 text-brand-green
                            focus:ring-brand-green cursor-pointer">
              <span class="text-sm text-gray-600 font-medium">Remember me</span>
            </label>
          </div>

          {{-- Submit --}}
          <button type="submit"
                  class="btn btn-primary btn-lg w-full mt-2">
            <i class="fa-solid fa-right-to-bracket mr-2"></i>
            Sign In to Dashboard
          </button>

        </form>

      </div>

      {{-- Back to store link --}}
      <div class="text-center mt-6">
        <a href="{{ route('home') }}"
           class="text-sm text-gray-400 hover:text-brand-green transition-colors
                  font-medium flex items-center justify-center gap-1.5">
          <i class="fa-solid fa-arrow-left text-xs"></i>
          Back to store
        </a>
      </div>

    </div>
  </div>

  <script>
    // Toggle password visibility
    const toggleBtn  = document.getElementById('toggle-password');
    const pwdInput   = document.getElementById('password');
    const pwdEyeIcon = document.getElementById('pwd-eye-icon');
    toggleBtn?.addEventListener('click', function() {
      const isHidden = pwdInput.type === 'password';
      pwdInput.type  = isHidden ? 'text' : 'password';
      pwdEyeIcon.className = isHidden
        ? 'fa-solid fa-eye-slash text-sm'
        : 'fa-solid fa-eye text-sm';
    });
  </script>

</body>
</html>
