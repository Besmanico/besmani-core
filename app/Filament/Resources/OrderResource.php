<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Cart;
use Filament\Tables;
use App\Models\Order;
use App\Models\Service;
use Filament\Forms\Get;
use App\Models\MainUser;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PackageService;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'BESMANI';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Orders';
    protected static ?string $pluralNavigationLabel = 'Orders';
    protected static ?string $pluralModelLabel = 'Orders';
    protected static ?string $modelLabel = 'Order';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Info')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->options(function () {
                             return   MainUser::select('id', 'fl_name', 'email', 'mobile')
                                    ->get()
                                    ->mapWithKeys(function ($user) {
                                        $displayName = $user->fl_name ?: ($user->email ?: ($user->mobile ?: "User #{$user->id}"));
                                        return [$user->id => $displayName];
                                    });
                            }) 
                            ->searchable()
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('service_id')
                            ->options(Service::all()->pluck('title', 'id'))
                            ->label('Service')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive(),
                        // when select service, show package services
                        Forms\Components\Select::make('package_service_id')
                            ->label('Package Service')
                            ->options(function (Get $get) {
                                $serviceId = $get('service_id');
                                if (!$serviceId) {
                                    return [];
                                }
                                return PackageService::where('service_id', $serviceId)->pluck('title', 'id')->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->disabled(fn(Get $get) => blank($get('service_id'))),


                        // Forms\Components\TextInput::make('tracking_code')
                        //     ->required()
                        //     ->maxLength(255),
                        Forms\Components\TextInput::make('total_payment')
                            ->required()
                            ->numeric()
                            ->default(0.00),
                        Forms\Components\Toggle::make('cuote')


                            ->default(0)
                            ->label('Cuote'),
                        Forms\Components\Toggle::make('invoice')
                            ->default(0)
                            ->label('Invoice'),
                        // Forms\Components\Toggle::make('is_admin'),
                        // Forms\Components\Toggle::make('status'),

                    ])->collapsible(false)->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.fl_name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tracking_code')->badge()->color('danger')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_payment')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax_fee')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount')
                    ->money()
                    ->sortable(),
                    Tables\Columns\ToggleColumn::make('status')->label('Start'), 

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                   ->badge(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make('Pay Order'),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()->where('status', 1);
    // }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
