<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\MainUser;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProductComment;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductCommentResource\Pages;
use App\Filament\Resources\ProductCommentResource\RelationManagers;

class ProductCommentResource extends Resource
{
    protected static ?string $model = ProductComment::class;

     protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationGroup = 'BEAUTY';

    protected static ?string $navigationLabel = "  Product Comments  ";
    protected static ?string $modelLabel = "   Product Comments  ";
    protected static ?string $pluralModelLabel = "  Product Comments   ";
    protected static ?int $navigationSort = 8;
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
                TextColumn::make('comment')->searchable()->label('Comment')->limit(20)->html()
                ->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();
                    if (strlen($state) <= $column->getCharacterLimit()) {
                        return null;
                    }
                    return $state;
                }),
                TextColumn::make('service')->searchable()->label('Service'), 

                TextColumn::make('user_id')->getStateUsing(function ($record) {
                    $user = MainUser::find($record->user_id);
                    if ($user) {
                        return $user->fl_name . ' ' . ($user->last_name ?? '');
                    }
                    return 'N/A';
                })->label('User'),
                // TextColumn::make('salon_id')->getStateUsing(function ($record) {
                //     $user = MainUser::find($record->salon_id);
                //     return $user->fl_name;
                // })->label('Provider'),
                ToggleColumn::make('c_status')->label('publish'),
                TextColumn::make('create_at')->searchable()->label('Created At'), 
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
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
           ->orderBy('id', 'desc');
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductComments::route('/'),
            'create' => Pages\CreateProductComment::route('/create'),
            'edit' => Pages\EditProductComment::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
