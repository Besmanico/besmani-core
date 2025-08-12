<section class="site-section section-services gray-bg text-center">
    <div class="container ">
        <h2 class="heading-separator ">Our Services</h2>
        <p class="subheading-text">
            At Besmani, our horizon is to bring the latest technology knowledge to the most sophisticated elements
            <br>
            by our highly accomplished scientists in designing.
        </p>
        <div class="row aos" data-aos="fade-up">

            <?php
             
            foreach ($services as $service) {
            ?>
                <div class="col-md-3 col-xs-6">
                    <div class="service">
                        <img src="{{ config('app.url') }}assets-file/img/<?= $service['icon'] ?>" alt="<?= $service['title'] ?>">
                        <h3 class="service-title">
                            <?= $service['title'] ?>
                            <hr>
                    </h3>
                        <p class="service-info">
                            <?= $service['meta'] ?>
                        </p>
                        <div style="position: absolute;width:100%;bottom:10px;left:0">
                            <a href="{{ config('app.url') }}services/service/<?= $service['slug'] ?>" class="btn-service-read-more ">Read More</a>
                        </div>
                    </div><!-- /.service -->

                </div>
            <?php } ?>


        </div>
    </div>
</section><!-- /.section-services -->