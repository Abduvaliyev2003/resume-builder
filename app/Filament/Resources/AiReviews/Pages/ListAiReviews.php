<?php

namespace App\Filament\Resources\AiReviews\Pages;

use App\Filament\Resources\AiReviews\AiReviewResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAiReviews extends ListRecords
{
    protected static string $resource = AiReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
