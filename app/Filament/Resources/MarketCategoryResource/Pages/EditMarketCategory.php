<?php

namespace App\Filament\Resources\MarketCategoryResource\Pages;

use App\Filament\Resources\MarketCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarketCategory extends EditRecord
{
    protected static string $resource = MarketCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
         $data['slug'] = str_replace(' ', '-', $data['name']);
      
        return $data;
    }  
}
