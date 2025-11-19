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

                    {{-- {{ $service }} --}}


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
                        @if($userInfo->InfoActivity->count() > 0)
                         {{-- get first activity --}}
                         @php
                            $activity = $userInfo->InfoActivity->first();
                            $CompaniName = $activity->name;
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
                                                    value="{{ $mainUser->fl_name }} {{ $mainUser->last_name }}">

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
                                            <th style="min-width:110px;">Product Image</th>
                                            <th>Item Name</th>
                                            <th style="min-width:80px;">Quantity</th>
                                            <th style="min-width:90px;"> Price</th>
                                            <th style="min-width:100px;">Item Total</th>
                                            <th style="min-width:100px;">Discount</th>
                                            <th style="min-width:120px;">Total After Discount</th>
                                            <th style="min-width:140px;">Tax & Fees</th>
                                            <th style="min-width:90px;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($packageServiceItems as $packageServiceItem)
                                            <tr>
                                                <td class="number">{{ $packageServiceItem->orderItem->code }}</td>
                                                <td class="number">—</td>
                                                <td>
                                                    <div><strong>{{ $packageServiceItem->orderItem->name }}</strong>
                                                    </div>
                                                    <div class="muted">-</div>
                                                </td>
                                                <td class="number">{{ $packageServiceItem->quantity }}</td>
                                                <td class="money">{{ $packageServiceItem->orderItem->price }}</td>

                                                <?php
                                                $itemTotal = $packageServiceItem->quantity * $packageServiceItem->orderItem->price;
                                                $totalAfterDiscount = $itemTotal - $packageServiceItem->orderItem->tax;
                                                // $totalAfterDiscount = $itemTotal + $tax;
                                                $totalBefore = ($packageServiceItem->orderItem->tax / 100) * $totalAfterDiscount;
                                                $total = $totalBefore+$totalAfterDiscount ;
                                                ?>

                                                <td class="money">{{ $itemTotal }}</td>
                                                <td class="money">{{ $packageServiceItem->orderItem->tax }}</td>
                                                <td class="money">{{ $totalAfterDiscount }} </td>
                                                <td class="muted">Regulatory Fees &amp; Taxes: 25% = 12.48</td>
                                                <td class="money">{{ $total }}</td>
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
                                        <td class="value">$1,859.00</td>
                                    </tr>
                                    <tr>
                                        <td class="label">Tax</td>
                                        <td class="value">$187.23</td>
                                    </tr>
                                    {{-- <tr>
                                        <td class="label">Total</td>
                                        <td class="value">$2,046.23</td>
                                    </tr> --}}
                                    <tr>
                                        <td class="label">Discount</td>
                                        <td class="value">0</td>
                                    </tr>
                                    <tr>
                                        <td class="label"> Total</td>
                                        <td class="value">0</td>
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
                            <button class="btn-green btn-order-submit ">Submit</button>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                /* Quote Header Responsive */
                .quote-logo-img {
                    max-width: 120px;
                    height: auto;
                }

                .quote-company-name {
                    font-weight: 800;
                    font-size: 18px;
                    letter-spacing: .3px;
                }

                .quote-tagline,
                .quote-location {
                    font-size: 13px;
                    color: #444;
                }

                .quote-number-label {
                    display: inline-flex;
                    flex-wrap: wrap;
                }

                .quote-label {
                    font-weight: 800;
                    font-size: 18px;
                    letter-spacing: .3px;
                }

                .quote-number {
                    font-size: 15px;
                    font-weight: normal;
                    margin-left: 7px;
                    margin-top: 4px;
                }

                .quote-dates {
                    margin-top: 6px;
                }

                /* Contact Boxes */
                .contact-box {
                    border: 2px solid #f7f7f7;
                    padding: 15px;
                    height: 100%;
                }

                .contact-value {
                    font-size: 16px;
                    letter-spacing: .3px;
                    margin-top: 8px;
                }

                @media (min-width: 768px) {
                    .quote-company-name {
                        font-size: 20px;
                    }

                    .contact-value {
                        font-size: 18px;
                    }
                }

                /* Table Responsive */
                .quote-table-wrap {
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                }

                .table-responsive {
                    min-width: 100%;
                    overflow-x: auto;
                }

                .quote-table {
                    min-width: 1000px;
                }

                /* Addresses */
                .addresses {
                    margin-top: 20px;
                }

                .addr-box {
                    flex: 1;
                }

                /* Signature Container */
                .signature-container {
                    margin-top: -90px;
                }

                .signature-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-end;
                    flex-wrap: wrap;
                    gap: 10px;
                    margin-bottom: 20px;
                }

                .signature-row-second {
                    margin-top: 20px;
                }

                .signature-input-wrapper {
                    flex: 1;
                    min-width: 150px;
                    margin-right: 20px;
                }

                .signature-date-wrapper {
                    flex: 0 0 180px;
                    min-width: 120px;
                }

                .signature-input-border {
                    border-bottom: 1px solid #000;
                    height: 30px;
                    width: 100%;
                }

                .input-signature {
                    border: none;
                    height: 30px;
                    width: 100%;
                    padding: 0;
                    margin: 0;
                    font-size: 14px;
                    color: #000;
                    outline: none;
                    background: transparent;
                }

                .title-signature {
                    margin-right: 5px;
                    font-size: 14px;
                    color: #333;
                    white-space: nowrap;
                }

                .bg-none {
                    border: none;
                    outline: none;
                    padding: 3px;
                }

                .signature-besmani-formal {
                    font-family: 'Lucida Handwriting', 'Dancing Script', cursive;
                    font-style: italic;
                    font-size: 16px;
                    font-weight: 500;
                    letter-spacing: 2px;
                    color: #1a1a1a;
                    background: transparent;
                }

                @media (min-width: 768px) {
                    .signature-besmani-formal {
                        font-size: 18px;
                    }

                    .signature-input-wrapper {
                        margin-right: 40px;
                    }

                    .signature-date-wrapper {
                        flex: 0 0 200px;
                    }
                }

                @media (max-width: 767px) {
                    .signature-container {
                        margin-top: 20px;
                    }

                    .signature-row {
                        flex-direction: column;
                        align-items: stretch;
                    }

                    .signature-input-wrapper,
                    .signature-date-wrapper {
                        flex: 1;
                        margin-right: 0;
                        margin-top: 10px;
                    }

                    .title-signature {
                        margin-bottom: 5px;
                    }
                }

                /* Submit Button */
                .submit-button-wrapper {
                    padding: 16px;
                }

                .btn-order-submit {
                    border: none;
                    width: 100%;
                    max-width: 150px;
                    height: 40px;
                    border-radius: 8px;
                    background-color: #071021;
                    color: #fff;
                    font-size: 14px;
                    font-weight: bold;
                    cursor: pointer;
                    transition: all 0.3s ease;
                }

                .btn-order-submit:hover {
                    background-color: #666;
                    color: #fff;
                    transform: translateY(-1px);
                }

                @media (max-width: 767px) {
                    .btn-order-submit {
                        width: 100%;
                        max-width: 100%;
                    }
                }
            </style>


        </div>


</div>
