<?php

namespace App\Livewire\About;

use Livewire\Component;

class AboutPage extends Component
{
    public function render()
    {
        $metaData = ['title' => 'About Us'];
        return view('livewire.about.about-page')->layout('components.layouts.difheader',$metaData);
    }
}
