@if(!empty($education['items']))
    <section class="section">
        <div class="section-title">{{ __('app.sec_education') }}</div>
        @foreach($education['items'] as $edu)
            <div class="item">
                <div class="item-head"><span>{{ $edu['degree'] ?? '' }}</span><span class="muted">{{ $edu['year'] ?? $edu['graduation_date'] ?? '' }}</span></div>
                <div class="muted">{{ $edu['school'] ?? '' }}</div>
            </div>
        @endforeach
    </section>
@endif
