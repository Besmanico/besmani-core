<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\ManService;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\WomenServiceCategory;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ManServiceResource\Pages;
use App\Filament\Resources\ManServiceResource\RelationManagers;

class ManServiceResource extends Resource
{
    protected static ?string $model = ManService::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'BEAUTY';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = "  Men's Salon  ";
    protected static ?string $modelLabel = "   Men's Salon  ";
    protected static ?string $pluralModelLabel = "  Men's Salon    ";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Man Service Information')
                ->schema([


                    Forms\Components\Select::make('category_id')
                        ->label('Select Man Service Category')
                        ->options(WomenServiceCategory::where('man',1)->pluck('title', 'id'))
                        ->searchable(),


                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->label('Title'),
                    FileUpload::make('img')
                        ->label('Image')
                        ->directory('man_services')
                        ->imageEditor()
                        ->downloadable()
                        ->optimize('webp')
                        ->helperText('Recommended size: 70*70 pixels')
                        ->image()
                        ->columnSpanFull(),

                  

                    Forms\Components\Textarea::make('description')
                        ->maxLength(1000)
                        ->label('Description'),

                 


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
                // ->getStateUsing(function ($record) {
                //     if (!$record || !$record->img) {
                //         return null; 
                //     }
                //     $beautyUrl = env('BEAUTY_URL', 'https://beauty.besmani.com');
                //     return $record->img ? $beautyUrl  . $record->img : null;
                //  }),  
                Tables\Columns\TextColumn::make('category.title')->label('Category')->badge()->color('info'),
                Tables\Columns\TextColumn::make('technical_code')->label('Technical Code'),
                Tables\Columns\ToggleColumn::make('status')->label('Status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'title')
                    ->label('Category'),
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
            'index' => Pages\ListManServices::route('/'),
            'create' => Pages\CreateManService::route('/create'),
            'edit' => Pages\EditManService::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
