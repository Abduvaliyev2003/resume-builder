@props([
    'resume',
])

<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md hover:border-primary-500/30 dark:hover:border-primary-500/20 transition-all duration-200 flex flex-col justify-between">
    <div>
        <!-- Top bar with score -->
        <div class="flex items-start justify-between gap-4 mb-3">
            <h3 class="text-base font-bold font-outfit truncate text-slate-900 dark:text-slate-100" title="{{ $resume->title }}">
                {{ $resume->title }}
            </h3>
            
            @php
                $score = $resume->score ?? 0;
                $scoreColor = 'bg-rose-50 text-rose-700 dark:bg-rose-950/20 dark:text-rose-400 border-rose-100 dark:border-rose-900/30';
                if ($score >= 70) {
                    $scoreColor = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900/30';
                } elseif ($score >= 40) {
                    $scoreColor = 'bg-amber-50 text-amber-700 dark:bg-amber-950/20 dark:text-amber-400 border-amber-100 dark:border-amber-900/30';
                }
            @endphp
            
            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $scoreColor }}">
                <i class="fa-solid fa-gauge-high"></i>
                <span>{{ $score }}%</span>
            </div>
        </div>

        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 flex items-center gap-1.5">
            <i class="fa-regular fa-clock"></i>
            <span>Updated {{ $resume->updated_at ? \Carbon\Carbon::parse($resume->updated_at)->diffForHumans() : 'just now' }}</span>
        </p>

        <!-- Template Style Indicator -->
        <div class="flex items-center gap-2 mb-6">
            <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-lg">
                <i class="fa-solid fa-palette mr-1"></i>
                {{ ucfirst($resume->template?->name ?? 'Default') }}
            </span>
        </div>
    </div>

    <!-- Actions grid -->
    <div class="grid grid-cols-2 gap-2 border-t border-slate-100 dark:border-slate-850 pt-4">
        <a href="/resumes/{{ $resume->id }}/builder" class="flex items-center justify-center gap-1.5 px-3 py-2 bg-primary-50 hover:bg-primary-100 dark:bg-primary-950/20 dark:hover:bg-primary-950/40 text-primary-600 dark:text-primary-400 rounded-xl text-xs font-bold transition">
            <i class="fa-solid fa-pen-to-square"></i>
            <span>Edit</span>
        </a>
        <a href="/resumes/{{ $resume->id }}/preview" class="flex items-center justify-center gap-1.5 px-3 py-2 bg-slate-50 hover:bg-slate-100 dark:bg-slate-850 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold transition">
            <i class="fa-solid fa-eye"></i>
            <span>Preview</span>
        </a>
        <button @click="$dispatch('export-pdf', { id: '{{ $resume->id }}' })" class="flex items-center justify-center gap-1.5 px-3 py-2 bg-slate-50 hover:bg-slate-100 dark:bg-slate-850 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold transition">
            <i class="fa-solid fa-file-pdf"></i>
            <span>PDF</span>
        </button>
        <button @click="$dispatch('confirm-delete', { id: @js($resume->id), title: @js($resume->title) })" class="flex items-center justify-center gap-1.5 px-3 py-2 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 rounded-xl text-xs font-bold transition">
            <i class="fa-solid fa-trash-can"></i>
            <span>Delete</span>
        </button>
    </div>
</div>
