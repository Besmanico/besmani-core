<?php

namespace App\Filament\Resources\ServiceResource\RelationManagers;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\CategoryWebDesignResource\RelationManagers\CategoryWebDesignItemsRelationManager;

class CategoryWebDesignsRelationManager extends RelationManager
{
    protected static string $relationship = 'categoryWebDesigns';
    protected static ?string $inverseRelationship = 'service';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Info')
                
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Name')
                            ->maxLength(255),
                        TextInput::make('price')
                            ->numeric()
                            ->label('Base Price')
                            ->prefix('$'),
                        TextInput::make('delivery')
                            ->label('Delivery')
                            ->placeholder('e.g. 7 business days'),
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsed(false)
                    ->columns(2),

                Section::make('Category web design items')
                    ->description('Define the deliverables that belong to this web design package.')
                    ->schema([
                        Repeater::make('categoryWebDesignItems')
                            ->relationship('categoryWebDesignItems')
                            ->label('Items')
                            ->collapsed()
                            ->grid([
                                'sm' => 1,
                                'md' => 2,
                            ])
                            ->schema([
                                TextInput::make('title')
                                    ->label('Title')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('price_item')
                                    ->label('Price Item')
                                    ->numeric()
                                    ->prefix('$')->default(0),
                                Hidden::make('user_id')->default(Auth::user()->id)->dehydrated(true),
                                TextInput::make('delivery_time')
                                    ->label('Delivery Time'),
                                TextInput::make('link')
                                    ->label('Link')
                                    ->url()
                                    ->columnSpanFull(),
                            ])
                            ->addActionLabel('Add item')
                            ->columnSpanFull(),
                     ])
                    ->collapsible()
                    ->collapsed(),
                // Web Design Gallery
                Section::make('Web Design Gallery')
                    ->label('Web Design Gallery')
                    ->description('Showcase previous work or references related to this web design package.')
                    ->schema([
                        Repeater::make('portfolioService')
                            ->relationship('portfolioService')
                            ->label('Gallery entries')

                            ->schema([
                                TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255),
                                Repeater::make('portfolioServiceLinks')
                                    ->relationship('portfolioServiceLinks')
                                    ->label('Links')
                                    ->collapsed(false)
                                    ->schema([
                                        TextInput::make('link')
                                            ->label('Link')
                                            ->url()
                                            ->placeholder('https://'),
                                    ])
                                    ->addActionLabel('Add link')
                                    ->columnSpanFull(),
                            ])
                            ->grid([
                                'md' => 2,
                            ])
                            ->addActionLabel('Add portfolio entry'),
                    ])
                    ->collapsible()
                    ->collapsed()
                    ->columns(1),
                    // Client Projects
                    Section::make('Client Projects')
                    ->label('Client Projects')
                    ->description('Showcase previous work or references related to this web design package.')
                    ->schema([
                        Repeater::make('clientProjects')
                            ->relationship('clientProjects')
                            ->label('Projects')
                            ->collapsed(false)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('link')
                                    ->label('Link')
                                    ->url()
                                    ->placeholder('https://'),
                                FileUpload::make('image')
                                    ->label('Image')
                                    ->directory('client-projects')
                                    ->optimize('webp')
                                     
                                    ->helperText('1000*1000'),
                            ])
                            ->addActionLabel('Add project')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed()
                    ->columns(1),
  
            ]);
            
    }

    public static function getRelations(): array
    {
        return [
            CategoryWebDesignItemsRelationManager::class,
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
             ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('delivery'),
                Tables\Columns\TextColumn::make('description'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\Action::make('items')
                //     ->label('Items')
                //     ->icon('heroicon-m-queue-list') 
                //     ->color('gray')
                //     ->url(fn ($record) => CategoryWebDesignResource::getUrl('edit', ['record' => $record]))
                //     ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
