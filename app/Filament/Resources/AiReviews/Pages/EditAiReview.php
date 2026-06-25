<?php

namespace App\Filament\Resources\AiReviews\Pages;

use App\Filament\Resources\AiReviews\AiReviewResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAiReview extends EditRecord
{
    protected static string $resource = AiReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
