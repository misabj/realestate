<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('key')
                    ->label('Key (bottom1, bottom2, bottom3, bottom4)')
                    ->required()
                    ->maxLength(50),

                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->maxLength(255),

                Forms\Components\FileUpload::make('image')
                    ->label('Image')
                    ->image()
                    ->disk('public')
                    ->directory('banners')
                    ->visibility('public'),

                Forms\Components\TextInput::make('link')
                    ->label('Link (URL ili route)')
                    ->maxLength(255)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state && ! str_starts_with($state, 'http://') && ! str_starts_with($state, 'https://')) {
                            $set('link', 'https://' . $state);
                        }
                    })
                    ->helperText('Unesi pun URL ili samo domen (automatski dodajemo https://).'),

                Forms\Components\Toggle::make('active')
                    ->label('Active')
                    ->default(true),
            ]);
    }
}
