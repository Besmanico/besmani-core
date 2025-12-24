<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use App\Models\TravelAdmin;
use App\Filament\Resources\UserResource;
use App\Models\ShopAdmin;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {
         $Edit = TravelAdmin::where('email', $data['email'])->first();
        $EditShop = ShopAdmin::where('email', $data['email'])->first();

        
        // travel
        $Edit->name = $data['name'];
        $Edit->email = $data['email'];
        // Only update password if it's provided
        if (isset($data['password']) && filled($data['password'])) {
            $Edit->password = $data['password'];
        }
        $Edit->status = $data['status'];
        $Edit->update();

        // shop
 
        $EditShop->name = $data['name'];
        $EditShop->email = $data['email']; 
        // Only update password if it's provided
        if (isset($data['password']) && filled($data['password'])) {
            $EditShop->password = $data['password'];
        }
        $EditShop->status = $data['status'];
        $EditShop->update();

        // Remove password from data if empty to keep current password
        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }
}
