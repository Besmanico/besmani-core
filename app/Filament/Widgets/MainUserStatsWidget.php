<?php

namespace App\Filament\Widgets;

use App\Models\MainUser;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class MainUserStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $lastWeek = Carbon::now()->subWeek();
        $lastMonth = Carbon::now()->subMonth();

        return [
            Stat::make('Total Users', MainUser::count())
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart($this->getLast7DaysData()),

            Stat::make('Today\'s Registrations', MainUser::whereDate('created_at', $today)->count())
                ->description('New users today')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('This Week', MainUser::where('created_at', '>=', $lastWeek)->count())
                ->description('Last 7 days')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('This Month', MainUser::where('created_at', '>=', $lastMonth)->count())
                ->description('Last 30 days')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),
        ];
    }

    private function getLast7DaysData(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = MainUser::whereDate('created_at', $date->format('Y-m-d'))->count();
            $data[] = $count;
        }
        return $data;
    }
}
