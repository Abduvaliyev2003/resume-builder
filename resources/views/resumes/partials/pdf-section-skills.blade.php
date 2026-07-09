@if(!empty($skills['list']))
    <section class="section">
        <div class="section-title">{{ __('app.sec_skills') }}</div>
        <div class="tags">
            @foreach($skills['list'] as $skill)
                <span class="tag">{{ $skill }}</span>
            @endforeach
        </div>
    </section>
@endif
