@extends('layouts.app')

@section('title', 'AI Analysis Feedback')

@section('content')
<div class="flex flex-col gap-6 max-w-4xl mx-auto">
    
    <!-- Top breadcrumb / navigation -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="/dashboard" class="hover:text-primary-650 font-medium">Dashboard</a>
            <span>/</span>
            <a href="/resumes/{{ $resume->id }}/builder" class="hover:text-primary-650 font-medium">{{ $resume->title }}</a>
            <span>/</span>
            <span class="text-slate-900 dark:text-slate-100 font-semibold">AI Feedback</span>
        </div>
        
        <a href="/resumes/{{ $resume->id }}/builder" class="flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-350 rounded-xl text-xs font-bold transition">
            <i class="fa-solid fa-pen-to-square"></i>
            <span>Return to Editor</span>
        </a>
    </div>

    <!-- Main Score Card Overview -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-850 rounded-3xl p-8 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            @php
                $score = $resume->score ?? 0;
                $scoreColorClass = 'text-rose-500 border-rose-100 bg-rose-50 dark:bg-rose-950/20';
                if ($score >= 70) {
                    $scoreColorClass = 'text-emerald-500 border-emerald-100 bg-emerald-50 dark:bg-emerald-950/20';
                } elseif ($score >= 40) {
                    $scoreColorClass = 'text-amber-500 border-amber-100 bg-amber-50 dark:bg-amber-950/20';
                }
            @endphp
            
            <div class="w-24 h-24 rounded-full border-4 flex flex-col items-center justify-center font-outfit {{ $scoreColorClass }}">
                <span class="text-3xl font-black">{{ $score }}%</span>
                <span class="text-[9px] uppercase tracking-wider font-extrabold text-slate-400">Score</span>
            </div>
            <div>
                <h1 class="text-2xl font-black font-outfit text-slate-900 dark:text-slate-100">AI ATS Scoring Review</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">This report summarizes all AI checks compiled for "{{ $resume->title }}".</p>
            </div>
        </div>

        <a href="/resumes/{{ $resume->id }}/builder" class="px-5 py-3 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-sm shadow-md transition">
            <i class="fa-solid fa-wand-magic-sparkles mr-1.5"></i> Optimize Sections
        </a>
    </div>

    <!-- AI Reports History List -->
    <div>
        <h2 class="text-lg font-bold font-outfit text-slate-900 dark:text-slate-100 mb-4">Analysis Reports</h2>

        <div class="flex flex-col gap-6">
            @forelse($reviews as $review)
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-850 rounded-2xl p-6 shadow-sm flex flex-col gap-4">
                    <!-- Report Header -->
                    <div class="flex justify-between items-start gap-4 border-b border-slate-100 dark:border-slate-850 pb-3">
                        <div>
                            <span class="px-2.5 py-1 bg-slate-150 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg text-xs font-bold uppercase">
                                {{ str_replace('_', ' ', $review->review_type) }}
                            </span>
                            <span class="text-xs text-slate-400 ml-2">
                                {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
                            </span>
                        </div>
                        
                        <div class="text-sm font-black font-outfit text-primary-600">
                            Score: {{ $review->score }}%
                        </div>
                    </div>

                    <!-- Report Content -->
                    <div class="text-sm text-slate-650 flex flex-col gap-3">
                        @if(!empty($review->feedback_data['suggestions']))
                            <div>
                                <h4 class="font-bold text-slate-900 dark:text-slate-100 mb-1 text-xs uppercase tracking-wider">Suggestions:</h4>
                                <ul class="list-disc pl-4 text-slate-500 text-xs flex flex-col gap-1">
                                    @foreach($review->feedback_data['suggestions'] as $sug)
                                        <li>{{ $sug }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(!empty($review->feedback_data['corrections']))
                            <div>
                                <h4 class="font-bold text-slate-900 dark:text-slate-100 mb-1 text-xs uppercase tracking-wider">Grammar Corrections:</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2">
                                    @foreach($review->feedback_data['corrections'] as $cor)
                                        <div class="p-3 bg-slate-50 dark:bg-slate-850 rounded-xl border border-slate-100 dark:border-slate-800/80">
                                            <p class="text-xs line-through text-rose-500 font-semibold">"{{ $cor['original'] }}"</p>
                                            <p class="text-xs text-emerald-600 font-bold mt-1">"{{ $cor['suggestion'] }}"</p>
                                            <p class="text-[10px] text-slate-400 mt-1">{{ $cor['reason'] ?? '' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-850 rounded-2xl p-12 text-center flex flex-col items-center justify-center gap-3">
                    <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-850 flex items-center justify-center text-2xl text-slate-400">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">No AI audit run yet</h3>
                    <p class="text-xs text-slate-500 max-w-sm">Go to the Resume Builder page and click 'AI Assistant' to run optimization checks on your content.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
