<div>
    
    <section class="site-section subpage-site-section section-contact-us">
    
        <div class="container">
            <?php
            foreach ($services as $infoService) {
            ?>
                <div class="row content-row-services">
                    <a href="{{ config('app.url') }}services/service/<?= $infoService['slug'] ?>" >

                    <div class="col-sm-5">
                        <img src="{{ config('app.url') }}storage/<?= $infoService['image'] ?>" alt="<?= $infoService['title'] ?>" class="w-100">
                    </div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-5">
                        <h2><?= $infoService['title'] ?></h2>
                        <p class="service-body">
                            <?= $infoService['body']  ?>
                        </p>
    
                    </div>
                </a>
     
                </div>
            <?php } ?>
             
        </div>
    
        </div>
        </div>
    
    </section><!-- /.section-contact-us -->
</div>
