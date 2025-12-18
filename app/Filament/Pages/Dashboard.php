<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('new_user')
                ->label('New User')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->url(fn () => 'https://beauty.besmani.com/index/data_entry/' . Auth::id(), shouldOpenInNewTab: true),
        ]; 
    }
}


