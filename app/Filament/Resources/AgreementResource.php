<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Agreement;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\AgreementCategory;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AgreementResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AgreementResource\RelationManagers;
use Filament\Forms\Components\RichEditor;
class AgreementResource extends Resource
{
    protected static ?string $model = Agreement::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Public';
    protected static ?string $navigationLabel = "  Agreement  ";
    protected static ?string $modelLabel = "   Agreement  ";
    protected static ?string $pluralModelLabel = "  Agreement    ";
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('info')
                    ->schema([
                        Forms\Components\Select::make('agreement_category_id')->label('Category')
                            ->options(AgreementCategory::all()->pluck('title', 'id'))
                            ->required(),
                        RichEditor::make('description')
                        ->columnSpanFull()
                            ->required(),
                    ])->collapsed(false)->columns(2),
                Hidden::make('user_id')
                    ->default(Auth::user()->id),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('agreement_category.title')->label('Category')->badge(),
                Tables\Columns\TextColumn::make('description')->label('Description')->limit(20),
                Tables\Columns\TextColumn::make('user.email')->label('User'),
                Tables\Columns\TextColumn::make('created_at')->label('Created At'),
                Tables\Columns\TextColumn::make('updated_at')->label('Updated At'),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgreements::route('/'),
            'create' => Pages\CreateAgreement::route('/create'),
            'edit' => Pages\EditAgreement::route('/{record}/edit'),
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
