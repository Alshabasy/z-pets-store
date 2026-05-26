@props(['type' => 'success', 'message'])

@php
    $colors = [
        'success' => 'bg-green-50 text-green-800 border-green-200',
        'error' => 'bg-red-50 text-red-800 border-red-200',
        'warning' => 'bg-amber-50 text-amber-800 border-amber-200',
    ];
    $colorClass = $colors[$type] ?? $colors['success'];
@endphp

<div class="relative flex items-center p-4 mb-4 border rounded-lg {{ $colorClass }} alert-dismissible transition-opacity duration-300 shadow-sm" role="alert">
    <div class="flex-1 font-medium">{{ $message }}</div>
    <button type="button" class="ml-4 -mx-1.5 -my-1.5 rounded-lg p-1.5 inline-flex h-8 w-8 hover:bg-black/5 focus:outline-none" aria-label="Close" onclick="this.parentElement.style.opacity='0'; setTimeout(() => this.parentElement.remove(), 300);">
        <span class="sr-only">Close</span>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
    </button>
</div>

<script>
    // Auto dismiss alerts after 4 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert-dismissible').forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 4000);
</script>
