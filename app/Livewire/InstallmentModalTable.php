<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\InstallmentPay;
use Livewire\Component;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;

class InstallmentModalTable extends Component
{
    public ?int $orderId = null;

    /** Order free_price (saved to orders table) */
    public string $orderFreePrice = ''; 

    /** Order free_price_date (saved to orders table) */
    public string $orderFreePriceDate = '';
 
    public function updatedOrderId(): void
    {
        $this->loadOrderFreePrice();
    }

    public function mount(): void
    {
        $this->loadOrderFreePrice();
    }

    protected function loadOrderFreePrice(): void
    {
        if (!$this->orderId) {
            return;
        }
        $order = Order::find($this->orderId);
        if (!$order) {
            return;
        }
        if ($order->free_price !== null && $order->free_price !== '') {
            $this->orderFreePrice = (string) $order->free_price;
        } else {
            $this->orderFreePrice = '';
        }
        if ($order->free_price_date) {
            $this->orderFreePriceDate = $order->free_price_date instanceof \Carbon\Carbon
                ? $order->free_price_date->format('Y-m-d')
                : \Carbon\Carbon::parse($order->free_price_date)->format('Y-m-d');
        } else {
            $this->orderFreePriceDate = '';
        } 
    }

    public function toggleStatus(int $id): void
    {
        $installment = InstallmentPay::find($id);
        if ($installment) {
            $installment->update(['status' => ! (bool) $installment->status]);
        }
    }

    public function saveOrderFreePrice(): void
    {
        if (!$this->orderId) {
            return;
        }
        $order = Order::find($this->orderId);
        if (!$order) {
            return;
        }
        $value = trim($this->orderFreePrice);
        $num = $value !== '' && is_numeric($value) ? (float) $value : null;
        $dateValue = trim($this->orderFreePriceDate);
        $freePriceDate = null;
        if ($dateValue !== '') {
            try {
                $freePriceDate = \Carbon\Carbon::parse($dateValue)->startOfDay()->format('Y-m-d H:i:s');
            } catch (\Throwable $e) {
                $freePriceDate = null;
            } 
        }
        $order->update([
            'free_price' => $num,
            'free_price_date' => $freePriceDate,
        ]);

        Notification::make()
            ->title('Saved')
            ->body('Free price updated successfully.')
            ->success()
            ->send();

        $this->dispatch('saved');
    } 

    public function render()
    {
        $installments = new Collection();

        if ($this->orderId) {
            $order = Order::find($this->orderId);
            if ($order && $order->cart_id !== null && $order->cart_id !== '') {
                $installments = InstallmentPay::where('cart_id', $order->cart_id)->orderBy('id')->get();
            }
        } 

        $totalAmount = $installments->sum(fn ($i) => (float) ($i->amount ?? 0));
        $freePriceNum = trim($this->orderFreePrice) !== '' && is_numeric($this->orderFreePrice)
            ? (float) $this->orderFreePrice
            : 0;
        $totalWithFreePrice = $totalAmount + $freePriceNum;

        return view('livewire.installment-modal-table', [
            'installments' => $installments,
            'totalAmount' => $totalAmount,
            'totalWithFreePrice' => $totalWithFreePrice,
        ]);
    }
}
