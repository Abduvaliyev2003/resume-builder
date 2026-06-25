<?php

namespace App\Filament\Resources\GeneratedFiles\Pages;

use App\Filament\Resources\GeneratedFiles\GeneratedFileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGeneratedFiles extends ListRecords
{
    protected static string $resource = GeneratedFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
