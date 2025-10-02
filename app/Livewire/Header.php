<?php

namespace App\Livewire;

use App\Models\PhoneCountry;
use Livewire\Component;
use Illuminate\Http\Request;

class Header extends Component
{
    

    public function signup(Request $request)
    {
         return 1;
    }

    public function render()
    {

        return view('livewire.header');
    }
}
