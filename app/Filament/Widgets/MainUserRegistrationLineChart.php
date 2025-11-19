<?php

namespace App\Filament\Widgets;

use App\Models\MainUser;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class MainUserRegistrationLineChart extends ChartWidget
{
    protected static ?string $heading = 'MainUser Registration Trend (Last 7 Days)';
    
    protected static ?int $sort = 3;

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
                    'label' => 'Daily Registrations',
                    'data' => $data,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => 'rgb(59, 130, 246)',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 6,
                    'pointHoverRadius' => 8,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
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
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'titleColor' => '#ffffff',
                    'bodyColor' => '#ffffff',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 1,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                        'color' => '#6b7280',
                    ],
                    'grid' => [
                        'color' => 'rgba(107, 114, 128, 0.1)',
                    ],
                ],
                'x' => [
                    'ticks' => [
                        'color' => '#6b7280',
                    ],
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'elements' => [
                'point' => [
                    'hoverBackgroundColor' => 'rgb(59, 130, 246)',
                ],
            ],
        ];
    }
}
