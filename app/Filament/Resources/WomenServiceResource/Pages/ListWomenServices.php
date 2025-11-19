<?php

namespace App\Filament\Resources\WomenServiceResource\Pages;

use App\Filament\Resources\WomenServiceResource;
use App\Filament\Widgets\WomenSalonServiceStatsWidget;
use Filament\Resources\Pages\ListRecords;

class ListWomenServices extends ListRecords
{
    protected static string $resource = WomenServiceResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            WomenSalonServiceStatsWidget::class,
        ];
    }
}
