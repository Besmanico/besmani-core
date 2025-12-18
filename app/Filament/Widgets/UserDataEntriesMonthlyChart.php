<?php

namespace App\Filament\Widgets;

use App\Models\DataEntry;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserDataEntriesMonthlyChart extends ChartWidget
{
    protected static ?string $heading = 'User Data Entries by Phone';
    
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        // Get users with their data entries count, ordered by count descending
        $userDataEntries = User::withCount('data_entries')
            ->whereHas('data_entries')
            ->orderBy('data_entries_count', 'desc')
            ->limit(20) // Show top 20 users
            ->get();

        $data = [];
        $labels = [];
        
        foreach ($userDataEntries as $user) {
            $labels[] = $user->phone ?? 'N/A';
            $data[] = $user->data_entries_count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Data Entries Count',
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
                    'ticks' => [
                        'maxRotation' => 45,
                        'minRotation' => 45,
                    ],
                ],
            ],
        ];
    }
}

