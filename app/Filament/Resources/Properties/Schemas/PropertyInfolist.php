<?php

namespace App\Filament\Resources\Properties\Schemas;

use Faker\Core\File;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PropertyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('category.name')
                    ->label('Category'),
                TextEntry::make('images')
                    ->label('Images'),
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('price')
                    ->money()
                    ->placeholder('-'),
                TextEntry::make('area')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('rooms')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('floor')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('city')
                    ->placeholder('-'),
                TextEntry::make('address')
                    ->placeholder('-'),
                TextEntry::make('lat')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('lng')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_published')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
