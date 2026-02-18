<div>
    <main class="panel-main">
        {{-- @livewire('panel.header') --}}
        <div>
            <header class="panel-header">
                <div>
                    {{-- show title based on current page --}}
                    <h1>Payments</h1>

                </div>

            </header>
        </div>


        <section class="invoice-section">
            <div class="panel-card">
                <div class="table-responsive">
                    <table class="besmani-table">
                        <thead>
                            <tr>

                                <th>Date</th>
                                <th>Service</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Discount</th>
                                <th>Tax & Fee</th>
                                <th>Balance</th>

                                <th>Tracking Code</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                @php
                                    $serviceNames =
                                        $order->cart && $order->cart->cartServices
                                            ? $order->cart->cartServices
                                                ->map(fn($cs) => $cs->serviceInfo->title ?? '')
                                                ->filter()
                                                ->unique()
                                                ->implode(', ')
                                            : '—';
                                @endphp
                                <tr class="payment-row" data-order-id="{{ $order->id }}" style="cursor: pointer;">

                                    <td>
                                        <span class="invoice-date"
                                            style="color: rgba(204, 162, 0, 0.97) !important;
  font-weight: bold;">{{ $order->created_at->format('m/d/Y') }}</span>
                                    </td>
                                    <td class="font-bold">
                                        {{ $serviceNames ?: '—' }}
                                    </td>
                                    <td>
                                        <span class="invoice-service font-bold color-red">


                                            @php
                                                $order_status = $order->payment_status;
                                            @endphp
                                            @if ($order_status == 'Pending')
                                                <span class="invoice-service font-bold color-yellow">Pending</span>
                                            @elseif($order_status == 'Paid')
                                                <span class="invoice-service font-bold color-green">Paid</span>
                                            @elseif($order_status == 'Unpaid')
                                                <span class="invoice-service font-bold color-red">Unpaid</span>
                                            @elseif($order_status == 'Partially Paid')
                                                <span class="invoice-service font-bold color-orange">Partially
                                                    Paid</span>
                                            @elseif($order_status == 'Overpaid')
                                                <span class="invoice-service font-bold color-blue">Overpaid</span>
                                            @endif



                                        </span>
                                    </td>


                                    <td>
                                        <span
                                            class="invoice-amount color-green font-bold">${{ number_format($order->total_payment, 2) }}</span>
                                    </td>

                                    <td>
                                        <span
                                            class="invoice-amount color-red">${{ number_format($order->discount, 2) }}</span>
                                    </td>
                                    <td>
                                        <b
                                            class="invoice-amount color-dark">${{ number_format($order->tax_fee, 2) }}</b>
                                    </td>

                                    <td>
                                        <b class="invoice-amount extra-pay-amount {{ ($order->free_price ?? 0) < 0 ? 'color-red' : 'color-green' }}">${{ number_format($order->free_price ?? 0, 2) }}</b>
                                    </td>
                                    <td>
                                        <span class="invoice-id">{{ $order->tracking_code }}</span>
                                    </td>


                                </tr> 
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <!-- Pay detail modal -->
    <div id="payment-detail-modal" class="payment-modal-overlay" style="display: none;">
        <div class="payment-modal-container">
            <button class="payment-modal-close" type="button" onclick="closePaymentModal()">×</button>
            <div class="payment-modal-body" id="payment-modal-content">
                <div style="text-align: center; padding: 40px;">
                    <i class="fa fa-spinner fa-spin" style="font-size: 24px;"></i>
                    <p>Loading pay detail...</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .payment-row:hover {
            background-color: #e3f2fd !important;
        }

        .payment-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            overflow-y: auto;
            padding: 20px;
        }

        .payment-modal-container {
            position: relative;
            max-width: 520px;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            padding: 0;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .payment-modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #dc3545;
            color: #fff;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .payment-modal-close:hover {
            background: #c82333;
            transform: scale(1.1);
        }

        .payment-modal-body {
            padding: 0;
            max-height: 90vh;
            overflow-y: auto;
        }

        .payment-detail-content {
            padding: 0;
        }

        .payment-detail-content .payment-detail-header {
            margin-bottom: 0;
            padding: 28px 24px 24px;
            background: linear-gradient(160deg, #06283f 0%, #0c4a6e 100%);
            color: #fff;
            text-align: center;
        }

        .payment-detail-header-icon {
            width: 56px;
            height: 56px;
            margin: 0 auto 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            font-size: 1.5rem;
        }

        .payment-detail-content .payment-detail-title {
            margin: 0 0 8px;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
        }

        .payment-detail-content .payment-detail-meta {
            margin: 0;
            font-size: 15px;
            color: rgba(255, 255, 255, 0.9);
        }

        .payment-detail-content .payment-detail-code {
            color: #93c5fd;
            margin-right: 4px;
        }

        .payment-detail-content .payment-detail-date {
            margin: 10px 0 0;
            font-size: 15px;
            color: rgba(244, 201, 36, 0.97);
        }

        .payment-detail-content .payment-detail-date i {
            margin-left: 6px;
        }

        .payment-detail-table-wrap {
            padding: 20px 24px;
        }

        .payment-detail-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .payment-detail-table th,
        .payment-detail-table td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .payment-detail-table thead th {
            font-weight: 600;
            color: #475569;
            background: #f8fafc;
        }

        .payment-detail-table tbody tr:hover {
            background: #f8fafc;
        }

        .payment-detail-table .payment-amount {
            font-weight: 700;
            color: #059669;
        }

        .payment-detail-num {
            color: #64748b;
            font-weight: 500;
        }

        .payment-detail-empty {
            text-align: center !important;
            padding: 32px 16px !important;
            color: #94a3b8;
        }

        .payment-detail-empty i {
            display: block;
            font-size: 2rem;
            margin-bottom: 10px;
            opacity: 0.6;
        }

        .payment-detail-summary {
            margin: 0 24px 24px;
            padding: 18px 20px;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 12px;
            border: 1px solid #bbf7d0;
        }

        .payment-detail-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
        }

        .payment-detail-summary-row.payment-detail-summary-total {
            margin-top: 8px;
            padding-top: 12px;
            border-top: 1px solid #86efac;
            font-size: 13px;
        }

        .payment-detail-summary-label {
            color: #166534;
            font-weight: 600;
        }

        .payment-detail-summary-value {
            font-weight: 700;
            color: #15803d;
        }

        .payment-detail-actions {
            padding: 20px 24px 24px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .btn-payment-pdf {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #dc2626;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
        }

        .btn-payment-pdf:hover {
            background: #b91c1c;
        }

        .btn-payment-pdf:active {
            transform: scale(0.98);
        }

        .btn-payment-pdf .payment-pdf-spinner {
            margin-left: 6px;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #payment-detail-modal,
            #payment-detail-modal .payment-modal-container,
            #payment-detail-modal .payment-modal-container * {
                visibility: visible;
            }

            #payment-detail-modal {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                background: #fff;
                padding: 0;
            }

            .payment-modal-close,
            .payment-detail-actions {
                display: none !important;
            }

            .payment-modal-container {
                box-shadow: none;
                max-width: 100%;
            }
        }
    </style>

    <script>
        function openPaymentModal(orderId) {
            $('#payment-detail-modal').fadeIn(300);
            $('body').css('overflow', 'hidden');
            $('#payment-modal-content').html(
                '<div style="text-align: center; padding: 40px;">' +
                '<i class="fa fa-spinner fa-spin" style="font-size: 24px;"></i>' +
                '<p>Loading pay detail...</p></div>'
            );
            $.ajax({
                url: '{{ route('panel.payment.details') }}',
                type: 'GET',
                data: {
                    order_id: orderId
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success && response.html) {
                        $('#payment-modal-content').html(response.html);
                    } else {
                        $('#payment-modal-content').html(
                            '<div style="text-align: center; padding: 40px; color: #dc3545;">' +
                            '<i class="fa fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 20px;"></i>' +
                            '<p>Unable to load pay detail.</p></div>'
                        );
                    }
                },
                error: function() {
                    $('#payment-modal-content').html(
                        '<div style="text-align: center; padding: 40px; color: #dc3545;">' +
                        '<i class="fa fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 20px;"></i>' +
                        '<p>Error loading pay detail. Please try again.</p></div>'
                    );
                }
            });
        }

        function closePaymentModal() {
            $('#payment-detail-modal').fadeOut(300);
            $('body').css('overflow', 'auto');
        }

        $(document).on('click', '.payment-modal-overlay', function(e) {
            if ($(e.target).hasClass('payment-modal-overlay')) {
                closePaymentModal();
            }
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('#payment-detail-modal').is(':visible')) {
                closePaymentModal();
            }
        });

        $(document).on('click', '.payment-row', function() {
            var orderId = $(this).data('order-id');
            if (orderId) openPaymentModal(orderId);
        });

        $(document).on('click', '#payment-detail-modal .payment-pdf-download', function() {
            var $btn = $(this);
            $btn.find('.payment-pdf-spinner').show();
            $btn.prop('disabled', true);
            setTimeout(function() {
                window.print();
                $btn.find('.payment-pdf-spinner').hide();
                $btn.prop('disabled', false);
            }, 300);
        });
    </script>
</div>
