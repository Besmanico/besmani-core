<?php

namespace App\Observers;

use App\Models\WbComment;
use Illuminate\Notifications\DatabaseNotification as DatabaseNotificationModel;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class CommentObserver
{
    /**
     * Handle the WbComment "created" event.
     */
    public function created(WbComment $wbComment): void
    {
        // Test if observer is working at all
       
    }

    /**
     * Handle the WbComment "updated" event.
     */
    public function updated(WbComment $wbComment): void
    {
        //
    }

    /**
     * Handle the WbComment "deleted" event.
     */
    public function deleted(WbComment $wbComment): void
    {
        //
    }

    /**
     * Handle the WbComment "restored" event.
     */
    public function restored(WbComment $wbComment): void
    {
        //
    }

    /**
     * Handle the WbComment "force deleted" event.
     */
    public function forceDeleted(WbComment $wbComment): void
    {
        //
    }
}
