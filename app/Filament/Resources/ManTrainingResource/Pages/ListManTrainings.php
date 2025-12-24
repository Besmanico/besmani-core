<?php

namespace App\Filament\Resources\ManTrainingResource\Pages;

use App\Filament\Resources\ManTrainingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManTrainings extends ListRecords
{
    protected static string $resource = ManTrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
