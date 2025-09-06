<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
    <link href="{{ config('app.url') }}assets-file/css/style.css?v=<?=  filemtime('assets-file/css/style.css'); ?>" rel="stylesheet">
    <link href="{{ config('app.url') }}assets-file/css/responsive.css?v=<?=  filemtime('assets-file/css/responsive.css'); ?>" rel="stylesheet">
    <link href="{{ config('app.url') }}assets-file/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="{{ config('app.url') }}assets-file/aos/aos.css" rel="stylesheet">
    <script src="{{ config('app.url') }}assets-file/js/jquery.min.js"></script>

    @livewireStyles


</head>

<body>
    
    <header id="masthead" class="site-header site-header-white">
        <nav id="primary-navigation" class="site-navigation">
            <div class="container">
    
            <div class="navbar-header" style="float: left;">
                   
                   <a href="{{ config('app.url') }}" class="site-title logo-header"><img src="{{ config('app.url') }}assets-file/img/logo.png" alt="logo"></a>
    
               </div><!-- /.navbar-header -->
    
               <div class="collapse navbar-collapse" id="agency-navbar-collapse">
    
                   <ul class="nav navbar-nav navbar-right">
    
                       <li class="active"><a href="{{ config('app.url') }}" >HOME</a></li>
                       <li class=""><a href="{{ config('app.url') }}services">SERVICES</a></li>
                        <li class=""><a href="{{ config('app.url') }}careers">CAREERS</a></li>
                       <li class=""><a href="{{ config('app.url') }}aboutus">ABOUT US</a></li>
                       <li class=""><a href="{{ config('app.url') }}contactus">CONTACT US</a></li>
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
    @livewireScripts

    <style>
        /* Custom Right-to-Left Sliding Navigation - Override Default Slicknav */
        
        /* Hide default slicknav behavior */
        .slicknav_nav {
            display: none !important;
        }
        
        /* Custom Mobile Navigation Container */
        .custom-mobile-nav {
            position: fixed;
            top: 0;
            right: -100%;
            width: 300px;
            height: 100vh;
            background: #ffffff;
            backdrop-filter: blur(25px);
            border-left: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: -10px 0 50px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            padding: 80px 0 30px 0;
        }

        .custom-mobile-nav.active {
            right: 0;
        }

        /* Overlay */
        .mobile-nav-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-nav-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Navigation Items */
        .custom-mobile-nav .nav-item {
            margin: 8px 25px;
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.4s ease;
        }

        .custom-mobile-nav.active .nav-item {
            opacity: 1;
            transform: translateX(0);
        }

        /* Staggered Animation */
        .custom-mobile-nav.active .nav-item:nth-child(1) { transition-delay: 0.1s; }
        .custom-mobile-nav.active .nav-item:nth-child(2) { transition-delay: 0.15s; }
        .custom-mobile-nav.active .nav-item:nth-child(3) { transition-delay: 0.2s; }
        .custom-mobile-nav.active .nav-item:nth-child(4) { transition-delay: 0.25s; }
        .custom-mobile-nav.active .nav-item:nth-child(5) { transition-delay: 0.3s; }
        .custom-mobile-nav.active .nav-item:nth-child(6) { transition-delay: 0.35s; }
        .custom-mobile-nav.active .nav-item:nth-child(7) { transition-delay: 0.4s; }

        .custom-mobile-nav .nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 25px;
            color: #071021;
            font-size: 17px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            background: rgba(7, 16, 33, 0.05);
            border: 1px solid rgba(7, 16, 33, 0.1);
            border-radius: 15px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-decoration: none;
        }

        /* Shimmer Effect */
        .custom-mobile-nav .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(7, 16, 33, 0.1), transparent);
            transition: left 0.8s ease;
        }

        .custom-mobile-nav .nav-link:hover::before {
            left: 100%;
        }

        /* Hover Effects */
        .custom-mobile-nav .nav-link:hover {
            background: rgba(7, 16, 33, 0.1);
            color: #071021;
            transform: translateX(-8px) scale(1.02);
            box-shadow: 0 6px 20px rgba(7, 16, 33, 0.2);
            border-color: rgba(7, 16, 33, 0.2);
        }

        /* Active State */
        .custom-mobile-nav .nav-item.active .nav-link {
            background: linear-gradient(135deg, #fe0002 0%, #ff6b6b 100%);
            color: #fff;
            /* box-shadow: 0 8px 25px rgba(254, 0, 2, 0.5); */
            transform: translateX(-5px);
            /* border-color: rgba(254, 0, 2, 0.5); */
        }

        /* Close Button */
        .mobile-nav-close {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            background: rgba(7, 16, 33, 0.1);
            border: 1px solid rgba(7, 16, 33, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #071021;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mobile-nav-close:hover {
            background: rgba(7, 16, 33, 0.2);
            transform: rotate(90deg);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .custom-mobile-nav {
                width: 280px;
            }
            .custom-mobile-nav .nav-link {
                font-size: 15px;
                padding: 12px 20px;
            }
        }

        @media (max-width: 360px) {
            .custom-mobile-nav {
                width: 260px;
            }
            .custom-mobile-nav .nav-link {
                font-size: 14px;
                padding: 10px 15px;
            }
        }
    </style>

    <!-- Custom Mobile Navigation HTML -->
    <div class="mobile-nav-overlay"></div>
    <div class="custom-mobile-nav">
        <div class="mobile-nav-close">&times;</div>
        <div class="nav-item active">
            <a href="{{ config('app.url') }}" class="nav-link">HOME</a>
        </div>
        <div class="nav-item">
            <a href="{{ config('app.url') }}services" class="nav-link">SERVICES</a>
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
                window.location = '{{ config('app.url') }}subscribe/check/'+email;
            })
    }) 
</script>
</html>


