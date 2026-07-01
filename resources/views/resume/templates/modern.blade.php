@include('resume.templates._base_start')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap');
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:'Nunito',sans-serif;font-size:14px;color:#1e293b;line-height:1.6;background:#fff}
  .sheet{width:100%;min-height:100%;display:flex}

  /* ─── SIDEBAR ─── */
  .sidebar{width:260px;flex-shrink:0;background:linear-gradient(180deg,#1e40af 0%,#1d4ed8 60%,#2563eb 100%);color:#fff;padding:36px 24px;display:flex;flex-direction:column;gap:28px}
  .avatar-wrap{display:flex;flex-direction:column;align-items:center;gap:14px;padding-bottom:24px;border-bottom:1px solid rgba(255,255,255,.2)}
  .avatar{width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.5)}
  .avatar-initials{width:90px;height:90px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:800}
  .sidebar-name{font-size:20px;font-weight:800;text-align:center;line-height:1.2}
  .sidebar-title{font-size:13px;color:#bfdbfe;text-align:center;font-weight:500;margin-top:4px}
  .s-sec{display:flex;flex-direction:column;gap:10px}
  .s-label{font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:#93c5fd;margin-bottom:6px}
  .contact-row{display:flex;align-items:center;gap:9px;font-size:12px;color:#e0e7ff;line-height:1.4}
  .contact-row i{width:16px;text-align:center;color:#60a5fa;font-size:11px}
  .skill-bar-wrap{display:flex;flex-direction:column;gap:8px}
  .skill-name{font-size:13px;color:#e0e7ff;font-weight:600}
  .skill-bar{height:5px;background:rgba(255,255,255,.2);border-radius:10px;margin-top:3px}
  .skill-bar-fill{height:100%;width:75%;background:linear-gradient(90deg,#60a5fa,#a5f3fc);border-radius:10px}
  .lang-row{display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid rgba(255,255,255,.1)}
  .lang-row:last-child{border-bottom:none}
  .lang-n{font-size:13px;color:#e0e7ff;font-weight:600}
  .lang-l{font-size:11px;background:rgba(255,255,255,.15);color:#bfdbfe;padding:2px 8px;border-radius:20px}

  /* ─── MAIN ─── */
  .main{flex:1;padding:36px 40px;display:flex;flex-direction:column;gap:28px}
  .m-sec{display:flex;flex-direction:column;gap:14px}
  .m-title{font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:.14em;color:#1d4ed8;padding-bottom:8px;border-bottom:2px solid #dbeafe;margin-bottom:6px}
  .summary-text{font-size:14px;color:#475569;line-height:1.7}

  .item{padding-bottom:18px;border-bottom:1px solid #f1f5f9;margin-bottom:2px}
  .item:last-child{border-bottom:none;padding-bottom:0}
  .item-head{display:flex;justify-content:space-between;align-items:flex-start;gap:10px}
  .item-role{font-size:16px;font-weight:700;color:#0f172a}
  .item-dur{font-size:11px;color:#64748b;background:#f1f5f9;padding:3px 10px;border-radius:20px;white-space:nowrap;font-weight:600}
  .item-company{font-size:13px;color:#1d4ed8;font-weight:600;margin:4px 0}
  .item-desc{font-size:13px;color:#475569;line-height:1.65}

  .edu-item{background:#f8fafc;border-radius:10px;padding:14px 16px;border-left:4px solid #2563eb}
  .edu-degree{font-size:15px;font-weight:700;color:#0f172a}
  .edu-school{font-size:13px;color:#1d4ed8;font-weight:600;margin:3px 0}
  .edu-year{font-size:12px;color:#94a3b8}

  .cert-item{padding:10px 0;border-bottom:1px solid #f1f5f9}
  .cert-item:last-child{border-bottom:none}
  .cert-name{font-size:14px;font-weight:700;color:#0f172a}
  .cert-org{font-size:12px;color:#1d4ed8;font-weight:500}
  .cert-date{font-size:12px;color:#94a3b8}
</style>

<div class="sheet">
  <div class="sidebar">
    <div class="avatar-wrap">
      @if(!empty($contact['photo']))
        <img src="{{ $contact['photo'] }}" class="avatar" alt="photo">
      @else
        <div class="avatar-initials">{{ substr($contact['name'] ?? 'U', 0, 1) }}</div>
      @endif
      <div>
        <div class="sidebar-name">{{ $contact['name'] ?? 'Your Name' }}</div>
        <div class="sidebar-title">{{ $contact['title'] ?? '' }}</div>
      </div>
    </div>

    <div class="s-sec">
      <div class="s-label">Contact</div>
      @if(!empty($contact['email']))<div class="contact-row"><i class="fa-regular fa-envelope"></i>{{ $contact['email'] }}</div>@endif
      @if(!empty($contact['phone']))<div class="contact-row"><i class="fa-solid fa-phone"></i>{{ $contact['phone'] }}</div>@endif
      @if(!empty($contact['address']))<div class="contact-row"><i class="fa-solid fa-location-dot"></i>{{ $contact['address'] }}</div>@endif
    </div>

    @if(!empty($skills['list']))
    <div class="s-sec">
      <div class="s-label">Skills</div>
      <div class="skill-bar-wrap">
        @foreach($skills['list'] as $sk)
        <div>
          <div class="skill-name">{{ $sk }}</div>
          <div class="skill-bar"><div class="skill-bar-fill"></div></div>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    @if(!empty($languages['items']))
    <div class="s-sec">
      <div class="s-label">Languages</div>
      @foreach($languages['items'] as $lang)
      <div class="lang-row">
        <span class="lang-n">{{ $lang['language'] ?? '' }}</span>
        <span class="lang-l">{{ $lang['level'] ?? '' }}</span>
      </div>
      @endforeach
    </div>
    @endif
  </div>

  <div class="main">
    @if(!empty($summary['text']))
    <div class="m-sec">
      <div class="m-title">About Me</div>
      <p class="summary-text">{{ $summary['text'] }}</p>
    </div>
    @endif

    @if(!empty($experience['items']))
    <div class="m-sec">
      <div class="m-title">Experience</div>
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

    @if(!empty($education['items']))
    <div class="m-sec">
      <div class="m-title">Education</div>
      @foreach($education['items'] as $edu)
      <div class="edu-item" style="margin-bottom:10px">
        <div class="edu-degree">{{ $edu['degree'] ?? '' }}</div>
        <div class="edu-school">{{ $edu['school'] ?? '' }}</div>
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
