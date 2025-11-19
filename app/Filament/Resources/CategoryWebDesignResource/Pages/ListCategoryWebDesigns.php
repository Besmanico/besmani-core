<?php

namespace App\Filament\Resources\CategoryWebDesignResource\Pages;

use App\Filament\Resources\CategoryWebDesignResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoryWebDesigns extends ListRecords
{
    protected static string $resource = CategoryWebDesignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
