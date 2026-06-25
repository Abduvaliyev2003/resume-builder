<?php

namespace App\Filament\Widgets;

use App\Domains\Template\Models\Template;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class TopTemplatesChart extends ChartWidget
{
    protected ?string $heading = 'Top Resume Templates';

    protected function getData(): array
    {
        $templates = Cache::remember(
            'dashboard.top-templates',
            now()->addMinutes(10),
            fn () => Template::query()
                ->withCount('resumes')
                ->orderByDesc('resumes_count')
                ->limit(5)
                ->get()
        );

        return [
            'datasets' => [
                [
                    'label' => 'Usage Count',
                    'data' => $templates
                        ->pluck('resumes_count')
                        ->toArray(),
                ],
            ],
            'labels' => $templates
                ->pluck('name')
                ->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}