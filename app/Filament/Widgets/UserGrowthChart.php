<?php

namespace App\Filament\Widgets;

use App\Domains\User\Models\User;
use Filament\Widgets\ChartWidget;

class UserGrowthChart extends ChartWidget
{
    protected ?string $heading = 'User Growth (Last 7 Days)';

    protected function getData(): array
    {
        $users = User::query()
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

            $data[] = $users[$date]->total ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Users',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}