<?php

namespace App\Filament\Resources\AiReviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class AiReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('resume.title')
                    ->label('Resume')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ats_score')
                    ->badge()
                    ->sortable(),

                TextColumn::make('grammar_score')
                    ->badge()
                    ->sortable(),

                TextColumn::make('job_match_score')
                    ->badge()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

            ])

            ->filters([
                //
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}