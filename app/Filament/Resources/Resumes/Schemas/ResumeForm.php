<?php

namespace App\Filament\Resources\Resumes\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;

class ResumeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Resume Information')
                    ->schema([

                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Grid::make(2)
                            ->schema([

                                Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->required(),

                                Select::make('template_id')
                                    ->relationship('template', 'name')
                                    ->searchable()
                                    ->required(),

                            ]),

                        Toggle::make('is_completed')
                            ->default(false),

                    ]),

                Section::make('Personal Information')
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                TextInput::make('personal_info.first_name')
                                    ->required(),

                                TextInput::make('personal_info.last_name')
                                    ->required(),

                                TextInput::make('personal_info.email')
                                    ->email(),

                                TextInput::make('personal_info.phone'),

                                TextInput::make('personal_info.city'),

                                TextInput::make('personal_info.country'),

                            ]),

                        Textarea::make('personal_info.address'),

                        FileUpload::make('personal_info.avatar')
                            ->image(),

                        Textarea::make('personal_info.summary')
                            ->rows(5),

                    ]),

                Section::make('Work Experience')
                    ->schema([

                        Repeater::make('experiences')
                            ->schema([

                                TextInput::make('job_title')
                                    ->required(),

                                TextInput::make('company')
                                    ->required(),

                                TextInput::make('location'),

                                DatePicker::make('start_date'),

                                DatePicker::make('end_date'),

                                Textarea::make('description'),

                            ])
                            ->defaultItems(1)
                            ->collapsible()
                            ->cloneable(),

                    ]),

                Section::make('Education')
                    ->schema([

                        Repeater::make('educations')
                            ->schema([

                                TextInput::make('institution'),

                                TextInput::make('degree'),

                                TextInput::make('field_of_study'),

                                DatePicker::make('start_date'),

                                DatePicker::make('end_date'),

                            ])
                            ->collapsible()
                            ->cloneable(),

                    ]),

                Section::make('Skills')
                    ->schema([

                        Repeater::make('skills')
                            ->schema([

                                TextInput::make('name'),

                                Select::make('level')
                                    ->options([
                                        'Beginner' => 'Beginner',
                                        'Intermediate' => 'Intermediate',
                                        'Advanced' => 'Advanced',
                                        'Expert' => 'Expert',
                                    ]),

                            ])
                            ->collapsible(),

                    ]),

                Section::make('Languages')
                    ->schema([

                        Repeater::make('languages')
                            ->schema([

                                TextInput::make('language'),

                                Select::make('level')
                                    ->options([
                                        'A1' => 'A1',
                                        'A2' => 'A2',
                                        'B1' => 'B1',
                                        'B2' => 'B2',
                                        'C1' => 'C1',
                                        'C2' => 'C2',
                                        'Native' => 'Native',
                                    ]),

                            ])
                            ->collapsible(),

                    ]),

                Section::make('Certificates')
                    ->schema([

                        Repeater::make('certificates')
                            ->schema([

                                TextInput::make('name'),

                                TextInput::make('issuer'),

                                DatePicker::make('issue_date'),

                            ])
                            ->collapsible(),

                    ]),

                Section::make('Projects')
                    ->schema([

                        Repeater::make('projects')
                            ->schema([

                                TextInput::make('title'),

                                Textarea::make('description'),

                                TextInput::make('url'),

                            ])
                            ->collapsible(),

                    ]),

                Section::make('Social Links')
                    ->schema([

                        Repeater::make('social_links')
                            ->schema([

                                Select::make('platform')
                                    ->options([
                                        'LinkedIn' => 'LinkedIn',
                                        'GitHub' => 'GitHub',
                                        'Telegram' => 'Telegram',
                                        'Facebook' => 'Facebook',
                                        'Instagram' => 'Instagram',
                                        'Website' => 'Website',
                                    ]),

                                TextInput::make('url'),

                            ])
                            ->collapsible(),

                    ]),
            ]);
    }
}