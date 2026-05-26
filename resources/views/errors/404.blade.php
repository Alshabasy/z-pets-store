@extends('layouts.app')

@section('meta_title', '404 — Page Not Found | Z-Pets Store')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-16">
    <div class="text-center max-w-lg mx-auto">
        <!-- Background 404 number -->
        <div class="relative mb-6 select-none">
            <span class="text-[10rem] leading-none font-bold text-brand-green opacity-10 block">404</span>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-6xl">🐾</span>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-3">Page Not Found</h1>
        <p class="text-gray-500 text-base mb-8">
            The page you're looking for doesn't exist or has been moved.
        </p>

        <!-- Search Bar -->
        <form action="{{ route('search') }}" method="GET" class="flex items-center border border-gray-300 rounded-xl overflow-hidden shadow-sm mb-6 focus-within:border-brand-green focus-within:ring-2 focus-within:ring-brand-green/20 transition-all">
            <input
                type="text"
                name="q"
                placeholder="Search for products..."
                class="flex-1 px-4 py-3 text-sm text-gray-700 border-none focus:ring-0 outline-none"
            >
            <button type="submit" class="bg-brand-green hover:bg-brand-green-dark text-white px-5 py-3 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </form>

        <a href="{{ route('home') }}" class="inline-flex items-center bg-brand-green hover:bg-brand-green-dark text-white font-bold px-8 py-3 rounded-xl transition-colors shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Go to Homepage
        </a>
    </div>
</div>
@endsection
