<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Page Title' }}</title>

    <!-- Mobile Specific Metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Fonts -->

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,600,700" rel="stylesheet">

    <!-- <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="{{ config('app.url') }}assets-file/css/font-awesome.min.css">

    <!-- Favicon
    ================================================== -->
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ config('app.url') }}assets-file/img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ config('app.url') }}assets-file/img/favicon_io/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ config('app.url') }}assets-file/img/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ config('app.url') }}assets-file/img/favicon_io/android-chrome-512x512.png">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ config('app.url') }}assets-file/img/favicon_io/android-chrome-192x192.png">

    <!-- Stylesheets
    ================================================== -->
    <!-- Bootstrap core CSS -->
    <link href="{{ config('app.url') }}assets-file/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ config('app.url') }}assets-file/css/style.css?v=<?= filemtime('assets-file/css/style.css') ?>"
        rel="stylesheet">
    <link
        href="{{ config('app.url') }}assets-file/css/responsive.css?v=<?= filemtime('assets-file/css/responsive.css') ?>"
        rel="stylesheet">
    <link href="{{ config('app.url') }}assets-file/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="{{ config('app.url') }}assets-file/aos/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ config('app.url') }}assets-file/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ config('app.url') }}assets-file/css/owl.theme.default.min.css">

    <link rel="stylesheet"
        href="{{ config('app.url') }}assets-file/css/sonnet-toast.css?v=<?= filemtime('assets-file/css/sonnet-toast.css') ?>">

    <script src="{{ config('app.url') }}assets-file/js/jquery.min.js"></script>


    @livewireStyles



</head>

