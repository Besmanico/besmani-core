<?php

namespace App\Livewire\Serviceagreement;

use Livewire\Component;

class ServicePage extends Component
{
    public function render()
    {
        $metaData = ['title' => 'Service Agreement'];
        return view('livewire.serviceagreement.service-page')->layout('components.layouts.difheader',$metaData);
    }
}
