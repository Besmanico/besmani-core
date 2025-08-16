<div>

    <section class="section-beauty get-area three " style="background: #fff; position: relative; overflow: hidden; ">

        {{-- <section class="site-section section-beauty get-area three"
        style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%); position: relative; overflow: hidden; padding: 40px 0;"> --}}

        {{-- <div class="bg-section"
            style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1; background: url('{{ config('app.url') }}assets-file/img/adv/bg-blue.JPG') repeat; background-size: cover; opacity: 0.8;">
        </div> --}}

        <div class="container mt-20" style="position: relative; z-index: 2;">

            <!-- Services Grid Section -->
            <div class="row" style="margin-bottom: 40px; gap: 20px;">

                {{-- p-95 --}}
                <!-- Beauty Service - Left Large -->
                <div class="col-lg-6 col-md-6 col-sm-12 mb-4 pr-0">
                    <a href="https://beauty.besmani.com/" target="_blank">
                        <div class="service-card hover-remove-effect"
                            style="height: 600px; background:url('{{ asset('assets-file/img/adv/b-girl.jpg') }}') center/cover; border-radius: 20px; position: relative;  box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: all 0.3s ease;  justify-content: center; align-items: center; text-align: center;"
                            onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 40px rgba(0,0,0,0.15)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.1)'">
                            <div class="service-card-filter-cover"></div>
                            <div style="margin-bottom: 5px;">

                                <img src="{{ asset('assets-file/img/adv/b-logo-dark.png') }}" class="img-beauty-logo">
                            </div>
                            <div class="beauty-title-text" style="padding: 0 30px;">
                                <p class="beauty-title-text-title">Beauty Besmani</p>

                                <p class="beauty-title-text-description">
                                    Beauty is your ultimate beauty <br> destination</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Petaban Service - Top Right -->
                <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                    <div class="service-card"
                        style="height: 280px; background:  url('{{ asset('assets-file/img/adv/dog.jpg') }}') center/cover; border-radius: 20px; position: relative;  box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: all 0.3s ease;  justify-content: center; align-items: center; text-align: center;"
                        onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 40px rgba(0,0,0,0.15)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.1)'">
                        <div class="service-card-filter-cover"></div>

                        <div style="margin-bottom: 20px;">

                            <img src="{{ asset('assets-file/img/adv/pet-logo.png') }}" class="img-beauty-logo">
                        </div>
                        <div style="margin-bottom: 15px;">

                            <h3
                                style="color: #222; font-size: 2rem; font-weight: bold; margin: 0; font-family: 'Quicksand', sans-serif;">
                                A safe way for your pet</h3>
                        </div>

                    </div>
                </div>

                <!-- Store Service - Bottom Right -->
                <div class="col-lg-6 col-md-6 col-sm-12 mb-4 mt-40">
                    <div class="service-card mt-sm-store-card"
                        style="height: 280px; background: url('{{ asset('assets-file/img/adv/store.jpg') }}') center/cover; border-radius: 20px; position: relative;  box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: all 0.3s ease;  justify-content: center; align-items: center; text-align: center;"
                        onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 40px rgba(0,0,0,0.15)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.1)'">
                        <div class="service-card-filter-cover"></div>

                        <div style="margin-bottom: 20px;">

                            <img src="{{ asset('assets-file/img/adv/logo-store-black.png') }}" class="img-store-logo">
                        </div>

                        <div style="padding: 0 20px;">

                            <h3 class="mt-60"
                                style="color: #222; font-size: 2rem; font-weight: bold; font-family: 'Quicksand', sans-serif;">
                                Safe shopping for you
                            </h3>
                        </div>
                    </div>
                </div>

                <!-- Travel Banner - Full Width Bottom -->
                <div class="col-lg-12 col-md-12 col-sm-12 mb-4 mt-40 mt-travel">

                    <div class="service-card"
                        style="height: 220px; background: url('{{ asset('assets-file/img/adv/travel.jpeg') }}') center/cover; border-radius: 20px; position: relative;  box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: all 0.3s ease;  align-items: center; justify-content: center; text-align: center;"
                        onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 40px rgba(0,0,0,0.15)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.1)'">
                            
                        <div style="margin-bottom: 20px;">

                            <img src="{{ asset('assets-file/img/adv/travel-logo.png') }}" class="img-travel-logo">
                        </div>

                        <br>

                        <div>
                            <h3
                                style="color: #000; font-size: 20px; font-weight: bold; margin: 0; font-family: 'Inter', sans-serif;  ">
                                Travel site for immigration
                            </h3>


                        </div>

                    </div>
                </div>

            </div>

 

            {{-- owl-carousel --}}

            <div class="container">
                <div class="row">
                    <div class="beauty-slider owl-theme owl-carousel">

                        <div class="offer-item">
                            <a href="https://beauty.besmani.com/signup" target="_blank">
                                <img src="{{ config('app.url') }}assets-file/img/work/sing-up-k.png" class="img-res">
                            </a>
                        </div>
                        <!-- item -->
                        <div class="offer-item">
                            <img src="{{ config('app.url') }}assets-file/img/work/u-acc-k.png" class="img-res">

                        </div>
                        <!-- item -->
                        <div class="offer-item">
                            <img src="{{ config('app.url') }}assets-file/img/work/m-k.png" class="img-res">

                        </div>
                        <!-- item -->


                        <div class="offer-item">
                            <img src="{{ config('app.url') }}assets-file/img/work/hapally1.jpg" class="img-res">

                        </div>
                        <!-- item -->

                        <div class="offer-item">
                            <img src="{{ config('app.url') }}assets-file/img/besmo.jpg" class="img-res">

                        </div>
                        <!-- item -->
                        <div class="offer-item">
                            <img src="{{ config('app.url') }}assets-file/img/portfolio-1.jpg" class="img-res">

                        </div>
                        <!-- item -->

                        <div class="offer-item">
                            <img src="{{ config('app.url') }}assets-file/img/portfolio-12.jpg" class="img-res">

                        </div>
                        <!-- item -->
                        <div class="offer-item">
                            <img src="{{ config('app.url') }}assets-file/img/work/immigration.jpg" class="img-res">

                        </div>
                        <!-- item -->
                        <div class="offer-item">
                            <img src="{{ config('app.url') }}assets-file/img/work/beauty.jpg" class="img-res">

                        </div>
                        <!-- item -->

                    </div>
                </div>
            </div>
            {{-- owl-carousel END --}}


        </div>




    </section>




    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script>
        // Offer Slider JS
        $('.beauty-slider').owlCarousel({
            loop: true,
            margin: 15,
            nav: true,
            dots: false,

            smartSpeed: 1000,
            autoplay: false,
            // autoplayTimeout: 4000,
            // autoplayHoverPause: true,
            rtl: false,
            navText: [
                "<i class='fa fa-angle-left'></i>",
                "<i class='fa fa-angle-right'></i>"
            ],
            responsive: {
                0: {
                    items: 3,
                },
                768: {
                    items: 2,
                },
                992: {
                    items: 6,
                }
            }
        });
    </script>

    <style>
        .beauty-slider .offer-item {
            height: 150px;
            background: #fff;
            border-radius: 20px;
             overflow: hidden;
            position: relative;
            transition: all 0.3s ease;
            border: 1px solid #071021;
        }

        /* Beautiful Navigation Icons */
        .beauty-slider .owl-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 100%;
            display: flex;
            justify-content: space-between;
            pointer-events: none;
            z-index: 10;
        }

        .beauty-slider .owl-nav button {
            width: 50px;
            height: 50px;
            background: rgba(251, 251, 251, 0.6) !important;
            border: none !important;
            border-radius: 50% !important;
            color: #000 !important;
            font-size: 18px !important;
            display: flex !important;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            pointer-events: auto;
            margin: 0 20px;
        }

        .beauty-slider .owl-nav button:hover {
            background: rgba(235, 235, 235, 0.6) !important;
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .beauty-slider .owl-nav button:active {
            transform: scale(0.95);
        }

        .beauty-slider .owl-nav button i {
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .beauty-slider .owl-nav .owl-prev {
            left: -25px;
        }

        .beauty-slider .owl-nav .owl-next {
            right: -25px;
        }

        /* Responsive navigation */
        @media (max-width: 768px) {
            .beauty-slider .owl-nav button {
                width: 40px;
                height: 40px;
                font-size: 16px;
                margin: 0 10px;
            }

            .beauty-slider .offer-item { 
                height: 99px;

            }
        }

        .section-beauty {
            box-shadow: 0 -3px 16px 24px #fff;
        }

        .hover-remove-effect:hover .service-card-filter-cover {

            background: none;

        }

        /* Vertical Slider Styles */
        .vertical-slider {
            height: 400px;
            margin-top: 40px;
            display: block !important;
        }

        .vertical-slider .owl-stage-outer {
            height: 100%;
        }

        .vertical-slider .owl-stage {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .vertical-slider .owl-item {
            height: calc(100% / 3);
            margin-bottom: 10px;
        }

        .carousel-item-card {
            height: 100%;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            transition: all 0.3s ease;
        }

        .carousel-item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .carousel-item-card img {
            width: 100%;
            height: 60%;
            object-fit: cover;
        }

        .carousel-content {
            padding: 15px;
            text-align: center;
        }

        .carousel-content h4 {
            color: #2c3e50;
            font-size: 1.2rem;
            font-weight: bold;
            margin: 0 0 8px 0;
            font-family: 'Quicksand', sans-serif;
        }

        .carousel-content p {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin: 0;
            font-family: 'Inter', sans-serif;
        }

        .service-card-filter-cover {
            transition: all 0.5s ease;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(111, 204, 219, 0.6);
            border-radius: 20px;
            z-index: 0;
        }

        .p-95 {
            padding: 95px;
        }

        .mt-40 {
            margin-top: 40px;
        }

        .mt-60 {
            margin-top: 60px;
        }

        .beauty-title-text-title {
            color: #315d8a;
            font-size: 18px;
            margin: 10px 0 0 0;
            letter-spacing: 2px;
            /* font-family: 'Inter', sans-serif; */
        }

        .beauty-title-text-description {
            color: #2c3e50;
            font-size: 19px;
            line-height: 1.4;
            margin-top: 30px;
        }

        .beauty-title-text {
            bottom: 30px;
            position: absolute;
            left: 0;
            right: 0;
            text-align: center;

            padding: 10px;
        }

        .img-travel-logo {
            width: 200px;
            margin-top: 20px;
        }

        .img-beauty-logo {
            width: 200px;
            margin-top: 8px;
        }

        .img-store-logo {
            width: 190px;
            margin-top: 58px;
        }

        @media (max-width: 768px) {
            .mt-travel {
                margin-top: 40px !important;
            }

            .p-95 {
                padding: 0px !important;
            }

            .subscription-form {
                flex-direction: column !important;
            }

            .subscription-form input {
                width: 100% !important;
                max-width: 300px;
            }

            .service-card {
                height: auto !important;
                min-height: 300px !important;
                position: relative;
            }

            .service-card h3 {
                font-size: 1.5rem !important;
            }

            .img-beauty-logo {
                width: 150px !important;
                margin-top: 40px !important;
            }

            .img-travel-logo {
                width: 150px !important;
                margin-top: 15px !important;
            }

            .beauty-title-text-title {
                font-size: 16px !important;
            }

            .beauty-title-text-description {
                font-size: 16px !important;
                margin-top: 20px !important;
            }

            .beauty-title-text {
                bottom: 20px !important;
                padding: 5px !important;
            }

            .mt-40 {
                margin-top: 20px !important;
            }

            .row {
                gap: 15px !important;
            }

            /* Mobile two-column layout */
            .col-lg-6.col-md-6.col-sm-12:nth-child(1) {
                width: 50% !important;
                float: left !important;
            }

            .col-lg-6.col-md-6.col-sm-12:nth-child(2) {
                width: 50% !important;
                float: right !important;
                margin-top: 0 !important;
            }

            .col-lg-6.col-md-6.col-sm-12:nth-child(3) {
                width: 50% !important;
                float: right !important;
                clear: right !important;
                margin-top: 15px !important;
            }

            .col-lg-12.col-md-12.col-sm-12 {
                width: 100% !important;
                float: none !important;
                clear: both !important;
                margin-top: 20px !important;
            }

            /* Adjust heights for mobile layout */
            .col-lg-6.col-md-6.col-sm-12:nth-child(1) .service-card {
                height: 600px !important;
                min-height: 600px !important;
            }

            .col-lg-6.col-md-6.col-sm-12:nth-child(2) .service-card,
            .col-lg-6.col-md-6.col-sm-12:nth-child(3) .service-card {
                height: 280px !important;
                min-height: 280px !important;
            }
        }

        @media (max-width: 576px) {

            .hero-text h1 {
                font-size: 20px !important;
            }

            .hero-text .site-title img {
                width: 180px !important;
            }

            .hero-text p {
                margin-top: 16px !important;
                font-size: 14px !important;
            }

            .pr-0 {
                padding-right: 0 !important;
            }

            .p-95 {
                padding: 0 !important;
            }

            .service-card {
                min-height: 160px !important;
            }

            .service-card h3 {
                font-size: 1.3rem !important;
            }

            .img-beauty-logo {
                width: 120px !important;
                margin-top: 30px !important;
            }

            .img-travel-logo {
                width: 120px !important;
                margin-top: 10px !important;
            }

            .beauty-title-text-title {
                font-size: 14px !important;
            }

            .beauty-title-text-description {
                font-size: 14px !important;
                margin-top: 15px !important;
            }

            .beauty-title-text {
                bottom: 15px !important;
                padding: 5px !important;
            }

            .mt-40 {
                margin-top: 60px !important;
            }

            .row {
                gap: 10px !important;
            }

            /* Maintain two-column layout on smaller screens */
            .col-lg-6.col-md-6.col-sm-12:nth-child(1) {
                width: 50% !important;
                float: left !important;
                margin-bottom: 18px !important;
            }

            .col-lg-6.col-md-6.col-sm-12:nth-child(2) {
                width: 50% !important;
                float: right !important;
                margin-top: 0 !important;
            }

            .col-lg-6.col-md-6.col-sm-12:nth-child(3) {
                width: 50% !important;
                float: right !important;
                clear: right !important;
                margin-top: 10px !important;
            }

            .col-lg-12.col-md-12.col-sm-12 {
                width: 100% !important;
                float: none !important;
                clear: both !important;
                margin-top: 15px !important;
            }

            /* Adjust heights for smaller screens */
            .col-lg-6.col-md-6.col-sm-12:nth-child(1) .service-card {
                height: 500px !important;
                min-height: 500px !important;
            }

            .col-lg-6.col-md-6.col-sm-12:nth-child(2) .service-card,
            .col-lg-6.col-md-6.col-sm-12:nth-child(3) .service-card {
                height: 240px !important;
                min-height: 240px !important;
            }
        }
    </style>



</div>
