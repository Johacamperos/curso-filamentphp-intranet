<?php

namespace App\Filament\Resources\States\Schemas;

use App\Models\Country;
use Filament\Forms; // para los Components
use Filament\Schemas\Schema;
// 👇 este ES el Set correcto en modo modular
use Filament\Schemas\Components\Utilities\Set;

class StateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Select::make('country_id')
                ->label('Country')
                ->relationship('country', 'name')
                ->searchable()
                ->preload()
                ->reactive()
                ->required()
                ->afterStateUpdated(function ($state, Set $set) {
                    // Solo si tu tabla states tiene country_code (ISO2)
                    $set('country_code', Country::find($state)?->iso2);
                }),

            Forms\Components\TextInput::make('name')
                ->label('State')
                ->required()
                ->maxLength(255),

            // Oculto si necesitas guardar el ISO2
            Forms\Components\Hidden::make('country_code'),
        ])->columns(2);
    }
}
