<div class="besmo-page">
    <style>
        .besmo-page .besmo-video-index {
            margin-top: 16px;
        }

        .besmo-page .section-beauty {
            overflow: inherit !important;
        }

        .besmo-page .movies-video-besmani {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .besmo-page .movies-video-besmani video {
            width: 100%;
            height: auto;
            min-height: 200px;
            border-radius: 14px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.08);
            background: #111;
            display: block;
        }

        .besmo-page .besmo-img {
            position: relative;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .besmo-page .besmo-img:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.18), 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .besmo-page .besmo-img img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 14px;
        }

        .besmo-page .video-btn:hover {
            background-color: #fe0001 !important;
            color: #fff !important;
        }

        .besmo-page .video-btn:hover::before,
        .besmo-page .video-btn:hover::after {
            background-color: #fe0001 !important;
        }

        .besmo-page .besmo-gallery {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .besmo-page .besmo-gallery-item {
            display: block;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            aspect-ratio: 1;
        }

        .besmo-page .besmo-gallery-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        }

        .besmo-page .besmo-gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }

        .besmo-page .besmo-gallery-item:hover img {
            transform: scale(1.05);
        }

        @media (max-width: 767px) {
            .besmo-page .besmo-gallery {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
        }

        /* Left column video: title, subtitle, container */
        .besmo-page .left-column-video {
            padding: 24px 20px 28px;
            background: linear-gradient(180deg, #fafafa 0%, #f5f5f5 100%);
            border-radius: 16px;
            border: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        }

        .besmo-page .left-column-video-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            letter-spacing: 0.02em;
            margin: 0 0 8px 0;
            line-height: 1.3;
        }

        .besmo-page .left-column-video-title-span {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
            letter-spacing: 0.02em;
            margin: 0 0 8px 0;
            line-height: 1.3;
        }

        .besmo-page .left-column-video-subtitle {
            font-size: 14px;
            color: #555;
            line-height: 1.5;
            margin: 0 0 20px 0;
            padding-bottom: 18px;
            border-bottom: 2px solid #fe0001;
        }

        .besmo-page .left-column-video .besmo-video-index {
            margin-top: 0;
        }

        /* Event column slide: category strip */
        .besmo-page .event-column-silde {
            margin-top: 32px;
            /* margin-bottom: 24px; */
        }

        .besmo-page .event-column-silde .row {
            display: flex;
            flex-wrap: nowrap;
            gap: 12px;
            justify-content: center;
        }

        .besmo-page .event-column-silde [class*="col-"] {
            padding-left: 6px;
            padding-right: 6px;
            flex: 1 1 0;
            min-width: 0;
        }

        .besmo-page .event-column-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100px;
            padding: 20px 14px;
            background: linear-gradient(145deg, #ffffff 0%, #f8f8f8 100%);
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease, background 0.25s ease;
        }

        .besmo-page .event-column-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(254, 0, 1, 0.12), 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: rgba(254, 0, 1, 0.25);
            background: linear-gradient(145deg, #fff 0%, #fff5f5 100%);
        }

        .besmo-page .event-column-icon {
            font-size: 26px;
            color: #fe0001;
            margin-bottom: 10px;
            transition: transform 0.25s ease;
        }

        .besmo-page .event-column-item:hover .event-column-icon {
            transform: scale(1.1);
        }

        .besmo-page .event-column-label {
            font-size: 13px;
            font-weight: 600;
            color: #1a1a1a;
            text-align: center;
            line-height: 1.35;
            letter-spacing: 0.02em;
        }

        @media (max-width: 767px) {
            .besmo-page .event-column-silde .row {
                flex-wrap: wrap;
            }

            .besmo-page .event-column-silde [class*="col-"] {
                flex: 1 1 calc(50% - 6px);
            }

            .besmo-page .event-column-item {
                min-height: 88px;
                padding: 16px 10px;
            }

            .besmo-page .event-column-icon {
                font-size: 22px;
            }

            .besmo-page .event-column-label {
                font-size: 12px;
            }
        }

        @media (min-width: 768px) {
            .col3-desctop {
                width: 20%;
            }
        }

        /* فاصله و خط جداکننده بین سکشن اسلاید و سکشن Besmo */
        .besmo-page .besmo-section-divider {
            margin-top: 48px;
            margin-bottom: 40px;
            padding-top: 0;
        }
        .besmo-page .besmo-section-divider-line {
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, #fe0001 20%, #fe0001 80%, transparent 100%);
            opacity: 0.6; 
            margin: 0 auto 40px;
            max-width: 525px;
            border-radius: 2px;
        } 
        .besmo-page .besmo-section-below {
            margin-top: 8px;
        }
    </style>

    <section class="site-section subpage-site-section section-contact-us">

        <div class="container">
            <h2 class="text-center">Technology at Besmani
            </h2>
            <p class="besmo-lead text-center">
                Where ideas turn into intelligent solutions.
            </p>

            <div class="row event-column-silde">
                <div class="col-xs-6 col-sm-3 col-md col3-desctop">
                    <div class="event-column-item">
                        <i class="fa fa-calendar-check-o event-column-icon"></i>
                        <span class="event-column-label">Events</span>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3 col3-desctop col-md">
                    <div class="event-column-item">
                        <i class="fa fa-microchip event-column-icon"></i>
                        <span class="event-column-label">Artificial Intelligence</span>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3 col3-desctop col-md">
                    <div class="event-column-item">
                        <i class="fa fa-android event-column-icon"></i>
                        <span class="event-column-label">Robotics</span>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3 col3-desctop col-md">
                    <div class="event-column-item">
                        <i class="fa fa-mobile event-column-icon"></i>
                        <span class="event-column-label">Smart Devices</span>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3 col3-desctop col-md">
                    <div class="event-column-item">
                        <i class="fa fa-rocket event-column-icon"></i>
                        <span class="event-column-label">Future Systems</span>
                    </div>
                </div>
            </div>


        </div>
        <div class="besmo-section-divider">
            <div class="besmo-section-divider-line"></div>
        </div>
        <div class="container w-comming">
            <h3 class="text-left">
                What's Coming Next:

            </h3>
            <p class="besmo-lead text-left;">
                Some things arrive when they’re meant to.
            </p>
            @livewire('besmo.slide')

        </div>

        <div class="besmo-section-divider">
            <div class="besmo-section-divider-line"></div>
        </div>
        <div class="container besmo-section-below">
            <div class="row">

                <div class="col-sm-4 left-column-video">
                    <h3 class="left-column-video-title">Besmo:<br>
                        <p class="left-column-video-title-span"> The Finest Robot Technology</p>
                    </h3>
                    <p class="left-column-video-subtitle">
                        The best smart robot, friend, and gift for anyone who wants to break the boundaries to make the
                        smart home era a reality.
                    </p>

                    <div class="besmo-video-index">
                        <div class="movies-video-besmani">

                            <div class="besmo-img">
                                <img src="{{ config('app.url') }}assets-file/img/besmo.jpg" alt="Besmo robot">
                                <div class="play-button">
                                    <a href="{{ config('app.url') }}assets-file/img/file/besmo/besmo.mp4"
                                        class="video-btn">
                                        <i class="fa fa-play"></i>
                                    </a>
                                </div>
                            </div>
                            <video class="w-100" controls preload="metadata" poster="">
                                <source src="{{ config('app.url') }}assets-file/img/file/besmo/1.mp4" type="video/mp4">
                            </video>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8" style="  margin-top: 16px;">
                    <div class="besmo-gallery mt-5">
                        @foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $i)
                            <a href="{{ config('app.url') }}assets-file/img/file/besmo/{{ $i }}.jpg"
                                data-lightbox="besmo" class="besmo-gallery-item">
                                <img src="{{ config('app.url') }}assets-file/img/file/besmo/{{ $i }}.jpg"
                                    alt="Besmo {{ $i }}">
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>



</div>
