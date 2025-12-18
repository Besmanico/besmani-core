<div>
    <style>
        .services-grid-container {
            background-color: #ffffff;
            padding: 60px 0;

        }
        .services-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            max-width: 1190px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .service-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .service-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            transform: translateY(-3px);
            text-decoration: none;
            color: inherit;
        }
        .service-card-image { 
            width: 100%; 
            height: 268px;    
            overflow: hidden;
            background: #f8f9fa;
            position: relative;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }
        .service-card-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(44, 43, 43, 0.85) 0%, rgba(0, 0, 0, 0.5) 50%, rgba(0, 0, 0, 0) 100%);
            z-index: 1;
            transition: opacity 0.3s ease;
        }
        .service-card:hover .service-card-image::before {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.95) 0%, rgba(0, 0, 0, 0.6) 50%, rgba(0, 0, 0, 0) 100%);
        }
        .service-card-image img {
            width: 100%;
            height: 100%;
            /* object-fit: cover; */
            transition: transform 0.3s ease;
        }
        .service-card:hover .service-card-image img {
            transform: scale(1.05);
        }
        .service-card-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            z-index: 2;
        }
        .service-card-title {
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            line-height: 1.3;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
        }
        .service-card-subtitle {
            color: #ffffff;
            font-size: 11px;
            font-weight: 500;
            margin: 0;
            line-height: 1.3;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
            /* text-transform: uppercase; */
        }
        .service-card-price {
            color: #5a6c7d;
            font-size: 14px;
            font-weight: 500;
            margin: 0;
        }
        @media (max-width: 1200px) {
            .services-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }
        }
        @media (max-width: 768px) {
            .services-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
                padding: 0 15px;
            }
            .service-card-image {
                height: 160px;
            }
            .service-card-content {
                padding: 15px;
            }
            .service-card-title {
                font-size: 16px;
            }
            .service-card-price {
                font-size: 13px;
            }
        }
        @media (max-width: 480px) {
            .services-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            .service-card-image {
                height: 140px;
            }
        }
    </style>
    <br>
    <br>
    
    <br>
    <br>
    <section class="site-section subpage-site-section section-contact-us services-grid-container">
        <div class="services-grid">
            <?php
            foreach ($services as $infoService) {
                // Get minimum price from package services if available
                
            ?>
                <a href="{{ config('app.url') }}services/service/<?= $infoService->slug ?>" class="service-card">
                    <div class="service-card-image">
                        <img src="{{ config('app.url') }}storage/<?= $infoService->image ?>" alt="<?= $infoService->title ?>">
                        <div class="service-card-content">
                            <h2 class="service-card-title" style="padding-bottom: 5px; !important;"><?= $infoService->title ?></h2>
                            <small class="service-card-subtitle"><?= $infoService->subtitle ?></small>
                        </div>
                    </div>
                </a>
            <?php } ?>
        </div>
    </section> 
</div>
