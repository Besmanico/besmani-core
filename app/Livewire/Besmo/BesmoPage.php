<?php

namespace App\Livewire\Besmo;

use Livewire\Component;

class BesmoPage extends Component
{
    public function render()
    {
         $metaData = ['title' => 'Besmo'];
        return view('livewire.besmo.besmo-page')->layout('components.layouts.difheader',$metaData);;
    }
}
