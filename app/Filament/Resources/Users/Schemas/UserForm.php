<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                   TextInput::make('name')
    ->required()
    ->maxLength(255),

TextInput::make('email')
    ->email()
    ->required(),

TextInput::make('password')
    ->password()
    ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
    ->dehydrated(fn ($state) => filled($state)),

Select::make('role')
    ->options([
        'admin' => 'Admin',
        'user' => 'User',
    ]),

Toggle::make('is_active'),
            ]);
    }
}
