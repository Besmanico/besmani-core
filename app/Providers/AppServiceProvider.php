<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\WbComment;
use App\Models\Notification;
use App\Observers\OrderObserver;
use App\Observers\CommentObserver;
use App\Observers\NotificationObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */ 
    public function boot(): void 
    {
        
        WbComment::observe(CommentObserver::class);
        Order::observe(OrderObserver::class);
     }
}
