<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\WbComment;
use App\Observers\CommentObserver;

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
    }
}
