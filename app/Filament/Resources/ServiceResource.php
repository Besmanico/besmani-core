<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Service;
use Filament\Forms\Form;
use App\Models\OrderItem;
use Filament\Tables\Table;
use App\Models\PackageItem;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\ServiceResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Filament\Resources\ServiceResource\RelationManagers\CategoryWebDesignsRelationManager;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'BESMANI';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Services';
    protected static ?string $pluralNavigationLabel = 'Services';
    protected static ?string $pluralModelLabel = 'Services';
    protected static ?string $modelLabel = 'Service';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Info')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Section::make('Service Information')
                                ->description('Enter the basic information about the service')
                                ->schema([
                                    TextInput::make('title')
                                        ->required()
                                        ->label('Name')
                                        ->maxLength(255)
                                        ->columnSpanFull(),
                                    FileUpload::make('image')
                                        ->label('Image')
                                        ->directory('services')
                                        ->optimize('webp')
                                        ->helperText('Recommended size: 2000x2000 pixels')
                                        ->image()
                                        ->columnSpanFull(),
                                    Textarea::make('body')
                                        ->label('Description')
                                        ->rows(10)
                                        ->columnSpanFull(),
                                ])
                                ->columns(2),
                        ]),
                    Wizard\Step::make('Package Items')
                        ->icon('heroicon-o-list-bullet')
                        ->schema([
                            Section::make('Package Items')
                                ->description('Add order items that will be available for this service')
                              
                                ->schema([
                                    Repeater::make('packageItems')
                                        ->relationship('packageItems')
                                        ->label('Package Items')
                                        ->grid([
                                            'sm' => 1,
                                            'md' => 3,
                                        ])
                                        ->schema([
                                            Select::make('order_item_id')
                                                ->label('Select Order Item')
                                                ->options(OrderItem::all()->pluck('name', 'id'))
                                                ->searchable()
                                                ->required(),
                                        ])
                                        ->addActionLabel('Add package item')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    Wizard\Step::make('Package Services')
                        ->icon('heroicon-o-squares-2x2')
                        ->schema([
                            Section::make('Package Services')
                                ->description('Configure service packages with pricing and included items')
                                ->schema([
                                        
                                    Repeater::make('packageServices')
                                    
                                        ->relationship('packageServices')
                                        ->label('Package Services')
                                        ->grid([
                                            'sm' => 1,
                                            'md' => 3,
                                        ])
                                        ->schema([
                                            
                                            TextInput::make('title')
                                                ->label('Title')
                                                ->required()
                                                ->maxLength(255),
                                            TextInput::make('price')
                                                ->label('Price')
                                                ->numeric()
                                                ->prefix('$'),
                                            Repeater::make('packageServiceItems')
                                            
                                                ->relationship('packageServiceItems')
                                                ->extraAttributes([
                                                    'style' => 'border: 2px solid green; border-radius: 8px; padding: 1.5rem;'
                                                ])
                                                ->label('Package Items')
                                                
                                                ->schema([
                                                    Select::make('name')
                                                        ->label('Select Package Item')
                                                        ->options(function ($livewire) {
                                                            $serviceId = $livewire->record->id ?? null;
                                                            if (!$serviceId) {
                                                                return [];
                                                            }
                                                            // order_item_id send with orderItem id send
                                                            $packageItems = PackageItem::with('orderItem')->where('service_id', $serviceId)->get();
                                                            return $packageItems->mapWithKeys(function ($item) {
                                                                $orderItemName = $item->orderItem ? $item->orderItem->name : 'N/A';
                                                                return [$item->id => $orderItemName . ' - ' . ($item->name ?? '')];
                                                            })->toArray();
                                                        })
                                                        ->searchable()
                                                        // ->dehydrated(false)
                                                        ->live()
                                                        ->afterStateUpdated(function ($state, $set, $get) {
                                                            if ($state) {
                                                                $packageItem = PackageItem::with('orderItem')->find($state);
                                                                if ($packageItem && $packageItem->orderItem) {
                                                                    $quantity = $get('quantity') ?? 1;
                                                                    $name = $packageItem->orderItem->name . ($quantity > 1 ? ' (Qty: ' . $quantity . ')' : '');
                                                                    $oii = $packageItem->orderItem->id;
                                                                    $set('name', $name);
                                                                    $set('orderitem_id', $oii);  

                                                                }

                                                               

                                                            }
                                                        }),
                                                        Hidden::make('name')
                                                        ,
                                                        TextInput::make('orderitem_id')
                                                        ,
                                                    TextInput::make('quantity')
                                                        ->label('Quantity')
                                                        ->numeric()
                                                        ->default(1)
                                                     
                                                        ->live()
                                                        ->afterStateUpdated(function ($state, $set, $get) {
                                                            $packageItemId = $get('package_item_select');
                                                            if ($packageItemId && $state) {
                                                                $packageItem = PackageItem::with('orderItem')->find($packageItemId);
                                                                if ($packageItem && $packageItem->orderItem) {
                                                                    $name = $packageItem->orderItem->name . ($state > 1 ? ' (Qty: ' . $state . ')' : '');
                                                                    $set('name', $name);
                                                                }
                                                            }
                                                        }),
                                                    // TextInput::make('name')
                                                    //     ->label('Name')
                                                    //     ->required()
                                                    //     ->hidden()
                                                    //     ->dehydrated(),
                                                ])
                                                ->addActionLabel('Add Package Item')
                                                ->defaultItems(0)
                                                 ->collapsible()
                                                // ->collapsed()
                                                
                                                // ->itemLabel(fn(array $state): ?string => $state['name'] ?? 'New Package Item')
                                                ->columnSpanFull(),
                                        ])
                                        ->addActionLabel('Add package service')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                ])
                ->skippable()
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Name'),
                ImageColumn::make('image')->label('Image')->circular(),
                TextColumn::make('body')->label('Body')->limit(50),
                ToggleColumn::make('status')->label('Status'),

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
            CategoryWebDesignsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
