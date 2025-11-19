<?php

namespace App\Filament\Widgets;

use App\Models\MainUser;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class MainUserRegistrationChart extends ChartWidget
{
    protected static ?string $heading = 'MainUser Registrations (Last 7 Days)';
    
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        
        // Get data for the last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = MainUser::whereDate('created_at', $date->format('Y-m-d'))->count();
            
            $data[] = $count;
            $labels[] = $date->format('M d');
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Registrations',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.1)',
                        'rgba(16, 185, 129, 0.1)',
                        'rgba(245, 158, 11, 0.1)',
                        'rgba(239, 68, 68, 0.1)',
                        'rgba(139, 92, 246, 0.1)',
                        'rgba(236, 72, 153, 0.1)',
                        'rgba(6, 182, 212, 0.1)',
                    ],
                    'borderColor' => [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(139, 92, 246, 1)',
                        'rgba(236, 72, 153, 1)',
                        'rgba(6, 182, 212, 1)',
                    ],
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'enabled' => true,
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}
