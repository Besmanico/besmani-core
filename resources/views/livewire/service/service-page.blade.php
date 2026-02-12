<section class="site-section subpage-site-section section-contact-us">

    <style>
        .service-back-row {
            margin-bottom: 12px;
        }
        .service-back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.6);
            background: #ffffff;
            color: #374151;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            text-decoration: none;
            letter-spacing: 0.06em;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
            transition: background 0.2s ease, box-shadow 0.2s ease, transform 0.15s ease, color 0.2s ease, border-color 0.2s ease;
        }
        .service-back-btn i {
            font-size: 13px;
        }
        .service-back-btn:hover {
            background: #f3f4f6;
            color: #111827;
            border-color: #9ca3af;
            transform: translateY(-1px);
            text-decoration: none;
        }
        .service-hero-row {
            display: flex;
            align-items: center;
            gap: 32px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        .service-hero-image {
            flex: 0 1 286px;
            min-width: 260px;
        }
        .service-hero-image img {
            max-width: 320px;
            width: 100%;
            height: auto;
            border-radius: 16px;
            box-shadow: 0 14px 35px rgba(15, 23, 42, 0.12);
            display: block;
            margin: 0 auto;
        }
        .service-hero-text {
            flex: 1 1 360px;
            min-width: 260px;
        }
        .service-hero-text h2 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 14px;
            color: #111827;
        }
        .service-hero-text .service-body {
            font-size: 14px !important;
            line-height: 1.7;
            color: #4b5563;
            margin: 0;
            line-height: 22px !important;
        } 
        .service-hero-text .service-body  b{
           width: 100%;
           display: block;
           
        } 
        .service-hero-text .service-body strong {
            display: block;
            font-weight: 700;
            color: #111827;
            margin-top: 8px;
        }
        @media (max-width: 767px) {
            .service-hero-row {
                gap: 20px;
            }
            .service-hero-text h2 {
                font-size: 22px;
                text-align: left;
            }
        }
    </style> 

    <div class="container">
        <div class="row service-back-row">
            <div class="col-xs-12">
                <a href="{{ url()->previous() }}" class="service-back-btn">
                    <i class="fa fa-arrow-left"></i>
                    <span>Back</span>
                </a>
            </div>
        </div>
        <div class="row service-hero-row">
            <div class="col-sm-5 service-hero-image">
                <img src="{{ config('app.url') }}storage/<?= $service['image'] ?>" alt="<?= $service['title'] ?>">
            </div>

            <div class="col-sm-6 service-hero-text">
                <h2><?= $service['title'] ?></h2>
                <p class="service-body">
                    <?= $service['body'] ?>
                </p>
            </div>
        </div>
        <div class="besmo-section-divider">
            <div class="besmo-section-divider-line"></div>
        </div>
 
        @php
        
            $rawPackageServices = optional($service)->packageServices;
            $packageServices = collect($rawPackageServices)->values();
                
        @endphp

        <section class="section-services tab-request-user text-center">

            @php
                $currencySymbol = config('app.currency_symbol');
                if (blank($currencySymbol)) {
                    $currencySymbol = '$';
                }

                $normalizedPackages = [];

                // Build normalized packages array
                foreach ($packageServices as $index => $packageService) {
                    $packageKey = 'package_' . $index;

                    $rawPrice = $packageService->price;
                    $priceDisplay = null;
                    if (!is_null($rawPrice) && $rawPrice !== '') {
                        $priceDisplay = is_numeric($rawPrice)
                            ? ($currencySymbol ? $currencySymbol : '') . number_format((float) $rawPrice, 2)
                            : $rawPrice;
                    }

                    $normalizedPackages[] = [
                        'key' => $packageKey,
                        'model' => $packageService,
                        'price_display' => $priceDisplay,
                    ];
                }

                // Directly get all package service items for each package without name matching

            @endphp

            @if ($normalizedPackages)
                <div class="pricing-comparison">
                    <div class="pricing-comparison__row pricing-comparison__row--packages">
                        @foreach ($normalizedPackages as $package)
                            @php
                                /** @var \App\Models\PackageService $packageService */
                                $packageService = $package['model'];

                                // Directly get all package service items for this package with quantity
                                $packageFeatures = collect($packageService->packageServiceItems ?? [])
                                    ->map(function ($item) {
                                        $name = trim($item->name ?? '');
                                        if (blank($name)) {
                                            return null;
                                        }
                                        return [
                                            'name' => $name,
                                            'quantity' => $item->quantity ?? 0
                                        ];
                                    })
                                    ->filter(function ($item) {
                                        return $item !== null;
                                    })
                                    ->values()
                                    ->toArray();
                            @endphp
                            <div class="pricing-comparison__cell pricing-comparison__cell--package">
                                {{-- @if (!Auth::guard('mainUsers')->check())
                                    <a class="w-100" onclick="storeRedirectUrlAndOpenLogin('{{ config('app.url') }}order/{{ $packageService->id }}/{{ $service['id'] }}')">
                                    @else
                                        <a class="w-100"
                                            href="{{ config('app.url') }}order/{{ $packageService->id }}/{{ $service['id'] }}">
                                @endif --}}

                                {{-- @if (!blank($packageService->title)) --}}
                                    <div class="pricing-comparison__title">{{ $packageService->title }}</div>
                                {{-- @endif --}}
                                {{-- @if (!blank($package['price_display'])) --}}
                                    <div class="pricing-comparison__price">{{ $package['price_display'] }}</div>
                                {{-- @endif --}}

                                @if (!blank($packageService->description))
                                    @php
                                        $description = $packageService->description;
                                        $descriptionLength = strlen($description);
                                        $maxLength = 25;
                                        $isLong = $descriptionLength > $maxLength;
                                        $shortDescription = $isLong
                                            ? substr($description, 0, $maxLength) . '...'
                                            : $description;
                                        $modalId = 'description-modal-' . $packageService->id;
                                    @endphp
                                    <div class="pricing-comparison__description">
                                        <span class="description-text">{{ $shortDescription }}</span>
                                        @if ($isLong)
                                            <a href="javascript:void(0)" class="description-more-link"
                                                data-title="{{ htmlspecialchars($packageService->title ?? 'Package Description', ENT_QUOTES, 'UTF-8') }}"
                                                data-description="{{ htmlspecialchars($description, ENT_QUOTES, 'UTF-8') }}">
                                                More...
                                            </a>
                                        @endif
                                    </div>
                                @endif


                                @if (!blank($packageService->delivery))
                                    <div class="pricing-comparison__delivery">Delivery: {{ $packageService->delivery }} Days
                                    </div>
                                @endif

                                @if (count($packageFeatures) > 0)
                                    <div class="pricing-comparison__features">
                                        @foreach ($packageFeatures as $feature)
                                            @php
                                                $quantity = $feature['quantity'] ?? 0;
                                                $isAvailable = $quantity > 0;
                                            @endphp
                                            <span class="pricing-comparison__feature-item {{ $isAvailable ? 'feature-available' : 'feature-unavailable' }}">
                                                @if ($isAvailable)
                                                    <span class="feature-icon feature-check">✓</span>
                                                @else
                                                    <span class="feature-icon feature-cross">✕</span>
                                                @endif
                                                {{ $feature['name'] }} ({{ $feature['quantity'] }})
                                            </span>
                                        @endforeach
                                       

                                    </div>
                                    @if (!Auth::guard('mainUsers')->check())
                                        {{-- <button onclick="storeRedirectUrlAndOpenLogin('{{ config('app.url') }}order/{{ $packageService->id }}/{{ $service['id'] }}')"
                                            class="btn btn-green btn-order-now w-100">Order Now</button> --}}

                                            <button onclick="storeRedirectUrlAndOpenLogin('{{ config('app.url') }}order/{{ $packageService->id }}/{{ $service['id'] }}')"
                                                class="btn btn-green btn-order-now w-100">Add to Cart</button>

                                    @else
                                        {{-- <a class="w-100"
                                            href="{{ config('app.url') }}order/{{ $packageService->id }}/{{ $service['id'] }}">
                                            <button class="btn btn-green btn-order-now w-100">Order Now</button>
                                        </a> --}}

                                       
                                        <button onclick="addToCart('{{ $packageService->id }}', '{{ $service['id'] }}', this)" class="btn btn-green btn-order-now w-100">
                                            <span class="button-text">Add to Cart</span>
                                            <i class="fa fa-spinner fa-spin" style="display: none; margin-left: 8px;"></i>
                                        </button>
                                    
                                        
                                    @endif
                                @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="pricing-comparison__empty">
                    More packages will be available soon. Stay tuned!
                </div>
            @endif
        </section>





    </div>

    </div>
    </div>


    <!-- Description Modal -->
    <div id="description-modal" class="description-modal-overlay" style="display: none;">
        <div class="description-modal-container">
            <button class="description-modal-close" onclick="closeDescriptionModal()">×</button>
            <div class="description-modal-header">
                <div class="description-modal-title" id="description-modal-title">Package Description</div>
            </div>
            <div class="description-modal-body">
                <div class="description-modal-content" id="description-modal-content"></div>
            </div>

        </div>
    </div>


    <script>
        //    addToCart
        function addToCart(package_service_id, service_id, buttonElement) {
            var $button = $(buttonElement);
            var $spinner = $button.find('.fa-spinner');
            var $buttonText = $button.find('.button-text');
            
            // Show loading spinner and disable button
            $spinner.show();
            $button.prop('disabled', true);
            $button.css('opacity', '0.7');
            $button.css('cursor', 'not-allowed');

            $.ajax({
                url: '{{ route('addToCart') }}',
                type: 'POST',
                data: {
                    service_id: service_id,
                    package_service_id: package_service_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Hide loading spinner and re-enable button
                    $spinner.hide();
                    $button.prop('disabled', false);
                    $button.css('opacity', '1');
                    $button.css('cursor', 'pointer');
                    
                    // Show success modal
                    if (response.success) {
                        window.location.href = '{{ route('cart') }}';
                        // openCartSuccessModal();
                    }
                },
                error: function(response) {
                    // Hide loading spinner and re-enable button
                    $spinner.hide();
                    $button.prop('disabled', false);
                    $button.css('opacity', '1');
                    $button.css('cursor', 'pointer');
                    
                    // Show error message
                    alert('An error occurred. Please try again.');
                }
            });
        }
    </script> 

    <script>
        // Store redirect URL and open login modal
        function storeRedirectUrlAndOpenLogin(redirectUrl) {
            // Store the intended URL in localStorage
            localStorage.setItem('loginRedirectUrl', redirectUrl);
            // Open login modal
            if (typeof openLoginModal === 'function') {
                openLoginModal();
            } else {
                // Fallback if openLoginModal is not available
                $('#login-modal').fadeIn(300);
                $('body').css('overflow', 'hidden');
            }
        }

        // Description Modal Functions - Global scope
        function openDescriptionModal(modalId, title, description) {
            $('#description-modal-title').text(title);
            $('#description-modal-content').text(description);
            $('#description-modal').fadeIn(300);
            $('body').css('overflow', 'hidden');
        }

        function closeDescriptionModal() {
            $('#description-modal').fadeOut(300);
            $('body').css('overflow', 'auto');
        }

        $(function() {
            $('.goReq').on('click', function() {
                $('.formReq').slideToggle();
            });

            $('.goSaveReq').on('click', function() {
                var error = 0;
                var service = '<?= $service['id'] ?>';
                var reqDescription = $("#req-description").val();

                if (reqDescription === '') {
                    error = 1;
                    $("#req-description").focus();
                }
                if (error === 1) {
                    return false;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: '{{ config('app.url') }}services/AddRequest',
                    type: 'POST',
                    async: false,
                    data: {
                        reqDescription: reqDescription,
                        service: service
                    }
                }).done(function(msg) {
                    $("#req-description").val('');
                    $(".msg-success").slideDown();
                });
            });

            // Handle More... link clicks
            $(document).on('click', '.description-more-link', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                var title = $(this).data('title') || 'Package Description';
                var description = $(this).data('description') || '';
                openDescriptionModal(null, title, description);
                return false;
            });

            // Prevent parent anchor from being triggered when clicking More... link
            $(document).on('click', '.pricing-comparison__cell--package > a', function(e) {
                if ($(e.target).hasClass('description-more-link') || $(e.target).closest(
                        '.description-more-link').length > 0) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });

            // Close modal when clicking outside
            $(document).on('click', '#description-modal', function(e) {
                if ($(e.target).hasClass('description-modal-overlay')) {
                    closeDescriptionModal();
                }
            });

            // Close modal with Escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $('#description-modal').is(':visible')) {
                    closeDescriptionModal();
                }
            });
        });
    </script>

    <style>
        /* Feature Item Quantity Indicators */
        .pricing-comparison__feature-item {
            display: flex !important;
            align-items: center;
            gap: 8px;
            margin-bottom: 0.875rem;
            line-height: 1.6;
            font-size: 1.4rem;
            color: #4a5568;
            font-weight: 400;
            padding: 0.5rem 0;
            position: relative;
            padding-left: 0 !important; /* Remove default padding */
        }

        /* Remove the default ::before checkmark */
        .pricing-comparison__feature-item::before {
            display: none !important;
        }

        .feature-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            font-size: 10px;
            font-weight: bold;
            flex-shrink: 0;
            line-height: 1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        }

        .feature-check {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
        }

        .feature-cross {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
        }

        .feature-available {
            color: #1f2937;
        }

        .feature-unavailable {
            color: #6b7280;
            opacity: 0.8;
        }
    </style>

</section><!-- /.section-contact-us -->
