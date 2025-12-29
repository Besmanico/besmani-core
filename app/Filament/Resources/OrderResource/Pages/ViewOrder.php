<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Models\OrderItem;
use Filament\Forms\Form;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;
    
    protected ?string $maxContentWidth = 'full';

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function getOrderData()
    {
        // Load order with all necessary relationships
        $order = $this->record->load([
            'cart.cartServices.packageServiceItems.orderItem',
            'cart.cartServices.packageServiceItems.customeDeleteItem',
            'cart.cartServices.customePackageItems.orderItem',
            'cart.cartServices.serviceInfo',
            'user',
        ]);
        
        // Load installment pays specifically for this order's cart_id
        $order->load(['installmentPays' => function ($query) use ($order) {
            $query->where('cart_id', $order->cart_id);
        }]);

        // Prepare package services data
        $cartInfo = $order->cart;
        $packageServices = $cartInfo ? $cartInfo->cartServices : collect();

        // Load orderItem for each packageServiceItem if not loaded
        if ($cartInfo && $packageServices) {
            foreach ($packageServices as $cartService) {
                if (!$cartService->relationLoaded('packageServiceItems')) {
                    $cartService->load('packageServiceItems.orderItem', 'packageServiceItems.customeDeleteItem');
                }

                if ($cartService->packageServiceItems) {
                    foreach ($cartService->packageServiceItems as $packageServiceItem) {
                        // Load orderItem if not already loaded
                        if (!$packageServiceItem->orderItem && $packageServiceItem->orderitem_id) {
                            $packageServiceItem->orderItem = OrderItem::where('id', $packageServiceItem->orderitem_id)->first();
                        }
                    }
                }

                // Load custom package items
                if (!$cartService->relationLoaded('customePackageItems')) {
                    $cartService->load('customePackageItems.orderItem');
                }
            }
        }

        // Calculate totals
        $grandSubtotal = 0;
        $grandTotalTax = 0;
        $grandTotalDiscount = 0;
        $grandGrandTotal = 0;

        if ($cartInfo && $packageServices) {
            foreach ($packageServices as $packageService) {
                if ($packageService->packageServiceItems) {
                    foreach ($packageService->packageServiceItems as $packageServiceItem) {
                        // Skip deleted items
                        if ($packageServiceItem->customeDeleteItem) {
                            continue;
                        }

                        if ($packageServiceItem->orderItem) {
                            $itemTotal = $packageServiceItem->quantity * $packageServiceItem->orderItem->price;
                            $grandSubtotal += $itemTotal;

                            // Calculate discount
                            if ($packageServiceItem->orderItem->discount_type == '%') {
                                $discountAmount = ($itemTotal * $packageServiceItem->orderItem->discount) / 100;
                                $itemTotalWithDiscount = $itemTotal - $discountAmount;
                                if ($itemTotalWithDiscount < 0) {
                                    $itemTotalWithDiscount = 0;
                                }
                                $testttt = ($packageServiceItem->orderItem->tax * $itemTotal) / 100;
                                $TotalLastColumnFinal = ($packageServiceItem->orderItem->tax * $itemTotal) / 100 + $itemTotalWithDiscount;
                            } else {
                                $discountAmount = $packageServiceItem->orderItem->discount;
                                $itemTotalWithDiscount = $itemTotal - $discountAmount;
                                if ($itemTotalWithDiscount < 0) {
                                    $itemTotalWithDiscount = 0;
                                }
                                $testttt = ($packageServiceItem->orderItem->tax * $itemTotal) / 100;
                                $TotalLastColumnFinal = $itemTotalWithDiscount + $testttt;
                            }

                            $grandTotalTax += $testttt;
                            $grandTotalDiscount += $discountAmount;
                            $grandGrandTotal += $TotalLastColumnFinal;
                        }
                    }
                }

                // Calculate for custom package items
                if ($packageService->customePackageItems) {
                    foreach ($packageService->customePackageItems as $customePackageItem) {
                        if ($customePackageItem->orderItem) {
                            $customQuantity = 1;
                            $customItemTotal = $customQuantity * $customePackageItem->orderItem->price;
                            $grandSubtotal += $customItemTotal;

                            // Calculate discount for custom item
                            if ($customePackageItem->orderItem->discount_type == '%') {
                                $customDiscountAmount = ($customItemTotal * $customePackageItem->orderItem->discount) / 100;
                                $customItemTotalWithDiscount = $customItemTotal - $customDiscountAmount;
                                if ($customItemTotalWithDiscount < 0) {
                                    $customItemTotalWithDiscount = 0;
                                }
                                $customTestttt = ($customePackageItem->orderItem->tax * $customItemTotal) / 100;
                                $customTotalLastColumnFinal = $customTestttt + $customItemTotalWithDiscount;
                            } else {
                                $customDiscountAmount = $customePackageItem->orderItem->discount;
                                $customItemTotalWithDiscount = $customItemTotal - $customDiscountAmount;
                                if ($customItemTotalWithDiscount < 0) {
                                    $customItemTotalWithDiscount = 0;
                                }
                                $customTestttt = ($customePackageItem->orderItem->tax * $customItemTotal) / 100;
                                $customTotalLastColumnFinal = $customItemTotalWithDiscount + $customTestttt;
                            }

                            $grandTotalTax += $customTestttt;
                            $grandTotalDiscount += $customDiscountAmount;
                            $grandGrandTotal += $customTotalLastColumnFinal;
                        }
                    }
                }
            }
        }

        return [
            'order' => $order,
            'cartInfo' => $cartInfo,
            'packageServices' => $packageServices,
            'grandSubtotal' => $grandSubtotal,
            'grandTotalTax' => $grandTotalTax,
            'grandTotalDiscount' => $grandTotalDiscount,
            'grandGrandTotal' => $grandGrandTotal,
        ];
    }

    public function form(Form $form): Form
    {
        $orderData = $this->getOrderData();
        
        // Render the custom view to HTML
        $htmlContent = view('filament.resources.order-resource.pages.view-order', [
            'data' => $orderData,
            'record' => $this->record,
        ])->render();
        
        return $form
            ->schema([
                Placeholder::make('order_details')
                    ->label('')
                    ->content(new HtmlString($htmlContent))
                    ->columnSpanFull()
            ])
            ->columns(1)
            ->extraAttributes([
                'style' => 'max-width: 100% !important; width: 100% !important;'
            ]);
    }
}

