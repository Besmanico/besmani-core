<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\MainUser;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CustomerApi;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CustomerApiResource\Pages;
use App\Filament\Resources\CustomerApiResource\RelationManagers;

class CustomerApiResource extends Resource
{
    protected static ?string $model = CustomerApi::class;


    protected static ?string $navigationIcon = 'antdesign-api';
    protected static ?string $navigationGroup = 'Public';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('info')
            ->schema([ 

                Forms\Components\Select::make('user_id') 
                ->options(MainUser::all()->pluck('fl_name', 'id'))->searchable()->required(),
              

            Forms\Components\TextInput::make('api_url')->label('API URL')->columnSpanFull() 
            ->placeholder('https://api.example.com')->required(),

            
            
            
          
          
            ])->collapsed(false)->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListCustomerApis::route('/'),
            'create' => Pages\CreateCustomerApi::route('/create'),
            'edit' => Pages\EditCustomerApi::route('/{record}/edit'),
        ];
    }
}
