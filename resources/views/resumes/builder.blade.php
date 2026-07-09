@extends('layouts.app')

@section('title', 'Resume Builder')

@section('styles')
<style>
    /* Styling specifically for split screen layout */
    .builder-container {
        height: calc(100vh - 4.5rem);
    }

    /* ─── TEMPLATE PREVIEW GLOBAL STYLE WRAPPERS ─── */
    .tpl-professional { font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; line-height: 1.6; }
    .tpl-professional .hdr { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%); color: #fff; padding: 24px; display: flex; justify-content: space-between; align-items: center; gap: 16px; }
    .tpl-professional .hdr h1 { font-size: 26px; font-weight: 800; line-height: 1.1; margin-bottom: 4px; }
    .tpl-professional .hdr .title { color: #93c5fd; font-size: 13px; font-weight: 600; }
    .tpl-professional .hdr-right-wrap { display: flex; align-items: center; gap: 12px; }
    .tpl-professional .hdr-right { text-align: right; display: flex; flex-direction: column; gap: 4px; }
    .tpl-professional .hdr-contact-item { font-size: 11px; color: #cbd5e1; display: flex; align-items: center; justify-content: flex-end; gap: 5px; }
    .tpl-professional .photo { width: 60px; height: 60px; border-radius: 8px; object-fit: cover; border: 2px solid rgba(255,255,255,.2); }
    .tpl-professional .body { display: grid; grid-template-columns: 1.2fr 1fr; gap: 16px; padding: 20px; }
    .tpl-professional .col-left { border-right: 1px solid #f1f5f9; padding-right: 16px; }
    .tpl-professional .col-right { padding-left: 8px; }
    .tpl-professional .sec { margin-bottom: 18px; }
    .tpl-professional .sec-title { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #2563eb; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
    .tpl-professional .sec-title::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }
    .tpl-professional .item { margin-bottom: 14px; padding-bottom: 14px; border-bottom: 1px solid #f1f5f9; }
    .tpl-professional .item-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; }
    .tpl-professional .item-role { font-size: 13px; font-weight: 700; color: #0f172a; }
    .tpl-professional .item-dur { font-size: 10px; color: #64748b; background: #e2e8f0; padding: 1px 6px; border-radius: 12px; }
    .tpl-professional .item-company { font-size: 11.5px; color: #2563eb; font-weight: 600; margin-bottom: 4px; }
    .tpl-professional .item-desc { font-size: 11px; color: #475569; }
    .tpl-professional .tags { display: flex; flex-wrap: wrap; gap: 4px; }
    .tpl-professional .tag { background: #dbeafe; color: #1d4ed8; font-size: 10.5px; font-weight: 600; padding: 3px 8px; border-radius: 6px; }
    .tpl-professional .edu-item { margin-bottom: 10px; padding: 10px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; }
    .tpl-professional .edu-degree { font-size: 12px; font-weight: 700; color: #0f172a; }
    .tpl-professional .edu-school { font-size: 11px; color: #2563eb; font-weight: 500; }
    .tpl-professional .edu-year { font-size: 10.5px; color: #94a3b8; }
    .tpl-professional .lang-item { display: flex; justify-content: space-between; align-items: center; padding: 5px 0; border-bottom: 1px solid #f1f5f9; font-size: 11.5px; }
    .tpl-professional .lang-level { color: #2563eb; background: #dbeafe; padding: 1px 6px; border-radius: 10px; font-size: 10px; }
    .tpl-professional .cert-item { margin-bottom: 10px; padding: 8px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; }
    .tpl-professional .cert-name { font-size: 12px; font-weight: 700; color: #0f172a; }
    .tpl-professional .cert-org { font-size: 11px; color: #2563eb; }
    .tpl-professional .cert-date { font-size: 10px; color: #94a3b8; }
    .tpl-professional .summary-text { font-size: 12px; color: #475569; }

    /* ─── TPL: MODERN ─── */
    .tpl-modern { font-family: 'Nunito', sans-serif; color: #1e293b; line-height: 1.6; display: flex; min-height: 100%; }
    .tpl-modern .sidebar { width: 200px; background: linear-gradient(180deg, #1e40af 0%, #1d4ed8 60%, #2563eb 100%); color: #fff; padding: 20px 14px; display: flex; flex-direction: column; gap: 20px; }
    .tpl-modern .avatar-wrap { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 8px; }
    .tpl-modern .avatar { width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,.4); }
    .tpl-modern .avatar-initials { width: 64px; height: 64px; border-radius: 50%; background: rgba(255,255,255,.2); display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800; }
    .tpl-modern .sidebar-name { font-size: 15px; font-weight: 800; }
    .tpl-modern .sidebar-title { font-size: 11px; color: #bfdbfe; }
    .tpl-modern .s-sec { display: flex; flex-direction: column; gap: 6px; }
    .tpl-modern .s-label { font-size: 9px; font-weight: 800; text-transform: uppercase; tracking-wider; color: #93c5fd; }
    .tpl-modern .contact-row { display: flex; align-items: center; gap: 6px; font-size: 10.5px; color: #e0e7ff; }
    .tpl-modern .contact-row i { color: #60a5fa; width: 12px; text-align: center; }
    .tpl-modern .skill-bar-wrap { display: flex; flex-direction: column; gap: 6px; }
    .tpl-modern .skill-name { font-size: 11px; color: #e0e7ff; }
    .tpl-modern .skill-bar { height: 4px; background: rgba(255,255,255,.2); border-radius: 4px; margin-top: 2px; }
    .tpl-modern .skill-bar-fill { height: 100%; width: 75%; background: #60a5fa; border-radius: 4px; }
    .tpl-modern .lang-row { display: flex; justify-content: space-between; font-size: 11px; border-bottom: 1px solid rgba(255,255,255,.1); padding: 4px 0; }
    .tpl-modern .main { flex: 1; padding: 24px; display: flex; flex-direction: column; gap: 20px; }
    .tpl-modern .m-sec { display: flex; flex-direction: column; gap: 8px; }
    .tpl-modern .m-title { font-size: 10px; font-weight: 900; text-transform: uppercase; color: #1d4ed8; border-bottom: 2px solid #dbeafe; padding-bottom: 4px; }
    .tpl-modern .summary-text { font-size: 12px; color: #475569; }
    .tpl-modern .item { padding-bottom: 10px; border-bottom: 1px solid #f1f5f9; }
    .tpl-modern .item-role { font-size: 13px; font-weight: 700; color: #0f172a; }
    .tpl-modern .item-dur { font-size: 9.5px; color: #64748b; background: #f1f5f9; padding: 1px 6px; border-radius: 10px; }
    .tpl-modern .item-company { font-size: 11px; color: #1d4ed8; font-weight: 600; }
    .tpl-modern .item-desc { font-size: 11px; color: #475569; }
    .tpl-modern .edu-item { background: #f8fafc; border-left: 3px solid #2563eb; padding: 8px 10px; border-radius: 4px; }
    .tpl-modern .edu-degree { font-size: 12.5px; font-weight: 700; }
    .tpl-modern .edu-school { font-size: 11px; color: #1d4ed8; }
    .tpl-modern .edu-year { font-size: 10px; color: #94a3b8; }
    .tpl-modern .cert-item { padding: 4px 0; border-bottom: 1px solid #f1f5f9; }
    .tpl-modern .cert-name { font-size: 12px; font-weight: 700; }
    .tpl-modern .cert-org { font-size: 11px; color: #1d4ed8; }

    /* ─── TPL: ELEGANT ─── */
    .tpl-elegant { font-family: 'Lato', sans-serif; color: #2d2d2d; line-height: 1.6; }
    .tpl-elegant .hdr { text-align: center; padding: 24px; border-bottom: 2px solid #c8a96e; position: relative; }
    .tpl-elegant .hdr h1 { font-family: 'Playfair Display', serif; font-size: 32px; font-weight: 800; color: #1a1a1a; }
    .tpl-elegant .hdr .job-title { font-size: 12px; color: #c8a96e; letter-spacing: 3px; text-transform: uppercase; margin-top: 4px; }
    .tpl-elegant .hdr-contact { display: flex; justify-content: center; gap: 16px; margin-top: 12px; flex-wrap: wrap; }
    .tpl-elegant .hdr-contact-item { font-size: 11px; color: #555; display: flex; align-items: center; gap: 4px; }
    .tpl-elegant .hdr-contact-item i { color: #c8a96e; }
    .tpl-elegant .photo { width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 2px solid #c8a96e; margin: 0 auto 10px; display: block; }
    .tpl-elegant .body { display: grid; grid-template-columns: 1fr 180px; gap: 20px; padding: 24px; }
    .tpl-elegant .sec { margin-bottom: 20px; }
    .tpl-elegant .sec-title { font-family: 'Playfair Display', serif; font-size: 15px; font-weight: 700; color: #1a1a1a; margin-bottom: 10px; padding-bottom: 4px; border-bottom: 1px solid #c8a96e; display: flex; align-items: center; gap: 6px; }
    .tpl-elegant .sec-title::before { content: ''; width: 3px; height: 12px; background: #c8a96e; }
    .tpl-elegant .item { margin-bottom: 14px; }
    .tpl-elegant .item-head { display: flex; justify-content: space-between; align-items: baseline; }
    .tpl-elegant .item-role { font-size: 13px; font-weight: 700; color: #1a1a1a; }
    .tpl-elegant .item-dur { font-size: 10.5px; color: #c8a96e; font-style: italic; }
    .tpl-elegant .item-company { font-size: 11px; color: #666; font-style: italic; }
    .tpl-elegant .item-desc { font-size: 11px; color: #555; }
    .tpl-elegant .tag { font-size: 11px; color: #2d2d2d; padding: 4px 8px; background: #fdf8f0; border-left: 2px solid #c8a96e; margin-bottom: 4px; display: block; }
    .tpl-elegant .edu-item { margin-bottom: 10px; }
    .tpl-elegant .edu-degree { font-size: 12px; font-weight: 700; }
    .tpl-elegant .edu-school { font-size: 11px; color: #666; }
    .tpl-elegant .edu-year { font-size: 10.5px; color: #c8a96e; }
    .tpl-elegant .lang-item { display: flex; justify-content: space-between; font-size: 11px; padding: 4px 0; border-bottom: 1px dotted #e8d5a3; }

    /* ─── TPL: CIRCULAR ─── */
    .tpl-circular { font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; line-height: 1.6; display: flex; min-height: 100%; }
    .tpl-circular .sidebar { width: 190px; background: #0c1a2e; padding: 0 0 20px; display: flex; flex-direction: column; gap: 16px; }
    .tpl-circular .sidebar-top { background: linear-gradient(160deg, #0f2d52, #1a4080); padding: 20px 14px; text-align: center; }
    .tpl-circular .avatar { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,.3); margin: 0 auto 10px; }
    .tpl-circular .avatar-initials { width: 60px; height: 60px; border-radius: 50%; background: rgba(255,255,255,.1); color: #fff; font-size: 22px; font-weight: 800; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; }
    .tpl-circular .s-name { color: #fff; font-size: 14px; font-weight: 800; }
    .tpl-circular .s-title { color: #93c5fd; font-size: 11px; }
    .tpl-circular .s-section { padding: 0 14px; }
    .tpl-circular .s-label { font-size: 9px; font-weight: 800; text-transform: uppercase; color: #60a5fa; margin-bottom: 6px; }
    .tpl-circular .contact-item { display: flex; gap: 6px; font-size: 10.5px; color: #94a3b8; margin-bottom: 6px; }
    .tpl-circular .contact-item i { color: #60a5fa; }
    .tpl-circular .skill-chip { display: inline-block; background: rgba(255,255,255,.08); color: #e2e8f0; font-size: 10.5px; padding: 3px 6px; border-radius: 4px; margin: 2px; border: 1px solid rgba(255,255,255,.1); }
    .tpl-circular .lang-row { display: flex; justify-content: space-between; font-size: 11px; padding: 4px 0; color: #cbd5e1; }
    .tpl-circular .main { flex: 1; padding: 24px; display: flex; flex-direction: column; gap: 20px; }
    .tpl-circular .m-sec { margin-bottom: 8px; }
    .tpl-circular .m-title { font-size: 9.5px; font-weight: 800; text-transform: uppercase; color: #0c1a2e; border-bottom: 2px solid #0c1a2e; padding-bottom: 4px; margin-bottom: 12px; }
    .tpl-circular .timeline { border-left: 2px solid #e2e8f0; padding-left: 14px; margin-left: 4px; }
    .tpl-circular .tl-item { position: relative; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #f8fafc; }
    .tpl-circular .tl-dot { position: absolute; left: -19px; top: 4px; width: 8px; height: 8px; border-radius: 50%; background: #0c1a2e; border: 2px solid #fff; box-shadow: 0 0 0 1px #0c1a2e; }
    .tpl-circular .item-role { font-size: 13px; font-weight: 700; }
    .tpl-circular .item-dur { font-size: 9.5px; color: #64748b; background: #f1f5f9; padding: 1px 6px; border-radius: 10px; }
    .tpl-circular .item-company { font-size: 11px; color: #1a4080; font-weight: 600; }
    .tpl-circular .edu-card { background: #f8fafc; border-left: 3px solid #0c1a2e; padding: 8px 10px; margin-bottom: 8px; border-radius: 4px; display: flex; justify-content: space-between; }
    .tpl-circular .edu-degree { font-size: 12.5px; font-weight: 700; }

    /* ─── TPL: CHRONO ─── */
    .tpl-chrono { font-family: 'Inter', sans-serif; color: #1e293b; }
    .tpl-chrono .hdr { background: #059669; padding: 24px; color: #fff; }
    .tpl-chrono .hdr h1 { font-size: 26px; font-weight: 900; }
    .tpl-chrono .hdr .role { font-size: 13px; color: #a7f3d0; margin-bottom: 12px; }
    .tpl-chrono .contact-pills { display: flex; flex-wrap: wrap; gap: 8px; }
    .tpl-chrono .pill { display: flex; align-items: center; gap: 4px; font-size: 11px; background: rgba(255,255,255,.15); padding: 3px 8px; border-radius: 12px; }
    .tpl-chrono .photo { width: 64px; height: 64px; border-radius: 8px; object-fit: cover; border: 2px solid rgba(255,255,255,.3); }
    .tpl-chrono .body { padding: 20px; columns: 2; column-gap: 20px; }
    .tpl-chrono .sec { break-inside: avoid; margin-bottom: 18px; }
    .tpl-chrono .sec-title { font-size: 9.5px; font-weight: 800; text-transform: uppercase; color: #059669; border-bottom: 2px solid #d1fae5; padding-bottom: 4px; margin-bottom: 10px; }
    .tpl-chrono .item { margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #f0fdf4; }
    .tpl-chrono .item-role { font-size: 12.5px; font-weight: 700; }
    .tpl-chrono .item-dur { font-size: 9.5px; color: #64748b; background: #f0fdf4; padding: 1px 6px; border-radius: 10px; }
    .tpl-chrono .item-company { font-size: 11px; color: #059669; font-weight: 600; }
    .tpl-chrono .tag { background: #ecfdf5; color: #065f46; font-size: 10.5px; font-weight: 600; padding: 3px 6px; border-radius: 4px; border: 1px solid #a7f3d0; margin: 2px; display: inline-block; }
    .tpl-chrono .edu-item { background: #f0fdf4; padding: 8px 10px; border-radius: 6px; margin-bottom: 8px; }

    /* ─── TPL: VERTICAL ─── */
    .tpl-vertical { font-family: 'Montserrat', sans-serif; color: #1e293b; display: flex; min-height: 100%; }
    .tpl-vertical .stripe { width: 8px; background: linear-gradient(180deg, #7c3aed, #c084fc); }
    .tpl-vertical .sidebar { width: 185px; background: #1e1b4b; padding: 24px 14px; display: flex; flex-direction: column; gap: 20px; color: #fff; }
    .tpl-vertical .avatar { width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 2px solid #7c3aed; margin: 0 auto; display: block; }
    .tpl-vertical .avatar-initials { width: 64px; height: 64px; border-radius: 50%; background: rgba(124,58,237,.2); color: #c4b5fd; font-size: 24px; font-weight: 800; display: flex; align-items: center; justify-content: center; margin: 0 auto; }
    .tpl-vertical .s-name { font-size: 14px; font-weight: 800; text-align: center; }
    .tpl-vertical .s-title { font-size: 11px; color: #a78bfa; text-align: center; }
    .tpl-vertical .s-label { font-size: 9px; font-weight: 800; text-transform: uppercase; color: #7c3aed; }
    .tpl-vertical .c-item { display: flex; gap: 6px; font-size: 10.5px; color: #94a3b8; }
    .tpl-vertical .skill-track { height: 3px; background: rgba(255,255,255,.1); border-radius: 4px; margin-top: 2px; }
    .tpl-vertical .skill-fill { height: 100%; width: 75%; background: #7c3aed; border-radius: 4px; }
    .tpl-vertical .main { flex: 1; padding: 24px; display: flex; flex-direction: column; gap: 20px; }
    .tpl-vertical .m-sec-title { font-size: 9.5px; font-weight: 800; text-transform: uppercase; color: #7c3aed; border-bottom: 2px solid #ede9fe; padding-bottom: 4px; }
    .tpl-vertical .item { padding-bottom: 12px; border-bottom: 1px solid #f5f3ff; }
    .tpl-vertical .item-role { font-size: 12.5px; font-weight: 700; }
    .tpl-vertical .item-dur { font-size: 9.5px; color: #64748b; background: #f5f3ff; padding: 1px 6px; border-radius: 10px; }
    .tpl-vertical .item-company { font-size: 11px; color: #7c3aed; font-weight: 600; }
    .tpl-vertical .edu-block { background: #faf5ff; border-left: 3px solid #7c3aed; padding: 8px 10px; border-radius: 4px; }

    /* ─── TPL: HORIZONTAL ─── */
    .tpl-horizontal { font-family: 'Inter', sans-serif; color: #1e293b; }
    .tpl-horizontal .hdr { background: #dc2626; padding: 24px; color: #fff; }
    .tpl-horizontal .hdr-inner { display: flex; align-items: center; gap: 16px; margin-bottom: 14px; }
    .tpl-horizontal .photo { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,.4); }
    .tpl-horizontal .avatar-initials { width: 60px; height: 60px; border-radius: 50%; background: rgba(255,255,255,.2); font-size: 22px; font-weight: 800; display: flex; align-items: center; justify-content: center; }
    .tpl-horizontal .hdr-title h1 { font-size: 26px; font-weight: 900; }
    .tpl-horizontal .hdr-title .role { font-size: 12px; color: #fca5a5; }
    .tpl-horizontal .contact-row { display: flex; flex-wrap: wrap; gap: 12px; border-top: 1px solid rgba(255,255,255,.15); padding-top: 10px; }
    .tpl-horizontal .c-pill { display: flex; align-items: center; gap: 4px; font-size: 11.5px; }
    .tpl-horizontal .body { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding: 20px; }
    .tpl-horizontal .col-a { border-right: 1px solid #fef2f2; padding-right: 12px; }
    .tpl-horizontal .sec-title { font-size: 9.5px; font-weight: 800; text-transform: uppercase; color: #dc2626; border-bottom: 2px solid #fecaca; padding-bottom: 4px; margin-bottom: 12px; }
    .tpl-horizontal .item { padding-bottom: 10px; border-bottom: 1px solid #fef2f2; margin-bottom: 10px; }
    .tpl-horizontal .item-role { font-size: 13px; font-weight: 700; }
    .tpl-horizontal .item-dur { font-size: 9.5px; color: #64748b; background: #fef2f2; padding: 1px 6px; border-radius: 10px; }
    .tpl-horizontal .item-company { font-size: 11px; color: #dc2626; font-weight: 600; }
    .tpl-horizontal .edu-card { background: #fff5f5; border-left: 3px solid #dc2626; padding: 8px 10px; border-radius: 4px; margin-bottom: 8px; }

    /* ─── TPL: LUXURIOUS ─── */
    .tpl-luxurious { font-family: 'Source Sans 3', sans-serif; color: #1a1a1a; }
    .tpl-luxurious .hdr { padding: 24px; position: relative; border-bottom: 1px solid #e8c84a; }
    .tpl-luxurious .hdr::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #b7860f, #e8c84a, #d4a017); }
    .tpl-luxurious .hdr-inner { display: flex; justify-content: space-between; align-items: center; }
    .tpl-luxurious .hdr-left h1 { font-family: 'Playfair Display', serif; font-size: 30px; font-weight: 800; }
    .tpl-luxurious .hdr-left .role { font-size: 11px; color: #b7860f; letter-spacing: 2px; text-transform: uppercase; margin-top: 4px; }
    .tpl-luxurious .hdr-right { text-align: right; font-size: 11px; color: #555; }
    .tpl-luxurious .photo { width: 56px; height: 56px; border-radius: 6px; object-fit: cover; border: 1.5px solid #e8c84a; }
    .tpl-luxurious .body { display: grid; grid-template-columns: 1fr 190px; gap: 20px; padding: 24px; }
    .tpl-luxurious .sec-title { font-family: 'Playfair Display', serif; font-size: 14px; font-weight: 700; color: #0d0d0d; border-left: 3px solid #b7860f; padding-left: 8px; margin-bottom: 12px; }
    .tpl-luxurious .item { padding-bottom: 12px; border-bottom: 1px solid #f5f0e8; margin-bottom: 12px; }
    .tpl-luxurious .item-role { font-size: 13px; font-weight: 700; }
    .tpl-luxurious .item-dur { font-size: 10px; color: #b7860f; }
    .tpl-luxurious .tag { display: block; font-size: 11px; padding: 4px 8px; background: #fdf8ec; border-left: 2px solid #b7860f; margin-bottom: 4px; }
    .tpl-luxurious .edu-item { margin-bottom: 8px; border-bottom: 1px dotted #e8c84a; padding-bottom: 6px; }
</style>
@endsection

@section('content')
<div x-data="resumeBuilder()" data-resume-builder class="grid grid-cols-1 lg:grid-cols-12 gap-6 builder-container -mt-6">

    <!-- LEFT COLUMN: Forms and Inputs (lg:col-span-5) -->
    <div class="lg:col-span-5 flex flex-col h-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">

        <!-- Header Bar -->
        <div class="p-5 border-b border-slate-200 dark:border-slate-850 flex items-center justify-between gap-4">
            <div class="flex-1 min-w-0">
                <!-- Editable Resume Title -->
                <input
                    type="text"
                    x-model="title"
                    @input="triggerAutoSave()"
                    class="text-lg font-extrabold font-outfit text-slate-900 dark:text-slate-100 bg-transparent border-b border-transparent hover:border-slate-300 focus:border-primary-500 focus:outline-none w-full pb-0.5 truncate"
                />
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs font-semibold text-slate-400 flex items-center gap-1.5">
                        <i class="fa-solid" :class="saveStatus === 'Saved' ? 'fa-cloud-arrow-up text-emerald-500' : 'fa-spinner animate-spin text-primary-500'"></i>
                        <span x-text="saveStatus">Saved</span>
                    </span>
                    <span class="text-xs text-slate-400">•</span>
                    <!-- Template Selector Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                            <span x-text="getTemplateName()">Template</span>
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute left-0 mt-1.5 w-48 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-705 rounded-xl shadow-lg z-50 py-1.5 text-xs text-slate-700 dark:text-slate-350">
                            @foreach($templates as $tpl)
                                <button @click="switchTemplate(@js($tpl->id), @js($tpl->style)); open = false"
                                        :class="templateId === '{{ $tpl->id }}' ? 'bg-primary-50 dark:bg-primary-950/20 text-primary-600 font-bold' : ''"
                                        class="w-full text-left px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-750 transition">
                                    {{ $tpl->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="mt-2 flex flex-wrap items-center gap-3 text-xs">
                    <span class="font-semibold text-slate-500">Draft:</span>
                    <span x-text="saveStatus" :class="hasUnsavedChanges ? 'text-amber-600' : 'text-emerald-600'" class="font-bold"></span>
                    <span class="text-slate-300">|</span>
                    <span class="font-semibold text-slate-500">Complete:</span>
                    <span x-text="completionPercent + '%'" class="font-bold text-primary-600"></span>
                    <span class="text-slate-300">|</span>
                    <span class="font-semibold text-slate-500">Score:</span>
                    <span x-text="resumeScore + '%'" class="font-bold text-emerald-600"></span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex items-center gap-2">
                <button @click="openAIModal()" class="flex items-center gap-1.5 px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>AI Assistant</span>
                </button>
                <a href="/resumes/{{ $resume->id }}/preview" class="p-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl transition" title="Full Preview">
                    <i class="fa-solid fa-expand"></i>
                </a>
            </div>
        </div>

        <!-- Form Navigation Tabs -->
        <div class="flex border-b border-slate-200 dark:border-slate-850 overflow-x-auto scrollbar-none px-4 bg-slate-50/50 dark:bg-slate-900/50">
            <template x-for="tab in tabs" :key="tab.id">
                <button
                    @click="activeTab = tab.id"
                    :class="activeTab === tab.id ? 'border-primary-500 text-primary-600 dark:text-primary-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:hover:text-slate-300'"
                    class="whitespace-nowrap py-3.5 px-3 border-b-2 font-medium text-xs transition duration-150 flex items-center gap-1.5"
                >
                    <i :class="tab.icon"></i>
                    <span x-text="tab.name"></span>
                </button>
            </template>
        </div>

        <!-- Tab Contents Wrapper (Scrollable area) -->
        <div class="flex-1 overflow-y-auto p-6 flex flex-col gap-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 rounded-2xl border border-slate-100 dark:border-slate-850 bg-slate-50/60 dark:bg-slate-900/40 p-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Target Job Role</label>
                    <select x-model="jobRole" @change="applyJobRole()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150">
                        <option value="">Select role</option>
                        <template x-for="role in jobRoles" :key="role.name">
                            <option :value="role.name" x-text="role.name"></option>
                        </template>
                    </select>
                </div>
                <div x-show="selectedRole" x-cloak class="text-xs text-slate-600 dark:text-slate-400">
                    <div class="font-bold text-slate-800 dark:text-slate-200 mb-1">AI Suggestions</div>
                    <p x-text="selectedRole?.summary"></p>
                    <div class="flex flex-wrap gap-1 mt-2">
                        <template x-for="skill in (selectedRole?.skills || [])">
                            <button type="button" @click="addSuggestedSkill(skill)" class="px-2 py-1 rounded-md bg-primary-50 text-primary-700 font-bold" x-text="skill"></button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- CONTACT DETAILS TAB -->
            <div x-show="activeTab === 'contact'" class="flex flex-col gap-5">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-850 pb-2">Personal Contact Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <x-input label="Full Name" name="c_name" placeholder="John Doe" model="contact.name" @input="triggerAutoSave()" />
                    <x-input label="Professional Title" name="c_title" placeholder="Backend Engineer" model="contact.title" @input="triggerAutoSave()" />
                </div>
                <div class="flex items-center gap-4 p-4 rounded-2xl border border-slate-100 dark:border-slate-850 bg-slate-50/50 dark:bg-slate-900/40">
                    <div class="w-16 h-16 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden flex items-center justify-center text-slate-400 shrink-0">
                        <template x-if="contact.photo">
                            <img :src="contact.photo" alt="Profile photo" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!contact.photo">
                            <i class="fa-solid fa-user text-xl"></i>
                        </template>
                    </div>
                    <div class="flex-1 min-w-0">
                        <label for="c_photo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Profile Photo</label>
                        <div class="flex flex-wrap items-center gap-2">
                            <input id="c_photo" type="file" accept="image/*" @change="handlePhotoUpload($event)" class="block w-full text-xs text-slate-500 file:mr-3 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary-700 file:font-bold hover:file:bg-primary-100">
                            <button type="button" x-show="contact.photo" x-cloak @click="removePhoto()" class="text-xs font-bold text-rose-600 hover:text-rose-700">Remove photo</button>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="c_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Phone Number</label>
                        <div class="flex gap-2">
                            <select x-model="contact.phone_country" @change="applyPhoneCountry()" class="w-32 px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150 text-sm">
                                <template x-for="country in phoneCountries" :key="country.code">
                                    <option :value="country.code" x-text="country.label"></option>
                                </template>
                            </select>
                            <input id="c_phone" name="c_phone" type="tel" x-model="contact.phone" @input="triggerAutoSave()" placeholder="+998 90 123 45 67" class="flex-1 min-w-0 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150">
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-input label="Location/Address" name="c_address" placeholder="San Francisco, CA" model="contact.address" @input="triggerAutoSave()" />
                    <div class="w-full">
                        <label for="c_dob" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Date of Birth</label>
                        <input id="c_dob" name="c_dob" type="date" x-model="contact.date_of_birth" @input="triggerAutoSave()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150 text-sm">
                    </div>
                </div>
            </div>

            <!-- SUMMARY TAB -->
            <div x-show="activeTab === 'summary'" class="flex flex-col gap-4">
                <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-850 pb-2">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Professional Summary</h3>
                    <button @click="improveText('summary.text')" class="text-xs font-semibold text-purple-600 hover:text-purple-700 flex items-center gap-1">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> AI Improve
                    </button>
                </div>
                <x-textarea label="About Me" name="s_text" placeholder="Write a brief summary of your achievements and expertise..." model="summary.text" rows="8" @input="triggerAutoSave()" />
            </div>

            <!-- SKILLS TAB -->
            <div x-show="activeTab === 'skills'" class="flex flex-col gap-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-850 pb-2">Core Skills & Competencies</h3>

                <!-- Tag Input Controller -->
                <div x-data="{ newSkill: '' }" class="flex flex-col gap-3">
                    <div class="flex gap-2">
                        <input
                            type="text"
                            x-model="newSkill"
                            @keydown.enter.prevent="if(newSkill.trim()) { skills.list.push(newSkill.trim()); newSkill = ''; triggerAutoSave(); }"
                            placeholder="Add skill (e.g. PHP, Kubernetes) and press Enter"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150 text-sm"
                        />
                        <button type="button" @click="if(newSkill.trim()) { skills.list.push(newSkill.trim()); newSkill = ''; triggerAutoSave(); }" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-sm font-bold">
                            Add
                        </button>
                    </div>

                    <!-- Skills List -->
                    <div class="flex flex-wrap gap-2 mt-2">
                        <template x-for="(skill, index) in skills.list" :key="index">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-lg text-xs font-semibold">
                                <span x-text="skill"></span>
                                <button type="button" @click="skills.list.splice(index, 1); triggerAutoSave();" class="text-slate-400 hover:text-rose-500 transition">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </span>
                        </template>
                        <template x-if="skills.list.length === 0">
                            <p class="text-xs text-slate-400 italic">No skills added yet.</p>
                        </template>
                    </div>
                </div>
            </div>

            <!-- EXPERIENCE TAB -->
            <div x-show="activeTab === 'experience'" class="flex flex-col gap-5">
                <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-850 pb-2">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Work Experience</h3>
                    <button type="button" @click="addJob()" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        <i class="fa-solid fa-plus"></i> Add Job
                    </button>
                </div>

                <div class="flex flex-col gap-6">
                    <template x-for="(job, index) in experience.items" :key="index">
                        <div class="p-5 border border-slate-100 dark:border-slate-850 bg-slate-50/30 dark:bg-slate-900/30 rounded-2xl flex flex-col gap-4 relative">
                            <!-- Remove button -->
                            <button type="button" @click="experience.items.splice(index, 1); triggerAutoSave();" class="absolute top-4 right-4 text-slate-400 hover:text-rose-500 transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>

                            <div class="grid grid-cols-2 gap-4 pr-6">
                                <x-input label="Company Name" name="job_company" placeholder="Google" x-model="job.company" @input="triggerAutoSave()" />
                                <x-input label="Job Role / Position" name="job_role" placeholder="Software Engineer" x-model="job.role" @input="triggerAutoSave()" />
                            </div>

                            {{-- DATE RANGE PICKER
                                WHY: Free-text duration is replaced with structured month/year selects.
                                The `start_date` and `end_date` are stored as "YYYY-MM" strings.
                                The `is_present` flag disables end date and sets display to "Present".
                                ResumeTemplateRenderer::formatDateRange() turns them into display text.
                            --}}
                            <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900/60">
                                <div class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Duration</div>
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Start Date -->
                                    <div>
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Start Date</label>
                                        <div class="grid grid-cols-2 gap-2">
                                            <select x-model="job.start_month"
                                                @change="job.start_date = (job.start_year || '') + '-' + (job.start_month || ''); triggerAutoSave();"
                                                class="w-full px-2 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition text-xs">
                                                <option value="">Month</option>
                                                <option value="01">Jan</option><option value="02">Feb</option><option value="03">Mar</option>
                                                <option value="04">Apr</option><option value="05">May</option><option value="06">Jun</option>
                                                <option value="07">Jul</option><option value="08">Aug</option><option value="09">Sep</option>
                                                <option value="10">Oct</option><option value="11">Nov</option><option value="12">Dec</option>
                                            </select>
                                            <select x-model="job.start_year"
                                                @change="job.start_date = (job.start_year || '') + '-' + (job.start_month || ''); triggerAutoSave();"
                                                class="w-full px-2 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition text-xs">
                                                <option value="">Year</option>
                                                <template x-for="y in yearRange" :key="y"><option :value="y" x-text="y"></option></template>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- End Date -->
                                    <div :class="job.is_present ? 'opacity-50 pointer-events-none' : ''">
                                        <label class="block text-xs font-medium text-slate-500 mb-1">
                                            End Date
                                            <span x-show="job.is_present" class="text-emerald-600 font-bold ml-1">→ Present</span>
                                        </label>
                                        <div class="grid grid-cols-2 gap-2">
                                            <select x-model="job.end_month"
                                                :disabled="job.is_present"
                                                @change="job.end_date = (job.end_year || '') + '-' + (job.end_month || ''); triggerAutoSave();"
                                                class="w-full px-2 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition text-xs">
                                                <option value="">Month</option>
                                                <option value="01">Jan</option><option value="02">Feb</option><option value="03">Mar</option>
                                                <option value="04">Apr</option><option value="05">May</option><option value="06">Jun</option>
                                                <option value="07">Jul</option><option value="08">Aug</option><option value="09">Sep</option>
                                                <option value="10">Oct</option><option value="11">Nov</option><option value="12">Dec</option>
                                            </select>
                                            <select x-model="job.end_year"
                                                :disabled="job.is_present"
                                                @change="job.end_date = (job.end_year || '') + '-' + (job.end_month || ''); triggerAutoSave();"
                                                class="w-full px-2 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition text-xs">
                                                <option value="">Year</option>
                                                <template x-for="y in yearRange" :key="y"><option :value="y" x-text="y"></option></template>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Currently working checkbox -->
                                <label class="flex items-center gap-2 mt-3 cursor-pointer">
                                    <input type="checkbox" x-model="job.is_present"
                                        @change="if(job.is_present){job.end_date=null;job.end_month='';job.end_year='';} triggerAutoSave();"
                                        class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500">
                                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">I currently work here</span>
                                </label>
                                <!-- Display preview of formatted duration -->
                                <div class="mt-2 text-xs text-slate-500" x-show="job.start_date || job.is_present">
                                    <span class="font-semibold text-slate-700 dark:text-slate-400">Preview: </span>
                                    <span x-text="formatDateRange(job)" class="text-primary-600 font-bold"></span>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Description / Achievements</label>
                                    <button type="button" @click="improveText('experience.items[' + index + '].description')" class="text-xs text-purple-600 hover:text-purple-700 font-semibold flex items-center gap-0.5">
                                        <i class="fa-solid fa-wand-magic-sparkles"></i> AI Improve
                                    </button>
                                </div>
                                <textarea x-model="job.description" @input="triggerAutoSave()" rows="3" placeholder="Briefly write your day-to-day responsibilities and impact..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150 text-sm"></textarea>
                            </div>
                        </div>
                    </template>
                    <template x-if="experience.items.length === 0">
                        <div class="text-center py-6 text-slate-400 text-xs italic">No experience records added yet. Click 'Add Job' to begin.</div>
                    </template>
                </div>
            </div>

            <!-- EDUCATION TAB -->
            <div x-show="activeTab === 'education'" class="flex flex-col gap-5">
                <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-850 pb-2">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Education Details</h3>
                    <button type="button" @click="addEducation()" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        <i class="fa-solid fa-plus"></i> Add Education
                    </button>
                </div>

                <div class="flex flex-col gap-6">
                    <template x-for="(edu, index) in education.items" :key="index">
                        <div class="p-5 border border-slate-100 dark:border-slate-850 bg-slate-50/30 dark:bg-slate-900/30 rounded-2xl flex flex-col gap-4 relative">
                            <!-- Remove button -->
                            <button type="button" @click="education.items.splice(index, 1); triggerAutoSave();" class="absolute top-4 right-4 text-slate-400 hover:text-rose-500 transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>

                            <div class="grid grid-cols-2 gap-4 pr-6">
                                <x-input label="School / University" name="edu_school" placeholder="Stanford University" x-model="edu.school" @input="triggerAutoSave()" />
                                <x-input label="Degree / Program" name="edu_degree" placeholder="B.S. Computer Science" x-model="edu.degree" @input="triggerAutoSave()" />
                            </div>

                            {{-- Education Date Range
                                WHY: "Graduation Year" as a free-text field was too vague.
                                Now we store start_date and end_date for education too.
                                is_present means "currently enrolled".
                            --}}
                            <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900/60">
                                <div class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Study Period</div>
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Start Date -->
                                    <div>
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Start Date</label>
                                        <div class="grid grid-cols-2 gap-2">
                                            <select x-model="edu.start_month"
                                                @change="edu.start_date = (edu.start_year||'') + '-' + (edu.start_month||''); triggerAutoSave();"
                                                class="w-full px-2 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition text-xs">
                                                <option value="">Month</option>
                                                <option value="01">Jan</option><option value="02">Feb</option><option value="03">Mar</option>
                                                <option value="04">Apr</option><option value="05">May</option><option value="06">Jun</option>
                                                <option value="07">Jul</option><option value="08">Aug</option><option value="09">Sep</option>
                                                <option value="10">Oct</option><option value="11">Nov</option><option value="12">Dec</option>
                                            </select>
                                            <select x-model="edu.start_year"
                                                @change="edu.start_date = (edu.start_year||'') + '-' + (edu.start_month||''); triggerAutoSave();"
                                                class="w-full px-2 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition text-xs">
                                                <option value="">Year</option>
                                                <template x-for="y in yearRange" :key="y"><option :value="y" x-text="y"></option></template>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- End / Graduation Date -->
                                    <div :class="edu.is_present ? 'opacity-50 pointer-events-none' : ''">
                                        <label class="block text-xs font-medium text-slate-500 mb-1">
                                            End Date
                                            <span x-show="edu.is_present" class="text-emerald-600 font-bold ml-1">→ Present</span>
                                        </label>
                                        <div class="grid grid-cols-2 gap-2">
                                            <select x-model="edu.end_month"
                                                :disabled="edu.is_present"
                                                @change="edu.end_date = (edu.end_year||'') + '-' + (edu.end_month||''); triggerAutoSave();"
                                                class="w-full px-2 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition text-xs">
                                                <option value="">Month</option>
                                                <option value="01">Jan</option><option value="02">Feb</option><option value="03">Mar</option>
                                                <option value="04">Apr</option><option value="05">May</option><option value="06">Jun</option>
                                                <option value="07">Jul</option><option value="08">Aug</option><option value="09">Sep</option>
                                                <option value="10">Oct</option><option value="11">Nov</option><option value="12">Dec</option>
                                            </select>
                                            <select x-model="edu.end_year"
                                                :disabled="edu.is_present"
                                                @change="edu.end_date = (edu.end_year||'') + '-' + (edu.end_month||''); triggerAutoSave();"
                                                class="w-full px-2 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition text-xs">
                                                <option value="">Year</option>
                                                <template x-for="y in yearRange" :key="y"><option :value="y" x-text="y"></option></template>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <label class="flex items-center gap-2 mt-3 cursor-pointer">
                                    <input type="checkbox" x-model="edu.is_present"
                                        @change="if(edu.is_present){edu.end_date=null;edu.end_month='';edu.end_year='';} triggerAutoSave();"
                                        class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500">
                                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Currently enrolled</span>
                                </label>
                            </div>
                        </div>
                    </template>
                    <template x-if="education.items.length === 0">
                        <div class="text-center py-6 text-slate-400 text-xs italic">No education records added yet.</div>
                    </template>
                </div>
            </div>

            <!-- CERTIFICATIONS TAB -->
            <div x-show="activeTab === 'certifications'" class="flex flex-col gap-5">
                <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-850 pb-2">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Certifications</h3>
                    <button type="button" @click="addCertificate()" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        <i class="fa-solid fa-plus"></i> Add Certificate
                    </button>
                </div>

                <div class="flex flex-col gap-6">
                    <template x-for="(cert, index) in certifications.items" :key="index">
                        <div class="p-5 border border-slate-100 dark:border-slate-850 bg-slate-50/30 dark:bg-slate-900/30 rounded-2xl flex flex-col gap-4 relative">
                            <!-- Remove button -->
                            <button type="button" @click="certifications.items.splice(index, 1); triggerAutoSave();" class="absolute top-4 right-4 text-slate-400 hover:text-rose-500 transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>

                            <div class="grid grid-cols-2 gap-4 pr-6">
                                <x-input label="Certificate Name" name="cert_name" placeholder="AWS Certified Developer" x-model="cert.name" @input="triggerAutoSave()" />
                                <x-input label="Organization" name="cert_org" placeholder="Amazon Web Services" x-model="cert.organization" @input="triggerAutoSave()" />
                            </div>
                            <div class="grid grid-cols-2 gap-4 pr-6">
                                <x-input label="Issue Date" name="cert_issue_date" type="month" x-model="cert.issue_date" @input="triggerAutoSave()" />
                                <x-input label="Credential ID" name="cert_credential_id" placeholder="Optional" x-model="cert.credential_id" @input="triggerAutoSave()" />
                            </div>
                        </div>
                    </template>
                    <template x-if="certifications.items.length === 0">
                        <div class="text-center py-6 text-slate-400 text-xs italic">No certificate records added yet.</div>
                    </template>
                </div>
            </div>

            <!-- LANGUAGES TAB -->
            <div x-show="activeTab === 'languages'" class="flex flex-col gap-5">
                <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-850 pb-2">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Languages</h3>
                    <button type="button" @click="addLanguage()" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        <i class="fa-solid fa-plus"></i> Add Language
                    </button>
                </div>

                <div class="flex flex-col gap-4">
                    <template x-for="(lang, index) in languages.items" :key="index">
                        <div class="p-5 border border-slate-100 dark:border-slate-850 bg-slate-50/30 dark:bg-slate-900/30 rounded-2xl grid grid-cols-2 gap-4 relative">
                            <button type="button" @click="languages.items.splice(index, 1); triggerAutoSave();" class="absolute top-4 right-4 text-slate-400 hover:text-rose-500 transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                            <x-input label="Language" name="language" placeholder="English" x-model="lang.language" @input="triggerAutoSave()" />
                            <div class="pr-6">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Level</label>
                                <select x-model="lang.level" @change="triggerAutoSave()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150">
                                    <option>Native</option>
                                    <option>Fluent</option>
                                    <option>Advanced</option>
                                    <option>Intermediate</option>
                                    <option>Beginner</option>
                                </select>
                            </div>
                        </div>
                    </template>
                    <template x-if="languages.items.length === 0">
                        <div class="text-center py-6 text-slate-400 text-xs italic">No languages added yet.</div>
                    </template>
                </div>
            </div>

        </div>
    </div>

    <!-- RIGHT COLUMN: Live Resume Preview (lg:col-span-7) -->
    <div class="lg:col-span-7 flex flex-col h-full bg-slate-100 dark:bg-slate-950 border border-slate-300 dark:border-slate-850/80 rounded-3xl overflow-hidden shadow-inner relative">
        <div class="absolute top-3 right-4 z-20 text-[10px] uppercase font-extrabold tracking-wider bg-slate-800 text-white px-2 py-0.5 rounded-md shadow-sm opacity-70">
            Live Preview
        </div>

        <!-- Renderable Resume Box — full width, scrollable, no extra padding -->
        <div class="w-full flex-1 overflow-y-auto bg-white shadow-xl select-text"
             :style="getPreviewFontStyle()">
            <!-- 1. PROFESSIONAL -->
            <template x-if="selectedStyle === 'professional'">
                <div class="tpl-professional">
                    <div class="hdr">
                        <div class="hdr-left">
                            <h1 x-text="contact.name || 'Your Name'"></h1>
                            <div class="title" x-text="contact.title || 'Professional Title'"></div>
                        </div>
                        <div class="hdr-right-wrap">
                            <div class="hdr-right">
                                <template x-if="contact.email"><div class="hdr-contact-item"><i class="fa-regular fa-envelope"></i><span x-text="contact.email"></span></div></template>
                                <template x-if="contact.phone"><div class="hdr-contact-item"><i class="fa-solid fa-phone"></i><span x-text="contact.phone"></span></div></template>
                                <template x-if="contact.address"><div class="hdr-contact-item"><i class="fa-solid fa-location-dot"></i><span x-text="contact.address"></span></div></template>
                                <template x-if="contact.date_of_birth"><div class="hdr-contact-item"><i class="fa-regular fa-calendar"></i><span x-text="contact.date_of_birth"></span></div></template>
                            </div>
                            <template x-if="contact.photo">
                                <img :src="contact.photo" class="photo" alt="photo">
                            </template>
                        </div>
                    </div>
                    <div class="body">
                        <div class="col-left">
                            <template x-if="summary.text">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.about"></div>
                                    <p class="summary-text" x-text="summary.text"></p>
                                </div>
                            </template>
                            <template x-if="experience.items && experience.items.length > 0">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.experience"></div>
                                    <template x-for="job in experience.items">
                                        <div class="item">
                                            <div class="item-head">
                                                <span class="item-role" x-text="job.role"></span>
                                                <span class="item-dur" x-text="formatDateRange(job)"></span>
                                            </div>
                                            <div class="item-company" x-text="job.company"></div>
                                            <div class="item-desc" x-text="job.description"></div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                        <div class="col-right">
                            <template x-if="skills.list && skills.list.length > 0">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.skills"></div>
                                    <div class="tags">
                                        <template x-for="sk in skills.list">
                                            <span class="tag" x-text="sk"></span>
                                        </template>
                                    </div>
                                </div>
                            </template>
                            <template x-if="education.items && education.items.length > 0">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.education"></div>
                                    <template x-for="edu in education.items">
                                        <div class="edu-item">
                                            <div class="edu-degree" x-text="edu.degree"></div>
                                            <div class="edu-school" x-text="edu.school"></div>
                                            <div class="edu-year" x-text="edu.year"></div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="certifications.items && certifications.items.length > 0">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.certifications"></div>
                                    <template x-for="cert in certifications.items">
                                        <div class="cert-item">
                                            <div class="cert-name" x-text="cert.name"></div>
                                            <div class="cert-org" x-text="cert.organization"></div>
                                            <div class="cert-date" x-text="cert.issue_date"></div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="languages.items && languages.items.length > 0">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.languages"></div>
                                    <template x-for="lang in languages.items">
                                        <div class="lang-item">
                                            <span class="lang-name" x-text="lang.language"></span>
                                            <span class="lang-level" x-text="lang.level"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

            <!-- 2. MODERN -->
            <template x-if="selectedStyle === 'modern'">
                <div class="tpl-modern">
                    <div class="sidebar">
                        <div class="avatar-wrap">
                            <template x-if="contact.photo">
                                <img :src="contact.photo" class="avatar" alt="photo">
                            </template>
                            <template x-if="!contact.photo">
                                <div class="avatar-initials" x-text="contact.name ? contact.name.charAt(0) : 'U'"></div>
                            </template>
                            <div>
                                <div class="sidebar-name" x-text="contact.name || 'Your Name'"></div>
                                <div class="sidebar-title" x-text="contact.title || 'Professional Title'"></div>
                            </div>
                        </div>
                        <div class="s-sec">
                            <div class="s-label" x-text="trans.contact"></div>
                            <template x-if="contact.email"><div class="contact-row"><i class="fa-regular fa-envelope"></i><span x-text="contact.email"></span></div></template>
                            <template x-if="contact.phone"><div class="contact-row"><i class="fa-solid fa-phone"></i><span x-text="contact.phone"></span></div></template>
                            <template x-if="contact.address"><div class="contact-row"><i class="fa-solid fa-location-dot"></i><span x-text="contact.address"></span></div></template>
                            <template x-if="contact.date_of_birth"><div class="contact-row"><i class="fa-regular fa-calendar"></i><span x-text="contact.date_of_birth"></span></div></template>
                        </div>
                        <template x-if="skills.list && skills.list.length > 0">
                            <div class="s-sec">
                                <div class="s-label" x-text="trans.skills"></div>
                                <div class="skill-bar-wrap">
                                    <template x-for="sk in skills.list">
                                        <div>
                                            <div class="skill-name" x-text="sk"></div>
                                            <div class="skill-bar"><div class="skill-bar-fill"></div></div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template x-if="languages.items && languages.items.length > 0">
                            <div class="s-sec">
                                <div class="s-label" x-text="trans.languages"></div>
                                <template x-for="lang in languages.items">
                                    <div class="lang-row">
                                        <span class="lang-n" x-text="lang.language"></span>
                                        <span class="lang-l" x-text="lang.level"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                    <div class="main">
                        <template x-if="summary.text">
                            <div class="m-sec">
                                <div class="m-title" x-text="trans.about"></div>
                                <p class="summary-text" x-text="summary.text"></p>
                            </div>
                        </template>
                        <template x-if="experience.items && experience.items.length > 0">
                            <div class="m-sec">
                                <div class="m-title" x-text="trans.experience"></div>
                                <template x-for="job in experience.items">
                                    <div class="item">
                                        <div class="item-head">
                                            <span class="item-role" x-text="job.role"></span>
                                            <span class="item-dur" x-text="formatDateRange(job)"></span>
                                        </div>
                                        <div class="item-company" x-text="job.company"></div>
                                        <div class="item-desc" x-text="job.description"></div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="education.items && education.items.length > 0">
                            <div class="m-sec">
                                <div class="m-title" x-text="trans.education"></div>
                                <template x-for="edu in education.items">
                                    <div class="edu-item">
                                        <div class="edu-degree" x-text="edu.degree"></div>
                                        <div class="edu-school" x-text="edu.school"></div>
                                        <div class="edu-year" x-text="edu.year"></div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="certifications.items && certifications.items.length > 0">
                            <div class="m-sec">
                                <div class="m-title" x-text="trans.certifications"></div>
                                <template x-for="cert in certifications.items">
                                    <div class="cert-item">
                                        <div class="cert-name" x-text="cert.name"></div>
                                        <div class="cert-org" x-text="cert.organization"></div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- 3. ELEGANT -->
            <template x-if="selectedStyle === 'elegant'">
                <div class="tpl-elegant">
                    <div class="hdr">
                        <template x-if="contact.photo">
                            <img :src="contact.photo" class="photo" alt="photo">
                        </template>
                        <h1 x-text="contact.name || 'Your Name'"></h1>
                        <div class="job-title" x-text="contact.title || 'Professional Title'"></div>
                        <div class="hdr-contact">
                            <template x-if="contact.email"><div class="hdr-contact-item"><i class="fa-regular fa-envelope"></i><span x-text="contact.email"></span></div></template>
                            <template x-if="contact.phone"><div class="hdr-contact-item"><i class="fa-solid fa-phone"></i><span x-text="contact.phone"></span></div></template>
                            <template x-if="contact.address"><div class="hdr-contact-item"><i class="fa-solid fa-location-dot"></i><span x-text="contact.address"></span></div></template>
                            <template x-if="contact.date_of_birth"><div class="hdr-contact-item"><i class="fa-regular fa-calendar"></i><span x-text="contact.date_of_birth"></span></div></template>
                        </div>
                    </div>
                    <div class="body">
                        <div>
                            <template x-if="summary.text">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.about"></div>
                                    <p class="summary-text" x-text="summary.text"></p>
                                </div>
                            </template>
                            <template x-if="experience.items && experience.items.length > 0">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.experience"></div>
                                    <template x-for="job in experience.items">
                                        <div class="item">
                                            <div class="item-head">
                                                <span class="item-role" x-text="job.role"></span>
                                                <span class="item-dur" x-text="formatDateRange(job)"></span>
                                            </div>
                                            <div class="item-company" x-text="job.company"></div>
                                            <div class="item-desc" x-text="job.description"></div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                        <div>
                            <template x-if="skills.list && skills.list.length > 0">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.skills"></div>
                                    <template x-for="sk in skills.list">
                                        <div class="tag" x-text="sk"></div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="education.items && education.items.length > 0">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.education"></div>
                                    <template x-for="edu in education.items">
                                        <div class="edu-item">
                                            <div class="edu-degree" x-text="edu.degree"></div>
                                            <div class="edu-school" x-text="edu.school"></div>
                                            <div class="edu-year" x-text="edu.year"></div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="languages.items && languages.items.length > 0">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.languages"></div>
                                    <template x-for="lang in languages.items">
                                        <div class="lang-item">
                                            <span x-text="lang.language"></span>
                                            <span class="lang-level" x-text="lang.level"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

            <!-- 4. CIRCULAR -->
            <template x-if="selectedStyle === 'circular'">
                <div class="tpl-circular">
                    <div class="sidebar">
                        <div class="sidebar-top">
                            <template x-if="contact.photo">
                                <img :src="contact.photo" class="avatar" alt="photo">
                            </template>
                            <template x-if="!contact.photo">
                                <div class="avatar-initials" x-text="contact.name ? contact.name.charAt(0) : 'U'"></div>
                            </template>
                            <div class="s-name" x-text="contact.name || 'Your Name'"></div>
                            <div class="s-title" x-text="contact.title || ''"></div>
                        </div>
                        <div class="s-section">
                            <div class="s-label" x-text="trans.contact"></div>
                            <template x-if="contact.email"><div class="contact-item"><i class="fa-regular fa-envelope"></i><span x-text="contact.email"></span></div></template>
                            <template x-if="contact.phone"><div class="contact-item"><i class="fa-solid fa-phone"></i><span x-text="contact.phone"></span></div></template>
                            <template x-if="contact.address"><div class="contact-item"><i class="fa-solid fa-location-dot"></i><span x-text="contact.address"></span></div></template>
                            <template x-if="contact.date_of_birth"><div class="contact-item"><i class="fa-regular fa-calendar"></i><span x-text="contact.date_of_birth"></span></div></template>
                        </div>
                        <template x-if="skills.list && skills.list.length > 0">
                            <div class="s-section">
                                <div class="s-label" x-text="trans.skills"></div>
                                <div>
                                    <template x-for="sk in skills.list">
                                        <span class="skill-chip" x-text="sk"></span>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template x-if="languages.items && languages.items.length > 0">
                            <div class="s-section">
                                <div class="s-label" x-text="trans.languages"></div>
                                <template x-for="lang in languages.items">
                                    <div class="lang-row">
                                        <span class="lang-name" x-text="lang.language"></span>
                                        <span class="lang-badge" x-text="lang.level"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                    <div class="main">
                        <template x-if="summary.text">
                            <div class="m-sec">
                                <div class="m-title" x-text="trans.about"></div>
                                <p class="summary-text" x-text="summary.text"></p>
                            </div>
                        </template>
                        <template x-if="experience.items && experience.items.length > 0">
                            <div class="m-sec">
                                <div class="m-title" x-text="trans.experience"></div>
                                <div class="timeline">
                                    <template x-for="job in experience.items">
                                        <div class="tl-item">
                                            <div class="tl-dot"></div>
                                            <div class="item-role" x-text="job.role"></div>
                                            <span class="item-dur" x-text="formatDateRange(job)"></span>
                                            <div class="item-company" x-text="job.company"></div>
                                            <div class="item-desc" x-text="job.description"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template x-if="education.items && education.items.length > 0">
                            <div class="m-sec">
                                <div class="m-title" x-text="trans.education"></div>
                                <template x-for="edu in education.items">
                                    <div class="edu-card">
                                        <div>
                                            <div class="edu-degree" x-text="edu.degree"></div>
                                            <div class="edu-school" x-text="edu.school"></div>
                                        </div>
                                        <div class="edu-year" x-text="edu.year"></div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- 5. CHRONO -->
            <template x-if="selectedStyle === 'chrono'">
                <div class="tpl-chrono">
                    <div class="hdr">
                        <h1 x-text="contact.name || 'Your Name'"></h1>
                        <div class="role" x-text="contact.title || ''"></div>
                        <div class="contact-pills">
                            <template x-if="contact.email"><div class="pill"><i class="fa-regular fa-envelope"></i><span x-text="contact.email"></span></div></template>
                            <template x-if="contact.phone"><div class="pill"><i class="fa-solid fa-phone"></i><span x-text="contact.phone"></span></div></template>
                            <template x-if="contact.address"><div class="pill"><i class="fa-solid fa-location-dot"></i><span x-text="contact.address"></span></div></template>
                            <template x-if="contact.date_of_birth"><div class="pill"><i class="fa-regular fa-calendar"></i><span x-text="contact.date_of_birth"></span></div></template>
                        </div>
                    </div>
                    <div class="body">
                        <template x-if="summary.text">
                            <div class="sec">
                                <div class="sec-title" x-text="trans.about"></div>
                                <p class="summary-text" x-text="summary.text"></p>
                            </div>
                        </template>
                        <template x-if="experience.items && experience.items.length > 0">
                            <div class="sec">
                                <div class="sec-title" x-text="trans.experience"></div>
                                <template x-for="job in experience.items">
                                    <div class="item">
                                        <div class="item-role" x-text="job.role"></div>
                                        <span class="item-dur" x-text="formatDateRange(job)"></span>
                                        <div class="item-company" x-text="job.company"></div>
                                        <div class="item-desc" x-text="job.description"></div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="skills.list && skills.list.length > 0">
                            <div class="sec">
                                <div class="sec-title" x-text="trans.skills"></div>
                                <template x-for="sk in skills.list">
                                    <span class="tag" x-text="sk"></span>
                                </template>
                            </div>
                        </template>
                        <template x-if="education.items && education.items.length > 0">
                            <div class="sec">
                                <div class="sec-title" x-text="trans.education"></div>
                                <template x-for="edu in education.items">
                                    <div class="edu-item">
                                        <div class="edu-degree" x-text="edu.degree"></div>
                                        <div class="edu-school" x-text="edu.school"></div>
                                        <div class="edu-year" x-text="edu.year"></div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- 6. VERTICAL -->
            <template x-if="selectedStyle === 'vertical'">
                <div class="tpl-vertical">
                    <div class="stripe"></div>
                    <div class="sidebar">
                        <template x-if="contact.photo">
                            <img :src="contact.photo" class="avatar" alt="photo">
                        </template>
                        <template x-if="!contact.photo">
                            <div class="avatar-initials" x-text="contact.name ? contact.name.charAt(0) : 'U'"></div>
                        </template>
                        <div class="s-name" x-text="contact.name || 'Your Name'"></div>
                        <div class="s-title" x-text="contact.title || ''"></div>
                        <div>
                            <div class="s-label" x-text="trans.contact"></div>
                            <template x-if="contact.email"><div class="c-item"><i class="fa-regular fa-envelope"></i><span x-text="contact.email"></span></div></template>
                            <template x-if="contact.phone"><div class="c-item"><i class="fa-solid fa-phone"></i><span x-text="contact.phone"></span></div></template>
                            <template x-if="contact.address"><div class="c-item"><i class="fa-solid fa-location-dot"></i><span x-text="contact.address"></span></div></template>
                            <template x-if="contact.date_of_birth"><div class="c-item"><i class="fa-regular fa-calendar"></i><span x-text="contact.date_of_birth"></span></div></template>
                        </div>
                        <template x-if="skills.list && skills.list.length > 0">
                            <div>
                                <div class="s-label" x-text="trans.skills"></div>
                                <template x-for="sk in skills.list">
                                    <div style="margin-bottom:6px">
                                        <div x-text="sk"></div>
                                        <div class="skill-track"><div class="skill-fill"></div></div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                    <div class="main">
                        <template x-if="summary.text">
                            <div>
                                <div class="m-sec-title" x-text="trans.about"></div>
                                <p class="summary-text" x-text="summary.text"></p>
                            </div>
                        </template>
                        <template x-if="experience.items && experience.items.length > 0">
                            <div>
                                <div class="m-sec-title" x-text="trans.experience"></div>
                                <template x-for="job in experience.items">
                                    <div class="item">
                                        <div class="item-role" x-text="job.role"></div>
                                        <span class="item-dur" x-text="formatDateRange(job)"></span>
                                        <div class="item-company" x-text="job.company"></div>
                                        <div class="item-desc" x-text="job.description"></div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="education.items && education.items.length > 0">
                            <div>
                                <div class="m-sec-title" x-text="trans.education"></div>
                                <template x-for="edu in education.items">
                                    <div class="edu-block">
                                        <div class="edu-degree" x-text="edu.degree"></div>
                                        <div class="edu-school" x-text="edu.school"></div>
                                        <div class="edu-year" x-text="edu.year"></div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- 7. HORIZONTAL -->
            <template x-if="selectedStyle === 'horizontal'">
                <div class="tpl-horizontal">
                    <div class="hdr">
                        <div class="hdr-inner">
                            <template x-if="contact.photo">
                                <img :src="contact.photo" class="photo" alt="photo">
                            </template>
                            <div class="hdr-title">
                                <h1 x-text="contact.name || 'Your Name'"></h1>
                                <div class="role" x-text="contact.title || ''"></div>
                            </div>
                        </div>
                        <div class="contact-row">
                            <template x-if="contact.email"><div class="c-pill"><i class="fa-regular fa-envelope"></i><span x-text="contact.email"></span></div></template>
                            <template x-if="contact.phone"><div class="c-pill"><i class="fa-solid fa-phone"></i><span x-text="contact.phone"></span></div></template>
                            <template x-if="contact.address"><div class="c-pill"><i class="fa-solid fa-location-dot"></i><span x-text="contact.address"></span></div></template>
                            <template x-if="contact.date_of_birth"><div class="c-pill"><i class="fa-regular fa-calendar"></i><span x-text="contact.date_of_birth"></span></div></template>
                        </div>
                    </div>
                    <div class="body">
                        <div class="col-a">
                            <template x-if="summary.text">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.about"></div>
                                    <p class="summary-text" x-text="summary.text"></p>
                                </div>
                            </template>
                            <template x-if="experience.items && experience.items.length > 0">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.experience"></div>
                                    <template x-for="job in experience.items">
                                        <div class="item">
                                            <div class="item-role" x-text="job.role"></div>
                                            <span class="item-dur" x-text="formatDateRange(job)"></span>
                                            <div class="item-company" x-text="job.company"></div>
                                            <div class="item-desc" x-text="job.description"></div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                        <div>
                            <template x-if="skills.list && skills.list.length > 0">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.skills"></div>
                                    <div class="tags">
                                        <template x-for="sk in skills.list">
                                            <span class="tag" x-text="sk"></span>
                                        </template>
                                    </div>
                                </div>
                            </template>
                            <template x-if="education.items && education.items.length > 0">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.education"></div>
                                    <template x-for="edu in education.items">
                                        <div class="edu-card">
                                            <div class="edu-degree" x-text="edu.degree"></div>
                                            <div class="edu-school" x-text="edu.school"></div>
                                            <div class="edu-year" x-text="edu.year"></div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

            <!-- 8. LUXURIOUS -->
            <template x-if="selectedStyle === 'luxurious'">
                <div class="tpl-luxurious">
                    <div class="hdr">
                        <div class="hdr-inner">
                            <div class="hdr-left">
                                <h1 x-text="contact.name || 'Your Name'"></h1>
                                <div class="role" x-text="contact.title || ''"></div>
                            </div>
                            <div class="hdr-right">
                                <template x-if="contact.email"><div x-text="contact.email"></div></template>
                                <template x-if="contact.phone"><div x-text="contact.phone"></div></template>
                                <template x-if="contact.address"><div x-text="contact.address"></div></template>
                                <template x-if="contact.date_of_birth"><div x-text="contact.date_of_birth"></div></template>
                            </div>
                            <template x-if="contact.photo">
                                <img :src="contact.photo" class="photo" alt="photo">
                            </template>
                        </div>
                    </div>
                    <div class="body">
                        <div>
                            <template x-if="summary.text">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.about"></div>
                                    <p class="summary-text" x-text="summary.text"></p>
                                </div>
                            </template>
                            <template x-if="experience.items && experience.items.length > 0">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.experience"></div>
                                    <template x-for="job in experience.items">
                                        <div class="item">
                                            <div class="item-role" x-text="job.role"></div>
                                            <span class="item-dur" x-text="formatDateRange(job)"></span>
                                            <div class="item-company" x-text="job.company"></div>
                                            <div class="item-desc" x-text="job.description"></div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                        <div>
                            <template x-if="skills.list && skills.list.length > 0">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.skills"></div>
                                    <template x-for="sk in skills.list">
                                        <span class="tag" x-text="sk"></span>
                                    </template>
                                </div>
                            </template>
                            <template x-if="education.items && education.items.length > 0">
                                <div class="sec">
                                    <div class="sec-title" x-text="trans.education"></div>
                                    <template x-for="edu in education.items">
                                        <div class="edu-item">
                                            <div class="edu-degree" x-text="edu.degree"></div>
                                            <div class="edu-school" x-text="edu.school"></div>
                                            <div class="edu-year" x-text="edu.year"></div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

        </div>
    </div>

    <!-- AI ASSISTANT MODAL / PANEL -->
    <x-modal id="ai-assistant" title="AI Resume Copilot">
        <div x-data="aiCopilot()" class="flex flex-col gap-5">
            <!-- Tabs -->
            <div class="flex border-b border-slate-200 dark:border-slate-800">
                <button @click="subTab = 'ats'" :class="subTab === 'ats' ? 'border-primary-500 text-primary-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="flex-1 py-2.5 text-xs font-semibold border-b-2">ATS Score Card</button>
                <button @click="subTab = 'grammar'" :class="subTab === 'grammar' ? 'border-primary-500 text-primary-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="flex-1 py-2.5 text-xs font-semibold border-b-2">Grammar & Typos</button>
                <button @click="subTab = 'job'" :class="subTab === 'job' ? 'border-primary-500 text-primary-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="flex-1 py-2.5 text-xs font-semibold border-b-2">Job Description Match</button>
            </div>

            <!-- ATS Score Card Tab -->
            <div x-show="subTab === 'ats'" class="flex flex-col gap-4">
                <div class="flex justify-between items-center bg-slate-50 dark:bg-slate-850 p-4 rounded-xl">
                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-slate-100">AI ATS Scoring Check</h4>
                        <p class="text-xs text-slate-500">Evaluates content complexity and section representation.</p>
                    </div>
                    <button @click="runATS()" class="px-3.5 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-xs font-bold transition" :disabled="loading">
                        <span x-show="!loading">Analyze</span>
                        <span x-show="loading"><i class="fa-solid fa-spinner animate-spin"></i></span>
                    </button>
                </div>

                <div x-show="atsResult" x-cloak class="flex flex-col gap-3">
                    <div class="flex items-center gap-3">
                        <div class="text-3xl font-black font-outfit" :class="atsResult.score >= 70 ? 'text-emerald-500' : 'text-amber-500'" x-text="atsResult.score + '%'"></div>
                        <div class="text-xs text-slate-500">Recommended score: 75% for enterprise ATS engines.</div>
                    </div>

                    <!-- Suggestions list -->
                    <div>
                        <h5 class="text-xs font-bold text-slate-800 dark:text-slate-200 mb-1">ATS Optimization Suggestions:</h5>
                        <ul class="text-xs text-slate-500 flex flex-col gap-1 list-disc pl-4">
                            <template x-for="item in (atsResult.feedback_data?.suggestions || atsResult.feedback_data?.recommendations || [])">
                                <li x-text="item"></li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Grammar Tab -->
            <div x-show="subTab === 'grammar'" class="flex flex-col gap-4">
                <div class="flex justify-between items-center bg-slate-50 dark:bg-slate-850 p-4 rounded-xl">
                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-slate-100">AI Grammar Audit</h4>
                        <p class="text-xs text-slate-500 font-semibold">Flags spelling issues and stylistic suggestions.</p>
                    </div>
                    <button @click="runGrammar()" class="px-3.5 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-xs font-bold transition" :disabled="loading">
                        <span x-show="!loading">Audit</span>
                        <span x-show="loading"><i class="fa-solid fa-spinner animate-spin"></i></span>
                    </button>
                </div>

                <div x-show="grammarResult" x-cloak class="flex flex-col gap-3 text-xs text-slate-500">
                    <h5 class="font-bold text-slate-800 dark:text-slate-200">Issues Flagged:</h5>
                    <ul class="flex flex-col gap-2">
                        <template x-for="issue in (grammarResult.feedback_data?.corrections || [])">
                            <li class="bg-rose-50 dark:bg-rose-950/20 p-2.5 rounded-lg border border-rose-100 dark:border-rose-900/35">
                                <p class="font-bold text-rose-700">Original: "<span x-text="issue.original"></span>"</p>
                                <p class="font-semibold text-emerald-600 mt-0.5">Suggestion: "<span x-text="issue.suggestion || issue.replacement"></span>"</p>
                                <p class="text-[10px] text-slate-400 mt-0.5" x-text="issue.reason"></p>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <!-- Job Description Match Tab -->
            <div x-show="subTab === 'job'" class="flex flex-col gap-4">
                <div class="flex flex-col gap-3">
                    <x-input label="Target Job Title" name="job_title_target" placeholder="Senior Backend Developer" model="jobTitle" />
                    <x-textarea label="Paste Job Description" name="job_desc_target" placeholder="Paste the job listing requirements here..." model="jobDesc" rows="4" />

                    <button @click="runJobMatch()" class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition" :disabled="loading || !jobTitle || !jobDesc">
                        <span x-show="!loading">Analyze Alignment</span>
                        <span x-show="loading"><i class="fa-solid fa-spinner animate-spin"></i> Analyzing...</span>
                    </button>
                </div>

                <div x-show="jobResult" x-cloak class="flex flex-col gap-3">
                    <div class="flex items-center gap-3">
                        <div class="text-3xl font-black font-outfit text-purple-600" x-text="jobResult.match_score + '%'"></div>
                        <div class="text-xs text-slate-500 font-bold">Matching alignment with job description properties.</div>
                    </div>
                    <div>
                        <h5 class="text-xs font-bold text-slate-800 dark:text-slate-200 mb-1">Keywords Missing:</h5>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="kw in (jobResult.analysis_data?.missing_keywords || [])">
                                <span class="px-2 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded-md text-[10px]" x-text="kw"></span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </x-modal>

</div>
@endsection

@section('scripts')
@php
    $builderSelectedStyle = $resume->template?->style ?? 'professional';
    $builderContact = $sections->get('contact')?->content ?? ['name' => '', 'title' => '', 'email' => '', 'phone' => '', 'phone_country' => '+998', 'address' => '', 'photo' => '', 'date_of_birth' => ''];
    $builderSummary = $sections->get('summary')?->content ?? ['text' => ''];
    $builderSkills = $sections->get('skills')?->content ?? ['list' => []];
    $builderExperience = $sections->get('experience')?->content ?? ['items' => []];
    $builderEducation = $sections->get('education')?->content ?? ['items' => []];
    $builderCertifications = $sections->get('certifications')?->content ?? ['items' => []];
    $builderLanguages = $sections->get('languages')?->content ?? ['items' => []];
@endphp
<script>
    requireAuth();

    function getBuilderState() {
        const builder = document.querySelector('[data-resume-builder]');
        return builder ? Alpine.$data(builder) : null;
    }

    function buildResumePlainText(state) {
        if (!state) return '';

        const parts = [
            state.title,
            state.contact?.name,
            state.contact?.title,
            state.contact?.email,
            state.contact?.phone,
            state.contact?.address,
            state.summary?.text,
            ...(state.skills?.list || []),
            ...(state.experience?.items || []).flatMap((job) => [job.company, job.role, job.duration, job.description]),
            ...(state.education?.items || []).flatMap((edu) => [edu.school, edu.degree, edu.year]),
            ...(state.certifications?.items || []).flatMap((certificate) => [certificate.name, certificate.organization, certificate.issue_date, certificate.credential_id]),
            ...(state.languages?.items || []).flatMap((language) => [language.language, language.level]),
        ];

        return parts.filter(Boolean).join('\n');
    }

    function resumeBuilder() {
        return {
            resumeId: @js($resume->id),
            title: @js($resume->title),
            templateId: @js($resume->template_id),
            selectedStyle: @js($builderSelectedStyle),

            // Translations (server-injected, locale-aware)
            trans: @js([
                'about'          => __('app.sec_about'),
                'experience'     => __('app.sec_experience'),
                'education'      => __('app.sec_education'),
                'skills'         => __('app.sec_skills'),
                'languages'      => __('app.sec_languages'),
                'certifications' => __('app.sec_certifications'),
                'contact'        => __('app.sec_contact'),
                'projects'       => __('app.sec_projects'),
            ]),
            // Sections data
            contact: @js($builderContact),
            summary: @js($builderSummary),
            skills: @js($builderSkills),
            experience: @js($builderExperience),
            education: @js($builderEducation),
            certifications: @js($builderCertifications),
            languages: @js($builderLanguages),

            activeTab: 'contact',
            saveStatus: 'Saved',
            saveTimeout: null,
            autoSaveInterval: null,
            hasUnsavedChanges: false,
            jobRole: '',
            selectedRole: null,

            // yearRange is used by the date picker selects in Experience and Education tabs.
            // WHY COMPUTED HERE: Avoids hardcoding years in the template.
            // Range: 2 years into future (for expected graduation) to 1980.
            yearRange: Array.from({ length: new Date().getFullYear() + 3 - 1980 }, (_, i) => new Date().getFullYear() + 2 - i),
            jobRoles: [
                { name: 'Backend Developer', skills: ['PHP', 'Laravel', 'PostgreSQL', 'Redis', 'Docker', 'REST API'], summary: 'Backend Developer with experience building scalable web applications using Laravel and PostgreSQL.', experience: 'Built REST APIs, optimized database queries, and integrated Redis caching for high-traffic services.' },
                { name: 'Frontend Developer', skills: ['JavaScript', 'Vue', 'React', 'Tailwind CSS', 'API Integration'], summary: 'Frontend Developer focused on responsive interfaces, reusable components, and accessible user experiences.', experience: 'Developed interactive dashboards and integrated frontend workflows with REST APIs.' },
                { name: 'Full Stack Developer', skills: ['Laravel', 'Vue', 'PostgreSQL', 'Docker', 'CI/CD'], summary: 'Full Stack Developer experienced in delivering complete web products from database design to polished UI.', experience: 'Delivered end-to-end features across backend APIs, database schemas, and frontend components.' },
                { name: 'Mobile Developer', skills: ['Flutter', 'React Native', 'Firebase', 'REST API', 'App Store'], summary: 'Mobile Developer building performant cross-platform apps with clean architecture and API integrations.', experience: 'Implemented mobile authentication, offline storage, and push notification workflows.' },
                { name: 'DevOps Engineer', skills: ['Docker', 'Kubernetes', 'CI/CD', 'AWS', 'Linux', 'Monitoring'], summary: 'DevOps Engineer experienced in deployment automation, cloud infrastructure, and reliable production systems.', experience: 'Built CI/CD pipelines and improved deployment reliability with containerized infrastructure.' },
                { name: 'QA Engineer', skills: ['Manual Testing', 'Automation', 'Selenium', 'API Testing', 'Bug Tracking'], summary: 'QA Engineer focused on test planning, automation, and improving release quality.', experience: 'Created regression test suites and validated API behavior across release cycles.' },
                { name: 'Data Analyst', skills: ['SQL', 'Excel', 'Power BI', 'Python', 'Data Visualization'], summary: 'Data Analyst turning business data into actionable reports and clear visual insights.', experience: 'Built dashboards and analyzed operational datasets to support business decisions.' },
                { name: 'Data Scientist', skills: ['Python', 'Machine Learning', 'Pandas', 'SQL', 'Statistics'], summary: 'Data Scientist experienced in modeling, experimentation, and insight generation from complex datasets.', experience: 'Built predictive models and evaluated performance using statistical validation.' },
                { name: 'Product Manager', skills: ['Roadmap', 'User Research', 'Agile', 'Analytics', 'Stakeholder Management'], summary: 'Product Manager aligning user needs, business goals, and engineering execution.', experience: 'Led product discovery, prioritized roadmap items, and coordinated cross-functional delivery.' },
                { name: 'UI/UX Designer', skills: ['Figma', 'Wireframing', 'User Research', 'Design Systems', 'Prototyping'], summary: 'UI/UX Designer crafting user-centered flows, polished interfaces, and scalable design systems.', experience: 'Designed prototypes and improved usability through research-driven iteration.' },
                { name: 'Graphic Designer', skills: ['Adobe Photoshop', 'Illustrator', 'Branding', 'Typography', 'Layout'], summary: 'Graphic Designer creating visual identities, marketing assets, and consistent brand systems.', experience: 'Produced campaign assets and refined brand visuals across digital channels.' },
                { name: 'Cybersecurity Specialist', skills: ['Network Security', 'SIEM', 'Vulnerability Assessment', 'Incident Response'], summary: 'Cybersecurity Specialist protecting systems through monitoring, assessment, and incident response.', experience: 'Performed vulnerability assessments and improved security monitoring workflows.' },
                { name: 'System Administrator', skills: ['Linux', 'Windows Server', 'Networking', 'Backups', 'Monitoring'], summary: 'System Administrator maintaining secure, stable, and well-monitored IT infrastructure.', experience: 'Managed servers, backups, user access, and operational monitoring.' },
                { name: 'Business Analyst', skills: ['Requirements', 'Process Mapping', 'SQL', 'Stakeholder Interviews', 'Documentation'], summary: 'Business Analyst translating business needs into clear requirements and actionable improvements.', experience: 'Mapped workflows, documented requirements, and supported delivery teams with analysis.' },
            ],
            phoneCountries: [
                { code: '+998', label: 'UZ +998' },
                { code: '+1', label: 'US +1' },
                { code: '+44', label: 'UK +44' },
                { code: '+49', label: 'DE +49' },
                { code: '+33', label: 'FR +33' },
                { code: '+7', label: 'RU/KZ +7' },
                { code: '+82', label: 'KR +82' },
                { code: '+86', label: 'CN +86' },
                { code: '+91', label: 'IN +91' },
                { code: '+971', label: 'AE +971' },
                { code: '+966', label: 'SA +966' },
                { code: '+90', label: 'TR +90' },
            ],

            tabs: [
                { id: 'contact',        name: @js(__('app.sec_contact')),        icon: 'fa-regular fa-address-card' },
                { id: 'summary',        name: @js(__('app.sec_about')),           icon: 'fa-regular fa-file-text' },
                { id: 'skills',         name: @js(__('app.sec_skills')),          icon: 'fa-solid fa-code' },
                { id: 'experience',     name: @js(__('app.sec_experience')),      icon: 'fa-solid fa-briefcase' },
                { id: 'education',      name: @js(__('app.sec_education')),       icon: 'fa-solid fa-graduation-cap' },
                { id: 'certifications', name: @js(__('app.sec_certifications')),  icon: 'fa-solid fa-certificate' },
                { id: 'languages',      name: @js(__('app.sec_languages')),       icon: 'fa-solid fa-language' }
            ],

            init() {
                // Ensure arrays are initialized
                if (!this.skills.list) this.skills.list = [];
                if (!this.experience.items) this.experience.items = [];
                if (!this.education.items) this.education.items = [];
                if (!this.certifications.items) this.certifications.items = [];
                if (!this.languages.items) this.languages.items = [];
                if (typeof this.contact.photo === 'undefined') this.contact.photo = '';
                if (!this.contact.phone_country) this.contact.phone_country = this.detectPhoneCountry(this.contact.phone);
                this.autoSaveInterval = setInterval(() => {
                    if (this.hasUnsavedChanges) this.saveData();
                }, 10000);
                window.addEventListener('beforeunload', (event) => {
                    if (!this.hasUnsavedChanges) return;
                    event.preventDefault();
                    event.returnValue = '';
                });
            },

            detectPhoneCountry(phone) {
                const value = (phone || '').trim();
                const match = this.phoneCountries.find((country) => value.startsWith(country.code));
                return match ? match.code : '+998';
            },

            applyPhoneCountry() {
                const code = this.contact.phone_country || '+998';
                const current = (this.contact.phone || '').trim();
                const withoutCode = current.replace(/^\+\d{1,4}\s*/, '');
                this.contact.phone = `${code}${withoutCode ? ' ' + withoutCode : ' '}`;
                this.triggerAutoSave();
            },

            handlePhotoUpload(event) {
                const file = event.target.files?.[0];

                if (!file) return;

                if (!file.type.startsWith('image/')) {
                    showToast('Please choose an image file.', 'warning');
                    event.target.value = '';
                    return;
                }

                if (file.size > 1024 * 1024) {
                    showToast('Photo must be under 1 MB.', 'warning');
                    event.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = () => {
                    this.contact.photo = reader.result;
                    this.triggerAutoSave();
                };
                reader.readAsDataURL(file);
            },

            removePhoto() {
                this.contact.photo = '';
                const input = document.getElementById('c_photo');
                if (input) input.value = '';
                this.triggerAutoSave();
            },

            getTemplateName() {
                const templates = {
                    circular: 'Circular Layout',
                    professional: 'Professional Layout',
                    vertical: 'Vertical Stripe Layout',
                    horizontal: 'Horizontal Accent Layout',
                    elegant: 'Elegant Crimson Layout',
                    modern: 'Terracotta Modern Layout',
                    casual: 'Casual Gold Layout',
                    chrono: 'Chrono Timeline Layout',
                    luxurious: 'Rose Luxurious Layout'
                };
                return templates[this.selectedStyle] || 'Default Layout';
            },

            getPreviewFontStyle() {
                const fonts = {
                    circular: 'font-family: Inter, sans-serif',
                    professional: 'font-family: Outfit, sans-serif',
                    vertical: 'font-family: Roboto, sans-serif',
                    elegant: 'font-family: Merriweather, serif',
                    modern: 'font-family: Nunito, sans-serif',
                    luxurious: 'font-family: Playfair Display, serif'
                };
                return fonts[this.selectedStyle] || 'font-family: Inter, sans-serif';
            },

            addJob() {
                // New structured fields: start_date, end_date, is_present
                // WHY: Storing dates as "YYYY-MM" strings instead of free-text "Jan 2024 - Present"
                // allows consistent display formatting and future date validation.
                this.experience.items.push({
                    company: '',
                    role: '',
                    description: '',
                    start_date: '',
                    end_date: null,
                    is_present: false,
                    start_month: '',
                    start_year: '',
                    end_month: '',
                    end_year: '',
                    // `duration` is kept for backward compatibility with old data
                    // and is computed dynamically by ResumeTemplateRenderer
                    duration: ''
                });
                this.triggerAutoSave();
            },

            addEducation() {
                this.education.items.push({
                    school: '',
                    degree: '',
                    start_date: '',
                    end_date: null,
                    is_present: false,
                    start_month: '',
                    start_year: '',
                    end_month: '',
                    end_year: '',
                    year: ''
                });
                this.triggerAutoSave();
            },

            addCertificate() {
                this.certifications.items.push({ name: '', organization: '', issue_date: '', credential_id: '' });
                this.triggerAutoSave();
            },

            addLanguage() {
                this.languages.items.push({ language: '', level: 'Intermediate' });
                this.triggerAutoSave();
            },

            addSuggestedSkill(skill) {
                if (!this.skills.list.includes(skill)) {
                    this.skills.list.push(skill);
                    this.triggerAutoSave();
                }
            },

            applyJobRole() {
                this.selectedRole = this.jobRoles.find((role) => role.name === this.jobRole) || null;
                if (!this.selectedRole) return;

                // Only show suggestions for the selected job role.
                // Do not auto-fill summary, skills, or experience so the user can edit them manually.
            },

            switchTemplate(id, style) {
                this.templateId = id;
                this.selectedStyle = style;
                this.triggerAutoSave();
            },

            triggerAutoSave() {
                this.saveStatus = 'Unsaved';
                this.hasUnsavedChanges = true;

                if (this.saveTimeout) clearTimeout(this.saveTimeout);

                this.saveTimeout = setTimeout(() => {
                    this.saveData();
                }, 1000);
            },

            /**
             * formatDateRange — compute a human-readable duration string from structured data.
             *
             * WHY THIS EXISTS IN JS:
             * The live preview in the builder is rendered by Alpine.js (client-side).
             * The PDF/server-side preview uses ResumeTemplateRenderer::formatDateRange() (PHP).
             * Both use the same logic so the preview and PDF always match.
             *
             * INPUT:  { start_date: "2022-01", end_date: "2025-03", is_present: false }
             * OUTPUT: "Jan 2022 – Mar 2025"
             */
            formatDateRange(item) {
                const months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                const fmtDate = (dateStr) => {
                    if (!dateStr) return '';
                    const parts = dateStr.split('-');
                    if (parts.length < 2) return dateStr;
                    const m = parseInt(parts[1], 10);
                    return (months[m] || '') + ' ' + parts[0];
                };

                const start = fmtDate(item.start_date);

                if (item.is_present) {
                    return start ? `${start} – Present` : 'Present';
                }

                if (item.end_date) {
                    const end = fmtDate(item.end_date);
                    return start && end ? `${start} – ${end}` : (start || end);
                }

                return start;
            },

            async saveData() {
                try {
                    const payload = {
                        title: this.title,
                        template_id: this.templateId,
                        sections: [
                            { section_type: 'contact', content: this.contact, order_index: 1 },
                            { section_type: 'summary', content: this.summary, order_index: 2 },
                            { section_type: 'skills', content: this.skills, order_index: 3 },
                            { section_type: 'experience', content: this.experience, order_index: 4 },
                            { section_type: 'education', content: this.education, order_index: 5 },
                            { section_type: 'certifications', content: this.certifications, order_index: 6 },
                            { section_type: 'languages', content: this.languages, order_index: 7 }
                        ]
                    };

                    const response = await fetch(`/api/resumes/${this.resumeId}`, {
                        method: 'PUT',
                        headers: getAuthHeaders(),
                        body: JSON.stringify(payload)
                    });

                    if (!response.ok) throw new Error('Auto-save failed.');

                    this.saveStatus = 'Saved';
                    this.hasUnsavedChanges = false;
                } catch (e) {
                    this.saveStatus = 'Failed to save';
                }
            },

            get completionPercent() {
                const checks = [
                    this.contact.name,
                    this.contact.email,
                    this.contact.phone,
                    this.summary.text,
                    this.skills.list.length > 0,
                    this.experience.items.length > 0,
                    this.education.items.length > 0,
                    this.certifications.items.length > 0,
                    this.languages.items.length > 0,
                    this.templateId,
                ];
                return Math.round((checks.filter(Boolean).length / checks.length) * 100);
            },

            get resumeScore() {
                return Math.min(100, Math.round(this.completionPercent * 0.75 + Math.min(this.skills.list.length * 3, 15) + Math.min(this.experience.items.length * 5, 10)));
            },

            openAIModal() {
                this.$dispatch('open-modal-ai-assistant');
            },

            async improveText(fieldPath) {
                // Get current value
                let val = '';
                try {
                    val = eval('this.' + fieldPath);
                } catch (e) {}

                if (!val.trim()) {
                    showToast('Please type some text first before optimizing.', 'warning');
                    return;
                }

                showToast('AI is optimizing text...', 'info');

                try {
                    const response = await fetch(`/api/resumes/${this.resumeId}/grammar-check`, {
                        method: 'POST',
                        headers: getAuthHeaders(),
                        body: JSON.stringify({ text: val })
                    });

                    const data = await response.json();

                    if (response.ok && data.review && data.review.feedback_data) {
                        const improved = data.review.feedback_data.improved_text || val + " (AI-Optimized)";
                        // Set value
                        eval('this.' + fieldPath + ' = improved');
                        this.triggerAutoSave();
                        showToast('Text optimized with AI!', 'success');
                    } else {
                        throw new Error('AI Service unavailable.');
                    }
                } catch (e) {
                    showToast('AI improvement failed. Using mock enhancement.', 'info');
                    eval('this.' + fieldPath + ' = val + " (Refined, professional, and optimized for key performance metrics)"');
                    this.triggerAutoSave();
                }
            }
        }
    }

    // AI copilot controller
    function aiCopilot() {
        return {
            resumeId: @js($resume->id),
            subTab: 'ats',
            loading: false,
            atsResult: null,
            grammarResult: null,
            jobResult: null,
            jobTitle: '',
            jobDesc: '',

            async runATS() {
                this.loading = true;
                try {
                    const res = await fetch(`/api/resumes/${this.resumeId}/ats-analyze`, {
                        method: 'POST',
                        headers: getAuthHeaders()
                    });
                    const data = await res.json();
                    if (res.ok) {
                        this.atsResult = data.review;
                        showToast('ATS Scan complete!', 'success');
                    } else {
                        throw new Error(data.message || 'ATS check failed.');
                    }
                } catch (e) {
                    showToast(e.message, 'error');
                } finally {
                    this.loading = false;
                }
            },

            async runGrammar() {
                this.loading = true;
                try {
                    const text = buildResumePlainText(getBuilderState());

                    if (!text.trim()) {
                        throw new Error('Please add resume content before running grammar audit.');
                    }

                    const res = await fetch(`/api/resumes/${this.resumeId}/grammar-check`, {
                        method: 'POST',
                        headers: getAuthHeaders(),
                        body: JSON.stringify({ text })
                    });
                    const data = await res.json();
                    if (res.ok) {
                        this.grammarResult = data.review;
                        showToast('Grammar Audit complete!', 'success');
                    } else {
                        throw new Error(data.message || 'Grammar check failed.');
                    }
                } catch (e) {
                    showToast(e.message, 'error');
                } finally {
                    this.loading = false;
                }
            },

            async runJobMatch() {
                this.loading = true;
                try {
                    const res = await fetch(`/api/resumes/${this.resumeId}/job-match`, {
                        method: 'POST',
                        headers: getAuthHeaders(),
                        body: JSON.stringify({
                            job_title: this.jobTitle,
                            job_description: this.jobDesc
                        })
                    });
                    const data = await res.json();
                    if (res.ok) {
                        this.jobResult = data.target;
                        showToast('Job Alignment Check Complete!', 'success');
                    } else {
                        throw new Error(data.message || 'Job match check failed.');
                    }
                } catch (e) {
                    showToast(e.message, 'error');
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endsection
