<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Activity;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ActivityResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ActivityResource\RelationManagers;
use App\Filament\Resources\ActivityResource\RelationManagers\ProvidersRelationManager;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'BEAUTY';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('info')
                ->schema([ 
                TextInput::make('title')->required()->label('Title'),
  
                ])->collapsed(false)->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

        ->defaultPaginationPageOption(50)

            ->columns([
                TextColumn::make('title')->searchable()->label('Title'),
                TextColumn::make('providers_count')->label('Providers')
                    ->counts('Providers')->badge(),
                
            ])
            ->filters([
                //
            ])
            ->actions([
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
            ProvidersRelationManager::class, 
        ];
    }

     public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('parent', 0)->where('disable', 0)
           ->orderBy('id', 'desc');
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
            'view' => Pages\ViewActivity::route('/{record}'),

        ];
    }
     
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
