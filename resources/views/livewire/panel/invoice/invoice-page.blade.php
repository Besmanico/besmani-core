<div>
    <div>
        <main class="panel-main">
            @livewire('panel.header')

            <section class="invoice-section">
                <div class="panel-card">

                    <div class="table-responsive">
                        <table class="besmani-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Service</th>
                                    <th> Status</th>
                                    <th>Code</th>
                                    <th> Progress </th>

                                    <th>Total</th>

                                    {{-- <th>Discount</th>
                                <th>Tax & Fee</th> --}}
                                  
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
                                    <tr class="invoice-row" data-order-id="{{ $order->id }}"
                                        data-tracking-code="{{ $order->tracking_code }}" style="cursor: pointer;">


                                        <td>
                                            <span class="invoice-date"
                                                style="color: rgba(204, 162, 0, 0.97) !important;
  font-weight: bold;">{{ $order->created_at->format('m/d/Y') }}</span>
                                        </td>

                                        <td>
                                            <span class="invoice-service font-bold">{{ $serviceNames ?: '—' }}</span>
                                        </td>
                                        <td>
                                            {{-- if order_status== Pending --}}
                                            <span
                                                class="invoice-service font-bold color-red">
                                                

                                                @php
                                                    $order_status = $order->order_status;
                                                @endphp
                                                  @if($order_status == 'Pending')
                                                  <span class="invoice-service font-bold color-red">Pending</span>
                                                  @elseif($order_status == 'Starting')
                                                  <span class="invoice-service font-bold color-yellow">Starting</span>
                                                  @elseif($order_status == 'Processing')
                                                  <span class="invoice-service font-bold color-orange">Processing</span>
                                                  @elseif($order_status == 'Finalizing')
                                                  <span class="invoice-service font-bold color-blue">Finalizing</span>
                                                  @elseif($order_status == 'Done')
                                                  <span class="invoice-service font-bold color-green">Done</span>
                                                  @elseif($order_status == 'Cancelled')
                                                  <span class="invoice-service font-bold color-red">Cancelled</span>
                                                  @endif

 
                                                 
                                                 </span>
                                        </td>
                                      
                                      
                                        {{-- <td>
                                    <span class="invoice-amount color-red">${{ number_format($order->discount, 2) }}</span>
                                </td> --}}

                                        {{-- <td>
                                    <b class="invoice-amount color-dark">${{ number_format($order->tax_fee, 2) }}</b>
                                </td> --}}
                                <td>
                                    <span class="invoice-id">{{ $order->tracking_code }}</span>
                                </td>
                                

                                <td>
                                    @php
                                        $p = (int) ($order->progress ?? 0);
                                        $progressColorClass = $p >= 100 ? 'progress-fill-green' : ($p >= 80 ? 'progress-fill-blue' : ($p >= 50 ? 'progress-fill-orange' : 'progress-fill-red'));
                                    @endphp
                                    <div class="invoice-progress-wrap">
                                        <div class="invoice-progress-bar" title="{{ $p }}%">
                                            <div class="invoice-progress-fill {{ $progressColorClass }}" style="width: {{ min(100, max(0, $p)) }}%"></div>
                                        </div>
                                        <span class="invoice-progress-label">{{ $p }}%</span>
                                    </div>
                                </td> 
                                        

                                        <td>
                                            <span
                                                class="invoice-amount color-green font-bold">${{ number_format($order->total_payment, 2) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>

        <!-- Invoice Detail Modal -->
        <div id="invoice-detail-modal" class="invoice-modal-overlay" style="display: none;">
            <div class="invoice-modal-container">
                <button class="invoice-modal-close" onclick="closeInvoiceModal()">×</button>
                <div class="invoice-modal-body" id="invoice-modal-content">
                    <!-- Content will be loaded here via AJAX -->
                    <div style="text-align: center; padding: 40px;">
                        <i class="fa fa-spinner fa-spin" style="font-size: 24px;"></i>
                        <p>Loading invoice details...</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Invoice Detail Modal End -->

        <!-- Order processing message modal -->
        <div id="order-processing-msg-modal" class="order-msg-modal" role="dialog" aria-modal="true"
            aria-labelledby="order-msg-modal-title" style="display: none;">
            <div class="order-msg-modal-backdrop"></div>
            <div class="order-msg-modal-dialog">
                <div class="order-msg-modal-content">
                    <div class="order-msg-modal-icon">
                        <i class="fa fa-hourglass-half" aria-hidden="true"></i>
                    </div>
                    <h3 id="order-msg-modal-title" class="order-msg-modal-title">This order is currently in processing
                        and cannot be modified or deleted</h3>
                    <p class="order-msg-modal-text">You can find your order anytime in Dashboard/Orders.</p>
                    <button type="button" class="order-msg-modal-btn" onclick="closeOrderProcessingModal()">OK</button>
                </div>
            </div>
        </div>

        <style>
            /* Modal Styles */
            .invoice-modal-overlay {
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

            .invoice-modal-container {
                position: relative;
                max-width: 1200px;
                margin: 0 auto;
                background: white;
                border-radius: 8px;
                padding: 0;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            }

            .invoice-modal-close {
                position: absolute;
                top: 15px;
                right: 15px;
                background: #dc3545;
                color: white;
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

            .invoice-modal-close:hover {
                background: #c82333;
                transform: scale(1.1);
            }

            .invoice-modal-body {
                padding: 20px;
                max-height: 90vh;
                overflow-y: auto;
            }

            .invoice-row {
                cursor: pointer;
            }

            .invoice-row:hover {
                background-color: #e3f2fd !important;
            }

            /* Invoice progress bar */
            .invoice-progress-wrap {
                display: flex;
                align-items: center;
                gap: 10px;
                min-width: 40px;
            }
            .invoice-progress-bar {
                flex: 1;
                height: 10px;
                background: #e5e7eb;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: inset 0 1px 2px rgba(0,0,0,0.06);
            }
            .invoice-progress-fill {
                height: 100%;
                border-radius: 10px;
                transition: width 0.4s ease, background 0.3s ease;
            }
            .invoice-progress-fill.progress-fill-red {
                background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%);
            }
            .invoice-progress-fill.progress-fill-orange {
                background: linear-gradient(90deg, #ea580c 0%, #f97316 100%);
            }
            .invoice-progress-fill.progress-fill-blue {
                background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
            }
            .invoice-progress-fill.progress-fill-green {
                background: linear-gradient(90deg, #16a34a 0%, #22c55e 100%);
            }
            .invoice-progress-label {
                font-size: 12px;
                font-weight: 600;
                color: #475569;
                min-width: 32px;
            }

            /* Edit button and delete icon styles */
            #invoice-detail-modal .btn-edit-items {
                transition: all 0.3s ease;
            }

            #invoice-detail-modal .btn-edit-items:hover {
                opacity: 0.9;
                transform: translateY(-1px);
            }

            #invoice-detail-modal .delete-item-icon:hover {
                color: #c82333 !important;
                transform: scale(1.1);
            }

            /* Show More/Less styles for invoice modal */
            #invoice-detail-modal .hidden-item {
                display: none;
                opacity: 0;
                max-height: 0;
                overflow: hidden;
                transition: opacity 0.4s ease-in-out, max-height 0.5s ease-in-out;
            }

            #invoice-detail-modal .hidden-item.show {
                display: table-row !important;
                opacity: 1;
                max-height: 500px;
            }

            #invoice-detail-modal .btn-show-more {
                background: none;
                border: none;
                color: #2563eb;
                cursor: pointer;
                padding: 8px 16px;
                font-size: 14px;
                transition: all 0.3s ease;
            }

            #invoice-detail-modal .btn-show-more:hover {
                color: #1d4ed8;
                text-decoration: underline;
            }

            #invoice-detail-modal .btn-show-more.active .show-more-icon {
                display: none;
            }

            #invoice-detail-modal .btn-show-more.active .show-less-icon {
                display: inline-block !important;
            }

            #invoice-detail-modal .btn-show-more.active .show-more-text {
                display: none;
            }

            #invoice-detail-modal .btn-show-more.active .show-less-text {
                display: inline !important;
            }

            #invoice-detail-modal .order-processing {
                opacity: 0.65;
                cursor: not-allowed;
                pointer-events: auto;
            }

            #invoice-detail-modal .order-processing-msg {
                margin-bottom: 8px;
            }

            /* Order processing message modal */
            .order-msg-modal {
                position: fixed;
                inset: 0;
                z-index: 10000;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.25s ease, visibility 0.25s ease;
            }

            .order-msg-modal.is-open {
                opacity: 1;
                visibility: visible;
            }

            .order-msg-modal-backdrop {
                position: absolute;
                inset: 0;
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(4px);
            }

            .order-msg-modal-dialog {
                position: relative;
                width: 100%;
                max-width: 400px;
                transform: scale(0.9);
                transition: transform 0.25s ease;
            }

            .order-msg-modal.is-open .order-msg-modal-dialog {
                transform: scale(1);
            }

            .order-msg-modal-content {
                background: #fff;
                border-radius: 16px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                padding: 2rem 1.75rem;
                text-align: center;
            }

            .order-msg-modal-icon {
                width: 56px;
                height: 56px;
                margin: 0 auto 1.25rem;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
                border-radius: 50%;
                color: #d97706;
                font-size: 1.5rem;
            }

            .order-msg-modal-title {
                font-size: 1.35rem;
                font-weight: 700;
                color: #0f172a;
                margin: 0 0 0.5rem;
            }

            .order-msg-modal-text {
                font-size: 0.9375rem;
                color: #64748b;
                line-height: 1.5;
                margin: 0 0 15px;
            }

            .order-msg-modal-btn {
                background: linear-gradient(135deg, #06283f 0%, #0c4a6e 100%);
                color: #fff;
                border: none;
                padding: 10px 24px;
                border-radius: 10px;
                font-size: 0.9375rem;
                font-weight: 600;
                cursor: pointer;
                transition: opacity 0.2s, transform 0.15s;
            }

            .order-msg-modal-btn:hover {
                opacity: 0.95;
            }

            .order-msg-modal-btn:active {
                transform: scale(0.98);
            }
        </style>

        <script>
            $(document).ready(function() {
                // Handle row click
                $('.invoice-row').on('click', function() {
                    var orderId = $(this).data('order-id');
                    openInvoiceModal(orderId);
                });
            });

            function openInvoiceModal(orderId) {
                $('#invoice-detail-modal').fadeIn(300);
                $('body').css('overflow', 'hidden');

                // Load invoice details via AJAX
                $.ajax({
                    url: '{{ route('panel.invoice.details') }}',
                    type: 'GET',
                    data: {
                        order_id: orderId
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#invoice-modal-content').html(response.html);
                    },
                    error: function(xhr) {
                        $('#invoice-modal-content').html(
                            '<div style="text-align: center; padding: 40px; color: #dc3545;">' +
                            '<i class="fa fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 20px;"></i>' +
                            '<p>Error loading invoice details. Please try again.</p>' +
                            '</div>'
                        );
                    }
                });
            }

            function closeInvoiceModal() {
                $('#invoice-detail-modal').fadeOut(300);
                $('body').css('overflow', 'auto');
            }

            // Close modal when clicking outside
            $(document).on('click', '.invoice-modal-overlay', function(e) {
                if ($(e.target).hasClass('invoice-modal-overlay')) {
                    closeInvoiceModal();
                }
            });

            // Close modal with ESC key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $('#invoice-detail-modal').is(':visible')) {
                    closeInvoiceModal();
                }
            });

            // Show More/Less functionality for invoice modal
            $(document).on('click', '#invoice-detail-modal .btn-show-more', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var serviceId = $(this).data('service');
                var $button = $(this);
                var $hiddenItems = $('#invoice-detail-modal .hidden-item[data-service="' + serviceId + '"]');

                if ($button.hasClass('active')) {
                    // Hide items
                    $hiddenItems.each(function(index) {
                        var $item = $(this);
                        setTimeout(function() {
                            $item.removeClass('show');
                        }, index * 30);
                    });
                    $button.removeClass('active');
                    // Update button text
                    $button.find('.show-more-text').show();
                    $button.find('.show-less-text').hide();
                    $button.find('.show-more-icon').show();
                    $button.find('.show-less-icon').hide();
                } else {
                    // Show items
                    $hiddenItems.each(function(index) {
                        var $item = $(this);
                        setTimeout(function() {
                            $item.addClass('show');
                        }, index * 30);
                    });
                    $button.addClass('active');
                    // Update button text
                    $button.find('.show-more-text').hide();
                    $button.find('.show-less-text').show();
                    $button.find('.show-more-icon').hide();
                    $button.find('.show-less-icon').show();
                }
            });

            // PDF Download functionality for invoice modal
            $(document).on('click', '#invoice-detail-modal .go-pdf-download', function() {
                var $button = $(this);
                var $spinner = $button.find('.fa-spinner');

                // Show loading spinner
                $spinner.show();
                $button.prop('disabled', true);

                // Show all hidden items before printing
                $('#invoice-detail-modal .hidden-item').addClass('show');

                // Use browser's print dialog (which can save as PDF)
                setTimeout(function() {
                    window.print();
                    $spinner.hide();
                    $button.prop('disabled', false);
                }, 500);
            });

            // Edit button functionality - toggle delete icons
            $(document).on('click', '#invoice-detail-modal .btn-edit-items', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var $button = $(this);
                var serviceId = $button.data('service');
                var $deleteIcons = $('#invoice-detail-modal .delete-item-icon[data-service="' + serviceId + '"]');

                // If no service-specific icons found, get all delete icons in the modal
                if ($deleteIcons.length === 0) {
                    $deleteIcons = $('#invoice-detail-modal .delete-item-icon');
                }

                if ($button.hasClass('active')) {
                    // Hide delete icons
                    $deleteIcons.fadeOut(200);
                    $button.removeClass('active');
                    $button.find('.edit-text').show();
                    $button.find('.cancel-text').hide();
                    $button.css('background', '#2563eb');
                } else {
                    // Show delete icons
                    $deleteIcons.fadeIn(200);
                    $button.addClass('active');
                    $button.find('.edit-text').hide();
                    $button.find('.cancel-text').show();
                    $button.css('background', '#dc3545');
                }
            });

            // Order processing: block Payments and Delete when status == 1, show message modal on click
            $(document).on('click',
                '#invoice-detail-modal .go-pay.order-processing, #invoice-detail-modal .delete-cart-item.order-processing',
                function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openOrderProcessingModal();
                    return false;
                });

            function openOrderProcessingModal() {
                $('#order-processing-msg-modal').addClass('is-open').css('display', 'flex');
                $('body').css('overflow', 'hidden');
            }

            function closeOrderProcessingModal() {
                $('#order-processing-msg-modal').removeClass('is-open').css('display', 'none');
                $('body').css('overflow', '');
            }
            $(document).on('click', '#order-processing-msg-modal .order-msg-modal-backdrop', closeOrderProcessingModal);
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $('#order-processing-msg-modal').hasClass('is-open')) {
                    closeOrderProcessingModal();
                }
            });

            // Delete item functionality
            $(document).on('click', '#invoice-detail-modal .delete-item-icon', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var itemId = $(this).data('item-id');
                var $row = $(this).closest('tr');

                if (confirm('Are you sure you want to delete this item?')) {
                    // Add delete functionality here
                    // For now, just remove the row
                    $row.fadeOut(300, function() {
                        $(this).remove();
                    });
                }
            });
        </script>


    </div>
</div>