<body>
    @livewire('header')
    <div id="hero" class="hero overlay">
        <div class="hero-content aos" data-aos="fade-up">
            <div class="hero-text -mt-150">
                <h1>Your story begins from here.</h1>
                <a href="{{ config('app.url') }}" style="position:relative" class="site-title mt-sm-logo"><img
                        style="margin-top:15px;width:450px;" src="{{ config('app.url') }}assets-file/img/header.png"
                        alt="besmani"></a>

                <p style="margin-top:55px;">BESMANI EXPERIENCE OF THE FUTURE TECHNOLOGY AND DESIGN </p>

                {{-- subscribe --}}
                <section class="site-section section-newsletter Subscribe-section text-center ">
                    <div class="success-subscribe"> Success Subscribe </div>

                    <div class="form-group newsletter-group">
                        <input type="email" class="form-control email-subscribe" placeholder="Your e-mail">
                        <button id="Subscribe" class="btn btn-green absolute Subscribe-btn" type="button">
                            Subscribe
                            <i class="fa fa-spinner fa-spin" style="display: none;"></i>
                        </button>
                    </div><!-- /.newsletter-group -->

                </section><!-- /.section-newsletter -->

                {{-- subscribeEnd --}}


                <b class="txt-hero-measure">

                    We measure our success by the results we drive for our clients.

                </b>


            </div><!-- /.hero-text -->
        </div><!-- /.hero-content -->
    </div><!-- /.hero -->

    <main id="main" class="site-main">


        {{ $slot }}

    </main>

    @livewire('footer')

    <?php
    $countryCode = countryCode();
    
    ?>

    <!-- Signup Modal -->
    <div id="signup-modal" class="modal-overlay" style="display: none;">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeSignupModal()">×</button>

            <!-- Header -->
            <div class="modal-header">
                <div class="modal-logo">
                    {{-- BES<span class="logo-highlight">M</span>ANI --}}
                    <img src="{{ config('app.url') }}assets-file/img/logo-footer.png" alt="logo"
                        style="width: 150px;">
                </div>
                <div class="modal-title">Sign Up</div>
            </div>

            <!-- Form Container -->
            <div class="modal-form-container">
                <form id="signup-form" class="signup-form">
                    <input type="text" id="fname" placeholder="First Name " class="modal-input" required>
                    <input type="text" id="lname" placeholder="Last Name " class="modal-input" required>


                    <div class="input-group">
                        <select name="country-code" id="country-code" class="modal-input country-code">
                            @foreach ($countryCode as $country)
                                <option value="{{ $country->id }}"
                                    {{ strtolower($country->name_en) == 'united states' || strtolower($country->name_en) == 'usa' || $country->code == '+1' ? 'selected' : '' }}>
                                    {{ $country->code }} {{ $country->name_en }}</option>
                            @endforeach
                        </select>
                        {{-- <input type="text" id="country-code" placeholder="+1" class="modal-input country-code" > --}}
                        <input type="tel" id="phone" placeholder="Phone Number"
                            class="modal-input phone-input" required>
                    </div>
                    <input type="email" id="email" placeholder="Email" class="modal-input" required>

                    <input type="password" id="password" placeholder="Password" class="modal-input" required>


                    <div class="button-container">
                        <button type="submit" id="submit-btn" class="submit-button">
                            Sign Up
                            <i class="fa fa-spinner fa-spin" style="display: none; margin-left: 8px;"></i>
                        </button>
                    </div>
                </form>

                {{-- side confirm code after signup --}}
                <div class="side-confirm-code">
                    <p class="text-center text-white">Please enter the code below.</p>
                    <b class="w-100 text-center" style="font-size: 22px;
      color: #fff;">6630</b>
                    <p>Confirm Code</p>
                    <input type="text" id="confirm-code" placeholder="Confirm Code" class="modal-input">
                    <button type="submit" id="confirm-btn" class="submit-button">
                        Confirm
                        <i class="fa fa-spinner fa-spin" style="display: none; margin-left: 8px;"></i>
                    </button>
                </div>



            </div>
        </div>
    </div>

    {{-- login modal --}}
    <!-- Signup Modal -->
    <div id="login-modal" class="modal-overlay" style="display: none;">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeLoginModal()">×</button>

            <!-- Header -->
            <div class="modal-header">
                <div class="modal-logo">
                    {{-- BES<span class="logo-highlight">M</span>ANI --}}
                    <img src="{{ config('app.url') }}assets-file/img/logo-footer.png" alt="logo"
                        style="width: 150px;">
                </div>
                <div class="modal-title">Sign in</div>
            </div>

            <!-- Form Container -->
            <div class="modal-form-container">
                <form id="login-form" class="login-form">
                    <input type="text" id="emailOrPhoneLogin" placeholder="Email Or Phone Number"
                        class="modal-input" required>
                    <input type="password" id="passwordLogin" placeholder="Password" class="modal-input" required>


                    <div class="button-container">
                        <button type="submit" id="submit-btn-login" class="submit-button">
                            Login
                            <i class="fa fa-spinner fa-spin" style="display: none; margin-left: 8px;"></i>
                        </button>
                    </div>
                </form>

                {{-- side confirm code after signup --}}
                {{-- <div class="side-confirm-code">
                    <p class="text-center text-white">Please enter the code below.</p>
                    <b class="w-100 text-center" style="font-size: 22px;
  color: #fff;">6630</b>
                    <p>Confirm Code</p>
                    <input type="text" id="confirm-code" placeholder="Confirm Code" class="modal-input">
                    <button type="submit" id="confirm-btn" class="submit-button">
                        Confirm
                        <i class="fa fa-spinner fa-spin" style="display: none; margin-left: 8px;"></i>
                    </button>
                </div> --}}


                <div class="text-left" style="margin-top: 25px;">
                    <a href="#" style="color: #fff; text-decoration: none;">Forgot your password ? <b
                            style="color:#ed2226">Click
                            Here</b></a>
                    <br>
                    <br>
                    <a onclick="openSignupModal()" style="color: #fff; text-decoration: none;">Have not registered yet
                        ? <b style="color:#fe0002">Sign Up</b></a>
                </div>



            </div>
        </div>
    </div>

    @livewireScripts
    <script src="{{ config('app.url') }}assets-file/js/sonnet-toast.js"></script>

    <!-- Toast Container -->
    <div class="sonner-toast-container" id="sonner-container"></div>

