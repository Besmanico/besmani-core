<?php

namespace App\Livewire;

use Livewire\Component;

class Home extends Component
{
    public $home = 'active';
    public $service = '';
    public $career = '';
    public $about = '';
    public $contact = '';
    public $login = '';
    public $title = 'Besmani Experience of the Future Technology and Design';

    public function mount()
    {
        $this->home = 'active';
        $this->service = '';
        $this->career = '';
        $this->about = '';
        $this->contact = '';
        $this->login = '';
    }
   
    public function render()
    {
        $metaData = ['title' => $this->title];

         
        return view('livewire.home')->layout('components/layouts.app',$metaData); 
    }

} 
