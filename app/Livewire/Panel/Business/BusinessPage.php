<?php

namespace App\Livewire\Panel\Business;

use Livewire\Component;

class BusinessPage extends Component
{
     public function render()
    {
  
        $metaData = ['title' => 'Business'];
        return view('livewire.panel.business.business-page', ['title' => $metaData['title']])->layout('components.layouts.panel', $metaData);
    } 
}
