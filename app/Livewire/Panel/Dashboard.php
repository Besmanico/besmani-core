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
        $user = Auth::guard('mainUsers')->user();
        $isPersonalUser = $user && (int)($user->service_pr ?? 1) === 0;

        $metaData = ['title' => 'Dashboard'];
        return view('livewire.panel.dashboard', [
            'title' => $metaData['title'],
            'isPersonalUser' => $isPersonalUser,
        ])->layout('components/layouts.panel', $metaData);
    } 
}
