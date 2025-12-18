<?php

namespace App\Filament\Resources\CustomerApiResource\Pages;

use App\Filament\Resources\CustomerApiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerApi extends CreateRecord
{
    protected static string $resource = CustomerApiResource::class;
}
