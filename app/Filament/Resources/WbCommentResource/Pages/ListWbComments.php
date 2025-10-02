<?php

namespace App\Filament\Resources\WbCommentResource\Pages;

use App\Filament\Resources\WbCommentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWbComments extends ListRecords
{
    protected static string $resource = WbCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
