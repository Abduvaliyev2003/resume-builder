{{--
  <x-datepicker> — Month/Year Date Range Picker with "Currently Working Here" toggle.

  WHY THIS COMPONENT EXISTS:
  Previously, experience and education used a single free-text `duration` field
  (e.g. "Jan 2024 - Present"). This had several problems:
  1. Inconsistent formats ("Jan 2024", "January 2024", "01/2024") broke display.
  2. "Present" had to be typed manually — no automation.
  3. No validation possible (can't compare dates if they're strings).

  WHAT THIS SOLVES:
  - Structured month/year dropdowns enforce a consistent format.
  - `is_present` checkbox disables the end date automatically.
  - Data is stored as "YYYY-MM" strings, formatted for display by ResumeTemplateRenderer.

  HOW TO USE:
    <x-datepicker
        prefix="job"
        :index="$index"
        model-prefix="experience.items[index]"
        :show-present="true"
    />

  ALPINE.JS BINDING:
  Each item stores: start_date, end_date, is_present
  The preview uses the helper `formatDateRange(item)` to show the human-readable string.
--}}

@props([
    'prefix'       => 'item',
    'modelPrefix'  => 'item',
    'showPresent'  => true,
    'label'        => 'Date Range',
])

@php
    $months = [
        '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
        '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug',
        '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec',
    ];
    $currentYear = date('Y');
    $years = range($currentYear + 2, 1980);
@endphp

<div class="flex flex-col gap-3">
    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $label }}</label>

    {{-- Start Date --}}
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Start Month</label>
            <select
                x-model="{{ $modelPrefix }}.start_month"
                @change="
                    if ({{ $modelPrefix }}.start_month && {{ $modelPrefix }}.start_year) {
                        {{ $modelPrefix }}.start_date = {{ $modelPrefix }}.start_year + '-' + {{ $modelPrefix }}.start_month;
                    }
                    triggerAutoSave();
                "
                class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition text-sm"
            >
                <option value="">Month</option>
                @foreach($months as $value => $name)
                    <option value="{{ $value }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Start Year</label>
            <select
                x-model="{{ $modelPrefix }}.start_year"
                @change="
                    if ({{ $modelPrefix }}.start_month && {{ $modelPrefix }}.start_year) {
                        {{ $modelPrefix }}.start_date = {{ $modelPrefix }}.start_year + '-' + {{ $modelPrefix }}.start_month;
                    }
                    triggerAutoSave();
                "
                class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition text-sm"
            >
                <option value="">Year</option>
                @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- "Currently working here" checkbox --}}
    @if($showPresent)
    <label class="flex items-center gap-2 cursor-pointer select-none">
        <input
            type="checkbox"
            x-model="{{ $modelPrefix }}.is_present"
            @change="
                if ({{ $modelPrefix }}.is_present) {
                    {{ $modelPrefix }}.end_date = null;
                    {{ $modelPrefix }}.end_month = '';
                    {{ $modelPrefix }}.end_year = '';
                }
                triggerAutoSave();
            "
            class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500"
        >
        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">I currently work here</span>
    </label>
    @endif

    {{-- End Date (disabled when is_present) --}}
    <div class="grid grid-cols-2 gap-3" :class="{{ $modelPrefix }}.is_present ? 'opacity-40 pointer-events-none' : ''">
        <div>
            <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">
                End Month
                <template x-if="{{ $modelPrefix }}.is_present"><span class="text-emerald-600 font-bold ml-1">Present</span></template>
            </label>
            <select
                x-model="{{ $modelPrefix }}.end_month"
                :disabled="{{ $modelPrefix }}.is_present"
                @change="
                    if ({{ $modelPrefix }}.end_month && {{ $modelPrefix }}.end_year) {
                        {{ $modelPrefix }}.end_date = {{ $modelPrefix }}.end_year + '-' + {{ $modelPrefix }}.end_month;
                    }
                    triggerAutoSave();
                "
                class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition text-sm"
            >
                <option value="">Month</option>
                @foreach($months as $value => $name)
                    <option value="{{ $value }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">End Year</label>
            <select
                x-model="{{ $modelPrefix }}.end_year"
                :disabled="{{ $modelPrefix }}.is_present"
                @change="
                    if ({{ $modelPrefix }}.end_month && {{ $modelPrefix }}.end_year) {
                        {{ $modelPrefix }}.end_date = {{ $modelPrefix }}.end_year + '-' + {{ $modelPrefix }}.end_month;
                    }
                    triggerAutoSave();
                "
                class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition text-sm"
            >
                <option value="">Year</option>
                @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
