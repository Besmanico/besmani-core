<?php

namespace App\Filament\Resources\CategoryWebDesignResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CategoryWebDesignResource;

class CreateCategoryWebDesign extends CreateRecord
{
    protected static string $resource = CategoryWebDesignResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = Str::slug($data['name']);
        $data['user_id'] = Auth::user()->id;
        return $data;
    }
}
