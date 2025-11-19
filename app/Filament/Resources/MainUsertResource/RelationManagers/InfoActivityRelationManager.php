<?php

namespace App\Filament\Resources\MainUsertResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
class InfoActivityRelationManager extends RelationManager
{
    protected static string $relationship = 'InfoActivity';
    protected static ?string $title = ' Activity';
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        $count = $ownerRecord->InfoActivity()->count();
        return " Activity ({$count})";
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
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name')->searchable()->label('Name')->badge(),
                // Tables\Columns\ImageColumn::make('image')->label('Image'),
                Tables\Columns\ImageColumn::make('image') 
                    ->label('Image')
                    ->circular()
                    ->getStateUsing(function ($record) {
                        if (!$record || !$record->image) {
                            return null; 
                        }
                        $beautyUrl = env('BEAUTY_URL', 'https://beauty.besmani.com');
                        return $record->image ? $beautyUrl . '/' . $record->image : null;
                     }) 
                    ->url(function ($record) {
                        if (!$record || !$record->image) {
                            return null;
                        }
                        $beautyUrl = env('BEAUTY_URL', 'https://beauty.besmani.com');

                        return $record->image ? $beautyUrl . '/' . $record->image : null;
                    })
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('phone')->searchable()->label('Phone'),
                Tables\Columns\TextColumn::make('email')->searchable()->label('Email'),
                
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
