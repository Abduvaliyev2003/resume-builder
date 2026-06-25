<?php

namespace App\Filament\Resources\GeneratedFiles\Pages;

use App\Filament\Resources\GeneratedFiles\GeneratedFileResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGeneratedFile extends ViewRecord
{
    protected static string $resource = GeneratedFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
