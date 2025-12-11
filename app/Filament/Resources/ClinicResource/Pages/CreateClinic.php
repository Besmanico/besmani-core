<?php

namespace App\Filament\Resources\ClinicResource\Pages;

use App\Filament\Resources\ClinicResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClinic extends CreateRecord
{
    protected static string $resource = ClinicResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {

        //    slug generate from title
        $data['slug'] = str_replace(' ', '-', $data['title']);

        return $data;
    }
}
