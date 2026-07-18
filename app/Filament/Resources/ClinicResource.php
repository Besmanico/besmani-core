<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Clinic;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ClinicCategory;
use Filament\Resources\Resource;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Widgets\ClinicStatsWidget;
use App\Filament\Resources\ClinicResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClinicResource\RelationManagers;

class ClinicResource extends Resource
{
    protected static ?string $model = Clinic::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'BEAUTY';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = "   Clinic  ";
    protected static ?string $modelLabel = "   Clinic  ";
    protected static ?string $pluralModelLabel = "  Clinic    ";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Clinic Information')
                    ->schema([


                        Forms\Components\Select::make('category_id')
                            ->label('Select Clinic Category')
                            ->options(ClinicCategory::all()->pluck('title', 'id'))
                            ->searchable(),


                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->label('Title'),
                     
                            FileUpload::make('img')
    ->label('Image')
    ->disk('public')
    ->directory('clinics')
    ->visibility('public')
    ->imageEditor()
    ->downloadable()
    ->optimize('webp')
    ->helperText('Recommended size: 70*70 pixels')
    ->image()
    ->columnSpanFull(),


                        // Forms\Components\TextInput::make('slug')
                        //     ->required()
                        //     ->maxLength(255)
                        //     ->label('Slug'),

                        // Forms\Components\TextInput::make('img')
                        //     ->maxLength(255)
                        //     ->label('Image URL'),

                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                            ->label('Description'),

                        // Forms\Components\Textarea::make('meta')
                        //     ->label('Meta Description'),

                        // Forms\Components\Textarea::make('keywords')
                        //     ->label('Keywords'),



                        Forms\Components\Textarea::make('meta')
                            ->label('Meta Description'),
                        Forms\Components\TagsInput::make('keywords')
                            ->label('Meta Keywords'),


                        Forms\Components\Toggle::make('status')
                            ->label('Status'),

                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->columns([
                Tables\Columns\TextColumn::make(name: 'title')->searchable()->sortable(),
                Tables\Columns\ImageColumn::make('img')
                    ->label('Image')
                    ->circular(), 
                Tables\Columns\TextColumn::make('category.title')->label('Category')->badge()->color('info'),
                Tables\Columns\TextColumn::make('technical_code')->label('Technical Code'),
                Tables\Columns\IconColumn::make('status')->label('Status')->boolean(),
                Tables\Columns\TextColumn::make('create_at')
                    ->label('Created At')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
            ]) 
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ])
                    ->label('Status'),
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'title')
                    ->label('Category'),
            ])
            ->actions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
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

    public static function getWidgets(): array
    {
        return [
            ClinicStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClinics::route('/'),
            'create' => Pages\CreateClinic::route('/create'),
            'edit' => Pages\EditClinic::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('id', 'desc');
    }
}
