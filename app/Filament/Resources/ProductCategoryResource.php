<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProductCategory;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductCategoryResource\Pages;
use App\Filament\Resources\ProductCategoryResource\RelationManagers;

class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'BEAUTY';
    protected static ?string $navigationLabel = " Store(Category)  ";         
    protected static ?string $modelLabel = "   Category  "; 
    protected static ?string $pluralModelLabel = "  Category    ";
    protected static ?int $navigationSort = 6;

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
            ->columns([
                TextColumn::make('title')->searchable()->label('Name'),
                ToggleColumn::make('status')->searchable()->label('Status'),
                // ImageColumn::make('img')->label('image')->circular(),
                Tables\Columns\ImageColumn::make('img') 
                ->label('Image')
                ->circular()
                ->getStateUsing(function ($record) {
                    if (!$record || !$record->img) {
                        return null; 
                    }
                    $beautyUrl = env('BEAUTY_URL', 'https://beauty.besmani.com');
                    return $record->img ? $beautyUrl . '/' . $record->img : null;
                })
                ->url(function ($record) {
                    if (!$record || !$record->img) {
                        return null;
                    }
                    $beautyUrl = env('BEAUTY_URL', 'https://beauty.besmani.com');
                    return $record->img ? $beautyUrl . '/' . $record->img : null;
                })
                ->openUrlInNewTab(),

                // TextColumn::make('created_at')->searchable()->label('created_at'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductCategories::route('/'),
            'create' => Pages\CreateProductCategory::route('/create'),
            'edit' => Pages\EditProductCategory::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
