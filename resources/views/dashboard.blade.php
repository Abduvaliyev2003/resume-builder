@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div x-data="dashboardController()" class="flex flex-col gap-8">
    
    <!-- Top Greeting and CTA -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold font-outfit text-slate-900 dark:text-slate-100 flex items-center gap-2">
                <span>{{ __('app.hello') }},</span>
                <span class="text-primary-600" x-text="userName">User</span>
                <span class="animate-bounce">👋</span>
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Optimize and manage your professional career representations.</p>
        </div>
        <div>
            <a href="/templates" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-bold shadow-md hover:shadow-lg transition-all active:scale-[0.98]">
                <i class="fa-solid fa-plus"></i>
                <span>{{ __('app.create_new') }}</span>
            </a>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Card 1 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-primary-50 dark:bg-primary-950/20 text-primary-600 dark:text-primary-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-file-invoice"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('app.total_resumes') }}</p>
                <h3 class="text-2xl font-black font-outfit mt-0.5" x-text="stats.total_resumes">{{ $stats['total_resumes'] }}</h3>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-gauge-high"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('app.avg_ats_score') }}</p>
                <h3 class="text-2xl font-black font-outfit mt-0.5"><span x-text="stats.average_score">{{ $stats['average_score'] }}</span>%</h3>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/20 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-download"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('app.sim_exports') }}</p>
                <h3 class="text-2xl font-black font-outfit mt-0.5" x-text="stats.total_exports">{{ $stats['total_exports'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Resume Listing -->
    <div>
        <h2 class="text-lg font-bold font-outfit text-slate-900 dark:text-slate-100 mb-4">{{ __('app.your_resumes') }}</h2>
        
        @if($resumes->isEmpty())
            <!-- Empty State -->
            <div class="bg-white dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-3xl p-12 text-center flex flex-col items-center justify-center gap-4">
                <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-slate-850 flex items-center justify-center text-3xl text-slate-400">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ __('app.no_resumes') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto mt-1">Kickstart your job search by designing a new resume layout using our custom-tuned templates.</p>
                </div>
                <a href="/templates" class="mt-2 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-bold shadow-sm transition">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>{{ __('app.choose_template') }}</span>
                </a>
            </div>
        @else
            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($resumes as $resume)
                    <x-resume-card :resume="$resume" />
                @endforeach
            </div>
        @endif
    </div>

    <!-- Confirm Delete Modal -->
    <x-modal id="delete-resume" title="Delete Resume">
        <p class="text-sm text-slate-600 dark:text-slate-400">
            Are you sure you want to delete <strong class="text-slate-900 dark:text-slate-100" x-text="resumeToDelete.title"></strong>? This action is permanent and will destroy all version histories and AI reports linked to it.
        </p>
        <x-slot name="footer">
            <x-button variant="outline" @click="$dispatch('close-modal-delete-resume')">Cancel</x-button>
            <x-button variant="danger" @click="confirmDelete" ::disabled="deleteLoading">
                <span x-show="!deleteLoading">Delete Permanently</span>
                <span x-show="deleteLoading" x-cloak class="flex items-center gap-1.5">
                    <i class="fa-solid fa-spinner animate-spin"></i> Deleting...
                </span>
            </x-button>
        </x-slot>
    </x-modal>

    <!-- PDF Export Loading Spinner Modal -->
    <x-modal id="exporting-loader" title="Generating Document">
        <div class="flex flex-col items-center justify-center py-6 text-center gap-4">
            <div class="w-14 h-14 rounded-full border-4 border-primary-100 border-t-primary-600 animate-spin"></div>
            <div>
                <h4 class="font-bold text-slate-900 dark:text-slate-100">Compiling with LaTeX/PDF engine...</h4>
                <p class="text-xs text-slate-500 mt-1">This will simulate formatting and package asset compilation.</p>
            </div>
        </div>
    </x-modal>

</div>
@endsection

@section('scripts')
<script>
    requireAuth();

    function dashboardController() {
        return {
            userName: 'Developer',
            stats: {
                total_resumes: {{ $stats['total_resumes'] }},
                average_score: {{ $stats['average_score'] }},
                total_exports: {{ $stats['total_exports'] }}
            },
            resumeToDelete: { id: null, title: '' },
            deleteLoading: false,

            async init() {
                // Load User Details from API to get actual Name
                try {
                    const res = await fetch('/api/me', {
                        headers: getAuthHeaders()
                    });
                    if (res.ok) {
                        const data = await res.json();
                        this.userName = data.user.name;
                    }
                } catch (e) {
                    // Fail gracefully
                }

                // Global listeners
                window.addEventListener('confirm-delete', (e) => {
                    this.resumeToDelete = { id: e.detail.id, title: e.detail.title };
                    this.$dispatch('open-modal-delete-resume');
                });

                window.addEventListener('export-pdf', async (e) => {
                    const resumeId = e.detail.id;
                    this.$dispatch('open-modal-exporting-loader');
                    
                    try {
                        const response = await fetch(`/api/resumes/${resumeId}/export`, {
                            method: 'POST',
                            headers: getAuthHeaders(),
                            body: JSON.stringify({ file_type: 'pdf' })
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Export failed.');
                        }

                        showToast('PDF compiled successfully! Initializing download...', 'success');
                        
                        // Increment export count in dashboard view local stats state
                        this.stats.total_exports++;
                        
                        // Download the file
                        setTimeout(() => {
                            window.location.href = data.file.download_url;
                        }, 800);

                    } catch (err) {
                        showToast(err.message, 'error');
                    } finally {
                        this.$dispatch('close-modal-exporting-loader');
                    }
                });
            },

            async confirmDelete() {
                this.deleteLoading = true;
                try {
                    const response = await fetch(`/api/resumes/${this.resumeToDelete.id}`, {
                        method: 'DELETE',
                        headers: getAuthHeaders()
                    });

                    if (!response.ok) {
                        const data = await response.json();
                        throw new Error(data.message || 'Failed to delete resume.');
                    }

                    showToast('Resume deleted successfully!', 'success');
                    this.$dispatch('close-modal-delete-resume');
                    
                    // Reload page to refresh list and stats
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);

                } catch (err) {
                    showToast(err.message, 'error');
                } finally {
                    this.deleteLoading = false;
                }
            }
        }
    }
</script>
@endsection