</body>


<script src="{{ config('app.url') }}assets-file/js/bootstrap.min.js"></script>
<script src="{{ config('app.url') }}assets-file/js/bootstrap-select.min.js"></script>
<script src="{{ config('app.url') }}assets-file/js/jquery.slicknav.min.js"></script>
<script src="{{ config('app.url') }}assets-file/js/jquery.countTo.min.js"></script>
<script src="{{ config('app.url') }}assets-file/js/jquery.shuffle.min.js"></script>
<script src="{{ config('app.url') }}assets-file/aos/aos.js"></script>

<script src="{{ config('app.url') }}assets-file/js/script.js"></script>
<script src="{{ config('app.url') }}assets-file/lightbox/js/lightbox.min.js"></script>
<script>
    $('#Subscribe').click(function() {
        var subscribeEmail = $('.email-subscribe').val();

        // Validate email is entered
        if (!subscribeEmail) {
            $('.email-subscribe').focus();
            return;
        }

        // Validate email format
        var emailRegex = /^\S+@\S+\.\S+$/;
        if (!emailRegex.test(subscribeEmail)) {
            alert('Please enter a valid email address.');
            $('.email-subscribe').focus();
            return;
        }

        // Show loading state
        $('#Subscribe .fa-spinner').show();
        $('#Subscribe').prop('disabled', true);

        // Small delay for better UX
        setTimeout(function() {
            $('#Subscribe .fa-spinner').hide();
            $('#Subscribe').prop('disabled', false);

            // Open signup modal and pre-fill email
            openSignupModal();
            $('#email').val(subscribeEmail);

            // Add visual indication that email was pre-filled
            $('#email').css('background-color', '#f0f9ff');
            setTimeout(function() {
                $('#email').css('background-color', '');
            }, 2000);
        }, 500);
    })

    // Modal Functions
    function openSignupModal() {
        closeLoginModal();

        $('#signup-modal').fadeIn(300);
        $('body').css('overflow', 'hidden');
    }

    function closeSignupModal() {
        $('#signup-modal').fadeOut(300);
        $('body').css('overflow', 'auto');
        resetForm();
    }

    function resetForm() {
        $('#signup-form').removeClass('hide').addClass('signup-form');
        $('.side-confirm-code').removeClass('show').hide();
        $('#signup-form')[0].reset();
        $('#confirm-code').val('');
        $('.fa-spinner').hide();
        $('#submit-btn').prop('disabled', false);
        $('#confirm-btn').prop('disabled', false);
        // Reset email background color
        $('#email').css('background-color', '');
    }

    // Close modal when clicking outside
    $('#signup-modal').click(function(e) {
        if (e.target === this) {
            closeSignupModal();
        }
    });

    // Close modal with Escape key
    $(document).keydown(function(e) {
        if (e.keyCode === 27 && $('#signup-modal').is(':visible')) {
            closeSignupModal();
        }
    });

    // Form submission
    $('#signup-form').submit(function(e) {
        e.preventDefault();

        var fname = $('#fname').val();
        var lname = $('#lname').val();
        var email = $('#email').val();
        var countryCode = $('#country-code').val();
        var phone = $('#phone').val();
        var password = $('#password').val();

        // Basic validation
        if (!fname || !lname || !email || !password || !countryCode || !phone) {
            toastWarning('Please fill in all required fields.', 4000);
            return;
        }


        // Email validation
        var emailRegex = /^\S+@\S+\.\S+$/;
        if (!emailRegex.test(email)) {
            toastWarning('Please enter a valid email address.', 4000);
            return;
        }

        if (!countryCode || !phone) {
            toastWarning('Please enter a valid country code and phone number.', 4000);
            return;
        }

        // Show loading spinner
        $('.fa-spinner').show();
        $('#submit-btn').prop('disabled', true);

        // Submit the form via AJAX  
        $.ajax({
                url: '{{ route('signup') }}',
                type: 'POST',
                data: {
                    fname: fname,
                    lname: lname,
                    email: email,
                    country_code: countryCode,
                    phone: phone,
                    password: password,
                }
            })
            .done(function(response) {
                $('.fa-spinner').hide();
                $('#submit-btn').prop('disabled', false);

                if (response == 0) {
                    // Hide signup form and show confirmation code section
                    $('#signup-form').addClass('hide');
                    $('.side-confirm-code').addClass('show').show();
                    window.location.href = '/';

                    // Store phone number for confirmation
                    // window.signupPhone = phone;
                }

                if (response.success) {
                    // alert('Welcome to BESMANI!');
                    toastSuccess('Welcome to BESMANI!', 'Your account has been created successfully.',
                        3000);

                    setTimeout(function() {
                        closeSignupModal(); 
                        window.location.href = '/';
                    }, 3000);

                    $('.WuserName').text(response.userName);
                    $('.welcome-user').show();

                 }
            })
            .fail(function(xhr) {
                $('.fa-spinner').hide();
                $('#submit-btn').prop('disabled', false);

                if (xhr.status === 422) {
                    // Validation errors
                    var errors = xhr.responseJSON?.errors;
                    var message = xhr.responseJSON?.message || 'Please check your input and try again.';

                    if (errors) {
                        // Show specific field errors
                        var errorMessages = [];
                        for (var field in errors) {
                            errorMessages.push(errors[field][0]);
                        }
                        // alert(errorMessages.join('\n'));
                        toastError('Validation Error', errorMessages.join(', '), 5000);
                    } else {
                        // alert(message);
                        toastError('Error', message, 4000);
                    }
                } else {
                    // alert(xhr.responseJSON?.message || 'An error occurred. Please try again.');
                    toastError('Error', xhr.responseJSON?.message || 'An error occurred. Please try again.',
                        4000);
                }
            });

    });
    $('#confirm-btn').click(function() {
        var confirmCode = $('#confirm-code').val();

        // Basic validation
        if (!confirmCode) {
            toastWarning('Please enter the confirmation code.', 4000);
            return;
        }

        // Show loading spinner
        $('#confirm-btn .fa-spinner').show();
        $('#confirm-btn').prop('disabled', true);

        $.ajax({
                url: '{{ route('confirm-code') }}',
                type: 'POST',
                data: {
                    confirmCode: confirmCode,
                    phone: window.signupPhone,
                }
            })
            .done(function(response) {
                $('#confirm-btn .fa-spinner').hide();
                $('#confirm-btn').prop('disabled', false);

                if (response.success) {
                    // alert(response.message || 'Code confirmed! Welcome to BESMANI!');
                    toastSuccess('Code Confirmed!', 'Welcome to BESMANI!', 3000);
                    setTimeout(function() {
                        closeSignupModal(); 
                        window.location.href = '/';
                    }, 3000);
                    $('.WuserName').text(response.userName);
                    $('.welcome-user').show();
                   
                } else {
                    // alert(response.message || 'Invalid confirmation code.');
                    toastError('Invalid Code', response.message || 'Invalid confirmation code.', 4000);
                }
            })
            .fail(function(xhr) {
                $('#confirm-btn .fa-spinner').hide();
                $('#confirm-btn').prop('disabled', false);
                // alert(xhr.responseJSON?.message || 'An error occurred. Please try again.');
                toastError('Error', xhr.responseJSON?.message || 'An error occurred. Please try again.',
                    4000);
            });
    });

    if (window.innerWidth < 768) {
        $(window).scroll(function() {
            var scrollTop = $(window).scrollTop();
            if (scrollTop > 331) {
                $('.inMobileRemoveCover').css('display', 'none');
            }
            if (scrollTop < 331) {
                $('.inMobileRemoveCover').css('display', 'block');
            }


        });
    }

    $(document).ready(function() {
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Phone number formatting function
        function formatPhoneNumber(value) {
            // Remove all non-digit characters
            const phoneNumber = value.replace(/\D/g, '');

            // Format as (XXX) XXX-XXXX
            if (phoneNumber.length >= 6) {
                return '(' + phoneNumber.substring(0, 3) + ') ' + phoneNumber.substring(3, 6) + '-' +
                    phoneNumber.substring(6, 10);
            } else if (phoneNumber.length >= 3) {
                return '(' + phoneNumber.substring(0, 3) + ') ' + phoneNumber.substring(3);
            } else if (phoneNumber.length > 0) {
                return '(' + phoneNumber;
            }
            return phoneNumber;
        }

        // Phone number input formatting and copy-paste prevention
        $('#phone').on('input', function() {
            const formatted = formatPhoneNumber($(this).val());
            $(this).val(formatted);
        });

        // Prevent copy-paste on phone number field
        $('#phone').on('paste', function(e) {
            e.preventDefault();
        });

        $('#phone').on('copy cut', function(e) {
            e.preventDefault();
        });

        // Prevent copy-paste on email field
        $('#email').on('paste', function(e) {
            e.preventDefault();
        });

        $('#email').on('copy cut', function(e) {
            e.preventDefault();
        });

        // Also prevent right-click context menu on both fields
        $('#phone, #email').on('contextmenu', function(e) {
            e.preventDefault();
        });

        // Prevent keyboard shortcuts for copy/paste
        $('#phone, #email').on('keydown', function(e) {
            // Prevent Ctrl+C, Ctrl+V, Ctrl+X
            if ((e.ctrlKey || e.metaKey) && (e.keyCode === 67 || e.keyCode === 86 || e.keyCode ===
                    88)) {
                e.preventDefault();
            }
        });
    });


    // sign in modal
    function openLoginModal() {
        $('#login-modal').fadeIn(300);
        $('body').css('overflow', 'hidden');
    }

    function closeLoginModal() {
        $('#login-modal').fadeOut(300);
        $('body').css('overflow', 'auto');
    }

    // sign in form submission
    $('#login-form').submit(function(e) {
        e.preventDefault();
        var emailOrPhone = $('#emailOrPhoneLogin').val();
        var password = $('#passwordLogin').val();

        // Show loading spinner
        $('.fa-spinner').show();
        $('#submit-btn-login').prop('disabled', true);

        $.ajax({
                url: '{{ route('login') }}',
                type: 'POST',
                data: {
                    emailOrPhone: emailOrPhone,
                    password: password,
                }
            })
            .done(function(response) {
                if (response.success) {
                    // alert('Welcome to BESMANI!');
                    toastSuccess('Welcome to BESMANI!', 'You have successfully signed in.', 2000);
                    setTimeout(function() {
                        closeLoginModal();
                        window.location.href = '/';
                    }, 3000);
                    $('.WuserName').text(response.userName);
                    $('.welcome-user').show();
                  
                } else {
                    // alert(response.message || 'Invalid email or phone number or password!');
                    toastError('Login Failed', response.message ||
                        'Invalid email or phone number or password!', 4000);
                }
                $('.fa-spinner').hide();
                $('#submit-btn-login').prop('disabled', false);
                // closeLoginModal();
            })
            .fail(function(xhr) {
                $('.fa-spinner').hide();
                $('#submit-btn-login').prop('disabled', false);
                // alert(xhr.responseJSON?.message || 'An error occurred. Please try again.');
                toastError('Error', xhr.responseJSON?.message || 'An error occurred. Please try again.',
                    4000);
            });
    });
</script>
