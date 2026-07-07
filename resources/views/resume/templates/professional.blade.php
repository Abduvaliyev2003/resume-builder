@include('resume.templates._base_start')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;color:#1e293b;line-height:1.6;background:#fff}
  .sheet{width:100%;min-height:100%}

  /* ─── HEADER ─── */
  .hdr{background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 100%);color:#fff;padding:36px 40px;display:flex;justify-content:space-between;align-items:center;gap:24px}
  .hdr-left h1{font-size:36px;font-weight:800;letter-spacing:-0.5px;line-height:1.1;margin-bottom:6px}
  .hdr-left .title{color:#93c5fd;font-size:15px;font-weight:600}
  .hdr-right{display:flex;flex-direction:column;gap:6px;text-align:right}
  .hdr-contact-item{font-size:13px;color:#cbd5e1;display:flex;align-items:center;justify-content:flex-end;gap:7px}
  .hdr-contact-item i{color:#60a5fa;font-size:12px}
  .photo{width:76px;height:76px;border-radius:12px;object-fit:cover;border:3px solid rgba(255,255,255,.2);margin-left:20px;flex-shrink:0}

  /* ─── BODY ─── */
  .body{display:grid;grid-template-columns:1fr 300px;gap:0}
  .col-left{padding:32px 36px;border-right:1px solid #f1f5f9}
  .col-right{padding:32px 28px;background:#f8fafc}

  /* ─── SECTION ─── */
  .sec{margin-bottom:28px}
  .sec-title{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:#2563eb;margin-bottom:14px;display:flex;align-items:center;gap:8px}
  .sec-title::after{content:'';flex:1;height:2px;background:linear-gradient(90deg,#2563eb20,transparent)}

  /* ─── EXPERIENCE ─── */
  .item{margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #f1f5f9}
  .item:last-child{border-bottom:none;margin-bottom:0;padding-bottom:0}
  .item-head{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:3px}
  .item-role{font-size:15px;font-weight:700;color:#0f172a}
  .item-dur{font-size:12px;color:#64748b;background:#e2e8f0;padding:2px 8px;border-radius:20px;white-space:nowrap;font-weight:500}
  .item-company{font-size:13px;color:#2563eb;font-weight:600;margin-bottom:6px}
  .item-desc{font-size:13px;color:#475569;line-height:1.65}

  /* ─── SKILLS ─── */
  .tags{display:flex;flex-wrap:wrap;gap:7px}
  .tag{background:#dbeafe;color:#1d4ed8;font-size:12px;font-weight:600;padding:5px 12px;border-radius:8px}

  /* ─── EDUCATION ─── */
  .edu-item{margin-bottom:16px;padding:14px;background:#fff;border-radius:10px;border:1px solid #e2e8f0}
  .edu-degree{font-size:14px;font-weight:700;color:#0f172a;margin-bottom:3px}
  .edu-school{font-size:13px;color:#2563eb;font-weight:500;margin-bottom:2px}
  .edu-year{font-size:12px;color:#94a3b8}

  /* ─── LANGUAGES ─── */
  .lang-item{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f1f5f9}
  .lang-item:last-child{border-bottom:none}
  .lang-name{font-size:13px;font-weight:600;color:#1e293b}
  .lang-level{font-size:12px;color:#2563eb;background:#dbeafe;padding:2px 8px;border-radius:20px}

  /* ─── CERTS ─── */
  .cert-item{margin-bottom:14px;padding:12px;background:#fff;border-radius:10px;border:1px solid #e2e8f0}
  .cert-name{font-size:13px;font-weight:700;color:#0f172a;margin-bottom:2px}
  .cert-org{font-size:12px;color:#2563eb;font-weight:500}
  .cert-date{font-size:11px;color:#94a3b8;margin-top:2px}

  .summary-text{font-size:14px;color:#475569;line-height:1.7}
</style>

<div class="sheet">
  <div class="hdr">
    <div class="hdr-left">
      <h1>{{ $contact['name'] ?? 'Your Name' }}</h1>
      <div class="title">{{ $contact['title'] ?? 'Professional Title' }}</div>
    </div>
    <div style="display:flex;align-items:center;gap:0">
      <div class="hdr-right">
        @if(!empty($contact['email']))<div class="hdr-contact-item"><i class="fa-regular fa-envelope"></i>{{ $contact['email'] }}</div>@endif
        @if(!empty($contact['phone']))<div class="hdr-contact-item"><i class="fa-solid fa-phone"></i>{{ $contact['phone'] }}</div>@endif
        @if(!empty($contact['address']))<div class="hdr-contact-item"><i class="fa-solid fa-location-dot"></i>{{ $contact['address'] }}</div>@endif
        @if(!empty($contact['date_of_birth']))<div class="hdr-contact-item"><i class="fa-regular fa-calendar"></i>{{ $contact['date_of_birth'] }}</div>@endif
      </div>
      @if(!empty($contact['photo']))<img src="{{ $contact['photo'] }}" class="photo" alt="photo">@endif
    </div>
  </div>

  <div class="body">
    <div class="col-left">
      @if(!empty($summary['text']))
      <div class="sec">
        <div class="sec-title">Profile Summary</div>
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

    <div class="col-right">
      @if(!empty($skills['list']))
      <div class="sec">
        <div class="sec-title">Skills</div>
        <div class="tags">
          @foreach($skills['list'] as $sk)<span class="tag">{{ $sk }}</span>@endforeach
        </div>
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
        <div class="lang-item">
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
