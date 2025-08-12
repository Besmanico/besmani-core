<?php

namespace App\Filament\Widgets;

use App\Models\MainUser;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('TRAVEL', MainUser::where('child','travel')->count())
            ->description(' travel users  ')
            ->descriptionIcon('heroicon-m-arrow-trending-down')
            ->color('success')
            ->url('https://easyreasy.com/admin')
            ->openUrlInNewTab()
             
            ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
            Stat::make('BEAUTY', MainUser::where('child','travel')->count())
            ->description('beauty users  ')
            ->descriptionIcon('heroicon-m-arrow-trending-down')
            ->color('danger')
            ->url('https://beauty.en.easyreasy.com/cp_admin')
            ->openUrlInNewTab()
            ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
            Stat::make('PETSHOP', MainUser::where('child','travel')->count())
            ->description(' users  ')
            ->descriptionIcon('heroicon-m-arrow-trending-down')
            ->color('warning')
            ->url('/admin')
            ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
        ];
    }
}
