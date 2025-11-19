<?php

namespace App\Filament\Resources\BeBlogResource\Pages;

use App\Filament\Resources\BeBlogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBeBlogs extends ListRecords
{
    protected static string $resource = BeBlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
