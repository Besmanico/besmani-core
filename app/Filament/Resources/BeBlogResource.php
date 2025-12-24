<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\BeBlog;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BeBlogResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BeBlogResource\RelationManagers;

class BeBlogResource extends Resource
{
    protected static ?string $model = BeBlog::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'BEAUTY';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationLabel = "  Blogs  ";
    protected static ?string $modelLabel = "   Blogs  ";
    protected static ?string $pluralModelLabel = " Blogs ";


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
                TextColumn::make('title_news')->label('Title'),
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

                 Tables\Columns\IconColumn::make('confirm_show')->label('Status')->boolean(),


                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListBeBlogs::route('/'),
            'create' => Pages\CreateBeBlog::route('/create'),
            'edit' => Pages\EditBeBlog::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('id', 'desc');
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
