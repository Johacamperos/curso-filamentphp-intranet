<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Información Personal')
                ->description('Información básica del usuario')
                ->schema([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required(),

                    TextInput::make('email')
                        ->label('Correo electrónico')
                        ->email()
                        ->required(),

                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->revealable()
                        ->required(),
                ])
                ->columns(3),

            Section::make('Dirección')
                ->description('Ubicación del usuario')
                ->schema([
                    Select::make('country_id')
                        ->label('País')
                        ->relationship('country', 'name') 
                        ->searchable()
                        ->preload()
                        ->required(),                     
                ])
                ->columns(1)
                ->columnSpanFull(),
        ]);
    }
}
