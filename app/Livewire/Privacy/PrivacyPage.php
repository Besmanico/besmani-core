<?php

namespace App\Livewire\Privacy;

use Livewire\Component;

class PrivacyPage extends Component
{
    public function render()
    {
        $metaData = ['title' => 'Privacy Policy'];
        return view('livewire.privacy.privacy-page')->layout('components.layouts.difheader',$metaData);
    }
}
