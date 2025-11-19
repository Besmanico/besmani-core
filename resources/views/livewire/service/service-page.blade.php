<section class="site-section subpage-site-section section-contact-us">

    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                <img src="{{ config('app.url') }}storage/<?= $service['image'] ?>" alt="<?= $service['title'] ?>"
                    class="w-100">
            </div>


            <div class="col-sm-1"></div>
            <div class="col-sm-5">
                <h2><?= $service['title'] ?></h2>
                <p class="service-body">
                    <?= $service['body'] ?>
                </p>

            </div>

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

                                // Directly get all package service items for this package
                                $packageFeatures = collect($packageService->packageServiceItems ?? [])
                                    ->map(function ($item) {
                                        return trim($item->name ?? '');
                                    })
                                    ->filter(function ($name) {
                                        return !blank($name);
                                    })
                                    ->values()
                                    ->toArray();
                            @endphp
                            <div class="pricing-comparison__cell pricing-comparison__cell--package">
                                @if (!Auth::guard('mainUsers')->check())
                                    <a class="w-100" onclick="openLoginModal()">
                                    @else
                                        <a class="w-100"
                                            href="{{ config('app.url') }}order/{{ $packageService->id }}/{{ $service['id'] }}">
                                @endif

                                @if (!blank($packageService->title))
                                    <div class="pricing-comparison__title">{{ $packageService->title }}</div>
                                @endif
                                @if (!blank($package['price_display']))
                                    <div class="pricing-comparison__price">{{ $package['price_display'] }}</div>
                                @endif



                                @if (count($packageFeatures) > 0)
                                    <div class="pricing-comparison__features">
                                        @foreach ($packageFeatures as $feature)
                                            <span class="pricing-comparison__feature-item">{{ $feature }}</span>
                                        @endforeach

                                    </div>
                                    <button class="btn btn-green btn-order-now w-100">Order Now</button>
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

</section><!-- /.section-contact-us -->

<script>
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
    });
</script>
