@if(!empty($skills['list']))
    <section class="section">
        <div class="section-title">Skills</div>
        <div class="tags">
            @foreach($skills['list'] as $skill)
                <span class="tag">{{ $skill }}</span>
            @endforeach
        </div>
    </section>
@endif
