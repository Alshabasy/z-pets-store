@props(['items'])

<nav class="flex text-gray-500 text-sm font-medium mb-4" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        @foreach($items as $item)
            <li class="inline-flex items-center">
                @if(!$loop->first)
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                @endif
                
                @if($loop->last || empty($item['url']))
                    <span class="text-gray-400 cursor-default">{{ $item['label'] }}</span>
                @else
                    <a href="{{ $item['url'] }}" class="hover:text-brand-green transition-colors">{{ $item['label'] }}</a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
