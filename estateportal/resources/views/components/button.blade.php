@props(['variant' => 'primary', 'type' => 'button', 'icon' => null])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-body-sm font-medium py-2.5 px-5 rounded-lg transition-all duration-200 focus:outline-none active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:active:scale-100';
    
    $variants = [
        'primary' => 'bg-primary text-on-primary hover:bg-surface-tint shadow-sm',
        'secondary' => 'bg-surface-container text-on-surface border border-outline-variant/50 hover:bg-surface-container-high',
        'danger' => 'bg-error text-on-error hover:bg-error/90 shadow-sm',
        'ghost' => 'bg-transparent text-primary hover:bg-primary/5',
        'outline' => 'bg-transparent text-primary border border-primary hover:bg-primary/5'
    ];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $variants[$variant]]) }}>
    @if($icon)
        <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>
    @endif
    
    @if($slot->isNotEmpty())
        <span>{{ $slot }}</span>
    @endif
</button>
