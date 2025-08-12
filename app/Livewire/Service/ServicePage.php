<?php

namespace App\Livewire\Service;

use App\Models\Service;
use Livewire\Component;

class ServicePage extends Component
{
    public $slug;
    public function mount($slug)
    {
        $this->slug = $slug;
    }
    public function render()
    {
        $metaData = ['title' => $this->slug];
        $service = Service::where('slug', $this->slug)->first();
        $data = ['service' => $service];
        return view('livewire.service.service-page',$data)->layout('components.layouts.difheader',$metaData);
    }
}
