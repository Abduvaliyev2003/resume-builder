<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                

                IconColumn::make('email_verified_at')
                    ->boolean()
                    ->label('Verified'),

                TextColumn::make('created_at')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->dateTime('d.m.Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('role')
    ->badge()
    ->color(fn (string $state) => match ($state) {
        'admin' => 'danger',
        'user' => 'success',
        default => 'gray',
    }),
            ])

            ->filters([
                //
            ])

            ->recordActions([
                EditAction::make(),
            ])

            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}