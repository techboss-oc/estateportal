@props(['title', 'value', 'icon' => null, 'trend' => null, 'color' => 'primary'])

@php
    $colorOptions = [
        'primary' => 'text-primary bg-primary-fixed',
        'secondary' => 'text-secondary bg-secondary-fixed',
        'tertiary' => 'text-tertiary bg-tertiary-fixed',
        'success' => 'text-green-700 bg-green-50',
        'danger' => 'text-error bg-error-container',
    ];
    $iconColorClasses = $colorOptions[$color] ?? $colorOptions['primary'];
    $bgTintClass = explode(' ', $iconColorClasses)[0] ?? 'text-primary';
    $bgTintClass = str_replace('text-', 'bg-', $bgTintClass);
@endphp

<div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group shadow-soft bg-surface-container-lowest border border-outline-variant/30">
    <div class="absolute -right-4 -top-4 w-24 h-24 {{ $bgTintClass }}/5 rounded-full blur-xl group-hover:{{ $bgTintClass }}/10 transition-colors"></div>
    
    <div class="flex items-center justify-between z-10">
        <span class="font-label-caps text-label-caps text-on-surface-variant uppercase">{{ $title }}</span>
        @if($icon)
            <div class="w-8 h-8 rounded-full {{ $iconColorClasses }} flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>
            </div>
        @endif
    </div>
    
    <div class="z-10 flex items-baseline gap-2">
        <span class="font-display-lg text-display-lg text-on-surface leading-tight">{{ $value }}</span>
        @if($trend)
            <span class="font-label-caps text-label-caps {{ strpos($trend, '+') !== false || strpos($trend, 'Up') !== false ? 'text-green-600' : 'text-error' }}">
                {{ $trend }}
            </span>
        @endif
    </div>
</div>
