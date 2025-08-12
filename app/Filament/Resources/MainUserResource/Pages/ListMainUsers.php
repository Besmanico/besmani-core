<?php

namespace App\Filament\Resources\MainUserResource\Pages;

use Filament\Actions;
use App\Models\MainUser;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\MainUserResource;
use Illuminate\Database\Eloquent\Builder;

class ListMainUsers extends ListRecords
{
    protected static string $resource = MainUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(MainUser::count()),
            'all' => Tab::make('all')->label('All'),

            'user' => Tab::make('user')->label('User')
                ->badge(MainUser::query()->where('service_pr', 0)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('service_pr', 0)),

            'provider' => Tab::make('provider')->label('Provider')
                ->badge(MainUser::query()->where('service_pr', 1)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('service_pr', 1)),

            'pending' => Tab::make('pending')->label('Pending')
                ->badge(MainUser::query()->where('service_pr', 1)->where('approved', 0)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('service_pr', 1)->where('approved', 0)),

        ];
    }
}
