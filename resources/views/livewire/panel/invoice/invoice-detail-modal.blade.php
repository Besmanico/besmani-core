@php
    // check user hasActivity
    if ($userInfo->InfoActivity->count() > 0) {
        $activity = $userInfo->InfoActivity->first();
        $CompaniName = $activity->name;
    } else {
        $CompaniName = $mainUser->fl_name . $mainUser->last_name;
    }
@endphp

<div class="invoice-detail-content">
    @foreach ($packageServices as $key => $packageService)
        <div class="main-cart-container w-100">
            <div class="quote-card">
                <div class="quote-header">
                    <div class="quote-logo d-flex flex-column flex-md-row align-items-start align-items-md-center">
                        <img src="{{ asset('assets-file/img/logo.png') }}" alt="Besmani"
                            class="quote-logo-img mb-2 mb-md-0" style="margin-top: 20px;">
                        <div class="service-name-header">
                            {{ $packageService->serviceInfo->title ?? 'Service' }}
                        </div>
                        <div class="ms-0 ms-md-3">
                            <div class="quote-company-name">Besmani Technologies, Inc.</div>
                            <div class="quote-tagline">AI • Robots • Software • Marketing</div>
                            <div class="quote-location">Irvine, CA</div>
                        </div>
                    </div>

                    <div class="quote-meta mt-3 mt-md-0">
                        <div class="meta-box">
                            <small class="quote-number-label">
                                <b class="quote-label">Invoice : </b>
                                <b class="quote-number">{{ $order->tracking_code }}</b>
                            </small>
                            <div class="muted quote-dates">
                                Issued Date: {{ $order->created_at->format('m-d-Y') }}
                                <br>
                                Due Date: {{ $order->created_at->addDays(30)->format('m-d-Y') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-100">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-md-4 mb-3 mb-md-0">
                                <div class="meta-box contact-box">
                                    <b>Besmani Contact</b>
                                    <div class="value contact-value">Besmani.com</div>
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
                                    <div class="value contact-value">
                                        <input type="text" class="w-100 bg-none" value="{{ $CompaniName }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="addresses d-flex flex-column flex-md-row">
                    <div class="addr-box mb-3 mb-md-0 me-md-3">
                        <div class="addr-title">Billing Address</div>
                        <div class="addr-body">
                            Vascular Cosmetics<br>
                            113 Waterworks Way #140,<br>
                            Irvine, CA 92618
                        </div>
                    </div>
                    <div class="addr-box">
                        <div class="addr-title">Shipping Address</div>
                        <div class="addr-body">
                            <textarea class="w-100 bg-none">Vascular Cosmetics  113 Waterworks Way #140, 
                                Irvine, CA 92618
                            </textarea>
                        </div>
                    </div>
                </div>

                @php
                    $subtotal = 0;
                    $totalTax = 0;
                    $totalDiscount = 0;
                    $grandTotal = 0;
                    $itemIndex = 0;
                    $totalItems = count($packageService->packageServiceItems);
                    $serviceId = 'service-' . $key;
                @endphp

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
                                @if ($packageService->packageServiceItems && count($packageService->packageServiceItems) > 0)
                                    @foreach ($packageService->packageServiceItems as $packageServiceItem)
                                        @if ($packageServiceItem->orderItem)
                                            @php
                                                $itemTotal =
                                                    $packageServiceItem->quantity *
                                                    $packageServiceItem->orderItem->price;
                                                $subtotal += $itemTotal;

                                                if ($packageServiceItem->orderItem->discount_type == '%') {
                                                    $TypeDiscount = '%';
                                                    $TypeDiscountDollar = '';
                                                    $itemTotalWithDiscount =
                                                        $itemTotal -
                                                        ($itemTotal * $packageServiceItem->orderItem->discount) / 100;
                                                    if ($itemTotalWithDiscount < 0) {
                                                        $itemTotalWithDiscount = 0;
                                                    }
                                                    $TotalLatsColumn =
                                                        ($packageServiceItem->orderItem->tax * $itemTotal) / 100;
                                                    $TotalLastColumnFinal = $TotalLatsColumn + $itemTotalWithDiscount;
                                                    $discountAmount =
                                                        ($itemTotal * $packageServiceItem->orderItem->discount) / 100;
                                                    $testttt = ($packageServiceItem->orderItem->tax * $itemTotal) / 100;
                                                } else {
                                                    $itemTotalWithDiscount =
                                                        $itemTotal - $packageServiceItem->orderItem->discount;
                                                    if ($itemTotalWithDiscount < 0) {
                                                        $itemTotalWithDiscount = 0;
                                                    }
                                                    $TypeDiscount = '';
                                                    $TypeDiscountDollar = '$';
                                                    $TotalLatsCol =
                                                        $itemTotalWithDiscount *
                                                        ($packageServiceItem->orderItem->tax / 100);
                                                    $TotalLatsColumn = $TotalLatsCol + $itemTotalWithDiscount;
                                                    $discountAmount = $packageServiceItem->orderItem->discount;
                                                    $testttt = ($packageServiceItem->orderItem->tax * $itemTotal) / 100;
                                                    $TotalLastColumnFinal = $itemTotalWithDiscount + $testttt;
                                                }

                                                $totalTax += $testttt;
                                                $totalDiscount += $discountAmount;
                                                $grandTotal += $TotalLastColumnFinal;
                                            @endphp
                                             <tr class="order-item-row {{ $itemIndex >= 1 ? 'hidden-item' : '' }}"
                                                 data-service="{{ $serviceId }}">
                                                 <td class="number">
                                                     <div style="display: flex; align-items: center; gap: 8px;">
                                                         <span>{{ $packageServiceItem->orderItem->code }}</span>
                                                         <button class="btn-delete-item delete-item-icon" 
                                                                 style="display: none; background: none; border: none; color: #dc3545; cursor: pointer; padding: 4px 8px; font-size: 16px; transition: all 0.3s ease;"
                                                                 data-item-id="{{ $packageServiceItem->id }}"
                                                                 data-service="{{ $serviceId }}"
                                                                 title="Delete Item">
                                                             <i class="fa fa-trash"></i>
                                                         </button>
                                                     </div>
                                                 </td>
                                                 <td>
                                                     <div>
                                                         <strong>{{ $packageServiceItem->orderItem->name }}</strong>
                                                     </div>
                                                 </td>
                                                 <td class="number">{{ $packageServiceItem->quantity }}</td>
                                                 <td class="money">
                                                     {{ $packageServiceItem->orderItem->price }}
                                                     <br>
                                                     = {{ number_format($itemTotal, 2) }}
                                                 </td>
                                                 <td class="money">{{ number_format($itemTotal, 2) }}</td>
                                                 <td class="money">
                                                     {{ $TypeDiscountDollar }}{{ $packageServiceItem->orderItem->discount }}{{ $TypeDiscount }}
                                                     <br>
                                                     = {{ number_format($itemTotalWithDiscount, 2) }}
                                                 </td>
                                                 <td class="money">{{ number_format($itemTotalWithDiscount, 2) }}</td>
                                                 <td class="money">{{ number_format($testttt, 2) }}</td>
                                                 <td class="muted">
                                                     {{ $packageServiceItem->orderItem->tax }}%
                                                     <br>
                                                     = {{ number_format($testttt, 2) }}
                                                 </td>
                                                 <td class="money">{{ number_format($TotalLastColumnFinal, 2) }}</td>
                                             </tr>
                                             @php
                                                 $itemIndex++;
                                             @endphp
                                         @endif
                                     @endforeach
                                @else
                                    <tr>
                                        <td colspan="10" style="text-align: center; padding: 20px; color: #999;">
                                            No items found in this invoice.
                                        </td>
                                    </tr>
                                @endif
                                @if ($totalItems > 1)
                                    <tr class="show-more-row" data-service="{{ $serviceId }}">
                                        <td colspan="10"
                                            style="text-align: center; padding: 6px; background-color: #f8f9fa; border-top: 2px solid #e5e7eb;">
                                            <div style="display: flex; justify-content: center; align-items: center; gap: 15px;">
                                                {{-- <button class="btn-edit-items" data-service="{{ $serviceId }}" 
                                                        style="background: #2563eb; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 6px;">
                                                    <i class="fa fa-edit"></i>
                                                    <span class="edit-text">Edit</span>
                                                    <span class="cancel-text" style="display: none;">Cancel</span>
                                                </button> --}}
                                                <button class="btn-show-more" data-service="{{ $serviceId }}">
                                                    <span class="show-more-text">Show More
                                                        ({{ $totalItems - 1 }} more items)
                                                    </span>
                                                    <span class="show-less-text" style="display: none;">Show Less</span>
                                                    <i class="fa fa-chevron-down show-more-icon"></i>
                                                    <i class="fa fa-chevron-up show-less-icon" style="display: none;"></i>
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
                        Thank you for your business. Prices in USD. This invoice is valid for 30 days from the issued
                        date.
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

                    <div class="signature-container">
                        <div class="signature-row">
                            <div class="title-signature">Client Sign.</div>
                            <div class="signature-input-wrapper">
                                <div class="signature-input-border">
                                    <input type="text" class="w-100 input-signature signature-besmani-formal"
                                        value="{{ $mainUser->fl_name }} {{ $mainUser->last_name }}">
                                </div>
                            </div>
                            <div class="title-signature">Date:</div>
                            <div class="signature-date-wrapper">
                                <div class="signature-input-border">
                                    <input type="text" class="w-100 input-signature date-signature"
                                        value="{{ $order->created_at->format('m/d/Y') }}">
                                </div>
                            </div>
                        </div>
                        <div class="signature-row signature-row-second">
                            <div class="title-signature">Besmani Sign.</div>
                            <div class="signature-input-wrapper">
                                <div class="signature-input-border">
                                    <input type="text" class="w-100 input-signature signature-besmani-formal"
                                        value="Besmani">
                                </div>
                            </div>
                            <div class="title-signature">Date:</div>
                            <div class="signature-date-wrapper">
                                <div class="signature-input-border">
                                    <input type="text" class="w-100 input-signature date-signature"
                                        value="{{ $order->created_at->format('m/d/Y') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="table-operate-btn text-left text-md-left submit-button-wrapper felx justify-content-between">
                    <button class="btn-green btn-order-submit-pay go-pay">Payments

                        <i class="fa fa-spinner fa-spin" style="display: none; margin-left: 8px;"></i>
                    </button>
                    <button class="btn-green btn-order-submit-yellow go-pdf-download">Download pdf

                        <i class="fa fa-spinner fa-spin" style="display: none; margin-left: 8px;"></i>
                    </button>
                    <button class="btn-green delete-button delete-cart-item"
                        onclick="deleteCartItem({{ $packageService->id }},{{ $packageService->cart_id }})">Delete

                        <i class="fa fa-spinner fa-spin" style="display: none; margin-left: 8px;"></i>
                    </button>
                </div>



            </div>
        </div>
    @endforeach
</div>

