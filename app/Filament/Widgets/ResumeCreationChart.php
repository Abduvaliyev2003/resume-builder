<?php

namespace App\Filament\Widgets;

use App\Domains\Resume\Models\Resume;
use Filament\Widgets\ChartWidget;

class ResumeCreationChart extends ChartWidget
{
    protected ?string $heading = 'Resume Creation (Last 7 Days)';

    protected function getData(): array
    {
        $resumes = Resume::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();

            $labels[] = now()->subDays($i)->format('D');

            $data[] = $resumes[$date]->total ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Resumes',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}