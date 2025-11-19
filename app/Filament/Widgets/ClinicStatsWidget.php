<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Clinic;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ClinicStatsWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        $totalClinics = Clinic::count();
        $activeClinics = Clinic::where('status', 1)->count();
        $inactiveClinics = Clinic::where('status', 0)->count();
        // Get recent clinics (since create_at is a string field with Persian dates)
        // For now, we'll get all clinics as we can't easily compare Persian dates
        $recentClinics = Clinic::whereNotNull('create_at')->count();

        return [
            Stat::make('Total Clinics', $totalClinics)
                ->description('All registered clinics')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('primary'),

            Stat::make('Active Clinics', $activeClinics)
                ->description('Currently active')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Inactive Clinics', $inactiveClinics)
                ->description('Currently inactive')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('Clinics with Date', $recentClinics)
                ->description('Clinics with creation date')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
        ];
    }
}
