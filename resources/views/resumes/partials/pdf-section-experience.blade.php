@if(!empty($experience['items']))
    <section class="section">
        <div class="section-title">Experience</div>
        @foreach($experience['items'] as $job)
            <div class="item">
                <div class="item-head"><span>{{ $job['role'] ?? '' }}</span><span class="muted">{{ $job['duration'] ?? trim(($job['start_date'] ?? '').' - '.(!empty($job['is_present']) ? 'Present' : ($job['end_date'] ?? ''))) }}</span></div>
                <div class="muted">{{ $job['company'] ?? '' }}</div>
                <div>{{ $job['description'] ?? '' }}</div>
            </div>
        @endforeach
    </section>
@endif
