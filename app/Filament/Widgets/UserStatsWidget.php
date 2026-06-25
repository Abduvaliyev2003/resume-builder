<?php

namespace App\Filament\Widgets;

use App\Domains\AI\Models\AIReview;
use App\Domains\File\Models\GeneratedFile;
use App\Domains\Resume\Models\Resume;
use App\Domains\Template\Models\Template;
use App\Domains\User\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends StatsOverviewWidget
{ 
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
             Stat::make('Users', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Resumes', Resume::count())
                ->description('Created resumes')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('success'),

            Stat::make('Generated Files', GeneratedFile::count())
                ->description('PDF & DOCX exports')
                ->descriptionIcon('heroicon-o-arrow-down-tray')
                ->color('warning'),

            Stat::make('AI Reviews', AIReview::count())
                ->description('AI analyses')
                ->descriptionIcon('heroicon-o-cpu-chip')
                ->color('danger'),

            Stat::make('Templates', Template::count())
                ->description('Available templates')
                ->descriptionIcon('heroicon-o-rectangle-stack')
                ->color('info'),
            
        ];
    }
}
