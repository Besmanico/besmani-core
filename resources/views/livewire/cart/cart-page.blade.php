<div>
    @php
        $mainUser = Auth::guard('mainUsers')->user();
        $userInfo = UserInfoPublic();
        $cartInfo = CartInfo()['cartInfo'];
        $packageServiceItems = CartInfo()['packageServiceItems'];

    @endphp

    <section class="site-section subpage-site-section ">

        @if ($cartInfo)


        <div class="container">
            <div class="row">
               
                    <div class="w-100 order-from-side">


                        <div class="quote-card">

                            <div class="quote-header">
                                <div
                                    class="quote-logo d-flex flex-column flex-md-row align-items-start align-items-md-center">
                                    <img src="{{ asset('assets-file/img/logo.png') }}" alt="Besmani"
                                        class="quote-logo-img mb-2 mb-md-0" style="margin-top: 20px;">
                                    <div class="ms-0 ms-md-3">
                                        <div class="quote-company-name">Besmani
                                            Technologies, Inc.</div>
                                        <div class="quote-tagline">AI • Robots • Software • Marketing</div>
                                        <div class="quote-location">Irvine, CA</div>
                                    </div>

                                </div>

                                <div class="quote-meta mt-3 mt-md-0">
                                    <div class="meta-box">
                                        <small class="quote-number-label"><b class="quote-label"> Quote : </b> <b
                                                class="quote-number">
                                                QUO1816</b></small>
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
                                @endphp
                            @else
                                @php
                                    $CompaniName = $mainUser->fl_name . $mainUser->last_name;
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
                                                <div class="value contact-value">
                                                    <input type="text" class="w-100 bg-none"
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
                                                $subtotal = 0;
                                                $totalTax = 0;
                                                $totalDiscount = 0;
                                                $grandTotal = 0;

                                            @endphp
                                            @foreach ($packageServiceItems as $packageServiceItem)
                                                <?php
                                                
                                                $itemTotal = $packageServiceItem->quantity * $packageServiceItem->orderItem->price;
                                                
                                                // Add to subtotal
                                                $subtotal += $itemTotal;
                                                
                                                // check type of discount
                                                if ($packageServiceItem->orderItem->discount_type == '%') {
                                                    $TypeDiscount = '%';
                                                    $TypeDiscountDollar = '';
                                                    $itemTotalWithDiscount = $itemTotal - ($itemTotal * $packageServiceItem->orderItem->discount) / 100;
                                                
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
                                                
                                                // Add to totals
                                                $totalTax += $testttt;
                                                $totalDiscount += $discountAmount;
                                                $grandTotal += $TotalLastColumnFinal;
                                                
                                                ?>
                                                <tr>
                                                    <td class="number">{{ $packageServiceItem->orderItem->code }}</td>
                                                    <td>
                                                        <div><strong>{{ $packageServiceItem->orderItem->name }}</strong>
                                                        </div>
                                                        <div class="muted">-</div>
                                                    </td>
                                                    <td class="number">{{ $packageServiceItem->quantity }}</td>
                                                    <td class="money">{{ $packageServiceItem->orderItem->price }}

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
                                                    <td class="money">{{ number_format($itemTotalWithDiscount, 2) }}
                                                    </td>
                                                    <td class="money">{{ number_format($testttt, 2) }}</td>
                                                    <td class="muted">
                                                        {{ $packageServiceItem->orderItem->tax }}%
                                                        <br>
                                                        = {{ number_format($testttt, 2) }}

                                                    </td>
                                                    <td class="money">{{ number_format($TotalLastColumnFinal, 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="quote-footer">
                                <div class="note">
                                    Thank you for your business. Prices in USD. This quote is valid for 30 days from the
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
                                                <input type="text" class="w-100 input-signature date-signature"
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
                                                <input type="text" class="w-100 input-signature date-signature"
                                                    value="{{ \Carbon\Carbon::today()->format('m/d/Y') }}">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            {{-- submit button --}}
                            <div class="text-left text-md-left submit-button-wrapper">
                                <button class="btn-green btn-order-submit addToCart">ADD TO CART

                                    <i class="fa fa-spinner fa-spin" style="display: none; margin-left: 8px;"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                
            </div>


            @else
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="empty-cart-container">
                                <div class="empty-cart-icon">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <h2 class="empty-cart-title">Your Cart is Empty!</h2>
                                <p class="empty-cart-message">Looks like you haven't added anything to your cart yet.</p>
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

        </section>


        <style>
            /* Empty Cart Styles */
            .empty-cart-container {
                background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
                border-radius: 24px;
                padding: 80px 40px;
                text-align: center;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
                border: 2px solid #e5e7eb;
                margin: 40px 0;
                animation: fadeInUp 0.6s ease-out;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .empty-cart-icon {
                margin-bottom: 30px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .empty-cart-icon i {
                font-size: 120px;
                color: #d1d5db;
                opacity: 0.6;
                animation: emptyCartPulse 2s ease-in-out infinite;
            }

            @keyframes emptyCartPulse {
                0%, 100% {
                    transform: scale(1);
                    opacity: 0.6;
                }
                50% {
                    transform: scale(1.05);
                    opacity: 0.4;
                }
            }

            .empty-cart-title {
                font-size: 32px;
                font-weight: 700;
                color: #071021;
                margin-bottom: 16px;
                line-height: 1.3;
            }

            .empty-cart-message {
                font-size: 18px;
                color: #6b7280;
                margin-bottom: 8px;
                line-height: 1.6;
            }

            .empty-cart-submessage {
                font-size: 15px;
                color: #9ca3af;
                margin-bottom: 40px;
                line-height: 1.5;
            }

            .btn-empty-cart-shop {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                padding: 16px 32px;
                background: linear-gradient(135deg, #fe0002 0%, #dc2626 100%);
                color: #ffffff;
                border: none;
                border-radius: 12px;
                font-size: 16px;
                font-weight: 600;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 4px 14px rgba(254, 0, 2, 0.3);
                min-width: 200px;
            }

            .btn-empty-cart-shop:hover {
                background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(254, 0, 2, 0.4);
                color: #ffffff;
                text-decoration: none;
            }

            .btn-empty-cart-shop:active {
                transform: translateY(0);
            }

            .btn-empty-cart-shop i {
                font-size: 16px;
                transition: transform 0.3s ease;
            }

            .btn-empty-cart-shop:hover i {
                transform: translateX(-4px);
            }

            @media (max-width: 768px) {
                .empty-cart-container {
                    padding: 60px 30px;
                    margin: 30px 0;
                    border-radius: 20px;
                }

                .empty-cart-icon i {
                    font-size: 80px;
                }

                .empty-cart-title {
                    font-size: 26px;
                }

                .empty-cart-message {
                    font-size: 16px;
                }

                .empty-cart-submessage {
                    font-size: 14px;
                }

                .btn-empty-cart-shop {
                    padding: 14px 28px;
                    font-size: 15px;
                    min-width: 180px;
                }
            }

            @media (max-width: 480px) {
                .empty-cart-container {
                    padding: 50px 20px;
                    margin: 20px 0;
                }

                .empty-cart-icon i {
                    font-size: 60px;
                }

                .empty-cart-title {
                    font-size: 22px;
                    margin-bottom: 12px;
                }

                .empty-cart-message {
                    font-size: 15px;
                    margin-bottom: 6px;
                }

                .empty-cart-submessage {
                    font-size: 13px;
                    margin-bottom: 30px;
                }

                .btn-empty-cart-shop {
                    width: 100%;
                    padding: 14px 24px;
                    font-size: 14px;
                    min-width: auto;
                }
            }
        </style>

        <script>
            //    addToCart
        </script>
