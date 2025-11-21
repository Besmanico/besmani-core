<?php

namespace App\Livewire\Cart;

use Livewire\Component;

class CartPage extends Component
{
    public function render()
    {
        $metaData = ['title' => 'Cart'];
        return view('livewire.cart.cart-page')->layout('components.layouts.difheader', $metaData);
    }
}
