<?php

namespace App\Livewire\Terms;

use Livewire\Component;

class TermPage extends Component
{
    public function render()
    {
        $metaData = ['title' => 'Terms & Conditions'];
        return view('livewire.terms.term-page')->layout('components.layouts.difheader',$metaData);
    }
}
