<?php

namespace App\Filament\Resources\Templates\Schemas;

use Filament\Schemas\Schema;

class TemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //TextInput::make('name');

TextInput::make('slug'),

FileUpload::make('preview_image')
    ->image(),

Textarea::make('description'),

Toggle::make('is_active'),
            ]);
    }
}
