<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Province;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProvinceResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProvinceResource\RelationManagers;
use App\Filament\Resources\ProvinceResource\RelationManagers\CitiesRelationManager;

class ProvinceResource extends Resource
{
    protected static ?string $model = Province::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Public';
    protected static ?string $navigationLabel = "  State/Province  ";
    protected static ?string $modelLabel = "   State/Province  ";
    protected static ?string $pluralModelLabel = "  State/Province    ";
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('info')
                ->schema([ 

                Forms\Components\TextInput::make('name_en')
                    
                ->maxLength(191),
                Forms\Components\TextInput::make('name_fa')
                ->required()
                ->maxLength(191),
                Toggle::make('status')->label('publish'),
                Hidden::make('user_id')->default(Auth::user()->id)->dehydrated(true),

                ])->collapsed(false)->columns(2),
            ]);
    } 

    public static function table(Table $table): Table
    {
        return $table
        ->defaultPaginationPageOption('50')
        ->paginated([10, 25, 50, 100])
            ->columns([
                // Tables\Columns\TextColumn::make('id')
                // ->sortable(),
                Tables\Columns\TextColumn::make('name_en')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name_fa')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('cities_count')->counts('cities')->label('city')->badge()->color('danger')->sortable(),
                Tables\Columns\TextColumn::make('user.email')->label('user')->sortable(),

                ToggleColumn::make('status')->label('publish')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('created_at')->sortable()->badge(),

            ])
            ->filters([
                // SelectFilter::make('status')
                // ->options([
                //     'draft' => 'Draft',
                //     'reviewing' => 'Reviewing',
                //     'published' => 'Published',
                // ]),
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
            CitiesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProvinces::route('/'),
            'create' => Pages\CreateProvince::route('/create'),
            'edit' => Pages\EditProvince::route('/{record}/edit'),
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
