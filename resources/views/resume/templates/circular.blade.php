@include('resume.templates._base_start')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;color:#1e293b;line-height:1.6;background:#fff}
  .sheet{width:100%;min-height:100%;display:flex}

  /* ─── LEFT SIDEBAR ─── */
  .sidebar{width:240px;flex-shrink:0;background:#0c1a2e;padding:0 0 32px}
  .sidebar-top{background:linear-gradient(160deg,#0f2d52,#1a4080);padding:32px 24px 28px;text-align:center}
  .avatar{width:88px;height:88px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.3);margin:0 auto 16px}
  .avatar-initials{width:88px;height:88px;border-radius:50%;background:rgba(255,255,255,.1);color:#fff;font-size:30px;font-weight:800;display:flex;align-items:center;justify-content:center;margin:0 auto 16px}
  .s-name{color:#fff;font-size:18px;font-weight:800;line-height:1.2}
  .s-title{color:#93c5fd;font-size:12px;font-weight:500;margin-top:6px}
  .s-divider{height:1px;background:rgba(255,255,255,.1);margin:20px 0}
  .s-section{padding:0 20px;margin-bottom:22px}
  .s-label{font-size:9.5px;font-weight:800;text-transform:uppercase;letter-spacing:.14em;color:#60a5fa;margin-bottom:10px}
  .contact-item{display:flex;align-items:flex-start;gap:8px;font-size:12px;color:#94a3b8;margin-bottom:8px;line-height:1.4}
  .contact-item i{color:#60a5fa;font-size:11px;width:14px;flex-shrink:0;margin-top:2px}
  .skill-chip{display:inline-block;background:rgba(255,255,255,.08);color:#e2e8f0;font-size:11.5px;font-weight:600;padding:5px 11px;border-radius:6px;margin:3px;border:1px solid rgba(255,255,255,.12)}
  .lang-row{display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid rgba(255,255,255,.07)}
  .lang-row:last-child{border-bottom:none}
  .lang-name{font-size:12.5px;color:#cbd5e1;font-weight:600}
  .lang-badge{font-size:10px;color:#60a5fa;background:rgba(96,165,250,.15);padding:2px 7px;border-radius:10px}

  /* ─── MAIN CONTENT ─── */
  .main{flex:1;padding:36px 36px}
  .m-sec{margin-bottom:28px}
  .m-title{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.13em;color:#0c1a2e;padding-bottom:9px;border-bottom:2px solid #0c1a2e;margin-bottom:16px}
  .summary-text{font-size:14px;color:#475569;line-height:1.75}
  .timeline{position:relative;padding-left:20px}
  .timeline::before{content:'';position:absolute;left:0;top:6px;bottom:0;width:2px;background:#e2e8f0}
  .tl-item{position:relative;margin-bottom:22px;padding-bottom:22px;border-bottom:1px solid #f8fafc}
  .tl-item:last-child{border-bottom:none;margin-bottom:0;padding-bottom:0}
  .tl-dot{position:absolute;left:-25px;top:5px;width:10px;height:10px;border-radius:50%;background:#0c1a2e;border:2px solid #fff;box-shadow:0 0 0 2px #0c1a2e}
  .item-head{display:flex;justify-content:space-between;align-items:flex-start;gap:10px}
  .item-role{font-size:15px;font-weight:700;color:#0f172a}
  .item-dur{font-size:11px;color:#64748b;font-weight:600;background:#f1f5f9;padding:3px 10px;border-radius:20px;white-space:nowrap}
  .item-company{font-size:13px;color:#1a4080;font-weight:600;margin:4px 0}
  .item-desc{font-size:13px;color:#475569;line-height:1.65;margin-top:6px}

  .edu-card{background:#f8fafc;border-radius:10px;padding:14px 16px;margin-bottom:12px;display:flex;justify-content:space-between;align-items:flex-start;border-left:4px solid #0c1a2e}
  .edu-left .edu-degree{font-size:14px;font-weight:700;color:#0f172a}
  .edu-left .edu-school{font-size:13px;color:#1a4080;font-weight:500;margin-top:3px}
  .edu-year{font-size:12px;color:#64748b;font-weight:600}

  .cert-item{padding:10px 0;border-bottom:1px solid #f1f5f9}
  .cert-item:last-child{border-bottom:none}
  .cert-name{font-size:14px;font-weight:700;color:#0f172a}
  .cert-org{font-size:12px;color:#1a4080;font-weight:500}
  .cert-date{font-size:11px;color:#94a3b8;margin-top:2px}
</style>

<div class="sheet">
  <div class="sidebar">
    <div class="sidebar-top">
      @if(!empty($contact['photo']))
        <img src="{{ $contact['photo'] }}" class="avatar" alt="photo">
      @else
        <div class="avatar-initials">{{ substr($contact['name'] ?? 'U', 0, 1) }}</div>
      @endif
      <div class="s-name">{{ $contact['name'] ?? 'Your Name' }}</div>
      <div class="s-title">{{ $contact['title'] ?? '' }}</div>
    </div>

    <div style="height:20px"></div>

    @if(!empty($contact['email']) || !empty($contact['phone']) || !empty($contact['address']))
    <div class="s-section">
      <div class="s-label">Contact</div>
      @if(!empty($contact['email']))<div class="contact-item"><i class="fa-regular fa-envelope"></i>{{ $contact['email'] }}</div>@endif
      @if(!empty($contact['phone']))<div class="contact-item"><i class="fa-solid fa-phone"></i>{{ $contact['phone'] }}</div>@endif
      @if(!empty($contact['address']))<div class="contact-item"><i class="fa-solid fa-location-dot"></i>{{ $contact['address'] }}</div>@endif
      @if(!empty($contact['date_of_birth']))<div class="contact-item"><i class="fa-regular fa-calendar"></i>{{ $contact['date_of_birth'] }}</div>@endif
    </div>
    @endif

    @if(!empty($skills['list']))
    <div class="s-section">
      <div class="s-label">Skills</div>
      <div>@foreach($skills['list'] as $sk)<span class="skill-chip">{{ $sk }}</span>@endforeach</div>
    </div>
    @endif

    @if(!empty($languages['items']))
    <div class="s-section">
      <div class="s-label">Languages</div>
      @foreach($languages['items'] as $lang)
      <div class="lang-row">
        <span class="lang-name">{{ $lang['language'] ?? '' }}</span>
        <span class="lang-badge">{{ $lang['level'] ?? '' }}</span>
      </div>
      @endforeach
    </div>
    @endif
  </div>

  <div class="main">
    @if(!empty($summary['text']))
    <div class="m-sec">
      <div class="m-title">About</div>
      <p class="summary-text">{{ $summary['text'] }}</p>
    </div>
    @endif

    @if(!empty($experience['items']))
    <div class="m-sec">
      <div class="m-title">Work Experience</div>
      <div class="timeline">
        @foreach($experience['items'] as $job)
        <div class="tl-item">
          <div class="tl-dot"></div>
          <div class="item-head">
            <span class="item-role">{{ $job['role'] ?? '' }}</span>
            <span class="item-dur">{{ $job['duration'] ?? '' }}</span>
          </div>
          <div class="item-company">{{ $job['company'] ?? '' }}</div>
          @if(!empty($job['description']))<div class="item-desc">{{ $job['description'] }}</div>@endif
        </div>
        @endforeach
      </div>
    </div>
    @endif

    @if(!empty($education['items']))
    <div class="m-sec">
      <div class="m-title">Education</div>
      @foreach($education['items'] as $edu)
      <div class="edu-card">
        <div class="edu-left">
          <div class="edu-degree">{{ $edu['degree'] ?? '' }}</div>
          <div class="edu-school">{{ $edu['school'] ?? '' }}</div>
        </div>
        <div class="edu-year">{{ $edu['year'] ?? '' }}</div>
      </div>
      @endforeach
    </div>
    @endif

    @if(!empty($certifications['items']))
    <div class="m-sec">
      <div class="m-title">Certifications</div>
      @foreach($certifications['items'] as $cert)
      <div class="cert-item">
        <div class="cert-name">{{ $cert['name'] ?? '' }}</div>
        <div class="cert-org">{{ $cert['organization'] ?? '' }}</div>
        <div class="cert-date">{{ $cert['issue_date'] ?? '' }}</div>
      </div>
      @endforeach
    </div>
    @endif
  </div>
</div>
@include('resume.templates._base_end')
