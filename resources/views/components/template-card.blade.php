@props([
    'template',
])

<div class="relative group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm hover:shadow-xl hover:border-primary-500/30 dark:hover:border-primary-500/20 transition-all duration-350 flex flex-col justify-between overflow-hidden">
    <!-- Visual Representation of the template (since we don't have images, we make a beautiful CSS mockup representation that matches the actual structure!) -->
    @php
        $layout = $template->structure['layout'] ?? 'single-column';
        $primaryColor = $template->structure['colors']['primary'] ?? '#2563eb';
        $accentColor = $template->structure['colors']['accent'] ?? '#60a5fa';
    @endphp
    
    <div class="relative h-52 rounded-xl bg-slate-100 dark:bg-slate-950 p-4 mb-4 border border-slate-200/50 dark:border-slate-800/50 overflow-hidden transition duration-300 group-hover:scale-[1.02]">
        
        <!-- Hover Zoom In Overlay -->
        <div class="absolute inset-0 bg-primary-600/5 dark:bg-primary-500/5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none duration-300"></div>
        <div class="absolute top-2 right-2 z-10 rounded-md bg-white/90 dark:bg-slate-900/90 px-2 py-1 text-[10px] font-black uppercase tracking-wide text-slate-500 shadow-sm">
            {{ $template->style }}
        </div>

        <!-- Render Layout Preview CSS Mockups -->
        @if($layout === 'left-curved-sidebar')
            <div class="mx-auto h-full max-w-[150px] bg-white dark:bg-slate-900 shadow-sm border border-slate-200/70 dark:border-slate-800 flex">
                <div class="w-1/3 p-2 text-white" style="background-color: {{ $primaryColor }}">
                    <div class="w-7 h-7 rounded-full bg-white/25 mb-3"></div>
                    <div class="h-1.5 bg-white/70 rounded mb-1"></div>
                    <div class="h-1 bg-white/40 rounded mb-4"></div>
                    <div class="space-y-1">
                        <div class="h-1 bg-white/50 rounded"></div>
                        <div class="h-1 bg-white/50 rounded w-4/5"></div>
                        <div class="h-1 bg-white/50 rounded w-3/5"></div>
                    </div>
                </div>
                <div class="flex-1 p-2">
                    <div class="h-2.5 rounded w-3/4 mb-2" style="background-color: {{ $accentColor }}"></div>
                    <div class="space-y-1 mb-3">
                        <div class="h-1 bg-slate-200 dark:bg-slate-700 rounded"></div>
                        <div class="h-1 bg-slate-200 dark:bg-slate-700 rounded w-5/6"></div>
                    </div>
                    <div class="h-px bg-slate-200 dark:bg-slate-700 mb-2"></div>
                    <div class="space-y-1">
                        <div class="h-1.5 bg-slate-300 dark:bg-slate-600 rounded w-1/2"></div>
                        <div class="h-1 bg-slate-200 dark:bg-slate-700 rounded"></div>
                        <div class="h-1 bg-slate-200 dark:bg-slate-700 rounded w-4/5"></div>
                    </div>
                </div>
            </div>
        @elseif($layout === 'header-banner-split')
            <div class="mx-auto h-full max-w-[150px] bg-white dark:bg-slate-900 shadow-sm border border-slate-200/70 dark:border-slate-800 p-2">
                <div class="h-9 rounded-md w-full mb-3 flex items-center justify-between px-2" style="background-color: {{ $primaryColor }}">
                    <div class="space-y-1 flex-1">
                        <div class="h-1.5 bg-white/80 rounded w-2/3"></div>
                        <div class="h-1 bg-white/40 rounded w-1/2"></div>
                    </div>
                    <div class="w-7 h-7 rounded-md bg-white/20"></div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div class="space-y-1">
                        <div class="h-1.5 bg-slate-300 dark:bg-slate-600 rounded w-2/3"></div>
                        <div class="h-1 bg-slate-200 dark:bg-slate-700 rounded"></div>
                        <div class="h-1 bg-slate-200 dark:bg-slate-700 rounded w-5/6"></div>
                    </div>
                    <div class="space-y-1">
                        <div class="h-1.5 bg-slate-300 dark:bg-slate-600 rounded w-2/3"></div>
                        <div class="h-1 bg-slate-200 dark:bg-slate-700 rounded"></div>
                        <div class="h-1 bg-slate-200 dark:bg-slate-700 rounded w-4/5"></div>
                    </div>
                </div>
            </div>
        @elseif($layout === 'left-vertical-stripe')
            <div class="mx-auto h-full max-w-[150px] bg-white dark:bg-slate-900 shadow-sm border border-slate-200/70 dark:border-slate-800 p-3 flex gap-2">
                <div class="w-1.5 rounded-full" style="background-color: {{ $primaryColor }}"></div>
                <div class="flex-1">
                    <div class="h-3 bg-slate-300 dark:bg-slate-600 w-2/3 rounded mb-1.5"></div>
                    <div class="h-1.5 rounded w-1/2 mb-3" style="background-color: {{ $accentColor }}"></div>
                    <div class="space-y-1 mb-3">
                        <div class="h-1 bg-slate-200 dark:bg-slate-700 rounded"></div>
                        <div class="h-1 bg-slate-200 dark:bg-slate-700 rounded w-4/5"></div>
                    </div>
                    <div class="h-px bg-slate-200 dark:bg-slate-700 mb-2"></div>
                    <div class="space-y-1">
                        <div class="h-1 bg-slate-200 dark:bg-slate-700 rounded"></div>
                        <div class="h-1 bg-slate-200 dark:bg-slate-700 rounded w-3/4"></div>
                    </div>
                </div>
            </div>
        @elseif($layout === 'asymmetrical-header')
            <div class="flex flex-col h-full gap-2">
                <div class="flex gap-2 items-center">
                    <div class="w-8 h-8 rounded-full bg-slate-300 dark:bg-slate-700"></div>
                    <div class="h-4 rounded-sm flex-1" style="background-color: {{ $primaryColor }}"></div>
                </div>
                <div class="flex-1 flex flex-col gap-1.5">
                    <div class="h-2 bg-slate-200 dark:bg-slate-800 w-full rounded-sm"></div>
                    <div class="h-2 bg-slate-200 dark:bg-slate-800 w-5/6 rounded-sm"></div>
                </div>
            </div>
        @else
            <!-- Standard Sidebar / Grid Mockup -->
            <div class="mx-auto h-full max-w-[150px] bg-white dark:bg-slate-900 shadow-sm border border-slate-200/70 dark:border-slate-800 p-3">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="h-3 bg-slate-300 dark:bg-slate-600 rounded w-16 mb-1"></div>
                        <div class="h-1.5 rounded w-12" style="background-color: {{ $primaryColor }}"></div>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-slate-200 dark:bg-slate-700"></div>
                </div>
                <div class="space-y-1.5">
                    <div class="h-1 bg-slate-200 dark:bg-slate-700 rounded"></div>
                    <div class="h-1 bg-slate-200 dark:bg-slate-700 rounded w-5/6"></div>
                    <div class="h-px bg-slate-200 dark:bg-slate-700 my-2"></div>
                    <div class="h-1.5 rounded w-1/2" style="background-color: {{ $accentColor }}"></div>
                    <div class="h-1 bg-slate-200 dark:bg-slate-700 rounded"></div>
                </div>
            </div>
        @endif
    </div>

    <!-- Info & Select Button -->
    <div>
        <h3 class="text-base font-bold font-outfit text-slate-900 dark:text-slate-100 mb-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-250">
            {{ $template->name }}
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mb-5 min-h-[32px] line-clamp-2">
            {{ $template->description }}
        </p>

        <div class="flex items-center justify-between gap-3 border-t border-slate-100 dark:border-slate-850 pt-4">
            <!-- Palette swatches -->
            <div class="flex gap-1">
                <span class="w-3.5 h-3.5 rounded-full border border-white dark:border-slate-900 shadow-sm" style="background-color: {{ $primaryColor }}"></span>
                <span class="w-3.5 h-3.5 rounded-full border border-white dark:border-slate-900 shadow-sm" style="background-color: {{ $accentColor }}"></span>
            </div>
            
            <button @click="$dispatch('select-template', { id: '{{ $template->id }}' })" 
                    class="px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-primary-600 hover:bg-primary-700 text-white shadow-sm transition">
                Use Template
            </button>
        </div>
    </div>
</div>
