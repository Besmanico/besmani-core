<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\WbComment;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\WbCommentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\ToggleColumn;
use App\Filament\Resources\WbCommentResource\RelationManagers;
use App\Models\MainUser;

class WbCommentResource extends Resource
{
    protected static ?string $model = WbComment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationGroup = 'BEAUTY';
    protected static ?string $navigationLabel = "  Comments  ";
    protected static ?string $modelLabel = "   Comments  ";
    protected static ?string $pluralModelLabel = "  Comments   ";
    protected static ?int $navigationSort = 7;

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
                    return $user->fl_name;
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
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListWbComments::route('/'),
            'create' => Pages\CreateWbComment::route('/create'),
            'edit' => Pages\EditWbComment::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
