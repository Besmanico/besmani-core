<?php

namespace App\Livewire\Panel\Payment;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PaymentPage extends Component
{
    public function mount()
    {
        if (!Auth::guard('mainUsers')->check()) {
            $this->redirect('/', navigate: true);
        }
    }

    public function render()
    {
        $orders = Order::where('user_id', Auth::guard('mainUsers')->user()->id)
        ->with(['cart.cartServices.packageServiceItems.orderItem', 'cart.cartServices.serviceInfo'])
        ->get();
        $metaData = ['title' => 'Payments'];   

       
        return view('livewire.panel.payment.payment-page', ['orders' => $orders])->layout('components/layouts.panel', $metaData);
    } 
}
