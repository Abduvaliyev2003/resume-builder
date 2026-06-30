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
        $sections = $resume->sections->keyBy('section_type');
        $contact = $sections['contact']?->content ?? [];
        $summary = $sections['summary']?->content ?? [];
        $skills = $sections['skills']?->content ?? [];
        $experience = $sections['experience']?->content ?? [];
        $education = $sections['education']?->content ?? [];
        $certifications = $sections['certifications']?->content ?? [];
        $languages = $sections['languages']?->content ?? [];

        $templateStyle = $resume->template?->style;
        $layout = $resume->template?->structure['layout'] ?? 'single-column';
        $primaryColor = $resume->template?->structure['colors']['primary'] ?? '#2563eb';
        $accentColor = $resume->template?->structure['colors']['accent'] ?? '#60a5fa';

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

    <div class="resume-sheet max-w-4xl mx-auto w-full bg-white text-slate-900 shadow-xl rounded-3xl border border-slate-200/50 p-12 select-text text-[13px] leading-relaxed" style="{{ $fontStyle }}">

        <!-- Render layout by layout type -->
        @if($layout === 'left-curved-sidebar')
            <!-- CIRCULAR -->
            <div class="flex gap-8">
                <!-- Left circular header / Accent Column -->
                <div class="w-1/3 border-r border-slate-100 pr-6 flex flex-col gap-4">
                    <div class="pb-4 border-b border-slate-100">
                        <div class="w-16 h-16 rounded-full bg-blue-900 text-white flex items-center justify-center font-extrabold text-xl mb-4 overflow-hidden">
                            @if(!empty($contact['photo']))
                                <img src="{{ $contact['photo'] }}" alt="Profile photo" class="w-full h-full object-cover">
                            @else
                                <span>{{ !empty($contact['name']) ? substr($contact['name'], 0, 1) : 'J' }}</span>
                            @endif
                        </div>
                        <h2 class="font-extrabold text-blue-950 text-base">{{ $contact['name'] ?? 'Your Name' }}</h2>
                        <p class="text-xs text-blue-700 font-semibold mt-0.5">{{ $contact['title'] ?? 'Job Title' }}</p>
                    </div>
                    <div class="flex flex-col gap-2 text-[11px] text-slate-600">
                        <p><i class="fa-regular fa-envelope mr-1.5 text-blue-900"></i>{{ $contact['email'] ?? 'email@example.com' }}</p>
                        <p><i class="fa-solid fa-phone mr-1.5 text-blue-900"></i>{{ $contact['phone'] ?? 'Phone' }}</p>
                        <p><i class="fa-solid fa-location-dot mr-1.5 text-blue-900"></i>{{ $contact['address'] ?? 'Location' }}</p>
                    </div>

                    @if(!empty($skills['list']))
                        <div class="mt-4">
                            <h4 class="font-extrabold text-[12px] text-blue-950 uppercase mb-2">Skills</h4>
                            <div class="flex flex-col gap-1.5">
                                @foreach($skills['list'] as $sk)
                                    <div class="flex items-center justify-between">
                                        <span class="text-[11px]">{{ $sk }}</span>
                                        <div class="flex gap-0.5">
                                            <span class="w-1.5 h-1.5 bg-blue-900 rounded-full"></span>
                                            <span class="w-1.5 h-1.5 bg-blue-900 rounded-full"></span>
                                            <span class="w-1.5 h-1.5 bg-blue-900 rounded-full"></span>
                                            <span class="w-1.5 h-1.5 bg-blue-300 rounded-full"></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right details column -->
                <div class="flex-1 flex flex-col gap-6">
                    <div>
                        <h4 class="font-extrabold text-blue-950 uppercase border-b pb-1 mb-2 text-[12px]">Profile</h4>
                        <p class="text-slate-600">{{ $summary['text'] ?? '' }}</p>
                    </div>

                    @if(!empty($experience['items']))
                    {{-- @dd($experience) --}}
                        <div>
                            <h4 class="font-extrabold text-blue-950 uppercase border-b pb-1 mb-2 text-[12px]">Experience</h4>
                            <div class="flex flex-col gap-4">
                                @foreach($experience['items'] as $job)
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <strong class="text-blue-950 text-xs">{{ $job['role'] ?? "" }}</strong>
                                            <span class="text-[10px] text-slate-500">{{ $job['duration'] ?? "" }}</span>
                                        </div>
                                        <p class="text-[11px] font-semibold text-blue-800">{{ $job['company'] ?? "" }}</p>
                                        <p class="text-slate-600 text-[11px] mt-1">{{ $job['description'] ?? "" }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($education['items']))
                        <div>
                            <h4 class="font-extrabold text-blue-950 uppercase border-b pb-1 mb-2 text-[12px]">Education</h4>
                            <div class="flex flex-col gap-3">
                                @foreach($education['items'] as $edu)
                                    <div>
                                        <div class="flex justify-between items-start">
                                            
                                            <strong class="text-blue-950 text-[11px]">{{ $edu['degree'] ?? '' }}</strong>
                                            <span class="text-[10px] text-slate-500">{{ $edu['year'] ?? '' }}</span>
                                        </div>
                                        <p class="text-[11px] font-semibold text-blue-800">{{ $edu['school'] ?? '' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($certifications['items']))
                        <div>
                            <h4 class="font-extrabold text-blue-950 uppercase border-b pb-1 mb-2 text-[12px]">Certifications</h4>
                            <div class="flex flex-col gap-3">
                                @foreach($certifications['items'] as $certificate)
                                    <div>
                                        <strong class="text-blue-950 text-[11px]">{{ $certificate['name'] ?? '' }}</strong>
                                        <p class="text-[10px] font-semibold text-blue-800">{{ $certificate['organization'] ?? '' }}</p>
                                        <p class="text-slate-600 text-[11px] mt-1">{{ $certificate['issue_date'] ?? '' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        @elseif($layout === 'header-banner-split')
            <!-- PROFESSIONAL -->
            <div class="flex flex-col gap-6">
                <!-- Dark Slate Header -->
                <div class="bg-slate-900 text-white p-6 rounded-2xl flex justify-between items-center gap-4">
                    <div>
                        <h2 class="text-2xl font-bold">{{ $contact['name'] ?? 'Your Name' }}</h2>
                        <p class="text-slate-300 font-semibold">{{ $contact['title'] ?? 'Job Title' }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right text-[11px] text-slate-350 flex flex-col gap-0.5">
                            <p><i class="fa-regular fa-envelope mr-1.5"></i>{{ $contact['email'] ?? '' }}</p>
                            <p><i class="fa-solid fa-phone mr-1.5"></i>{{ $contact['phone'] ?? '' }}</p>
                            <p><i class="fa-solid fa-location-dot mr-1.5"></i>{{ $contact['address'] ?? '' }}</p>
                        </div>
                        @if(!empty($contact['photo']))
                            <img src="{{ $contact['photo'] }}" alt="Profile photo" class="w-16 h-16 rounded-xl object-cover border border-white/20">
                        @endif
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-slate-900 border-b pb-1.5 mb-2 uppercase text-xs">Profile Summary</h4>
                    <p class="text-slate-600">{{ $summary['text'] ?? '' }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-bold text-slate-900 border-b pb-1.5 mb-2 uppercase text-xs">Experience</h4>
                        <div class="flex flex-col gap-4">
                            @foreach($experience['items'] ?? [] as $job)
                                <div>
                                    <div class="flex justify-between items-start">
                                        <strong class="text-slate-900 text-[12px]">{{ $job['role'] }}</strong>
                                        <span class="text-[9px] text-slate-400">{{ $job['duration'] }}</span>
                                    </div>
                                    <p class="text-[10px] text-slate-500">{{ $job['company'] }}</p>
                                    <p class="text-slate-600 text-[11px] mt-1">{{ $job['description'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-col gap-6">
                        @if(!empty($education['items']))
                            <div>
                                <h4 class="font-bold text-slate-900 border-b pb-1.5 mb-2 uppercase text-xs">Education</h4>
                                <div class="flex flex-col gap-3">
                                    @foreach($education['items'] as $edu)
                                        <div>
                                            <div class="flex justify-between">
                                                <strong class="text-slate-900 text-[11px]">{{ $edu['degree'] }}</strong>
                                                <span class="text-[9px] text-slate-400">{{ $edu['year'] }}</span>
                                            </div>
                                            <p class="text-[10px] text-slate-500">{{ $edu['school'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($skills['list']))
                            <div>
                                <h4 class="font-bold text-slate-900 border-b pb-1.5 mb-2 uppercase text-xs">Skills</h4>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($skills['list'] as $sk)
                                        <span class="px-2 py-1 bg-slate-100 text-slate-700 font-bold rounded-md text-[10px]">{{ $sk }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($certifications['items']))
                            <div>
                                <h4 class="font-bold text-slate-900 border-b pb-1.5 mb-2 uppercase text-xs">Certifications</h4>
                                <div class="flex flex-col gap-3">
                                    @foreach($certifications['items'] as $certificate)
                                        <div>
                                            <strong class="text-slate-900 text-[11px]">{{ $certificate['name'] ?? '' }}</strong>
                                            <p class="text-[10px] text-slate-500">{{ $certificate['organization'] ?? '' }}</p>
                                            <p class="text-slate-600 text-[11px] mt-1">{{ $certificate['issue_date'] ?? '' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        @elseif($layout === 'left-vertical-stripe')
            <!-- VERTICAL -->
            <div class="flex gap-8">
                <!-- Left stripe band -->
                <div class="w-3 rounded-full flex-shrink-0" style="background-color: {{ $primaryColor }}"></div>

                <div class="flex-1 flex flex-col gap-5">
                    <div class="pb-4 border-b">
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <h2 class="text-2xl font-black text-slate-900">{{ $contact['name'] ?? '' }}</h2>
                                <p class="font-bold text-sm tracking-wide" style="color: {{ $primaryColor }}">{{ $contact ?? '' }}</p>
                            </div>
                            @if(!empty($contact['photo']))
                                <img src="{{ $contact['photo'] }}" alt="Profile photo" class="w-16 h-16 rounded-xl object-cover border border-slate-200">
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-4 mt-3 text-[11px] text-slate-500">
                            <span><i class="fa-regular fa-envelope mr-1.5" style="color: {{ $primaryColor }}"></i>{{ $contact['email'] ?? '' }}</span>
                            <span><i class="fa-solid fa-phone mr-1.5" style="color: {{ $primaryColor }}"></i>{{ $contact['phone'] ?? '' }}</span>
                            <span><i class="fa-solid fa-location-dot mr-1.5" style="color: {{ $primaryColor }}"></i>{{ $contact['address'] ?? '' }}</span>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-slate-900 uppercase text-xs tracking-wider mb-2" style="color: {{ $primaryColor }}">Professional Summary</h4>
                        <p class="text-slate-600">{{ $summary['text'] ?? '' }}</p>
                    </div>

                    @if(!empty($experience['items']))
                        <div>
                            <h4 class="font-bold text-slate-900 uppercase text-xs tracking-wider mb-3" style="color: {{ $primaryColor }}">Employment Details</h4>
                            <div class="flex flex-col gap-4">
                                @foreach($experience['items'] as $job)
                                    <div class="pl-3 border-l-2 border-slate-200">
                                        <div class="flex justify-between items-start">
                                            <strong class="text-slate-900 text-xs">{{ $job['role'] }}</strong>
                                            <span class="text-[10px] text-slate-400">{{ $job['duration'] }}</span>
                                        </div>
                                        <p class="text-[11px] font-semibold" style="color: {{ $primaryColor }}">{{ $job['company'] }}</p>
                                        <p class="text-slate-650 text-[11px] mt-1">{{ $job['description'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @if(!empty($education['items']))
                            <div>
                                <h4 class="font-bold text-slate-900 uppercase text-xs tracking-wider mb-2" style="color: {{ $primaryColor }}">Education</h4>
                                <div class="flex flex-col gap-3">
                                    @foreach($education['items'] as $edu)
                                        <div class="pl-3 border-l-2 border-slate-200">
                                            <strong class="text-slate-900 text-[11px]">{{ $edu['degree'] ?? '' }}</strong>
                                            <p class="text-[11px] font-semibold" style="color: {{ $primaryColor }}">{{ $edu['school'] ?? '' }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $edu['year'] ?? '' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($certifications['items']))
                            <div>
                                <h4 class="font-bold text-slate-900 uppercase text-xs tracking-wider mb-2" style="color: {{ $primaryColor }}">Certifications</h4>
                                <div class="flex flex-col gap-3">
                                    @foreach($certifications['items'] as $certificate)
                                        <div class="pl-3 border-l-2 border-slate-200">
                                            <strong class="text-slate-900 text-[11px]">{{ $certificate['name'] ?? '' }}</strong>
                                            <p class="text-[10px] font-semibold" style="color: {{ $primaryColor }}">{{ $certificate['organization'] ?? '' }}</p>
                                            <p class="text-slate-600 text-[11px] mt-1">{{ $certificate['issue_date'] ?? '' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        @else
            <!-- FALLBACK DEFAULT STYLES -->
            <div class="flex flex-col gap-6">
                <div class="border-b pb-4 flex justify-between items-start gap-4">
                    <div>
                        <h2 class="text-3xl font-extrabold text-slate-900">{{ $contact['name'] ?? 'Your Name' }}</h2>
                        <p class="text-primary-650 font-bold text-sm">{{ $contact['title'] ?? 'Title' }}</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="text-right text-[11px] text-slate-500 flex flex-col gap-0.5">
                            <p>{{ $contact['email'] ?? '' }}</p>
                            <p>{{ $contact['phone'] ?? '' }}</p>
                            <p>{{ $contact['address'] ?? '' }}</p>
                        </div>
                        @if(!empty($contact['photo']))
                            <img src="{{ $contact['photo'] }}" alt="Profile photo" class="w-16 h-16 rounded-xl object-cover border border-slate-200">
                        @endif
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-slate-900 uppercase border-b pb-1 mb-2">Summary</h4>
                    <p class="text-slate-650">{{ $summary['text'] ?? '' }}</p>
                </div>

                @if(!empty($experience['items']))
                    <div>
                        <h4 class="font-bold text-slate-900 uppercase border-b pb-1 mb-3">Experience</h4>
                        <div class="flex flex-col gap-4">
                            @foreach($experience['items'] as $job)
                                <div>
                                    <div class="flex justify-between font-bold text-slate-900">
                                        <span>{{ $job['role'] ?? "" }}</span>
                                        <span class="text-xs text-slate-400 font-normal">{{ $job['duration'] ?? "" }}</span>
                                    </div>
                                    <p class="text-xs text-primary-600 font-semibold">{{ $job['company']  ?? ""  }}</p>
                                    <p class="text-slate-650 mt-1">{{ $job['description'] ?? ""}}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @if(!empty($education['items']))
                        <div>
                            <h4 class="font-bold text-slate-900 uppercase border-b pb-1 mb-3">Education</h4>
                            <div class="flex flex-col gap-3">
                                @foreach($education['items'] as $edu)
                                    <div>
                                        <div class="flex justify-between font-bold text-slate-900">
                                            <span>{{ $edu['degree'] ?? '' }}</span>
                                            <span class="text-xs text-slate-400 font-normal">{{ $edu['year'] ?? '' }}</span>
                                        </div>
                                        <p class="text-xs text-primary-600 font-semibold">{{ $edu['school'] ?? '' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($certifications['items']))
                        <div>
                            <h4 class="font-bold text-slate-900 uppercase border-b pb-1 mb-3">Certifications</h4>
                            <div class="flex flex-col gap-3">
                                @foreach($certifications['items'] as $certificate)
                                    <div>
                                        <strong class="text-slate-900">{{ $certificate['name'] ?? '' }}</strong>
                                        <p class="text-xs text-primary-600 font-semibold">{{ $certificate['organization'] ?? '' }}</p>
                                        <p class="text-slate-600 mt-1">{{ $certificate['issue_date'] ?? '' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

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
                        window.open(data.file.download_url, '_blank');
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
