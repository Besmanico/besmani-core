<div>
    @php
        $mainUser = Auth::guard('mainUsers')->user();
        $userInfo = UserInfoPublic();
        $cartInfo = CartInfo();
        // dd($cartInfo);

        $packageServices = $cartInfo ? $cartInfo->cartServices : collect();
        // Calculate totals across all services
        $grandSubtotal = 0;
        $grandTotalTax = 0;
        $grandTotalDiscount = 0;
        $grandGrandTotal = 0;
        $grandTotal = 0; // Initialize grandTotal for modal display

        if ($cartInfo && $packageServices) {
            foreach ($packageServices as $packageService) {
                if ($packageService->packageServiceItems) {
                    foreach ($packageService->packageServiceItems as $packageServiceItem) {
                        // Skip items that have customeDeleteItem (deleted items)
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
                                $TotalLastColumnFinal =
                                    ($packageServiceItem->orderItem->tax * $itemTotal) / 100 + $itemTotalWithDiscount;
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
                            $grandTotal = $grandGrandTotal; // Set grandTotal equal to grandGrandTotal
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
                                $customDiscountAmount =
                                    ($customItemTotal * $customePackageItem->orderItem->discount) / 100;
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
                            $grandTotal = $grandGrandTotal;
                        }
                    }
                }
            }
        }
    @endphp

    <section class="site-section subpage-site-section ">


        @if ($cartInfo)

            {{-- pay total for cart - Fixed Checkout Section --}}
            <div class="pay-total-cart-fixed">
                <div class="container">
                    <div class="pay-total-cart-content">
                        <div class="pay-total-cart-left">
                            <div class="pay-total-item">
                                <span class="pay-total-label">Subtotal:</span>
                                <span class="pay-total-value">${{ number_format($grandSubtotal, 2) }}</span>
                            </div>
                            <div class="pay-total-item">
                                <span class="pay-total-label">Tax & Fee:</span>
                                <span class="pay-total-value">${{ number_format($grandTotalTax, 2) }}</span>
                            </div>
                            <div class="pay-total-item">
                                <span class="pay-total-label">Discount:</span>
                                <span
                                    class="pay-total-value discount-value">-${{ number_format($grandTotalDiscount, 2) }}</span>
                            </div>
                        </div>
                        <div class="pay-total-cart-right">
                            <div class="pay-total-grand">
                                <span class="pay-total-grand-label">Orders Total:</span>
                                <span class="pay-total-grand-value">${{ number_format($grandGrandTotal, 2) }}</span>
                            </div>
                            <button class="btn-checkout-pay go-pay goPayAllNow">
                                <i class="fa fa-credit-card"></i>
                                Pay Orders
                            </button>
                        </div>
                    </div>
                </div>
            </div>



            <div class="container">
                <div class="row">

                    <div class="w-100 order-from-side">
                        @foreach ($packageServices as $key => $packageService)
                            <div class="main-cart-container w-100">


                                <div class="quote-card">

                                    <div class="quote-header">
                                        <div
                                            class="quote-logo d-flex flex-column flex-md-row align-items-start align-items-md-center">
                                            <img src="{{ asset('assets-file/img/logo.png') }}" alt="Besmani"
                                                class="quote-logo-img mb-2 mb-md-0" style="margin-top: 20px;">
                                            {{-- service name --}}
                                            <div class="service-name-header">
                                                {{ $packageService->serviceInfo->title }}
                                            </div>
                                            {{-- service name end --}}
                                            <div class="ms-0 ms-md-3">
                                                <div class="quote-company-name">Besmani
                                                    Technologies, Inc.</div>
                                                <div class="quote-tagline">AI • Robots • Software • Marketing</div>
                                                <div class="quote-location">Irvine, CA</div>
                                            </div>

                                        </div>

                                        <div class="quote-meta mt-3 mt-md-0">
                                            <div class="meta-box">
                                                <small class="quote-number-label"><b class="quote-label"> Quote : </b>
                                                    <b class="quote-number">
                                                        {{ $packageService->code }}</b></small>
                                                <div class="muted quote-dates">
                                                    Issued Date:
                                                    {{ \Carbon\Carbon::today()->format('m-d-Y') }}

                                                    <br>

                                                    {{-- today date + 30 days --}}
                                                    @php
                                                        $dueDate = \Carbon\Carbon::today()->addDays(30);
                                                    @endphp
                                                    Due Date:
                                                    {{ $dueDate->format('m-d-Y') }}
                                                </div>
                                            </div>

                                        </div>


                                    </div>

                                    {{-- check user hasActivity --}}
                                    @if ($userInfo->InfoActivity->count() > 0)
                                        {{-- get first activity --}}
                                        @php
                                            $activity = $userInfo->InfoActivity->first();
                                            $CompaniName = $activity->name;
                                            $shippingAddress = $activity->address;
                                            $BillingAddress = $activity->address;
                                        @endphp
                                    @else
                                        @php
                                            $CompaniName = $mainUser->fl_name . $mainUser->last_name;
                                            $shippingAddress = $mainUser->address;
                                            $BillingAddress = $mainUser->address;
                                        @endphp
                                    @endif

                                    <div class="w-100">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-12 col-md-4 mb-3 mb-md-0">
                                                    <div class="meta-box contact-box">
                                                        <b>Besmani Contact</b>
                                                        <div class="value contact-value">
                                                            Besmani.com
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4 mb-3 mb-md-0">
                                                    <div class="meta-box contact-box">
                                                        <b>Company Name</b>
                                                        <div class="value contact-value">{{ $CompaniName }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="meta-box contact-box">
                                                        <b>Contact Name</b>
                                                        <div class="value contact-value ">
                                                            <input type="text" class="w-100 bg-none ContactNameEdit-{{ $packageService->id }}"
                                                                value="{{ $CompaniName }}">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    {{-- footer --}}
                                    <div class="addresses d-flex flex-column flex-md-row">
                                        <div class="addr-box mb-3 mb-md-0 me-md-3">
                                            <div class="addr-title">Billing Address</div>
                                            <div class="addr-body">
                                                <textarea class="w-100 bg-none BillingAddressEdit-{{ $packageService->id }}">{{ $BillingAddress }}</textarea>
                                            </div>
                                        </div>
                                        <div class="addr-box">
                                            <div class="addr-title">Shipping Address</div>
                                            <div class="addr-body">

                                                <textarea class="w-100 bg-none ShipingAddressEdit-{{ $packageService->id }}">{{ $shippingAddress }}</textarea>

                                            </div>
                                        </div>
                                    </div>
                                    {{-- Initialize totals once before looping through services --}}
                                    @php
                                        $subtotal = 0;
                                        $totalTax = 0;
                                        $totalDiscount = 0;
                                        $grandTotal = 0;
                                    @endphp
                                    {{-- table start --}}

                                    <div class="quote-table-wrap">
                                        <div class="table-responsive">
                                            <table class="quote-table">
                                                <thead>
                                                    <tr>
                                                        <th style="min-width:90px;">Item Code</th>
                                                        <th>Item Name</th>
                                                        <th style="min-width:80px;">Quantity</th>
                                                        <th style="min-width:90px;"> $Price</th>
                                                        <th style="min-width:100px;">Item Total*</th>
                                                        <th style="min-width:100px;">Discount</th>
                                                        <th style="min-width:120px;">Total After Discount*</th>
                                                        <th style="min-width:120px;">testttt*</th>
                                                        <th style="min-width:140px;">Tax & Fee</th>
                                                        <th style="min-width:90px;">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $itemIndex = 0;
                                                        $totalItems = count($packageService->packageServiceItems);
                                                        $customItemsCount = $packageService->customePackageItems
                                                            ? $packageService->customePackageItems->count()
                                                            : 0;
                                                        $totalItems += $customItemsCount;
                                                        $serviceId = 'service-' . $key;
                                                    @endphp
                                                    @foreach ($packageService->packageServiceItems as $packageServiceItem)
                                                        <?php
                                                        
                                                        $itemTotal = $packageServiceItem->quantity * $packageServiceItem->orderItem->price;
                                                        
                                                        // Check if item is deleted (has customeDeleteItem)
                                                        $isDeleted = $packageServiceItem->customeDeleteItem !== null;
                                                        
                                                        // Add to subtotal only if not deleted
                                                        if (!$isDeleted) {
                                                            $subtotal += $itemTotal;
                                                        }
                                                        
                                                        // check type of discount
                                                        if ($packageServiceItem->orderItem->discount_type == '%') {
                                                            $TypeDiscount = '%';
                                                            $TypeDiscountDollar = '';
                                                            $itemTotalWithDiscount = $itemTotal - ($itemTotal * $packageServiceItem->orderItem->discount) / 100;
                                                            if ($itemTotalWithDiscount < 0) {
                                                                $itemTotalWithDiscount = 0;
                                                            }
                                                        
                                                            $TotalLatsCol = $itemTotalWithDiscount * ($packageServiceItem->orderItem->tax / 100);
                                                            $TotalLatsColumn = ($packageServiceItem->orderItem->tax * $itemTotal) / 100;
                                                            $TotalLastColumnFinal = $TotalLatsColumn + $itemTotalWithDiscount;
                                                        
                                                            // Calculate discount amount
                                                            $discountAmount = ($itemTotal * $packageServiceItem->orderItem->discount) / 100;
                                                        
                                                            // new calcultae tax
                                                            $testttt = ($packageServiceItem->orderItem->tax * $itemTotal) / 100;
                                                            // endnew calcultae tax
                                                        
                                                            // Tax amount for percentage discount (tax on original total)
                                                            $taxAmount = $TotalLatsColumn;
                                                        } else {
                                                            $itemTotalWithDiscount = $itemTotal - $packageServiceItem->orderItem->discount;
                                                            if ($itemTotalWithDiscount < 0) {
                                                                $itemTotalWithDiscount = 0;
                                                            }
                                                            $TypeDiscount = '';
                                                            $TypeDiscountDollar = '$';
                                                            $TotalLatsCol = $itemTotalWithDiscount * ($packageServiceItem->orderItem->tax / 100);
                                                        
                                                            $TotalLatsColumn = $TotalLatsCol + $itemTotalWithDiscount;
                                                        
                                                            // Discount amount for dollar type
                                                            $discountAmount = $packageServiceItem->orderItem->discount;
                                                        
                                                            // Tax amount for dollar discount (tax on discounted amount)
                                                            $taxAmount = $TotalLatsCol;
                                                        
                                                            // new calcultae tax
                                                            $testttt = ($packageServiceItem->orderItem->tax * $itemTotal) / 100;
                                                            // endnew calcultae tax
                                                            $TotalLastColumnFinal = $itemTotalWithDiscount + $testttt;
                                                        }
                                                        
                                                        // Add to totals only if not deleted
                                                        if (!$isDeleted) {
                                                            $totalTax += $testttt;
                                                            $totalDiscount += $discountAmount;
                                                            $grandTotal += $TotalLastColumnFinal;
                                                        }
                                                        
                                                        ?>
                                                        <tr class="cart-item-row {{ $itemIndex >= 1 ? 'hidden-item' : '' }} {{ $isDeleted ? 'deleted-item-row' : '' }}"
                                                            data-service="{{ $serviceId }}"
                                                            style="{{ $isDeleted ? 'border: 2px solid red !important;' : '' }}">
                                                            <td class="number">
                                                                <div
                                                                    style="display: flex; align-items: center; gap: 8px;">
                                                                    <span>{{ $packageServiceItem->orderItem->code }}</span>
                                                                    @if (!$isDeleted)
                                                                        <button
                                                                            class="btn-delete-cart-item delete-item-icon"
                                                                            style="display: none; background: none; border: none; color: #dc3545; cursor: pointer; padding: 4px 8px; font-size: 16px; transition: all 0.3s ease;"
                                                                            data-item-id="{{ $packageServiceItem->id }}"
                                                                            data-cart-id="{{ $packageService->cart_id }}"
                                                                            data-service-id="{{ $packageService->service_id }}"
                                                                            data-package-service-id="{{ $packageService->id }}"
                                                                            title="Delete Item">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <strong
                                                                        style="{{ $isDeleted ? 'text-decoration: line-through; color: red;' : '' }}">{{ $packageServiceItem->orderItem->name }}</strong>
                                                                </div>
                                                                {{-- <div class="muted">-</div> --}}
                                                            </td>
                                                            <td class="number">{{ $packageServiceItem->quantity }}</td>
                                                            <td class="money">
                                                                {{ $packageServiceItem->orderItem->price }}

                                                                <br>
                                                                = {{ number_format($itemTotal, 2) }}

                                                            </td>



                                                            <td class="money">{{ number_format($itemTotal, 2) }}


                                                            </td>
                                                            <td class="money">
                                                                {{ $TypeDiscountDollar }}{{ $packageServiceItem->orderItem->discount }}{{ $TypeDiscount }}
                                                                <br>
                                                                =
                                                                {{ number_format($itemTotalWithDiscount, 2) }}
                                                            </td>
                                                            <td class="money">
                                                                {{ number_format($itemTotalWithDiscount, 2) }}
                                                            </td>
                                                            <td class="money">{{ number_format($testttt, 2) }}</td>
                                                            <td class="muted">
                                                                {{ $packageServiceItem->orderItem->tax }}%
                                                                <br>
                                                                = {{ number_format($testttt, 2) }}

                                                            </td>
                                                            <td class="money">
                                                                <span
                                                                    style="{{ $isDeleted ? 'text-decoration: line-through; color: red;' : '' }}">{{ number_format($TotalLastColumnFinal, 2) }}</span>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $itemIndex++;
                                                        @endphp
                                                    @endforeach
                                                    {{-- Custom Package Items --}}
                                                    @if ($packageService->customePackageItems && $packageService->customePackageItems->count() > 0)
                                                        @foreach ($packageService->customePackageItems as $customePackageItem)
                                                            @if ($customePackageItem->orderItem)
                                                                <?php
                                                                // Calculate for custom package item (quantity = 1)
                                                                $customQuantity = 1;
                                                                $customItemTotal = $customQuantity * $customePackageItem->orderItem->price;
                                                                
                                                                // Add to subtotal
                                                                $subtotal += $customItemTotal;
                                                                
                                                                // Check discount type
                                                                if ($customePackageItem->orderItem->discount_type == '%') {
                                                                    $customTypeDiscount = '%';
                                                                    $customTypeDiscountDollar = '';
                                                                    $customItemTotalWithDiscount = $customItemTotal - ($customItemTotal * $customePackageItem->orderItem->discount) / 100;
                                                                    if ($customItemTotalWithDiscount < 0) {
                                                                        $customItemTotalWithDiscount = 0;
                                                                    }
                                                                    $customTotalLatsColumn = ($customePackageItem->orderItem->tax * $customItemTotal) / 100;
                                                                    $customTotalLastColumnFinal = $customTotalLatsColumn + $customItemTotalWithDiscount;
                                                                    $customDiscountAmount = ($customItemTotal * $customePackageItem->orderItem->discount) / 100;
                                                                    $customTestttt = ($customePackageItem->orderItem->tax * $customItemTotal) / 100;
                                                                } else {
                                                                    $customItemTotalWithDiscount = $customItemTotal - $customePackageItem->orderItem->discount;
                                                                    if ($customItemTotalWithDiscount < 0) {
                                                                        $customItemTotalWithDiscount = 0;
                                                                    }
                                                                    $customTypeDiscount = '';
                                                                    $customTypeDiscountDollar = '$';
                                                                    $customTotalLatsCol = $customItemTotalWithDiscount * ($customePackageItem->orderItem->tax / 100);
                                                                    $customTotalLatsColumn = $customTotalLatsCol + $customItemTotalWithDiscount;
                                                                    $customDiscountAmount = $customePackageItem->orderItem->discount;
                                                                    $customTestttt = ($customePackageItem->orderItem->tax * $customItemTotal) / 100;
                                                                    $customTotalLastColumnFinal = $customItemTotalWithDiscount + $customTestttt;
                                                                }
                                                                
                                                                // Add to totals
                                                                $totalTax += $customTestttt;
                                                                $totalDiscount += $customDiscountAmount;
                                                                $grandTotal += $customTotalLastColumnFinal;
                                                                ?>
                                                                <tr class="cart-item-row custom-package-item-row {{ $itemIndex >= 1 ? 'hidden-item' : '' }}"
                                                                    data-service="{{ $serviceId }}"
                                                                    style="border: 2px solid #28a745 !important;">
                                                                    <td class="number">
                                                                        <div
                                                                            style="display: flex; align-items: center; gap: 8px;">
                                                                            <span>{{ $customePackageItem->orderItem->code }}</span>
                                                                            <button
                                                                                class="btn-remove-added-item delete-item-icon"
                                                                                style="display: none; background: none; border: none; color: #dc3545; cursor: pointer; padding: 4px 8px; font-size: 16px; transition: all 0.3s ease;"
                                                                                data-item-id="{{ $customePackageItem->id }}"
                                                                                data-cart-id="{{ $packageService->cart_id }}"
                                                                                data-service-id="{{ $packageService->service_id }}"
                                                                                data-package-service-id="{{ $packageService->id }}"
                                                                                title="Remove Item">
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div>
                                                                            <strong>{{ $customePackageItem->orderItem->name }}</strong>
                                                                        </div>
                                                                    </td>
                                                                    <td class="number">{{ $customQuantity }}</td>
                                                                    <td class="money">
                                                                        {{ $customePackageItem->orderItem->price }}
                                                                        <br>
                                                                        = {{ number_format($customItemTotal, 2) }}
                                                                    </td>
                                                                    <td class="money">
                                                                        {{ number_format($customItemTotal, 2) }}</td>
                                                                    <td class="money">
                                                                        {{ $customTypeDiscountDollar }}{{ $customePackageItem->orderItem->discount }}{{ $customTypeDiscount }}
                                                                        <br>
                                                                        =
                                                                        {{ number_format($customItemTotalWithDiscount, 2) }}
                                                                    </td>
                                                                    <td class="money">
                                                                        {{ number_format($customItemTotalWithDiscount, 2) }}
                                                                    </td>
                                                                    <td class="money">
                                                                        {{ number_format($customTestttt, 2) }}</td>
                                                                    <td class="muted">
                                                                        {{ $customePackageItem->orderItem->tax }}%
                                                                        <br>
                                                                        = {{ number_format($customTestttt, 2) }}
                                                                    </td>
                                                                    <td class="money">
                                                                        <span>{{ number_format($customTotalLastColumnFinal, 2) }}</span>
                                                                    </td>
                                                                </tr>
                                                                @php
                                                                    $itemIndex++;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    @if ($totalItems > 3)
                                                        <tr class="show-more-row" data-service="{{ $serviceId }}">
                                                            <td colspan="10"
                                                                style="text-align: center; padding: 6px; background-color: #f8f9fa; border-top: 2px solid #e5e7eb;">
                                                                <div
                                                                    style="display: flex; justify-content: space-between; align-items: center; gap: 15px;">
                                                                    <button class="btn-edit-cart-items"
                                                                        data-service="{{ $serviceId }}"
                                                                        style="background: #f8f9fa; color: #000; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease;">
                                                                        <i class="fa fa-edit"></i>
                                                                        <span class="edit-text">Edit</span>
                                                                        <span class="cancel-text"
                                                                            style="display: none;">Cancel</span>
                                                                    </button>
                                                                    <button class="btn-show-more"
                                                                        data-service="{{ $serviceId }}">
                                                                        <span class="show-more-text">Show More
                                                                            ({{ $totalItems - 3 }} more items)
                                                                        </span>
                                                                        <span class="show-less-text"
                                                                            style="display: none;">Show Less</span>
                                                                        <i
                                                                            class="fa fa-chevron-down show-more-icon"></i>
                                                                        <i class="fa fa-chevron-up show-less-icon"
                                                                            style="display: none;"></i>
                                                                    </button>
                                                                    <p></p>

                                                                </div>
                                                                {{-- add new item button --}}
                                                                <div class="text-left mt-2 " style="display: none;">
                                                                    <button class="btn-add-new-item"
                                                                        data-cart-service-id="{{ $packageService->id }}"
                                                                        data-service="{{ $serviceId }}"
                                                                        data-cart-id="{{ $packageService->cart_id }}"
                                                                        data-package-service-id="{{ $packageService->package_service_id }}"
                                                                        style="background: #28a745; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-size: 14px; display: inline-flex; align-items: center; gap: 6px; transition: all 0.3s ease;">
                                                                        <i class="fa fa-plus"></i> Add Item
                                                                    </button>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="quote-footer">
                                        <div class="note">
                                            Thank you for your business. Prices in USD. This quote is valid for 30 days
                                            from
                                            the
                                            issued date.
                                        </div>
                                        <div class="totals">
                                            <table>
                                                <tr>
                                                    <td class="label">Subtotal</td>
                                                    <td class="value">${{ number_format($subtotal, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="label">Tax & Fee</td>
                                                    <td class="value">${{ number_format($totalTax, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="label">Discount</td>
                                                    <td class="value">${{ number_format($totalDiscount, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="label">Total</td>
                                                    <td class="value">${{ number_format($grandTotal, 2) }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        {{-- signature --}}
                                        <div class="signature-container">
                                            <div class="signature-row">
                                                <div class="title-signature">Client
                                                    Sign. </div>
                                                <div class="signature-input-wrapper">

                                                    <div class="signature-input-border">
                                                        <input type="text"
                                                            class="w-100 input-signature signature-besmani-formal"
                                                            value="{{ $mainUser->fl_name }} {{ $mainUser->last_name }}">
                                                    </div>
                                                </div>
                                                <div class="title-signature">Date:</div>
                                                <div class="signature-date-wrapper">

                                                    <div class="signature-input-border">
                                                        <input type="text"
                                                            class="w-100 input-signature date-signature"
                                                            value="{{ \Carbon\Carbon::today()->format('m/d/Y') }}">

                                                    </div>
                                                </div>

                                            </div>
                                            {{-- besmani signature --}}
                                            <div class="signature-row signature-row-second">
                                                <div class="title-signature">
                                                    {{-- font cursive --}}
                                                    Besmani

                                                    Sign.</div>
                                                <div class="signature-input-wrapper">

                                                    <div class="signature-input-border">
                                                        <input type="text"
                                                            class="w-100 input-signature signature-besmani-formal"
                                                            value="Besmani">

                                                    </div>
                                                </div>
                                                <div class="title-signature">Date:</div>
                                                <div class="signature-date-wrapper">

                                                    <div class="signature-input-border">
                                                        <input type="text"
                                                            class="w-100 input-signature date-signature"
                                                            value="{{ \Carbon\Carbon::today()->format('m/d/Y') }}">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>




                                    </div>
                                    {{-- footer --}}

                                    {{-- operate btn --}}
                                    <div
                                        class="table-operate-btn text-left text-md-left submit-button-wrapper felx justify-content-between">
                                        <button class="btn-green btn-order-submit-pay go-pay">Pay

                                            <i class="fa fa-spinner fa-spin"
                                                style="display: none; margin-left: 8px;"></i>
                                        </button>
                                        <button class="btn-green btn-order-submit-yellow go-pdf-download">Download pdf

                                            <i class="fa fa-spinner fa-spin"
                                                style="display: none; margin-left: 8px;"></i>
                                        </button>
                                        <button class="btn-green delete-button delete-cart-item"
                                            onclick="deleteCartItem({{ $packageService->id }},{{ $packageService->cart_id }})">Delete

                                            <i class="fa fa-spinner fa-spin"
                                                style="display: none; margin-left: 8px;"></i>
                                        </button>
                                    </div>
                                    {{-- operate btn end --}}

                                </div>
                        @endforeach
                    </div>




                </div>
            </div>
            {{-- main-cart-container end --}}
        @else
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="empty-cart-container">
                            <div class="empty-cart-icon">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                            <h2 class="empty-cart-title">Your Cart is Empty!</h2>
                            <p class="empty-cart-message">Looks like you haven't added anything to your cart yet.
                            </p>
                            <p class="empty-cart-submessage">Start shopping to add items to your cart.</p>
                            <a href="{{ config('app.url') }}services" class="btn-empty-cart-shop">
                                <i class="fa fa-arrow-left"></i>
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        <!-- pay Modal -->
        <div id="description-modal" class="description-modal-overlay" style="display: none;">
            <div class="description-modal-container">
                <button class="description-modal-close" onclick="closeDescriptionModal()">×</button>
                <div class="description-modal-header">
                    <div class="description-modal-title" id="description-modal-title">Pay


                        <strong class="text-green"
                            style="color: #47da47;">${{ number_format($grandTotal ?? ($grandGrandTotal ?? 0), 2) }}
                        </strong>

                    </div>
                </div>
                <div class="description-modal-body">
                    <div class=" modal-form-pay">
                        <form id="payment-form" class="payment-form">
                            <div class="row-item-amount-date ">
                                <div class="form-group">
                                    <label for="payment-amount">Amount</label>
                                    <input type="text" id="payment-amount" name="amount[]"
                                        class="form-control amount-input" placeholder="Enter amount" step="0.01"
                                        min="0" required>
                                </div>
                                <div class="form-group">
                                    <label for="payment-date">Date</label>
                                    <input type="date" id="payment-date" name="date[]" class="form-control"
                                        placeholder="Enter date" required>
                                </div>
                            </div>
                            <br>
                            {{-- row-item-amount-date --}}
                            <div class="row-item-amount-date ">
                                <div class="form-group">
                                    <label for="payment-amount">Amount</label>
                                    <input type="text" id="payment-amount" name="amount[]"
                                        class="form-control amount-input" placeholder="Enter amount" step="0.01"
                                        min="0">
                                </div>
                                <div class="form-group">
                                    <label for="payment-date">Date</label>
                                    <input type="date" id="payment-date" name="date[]" class="form-control"
                                        placeholder="Enter date">
                                </div>
                            </div>
                            {{-- row-item-amount-date --}}
                            <br>
                            <div class="row-item-amount-date ">
                                <div class="form-group">
                                    <label for="payment-amount">Amount</label>
                                    <input type="text" id="payment-amount" name="amount[]"
                                        class="form-control amount-input amount-f" placeholder="Enter amount"
                                        step="0.01" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="payment-date">Date</label>
                                    <input type="date" id="payment-date" name="date[]"
                                        class="form-control date-f" placeholder="Enter date">
                                </div>
                            </div>
                            {{-- row-item-amount-date --}}
                            <br>
                            <div class="row-item-amount-date ">
                                <div class="form-group">
                                    <label for="payment-amount">Amount</label>
                                    <input type="text" id="payment-amount" name="amount[]"
                                        class="form-control amount-input" placeholder="Enter amount" step="0.01"
                                        min="0">
                                </div>
                                <div class="form-group">
                                    <label for="payment-date">Date</label>
                                    <input type="date" id="payment-date" name="date[]" class="form-control"
                                        placeholder="Enter date">
                                </div>
                            </div>
                            {{-- row-item-amount-date --}}
                            <br>
                            <br>
                            <div class="form-group mt-3 flex justify-content-between">

                                <button type="submit" class="btn-green btn-order-submit-pay  goCheckoutPay">Submit

                                    <i class="fa fa-spinner fa-spin" style="display: none; margin-left: 8px;"></i>
                                </button>
                                <div style="line-height: 38px;
  font-size: 17px;">
                                    <b>Balance: </b> <b id="payment-balance" class="text-green"
                                        style="color: #ea1617;">${{ number_format($grandGrandTotal ?? 0, 2) }}</b>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <!-- pay Modal end -->

        <style>
            .payment-form .row-item-amount-date {
                display: flex;
                gap: 10px;
            }

            .justify-content-between {
                display: flex;
                justify-content: space-between;
            }

            .payment-form .error-field {
                border: 2px solid #dc3545 !important;
                background-color: #fff5f5 !important;
            }

            /* Deleted item row style */
            .deleted-item-row {
                border: 2px solid red !important;
                opacity: 0.7;
            }

            .deleted-item-row td {
                background-color: #fff5f5 !important;
            }

            .deleted-item-row td strong,
            .deleted-item-row td.money span {
                text-decoration: line-through !important;
                color: red !important;
            }

            /* Custom package item row style */
            .custom-package-item-row {
                border: 2px solid #28a745 !important;
            }

            .custom-package-item-row td {
                background-color: #f0fff4 !important;
            }

            /* Edit button and delete icon styles for cart */
            .btn-edit-cart-items {
                transition: all 0.3s ease;
            }

            .btn-edit-cart-items:hover {
                opacity: 0.9;
                transform: translateY(-1px);
            }

            .delete-item-icon:hover {
                color: #c82333 !important;
                transform: scale(1.1);
            }

            /* Add Item button styles */
            .btn-add-new-item {
                transition: all 0.3s ease;
            }

            .btn-add-new-item:hover {
                opacity: 0.9;
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }

            .added-item-row {
                animation: fadeIn 0.3s ease-in;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>

        <!-- Add Item Modal -->
        <div id="add-item-modal" class="description-modal-overlay" style="display: none;">
            <div class="description-modal-container" style="max-width: 600px;">
                <button class="description-modal-close" onclick="closeAddItemModal()">×</button>
                <div class="description-modal-header">
                    <div class="description-modal-title">Add New Item</div>
                </div>
                <div class="description-modal-body">
                    <form id="add-item-form">
                        <div class="form-group">
                            <label for="order-item-select">Select Item</label>
                            <select id="order-item-select" name="order_item_id" class="form-control" required>
                                <option value="">-- Select an item --</option>
                            </select>
                        </div>
                        <input type="hidden" id="add-item-cart-id" name="cart_id">
                        <input type="hidden" id="add-item-package-service-id" name="package_service_id">
                        <input type="hidden" id="add-item-service-id" name="service_id">
                        <input type="hidden" id="add-item-cart-service-id" name="cart_service_id">
                        <br>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn-green btn-order-submit-pay">
                                Submit
                                <i class="fa fa-spinner fa-spin" style="display: none; margin-left: 8px;"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Add Item Modal end -->

    </section>



    <script>
        var cart_id = '{{ $cartInfo->id ?? 0 }}';
        var service_idd = '{{ $packageService->serviceInfo->id ?? 0 }}';
        var package_service_idd = '{{ $packageService->id ?? 0 }}';
        //    go-pay
        var TaxFee = '{{ $grandTotalTax ?? 0 }}';
        var subtotal = '{{ $grandGrandTotal ?? 0 }}';
        var discount = '{{ $grandTotalDiscount ?? 0 }}';
        var totalAmount = '{{ $grandGrandTotal ?? 0 }}';
        $('body').on('click', '.go-pay, .btn-checkout-pay', function() {
            var $button = $(this);
            var $spinner = $button.find('.fa-spinner');

            // Show loading spinner
            $spinner.show();
            $button.prop('disabled', true);
            $('.go-pay, .btn-checkout-pay').prop('disabled', true);

            $.ajax({
                url: '{{ route('payCart') }}',
                type: 'POST',
                data: {
                    subtotal: subtotal,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('.fa-spinner').hide();
                    $('.go-pay, .btn-checkout-pay').prop('disabled', false);

                    // Show success modal
                    if (response.success) {
                        if (typeof openCartSuccessModal === 'function') {
                            openCartSuccessModal();
                        } else {
                            alert('Payment successful!');
                        }
                    }
                },
                error: function(response) {
                    $('.fa-spinner').hide();
                    $('.go-pay, .btn-checkout-pay').prop('disabled', false);

                    // Show error message
                    alert('An error occurred. Please try again.');
                }
            });

        });

        // Show More/Less functionality
        $(document).ready(function() {
            $('body').on('click', '.btn-show-more', function() {
                var serviceId = $(this).data('service');
                var $button = $(this);
                var $hiddenItems = $('.hidden-item[data-service="' + serviceId + '"]');

                if ($button.hasClass('active')) {
                    // Hide items
                    $hiddenItems.each(function(index) {
                        var $item = $(this);
                        setTimeout(function() {
                            $item.removeClass('show');
                        }, index * 30); // Stagger the animation
                    });
                    $button.removeClass('active');
                } else {
                    // Show items
                    $hiddenItems.each(function(index) {
                        var $item = $(this);
                        setTimeout(function() {
                            $item.addClass('show');
                        }, index * 30); // Stagger the animation
                    });
                    $button.addClass('active');
                }
            });
        });
        // Show More/Less functionality

        // go-pdf-download
        // PDF Download functionality
        $('body').on('click', '.go-pdf-download', function() {
            var $button = $(this);
            var $spinner = $button.find('.fa-spinner');

            // Show loading spinner
            $spinner.show();
            $button.prop('disabled', true);

            // Show all hidden items before printing
            $('.hidden-item').addClass('show');

            $.ajax({
                url: '{{ route('cart.downloadPdf') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $spinner.hide();
                    $button.prop('disabled', false);

                    if (response.success) {
                        // Use browser's print dialog (which can save as PDF)
                        setTimeout(function() {
                            window.print();
                        }, 500);
                    } else {
                        alert(response.message || 'An error occurred while generating PDF.');
                    }
                },
                error: function(response) {
                    $spinner.hide();
                    $button.prop('disabled', false);

                    // Still allow print even if AJAX fails
                    setTimeout(function() {
                        window.print();
                    }, 500);
                }
            });
        });

        // Handle scroll behavior for fixed checkout
        $(document).ready(function() {
            // Add padding to body to prevent content from being hidden
            $('body').css('padding-bottom', '120px');

            // Show/hide fixed checkout on scroll (optional - can be removed if always visible)
            var lastScrollTop = 0;
            $(window).scroll(function() {
                var scrollTop = $(this).scrollTop();
                var $checkout = $('.pay-total-cart-fixed');

                // Always show on scroll down, hide on scroll up (optional)
                // Uncomment below if you want it to hide on scroll up
                /*
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // Scrolling down
                    $checkout.removeClass('hidden');
                } else {
                    // Scrolling up
                    if (scrollTop < 50) {
                        $checkout.removeClass('hidden');
                    }
                }
                */
                lastScrollTop = scrollTop;
            });
        });




        // delete cart item
        function deleteCartItem(id, cart_id, service_id) {

            // alert confirm
            if (confirm('Are you sure you want to delete this item?')) {
                $.ajax({
                    url: '{{ route('deleteCartItem') }}',
                    type: 'POST',
                    data: {
                        id: id,
                        cart_id: cart_id,

                    },
                    success: function(response) {
                        window.location.href = '{{ route('cart') }}';
                    },
                    error: function(response) {
                        alert('An error occurred. Please try again.');
                    }
                });
            }
        }

        // pay Modal Functions


        // goPayAllNow
        $('body').on('click', '.goPayAllNow', function(e) {
            openDescriptionModal();
            // Initialize balance and button state
            setTimeout(function() {
                calculateBalance();
            }, 100);
        });


        // allPayNow
        $('body').on('click', '.goCheckoutPay', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var ContactName = $('.ContactNameEdit-{{ $packageService->id ?? 0 }} ?: ""').val();
            var BillingAddress = $('.BillingAddressEdit-{{ $packageService->id ?? 0 }} ?: ""').val();
            var ShipingAddress = $('.ShipingAddressEdit-{{ $packageService->id ?? 0 }} ?: ""').val();

            // Get the form element
            var $form = $(this).closest('form');

            // Get first row fields (first occurrence of amount[] and date[])
            var $firstAmount = $form.find('input[name="amount[]"]').first();
            var $firstDate = $form.find('input[name="date[]"]').first();

            // Validate first row - check if required fields are filled
            var firstAmountValue = $firstAmount.val().trim();
            var firstDateValue = $firstDate.val().trim();

            // Remove previous error styling
            $firstAmount.removeClass('error-field');
            $firstDate.removeClass('error-field');

            // Validate first row
            var isValid = true;
            if (!firstAmountValue) {
                $firstAmount.addClass('error-field');
                $firstAmount.focus();
                isValid = false;
            } else if (isNaN(firstAmountValue) || parseFloat(firstAmountValue) < 0) {
                $firstAmount.addClass('error-field');
                $firstAmount.focus();
                isValid = false;
            } else if (!firstDateValue) {
                $firstDate.addClass('error-field');
                $firstDate.focus();
                isValid = false;
            }

            // If validation fails, stop here
            if (!isValid) {
                return false;
            }

            // get all amount and date in pairs (matching by row)
            var amount = [];
            var date = [];
            $form.find('.row-item-amount-date').each(function() {
                var $row = $(this);
                var amountVal = $row.find('input[name="amount[]"]').val();
                var dateVal = $row.find('input[name="date[]"]').val();

                // Only add if at least amount is provided (first row is required)
                if (amountVal || dateVal) {
                    amount.push(amountVal || '');
                    date.push(dateVal || '');
                }
            });
            var $button = $(this);
            var $spinner = $button.find('.fa-spinner');

            // Show loading spinner
            $spinner.show();
            $button.prop('disabled', true);
            $('.goCheckoutPay').prop('disabled', true);
            $.ajax({
                url: '{{ route('goPayAll') }}',
                type: 'POST',
                data: {
                    subtotal: subtotal,
                    TaxFee: TaxFee,
                    discount: discount,
                    amount: amount,
                    date: date,
                    cart_id: cart_id,
                    ContactName: ContactName,
                    BillingAddress: BillingAddress,
                    ShipingAddress: ShipingAddress,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('.fa-spinner').hide();
                    $('.goCheckoutPay').prop('disabled', false);

                    // Show success modal
                    if (response.success) {
                        if (typeof openCartSuccessModal === 'function') {
                            openCartSuccessModal();
                        } else {
                            window.location.href = '{{ config('app.url') }}panel';

                        }
                    }
                },
                error: function(response) {
                    $('.fa-spinner').hide();
                    $('.goCheckoutPay').prop('disabled', false);

                    // Show error message
                    alert('An error occurred. Please try again.');
                }
            });
        });



        // Description Modal Functions - Global scope
        function openDescriptionModal(modalId, title, description) {
            // $('#description-modal-title').text(title);
            // $('#description-modal-content').text(description);
            $('#description-modal').fadeIn(300);
            $('body').css('overflow', 'hidden');
            // Calculate balance when modal opens (with slight delay to ensure DOM is ready)
            setTimeout(function() {
                calculateBalance();
            }, 100);
        }

        function closeDescriptionModal() {
            $('#description-modal').fadeOut(300);
            $('body').css('overflow', 'auto');
        }

        // Calculate balance and update button state
        function calculateBalance() {
            var totalEntered = 0;
            var $form = $('#payment-form');

            // Check if form exists (modal might not be open)
            if ($form.length === 0) {
                return;
            }

            // Sum all entered amounts
            $form.find('input[name="amount[]"]').each(function() {
                var amountVal = parseFloat($(this).val()) || 0;
                totalEntered += amountVal;
            });

            // Calculate balance
            var balance = totalAmount - totalEntered;

            // Update balance display
            var $balanceSpan = $('#payment-balance');
            if ($balanceSpan.length > 0) {
                if (balance < 0) {
                    $balanceSpan.text('-$' + Math.abs(balance).toFixed(2));
                    $balanceSpan.css('color', 'green'); // Red for negative
                } else {
                    $balanceSpan.text('$' + balance.toFixed(2));
                    $balanceSpan.css('color', 'red'); // Green for positive or zero
                }
            }

            // Enable/disable submit button based on balance
            var $submitButton = $('.goCheckoutPay');
            if ($submitButton.length > 0) {
                if (balance == 0) {
                    // Enable button when balance is exactly 0 (all payments entered)
                    $submitButton.prop('disabled', false);
                    $submitButton.css('opacity', '1');
                    $submitButton.css('cursor', 'pointer');
                } else {
                    // Disable button when balance is not 0 (still owes or overpaid)
                    $submitButton.prop('disabled', true);
                    $submitButton.css('opacity', '0.6');
                    $submitButton.css('cursor', 'not-allowed');
                }
            }
        }

        // Restrict amount input to numbers only
        $(document).ready(function() {
            // Prevent non-numeric characters in amount fields
            $('body').on('keypress', '.amount-input', function(e) {
                // Allow: backspace, delete, tab, escape, enter, decimal point
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                    // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                    (e.keyCode === 65 && e.ctrlKey === true) ||
                    (e.keyCode === 67 && e.ctrlKey === true) ||
                    (e.keyCode === 86 && e.ctrlKey === true) ||
                    (e.keyCode === 88 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode >
                        105)) {
                    e.preventDefault();
                }
            });

            // Also validate on paste
            $('body').on('paste', '.amount-input', function(e) {
                var pastedData = (e.originalEvent || e).clipboardData.getData('text/plain');
                // Remove non-numeric characters except decimal point
                var cleaned = pastedData.replace(/[^0-9.]/g, '');
                // Only allow one decimal point
                var parts = cleaned.split('.');
                if (parts.length > 2) {
                    cleaned = parts[0] + '.' + parts.slice(1).join('');
                }
                $(this).val(cleaned);
                e.preventDefault();
            });

            // Validate on input to ensure only numbers and one decimal point
            $('body').on('input', '.amount-input', function() {
                var value = $(this).val();
                // Remove any non-numeric characters except decimal point
                value = value.replace(/[^0-9.]/g, '');
                // Ensure only one decimal point
                var parts = value.split('.');
                if (parts.length > 2) {
                    value = parts[0] + '.' + parts.slice(1).join('');
                }
                $(this).val(value);
                // Recalculate balance when amount changes
                calculateBalance();
            });

            // Also recalculate on blur (when user leaves the field)
            $('body').on('blur', '.amount-input', function() {
                calculateBalance();
            });
        });

        // Edit button functionality - toggle delete icons in cart
        $(document).on('click', '.btn-edit-cart-items', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var $button = $(this);
            var serviceId = $button.data('service');
            var $deleteIcons = $('.delete-item-icon[data-service="' + serviceId + '"]');
            var $addItemBtn = $('.btn-add-new-item[data-service="' + serviceId + '"]').closest('.text-left');

            // If no service-specific icons, get all delete icons
            if ($deleteIcons.length === 0) {
                $deleteIcons = $('.delete-item-icon');
            }

            if ($button.hasClass('active')) {
                // Hide delete icons and add item button
                $deleteIcons.fadeOut(200);
                $addItemBtn.fadeOut(200);
                $button.removeClass('active');
                $button.find('.edit-text').show();
                $button.find('.cancel-text').hide();
                // $button.css('background', '#2563eb');
            } else {
                // Show delete icons and add item button
                $deleteIcons.fadeIn(200);
                $addItemBtn.fadeIn(200);
                $button.addClass('active');
                $button.find('.edit-text').hide();
                $button.find('.cancel-text').show();
                // $button.css('background', '#dc3545');
            }
        });

        // Delete cart item icon functionality (for regular items - uses createCustomDeleteItem route)
        $(document).on('click', '.delete-item-icon:not(.btn-remove-added-item)', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Check if this is a custom package item button
            if ($(this).hasClass('btn-remove-added-item')) {
                return; // Let the btn-remove-added-item handler handle it
            }

            var itemId = $(this).data('item-id');
            var cartId = $(this).data('cart-id');
            var serviceId = $(this).data('service-id');
            var packageServiceId = $(this).data('package-service-id');
            if (confirm('Are you sure you want to delete this item?')) {
                createCustomDeleteItem(itemId, cartId, serviceId, packageServiceId);
            }
        });

        // function create customeDeleteItem
        function createCustomDeleteItem(itemId, cartId, serviceId, packageServiceId) {
            $.ajax({
                url: '{{ route('createCustomDeleteItem') }}',
                type: 'POST',
                data: {
                    id: itemId,
                    cart_id: cartId,
                    service_id: serviceId,
                    package_service_id: packageServiceId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = '{{ route('cart') }}';
                    } else {
                        alert(response.message || 'An error occurred. Please try again.');
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    var errorMessage = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    alert(errorMessage);
                }
            });
        }

        // Remove custom package item handler (uses deleteCustomPackageItem route)
        $(document).on('click', '.btn-remove-added-item', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var itemId = $(this).data('item-id');
            if (confirm('Are you sure you want to remove this item?')) {
                $.ajax({
                    url: '{{ route('deleteCustomPackageItem') }}',
                    type: 'POST',
                    data: {
                        id: itemId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = '{{ route('cart') }}';
                        } else {
                            alert(response.message || 'An error occurred. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        var errorMessage = 'An error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    }
                });
            }
        });

        // Add Item Modal Functions
        function openAddItemModal(serviceId, cartId, packageServiceId, cartServiceId) {
            $('#add-item-service-id').val(serviceId);
            $('#add-item-cart-id').val(cartId);
            $('#add-item-package-service-id').val(packageServiceId);
            $('#add-item-cart-service-id').val(cartServiceId);
            // Load order items
            $.ajax({
                url: '{{ route('getOrderItems') }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        var $select = $('#order-item-select');
                        $select.empty().append('<option value="">-- Select an item --</option>');
                        $.each(response.orderItems, function(index, item) {
                            $select.append('<option value="' + item.id + '">' + item.code + ' - ' + item
                                .name + ' ($' + parseFloat(item.price).toFixed(2) + ')</option>');
                        });
                    }
                },
                error: function() {
                    alert('Error loading items. Please try again.');
                }
            });

            $('#add-item-modal').fadeIn(300);
            $('body').css('overflow', 'hidden');
        }

        function closeAddItemModal() {
            $('#add-item-modal').fadeOut(300);
            $('body').css('overflow', 'auto');
            $('#add-item-form')[0].reset();
        }

        // Handle Add Item button click
        $(document).on('click', '.btn-add-new-item', function(e) {
            e.preventDefault();
            var serviceId = $(this).data('service');
            var cartId = $(this).data('cart-id') || '{{ $cartInfo->id ?? 0 }}';
            var packageServiceId = $(this).data('package-service-id');
            var cartServiceId = $(this).data('cart-service-id');

            openAddItemModal(serviceId, cartId, packageServiceId, cartServiceId);
        });

        // Handle Add Item form submission
        $(document).on('submit', '#add-item-form', function(e) {
            e.preventDefault();
            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');
            var $spinner = $submitBtn.find('.fa-spinner');

            $spinner.show();
            $submitBtn.prop('disabled', true);


            $.ajax({
                url: '{{ route('createCustomPackageItem') }}',
                type: 'POST',
                data: {
                    order_item_id: $('#order-item-select').val(),
                    cart_id: $('#add-item-cart-id').val(),
                    package_service_id: $('#add-item-package-service-id').val(),
                    cart_service_id: $('#add-item-cart-service-id').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $spinner.hide();
                    $submitBtn.prop('disabled', false);

                    if (response.success) {
                        // Add item to the list
                        //  var serviceId = $('#add-item-service-id').val();
                        //  var $itemsList = $('.items-list-add-here').filter(function() {
                        //      return $(this).closest('[data-service="' + serviceId + '"]').length > 0 || 
                        //             $(this).closest('.show-more-row[data-service="' + serviceId + '"]').length > 0;
                        //  });

                        //  var itemHtml = '<div class="added-item-row" data-item-id="' + response.item.id + '" style="padding: 8px; margin: 5px 0; background: #f0f0f0; border-radius: 4px; display: flex; justify-content: space-between; align-items: center;">' +
                        //      '<span><strong>' + response.item.code + '</strong> - ' + response.item.name + ' ($' + parseFloat(response.item.price).toFixed(2) + ')</span>' +
                        //      '<button class="btn-remove-added-item" data-item-id="' + response.item.id + '" style="background: #dc3545; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer;">Remove</button>' +
                        //      '</div>';

                        //  $itemsList.append(itemHtml);
                        closeAddItemModal();

                        // Reload page to show in table

                        window.location.reload();

                    } else {
                        alert(response.message || 'An error occurred. Please try again.');
                    }
                },
                error: function(xhr) {
                    $spinner.hide();
                    $submitBtn.prop('disabled', false);
                    var errorMessage = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    alert(errorMessage);
                }
            });
        });

        // Close modal on outside click
        $(document).on('click', '#add-item-modal', function(e) {
            if ($(e.target).hasClass('description-modal-overlay')) {
                closeAddItemModal();
            }
        });

        // Close modal on ESC key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('#add-item-modal').is(':visible')) {
                closeAddItemModal();
            }
        });
    </script>
