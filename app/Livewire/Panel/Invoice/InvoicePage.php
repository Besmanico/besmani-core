<?php

namespace App\Livewire\Panel\Invoice;

use App\Models\Cart;
use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class InvoicePage extends Component
{
    public function mount()
    {
        // Check if user is authenticated with mainUsers guard
        if (!Auth::guard('mainUsers')->check()) {
            $this->redirect('/', navigate: true);
        }
    }

    public function render()
    {


        $orders = Order::where('user_id', Auth::guard('mainUsers')->user()->id)
            ->with(['cart.cartServices.packageServiceItems.orderItem', 'cart.cartServices.serviceInfo'])
            ->get();


        $metaData = ['title' => 'Invoice'];



        return view('livewire.panel.invoice.invoice-page', ['title' => $metaData['title'], 'orders' => $orders])->layout('components/layouts.panel', $metaData);
    }
}
