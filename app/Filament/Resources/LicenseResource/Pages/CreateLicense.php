<?php

namespace App\Filament\Resources\LicenseResource\Pages;

use App\Filament\Resources\LicenseResource;
use App\Models\License;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class CreateLicense extends CreateRecord
{
    protected static string $resource = LicenseResource::class;

    protected function handleRecordCreation(array $data): License
    {
        $qty = $data['qty'] ?? 1;
        $user_id = $data['user_id'];
        
        $createdLicenses = [];
        
        for ($i = 0; $i < $qty; $i++) {
            $licenseCode = $this->generateLicenseCode();
            
            $license = License::create([
                'code' => $licenseCode,
                'user_id' => $user_id,
                'status' => 0,
            ]);
            
            $createdLicenses[] = $license;
        }
        
        // Show success notification with count
        Notification::make()
            ->title('Success!')
            ->body("Successfully created {$qty} license codes.")
            ->success()
            ->send();
        
        // Return the first created license for redirect purposes
        return $createdLicenses[0];
    }

    private function generateLicenseCode(): string
    {
        do {
            // Generate a unique 8-character license code
            $code = strtoupper(Str::random(8));
        } while (License::where('code', $code)->exists());
        
        return $code;
    } 

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
