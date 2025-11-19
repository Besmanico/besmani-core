<?php

namespace App\Filament\Resources\CategoryWebDesignResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CategoryWebDesignResource;

class EditCategoryWebDesign extends EditRecord
{
    protected static string $resource = CategoryWebDesignResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['slug'] = Str::slug($data['name']);
        $data['user_id'] = Auth::user()->id;
        return $data;
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
