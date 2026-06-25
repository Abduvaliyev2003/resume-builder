<?php

namespace App\Filament\Resources\AiReviews\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;

class AiReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ats_score')
                    ->numeric(),

                TextInput::make('grammar_score')
                    ->numeric(),

                TextInput::make('job_match_score')
                    ->numeric(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),

                Textarea::make('review_data')
                    ->rows(10),
            ]);
    }
}