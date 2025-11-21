<div>
    @php
        $mainUser = Auth::guard('mainUsers')->user();
        $userInfo = UserInfoPublic();

        // print_r($userInfo->InfoActivity);

    @endphp

    <section class="site-section subpage-site-section ">

        <div class="container">
            <div class="row">
                <div class="col-12 col-md-5 mb-3 mb-md-0">
                    {{-- <img src="{{ config('app.url') }}storage/<?= $service['image'] ?>" alt="<?= $service['title'] ?>"
                        class="w-100"> --}}
                    image here....
                </div>


                <div class="col-12 col-md-1 d-none d-md-block"></div>
                <div class="col-12 col-md-5">
                    <h2>test</h2>
                    <p class="service-body">
                        test description.....
                    </p>

                    {{ $service->id }}


                </div>

            </div>
        </div>

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
                                                <input type="text" class="w-100 bg-none" value="{{ $CompaniName }}">

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
                                                <td class="money">{{ number_format($itemTotalWithDiscount, 2) }} </td>
                                                <td class="money">{{ number_format($testttt, 2) }}</td>
                                                <td class="muted">
                                                    {{ $packageServiceItem->orderItem->tax }}%
                                                    <br>
                                                    = {{ number_format($testttt, 2) }}

                                                </td>
                                                <td class="money">{{ number_format($TotalLastColumnFinal, 2) }} </td>
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
                                            <input type="text" class="w-100 input-signature signature-besmani-formal"
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

           

        </div>

        <script>
            //    addToCart
            var service_id = '{{ $service->id }}';
            var package_service_id = '{{ $id }}';
            $('body').on('click', '.addToCart', function() {

                // Show loading spinner
                $('.fa-spinner').show();
                $('.addToCart').prop('disabled', true);

                $.ajax({
                    url: '{{ route('addToCart') }}',
                    type: 'POST',
                    data: {
                        service_id: service_id,
                        package_service_id: package_service_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('.fa-spinner').hide();
                        $('.addToCart').prop('disabled', false);
                        
                        // Show success modal
                        if (response.success) {
                            openCartSuccessModal();
                        }
                    },
                    error: function(response) {
                        $('.fa-spinner').hide();
                        $('.addToCart').prop('disabled', false);
                        
                        // Show error message
                        alert('An error occurred. Please try again.');
                    }
                });

            });
        </script> 

        <!-- Success Modal -->
        <div id="cart-success-modal" class="cart-success-modal-overlay" style="display: none;">
            <div class="cart-success-modal-container">
                <button class="cart-success-modal-close" onclick="closeCartSuccessModal()">×</button>
                
                <div class="cart-success-modal-content">
                    <div class="cart-success-icon">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <h2 class="cart-success-title">Successfully Added to Cart!</h2>
                    <p class="cart-success-message">Your item has been added to your shopping cart.</p>
                    
                    <div class="cart-success-actions">
                        <button class="btn-continue-shopping" onclick="closeCartSuccessModal()">
                            Continue Shopping
                        </button>
                        <a href="{{ route('cart') }}" class="btn-go-to-cart">
                            <i class="fa fa-shopping-cart"></i>
                            Go to Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Cart Success Modal Functions
            function openCartSuccessModal() {
                $('#cart-success-modal').fadeIn(300);
            }

            function closeCartSuccessModal() {
                $('#cart-success-modal').fadeOut(300);
            }

            // Close modal when clicking outside
            $(document).on('click', '#cart-success-modal', function(e) {
                if ($(e.target).hasClass('cart-success-modal-overlay')) {
                    closeCartSuccessModal();
                }
            });

            // Close modal with Escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $('#cart-success-modal').is(':visible')) {
                    closeCartSuccessModal();
                }
            });
        </script>

        <style>
            /* Cart Success Modal Styles */
            .cart-success-modal-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.75);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 10000;
                backdrop-filter: blur(4px);
                animation: fadeIn 0.3s ease-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }
                to {
                    opacity: 1;
                }
            }

            .cart-success-modal-container {
                position: relative;
                width: 90%;
                max-width: 480px;
                background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
                border-radius: 24px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                overflow: hidden;
                animation: modalSlideUp 0.3s ease-out;
                padding: 0;
            }

            @keyframes modalSlideUp {
                from {
                    opacity: 0;
                    transform: translateY(30px) scale(0.95);
                }
                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            .cart-success-modal-close {
                position: absolute;
                top: 15px;
                right: 15px;
                background: transparent;
                border: none;
                font-size: 28px;
                color: #666;
                cursor: pointer;
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                transition: all 0.3s ease;
                z-index: 1;
            }

            .cart-success-modal-close:hover {
                background-color: rgba(0, 0, 0, 0.05);
                color: #000;
                transform: rotate(90deg);
            }

            .cart-success-modal-content {
                padding: 50px 40px 40px;
                text-align: center;
            }

            .cart-success-icon {
                margin-bottom: 24px;
            }

            .cart-success-icon i {
                font-size: 60px;
                color: #10b981;
                animation: successScale 0.5s ease-out;
            }

            @keyframes successScale {
                0% {
                    transform: scale(0);
                }
                50% {
                    transform: scale(1.2);
                }
                100% {
                    transform: scale(1);
                }
            }

            .cart-success-title {
                font-size: 22px;
                font-weight: 700;
                color: #071021;
                margin-bottom: 7px;
                line-height: 1.1;
            }

            .cart-success-message {
                font-size: 13px;
                color: #6b7280;
                margin-bottom: 32px;
                line-height: 1.6;
            }

            .cart-success-actions {
                display: flex;
                gap: 12px;
                flex-direction: column;
            }

            .btn-continue-shopping {
                padding: 14px 24px;
                background-color: #f3f4f6;
                color: #374151;
                border: 2px solid #e5e7eb;
                border-radius: 12px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                width: 100%;
            }

            .btn-continue-shopping:hover {
                background-color: #e5e7eb;
                border-color: #d1d5db;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .btn-go-to-cart {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                padding: 14px 24px;
                background: linear-gradient(135deg, #fe0002 0%, #dc2626 100%);
                color: #ffffff;
                border: none;
                border-radius: 12px;
                font-size: 15px;
                font-weight: 600;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.3s ease;
                width: 100%;
                box-shadow: 0 4px 14px rgba(254, 0, 2, 0.3);
            }

            .btn-go-to-cart:hover {
                background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(254, 0, 2, 0.4);
                color: #ffffff;
                text-decoration: none;
            }

            .btn-go-to-cart i {
                font-size: 18px;
            }

            @media (min-width: 640px) {
                .cart-success-actions {
                    flex-direction: row;
                }

                .btn-continue-shopping,
                .btn-go-to-cart {
                    flex: 1;
                }
            }

            @media (max-width: 640px) {
                .cart-success-modal-container {
                    width: 95%;
                    margin: 20px;
                }

                .cart-success-modal-content {
                    padding: 40px 30px 30px;
                }

                .cart-success-icon i {
                    font-size: 60px;
                }

                .cart-success-title {
                    font-size: 24px;
                }

                .cart-success-message {
                    font-size: 15px;
                }
            }
        </style>

</div>
