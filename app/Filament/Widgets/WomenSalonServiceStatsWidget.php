<?php

namespace App\Filament\Widgets;

use App\Models\WomenService;
use Filament\Widgets\StatsOverviewWidget\Stat; 
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class WomenSalonServiceStatsWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        $totalWomenSalonServices = WomenService::count();
        $activeWomenSalonServices = WomenService::where('status', 1)->count();
        $inactiveWomenSalonServices = WomenService::where('status', 0)->count();
        // Get women salon services with creation date (since create_at is a string field with Persian dates)
        $recentWomenSalonServices = WomenService::whereNotNull('create_at')->count();

        return [
            Stat::make('Total Women Salon Services', $totalWomenSalonServices)
                ->description('All registered women salon services')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('primary'),

            Stat::make('Active Women Salon Services', $activeWomenSalonServices)
                ->description('Currently active')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Inactive Women Salon Services', $inactiveWomenSalonServices)
                ->description('Currently inactive')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('Services with Date', $recentWomenSalonServices)
                ->description('Services with creation date')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
        ];
    }
}