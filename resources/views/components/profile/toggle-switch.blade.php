{{--
  <x-profile.toggle-switch> — Animated iOS-style toggle switch.

  Props:
    - name: form field name
    - id: input id (for label association)
    - checked: boolean
    - label: visible label text
    - description: optional helper text
--}}
@props([
    'name',
    'id'          => null,
    'checked'     => false,
    'label'       => '',
    'description' => null,
])

@php $inputId = $id ?? $name; @endphp

<div class="flex items-start justify-between gap-4 py-3">
    <div class="flex-1 min-w-0">
        <label for="{{ $inputId }}" class="text-sm font-medium text-slate-800 dark:text-slate-200 cursor-pointer">{{ $label }}</label>
        @if($description)
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $description }}</p>
        @endif
    </div>
    <div x-data="{ checked: {{ $checked ? 'true' : 'false' }} }" class="flex-shrink-0">
        <input type="hidden" name="{{ $name }}" :value="checked ? '1' : '0'">
        <button type="button"
                id="{{ $inputId }}"
                @click="checked = !checked"
                :class="checked ? 'bg-primary-600' : 'bg-slate-200 dark:bg-slate-700'"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
                role="switch"
                :aria-checked="checked">
            <span :class="checked ? 'translate-x-5' : 'translate-x-0'"
                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
        </button>
    </div>
</div>
