<?php

namespace App\Filament\Resources\ManTrainingResource\Pages;

use App\Filament\Resources\ManTrainingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManTraining extends EditRecord
{
    protected static string $resource = ManTrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
