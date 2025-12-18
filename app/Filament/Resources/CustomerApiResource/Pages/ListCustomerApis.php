<?php

namespace App\Filament\Resources\CustomerApiResource\Pages;

use App\Filament\Resources\CustomerApiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomerApis extends ListRecords
{
    protected static string $resource = CustomerApiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
