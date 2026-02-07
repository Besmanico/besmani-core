<?php

namespace App\Livewire\Agreement;

use Livewire\Component;

class AgreementPage extends Component
{
    public function render()
    {
        $metaData = ['title' => 'Service Agreement'];
        return view('livewire.agreement.agreement-page')->layout('components.layouts.difheader',$metaData);
    }
}
