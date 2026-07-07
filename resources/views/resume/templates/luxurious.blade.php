@include('resume.templates._base_start')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Source+Sans+3:wght@300;400;600;700&display=swap');
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:'Source Sans 3',sans-serif;font-size:14px;color:#1a1a1a;line-height:1.7;background:#fff}
  .sheet{width:100%;min-height:100%}

  /* ─── HEADER ─── */
  .hdr{padding:44px 48px 32px;background:#fff;position:relative}
  .hdr::before{content:'';position:absolute;top:0;left:0;right:0;height:6px;background:linear-gradient(90deg,#b7860f,#e8c84a,#d4a017,#f0d060)}
  .hdr-inner{display:flex;justify-content:space-between;align-items:flex-start;gap:24px}
  .hdr-left h1{font-family:'Playfair Display',serif;font-size:44px;font-weight:800;color:#0d0d0d;letter-spacing:-1px;line-height:1.05}
  .hdr-left .role{font-size:14px;font-weight:300;color:#b7860f;letter-spacing:3px;text-transform:uppercase;margin-top:10px}
  .hdr-right{text-align:right;display:flex;flex-direction:column;gap:6px;padding-top:6px}
  .c-item{font-size:13px;color:#555;display:flex;align-items:center;justify-content:flex-end;gap:8px}
  .c-item i{color:#b7860f}
  .photo{width:86px;height:86px;border-radius:8px;object-fit:cover;border:2px solid #e8c84a}
  .hdr-divider{height:1px;background:linear-gradient(90deg,#e8c84a,#b7860f40,transparent);margin:20px 0 0}

  /* ─── BODY ─── */
  .body{padding:36px 48px;display:grid;grid-template-columns:1fr 240px;gap:40px}

  /* ─── SECTIONS ─── */
  .sec{margin-bottom:30px}
  .sec-title{font-family:'Playfair Display',serif;font-size:16px;font-weight:700;color:#0d0d0d;margin-bottom:16px;position:relative;padding-left:16px}
  .sec-title::before{content:'';position:absolute;left:0;top:50%;transform:translateY(-50%);width:4px;height:16px;background:#b7860f;border-radius:2px}
  .summary-text{font-size:14px;color:#444;line-height:1.8;font-weight:300}

  .item{margin-bottom:22px;padding-bottom:22px;border-bottom:1px solid #f5f0e8}
  .item:last-child{border-bottom:none;margin-bottom:0;padding-bottom:0}
  .item-head{display:flex;justify-content:space-between;align-items:baseline;gap:10px}
  .item-role{font-size:16px;font-weight:700;color:#0d0d0d}
  .item-dur{font-size:12px;color:#b7860f;font-weight:600}
  .item-company{font-size:13px;color:#666;font-style:italic;margin:4px 0}
  .item-desc{font-size:13px;color:#444;line-height:1.7;font-weight:300}

  /* ─── SIDEBAR ─── */
  .tag{display:block;font-size:13px;color:#0d0d0d;padding:7px 12px;margin-bottom:6px;background:#fdf8ec;border-left:3px solid #b7860f}
  .edu-item{margin-bottom:16px;padding-bottom:16px;border-bottom:1px dotted #e8c84a}
  .edu-item:last-child{border-bottom:none}
  .edu-degree{font-size:14px;font-weight:700;color:#0d0d0d}
  .edu-school{font-size:13px;color:#b7860f;font-weight:500;margin:2px 0}
  .edu-year{font-size:12px;color:#888}
  .cert-item{margin-bottom:12px;padding-bottom:12px;border-bottom:1px dotted #f0e0b0}
  .cert-item:last-child{border-bottom:none}
  .cert-name{font-size:13px;font-weight:700;color:#0d0d0d}
  .cert-org{font-size:12px;color:#b7860f}
  .cert-date{font-size:11px;color:#999;margin-top:2px}
  .lang-row{display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px dotted #f0e0b0}
  .lang-row:last-child{border-bottom:none}
  .lang-name{font-size:13px;font-weight:600;color:#0d0d0d}
  .lang-level{font-size:12px;color:#b7860f;font-style:italic}
</style>

<div class="sheet">
  <div class="hdr">
    <div class="hdr-inner">
      <div class="hdr-left">
        <h1>{{ $contact['name'] ?? 'Your Name' }}</h1>
        <div class="role">{{ $contact['title'] ?? '' }}</div>
      </div>
      <div style="display:flex;align-items:center;gap:20px">
        <div class="hdr-right">
          @if(!empty($contact['email']))<div class="c-item"><i class="fa-regular fa-envelope"></i>{{ $contact['email'] }}</div>@endif
          @if(!empty($contact['phone']))<div class="c-item"><i class="fa-solid fa-phone"></i>{{ $contact['phone'] }}</div>@endif
          @if(!empty($contact['address']))<div class="c-item"><i class="fa-solid fa-location-dot"></i>{{ $contact['address'] }}</div>@endif
          @if(!empty($contact['date_of_birth']))<div class="c-item"><i class="fa-regular fa-calendar"></i>{{ $contact['date_of_birth'] }}</div>@endif
        </div>
        @if(!empty($contact['photo']))<img src="{{ $contact['photo'] }}" class="photo" alt="photo">@endif
      </div>
    </div>
    <div class="hdr-divider"></div>
  </div>

  <div class="body">
    <div>
      @if(!empty($summary['text']))
      <div class="sec">
        <div class="sec-title">Professional Profile</div>
        <p class="summary-text">{{ $summary['text'] }}</p>
      </div>
      @endif

      @if(!empty($experience['items']))
      <div class="sec">
        <div class="sec-title">Work Experience</div>
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
        @foreach($skills['list'] as $sk)<span class="tag">{{ $sk }}</span>@endforeach
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
</div>
@include('resume.templates._base_end')
