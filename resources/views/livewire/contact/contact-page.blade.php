<section class="site-section subpage-site-section section-contact-us">

    <div class="container">
        <div class="row">
            <div class="col-sm-7">
                <h2>Send a message</h2>
                <div class="alert alert-success msg-success" style="display: none;">
					Message sent successfully
					</div>
                <div>
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
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message">Subject:</label>
                        <input class="form-control" id="msg_subject"></input>
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea class="form-control form-control-comment" id="message"></textarea>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-green SubmitForm">Contact us</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="contact-info">
                    <h2>Contact information</h2>
                    <div class="row">
                        <div class="col-sm-12">
                            <h3><i class="fa fa-map-marker" aria-hidden="true"></i> Location</h3>
                            <ul class="list-unstyled">
                                <li>
                                    Irvine, CA USA
                                </li>

                            </ul>
                            <h3><i class="fa fa-envelope" aria-hidden="true"></i> E-mail</h3>
                            <a href="mailto:Besmanico@gmail.com" target="_blank">Besmanico@gmail.com</a>
                            <h3><i class="fa fa-phone" aria-hidden="true"></i> Phone</h3>
                            <a href="tel:+19494328383" target="_blank">+1 949 432 8383</a>
                            <h3><i class="fa fa-whatsapp" aria-hidden="true"></i> WhatsApp</h3>
                            <a href="https://wa/me/+19515264212" target="_blank">+1 951 526 4212</a>
                            <h3><i class="fa fa-telegram" aria-hidden="true"></i> Telegram</h3>
                            <a href="https://t.me/+19514750995" target="_blank">+1 951 475 0995</a>

                        </div>
                    </div>
                </div><!-- /.contact-info -->
            </div>
        </div>
    </div>

</section><!-- /.section-contact-us -->


<script>
    $('.SubmitForm').click(function() {
        var error = 0;
        var name = $("#name").val();
        var email = $("#email").val();
        var msg_subject = $("#msg_subject").val();
        var message = $("#message").val();

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

        var EmailMatch = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!EmailMatch.test(email)) {
            error = 1;
            $("#email").focus();
        }
        if (error == 1) {
            return false;
        }



        if (msg_subject == '') {
            error = 1;
            $("#msg_subject").focus();
        }
        if (error == 1) {
            return false;
        }
        if (message == '') {
            error = 1;
            $("#message").focus();
        }
        if (error == 1) {
            return false;
        }


        $.ajax({

            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
                url: '{{ config('app.url') }}contactus/AddContact',
                type: 'POST',
                async: false,
                data: {
                    name: name,
                    email: email,
                    msg_subject: msg_subject,
                    message: message
                }
            })

            .done(function(msg) {

                $("#name").val('');
                $("#email").val('');
                $("#msg_subject").val('');
                  $("#message").val('');
                $(".msg-success").slideDown();


            })


    })
</script>