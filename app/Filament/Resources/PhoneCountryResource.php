<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PhoneCountry;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PhoneCountryResource\Pages;
use App\Filament\Resources\PhoneCountryResource\RelationManagers;
use App\Filament\Resources\ProvinceResource\RelationManagers\CitiesRelationManager;
use App\Filament\Resources\PhoneCountryResource\RelationManagers\ProvincesRelationManager;
   use Filament\Tables\Columns\ToggleColumn;
   
   use Illuminate\Support\HtmlString;

use function Filament\Support\is_app_url;

class PhoneCountryResource extends Resource
{

    public static $APP_URL = 'https://besmani.com/';
    protected static ?string $model = PhoneCountry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
     protected static ?string $navigationGroup = 'Public';
    protected static ?string $navigationLabel = "  Country  ";
    protected static ?string $modelLabel = "   Country  ";
    protected static ?string $pluralModelLabel = "  Country    ";
    protected static ?int $navigationSort = 2;


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
                Forms\Components\TextInput::make('code')
                ->required()
                 , 
                 Forms\Components\TextInput::make('flag')->label('flag'),
                //  ->directory('assets/images/flags')->optimize('webp')->helperText('  100*50 - Optimize image and convert to webp format'),
           
                Toggle::make('status')->label('publish'),
                Hidden::make('user_id')->default(Auth::user()->id)->dehydrated(true),

                ])->collapsed(false)->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->defaultPaginationPageOption('all')
            ->columns([
                TextColumn::make('name_en')->label('country en')->searchable(),
                TextColumn::make('name_fa')->label('country fa')->searchable(),
                TextColumn::make('code')->label('code')->searchable(),
                TextColumn::make('flag')->label('flag')
                ->formatStateUsing(fn (string $state) => new HtmlString('<img class="w-10" src="' .self::$APP_URL . ''.$state.'"  />')) 
                ->html(),
                TextColumn::make('province_count')->counts('province')->label('state/province')->badge()->color('danger'),
                TextColumn::make('user.email')->label('user'),
                ToggleColumn::make('status')->label('publish'),

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
            ProvincesRelationManager::class,
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPhoneCountries::route('/'),
            'create' => Pages\CreatePhoneCountry::route('/create'),
            'edit' => Pages\EditPhoneCountry::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
