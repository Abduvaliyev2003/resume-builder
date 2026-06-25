<?php

namespace App\Filament\Resources\AiReviews;

use App\Filament\Resources\AiReviews\Pages\CreateAiReview;
use App\Filament\Resources\AiReviews\Pages\EditAiReview;
use App\Filament\Resources\AiReviews\Pages\ListAiReviews;
use App\Filament\Resources\AiReviews\Schemas\AiReviewForm;
use App\Filament\Resources\AiReviews\Tables\AiReviewsTable;
use App\Domains\AI\Models\AIReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AiReviewResource extends Resource
{
    protected static ?string $model = AIReview::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Aireview';

    public static function form(Schema $schema): Schema
    {
        return AiReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AiReviewsTable::configure($table);
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
            'index' => ListAiReviews::route('/'),
            'create' => CreateAiReview::route('/create'),
            'edit' => EditAiReview::route('/{record}/edit'),
        ];
    }
}
