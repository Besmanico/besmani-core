<?php

namespace App\Filament\Resources\BeBlogResource\Pages;

use App\Filament\Resources\BeBlogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBeBlog extends EditRecord
{
    protected static string $resource = BeBlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
