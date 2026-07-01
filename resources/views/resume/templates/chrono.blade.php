@include('resume.templates._base_start')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:'Inter',sans-serif;font-size:14px;color:#1e293b;line-height:1.6;background:#fff}
  .sheet{width:100%;min-height:100%}

  /* ─── HEADER ─── */
  .hdr{background:#059669;padding:36px 40px;color:#fff;position:relative;overflow:hidden}
  .hdr::after{content:'';position:absolute;right:-60px;top:-60px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,.07)}
  .hdr::before{content:'';position:absolute;right:60px;bottom:-80px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,.05)}
  .hdr-content{position:relative;z-index:1;display:flex;justify-content:space-between;align-items:flex-start;gap:20px}
  .hdr-left h1{font-size:38px;font-weight:900;letter-spacing:-1px;margin-bottom:6px}
  .hdr-left .role{font-size:15px;color:#a7f3d0;font-weight:500;margin-bottom:16px}
  .contact-pills{display:flex;flex-wrap:wrap;gap:10px}
  .pill{display:flex;align-items:center;gap:6px;font-size:12px;color:#d1fae5;background:rgba(255,255,255,.12);padding:5px 12px;border-radius:20px}
  .pill i{font-size:11px;color:#6ee7b7}
  .photo{width:86px;height:86px;border-radius:12px;object-fit:cover;border:3px solid rgba(255,255,255,.3);flex-shrink:0}

  /* ─── BODY ─── */
  .body{padding:36px 40px;columns:2;column-gap:36px}
  .sec{break-inside:avoid;margin-bottom:28px}
  .sec-title{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.13em;color:#059669;margin-bottom:14px;padding-bottom:7px;border-bottom:2px solid #d1fae5}

  .summary-text{font-size:14px;color:#475569;line-height:1.75}

  .item{margin-bottom:18px;padding-bottom:18px;border-bottom:1px solid #f0fdf4}
  .item:last-child{border-bottom:none;margin-bottom:0;padding-bottom:0}
  .item-head{display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:3px}
  .item-role{font-size:15px;font-weight:700;color:#0f172a}
  .item-dur{font-size:11px;color:#64748b;background:#f0fdf4;padding:2px 8px;border-radius:20px;font-weight:600;white-space:nowrap}
  .item-company{font-size:13px;color:#059669;font-weight:600;margin-bottom:5px}
  .item-desc{font-size:13px;color:#475569;line-height:1.65}

  .tags{display:flex;flex-wrap:wrap;gap:6px}
  .tag{background:#ecfdf5;color:#065f46;font-size:12px;font-weight:600;padding:5px 11px;border-radius:7px;border:1px solid #a7f3d0}

  .edu-item{margin-bottom:14px;background:#f0fdf4;border-radius:8px;padding:12px 14px}
  .edu-degree{font-size:14px;font-weight:700;color:#0f172a}
  .edu-school{font-size:13px;color:#059669;font-weight:500;margin:2px 0}
  .edu-year{font-size:12px;color:#64748b}

  .cert-item{padding:10px 0;border-bottom:1px solid #f0fdf4}
  .cert-item:last-child{border-bottom:none}
  .cert-name{font-size:13px;font-weight:700;color:#0f172a}
  .cert-org{font-size:12px;color:#059669;font-weight:500}
  .cert-date{font-size:11px;color:#94a3b8}

  .lang-row{display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid #f0fdf4}
  .lang-row:last-child{border-bottom:none}
  .lang-name{font-size:13px;font-weight:600;color:#1e293b}
  .lang-level{font-size:11px;color:#059669;background:#ecfdf5;padding:2px 8px;border-radius:10px;font-weight:600}
</style>

<div class="sheet">
  <div class="hdr">
    <div class="hdr-content">
      <div class="hdr-left">
        <h1>{{ $contact['name'] ?? 'Your Name' }}</h1>
        <div class="role">{{ $contact['title'] ?? '' }}</div>
        <div class="contact-pills">
          @if(!empty($contact['email']))<div class="pill"><i class="fa-regular fa-envelope"></i>{{ $contact['email'] }}</div>@endif
          @if(!empty($contact['phone']))<div class="pill"><i class="fa-solid fa-phone"></i>{{ $contact['phone'] }}</div>@endif
          @if(!empty($contact['address']))<div class="pill"><i class="fa-solid fa-location-dot"></i>{{ $contact['address'] }}</div>@endif
        </div>
      </div>
      @if(!empty($contact['photo']))<img src="{{ $contact['photo'] }}" class="photo" alt="photo">@endif
    </div>
  </div>

  <div class="body">
    @if(!empty($summary['text']))
    <div class="sec">
      <div class="sec-title">Profile</div>
      <p class="summary-text">{{ $summary['text'] }}</p>
    </div>
    @endif

    @if(!empty($experience['items']))
    <div class="sec">
      <div class="sec-title">Experience</div>
      @foreach($experience['items'] as $job)
      <div class="item">
        <div class="item-head">
          <span class="item-role">{{ $job['role'] ?? '' }}</span>
          <span class="item-dur">{{ $job['duration'] ?? '' }}</span>
        </div>
        <div class="item-company">{{ $job['company'] ?? '' }}</div>
        @if(!empty($job['description']))<div class="item-desc">{{ $job['description'] }}</div>@endif
      </div>
      @endforeach
    </div>
    @endif

    @if(!empty($skills['list']))
    <div class="sec">
      <div class="sec-title">Skills</div>
      <div class="tags">@foreach($skills['list'] as $sk)<span class="tag">{{ $sk }}</span>@endforeach</div>
    </div>
    @endif

    @if(!empty($education['items']))
    <div class="sec">
      <div class="sec-title">Education</div>
      @foreach($education['items'] as $edu)
      <div class="edu-item">
        <div class="edu-degree">{{ $edu['degree'] ?? '' }}</div>
        <div class="edu-school">{{ $edu['school'] ?? '' }}</div>
        <div class="edu-year">{{ $edu['year'] ?? '' }}</div>
      </div>
      @endforeach
    </div>
    @endif

    @if(!empty($certifications['items']))
    <div class="sec">
      <div class="sec-title">Certifications</div>
      @foreach($certifications['items'] as $cert)
      <div class="cert-item">
        <div class="cert-name">{{ $cert['name'] ?? '' }}</div>
        <div class="cert-org">{{ $cert['organization'] ?? '' }}</div>
        <div class="cert-date">{{ $cert['issue_date'] ?? '' }}</div>
      </div>
      @endforeach
    </div>
    @endif

    @if(!empty($languages['items']))
    <div class="sec">
      <div class="sec-title">Languages</div>
      @foreach($languages['items'] as $lang)
      <div class="lang-row">
        <span class="lang-name">{{ $lang['language'] ?? '' }}</span>
        <span class="lang-level">{{ $lang['level'] ?? '' }}</span>
      </div>
      @endforeach
    </div>
    @endif
  </div>
</div>
@include('resume.templates._base_end')
