<?php

namespace App\Filament\Resources\PhoneCountryResource\Pages;

use App\Filament\Resources\PhoneCountryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPhoneCountry extends EditRecord
{
    protected static string $resource = PhoneCountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
