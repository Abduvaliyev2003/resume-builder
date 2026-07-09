@if(!empty($certifications['items']))
    <section class="section">
        <div class="section-title">{{ __('app.sec_certifications') }}</div>
        @foreach($certifications['items'] as $certificate)
            <div class="item">
                <div class="item-head"><span>{{ $certificate['name'] ?? '' }}</span><span class="muted">{{ $certificate['issue_date'] ?? '' }}</span></div>
                <div class="muted">{{ $certificate['organization'] ?? '' }}</div>
                @if(!empty($certificate['credential_id']))<div class="muted">Credential ID: {{ $certificate['credential_id'] }}</div>@endif
            </div>
        @endforeach
    </section>
@endif
