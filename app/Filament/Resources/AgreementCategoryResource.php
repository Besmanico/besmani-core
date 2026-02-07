<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgreementCategoryResource\Pages;
use App\Filament\Resources\AgreementCategoryResource\RelationManagers;
use App\Models\AgreementCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;

class AgreementCategoryResource extends Resource
{
    protected static ?string $model = AgreementCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Public';
    protected static ?string $navigationLabel = "  Agreement Category  ";
    protected static ?string $modelLabel = "   Agreement Category  ";
    protected static ?string $pluralModelLabel = "  Agreement Category    ";
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->label('Title')
                ->required()
                ->maxLength(191),
                Hidden::make('user_id')
                ->default(Auth::user()->id),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Title'),
                Tables\Columns\TextColumn::make('user.email')->label('User'),
                Tables\Columns\TextColumn::make('created_at')->label('Created At'),
                Tables\Columns\TextColumn::make('updated_at')->label('Updated At'),
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
            'index' => Pages\ListAgreementCategories::route('/'),
            'create' => Pages\CreateAgreementCategory::route('/create'),
            'edit' => Pages\EditAgreementCategory::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('id', 'desc');
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
