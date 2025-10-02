<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Tapp\FilamentAuthenticationLog\RelationManagers\AuthenticationLogsRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

 
    
    protected static ?string $navigationLabel = "admin";
    protected static ?string $modelLabel = "  admin   ";
    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

   

    protected static ?string $navigationIcon = 'heroicon-s-user-group';
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('')
                ->schema([
                    // Hidden::make('role')->default('super-admin')->dehydrated(true),
                    TextInput::make('name')->label('name')->required(),
                    TextInput::make('email')->label('email')->email()->required(),
                    TextInput::make('password')->minLength(6)
                        ->password()->required()
                        ->label('password')->helperText('Password must be at least 6 characters 
                '),
                Toggle::make('status')->label('status'),

                    // Select::make('roles')
                    //     ->relationship('roles', 'name')
                    //     ->multiple()
                    //     ->preload()
                    //     ->searchable(),

                ])->collapsible()->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->label('name'),
                TextColumn::make('email')->searchable()->label('email'),
                IconColumn::make('status')
                ->boolean(),

                TextColumn::make('created_at')->label('created_at')->badge(),
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
            AuthenticationLogsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('role', 0)->orderBy('id', 'desc');
    }
}
