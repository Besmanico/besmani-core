<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Livewire\Component;

class Services extends Component
{
    public function render()
    {
        $services = Service::where('status', 1)->get();
        $metaData = ['title' => 'Services'];
        $data = ['services' => $services];
        return view('livewire.services.services',$data)->layout('components.layouts.difheader',$metaData);
    }
}
