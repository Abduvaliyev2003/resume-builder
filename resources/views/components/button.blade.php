@props([
    'variant' => 'primary',
    'type' => 'button',
    'icon' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-150 active:scale-[0.98]';
    
    $variants = [
        'primary' => 'bg-primary-600 hover:bg-primary-700 text-white focus:ring-primary-500',
        'secondary' => 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 focus:ring-slate-400',
        'danger' => 'bg-rose-600 hover:bg-rose-700 text-white focus:ring-rose-500',
        'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white focus:ring-emerald-500',
        'outline' => 'bg-transparent border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-850 text-slate-700 dark:text-slate-200 focus:ring-slate-400',
    ];
    
    $variantClass = $variants[$variant] ?? $variants['primary'];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClasses $variantClass"]) }}>
    @if($icon)
        <i class="{{ $icon }}"></i>
    @endif
    {{ $slot }}
</button>
