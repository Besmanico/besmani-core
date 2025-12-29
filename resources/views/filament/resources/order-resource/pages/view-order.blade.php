<style>
    /* Override Filament's max-width constraints for order view */
    .fi-section-content-ctn,
    .fi-section,
    .fi-form-component-ctn,
    [data-slot="content"],
    .order-details-view {
        max-width: 100% !important;
        width: 100% !important;
    }
    
    /* Ensure parent containers are also full width */
    .fi-main-ctn .fi-section,
    .fi-main-ctn .fi-section-content {
        max-width: 100% !important;
        width: 100% !important;
    }
</style>

<div class="order-details-view" style="width: 100% !important; max-width: 100% !important; margin: 0 !important;">
    @php
        $orderData = $data ?? [];
        $order = $orderData['order'] ?? $record;
        $cartInfo = $orderData['cartInfo'] ?? null;
        $packageServices = $orderData['packageServices'] ?? collect();
        $grandSubtotal = $orderData['grandSubtotal'] ?? 0;
        $grandTotalTax = $orderData['grandTotalTax'] ?? 0;
        $grandTotalDiscount = $orderData['grandTotalDiscount'] ?? 0;
        $grandGrandTotal = $orderData['grandGrandTotal'] ?? 0;
        
        $mainUser = $order->user;
        
        // Get contact name from order or user
        $contactName = $order->contact_name ?? ($mainUser ? ($mainUser->fl_name . ' ' . $mainUser->last_name) : 'Unknown');
        $billingAddress = $order->billing_address ?? '';
        $shippingAddress = $order->shipping_address ?? '';
        $signatureClient = $order->signature_client ?? '';
        $signatureDate = $order->signature_date ?? $order->created_at->format('m/d/Y');
        
        $installmentPays = $order->installmentPays ?? collect();
        
        // If order is not loaded with relationships, load them
        if (!$order->relationLoaded('installmentPays')) {
            $order->load('installmentPays');
            $installmentPays = $order->installmentPays ?? collect();
        }
    @endphp

    <div style="width: 100% !important; max-width: 100% !important; padding: 20px; margin: 0;">
        @foreach ($packageServices as $key => $packageService)
            <div class="main-cart-container w-100" style="margin-bottom: 30px;">
                <div class="quote-card" style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    {{-- Header --}}
                    <div class="quote-header" style="margin-bottom: 30px;">
                        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 20px;">
                            <img src="{{ asset('assets-file/img/logo.png') }}" alt="Besmani" style="height: 60px; margin-right: 20px;">
                            <div>
                                <div style="font-size: 24px; font-weight: bold; color: #1f2937;">{{ $packageService->serviceInfo->title ?? 'Service' }}</div>
                                <div style="font-size: 18px; font-weight: 600; color: #374151;">Besmani Technologies, Inc.</div>
                                <div style="color: #6b7280;">AI • Robots • Software • Marketing</div>
                                <div style="color: #6b7280;">Irvine, CA</div>
                            </div>
                        </div>

                        <div style="margin-top: 20px; text-align: right;">
                            <div style="background: #f3f4f6; padding: 15px; border-radius: 6px; display: inline-block;">
                                <div style="font-size: 14px; color: #6b7280;"><b>Order #:</b> <span style="color: #dc2626; font-weight: bold;">{{ $order->tracking_code }}</span></div>
                                <div style="font-size: 12px; color: #9ca3af; margin-top: 5px;">
                                    Issued Date: {{ $order->created_at->format('m-d-Y') }}<br>
                                    Due Date: {{ $order->created_at->addDays(30)->format('m-d-Y') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Contact Info --}}
                    <div style="margin-bottom: 30px;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                            <div style="background: #f9fafb; padding: 15px; border-radius: 6px;">
                                <div style="font-weight: 600; margin-bottom: 5px;">Besmani Contact</div>
                                <div>Besmani.com</div>
                            </div>
                            <div style="background: #f9fafb; padding: 15px; border-radius: 6px;">
                                <div style="font-weight: 600; margin-bottom: 5px;">Company Name</div>
                                <div>{{ $contactName }}</div>
                            </div>
                            <div style="background: #f9fafb; padding: 15px; border-radius: 6px;">
                                <div style="font-weight: 600; margin-bottom: 5px;">Contact Name</div>
                                <div>{{ $contactName }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Addresses --}}
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
                        <div style="background: #f9fafb; padding: 15px; border-radius: 6px;">
                            <div style="font-weight: 600; margin-bottom: 10px;">Billing Address</div>
                            <div style="color: #6b7280; white-space: pre-line;">{{ $billingAddress }}</div>
                        </div>
                        <div style="background: #f9fafb; padding: 15px; border-radius: 6px;">
                            <div style="font-weight: 600; margin-bottom: 10px;">Shipping Address</div>
                            <div style="color: #6b7280; white-space: pre-line;">{{ $shippingAddress }}</div>
                        </div>
                    </div>

                    {{-- Items Table --}}
                    @php
                        $subtotal = 0;
                        $totalTax = 0;
                        $totalDiscount = 0;
                        $grandTotal = 0;
                    @endphp

                    <div style="overflow-x: auto; margin-bottom: 30px;">
                        <table style="width: 100%; border-collapse: collapse; border: 1px solid #e5e7eb;">
                            <thead>
                                <tr style="background: #f9fafb;">
                                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e5e7eb; min-width: 90px;">Item Code</th>
                                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e5e7eb;">Item Name</th>
                                    <th style="padding: 12px; text-align: center; border-bottom: 2px solid #e5e7eb; min-width: 80px;">Quantity</th>
                                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e5e7eb; min-width: 90px;">$Price</th>
                                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e5e7eb; min-width: 100px;">Discount</th>
                                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e5e7eb; min-width: 140px;">Tax & Fee</th>
                                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e5e7eb; min-width: 90px;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($packageService->packageServiceItems as $packageServiceItem)
                                    @if ($packageServiceItem->orderItem && !$packageServiceItem->customeDeleteItem)
                                        @php
                                            $itemTotal = $packageServiceItem->quantity * $packageServiceItem->orderItem->price;
                                            
                                            if ($packageServiceItem->orderItem->discount_type == '%') {
                                                $discountAmount = ($itemTotal * $packageServiceItem->orderItem->discount) / 100;
                                                $itemTotalWithDiscount = $itemTotal - $discountAmount;
                                                if ($itemTotalWithDiscount < 0) $itemTotalWithDiscount = 0;
                                                $testttt = ($packageServiceItem->orderItem->tax * $itemTotal) / 100;
                                                $TotalLastColumnFinal = $testttt + $itemTotalWithDiscount;
                                                $TypeDiscount = '%';
                                                $TypeDiscountDollar = '';
                                            } else {
                                                $discountAmount = $packageServiceItem->orderItem->discount;
                                                $itemTotalWithDiscount = $itemTotal - $discountAmount;
                                                if ($itemTotalWithDiscount < 0) $itemTotalWithDiscount = 0;
                                                $testttt = ($packageServiceItem->orderItem->tax * $itemTotal) / 100;
                                                $TotalLastColumnFinal = $itemTotalWithDiscount + $testttt;
                                                $TypeDiscount = '';
                                                $TypeDiscountDollar = '$';
                                            }
                                            
                                            $subtotal += $itemTotal;
                                            $totalTax += $testttt;
                                            $totalDiscount += $discountAmount;
                                            $grandTotal += $TotalLastColumnFinal;
                                        @endphp
                                        <tr style="border-bottom: 1px solid #e5e7eb;">
                                            <td style="padding: 12px;">{{ $packageServiceItem->orderItem->code }}</td>
                                            <td style="padding: 12px;"><strong>{{ $packageServiceItem->orderItem->name }}</strong></td>
                                            <td style="padding: 12px; text-align: center;">{{ $packageServiceItem->quantity }}</td>
                                            <td style="padding: 12px; text-align: right;">
                                                ${{ number_format($packageServiceItem->orderItem->price, 2) }}<br>
                                                <small style="color: #6b7280;">= ${{ number_format($itemTotal, 2) }}</small>
                                            </td>
                                            <td style="padding: 12px; text-align: right;">
                                                {{ $TypeDiscountDollar }}{{ $packageServiceItem->orderItem->discount }}{{ $TypeDiscount }}<br>
                                                <small style="color: #6b7280;">= ${{ number_format($itemTotalWithDiscount, 2) }}</small>
                                            </td>
                                            <td style="padding: 12px; text-align: right;">
                                                {{ $packageServiceItem->orderItem->tax }}%<br>
                                                <small style="color: #6b7280;">= ${{ number_format($testttt, 2) }}</small>
                                            </td>
                                            <td style="padding: 12px; text-align: right; font-weight: 600;">${{ number_format($TotalLastColumnFinal, 2) }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                
                                {{-- Custom Package Items --}}
                                @if ($packageService->customePackageItems && $packageService->customePackageItems->count() > 0)
                                    @foreach ($packageService->customePackageItems as $customePackageItem)
                                        @if ($customePackageItem->orderItem)
                                            @php
                                                $customQuantity = 1;
                                                $customItemTotal = $customQuantity * $customePackageItem->orderItem->price;
                                                
                                                if ($customePackageItem->orderItem->discount_type == '%') {
                                                    $customDiscountAmount = ($customItemTotal * $customePackageItem->orderItem->discount) / 100;
                                                    $customItemTotalWithDiscount = $customItemTotal - $customDiscountAmount;
                                                    if ($customItemTotalWithDiscount < 0) $customItemTotalWithDiscount = 0;
                                                    $customTestttt = ($customePackageItem->orderItem->tax * $customItemTotal) / 100;
                                                    $customTotalLastColumnFinal = $customTestttt + $customItemTotalWithDiscount;
                                                    $customTypeDiscount = '%';
                                                    $customTypeDiscountDollar = '';
                                                } else {
                                                    $customDiscountAmount = $customePackageItem->orderItem->discount;
                                                    $customItemTotalWithDiscount = $customItemTotal - $customDiscountAmount;
                                                    if ($customItemTotalWithDiscount < 0) $customItemTotalWithDiscount = 0;
                                                    $customTestttt = ($customePackageItem->orderItem->tax * $customItemTotal) / 100;
                                                    $customTotalLastColumnFinal = $customItemTotalWithDiscount + $customTestttt;
                                                    $customTypeDiscount = '';
                                                    $customTypeDiscountDollar = '$';
                                                }
                                                
                                                $subtotal += $customItemTotal;
                                                $totalTax += $customTestttt;
                                                $totalDiscount += $customDiscountAmount;
                                                $grandTotal += $customTotalLastColumnFinal;
                                            @endphp
                                            <tr style="border-bottom: 1px solid #e5e7eb; background: #f0fff4;">
                                                <td style="padding: 12px;">{{ $customePackageItem->orderItem->code }}</td>
                                                <td style="padding: 12px;"><strong>{{ $customePackageItem->orderItem->name }}</strong></td>
                                                <td style="padding: 12px; text-align: center;">{{ $customQuantity }}</td>
                                                <td style="padding: 12px; text-align: right;">
                                                    ${{ number_format($customePackageItem->orderItem->price, 2) }}<br>
                                                    <small style="color: #6b7280;">= ${{ number_format($customItemTotal, 2) }}</small>
                                                </td>
                                                <td style="padding: 12px; text-align: right;">
                                                    {{ $customTypeDiscountDollar }}{{ $customePackageItem->orderItem->discount }}{{ $customTypeDiscount }}<br>
                                                    <small style="color: #6b7280;">= ${{ number_format($customItemTotalWithDiscount, 2) }}</small>
                                                </td>
                                                <td style="padding: 12px; text-align: right;">
                                                    {{ $customePackageItem->orderItem->tax }}%<br>
                                                    <small style="color: #6b7280;">= ${{ number_format($customTestttt, 2) }}</small>
                                                </td>
                                                <td style="padding: 12px; text-align: right; font-weight: 600;">${{ number_format($customTotalLastColumnFinal, 2) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
 
                    {{-- Totals --}}
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px;">
                        <div style="color: #6b7280; font-size: 14px;">
                            Thank you for your business. Prices are in USD. This order is valid for 30 days from the issued date.
                        </div>
                        <div style="background: #f9fafb; padding: 20px; border-radius: 6px; min-width: 250px;">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="padding: 8px 0; color: #6b7280;">Subtotal:</td>
                                    <td style="padding: 8px 0; text-align: right; font-weight: 600;">${{ number_format($subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0; color: #6b7280;">Tax & Fee:</td>
                                    <td style="padding: 8px 0; text-align: right; font-weight: 600;">${{ number_format($totalTax, 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0; color: #6b7280;">Discount:</td>
                                    <td style="padding: 8px 0; text-align: right; font-weight: 600; color: #dc2626;">-${{ number_format($totalDiscount, 2) }}</td>
                                </tr>
                                <tr style="border-top: 2px solid #e5e7eb;">
                                    <td style="padding: 12px 0; font-size: 18px; font-weight: bold;">Total:</td>
                                    <td style="padding: 12px 0; text-align: right; font-size: 18px; font-weight: bold; color: #059669;">${{ number_format($grandTotal, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Signatures --}}
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
                        <div>
                            <div style="margin-bottom: 10px; font-weight: 600;">Client Signature</div>
                            <div style="border: 1px solid #e5e7eb; padding: 10px; border-radius: 4px; min-height: 50px;">
                                {{ $signatureClient }}
                            </div>
                        </div>
                        <div>
                            <div style="margin-bottom: 10px; font-weight: 600;">Date</div>
                            <div style="border: 1px solid #e5e7eb; padding: 10px; border-radius: 4px; min-height: 50px;">
                                {{ $signatureDate }}
                            </div>
                        </div>
                        <div>
                            <div style="margin-bottom: 10px; font-weight: 600;">Besmani Signature</div>
                            <div style="border: 1px solid #e5e7eb; padding: 10px; border-radius: 4px; min-height: 50px; color: #6b7280;">
                                Besmani
                            </div>
                        </div>
                        <div>
                            <div style="margin-bottom: 10px; font-weight: 600;">Date</div>
                            <div style="border: 1px solid #e5e7eb; padding: 10px; border-radius: 4px; min-height: 50px; color: #6b7280;">
                                {{ $order->created_at->format('m/d/Y') }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
        
        {{-- Installment Pays Section - Show once for the order's cart_id --}}
        @if ($installmentPays && $installmentPays->count() > 0)
            <div style="background: #f0f9ff; border: 2px solid #0ea5e9; border-radius: 8px; padding: 20px; margin-top: 30px; width: 100%;">
                <h3 style="margin-top: 0; margin-bottom: 20px; color: #0369a1; font-size: 20px;">
                    <i class="fa fa-calendar-check-o" style="margin-right: 8px;"></i>Installment Payment Schedule
                </h3>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #0ea5e9; color: white;">
                                <th style="padding: 12px; text-align: left; border: 1px solid #0284c7;">#</th>
                                <th style="padding: 12px; text-align: left; border: 1px solid #0284c7;">Amount</th>
                                <th style="padding: 12px; text-align: left; border: 1px solid #0284c7;">Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($installmentPays as $index => $installmentPay)
                                <tr style="background: white; {{ $loop->last ? '' : 'border-bottom: 1px solid #e5e7eb;' }}">
                                    <td style="padding: 12px; border: 1px solid #e5e7eb;">{{ $loop->iteration }}</td>
                                    <td style="padding: 12px; border: 1px solid #e5e7eb; font-weight: 600; color: #059669;">
                                        ${{ number_format($installmentPay->amount, 2) }}
                                    </td>
                                    <td style="padding: 12px; border: 1px solid #e5e7eb;">
                                        {{ \Carbon\Carbon::parse($installmentPay->date)->format('m/d/Y') }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr style="background: #f8fafc; font-weight: bold;">
                                <td style="padding: 12px; border: 1px solid #e5e7eb;" colspan="2">Total Installments:</td>
                                <td style="padding: 12px; border: 1px solid #e5e7eb; color: #059669;">
                                    ${{ number_format($installmentPays->sum('amount'), 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 15px; padding: 10px; background: #dbeafe; border-radius: 4px; color: #1e40af; font-size: 14px;">
                    <strong>Payment Method:</strong> {{ ucfirst($order->pay_method ?? 'N/A') }}
                </div>
            </div>
        @endif
    </div>
</div>

