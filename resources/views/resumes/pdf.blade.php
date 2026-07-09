<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page { size: A4; margin: 8mm; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #fff; color: #0f172a; font-family: Inter, Arial, sans-serif; font-size: 12px; line-height: 1.45; }
        .sheet { width: 100%; min-height: 100%; background: #fff; }
        .header { display: flex; justify-content: space-between; gap: 18px; border-bottom: 1px solid #e2e8f0; padding-bottom: 14px; margin-bottom: 16px; }
        .name { font-size: 28px; font-weight: 800; margin: 0; }
        .title { font-size: 13px; font-weight: 700; color: var(--primary); margin-top: 3px; }
        .contact { text-align: right; font-size: 10px; color: #475569; }
        .photo { width: 62px; height: 62px; object-fit: cover; border-radius: 10px; border: 1px solid #e2e8f0; }
        .section { margin-bottom: 14px; page-break-inside: avoid; }
        .section-title { color: var(--primary); border-bottom: 1px solid #e2e8f0; text-transform: uppercase; font-weight: 800; font-size: 11px; padding-bottom: 4px; margin-bottom: 8px; }
        .item { margin-bottom: 9px; page-break-inside: avoid; }
        .item-head { display: flex; justify-content: space-between; gap: 10px; font-weight: 700; }
        .muted { color: #64748b; font-size: 10px; }
        .tags { display: flex; flex-wrap: wrap; gap: 5px; }
        .tag { background: #f1f5f9; border-radius: 5px; padding: 4px 7px; font-size: 10px; font-weight: 700; }
        .split { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .sidebar-layout { display: grid; grid-template-columns: 30% 1fr; gap: 22px; }
        .sidebar { background: var(--primary); color: #fff; padding: 18px; min-height: 1000px; }
        .sidebar .section-title { color: #fff; border-color: rgba(255,255,255,.28); }
        .sidebar .muted { color: rgba(255,255,255,.75); }
        .sidebar .tag { background: rgba(255,255,255,.16); color: #fff; }
    </style>
</head>
@php
    $sections = $resume->sections->keyBy('section_type');
    $contact = $sections->get('contact')?->content ?? [];
    $summary = $sections->get('summary')?->content ?? [];
    $skills = $sections->get('skills')?->content ?? [];
    $experience = $sections->get('experience')?->content ?? [];
    $education = $sections->get('education')?->content ?? [];
    $certifications = $sections->get('certifications')?->content ?? [];
    $languages = $sections->get('languages')?->content ?? [];
    $layout = $resume->template?->structure['layout'] ?? 'single-column';
    $primaryColor = $resume->template?->structure['colors']['primary'] ?? '#2563eb';
@endphp
<body style="--primary: {{ $primaryColor }}">
<div class="sheet {{ $layout === 'left-curved-sidebar' || $layout === 'terracotta-sidebar' || $layout === 'golden-sidebar' ? 'sidebar-layout' : '' }}">
    @if($layout === 'left-curved-sidebar' || $layout === 'terracotta-sidebar' || $layout === 'golden-sidebar')
        <aside class="sidebar">
            @if(!empty($contact['photo']))<img src="{{ $contact['photo'] }}" class="photo" alt="">@endif
            <h1 class="name" style="font-size:20px">{{ $contact['name'] ?? $resume->title }}</h1>
            <div class="title" style="color:#fff">{{ $contact['title'] ?? '' }}</div>
            <div class="section"><div class="section-title">{{ __('app.sec_contact') }}</div><div class="muted">{{ $contact['email'] ?? '' }}<br>{{ $contact['phone'] ?? '' }}<br>{{ $contact['address'] ?? '' }}</div></div>
            @include('resumes.partials.pdf-section-skills', ['skills' => $skills])
            @include('resumes.partials.pdf-section-languages', ['languages' => $languages])
        </aside>
        <main>
    @else
        <header class="header">
            <div>
                <h1 class="name">{{ $contact['name'] ?? $resume->title }}</h1>
                <div class="title">{{ $contact['title'] ?? '' }}</div>
            </div>
            <div style="display:flex; gap:12px; align-items:flex-start;">
                <div class="contact">{{ $contact['email'] ?? '' }}<br>{{ $contact['phone'] ?? '' }}<br>{{ $contact['address'] ?? '' }}</div>
                @if(!empty($contact['photo']))<img src="{{ $contact['photo'] }}" class="photo" alt="">@endif
            </div>
        </header>
        <main>
    @endif

        @if(!empty($summary['text']))<section class="section"><div class="section-title">{{ __('app.sec_about') }}</div>{{ $summary['text'] }}</section>@endif
        @include('resumes.partials.pdf-section-experience', ['experience' => $experience])
        <div class="split">
            <div>
                @include('resumes.partials.pdf-section-education', ['education' => $education])
                @include('resumes.partials.pdf-section-certifications', ['certifications' => $certifications])
            </div>
            <div>
                @if(!($layout === 'left-curved-sidebar' || $layout === 'terracotta-sidebar' || $layout === 'golden-sidebar'))
                    @include('resumes.partials.pdf-section-skills', ['skills' => $skills])
                    @include('resumes.partials.pdf-section-languages', ['languages' => $languages])
                @endif
            </div>
        </div>
    </main>
</div>
</body>
</html>
