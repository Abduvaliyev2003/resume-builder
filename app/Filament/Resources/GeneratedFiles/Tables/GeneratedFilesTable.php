<?php

namespace App\Filament\Resources\GeneratedFiles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TrashedFilter;

class GeneratedFilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('resume.title')
                    ->label('Resume')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('file_type')
                    ->badge()
                    ->sortable(),

                TextColumn::make('file_path')
                    ->label('File')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->file_path),

                IconColumn::make('download_token')
                    ->label('Secure')
                    ->boolean(fn ($record) => ! empty($record->download_token)),

                TextColumn::make('expires_at')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                IconColumn::make('expires_at')
                    ->label('Expired')
                    ->boolean(
                        fn ($record) =>
                            $record->expires_at &&
                            $record->expires_at->isPast()
                    ),

                TextColumn::make('created_at')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

            ])

            ->filters([
                TrashedFilter::make(),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}