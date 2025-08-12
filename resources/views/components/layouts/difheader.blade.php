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


