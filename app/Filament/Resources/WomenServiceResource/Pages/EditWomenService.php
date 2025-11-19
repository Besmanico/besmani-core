<?php

namespace App\Filament\Resources\WomenServiceResource\Pages;

use App\Filament\Resources\WomenServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWomenService extends EditRecord
{
    protected static string $resource = WomenServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
