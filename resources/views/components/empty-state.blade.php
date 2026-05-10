@props(['icon', 'title', 'message', 'actionText' => null, 'actionUrl' => '#'])

<div class="flex flex-col items-center justify-center p-12 bg-white rounded-2xl border border-dashed border-gray-300 text-center">
    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-400">
        {!! $icon !!}
    </div>
    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $title }}</h3>
    <p class="text-gray-500 mb-6 max-w-md">{{ $message }}</p>
    
    @if($actionText)
        <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-agro-green text-white font-medium rounded-xl hover:bg-agro-green/90 transition shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ $actionText }}
        </a>
    @endif
</div>
