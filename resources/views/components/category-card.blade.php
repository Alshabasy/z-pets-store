@props(['category'])

<a href="{{ route('category.show', $category->slug) }}" class="group block relative rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 aspect-square">
    <img src="{{ asset('storage/' . ($category->image ?? 'images/placeholder.jpg')) }}" alt="{{ $category->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent group-hover:from-brand-green/90 group-hover:via-brand-green/40 transition-colors duration-300"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <h3 class="text-white text-2xl font-bold text-center tracking-wide group-hover:scale-105 transition-transform duration-300">{{ $category->name }}</h3>
    </div>
</a>
