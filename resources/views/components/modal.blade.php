@props([
    'id',
    'title' => null,
])

<div x-data="{ open: false }" 
     x-show="open"
     @open-modal-{{ $id }}.window="open = true"
     @close-modal-{{ $id }}.window="open = false"
     @keydown.escape.window="open = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" 
         x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"></div>

    <!-- Modal Card Wrapper -->
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 p-6 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100 dark:border-slate-800"
             x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <!-- Header -->
            <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                @if($title)
                    <h3 class="text-lg font-bold font-outfit text-slate-900 dark:text-slate-100">{{ $title }}</h3>
                @else
                    <div></div>
                @endif
                <button @click="open = false" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="mt-4 text-sm text-slate-600 dark:text-slate-400">
                {{ $slot }}
            </div>

            <!-- Footer -->
            @if(isset($footer))
                <div class="mt-6 flex justify-end gap-3 pt-3 border-t border-slate-200 dark:border-slate-800">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
