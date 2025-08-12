<?php

namespace App\Filament\Resources\PhoneCountryResource\Pages;

use App\Filament\Resources\PhoneCountryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPhoneCountries extends ListRecords
{
    protected static string $resource = PhoneCountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
