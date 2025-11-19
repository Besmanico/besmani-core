<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Model;
class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        $count = $ownerRecord->products()->count();
        return "Products ({$count})";
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
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('title')->limit(30)
                ->label('  Name ')
                ->searchable()
                ->sortable()
                ->weight('bold'),
                Tables\Columns\ImageColumn::make('img') 
                ->label('Image')
                ->circular()
                ->getStateUsing(function ($record) {
                    if (!$record || !$record->img) {
                        return null; 
                    }

                    $beautyUrl = env('BEAUTY_URL', 'https://beauty.besmani.com/public/assets/images/products/');
                    return $record->img ? $beautyUrl . '/' . $record->id . '/' . $record->img : null;
                 }) 
                ->url(function ($record) {
                    if (!$record || !$record->img) {
                        return null;
                    }
                    $beautyUrl = env('BEAUTY_URL', 'https://beauty.besmani.com/public/assets/images/products/');

                    return $record->img ? $beautyUrl . '/' . $record->id . '/' . $record->img : null;
                })
                ->openUrlInNewTab(),
            Tables\Columns\TextColumn::make('category.title')
                ->label('Category')
                ->searchable()
                ->sortable()
                ->badge()
                ->color('info'),   
                ToggleColumn::make('published')->label('Publish'),

            ])
            ->defaultSort('id', 'desc')
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
