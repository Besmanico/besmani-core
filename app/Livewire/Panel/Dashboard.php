<?php

namespace App\Livewire\Panel;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function mount()
    {
        // Check if user is authenticated with mainUsers guard
        if (!Auth::guard('mainUsers')->check()) {
            $this->redirect('/', navigate: true);
        }
    }

    public function render()
    {
        // // Double check authentication before rendering
        // if (!Auth::guard('mainUsers')->check()) {
        //     abort(403, 'Unauthorized access');
        // }

        $metaData = ['title' => 'Dashboard'];
        return view('livewire.panel.dashboard',['title' => $metaData['title']])->layout('components/layouts.panel', $metaData);
    }
}
