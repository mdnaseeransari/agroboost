@props(['title' => null, 'icon' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-300']) }}>
    @if($title || $icon)
    <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
            @if($icon)
                <span class="text-agro-green">
                    {!! $icon !!}
                </span>
            @endif
            {{ $title }}
        </h3>
        @if(isset($action))
            <div>{{ $action }}</div>
        @endif
    </div>
    @endif
    
    <div class="px-6 py-6">
        {{ $slot }}
    </div>
</div>
