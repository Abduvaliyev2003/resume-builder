<?php

namespace App\Filament\Resources\GeneratedFiles\Pages;

use App\Filament\Resources\GeneratedFiles\GeneratedFileResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditGeneratedFile extends EditRecord
{
    protected static string $resource = GeneratedFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
