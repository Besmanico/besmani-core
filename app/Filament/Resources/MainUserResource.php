<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\MainUser;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MainUserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MainUserResource\RelationManagers;
use App\Filament\Resources\ProductResource\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\MainUserResource\RelationManagers\WsPortfolioRelationManager;
use App\Filament\Resources\MainUsertResource\RelationManagers\InfoActivityRelationManager;

class MainUserResource extends Resource
{
    protected static ?string $model = MainUser::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';
    protected static ?string $navigationGroup = 'Public';
    protected static ?string $navigationLabel = "  Main User  ";
    protected static ?string $modelLabel = "   Main User  ";
    protected static ?string $pluralModelLabel = "  Main User    ";
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        TextInput::make('fl_name')->label('First Name')->required(),
                        TextInput::make('last_name')->label('Last Name')->required(),
                        TextInput::make('email')->label('Email')->required(),
                        TextInput::make('mobile')->label('Phone No.')->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('fl_name')->searchable()->label('First Name')->searchable(),
                TextColumn::make('last_name')->searchable()->label('Last Name')->searchable(),
                TextColumn::make('email')->searchable()->label('Email')->searchable(),
                TextColumn::make('mobile')->searchable()->label('Phone No.')->searchable(),
                TextColumn::make('confirm_code')->searchable()->label('Confirm Code')->badge()->color('danger')->toggleable(false),
                TextColumn::make('fl_moaref')->searchable()->label('Reference Name')->toggleable(true),
                TextColumn::make('mobile_moaref')->searchable()->label('Reference Phone No.')->toggleable(true),
                TextColumn::make('code_moaref')->searchable()->label('Code')->badge()->toggleable(true),
                ToggleColumn::make('approved')->label('Approved')->toggleable(true), 

               
            ])
            ->filters([
                //
            ])
            ->actions([
                //  Tables\Actions\DeleteAction::make(),  
                 Tables\Actions\EditAction::make(),  
                 Tables\Actions\ViewAction::make(),  
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
            InfoActivityRelationManager::class,

            ProductsRelationManager::class,   
            WsPortfolioRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMainUsers::route('/'),
            'create' => Pages\CreateMainUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            //  'edit' => Pages\EditMainUser::route('/{record}/edit'),
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
