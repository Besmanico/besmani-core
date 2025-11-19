<?php

namespace App\Filament\Resources\ProvinceResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\County;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class CitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'cities';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name_en')
                    ->required()

                    ->maxLength(191),
                Forms\Components\TextInput::make('name_fa')
                   
                    ->maxLength(191),
                Forms\Components\Select::make('county_id')->searchable()->label('County')
                    ->options(County::all()->pluck('name', 'id')),
                Hidden::make('user_id')->default(Auth::user()->id)->dehydrated(true),

                Toggle::make('status')->label('publish'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('name_en')->searchable(),
                Tables\Columns\TextColumn::make('name_fa')->searchable(),
                Tables\Columns\TextColumn::make('county.name')->searchable()->label('County'),
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
}
