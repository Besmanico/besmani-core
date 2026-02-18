<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Slider;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SliderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SliderResource\RelationManagers;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'BESMANI';
    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Info') 
                    ->schema([
                        FileUpload::make('image')->downloadable()->directory('Slider')->optimize('webp')->helperText('185*150')->required(),

                        TextInput::make('link'),
                        TextInput::make('hom_page_sort')->label('Home Page No.')->required(),
                        TextInput::make('page_src_sort')->label('  Technology No.')->required(),
                        Toggle::make('home_page')->label('home page'),
                        Toggle::make('page_src')->label('Technology'),

                        Toggle::make('status')->label('publish'),
                        Hidden::make('user_id')->default(fn () => auth()->id() ?? 0)->dehydrated(true),
 
                    ])->collapsed(false)->columns(2),
            ]);  
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('Image')->circular(),
                TextColumn::make('link')->label('Link'),
                TextColumn::make('hom_page_sort')->label('No 1'),

                ToggleColumn::make('home_page')->label('Home Page'),
                TextColumn::make('page_src_sort')->label('No 2'),

                ToggleColumn::make('page_src')->label('Technology'),
                ToggleColumn::make('status')->label('Status'),
                TextColumn::make('created_at')->sortable()->badge(),


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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
