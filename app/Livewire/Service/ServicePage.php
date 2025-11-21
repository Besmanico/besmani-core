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
        $service = Service::where('slug', $this->slug)
            ->with(['packageServices.packageServiceItems'])
            ->firstOrFail();


        $metaTitle = $service->title ?? $service->name ?? $service->slug ?? $this->slug;
        $metaData = ['title' => $metaTitle];

        $data = ['service' => $service];

        return view('livewire.service.service-page', $data)
            ->layout('components.layouts.difheader', $metaData);
    }
}
