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
                               Checkout
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
                                               <textarea class="w-100 bg-none BillingAddressEdit-{{ $packageService->id }}">{{ $userInfo->address }} {{ $userInfo->city }} {{ $userInfo->province }} {{ $userInfo->postal_code }} {{ $userInfo->country_name }}</textarea> 
                                               
                                            </div>
                                        </div>
                                        <div class="addr-box">
                                            <div class="addr-title">Shipping Address</div>
                                            <div class="addr-body" style="text-align: left !important;">

                                                <textarea  class="w-100 bg-none ShipingAddressEdit-{{ $packageService->id }}">{{ $userInfo->address }} {{ $userInfo->city }} {{ $userInfo->province }} {{ $userInfo->postal_code }} {{ $userInfo->country_name }}  </textarea>

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
                                                        <th style="min-width:100px;">Discount</th>
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
                                                        
                                                        // Check if price is 0
                                                        $isPriceZero = ($packageServiceItem->orderItem->price == 0);
                                                        
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
                                                        <tr class="cart-item-row {{ $itemIndex >= 1 ? 'hidden-item' : '' }} {{ $isDeleted ? 'deleted-item-row' : '' }} {{ $isPriceZero ? 'price-zero-row' : '' }}"
                                                            data-service="{{ $serviceId }}"
                                                            style="{{ $isDeleted ? 'border: 2px solid red !important;' : '' }} {{ $isPriceZero ? 'display: none !important;' : '' }}">
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
                                                            <td class="money">
                                                                {{ $TypeDiscountDollar }}{{ $packageServiceItem->orderItem->discount }}{{ $TypeDiscount }}
                                                                <br>
                                                                =
                                                                {{ number_format($itemTotalWithDiscount, 2) }}
                                                            </td>
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
                                                                
                                                                // Check if price is 0
                                                                $customIsPriceZero = ($customePackageItem->orderItem->price == 0);
                                                                
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
                                                                <tr class="cart-item-row custom-package-item-row {{ $itemIndex >= 1 ? 'hidden-item' : '' }} {{ $customIsPriceZero ? 'price-zero-row' : '' }}"
                                                                    data-service="{{ $serviceId }}"
                                                                    style="border: 2px solid #28a745 !important; {{ $customIsPriceZero ? 'display: none !important;' : '' }}">
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
                                                                        {{ $customTypeDiscountDollar }}{{ $customePackageItem->orderItem->discount }}{{ $customTypeDiscount }}
                                                                        <br>
                                                                        =
                                                                        {{ number_format($customItemTotalWithDiscount, 2) }}
                                                                    </td>
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
                                                            <td colspan="7"
                                                                style="text-align: center; padding: 6px; background-color: #f8f9fa; border-top: 2px solid #e5e7eb;">
                                                                <div
                                                                    style="display: flex;  justify-content: space-between; align-items: center; gap: 15px;">
                                                                    <button class="btn-edit-cart-items"
                                                                        data-service="{{ $serviceId }}"
                                                                        style="   background: #fac9ac;
  color: #000;   border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;font-weight: bold; font-size: 14px; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease;">
                                                                        <i class="fa fa-edit"></i>
                                                                        <span class="edit-text">Edit</span>
                                                                        <span class="cancel-text"
                                                                            style="display: none;">Cancel</span>
                                                                    </button>
                                                                    <button class="btn-show-more"
                                                                        data-service="{{ $serviceId }}">
                                                                        <span class="show-more-text">Show More
                                                                            {{-- ({{ $totalItems - 3 }} more items) --}}
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
                                            Thank you for your business. Prices are in USD. This quote is valid for 30 days
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
                                                    Signature. </div>
                                                <div class="signature-input-wrapper">

                                                    <div class="signature-input-border">
                                                        <select class="w-100 input-signature signature-besmani-formal">
                                                            <option value="">select</option>
                                                            <option value="{{ $mainUser->fl_name }} {{ $mainUser->last_name }}">{{ $mainUser->fl_name }} {{ $mainUser->last_name }}</option>
                                                        </select>
                                                        {{-- <input type="text"
                                                            class="w-100 input-signature signature-besmani-formal"
                                                            value="{{ $mainUser->fl_name }} {{ $mainUser->last_name }}">
                                                   --}}
                                                  
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

                                                    Signature.</div>
                                                <div class="signature-input-wrapper">

                                                    <div class="signature-input-border">
                                                        <input type="text"
                                                        disabled
                                                            class="w-100 input-signature signature-besmani-formal"
                                                            value="Besmani">

                                                    </div>
                                                </div>
                                                <div class="title-signature">Date:</div>
                                                <div class="signature-date-wrapper">

                                                    <div class="signature-input-border">
                                                        <input type="text"
                                                        disabled
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


                        {{-- <strong class="text-green"
                            style="color: #47da47;">${{ number_format($grandTotal ?? ($grandGrandTotal ?? 0), 2) }}
                        </strong> --}}

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
                            
                            {{-- Payment Options Message and Radio Buttons --}}
                            <div class="payment-options-section" style="margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #e5e7eb;">
                                <p style="font-size: 14px; color: #333; margin-bottom: 15px; font-weight: 500;">
                                    Online payment is currently not available.<br>
                                    To complete your purchase, please choose one of the payment options below.
                                </p>
                                
                                <div class="payment-radio-group" style="margin-left: 10px;">
                                    <div class="payment-option" style="margin-bottom: 15px; padding: 12px; background-color: #ffffff; border-radius: 6px; border: 2px solid #e5e7eb; transition: all 0.3s ease;">
                                        <label style="display: flex; align-items: flex-start; cursor: pointer; font-size: 14px; color: #333; margin: 0;">
                                            <input type="radio" name="payment_method" value="phone" 
                                                style="margin-right: 12px; margin-top: 3px; cursor: pointer; width: 18px; height: 18px; accent-color: #10b981;">
                                            <span style="flex: 1;">
                                                <strong style="display: block; margin-bottom: 4px; color: #1f2937;">1. Pay by Phone <b class="text-green" style="color: #10b981;">[ Zelle:  +1(949)432-8383 ]</b></strong>
                                                <span style="color: #666; font-size: 13px; line-height: 1.4;">Call us to complete payment securely.</span>
                                            </span>
                                        </label>
                                    </div>
                                    
                                    <div class="payment-option" style="padding: 12px; background-color: #ffffff; border-radius: 6px; border: 2px solid #e5e7eb; transition: all 0.3s ease;">
                                        <label style="display: flex; align-items: flex-start; cursor: pointer; font-size: 14px; color: #333; margin: 0;">
                                            <input type="radio" name="payment_method" value="check" 
                                                style="margin-right: 12px; margin-top: 3px; cursor: pointer; width: 18px; height: 18px; accent-color: #10b981;">
                                            <span style="flex: 1;">
                                                <strong style="display: block; margin-bottom: 4px; color: #1f2937;">2. Pay by Check</strong>
                                                <span style="color: #666; font-size: 13px; line-height: 1.4;">Mail or deliver a check using the billing details provided.</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3 flex justify-content-between">

                                <button type="submit" class="btn-green btn-order-submit-pay  goCheckoutPay" disabled style="opacity: 0.6; cursor: not-allowed;">Submit

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

        <!-- Order Confirmation Modal -->
        <div id="order-confirmation-modal" class="description-modal-overlay" style="display: none;">
            <div class="description-modal-container">
                <button class="description-modal-close" onclick="closeOrderConfirmationModal()">×</button>
                <div class="description-modal-header">
                    <div class="description-modal-title">Order Confirmation</div>
                </div>
                <div class="description-modal-body">
                    <div style="text-align: center; padding: 20px;">
                        <div style="font-size: 48px; color: #10b981; margin-bottom: 20px;">
                            <i class="fa fa-check-circle"></i>
                        </div>
                        <h2 style="color: #1f2937; margin-bottom: 15px; font-size: 24px;">Thank you for your order.</h2>
                        <p style="color: #4b5563; font-size: 16px; margin-bottom: 10px;">
                            <strong>Confirmation:</strong> <span id="confirmation-number" style="color: #10b981; font-weight: bold; font-size: 18px;">######</span>
                        </p>
                        <strong>
                            You can view, edit, or track your order anytime from the Orders menu.
                        </strong>
                        <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin-top: 20px;">
                            Our team will confirm your payment and begin your project immediately.
                        </p>
                        <div style="margin-top: 30px;">
                            <button onclick="closeOrderConfirmationModal(); window.location.href='{{ config('app.url') }}panel';" 
                                class="btn-green btn-order-submit-pay" style="min-width: 150px;">
                                Go to Dashboard
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Order Confirmation Modal end -->

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

            /* Payment Radio Button Styles */
            .payment-option {
                position: relative;
            }

            .payment-option:hover {
                border-color: #10b981 !important;
                background-color: #f0fdf4 !important;
            }

            .payment-option input[type="radio"]:checked + span {
                color: #059669;
            }

            .payment-option.selected {
                border-color: #10b981 !important;
                background-color: #f0fdf4 !important;
                box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            }

            .payment-option label {
                width: 100%;
            }

            .payment-option input[type="radio"] {
                flex-shrink: 0;
            }

            /* Validation error styles for required fields */
            .ContactNameEdit.error-field,
            .BillingAddressEdit.error-field,
            .ShipingAddressEdit.error-field,
            .signature-besmani-formal.error-field,
            .date-signature.error-field {
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

            /* Hide rows with zero price */
            .price-zero-row {
                display: none !important;
            }

            /* Lighter colors for buttons */
            .btn-order-submit-pay {
                background-color: #6ee068 !important;
                opacity: 0.85;
                color: #000;
            }

            .btn-order-submit-pay:hover {
                background-color: #5dd357 !important;
                opacity: 0.9;
            }

            .btn-order-submit-yellow {
                background-color: #f9d085 !important;
                opacity: 0.85;
                color: #000;
            }

            .btn-order-submit-yellow:hover {
                background-color: #f8c266 !important;
                opacity: 0.9;
            }

            .delete-button {
                background-color: #f15c5d !important;
                opacity: 0.85;
                color: #000;
            }

            .delete-button:hover {
                background-color: #f04a4b !important;
                opacity: 0.9;
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
                border:2px solid #fa8d4d  !important;
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
            e.preventDefault();
            
            var isValid = true;
            var errorMessages = [];
            
            // Validate Contact Name - check all contact name inputs
            var $contactNames = $('.ContactNameEdit');
            $contactNames.each(function() {
                var contactName = $(this).val().trim();
                if (!contactName) {
                    isValid = false;
                    $(this).css('border', '2px solid #dc3545').addClass('error-field');
                    if (errorMessages.indexOf('Contact Name is required') === -1) {
                        errorMessages.push('Contact Name is required');
                    }
                } else {
                    $(this).css('border', '').removeClass('error-field');
                }
            });
            
            // Validate Billing Address - check all billing address textareas
            var $billingAddresses = $('.BillingAddressEdit');
            $billingAddresses.each(function() {
                var billingAddress = $(this).val().trim();
                if (!billingAddress) {
                    isValid = false;
                    $(this).css('border', '2px solid #dc3545').addClass('error-field');
                    if (errorMessages.indexOf('Billing Address is required') === -1) {
                        errorMessages.push('Billing Address is required');
                    }
                } else {
                    $(this).css('border', '').removeClass('error-field');
                }
            });
            
            // Validate Shipping Address - check all shipping address textareas
            var $shippingAddresses = $('.ShipingAddressEdit');
            $shippingAddresses.each(function() {
                var shippingAddress = $(this).val().trim();
                if (!shippingAddress) {
                    isValid = false;
                    $(this).css('border', '2px solid #dc3545').addClass('error-field');
                    if (errorMessages.indexOf('Shipping Address is required') === -1) {
                        errorMessages.push('Shipping Address is required');
                    }
                } else {
                    $(this).css('border', '').removeClass('error-field');
                }
            });
            
            // Validate Client Signature - check only select elements (not disabled Besmani inputs)
            var $signatures = $('.signature-row').not('.signature-row-second').find('.signature-besmani-formal');
            $signatures.each(function() {
                var signature = $(this).val();
                if (!signature || signature === '' || signature === null) {
                    isValid = false;
                    $(this).css('border', '2px solid #dc3545').addClass('error-field');
                    if (errorMessages.indexOf('Client Signature is required') === -1) {
                        errorMessages.push('Client Signature is required');
                    }
                } else {
                    $(this).css('border', '').removeClass('error-field');
                }
            });
            
            // Validate Date Signature - check all date signature inputs (only client dates, not Besmani)
            // Select date inputs in signature-row but not in signature-row-second
            $('.signature-row').not('.signature-row-second').each(function() {
                var $dateInput = $(this).find('.date-signature');
                var dateSignature = $dateInput.val().trim();
                if (!dateSignature) {
                    isValid = false;
                    $dateInput.css('border', '2px solid #dc3545').addClass('error-field');
                    if (errorMessages.indexOf('Date Signature is required') === -1) {
                        errorMessages.push('Date Signature is required');
                    }
                } else {
                    $dateInput.css('border', '').removeClass('error-field');
                }
            });
            
            // If validation fails, show error message and scroll to first error
            if (!isValid) {
                var errorMessage = 'Please fill in all required fields:\n' + errorMessages.join('\n');
                alert(errorMessage);
                
                // Scroll to first error field
                var $firstError = $('.ContactNameEdit, .BillingAddressEdit, .ShipingAddressEdit, .signature-besmani-formal, .date-signature').filter(function() {
                    return $(this).css('border-color') === 'rgb(220, 53, 69)' || $(this).css('border-color') === '#dc3545';
                }).first();
                
                if ($firstError.length > 0) {
                    $('html, body').animate({
                        scrollTop: $firstError.offset().top - 100
                    }, 500);
                    $firstError.focus();
                }
                
                return false;
            }
            
            // If all validations pass, open the modal
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

            var ContactName = $('.ContactNameEdit-{{ $packageService->id ?? 0 }}').val() || '';
            var BillingAddress = $('.BillingAddressEdit-{{ $packageService->id ?? 0 }}').val() || '';
            var ShipingAddress = $('.ShipingAddressEdit-{{ $packageService->id ?? 0 }}').val() || '';
            
            // Get signature values - check all signature fields (client signatures only, not Besmani)
            var signature_client = '';
            var signature_date = '';
            var $clientSignatureRow = $('.signature-row').not('.signature-row-second').first();
            if ($clientSignatureRow.length > 0) {
                var $signatureSelect = $clientSignatureRow.find('.signature-besmani-formal');
                signature_client = $signatureSelect.val() || '';
                
                var $dateInput = $clientSignatureRow.find('.date-signature');
                signature_date = $dateInput.val().trim() || '';
            }

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

            // Validate signature_client - check all client signature fields
            var signatureValid = true;
            var signatureErrorMessage = '';
            var $firstInvalidSignature = null;
            
            $('.signature-row').not('.signature-row-second').each(function() {
                var $signatureSelect = $(this).find('.signature-besmani-formal');
                var sigValue = $signatureSelect.val();
                
                if (!sigValue || sigValue === '' || sigValue === null) {
                    signatureValid = false;
                    $signatureSelect.css('border', '2px solid #dc3545').addClass('error-field');
                    if (!$firstInvalidSignature) {
                        $firstInvalidSignature = $signatureSelect;
                        signatureErrorMessage = 'Please select a Client Signature for all service cards.';
                    }
                } else {
                    $signatureSelect.css('border', '').removeClass('error-field');
                }
            });
            
            if (!signatureValid) {
                isValid = false;
                alert(signatureErrorMessage || 'Please select a Client Signature.');
                if ($firstInvalidSignature && $firstInvalidSignature.length > 0) {
                    $('html, body').animate({
                        scrollTop: $firstInvalidSignature.offset().top - 100
                    }, 500);
                    $firstInvalidSignature.focus();
                }
                $('.fa-spinner').hide();
                $('.goCheckoutPay').prop('disabled', false);
                return false;
            }

            // Validate signature_date - check all client date signature fields
            var dateValid = true;
            var dateErrorMessage = '';
            var $firstInvalidDate = null;
            
            $('.signature-row').not('.signature-row-second').each(function() {
                var $dateInput = $(this).find('.date-signature');
                var dateValue = $dateInput.val().trim();
                
                if (!dateValue || dateValue === '') {
                    dateValid = false;
                    $dateInput.css('border', '2px solid #dc3545').addClass('error-field');
                    if (!$firstInvalidDate) {
                        $firstInvalidDate = $dateInput;
                        dateErrorMessage = 'Please enter a Date Signature for all service cards.';
                    }
                } else {
                    $dateInput.css('border', '').removeClass('error-field');
                }
            });
            
            if (!dateValid) {
                isValid = false;
                alert(dateErrorMessage || 'Please enter a Date Signature.');
                if ($firstInvalidDate && $firstInvalidDate.length > 0) {
                    $('html, body').animate({
                        scrollTop: $firstInvalidDate.offset().top - 100
                    }, 500);
                    $firstInvalidDate.focus();
                }
                $('.fa-spinner').hide();
                $('.goCheckoutPay').prop('disabled', false);
                return false;
            }
            
            // Update signature_client and signature_date values from first valid row
            var $firstClientSignatureRow = $('.signature-row').not('.signature-row-second').first();
            if ($firstClientSignatureRow.length > 0) {
                signature_client = $firstClientSignatureRow.find('.signature-besmani-formal').val() || '';
                signature_date = $firstClientSignatureRow.find('.date-signature').val().trim() || '';
            }

            // Validate payment method radio button
            var paymentMethod = $form.find('input[name="payment_method"]:checked').val();
            if (!paymentMethod) {
                alert('Please select a payment method (Pay by Phone or Pay by Check).');
                $('.payment-options-section').css('border-color', '#dc3545');
                isValid = false;
                // Stop execution if payment method not selected
                $('.fa-spinner').hide();
                $('.goCheckoutPay').prop('disabled', false);
                return false;
            } else {
                $('.payment-options-section').css('border-color', '#e5e7eb');
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
                    payment_method: paymentMethod,
                    signature_client: signature_client,
                    signature_date: signature_date,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) { 
                     $('.fa-spinner').hide();
                    $('.goCheckoutPay').prop('disabled', false);

                    // Show success modal with confirmation
                    if (response.success) {
                        var confirmationNumber = response.tracking_code ||  '######';
                        openOrderConfirmationModal(confirmationNumber);
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

        // Order Confirmation Modal Functions
        function openOrderConfirmationModal(confirmationNumber) {
            $('#confirmation-number').text(confirmationNumber);
            $('#order-confirmation-modal').fadeIn(300);
            $('body').css('overflow', 'hidden');
            // Close payment modal
            closeDescriptionModal();
        }

        function closeOrderConfirmationModal() {
            $('#order-confirmation-modal').fadeOut(300);
            $('body').css('overflow', 'auto');
        }

        // Close confirmation modal when clicking outside
        $(document).on('click', '#order-confirmation-modal', function(e) {
            if ($(e.target).hasClass('description-modal-overlay')) {
                closeOrderConfirmationModal();
            }
        });

        // Close confirmation modal with Escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('#order-confirmation-modal').is(':visible')) {
                closeOrderConfirmationModal();
            }
        });

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

        // Payment Radio Button Interaction
        $(document).ready(function() {
            // Function to check payment method and enable/disable submit button
            function checkPaymentMethod() {
                var paymentMethod = $('input[name="payment_method"]:checked').val();
                var $submitButton = $('.goCheckoutPay');
                
                if (paymentMethod) {
                    // Enable submit button
                    $submitButton.prop('disabled', false);
                    $submitButton.css('opacity', '1');
                    $submitButton.css('cursor', 'pointer');
                } else {
                    // Disable submit button
                    $submitButton.prop('disabled', true);
                    $submitButton.css('opacity', '0.6');
                    $submitButton.css('cursor', 'not-allowed');
                }
            }

            // Handle radio button change
            $(document).on('change', 'input[name="payment_method"]', function() {
                // Remove error styling when a selection is made
                $('.payment-options-section').css('border-color', '#e5e7eb');
                
                // Visual feedback for selected option
                $('.payment-option').removeClass('selected');
                $(this).closest('.payment-option').addClass('selected');
                
                // Enable/disable submit button
                checkPaymentMethod();
            });

            // Make entire payment option clickable
            $(document).on('click', '.payment-option', function(e) {
                // Don't trigger if clicking directly on the radio button (it handles itself)
                if (!$(e.target).is('input[type="radio"]') && !$(e.target).closest('input[type="radio"]').length) {
                    var $radio = $(this).find('input[type="radio"]');
                    $radio.prop('checked', true).trigger('change');
                }
            });

            // Initialize selected state if a radio is already checked
            $('input[name="payment_method"]:checked').each(function() {
                $(this).closest('.payment-option').addClass('selected');
            });

            // Check payment method when modal opens
            $(document).on('click', '.goPayAllNow', function() {
                setTimeout(function() {
                    checkPaymentMethod();
                }, 300);
            });

            // Initial check
            checkPaymentMethod();
        });

        // Clear validation errors when user starts typing/selecting
        $(document).ready(function() {
            // Clear error styling on Contact Name input
            $(document).on('input', '.ContactNameEdit', function() {
                $(this).css('border', '').removeClass('error-field');
            });
            
            // Clear error styling on Billing Address textarea
            $(document).on('input', '.BillingAddressEdit', function() {
                $(this).css('border', '').removeClass('error-field');
            });
            
            // Clear error styling on Shipping Address textarea
            $(document).on('input', '.ShipingAddressEdit', function() {
                $(this).css('border', '').removeClass('error-field');
            });
            
            // Clear error styling on Client Signature select
            $(document).on('change', '.signature-besmani-formal', function() {
                $(this).css('border', '').removeClass('error-field');
            });
            
            // Clear error styling on Date Signature input
            $(document).on('input', '.date-signature', function() {
                $(this).css('border', '').removeClass('error-field');
            });
        });
    </script>
