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
                                <th>Tracking Code</th>
                                <th>Tax & Fee</th>
                                <th>Discount</th>
                                <th>Total</th>
                                 <th>Date</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <tr class="invoice-row" 
                                data-order-id="{{ $order->id }}"
                                data-tracking-code="{{ $order->tracking_code }}"
                                style="cursor: pointer;">
                                <td>
                                    <span class="invoice-id">{{ $order->tracking_code }}</span>
                                </td>
                                <td>
                                    <b class="invoice-amount color-dark">${{ number_format($order->tax_fee, 2) }}</b>
                                </td>
                                <td>
                                    <span class="invoice-amount color-red">${{ number_format($order->discount, 2) }}</span>
                                </td>
                                <td>
                                    <span class="invoice-amount color-green font-bold">${{ number_format($order->total_payment, 2) }}</span>
                                </td>
                                
                                <td>
                                    <span class="invoice-date">{{ $order->created_at->format('Y-m-d') }}</span>
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
                url: '{{ route("panel.invoice.details") }}',
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
