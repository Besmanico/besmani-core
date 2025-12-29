<?php

namespace App\Filament\Resources\ProductCommentResource\Pages;

use App\Filament\Resources\ProductCommentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductComments extends ListRecords
{
    protected static string $resource = ProductCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
