<?php

namespace App\Filament\Resources\MainUserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
class MenSalonServiceRelationManager extends RelationManager
{
    protected static string $relationship = 'menSalonService';
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        $count = $ownerRecord->menSalonService()->count();
        return "Men Service  ({$count})";
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->defaultSort('create_at', 'desc')

            ->columns([
                Tables\Columns\TextColumn::make('service.title')->label('Service'),
                Tables\Columns\TextColumn::make('price')->label('Price'),
                Tables\Columns\TextColumn::make('capacity')->label('Seat(s)'),
                Tables\Columns\TextColumn::make('time_work')->label('Duration (hr)	'),
                Tables\Columns\TextColumn::make('create_at')->label('Created At')->badge()->color('info'),

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
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
           ->orderBy('create_at', 'desc');
    }
}
