<?php

namespace App\Filament\Resources\MarketCategoryResource\Pages;

use App\Filament\Resources\MarketCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMarketCategory extends CreateRecord
{
    protected static string $resource = MarketCategoryResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
         $data['slug'] = str_replace(' ', '-', $data['name']);
      
        return $data;
    }  
}
