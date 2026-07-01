{{--
  SHARED PDF/WEB BASE START
  ─────────────────────────────────────────────────────────────────────────
  When rendering for PDF ($for_pdf = true), outputs the full standalone HTML shell.
  When rendering for live website preview ($for_pdf = false), outputs a safe wrapper div.
--}}
@if($for_pdf ?? false)
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  /* Load Google Fonts */
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@400;600;700;800;900&family=Roboto:wght@400;500;700;900&family=Merriweather:wght@400;700;900&family=Nunito:wght@400;600;700;800;900&family=Playfair+Display:wght@400;600;700;800&display=swap');

  /* Page Setup */
  @page { size: A4 portrait; margin: 0; }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  html, body { width: 210mm; min-height: 297mm; background: #fff; }
  body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
</style>
</head>
<body style="--primary: {{ $primaryColor ?? '#2563eb' }}; --accent: {{ $accentColor ?? '#60a5fa' }};">
@else
<div class="resume-wrapper" style="--primary: {{ $primaryColor ?? '#2563eb' }}; --accent: {{ $accentColor ?? '#60a5fa' }};">
@endif
