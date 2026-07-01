@include('resume.templates._base_start')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:'Montserrat',sans-serif;font-size:14px;color:#1e293b;line-height:1.6;background:#fff}
  .sheet{width:100%;min-height:100%;display:flex}

  /* ─── ACCENT STRIPE ─── */
  .stripe{width:10px;background:linear-gradient(180deg,#7c3aed,#a855f7,#c084fc);flex-shrink:0}

  /* ─── SIDEBAR ─── */
  .sidebar{width:230px;flex-shrink:0;background:#1e1b4b;padding:36px 22px;display:flex;flex-direction:column;gap:26px}
  .avatar{width:84px;height:84px;border-radius:50%;object-fit:cover;border:3px solid #7c3aed;margin:0 auto 8px;display:block}
  .avatar-initials{width:84px;height:84px;border-radius:50%;background:rgba(124,58,237,.3);color:#c4b5fd;font-size:28px;font-weight:800;display:flex;align-items:center;justify-content:center;margin:0 auto 8px}
  .s-name{color:#e2e8f0;font-size:17px;font-weight:800;text-align:center;line-height:1.2}
  .s-title{color:#a78bfa;font-size:12px;font-weight:500;text-align:center;margin-top:4px}
  .s-divider{height:1px;background:rgba(255,255,255,.08)}
  .s-label{font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.15em;color:#7c3aed;margin-bottom:10px}
  .c-item{display:flex;align-items:flex-start;gap:8px;font-size:12px;color:#94a3b8;margin-bottom:8px;line-height:1.4}
  .c-item i{color:#7c3aed;font-size:11px;width:14px;flex-shrink:0;margin-top:2px}
  .skill-list{display:flex;flex-direction:column;gap:6px}
  .skill-name{font-size:12.5px;color:#c4b5fd;font-weight:600;margin-bottom:3px}
  .skill-track{height:4px;background:rgba(255,255,255,.1);border-radius:10px}
  .skill-fill{height:100%;width:78%;background:linear-gradient(90deg,#7c3aed,#c084fc);border-radius:10px}
  .lang-row{display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid rgba(255,255,255,.07)}
  .lang-row:last-child{border-bottom:none}
  .lang-n{font-size:12.5px;color:#c4b5fd;font-weight:600}
  .lang-l{font-size:10px;color:#7c3aed;background:rgba(124,58,237,.2);padding:2px 7px;border-radius:10px}

  /* ─── MAIN ─── */
  .main{flex:1;padding:36px 36px;display:flex;flex-direction:column;gap:26px}
  .m-sec-title{font-size:10.5px;font-weight:800;text-transform:uppercase;letter-spacing:.14em;color:#7c3aed;padding-bottom:8px;border-bottom:2px solid #ede9fe;margin-bottom:14px}
  .summary-text{font-size:14px;color:#475569;line-height:1.75}

  .item{margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #f5f3ff}
  .item:last-child{border-bottom:none;margin-bottom:0;padding-bottom:0}
  .item-row{display:flex;justify-content:space-between;align-items:flex-start;gap:10px}
  .item-role{font-size:15px;font-weight:700;color:#1e1b4b}
  .item-dur{font-size:11px;color:#64748b;background:#f5f3ff;padding:3px 10px;border-radius:20px;font-weight:600;white-space:nowrap}
  .item-company{font-size:13px;color:#7c3aed;font-weight:600;margin:4px 0}
  .item-desc{font-size:13px;color:#475569;line-height:1.65}

  .edu-block{background:#faf5ff;border-radius:10px;padding:14px 16px;margin-bottom:12px;border-left:4px solid #7c3aed}
  .edu-degree{font-size:14px;font-weight:700;color:#1e1b4b}
  .edu-school{font-size:13px;color:#7c3aed;font-weight:500;margin:3px 0}
  .edu-year{font-size:12px;color:#94a3b8}

  .cert-item{padding:10px 0;border-bottom:1px solid #f5f3ff}
  .cert-item:last-child{border-bottom:none}
  .cert-name{font-size:13px;font-weight:700;color:#1e1b4b}
  .cert-org{font-size:12px;color:#7c3aed;font-weight:500}
  .cert-date{font-size:11px;color:#94a3b8;margin-top:2px}
</style>

<div class="sheet">
  <div class="stripe"></div>
  <div class="sidebar">
    @if(!empty($contact['photo']))
      <img src="{{ $contact['photo'] }}" class="avatar" alt="photo">
    @else
      <div class="avatar-initials">{{ substr($contact['name'] ?? 'U', 0, 1) }}</div>
    @endif
    <div class="s-name">{{ $contact['name'] ?? 'Your Name' }}</div>
    <div class="s-title">{{ $contact['title'] ?? '' }}</div>

    <div class="s-divider"></div>

    <div>
      <div class="s-label">Contact</div>
      @if(!empty($contact['email']))<div class="c-item"><i class="fa-regular fa-envelope"></i>{{ $contact['email'] }}</div>@endif
      @if(!empty($contact['phone']))<div class="c-item"><i class="fa-solid fa-phone"></i>{{ $contact['phone'] }}</div>@endif
      @if(!empty($contact['address']))<div class="c-item"><i class="fa-solid fa-location-dot"></i>{{ $contact['address'] }}</div>@endif
    </div>

    @if(!empty($skills['list']))
    <div>
      <div class="s-label">Skills</div>
      <div class="skill-list">
        @foreach($skills['list'] as $sk)
        <div>
          <div class="skill-name">{{ $sk }}</div>
          <div class="skill-track"><div class="skill-fill"></div></div>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    @if(!empty($languages['items']))
    <div>
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
    <div>
      <div class="m-sec-title">About Me</div>
      <p class="summary-text">{{ $summary['text'] }}</p>
    </div>
    @endif

    @if(!empty($experience['items']))
    <div>
      <div class="m-sec-title">Work Experience</div>
      @foreach($experience['items'] as $job)
      <div class="item">
        <div class="item-row">
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
    <div>
      <div class="m-sec-title">Education</div>
      @foreach($education['items'] as $edu)
      <div class="edu-block">
        <div class="edu-degree">{{ $edu['degree'] ?? '' }}</div>
        <div class="edu-school">{{ $edu['school'] ?? '' }}</div>
        <div class="edu-year">{{ $edu['year'] ?? '' }}</div>
      </div>
      @endforeach
    </div>
    @endif

    @if(!empty($certifications['items']))
    <div>
      <div class="m-sec-title">Certifications</div>
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
