<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\manTraining;
use App\Models\TrainingCategory;
use Filament\Resources\Resource;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ManTrainingResource\Pages;
use App\Filament\Resources\ManTrainingResource\RelationManagers;

class ManTrainingResource extends Resource
{
    protected static ?string $model = manTraining::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'BEAUTY';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationLabel = "  Men Training  ";
    protected static ?string $modelLabel = "   Men Training  ";
    protected static ?string $pluralModelLabel = "  Men Training    ";
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Men Training Information')
                ->schema([


                    Forms\Components\Select::make('category_id')
                        ->label('Select Men Training Category')
                        ->options(TrainingCategory::all()->pluck('title', 'id'))
                        ->searchable(),


                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->label('Title'),
                    FileUpload::make('img')
                        ->label('Image')
                        ->required()
                        ->directory('man_trainings')
                        ->imageEditor()
                        ->downloadable()
                        ->optimize('webp')
                        ->helperText('Recommended size: 70*70 pixels')
                        ->image()
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('description')
                        
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
            ->columns([
                Tables\Columns\TextColumn::make(name: 'title')->searchable()->sortable(),
                Tables\Columns\ImageColumn::make('img') 
                ->label('Image')
                ->circular(),
                Tables\Columns\TextColumn::make('category.title')->label('Category')->badge()->color('info'),
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
            'index' => Pages\ListManTrainings::route('/'),
            'create' => Pages\CreateManTraining::route('/create'),
            'edit' => Pages\EditManTraining::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
   
}
