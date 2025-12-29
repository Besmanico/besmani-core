<div>
    @php
        $mainUser = Auth::guard('mainUsers')->user();
        $userInfo = UserInfoPublic();

        // print_r($userInfo->InfoActivity);

    @endphp

    <section class="site-section subpage-site-section ">
{{-- 
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-5 mb-3 mb-md-0">
                   
                    image here....
                </div>


                <div class="col-12 col-md-1 d-none d-md-block"></div>
                <div class="col-12 col-md-5">
                    <h2>test</h2>
                    <p class="service-body">
                        test description.....
                    </p>

                    {{-- {{ $service->id }} --}}


                {{-- </div>

            </div>
        </div> --}}
        

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
                                    {{-- {{ $CompaniName }}
                                    <br> --}}
                                    {{-- 113 Waterworks Way #140,<br> --}}
                                   {{ $userInfo->address }} {{ $userInfo->city }} {{ $userInfo->province }} {{ $userInfo->postal_code }} {{ $userInfo->country_name }} 
                                </div>
                            </div>
                            <div class="addr-box">
                                <div class="addr-title">Shipping Address</div>
                                <div class="addr-body">
                                    {{-- {{ $CompaniName }}
                                    <br> --}}
                                    {{-- 113 Waterworks Way #140,<br> --}}
                                   
                         <textarea class="w-100 bg-none">{{ $userInfo->address }} {{ $userInfo->city }} {{ $userInfo->province }} {{ $userInfo->postal_code }} {{ $userInfo->country_name }}</textarea>  

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
                                            <th style="min-width:100px;">Discount</th>
                                            {{-- <th style="min-width:120px;">testttt*</th> --}}
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
                                                if($itemTotalWithDiscount < 0){
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
                                                if($itemTotalWithDiscount < 0){
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
                                                    {{-- <div class="muted">-</div> --}}
                                                </td>
                                                <td class="number">{{ $packageServiceItem->quantity }}</td>
                                                <td class="money">{{ $packageServiceItem->orderItem->price }}

                                                    <br>
                                                    = {{ number_format($itemTotal, 2) }}

                                                </td>
                                                <td class="money">
                                                    {{ $TypeDiscountDollar }}{{ $packageServiceItem->orderItem->discount }}{{ $TypeDiscount }}
                                                    <br>
                                                    =
                                                    {{ number_format($itemTotalWithDiscount, 2) }}
                                                </td>
                                                {{-- <td class="money">{{ number_format($testttt, 2) }}</td> --}}
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
                                Thank you for your business. Prices are in USD. This quote is valid for 30 days from the
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
                                                <option value="">Select</option>
                                                <option value="{{ $mainUser->fl_name }} {{ $mainUser->last_name }}">{{ $mainUser->fl_name }} {{ $mainUser->last_name }}</option>
                                               
                                            </select>

                                            {{-- <input type="text" class="w-100 input-signature signature-besmani-formal"
                                                value="{{ $mainUser->fl_name }} {{ $mainUser->last_name }}">
                                       --}}
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

                                        Signature.</div>
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
                            <button class="btn-green btn-order-submit addToCart">
                                <span class="btn-text">Save & Continue</span>
                                <i class="fa fa-spinner fa-spin" style="display: none; margin-left: 8px;"></i>
                            </button>
                            <p class="muted ml-3" style="font-size: 16px;margin-left: 14px;margin-top: 10px;">
                                <i class="fa fa-info-circle"></i>
                                After saving, you can view, edit, cancel, or print your order anytime.
                            </p>
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
                           return window.location.href = '{{ route('cart') }}';
                            // openCartSuccessModal();
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
            /* Enhanced Green AddToCart Button Styles */
            .btn-green.btn-order-submit.addToCart {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: #ffffff;
                border: none;
                width: 100%;
                max-width: 200px;
                height: 48px;
                border-radius: 12px;
                font-size: 15px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .btn-green.btn-order-submit.addToCart::before {
                content: '';
                position: absolute;
                top: 0; 
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s;
            }

            .btn-green.btn-order-submit.addToCart:hover::before {
                left: 100%;
            }

            .btn-green.btn-order-submit.addToCart:hover {
                background: linear-gradient(135deg, #059669 0%, #047857 100%);
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            }

            .btn-green.btn-order-submit.addToCart:active {
                transform: translateY(0);
                box-shadow: 0 2px 10px rgba(16, 185, 129, 0.3);
            }

            .btn-green.btn-order-submit.addToCart:disabled {
                background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
                cursor: not-allowed;
                transform: none;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .btn-green.btn-order-submit.addToCart .btn-text {
                position: relative;
                z-index: 1;
            }

            .btn-green.btn-order-submit.addToCart .fa-spinner {
                position: relative;
                z-index: 1;
            }

            .submit-button-wrapper {
                padding: 20px 16px;
                display: flex;
                justify-content: flex-start;
            }

            @media (max-width: 767px) {
                .btn-green.btn-order-submit.addToCart {
                    max-width: 100%;
                    width: 100%;
                }

                .submit-button-wrapper {
                    justify-content: center;
                }
            }

            @media (min-width: 768px) {
                .submit-button-wrapper {
                    justify-content: flex-start;
                }
            }
        </style>

</div>
