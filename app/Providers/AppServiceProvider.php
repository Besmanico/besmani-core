<?php

namespace App\Providers;

use App\Contracts\Notifications\NotificationProvider;
use App\Models\MainUser;
use App\Models\Order;
use App\Models\WbComment;
use App\Observers\CommentObserver;
use App\Observers\OrderObserver;
use App\Observers\ReferralInvitationAcceptanceObserver;
use App\Services\Notifications\CustomerIoNotificationProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(NotificationProvider::class, CustomerIoNotificationProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        WbComment::observe(CommentObserver::class);
        Order::observe(OrderObserver::class);
        MainUser::observe(ReferralInvitationAcceptanceObserver::class);
    }
}
