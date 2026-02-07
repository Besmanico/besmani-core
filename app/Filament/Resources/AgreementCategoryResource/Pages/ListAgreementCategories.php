<?php

namespace App\Filament\Resources\AgreementCategoryResource\Pages;

use App\Filament\Resources\AgreementCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgreementCategories extends ListRecords
{
    protected static string $resource = AgreementCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
