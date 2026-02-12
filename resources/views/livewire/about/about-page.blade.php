<div class="about-page">
    <style>
        .about-page .about-section {
            margin-bottom: 56px;
        }
        .about-page .about-section:last-child {
            margin-bottom: 32px;
        }
        .about-page .about-section-inner {
            display: flex;
            align-items: center;
            gap: 40px;
            flex-wrap: wrap;
        }
        .about-page .about-section-inner.reverse { 
            flex-direction: row-reverse;
        }
        .about-page .about-text {
            flex: 1 1 320px;
            min-width: 0;
        }
        .about-page .about-text h2 {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 10px 0;
            padding-bottom: 6px;
            border-bottom: 2px solid #fe0001;
            display: inline-block;
        }
        .about-page .about-text p {
            font-size: 14px;
            line-height: 1.65;
            color: #444;
            margin: 0;
            max-width: 420px;
            text-align: justify;
        }
        .about-page .about-img-wrap {
            flex: 0 1 240px;
            max-width: 190px;
            min-width: 0;
        }
        .about-page .about-img-wrap img {
            width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.05);
            display: block;
        }
        @media (max-width: 767px) {
            .about-page .about-section-inner,
            .about-page .about-section-inner.reverse {
                flex-direction: column;
                gap: 20px;
            }
            .about-page .about-section { margin-bottom: 40px; }
            .about-page .about-text h2 { font-size: 15px; }
            .about-page .about-text p { max-width: none; }
            .about-page .about-img-wrap { max-width: 220px; }
        }
    </style>

    <section class="site-section subpage-site-section section-contact-us">
        <div class="container">
            <h2 class="text-center">About Us
            </h2>
            <div class="row">
                {{-- 1 --}}
                <div class="col-xs-12 about-section"> 
                    <div class="about-section-inner">
                        <div class="about-text">
                            <h2>Who We Are</h2>
                            <p>
                                Besmani is a technology-driven platform built with a long-term vision:
                                to create thoughtful, reliable solutions that connect people, services, and innovation in one
                                evolving ecosystem.
                            </p>
                        </div>
                        <div class="about-img-wrap">
                            <img src="{{ config('app.url') }}assets-file/img/about-1.jpg" alt="Besmani vision">
                        </div>
                    </div>
                </div> 

                {{-- 2 --}}
                <div class="col-xs-12 about-section">
                    <div class="about-section-inner reverse">
                        <div class="about-text">
                            <h2>Our Foundation</h2>
                            <p>
                                Built by a team of engineers and designers with academic roots in leading universities,
                                Besmani is shaped by years of learning and building, grounded in research and proven through real-world experience.
                            </p>
                        </div>
                        <div class="about-img-wrap">
                            <img src="{{ config('app.url') }}assets-file/img/about-2.jpg" alt="Besmani foundation">
                        </div>
                    </div>
                </div>

                {{-- 3 --}}
                <div class="col-xs-12 about-section">
                    <div class="about-section-inner">
                        <div class="about-text">
                            <h2>Our Focus</h2>
                            <p>
                                From digital platforms to intelligent systems,
                                Besmani focuses on building things that grow thoughtfully — shaped by clarity, trust, and purpose.
                            </p>
                        </div>
                        <div class="about-img-wrap">
                            <img src="{{ config('app.url') }}assets-file/img/about-2.jpg" alt="Besmani focus">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
