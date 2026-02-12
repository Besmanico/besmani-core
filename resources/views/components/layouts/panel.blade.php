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
    <link
        href="{{ config('app.url') }}assets-file/css/panel_style.css?v=<?= filemtime('assets-file/css/panel_style.css') ?>"
        rel="stylesheet">
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


    <script src="{{ config('app.url') }}assets-file/js/jquery.min.js"></script>


    @livewireStyles
</head>

<body> 

    <div class="panel-dashboard">
        <aside class="panel-sidebar">
            <div class="sidebar-header">
                <a href="{{ config('app.url') }}">
                    <div class="logo">
                        <img style="width: 100px;" src="{{ config('app.url') }}assets-file/img/logo-footer.png" alt="logo">
                    </div>
                </a>
                @auth('mainUsers')
                    @php
                        $panelUser = Auth::guard('mainUsers')->user();
                        $avatarUrl = $panelUser && $panelUser->avatar
                            ? (env('BEAUTY_URL', 'https://beauty.besmani.com') . '/public/assets/images/user/' . $panelUser->avatar)
                            : 'https://ui-avatars.com/api/?name=' . urlencode(trim(($panelUser->fl_name ?? '') . ' ' . ($panelUser->last_name ?? '')) ?: 'User') . '&color=7F9CF5&background=EBF4FF&size=128';
                        $displayName = trim(($panelUser->fl_name ?? '') . ' ' . ($panelUser->last_name ?? '')) ?: 'User';
                    @endphp
                    <a href="{{ config('app.url') }}panel/profile" class="sidebar-user-block">
                        <div class="sidebar-user-avatar">
                            <img src="{{ $avatarUrl }}" alt="{{ $displayName }}">
                        </div>
                        {{-- <p class="sidebar-welcome">Welcome back!</p> --}}
                        <p class="sidebar-user-name">{{ $displayName }}</p>
                        <p class="sidebar-user-date">{{ now()->format('l, F j, Y') }}</p>
                    </a>
                @else
                    {{-- <p class="welcome">Welcome back!</p> --}}
                @endauth
            </div>

            <nav class="sidebar-nav">
                <a href="{{ config('app.url') }}panel" class="nav-link active">
                    <i class="fa fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ config('app.url') }}panel/profile" class="nav-link">
                    <i class="fa fa-user"></i>
                    <span>My Profile</span>
                </a>
                <a href="{{ config('app.url') }}panel/invoice" class="nav-link">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="fa fa-briefcase"></i>
                    <span>Payments</span>
                </a>
                {{-- <a href="#" class="nav-link">
                    <i class="fa fa-briefcase"></i>
                    <span>My Projects</span>
                </a> --}}
              
                {{-- <a href="#" class="nav-link">
                    <i class="fa fa-calendar"></i>
                    <span>Schedule</span>
                </a> --}}
                <a href="#" class="nav-link">
                    <i class="fa fa-envelope"></i>
                    <span>Messages</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="fa fa-cog"></i>
                    <span>Settings</span>
                </a>
                <a href="#" class="nav-link" onclick="event.preventDefault(); panelLogout();">
                    <i class="fa fa-sign-out"></i>
                    <span>Logout</span>
                </a> 
            </nav>

            <div class="sidebar-footer">
                <button class="upgrade-btn">
                    <span>Upgrade Plan</span>
                    <i class="fa fa-arrow-up"></i>
                </button>
            </div>
        </aside>

        <button type="button" class="sidebar-toggle" aria-label="Toggle navigation">
            <span class="toggle-bar"></span>
            <span class="toggle-bar"></span>
            <span class="toggle-bar"></span>
        </button>

        <div class="sidebar-overlay"></div>

        {{ $slot }}

    </div><!-- End #main -->
</body>
<script src="{{ config('app.url') }}assets-file/js/bootstrap.min.js"></script>
<script src="{{ config('app.url') }}assets-file/js/bootstrap-select.min.js"></script>
<script src="{{ config('app.url') }}assets-file/js/script.js"></script>
<script>
    $(function () {
        const $sidebar = $('.panel-sidebar');
        const $overlay = $('.sidebar-overlay');
        const $dashboard = $('.panel-dashboard');
        const $toggle = $('.sidebar-toggle');

        function openSidebar() {
            $sidebar.addClass('open');
            $overlay.addClass('active');
            $dashboard.addClass('sidebar-open');
            $('body').addClass('no-scroll');
        }

        function closeSidebar() {
            $sidebar.removeClass('open');
            $overlay.removeClass('active');
            $dashboard.removeClass('sidebar-open');
            $('body').removeClass('no-scroll');
        }

        $toggle.on('click', function () {
            if ($sidebar.hasClass('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });

        $overlay.on('click', closeSidebar);

        $(window).on('resize', function () {
            if (window.innerWidth > 1024) {
                closeSidebar();
            }
        });
    });

    function panelLogout() {
        if (confirm('Are you sure you want to logout?')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('logout') }}';
            var csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@livewireScripts

</html>
