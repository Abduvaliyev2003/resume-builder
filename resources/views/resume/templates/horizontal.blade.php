@include('resume.templates._base_start')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:'Inter',sans-serif;font-size:14px;color:#1e293b;line-height:1.6;background:#fff}
  .sheet{width:100%;min-height:100%}

  /* ─── HEADER BANNER ─── */
  .hdr{background:#dc2626;padding:32px 40px 24px;color:#fff}
  .hdr-inner{display:flex;align-items:center;gap:24px;margin-bottom:20px}
  .photo{width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.4);flex-shrink:0}
  .avatar-initials{width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.15);color:#fff;font-size:28px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0}
  .hdr-title h1{font-size:36px;font-weight:900;letter-spacing:-0.5px;line-height:1.1;margin-bottom:5px}
  .hdr-title .role{font-size:14px;color:#fca5a5;font-weight:500}
  .contact-row{display:flex;flex-wrap:wrap;gap:20px;padding-top:16px;border-top:1px solid rgba(255,255,255,.15)}
  .c-pill{display:flex;align-items:center;gap:7px;font-size:12.5px;color:#fce7e7}
  .c-pill i{color:#fca5a5;font-size:11px}

  /* ─── BODY HORIZONTAL ─── */
  .body{display:grid;grid-template-columns:1fr 1fr;gap:0}
  .col-a,.col-b{padding:32px 36px}
  .col-a{border-right:1px solid #fef2f2}

  /* ─── SECTIONS ─── */
  .sec{margin-bottom:26px}
  .sec-title{font-size:10.5px;font-weight:800;text-transform:uppercase;letter-spacing:.13em;color:#dc2626;margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid #fecaca}
  .summary-text{font-size:14px;color:#475569;line-height:1.75}

  .item{margin-bottom:18px;padding-bottom:18px;border-bottom:1px solid #fef2f2}
  .item:last-child{border-bottom:none;margin-bottom:0;padding-bottom:0}
  .item-head{display:flex;justify-content:space-between;align-items:flex-start;gap:8px}
  .item-role{font-size:15px;font-weight:700;color:#0f172a}
  .item-dur{font-size:11px;color:#64748b;background:#fef2f2;padding:2px 9px;border-radius:20px;font-weight:600;white-space:nowrap}
  .item-company{font-size:13px;color:#dc2626;font-weight:600;margin:4px 0}
  .item-desc{font-size:13px;color:#475569;line-height:1.65}

  .tags{display:flex;flex-wrap:wrap;gap:6px}
  .tag{background:#fef2f2;color:#b91c1c;font-size:12px;font-weight:600;padding:5px 12px;border-radius:8px;border:1px solid #fecaca}

  .edu-card{background:#fff5f5;border-radius:8px;padding:14px;margin-bottom:10px;border-left:4px solid #dc2626}
  .edu-degree{font-size:14px;font-weight:700;color:#0f172a}
  .edu-school{font-size:13px;color:#dc2626;font-weight:500;margin:2px 0}
  .edu-year{font-size:12px;color:#94a3b8}

  .cert-item{padding:10px 0;border-bottom:1px solid #fef2f2}
  .cert-item:last-child{border-bottom:none}
  .cert-name{font-size:13px;font-weight:700;color:#0f172a}
  .cert-org{font-size:12px;color:#dc2626;font-weight:500}
  .cert-date{font-size:11px;color:#94a3b8;margin-top:2px}

  .lang-row{display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #fef2f2}
  .lang-row:last-child{border-bottom:none}
  .lang-name{font-size:13px;font-weight:600;color:#1e293b}
  .lang-level{font-size:11px;background:#fef2f2;color:#dc2626;padding:2px 8px;border-radius:10px;font-weight:600}
</style>

<div class="sheet">
  <div class="hdr">
    <div class="hdr-inner">
      @if(!empty($contact['photo']))
        <img src="{{ $contact['photo'] }}" class="photo" alt="photo">
      @else
        <div class="avatar-initials">{{ substr($contact['name'] ?? 'U', 0, 1) }}</div>
      @endif
      <div class="hdr-title">
        <h1>{{ $contact['name'] ?? 'Your Name' }}</h1>
        <div class="role">{{ $contact['title'] ?? '' }}</div>
      </div>
    </div>
    <div class="contact-row">
      @if(!empty($contact['email']))<div class="c-pill"><i class="fa-regular fa-envelope"></i>{{ $contact['email'] }}</div>@endif
      @if(!empty($contact['phone']))<div class="c-pill"><i class="fa-solid fa-phone"></i>{{ $contact['phone'] }}</div>@endif
      @if(!empty($contact['address']))<div class="c-pill"><i class="fa-solid fa-location-dot"></i>{{ $contact['address'] }}</div>@endif
      @if(!empty($contact['date_of_birth']))<div class="c-pill"><i class="fa-regular fa-calendar"></i>{{ $contact['date_of_birth'] }}</div>@endif
    </div>
  </div>

  <div class="body">
    <div class="col-a">
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
    </div>

    <div class="col-b">
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
        <div class="edu-card">
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
</div>
@include('resume.templates._base_end')
