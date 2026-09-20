<div {{ $attributes->merge(['class' => 'bg-surface-container-lowest border border-outline-variant/50 rounded-xl shadow-soft flex flex-col']) }}>
    @if(isset($header))
        <div class="px-6 py-4 border-b border-outline-variant/30 flex justify-between items-center {{ $headerClass ?? '' }}">
            {{ $header }}
        </div>
    @endif
    
    <div class="p-6 flex-1 {{ $contentClass ?? '' }}">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="px-6 py-4 border-t border-outline-variant/30 bg-surface/30 {{ $footerClass ?? '' }}">
            {{ $footer }}
        </div>
    @endif
</div>
