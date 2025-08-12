<?php

namespace App\Livewire\Contact;

use Livewire\Component;

class ContactPage extends Component
{
    public function render()
    {
        $metaData = ['title' => 'Contact Us'];
        return view('livewire.contact.contact-page')->layout('components.layouts.difheader',$metaData);
    }
}
