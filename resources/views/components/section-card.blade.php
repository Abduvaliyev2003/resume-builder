{{--
  <x-section-card> — Collapsible section card wrapper for builder form sections.

  WHY THIS COMPONENT EXISTS:
  The builder form had repeated patterns: a header with title + action button,
  a list of items, and an empty state message. Extracting this into a component
  makes the builder cleaner and allows consistent section styling across all tabs.

  USAGE:
    <x-section-card title="Work Experience" icon="fa-briefcase" @add="addJob()">
        ...items...
        <x-slot name="empty">No experience records added yet.</x-slot>
    </x-section-card>
--}}

@props([
    'title'    => 'Section',
    'icon'     => 'fa-list',
    'addLabel' => 'Add',
    'addEvent' => null,
])

<div class="flex flex-col gap-5">
    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-850 pb-2">
        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
            <i class="{{ $icon }} text-primary-500 text-xs"></i>
            {{ $title }}
        </h3>
        @if($addEvent)
        <button
            type="button"
            @click="{{ $addEvent }}"
            class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1 transition"
        >
            <i class="fa-solid fa-plus"></i> {{ $addLabel }}
        </button>
        @endif
    </div>

    <div class="flex flex-col gap-6">
        {{ $slot }}
    </div>
</div>
