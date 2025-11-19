<?php

namespace App\Livewire\Order;

use App\Models\Service;
use Livewire\Component;
use App\Models\OrderItem;
use App\Models\PackageServiceItem;

class OrderPage extends Component
{
    public $slug;
    public $service_id;
    public function mount($slug, $service_id)
    {
        $this->slug = $slug;

        $this->service_id = $service_id;
    }
    public function render()
    {

        $service = Service::where('id', $this->service_id)->first();
        // get package service items
        $packageServiceItems = PackageServiceItem::where('package_service_id', $this->slug)->get();
     
        foreach ($packageServiceItems as $packageServiceItem) {

            $packageServiceItem->orderItem = OrderItem::where('id', $packageServiceItem->orderitem_id)->first();

        }
      
         


        $metaData = ['title' => 'Order'];
        return view('livewire.order.order-page', ['id' => $this->slug, 'service' => $service, 'packageServiceItems' => $packageServiceItems])->layout('components.layouts.difheader', $metaData);
    }
}
