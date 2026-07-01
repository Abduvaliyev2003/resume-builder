@include('resume.templates._base_start')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Lato:wght@300;400;700&display=swap');
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:'Lato',sans-serif;font-size:14px;color:#2d2d2d;line-height:1.7;background:#fff}
  .sheet{width:100%;min-height:100%}

  /* ─── HEADER ─── */
  .hdr{text-align:center;padding:48px 48px 28px;border-bottom:2px solid #c8a96e;position:relative}
  .hdr::before{content:'';position:absolute;top:0;left:0;right:0;height:5px;background:linear-gradient(90deg,#c8a96e,#e8d5a3,#c8a96e)}
  .hdr h1{font-family:'Playfair Display',serif;font-size:42px;font-weight:800;color:#1a1a1a;letter-spacing:1px;line-height:1.1}
  .hdr .job-title{font-size:15px;font-weight:300;color:#c8a96e;letter-spacing:4px;text-transform:uppercase;margin-top:8px}
  .hdr-contact{display:flex;justify-content:center;gap:28px;margin-top:18px;flex-wrap:wrap}
  .hdr-contact-item{font-size:13px;color:#555;display:flex;align-items:center;gap:7px}
  .hdr-contact-item i{color:#c8a96e}
  .photo-wrap{display:flex;justify-content:center;margin-bottom:20px}
  .photo{width:96px;height:96px;border-radius:50%;object-fit:cover;border:4px solid #c8a96e;box-shadow:0 4px 20px rgba(200,169,110,.3)}

  /* ─── BODY ─── */
  .body{padding:36px 48px;display:grid;grid-template-columns:1fr 250px;gap:36px}

  /* ─── SECTION ─── */
  .sec{margin-bottom:30px}
  .sec-title{font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:#1a1a1a;margin-bottom:14px;padding-bottom:8px;border-bottom:1px solid #c8a96e;display:flex;align-items:center;gap:10px}
  .sec-title::before{content:'';width:4px;height:18px;background:#c8a96e;border-radius:2px}

  /* ─── EXPERIENCE ─── */
  .item{margin-bottom:22px}
  .item:last-child{margin-bottom:0}
  .item-head{display:flex;justify-content:space-between;align-items:baseline;gap:12px}
  .item-role{font-size:16px;font-weight:700;color:#1a1a1a}
  .item-dur{font-size:12px;color:#c8a96e;font-weight:700;font-style:italic}
  .item-company{font-size:13px;color:#666;font-style:italic;margin:4px 0}
  .item-desc{font-size:13px;color:#555;line-height:1.7}

  /* ─── SIDEBAR SECTIONS ─── */
  .summary-text{font-size:14px;color:#555;line-height:1.75;font-style:italic}

  .tags{display:flex;flex-direction:column;gap:7px}
  .tag{font-size:13px;color:#2d2d2d;padding:6px 12px;background:#fdf8f0;border-left:3px solid #c8a96e;font-weight:500}

  .edu-item{margin-bottom:16px}
  .edu-degree{font-size:14px;font-weight:700;color:#1a1a1a}
  .edu-school{font-size:13px;color:#666;font-style:italic;margin:2px 0}
  .edu-year{font-size:12px;color:#c8a96e;font-weight:700}

  .cert-item{margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid #f0e8d5}
  .cert-item:last-child{border-bottom:none}
  .cert-name{font-size:13px;font-weight:700;color:#1a1a1a}
  .cert-org{font-size:12px;color:#666;font-style:italic}
  .cert-date{font-size:11px;color:#c8a96e}

  .lang-item{display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px dotted #e8d5a3}
  .lang-item:last-child{border-bottom:none}
  .lang-name{font-size:13px;font-weight:600;color:#2d2d2d}
  .lang-level{font-size:12px;color:#c8a96e;font-style:italic}
</style>

<div class="sheet">
  <div class="hdr">
    @if(!empty($contact['photo']))
    <div class="photo-wrap"><img src="{{ $contact['photo'] }}" class="photo" alt="photo"></div>
    @endif
    <h1>{{ $contact['name'] ?? 'Your Name' }}</h1>
    <div class="job-title">{{ $contact['title'] ?? 'Professional' }}</div>
    <div class="hdr-contact">
      @if(!empty($contact['email']))<div class="hdr-contact-item"><i class="fa-regular fa-envelope"></i>{{ $contact['email'] }}</div>@endif
      @if(!empty($contact['phone']))<div class="hdr-contact-item"><i class="fa-solid fa-phone"></i>{{ $contact['phone'] }}</div>@endif
      @if(!empty($contact['address']))<div class="hdr-contact-item"><i class="fa-solid fa-location-dot"></i>{{ $contact['address'] }}</div>@endif
    </div>
  </div>

  <div class="body">
    <div>
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

    <div>
      @if(!empty($skills['list']))
      <div class="sec">
        <div class="sec-title">Skills</div>
        <div class="tags">
          @foreach($skills['list'] as $sk)<div class="tag">{{ $sk }}</div>@endforeach
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
