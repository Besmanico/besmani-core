<?php

namespace App\Filament\Resources\CategoryWebDesignResource\RelationManagers;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Resources\RelationManagers\RelationManager;

class CategoryWebDesignItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'categoryWebDesignItems';
    protected static ?string $inverseRelationship = 'categoryWebDesign';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Item details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('price_item')
                            ->label('Price Item')
                            ->numeric()
                            ->prefix('$')
                            ->columnSpanFull(),
                        TextInput::make('delivery_time')
                            ->label('Delivery Time')
                            ->columnSpanFull(),
                        TextInput::make('link')
                            ->label('Link')
                            ->columnSpanFull(),
                    ]),
                Hidden::make('user_id')
                    ->default(fn (): ?int => Auth::id())
                    ->dehydrated(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('price_item')
                    ->label('Price Item')
                    ->money('usd', true)
                    ->summarize(
                        Sum::make()
                            ->label('Total')
                            ->formatStateUsing(fn ($state): string => '$' . number_format($state, 2))
                    ),
                TextColumn::make('delivery_time')
                    ->label('Delivery Time')
                    ->wrap(),
                TextColumn::make('link')
                    ->label('Link')
                    ->wrap(),
                TextColumn::make('user.name')
                    ->label('Created by')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Created at')
                    ->dateTime()
                    ->since(),
                    
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
