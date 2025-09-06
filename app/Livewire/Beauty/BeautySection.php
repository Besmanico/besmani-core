<?php

namespace App\Livewire\Beauty;

use App\Models\Slider;
use Livewire\Component;

class BeautySection extends Component
{
    public function render()
    {
        $sliders = Slider::where('status', 1)->get();
        $data = ['sliders' => $sliders];
        return view('livewire.beauty.beauty-section', $data); 
    }
}
