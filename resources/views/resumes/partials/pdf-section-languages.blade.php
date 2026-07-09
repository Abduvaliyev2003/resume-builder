@if(!empty($languages['items']))
    <section class="section">
        <div class="section-title">{{ __('app.sec_languages') }}</div>
        @foreach($languages['items'] as $language)
            <div class="item-head"><span>{{ $language['language'] ?? '' }}</span><span class="muted">{{ $language['level'] ?? '' }}</span></div>
        @endforeach
    </section>
@endif
