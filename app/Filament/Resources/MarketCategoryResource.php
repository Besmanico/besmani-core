<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\MarketCategory;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MarketCategoryResource\Pages;
use App\Filament\Resources\MarketCategoryResource\RelationManagers;

class MarketCategoryResource extends Resource
{
    protected static ?string $model = MarketCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Market';
    protected static ?string $navigationLabel = "   Category  ";
    protected static ?string $modelLabel = "     Category  ";
    protected static ?string $pluralModelLabel = "    Category    ";
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('  info')
                    ->schema([
                        TextInput::make('name')->label('Name')->required(),

                        Hidden::make('parent_id')->default(0),

                        FileUpload::make('image')->label('Image')
                            ->directory('market_category')->optimize('webp')->helperText('  390*350 - Optimize image and convert to webp format'),
 
                        RichEditor::make('body')->label('Description')->columnSpanFull(),
                        Toggle::make('status')->label('Published'),
                        Hidden::make('user_id')->default(Auth::user()->id)->dehydrated(true),


                    ])->collapsed(false)->columns(2),
                Section::make('seo ')
                    ->schema([
                        TagsInput::make('keywords')->label('Keywords'),
                        Textarea::make('meta')->label('Meta'),

                    ])->collapsed(false)->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')->label('Name'),
                ImageColumn::make('image')->label('Image'),
                ToggleColumn::make('status')->label('published'),
                TextColumn::make('created_at')->label('Created At'),
                TextColumn::make('updated_at')->label('Updated At'),
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
            'index' => Pages\ListMarketCategories::route('/'),
            'create' => Pages\CreateMarketCategory::route('/create'),
            'edit' => Pages\EditMarketCategory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
