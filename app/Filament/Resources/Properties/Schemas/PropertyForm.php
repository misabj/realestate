<?php

namespace App\Filament\Resources\Properties\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

// >>> OVO su jedini "Set/Get" koje treba da koristiš u Schemas <<<
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;

use Filament\Notifications\Notification;
use Filament\Schemas\Components\Tabs as ComponentsTabs;
use Filament\Schemas\Components\Tabs\Tab as TabsTab;
use Illuminate\Support\Str;

class PropertyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // ===== Basic info =====
            Section::make('Basic info')
                ->schema([
                    Grid::make(12)->schema([
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->required()
                            ->columnSpan(3),

                        TextInput::make('title')
                            ->label('Title (EN)')
                            ->required()
                            ->maxLength(255)
                            ->live(debounce: 500)
                            ->afterStateUpdated(function (Set $set, ?string $state, Get $get) {
                                // auto-slug samo ako je prazan
                                if (blank($get('slug'))) {
                                    $set('slug', Str::slug($state ?? ''));
                                }
                            })
                            ->columnSpan(6),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->maxLength(255)
                            ->columnSpan(3),
                    ]),

                    Grid::make(12)->schema([
                        TextInput::make('price')->label('Price')->numeric()->prefix('€')->minValue(0)->columnSpan(3),
                        TextInput::make('area')->label('Area (m²)')->numeric()->minValue(0)->columnSpan(3),
                        TextInput::make('rooms')->label('Rooms')->numeric()->minValue(0)->columnSpan(3),
                        TextInput::make('floor')->label('Floor')->numeric()->minValue(0)->columnSpan(3),
                    ]),
                ])
                ->collapsible(),

            // ===== Location =====
            Section::make('Location')
                ->schema([
                    Grid::make(3)->schema([
                        TextInput::make('city')->label('City'),
                        TextInput::make('address')->label('Address')->placeholder('Street, number'),
                        TextInput::make('dummy')->hidden(), // placeholder da grid ostane 3 kolone
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('lat')->label('Lat')->numeric(),
                        TextInput::make('lng')->label('Lng')->numeric(),
                    ]),
                ])
                ->collapsible(),

            // ===== Content (prevodi) =====
            Section::make('Content')
                ->schema([
                    // “Dugme” bez Actions: toggle koji se posle klika resetuje
                    Toggle::make('copy_en_to_sr_ru')
                        ->label('Copy EN → SR/RU')
                        ->inline(false)
                        ->live()
                        ->afterStateUpdated(function (Set $set, Get $get, ?bool $state) {
                            if (! $state) {
                                return;
                            }

                            $set('title_sr',       $get('title'));
                            $set('title_ru',       $get('title'));
                            $set('description_sr', $get('description'));
                            $set('description_ru', $get('description'));

                            // reset da se ponaša kao “button”
                            $set('copy_en_to_sr_ru', false);

                            Notification::make()
                                ->title('EN sadržaj kopiran u SR i RU polja.')
                                ->success()
                                ->send();
                        }),

                    ComponentsTabs::make('Translations')
                        ->tabs([
                            TabsTab::make('EN')->schema([
                                Textarea::make('description')
                                    ->label('Description (EN)')
                                    ->rows(7),
                            ]),
                            TabsTab::make('SR')->schema([
                                TextInput::make('title_sr')
                                    ->label('Naslov (SR)')
                                    ->maxLength(255)
                                    ->required(fn (Get $get) => (bool) $get('is_published')),
                                Textarea::make('description_sr')
                                    ->label('Opis (SR)')
                                    ->rows(7)
                                    ->required(fn (Get $get) => (bool) $get('is_published')),
                            ]),
                            TabsTab::make('RU')->schema([
                                TextInput::make('title_ru')
                                    ->label('Заголовок (RU)')
                                    ->maxLength(255)
                                    ->required(fn (Get $get) => (bool) $get('is_published')),
                                Textarea::make('description_ru')
                                    ->label('Описание (RU)')
                                    ->rows(7)
                                    ->required(fn (Get $get) => (bool) $get('is_published')),
                            ]),
                        ])
                        ->persistTabInQueryString(),
                ])
                ->collapsible(),

            // ===== Media & Publish =====
            Section::make('Media & Publish')
                ->schema([
                    Grid::make(3)->schema([
                        FileUpload::make('images')
                            ->label('Images')
                            ->image()
                            ->multiple()
                            ->deletable(true)
                            ->reorderable()
                            ->openable()
                            ->downloadable()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('properties')
                            ->visibility('public')
                            ->appendFiles()
                            ->maxFiles(12)
                            ->preserveFilenames()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->columnSpan(2),

                        Toggle::make('is_published')
                            ->label('Is published')
                            ->inline(false)
                            ->default(false)
                            ->columnSpan(1),
                    ]),
                ])
                ->collapsible(),
        ]);
    }
}
