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
class WsPortfolioRelationManager extends RelationManager
{
    protected static string $relationship = 'WsPortfolio';
    protected static ?string $title = 'Portfolio';
    
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        $count = $ownerRecord->WsPortfolio()->count();
        return "Portfolio ({$count})";
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
                // Tables\Columns\ImageColumn::make('img')->label('Image'),
                Tables\Columns\ImageColumn::make('image') 
                ->label('Image')
                ->circular()
                ->getStateUsing(function ($record) {
                    if (!$record || !$record->img) {
                        return null; 
                    }
                    $beautyUrl = env('BEAUTY_URL', 'https://beauty.besmani.com/public/assets/photos/');
                    return $record->img ? $beautyUrl . '/' . $record->img : null;
                 }) 
                ->url(function ($record) {
                    if (!$record || !$record->img) {
                        return null;
                    }
                    $beautyUrl = env('BEAUTY_URL', 'https://beauty.besmani.com/public/assets/photos/');

                    return $record->img ? $beautyUrl . '/' . $record->img : null; 
                })
                ->openUrlInNewTab(),
                Tables\Columns\ToggleColumn::make('published')->label('Published'), 

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
