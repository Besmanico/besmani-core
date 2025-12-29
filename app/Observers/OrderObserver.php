<?php

namespace App\Observers;

use App\Models\Order;
use App\Http\Controllers\CartController;
use Filament\Notifications\Notification;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //  $this->sendNewOrderNotification($order, $order->tracking_code);
        //  Notification::make()->title('New Order Received')->body('New order #' . $order->tracking_code . ' from ' . $order->user->name . '. Total: $' . $order->total_payment)->send();
   

        Notification::make()
        ->title('New Order Received')
        ->body('New order #' . $order->tracking_code . ' from ' . $order->user->name . '. Total: $' . $order->total_payment)
        ->success()
        ->icon('heroicon-o-shopping-cart')
        // ->url(\App\Filament\Resources\OrderResource::getUrl('edit', ['record' => $order->id])) 
        
        ->sendToDatabase(auth()->user());
    
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
