@props([
    'label' => null,
    'name',
    'placeholder' => '',
    'rows' => 4,
    'required' => false,
    'model' => null,
])

<div class="w-full">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
            {{ $label }}
            @if($required) <span class="text-rose-500">*</span> @endif
        </label>
    @endif
    <textarea 
        id="{{ $name }}" 
        name="{{ $name }}" 
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($model) x-model="{{ $model }}" @endif
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150'
        ]) }}
    ></textarea>
</div>
