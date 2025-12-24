<?php

namespace App\Filament\Resources\WomanTrainingResource\Pages;

use App\Filament\Resources\WomanTrainingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWomanTrainings extends ListRecords
{
    protected static string $resource = WomanTrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
