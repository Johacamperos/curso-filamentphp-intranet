<?php

namespace App\Filament\Resources\Cities\Schemas;

use App\Models\Country;
use App\Models\State;
use Filament\Forms;
use Filament\Schemas\Schema;
// 👇 este Set es el correcto en Filament modular
use Filament\Schemas\Components\Utilities\Set;

class CityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([

            // Selección de Country
            Forms\Components\Select::make('country_id')
                ->label('Country')
                ->relationship('country', 'name') // usa belongsTo City -> Country
                ->searchable()
                ->preload()
                ->reactive()
                ->required()
                ->afterStateUpdated(function ($state, Set $set) {
                    // Cuando cambia el país, reseteamos el state_id
                    $set('state_id', null);

                    // Opcional: si quieres guardar el código automáticamente
                    $set('country_code', Country::find($state)?->iso2);
                }),

            // Selección de State (filtrado por country_id)
            Forms\Components\Select::make('state_id')
                ->label('State/Province')
                ->options(fn ($get) => 
                    $get('country_id')
                        ? State::query()
                            ->where('country_id', $get('country_id'))
                            ->pluck('name', 'id')
                            ->toArray()
                        : []
                )
                ->searchable()
                ->preload()
                ->required(),

            // Nombre de la ciudad
            Forms\Components\TextInput::make('name')
                ->label('City')
                ->required()
                ->maxLength(255),

            // Guardar el código del país (puede ser oculto si no quieres que el usuario lo edite)
            Forms\Components\Hidden::make('country_code')
                ->required(),
        ])->columns(2);
    }
}
