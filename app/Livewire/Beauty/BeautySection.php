<?php

namespace App\Livewire\Beauty;

use App\Models\Slider;
use Livewire\Component;

class BeautySection extends Component
{
    public function render()
    {
        $sliders = Slider::where('status', 1)
        ->where('home_page', 1)->orderBy('hom_page_sort', 'asc')->limit(6)->get();
        $data = ['sliders' => $sliders]; 
        return view('livewire.beauty.beauty-section', $data); 
    }
}
