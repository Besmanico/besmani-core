<?php

namespace App\Livewire\Careers;

use Livewire\Component;

class Careers extends Component
{
    public function render()
    {
        $metaData = ['title' => 'Careers'];
        return view('livewire.careers.careers')->layout('components.layouts.difheader',$metaData);  
    }
} 
