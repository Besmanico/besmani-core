<?php

namespace App\Livewire;

use App\Models\Service;
use Livewire\Component;

class ServicesSide extends Component
{
    public function render()
    {
        $services = Service::where('status', 1)->get();
        $data = ['services' => $services];

        return view('livewire.services-side', $data);
    }
}
