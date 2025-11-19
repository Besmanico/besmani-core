<?php

namespace App\Filament\Resources\OrderItemResource\Pages;

use Filament\Actions;
use App\Models\OrderItem;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\OrderItemResource;

class CreateOrderItem extends CreateRecord
{
    protected static string $resource = OrderItemResource::class;


 
    // before create code increment first BES  and start from 1000 form example BES1000, BES1001, BES1002, etc.
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        
        $maxCode = OrderItem::max('code_int');
        $nextCode = $maxCode ? $maxCode + 1 : 1000;
        $data['code'] = 'BES' . str_pad($nextCode, 4, '0', STR_PAD_LEFT);
        $data['code_int'] = $nextCode;
        return $data;
    }
     


}
