<?php

namespace App\Filament\Resources\Cities\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;

class CitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Ciudad')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('state.name')->label('Estado')->sortable(),
                Tables\Columns\TextColumn::make('state.country.name')->label('País')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // ❌ Quitar TrashedFilter: no hay SoftDeletes en cities
                // Tables\Filters\TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // ✅ Delete bulk hace eliminación dura si no hay SoftDeletes
                    DeleteBulkAction::make(),
                    // ❌ Quitar Force/Restore: requieren SoftDeletes
                    // ForceDeleteBulkAction::make(),
                    // RestoreBulkAction::make(),
                ]),
            ]);
    }
}
