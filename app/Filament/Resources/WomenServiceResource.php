<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WomenServiceResource\Pages;
use App\Filament\Resources\WomenServiceResource\RelationManagers;
use App\Models\WomenService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
 use Filament\Tables\Columns\TextColumn;

class WomenServiceResource extends Resource
{
    protected static ?string $model = WomenService::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';


    protected static ?string $navigationGroup = 'BEAUTY';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = "  Women's Salon  ";
    protected static ?string $modelLabel = "   Women's Salon  ";
    protected static ?string $pluralModelLabel = "  Women's Salon    ";



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->defaultPaginationPageOption(50)

            ->columns([
                Tables\Columns\TextColumn::make(name: 'title')->searchable()->sortable(),
                Tables\Columns\ImageColumn::make('img') 
                ->label('Image')
                ->circular()
                ->getStateUsing(function ($record) {
                    if (!$record || !$record->img) {
                        return null; 
                    }
                    $beautyUrl = env('BEAUTY_URL', 'https://beauty.besmani.com');
                    return $record->img ? $beautyUrl  . $record->img : null;
                 }), 
                Tables\Columns\TextColumn::make('category.title')->label('Category')->badge()->color('info'),
                Tables\Columns\TextColumn::make('technical_code')->label('Technical Code'),
                Tables\Columns\IconColumn::make('status')->label('Status')->boolean(),
                Tables\Columns\TextColumn::make('create_at')
                    ->label('Created At')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'title')
                    ->label('Category'),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWomenServices::route('/'),
            'create' => Pages\CreateWomenService::route('/create'),
            'edit' => Pages\EditWomenService::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('id', 'desc');
    }
}
