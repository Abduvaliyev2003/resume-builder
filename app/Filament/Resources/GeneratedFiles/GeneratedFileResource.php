<?php

namespace App\Filament\Resources\GeneratedFiles;

use App\Filament\Resources\GeneratedFiles\Pages\CreateGeneratedFile;
use App\Filament\Resources\GeneratedFiles\Pages\EditGeneratedFile;
use App\Filament\Resources\GeneratedFiles\Pages\ListGeneratedFiles;
use App\Filament\Resources\GeneratedFiles\Pages\ViewGeneratedFile;
use App\Filament\Resources\GeneratedFiles\Schemas\GeneratedFileForm;
use App\Filament\Resources\GeneratedFiles\Schemas\GeneratedFileInfolist;
use App\Filament\Resources\GeneratedFiles\Tables\GeneratedFilesTable;
use App\Domains\File\Models\GeneratedFile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GeneratedFileResource extends Resource
{
    protected static ?string $model = GeneratedFile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'File';

    public static function form(Schema $schema): Schema
    {
        return GeneratedFileForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GeneratedFileInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GeneratedFilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGeneratedFiles::route('/'),
            'create' => CreateGeneratedFile::route('/create'),
            'view' => ViewGeneratedFile::route('/{record}'),
            'edit' => EditGeneratedFile::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
