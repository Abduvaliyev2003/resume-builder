@extends('layouts.app')

@section('title', 'Preview Resume')

@section('styles')
<style>
    /* Print Styles to allow browser Ctrl+P printing cleanly */
    @media print {
        nav, .action-bar { display: none !important; }
        body { background-color: white !important; }
        .resume-sheet {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
    }
</style>
@endsection

@section('content')
<div x-data="resumePreview()" class="flex flex-col gap-6">

    <!-- Top Action Bar (hidden on printing) -->
    <div class="action-bar bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            @if(isset($isShared) && $isShared)
                <span class="text-xs font-bold px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 rounded-lg">
                    <i class="fa-solid fa-globe mr-1"></i> Public View
                </span>
            @else
                <a href="/dashboard" class="flex items-center gap-1.5 px-3.5 py-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl text-xs font-bold transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Dashboard</span>
                </a>
                <a href="/resumes/{{ $resume->id }}/builder" class="flex items-center gap-1.5 px-3.5 py-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl text-xs font-bold transition">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Back to Builder</span>
                </a>
            @endif
        </div>

        <div class="flex items-center gap-2">
            <!-- Share Link Button -->
            <button @click="copyShareLink()" class="flex items-center gap-1.5 px-3.5 py-2 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-705 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition">
                <i class="fa-solid fa-share-nodes"></i>
                <span x-text="shareText">Copy Share Link</span>
            </button>

            <!-- Browser Print Button -->
            <button @click="window.print()" class="flex items-center gap-1.5 px-3.5 py-2 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-705 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition">
                <i class="fa-solid fa-print"></i>
                <span>Print</span>
            </button>

            <!-- LaTeX compilation Export PDF -->
            <button @click="exportPDF()" class="flex items-center gap-1.5 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold shadow-sm transition" :disabled="exporting">
                <template x-if="!exporting">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-file-pdf"></i>
                        <span>Download PDF</span>
                    </span>
                </template>
                <template x-if="exporting">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-spinner animate-spin"></i>
                        <span>Compiling...</span>
                    </span>
                </template>
            </button>
        </div>
    </div>

    <!-- Resume Layout Output Frame -->
    @php
        $templateStyle = $resume->template?->style;
        $fontStyle = 'font-family: Inter, sans-serif;';
        if ($templateStyle === 'professional') {
            $fontStyle = 'font-family: Outfit, sans-serif;';
        } elseif ($templateStyle === 'vertical') {
            $fontStyle = 'font-family: Roboto, sans-serif;';
        } elseif ($templateStyle === 'elegant') {
            $fontStyle = 'font-family: Merriweather, serif;';
        } elseif ($templateStyle === 'modern') {
            $fontStyle = 'font-family: Nunito, sans-serif;';
        } elseif ($templateStyle === 'luxurious') {
            $fontStyle = 'font-family: Playfair Display, serif;';
        }
    @endphp

    <div class="resume-sheet max-w-4xl mx-auto w-full bg-white text-slate-900 shadow-xl rounded-3xl border border-slate-200/50 p-0 overflow-hidden select-text text-[13px] leading-relaxed" style="{{ $fontStyle }}">
        {!! $renderedHtml !!}
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Only check auth if not a public shared route
    @if(!isset($isShared) || !$isShared)
        requireAuth();
    @endif

    function resumePreview() {
        return {
            resumeId: '{{ $resume->id }}',
            exporting: false,
            shareText: 'Copy Share Link',

            async exportPDF() {
                this.exporting = true;
                showToast('Compiling document with LaTeX engine...', 'info');

                try {
                    const response = await fetch(`/api/resumes/${this.resumeId}/export`, {
                        method: 'POST',
                        headers: getAuthHeaders(),
                        body: JSON.stringify({ file_type: 'pdf' })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to export document.');
                    }

                    showToast('LaTeX compiled successfully!', 'success');

                    setTimeout(() => {
                        window.location.href = data.file.download_url;
                    }, 500);

                } catch (e) {
                    showToast(e.message, 'error');
                } finally {
                    this.exporting = false;
                }
            },

            copyShareLink() {
                const link = `${window.location.origin}/resumes/shared/${this.resumeId}`;

                navigator.clipboard.writeText(link).then(() => {
                    this.shareText = 'Copied!';
                    showToast('Public link copied to clipboard!', 'success');

                    setTimeout(() => {
                        this.shareText = 'Copy Share Link';
                    }, 2000);
                }).catch(err => {
                    showToast('Failed to copy link.', 'error');
                });
            }
        }
    }
</script>
@endsection
