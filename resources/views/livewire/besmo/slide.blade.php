<div>

    <section class="section-beauty get-area three " style="background: #fff; position: relative; overflow: hidden;margin-top: 7px; ">

        <div class="container w-comming-f" style="position: relative; z-index: 2;">

            {{-- owl-carousel --}}
            <div class="row ">

                <div class="beauty-slider owl-theme owl-carousel">
 

                    <?php
                    $sliders = slidersTechnology()
                    ?>

                    <!-- item -->
                    @foreach ($sliders as $slider)
                        <div class="offer-item">

                            <a href="{{ $slider->link }}">
                                <img src="{{ config('app.url') }}storage/{{ $slider->image }}" class="img-res">
                            </a>
                        </div>
                    @endforeach


                </div>
            </div>

            {{-- owl-carousel END --}}


        </div>

    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script>
        // Offer Slider JS
        $('.beauty-slider').owlCarousel({
            loop: false,
            margin: 25,
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
        .mt-70 {
            margin-top: 70px !important;
        }

        .store-title-text,
        .pet-title-text {
            position: absolute;
            bottom: 55px;
            width: 100%;
            font-size: 19px;
        }

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
            top: 44%;
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
            /* .inMobileRemoveCover{
                display: none;
            } */

            .store-title-text,
            .pet-title-text {

                font-size: 14px;
            }

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

        /* Full screen mobile display */
        /* @media (max-width: 768px) and (orientation: landscape) {
            .inMobileRemoveCover {
                display: none !important;
            }
        } */





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

            width: 120px !important;
            margin-top: 30px !important;
            z-index: 999999999;
            position: absolute;
            margin: 0 auto;
            left: 0;
            right: 0;
        }

        .img-store-logo {
            width: 136px;
            margin-top: 58px;
            z-index: 9999999;
            position: absolute;
            left: 0;
            right: 0;
            margin: 20px auto;
        }

        @media (min-width: 768px) {
            .hover-remove-effect .service-card-filter-cover {
                background: none;

            }
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
