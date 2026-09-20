@props(['name', 'label' => null, 'type' => 'text', 'icon' => null, 'placeholder' => '', 'value' => '', 'required' => false])

<div class="space-y-2">
    @if($label)
        <label for="{{ $name }}" class="block font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">
            {{ $label }}
            @if($required) <span class="text-error">*</span> @endif
        </label>
    @endif
    
    <div class="relative flex items-center">
        @if($icon)
            <span class="absolute left-4 text-on-surface-variant pointer-events-none">
                <span class="material-symbols-outlined text-xl">{{ $icon }}</span>
            </span>
        @endif
        
        <input 
            type="{{ $type }}" 
            id="{{ $name }}" 
            name="{{ $name }}"
            {{ $attributes->merge([
                'class' => 'w-full py-3.5 bg-surface rounded-xl border border-outline-variant text-on-surface font-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-outline ' . ($icon ? 'pl-12 pr-4' : 'px-4'),
                'placeholder' => $placeholder,
                'value' => old($name, $value),
                'required' => $required ? 'required' : null
            ]) }}
        />
    </div>
    
    @error($name)
        <p class="text-error text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
