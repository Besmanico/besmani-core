<?php

namespace App\Filament\Resources\ManServiceResource\Pages;

use App\Filament\Resources\ManServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManServices extends ListRecords
{
    protected static string $resource = ManServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
