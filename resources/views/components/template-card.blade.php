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
    
    <div class="relative h-44 rounded-xl bg-slate-100 dark:bg-slate-950 p-4 mb-4 border border-slate-200/50 dark:border-slate-800/50 overflow-hidden flex flex-col gap-2 transition duration-300 group-hover:scale-[1.02]">
        
        <!-- Hover Zoom In Overlay -->
        <div class="absolute inset-0 bg-primary-600/5 dark:bg-primary-500/5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none duration-300"></div>

        <!-- Render Layout Preview CSS Mockups -->
        @if($layout === 'left-curved-sidebar')
            <div class="flex h-full gap-2">
                <div class="w-1/3 rounded-l-lg rounded-br-2xl h-full" style="background-color: {{ $primaryColor }}"></div>
                <div class="flex-1 flex flex-col gap-1.5">
                    <div class="h-4 bg-slate-300 dark:bg-slate-700 w-2/3 rounded-sm"></div>
                    <div class="h-2 bg-slate-200 dark:bg-slate-800 w-full rounded-sm"></div>
                    <div class="h-2 bg-slate-200 dark:bg-slate-800 w-5/6 rounded-sm"></div>
                </div>
            </div>
        @elseif($layout === 'header-banner-split')
            <div class="flex flex-col h-full gap-2">
                <div class="h-8 rounded-lg w-full" style="background-color: {{ $primaryColor }}"></div>
                <div class="flex-1 flex gap-2">
                    <div class="w-1/2 flex flex-col gap-1.5">
                        <div class="h-2 bg-slate-300 dark:bg-slate-700 w-2/3 rounded-sm"></div>
                        <div class="h-1.5 bg-slate-200 dark:bg-slate-800 w-full rounded-sm"></div>
                    </div>
                    <div class="w-1/2 flex flex-col gap-1.5">
                        <div class="h-2 bg-slate-300 dark:bg-slate-700 w-2/3 rounded-sm"></div>
                        <div class="h-1.5 bg-slate-200 dark:bg-slate-800 w-full rounded-sm"></div>
                    </div>
                </div>
            </div>
        @elseif($layout === 'left-vertical-stripe')
            <div class="flex h-full gap-2">
                <div class="w-1.5 h-full rounded-full" style="background-color: {{ $primaryColor }}"></div>
                <div class="flex-1 flex flex-col gap-1.5">
                    <div class="h-4 bg-slate-300 dark:bg-slate-700 w-1/2 rounded-sm"></div>
                    <div class="h-2 bg-slate-200 dark:bg-slate-800 w-full rounded-sm"></div>
                    <div class="h-2 bg-slate-200 dark:bg-slate-800 w-3/4 rounded-sm"></div>
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
            <div class="flex h-full gap-2">
                <div class="w-1/4 rounded-lg h-full bg-slate-250 dark:bg-slate-800/80"></div>
                <div class="flex-1 flex flex-col gap-1.5">
                    <div class="h-4 rounded-sm w-3/4" style="background-color: {{ $primaryColor }}"></div>
                    <div class="h-2 bg-slate-200 dark:bg-slate-800 w-full rounded-sm"></div>
                    <div class="h-2 bg-slate-200 dark:bg-slate-800 w-5/6 rounded-sm"></div>
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
