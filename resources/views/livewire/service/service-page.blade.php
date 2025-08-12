<section class="site-section subpage-site-section section-contact-us">
    
    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                <img src="{{ config('app.url') }}assets-file/img/services/<?= $service['image'] ?>" alt="<?= $service['title'] ?>" class="w-100">
            </div>


            <div class="col-sm-1"></div>
            <div class="col-sm-5">
                <h2><?= $service['title'] ?></h2>
                <p class="service-body">
                    <?= $service['body']  ?>
                </p>

            </div>

        </div>

        <!-- request -->
        <div class="text-center">
            <button class="btn btn-fill goReq">Request
                <i class="fa fa-chevron-down"></i>
            </button>
        </div>
        <div class="row  center-col">


            <div class="col-sm-8 ">
                <div class="formReq" style="display: none;">
                    <div class="alert alert-success msg-success" style="display: none;">
                        request sent successfully
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control form-control-comment" id="req-description"></textarea>

                    </div>

                    <div class="w100">

                        <button class="btn btn-gray goSaveReq">Send

                        </button>
                    </div>

                </div>
            </div>

        </div>


    </div>

    </div>
    </div>

</section><!-- /.section-contact-us -->

<script>
    
    $('.goReq').click(function() {

        $('.formReq').slideToggle();
    });


    $('.goSaveReq').click(function() {
        var error = 0;
        var service = '<?= $service['id'] ?>';
        var reqDescription = $("#req-description").val();

        if (reqDescription == '') {
            error = 1;
            $("#reqDescription").focus();
        }
        if (error == 1) {
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
            })

            .done(function(msg) {

                $("#req-description").val('');
                $(".msg-success").slideDown();


            })


    })
</script>