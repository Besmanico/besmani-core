<?php

namespace App\Filament\Resources\PhoneCountryResource\RelationManagers;

use App\Filament\Resources\ProvinceResource\RelationManagers\CitiesRelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ToggleColumn;

class ProvincesRelationManager extends RelationManager
{
    protected static string $relationship = 'provinces';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name_en')
                    
                    ->maxLength(191),
                    Forms\Components\TextInput::make('name_fa')
                    ->required()
                    ->maxLength(191),
                    Toggle::make('status')->label('publish'),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
 

            ->columns([
                Tables\Columns\TextColumn::make('name_en'),
                Tables\Columns\TextColumn::make('name_fa'),
                ToggleColumn::make('status')->label('publish'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CitiesRelationManager::class
        ];
    }
    
}
