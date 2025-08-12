 
<section class="site-section subpage-site-section section-contact-us">

    <div class="container">
        <div class="row center-col">
            <div class="col-sm-6 text-center">
            <img src="{{ config('app.url') }}assets-file/img/besmani-lg.jpg" alt="careers" style="width:250px;">
 
            </div> 
        </div>
    </div>
    <div class="container">
        <div class="row center-col">
            <div class="col-sm-8">
                <div class="alert alert-success msg-success" style="display: none;">
                    information sent successfully
                </div>
                <div class="mt-50">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input type="email" class="form-control" id="email">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-25">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="mobile">Mobile:</label>
                                <input type="text" class="form-control" id="mobile">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="phone">Phone:</label>
                                <input type="text" class="form-control" id="phone">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="expertise">Expertise:</label>
                        <select class="form-control" id="expertise">
                            <option value=""></option>
                            
 
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control form-control-comment" id="description"></textarea>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-green SubmitForm">Send</button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

</section><!-- /.section-contact-us -->


<script>
    $('.SubmitForm').click(function() {
        var error = 0;
        var name = $("#name").val();
        var email = $("#email").val();
        var mobile = $("#mobile").val();
        var phone = $("#phone").val();
        var description = $("#description").val();
        var expertise = $('#expertise :selected').val();

        if (name == '') {
            error = 1;
            $("#name").focus();
        }
        if (error == 1) {
            return false;
        }
        if (email == '') {
            error = 1;
            $("#email").focus();
        }
        if (error == 1) {
            return false;
        }
        if (mobile == '') {
            error = 1;
            $("#mobile").focus();
        }
        if (error == 1) {
            return false;
        }
        if (expertise == '') {
            error = 1;
            $("#expertise").focus();
        }
        if (error == 1) {
            return false;
        }

        if (description == '') {
            error = 1;
            $("#description").focus();
        }
        if (error == 1) {
            return false;
        }


        $.ajax({

                url: '{{ config('app.url') }}careers/AddExpertise',
                type: 'POST',
                async: false,
                data: {
                    name: name,
                    email: email,
                    mobile: mobile,
                    phone: phone,
                    expertise: expertise,
                    description: description
                }
            })

            .done(function(msg) {

                $("#name").val('');
                $("#email").val('');
                $("#mobile").val('');
                $("#phone").val('');
                $("#description").val('');
                $(".msg-success").slideDown();


            })


    })
</script>