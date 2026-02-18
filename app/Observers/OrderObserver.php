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
   

        // Notification::make()
        // ->title('New Order Received')
        // ->body('New order #' . $order->tracking_code . ' from ' . $order->user->name . '. Total: $' . $order->total_payment)
        // ->success()
        // ->icon('heroicon-o-shopping-cart')
        // // ->url(\App\Filament\Resources\OrderResource::getUrl('edit', ['record' => $order->id])) 
        
        // ->sendToDatabase(auth()->user());
    
    }

    /** Progress: only replace with fixed value, no add/subtract. */
    private const STATUS_PROGRESS = [
        'Starting' => 10,
        'processing' => 50,
        'Processing' => 50,
        'Finalizing' => 80,
        'Done' => 100, 
    ];

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // When "Start" (status) is toggled ON: replace order_status and progress
        if ($order->wasChanged('status') && $order->status) {
            Order::withoutEvents(function () use ($order) {
                $order->updateQuietly([
                    'order_status' => 'Starting',
                    'progress' => 10,
                ]);
            }); 
            return;
        }

        // When "Start" (status) is toggled OFF: replace with Pending and 0
        if ($order->wasChanged('status') && ! $order->status) {
            Order::withoutEvents(function () use ($order) {
                $order->updateQuietly([
                    'order_status' => 'Pending',
                    'progress' => 0,
                ]);
            });
            return;
        } 

        // When Order Status is changed: if Pending → Start off; if Starting/Processing/Finalizing/Done → Start on + progress
        if ($order->wasChanged('order_status')) {
            $status = $order->order_status;
            if ($status === 'Pending' || strtolower($status ?? '') === 'pending') {
                Order::withoutEvents(function () use ($order) {
                    $order->updateQuietly(['status' => false, 'progress' => 0]);
                });
                return;
            }
            $progress = self::STATUS_PROGRESS[$status] ?? self::STATUS_PROGRESS[strtolower($status ?? '')] ?? null;
            if ($progress !== null) {
                Order::withoutEvents(function () use ($order, $progress) {
                    $order->updateQuietly(['status' => true, 'progress' => $progress]);
                });
            }
        } 
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
