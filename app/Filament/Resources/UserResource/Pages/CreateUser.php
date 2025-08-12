<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use App\Models\TravelAdmin;
use App\Filament\Resources\UserResource;
use App\Models\ShopAdmin;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public  $Name = null;
    public  $Email = null;
    public  $Password = null;
    public  $Status = null;


    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $this->Name = $data['name'];
        $this->Email = $data['email'];
        $this->Password = $data['password'];
        $this->Status = $data['status'];

        return $data;
    }

    public function afterCreate()
    {

        // $check = TravelAdmin::where('email', $this->Email)->first();

        // TRAVEL
        $newTravelAdmin = new TravelAdmin();
        $newTravelAdmin->name = $this->Name;
        $newTravelAdmin->email = $this->Email;
        $newTravelAdmin->password = $this->Password;
        $newTravelAdmin->status = $this->Status;
        $newTravelAdmin->save(); 

        // SHOP
        $newShopAdmin = new ShopAdmin();
        $newShopAdmin->name = $this->Name;
        $newShopAdmin->email = $this->Email;
        $newShopAdmin->password = $this->Password;
        $newShopAdmin->status = $this->Status;

        $newShopAdmin->save();

    }
}
