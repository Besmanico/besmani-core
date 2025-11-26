<?php

namespace App\Livewire\Panel;

use Livewire\Component;
use Illuminate\Support\Facades\Request;

class Header extends Component
{
    public function getTitleProperty()
    {
        $path = Request::path();
        
        // تعیین title بر اساس path
        if ($path === 'panel') {
            return 'Dashboard';
        } elseif ($path === 'panel/invoice') {
            return 'Orders';
        } elseif (str_starts_with($path, 'panel/')) {
            // برای صفحات دیگر، نام صفحه را از path استخراج می‌کنیم
            $pageName = str_replace('panel/', '', $path);
            return ucfirst($pageName);
        }
        
        return 'Personal Panel';
    }

    public function render()
    {
        return view('livewire.panel.header');
    }
}
