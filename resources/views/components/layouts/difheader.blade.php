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
    <link rel="stylesheet"
        href="{{ config('app.url') }}assets-file/css/sonnet-toast.css?v=<?= filemtime('assets-file/css/sonnet-toast.css') ?>">
    <script src="{{ config('app.url') }}assets-file/js/jquery.min.js"></script>

    @livewireStyles


</head>

<body>
    @php
        $mainUser = Auth::guard('mainUsers')->user();
    @endphp

    <header id="masthead" class="site-header site-header-white">
        <nav id="primary-navigation" class="site-navigation">
            <div class="container">

                <div class="navbar-header" style="float: left;">

                    <a href="{{ config('app.url') }}" class="site-title logo-header"><img
                            src="{{ config('app.url') }}assets-file/img/logo.png" alt="logo"></a>

                </div><!-- /.navbar-header -->

                <div class="collapse navbar-collapse" id="agency-navbar-collapse">

                    <ul class="nav navbar-nav navbar-right desktop-nav">

                        <li class=""><a href="{{ config('app.url') }}services">SERVICES</a></li>
                        <li class=""><a href="{{ config('app.url') }}orders">ORDERS</a></li>
                        <li class=""><a href="{{ config('app.url') }}design-style">DESIGN STYLE</a></li>
                        <li class=""><a href="{{ config('app.url') }}portfolios">PORTFOLIOS</a></li>
                        <li class=""><a href="{{ config('app.url') }}careers">CAREERS</a></li>
                        <li class=""><a href="{{ config('app.url') }}aboutus">ABOUT US</a></li>
                        <li class=""><a href="{{ config('app.url') }}contactus">CONTACT US</a></li>

                        @if ($mainUser)
                            <li class="welcome-user user-dropdown">
                                <a href="#" id="welcome-message" class="dropdown-toggle">
                                    <b class="WuserName">{{ $mainUser->fl_name }}</b>
                                    <i class="fa fa-chevron-down dropdown-arrow"></i>
                                </a>
                                <div class="user-dropdown-menu">
                                    <div class="dropdown-header">
                                        <div class="user-avatar">
                                            <i class="fa fa-user-circle"></i>
                                        </div>
                                        <div class="user-info">
                                            <span class="user-name">{{ $mainUser->fl_name }}
                                                {{ $mainUser->last_name }}</span>
                                            <span class="user-email">{{ $mainUser->email }}</span>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <div class="dropdown-items">
                                        <a href="{{ config('app.url') }}panel" class="dropdown-item">
                                            <i class="fa fa-user"></i>
                                            <span>Profile</span>
                                        </a>
                                        <a href="#" class="dropdown-item">
                                            <i class="fa fa-cog"></i>
                                            <span>Settings</span>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item logout-item" onclick="logout()">
                                            <i class="fa fa-sign-out"></i>
                                            <span>Logout</span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @else
                            <li class=""><a onclick="openLoginModal()">SIGN IN</a></li>
                        @endif
                        {{-- <li class=""><a href="{{ config('app.url') }}login" class="btn-login">   Login </a></li> --}}

                    </ul>

                </div>


            </div>
        </nav><!-- /.site-navigation -->
    </header><!-- /#mastheaed -->

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



    <!-- Custom Mobile Navigation HTML -->
    <div class="mobile-nav-overlay"></div>
    <div class="custom-mobile-nav">
        <div class="mobile-nav-close">&times;</div>

        <div class="nav-item">
            <a href="{{ config('app.url') }}services" class="nav-link">SERVICES</a>
        </div>
        <div class="nav-item">
            <a href="{{ config('app.url') }}orders" class="nav-link">ORDERS</a>
        </div>
        <div class="nav-item">
            <a href="{{ config('app.url') }}design-style" class="nav-link">DESIGN STYLE</a>
        </div>
        <div class="nav-item">
            <a href="{{ config('app.url') }}portfolios" class="nav-link">PORTFOLIOS</a>
        </div>
        <div class="nav-item">
            <a href="{{ config('app.url') }}careers" class="nav-link">CAREERS</a>
        </div>
        <div class="nav-item">
            <a href="{{ config('app.url') }}aboutus" class="nav-link">ABOUT US</a>
        </div>
        <div class="nav-item">
            <a href="{{ config('app.url') }}contactus" class="nav-link">CONTACT US</a>
        </div>
        <div class="nav-item">
            <a onclick="openLoginModal()" class="nav-link">SIGN IN</a>
        </div>
    </div>

    <script>
        // Custom Mobile Navigation JavaScript
        $(document).ready(function() {
            // Override default slicknav button click
            $('.slicknav_btn').off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Toggle custom mobile nav
                $('.custom-mobile-nav').toggleClass('active');
                $('.mobile-nav-overlay').toggleClass('active');
                $('body').toggleClass('menu-open');

                // Update button state
                $(this).toggleClass('act');
            });

            // Close on overlay click
            $('.mobile-nav-overlay').on('click', function() {
                closeMobileNav();
            });

            // Close on close button click
            $('.mobile-nav-close').on('click', function() {
                closeMobileNav();
            });

            // Close on escape key
            $(document).on('keyup', function(e) {
                if (e.key === "Escape" && $('.custom-mobile-nav').hasClass('active')) {
                    closeMobileNav();
                }
            });

            // Close on nav link click
            $('.custom-mobile-nav .nav-link').on('click', function() {
                closeMobileNav();
            });

            function closeMobileNav() {
                $('.custom-mobile-nav').removeClass('active');
                $('.mobile-nav-overlay').removeClass('active');
                $('.slicknav_btn').removeClass('act');
                $('body').removeClass('menu-open');
            }

            // Prevent body scroll when menu is open
            $('body').on('menu-open', function() {
                $('body').css('overflow', 'hidden');
            });

            $('body').on('menu-close', function() {
                $('body').css('overflow', 'auto');
            });
        });
    </script>
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
        var error = 0;
        var email = $(".email-subscribe").val();
        if (email == '') {
            error = 1;
            $(".email-subscribe").focus();
        }
        if (error == 1) {
            return false;
        }

        var regexp = /^\S+@\S+\.\S+$/;
        if (regexp.test(email) == false) {
            error = 1;
            $(".email-subscribe").focus();
        }
        if (error == 1) {
            return false;
        }
        $.ajax({
                url: '{{ config('app.url') }}subscribe/AddSubscribe',
                type: 'POST',
                async: false,
                data: {
                    email: email,
                }
            })
            .done(function(msg) {
                window.location = '{{ config('app.url') }}subscribe/check/' + email;
            })
    })


    // register script
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
            toastWarning('Required Fields', 'Please fill in all required fields.', 4000);
            return;
        }


        // Email validation
        var emailRegex = /^\S+@\S+\.\S+$/;
        if (!emailRegex.test(email)) {
            toastWarning('Invalid Email', 'Please enter a valid email address.', 4000);
            return;
        }

        if (!countryCode || !phone) {
            toastWarning('Invalid Phone', 'Please enter a valid country code and phone number.', 4000);
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
                    // Beautiful Sonner-like toast notification
                    toastSuccess('Welcome to BESMANI!', 'Your account has been created successfully.',
                    3000);

                    setTimeout(function() {
                        window.location.href = '/';
                    }, 500);

                    $('.WuserName').text(response.userName);
                    $('.welcome-user').show();

                    closeSignupModal();
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
                        toastError('Validation Error', errorMessages.join(', '), 5000);
                    } else {
                        toastError('Error', message, 4000);
                    }
                } else {
                    toastError('Error', xhr.responseJSON?.message || 'An error occurred. Please try again.',
                        4000);
                }
            });

    });
    $('#confirm-btn').click(function() {
        var confirmCode = $('#confirm-code').val();

        // Basic validation
        if (!confirmCode) {
            toastWarning('Confirmation Code', 'Please enter the confirmation code.', 4000);
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
                    toastSuccess('Code Confirmed!', response.message || 'Welcome to BESMANI!', 3000);
                    $('.WuserName').text(response.userName);
                    $('.welcome-user').show();
                    setTimeout(function() {
                        closeSignupModal();
                    }, 500);
                } else {
                    toastError('Invalid Code', response.message || 'Invalid confirmation code.', 4000);
                }
            })
            .fail(function(xhr) {
                $('#confirm-btn .fa-spinner').hide();
                $('#confirm-btn').prop('disabled', false);
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
                    // Beautiful Sonner-like toast notification
                    toastSuccess('Welcome to BESMANI!', 'You have successfully signed in.', 3000);
                    // get url address
                    var url = window.location.href;
                    setTimeout(function() {
                        window.location.href = url;
                    }, 4000);

                    $('.WuserName').text(response.userName);
                    $('.welcome-user').show();
                    closeLoginModal();
                } else {
                    toastError('Login Failed', response.message ||
                        'Invalid email or phone number or password!', 4000);
                }
                $('.fa-spinner').hide();
                $('#submit-btn-login').prop('disabled', false);
            })
            .fail(function(xhr) {
                $('.fa-spinner').hide();
                $('#submit-btn-login').prop('disabled', false);
                toastError('Error', xhr.responseJSON?.message || 'An error occurred. Please try again.',
                    4000);
            });
    });
</script>

</html>
