<?php

namespace App\Filament\Resources\WomanTrainingResource\Pages;

use App\Filament\Resources\WomanTrainingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWomanTraining extends EditRecord
{
    protected static string $resource = WomanTrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
