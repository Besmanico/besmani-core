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

    <style>
        .panel-logout-modal {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }
        .panel-logout-modal.is-open {
            opacity: 1;
            visibility: visible;
        }
        .panel-logout-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
        }
        .panel-logout-modal-dialog {
            position: relative;
            width: 100%;
            max-width: 400px;
            transform: scale(0.9);
            transition: transform 0.25s ease;
         }
        .panel-logout-modal.is-open .panel-logout-modal-dialog {
            transform: scale(1);
        } 
        .panel-logout-modal-content {
            background: #06283f;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            padding: 2rem 1.75rem;
            text-align: center;
        }
        .panel-logout-modal-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            color: #fca5a5;
            font-size: 1.75rem;
        }
        .panel-logout-modal-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.65rem;
            font-weight: 700;
            color: #fff;
            margin: 0 0 0.5rem;
        }
        .panel-logout-modal-text {
            font-size: 1.1rem;
            color: #94a3b8;
            line-height: 1.5;
            margin: 0 0 1.75rem;
        }
        .panel-logout-modal-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .panel-logout-btn {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.05rem;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: background 0.2s, color 0.2s, transform 0.15s;
        }
        .panel-logout-btn:active {
            transform: scale(0.98);
        }
        .panel-logout-btn-cancel {
            background: rgba(238, 198, 20, 0.813);
            color: #e2e8f0; 
        }
        .panel-logout-btn-cancel:hover {
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
        }
        .panel-logout-btn-confirm {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: #fff;
        }
        .panel-logout-btn-confirm:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
        }
    </style>

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
                <a href="{{ config('app.url') }}panel" class="nav-link {{ request()->path() === 'panel' ? 'active' : '' }}">
                    <i class="fa fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ config('app.url') }}panel/profile" class="nav-link {{ request()->is('panel/profile*') ? 'active' : '' }}">
                    <i class="fa fa-user"></i>
                    <span>My Profile</span> 
                </a>
                <a href="{{ config('app.url') }}panel/invoice" class="nav-link {{ request()->is('panel/invoice*') ? 'active' : '' }}">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
                <a href="{{ config('app.url') }}panel/payment" class="nav-link {{ request()->is('panel/payment*') ? 'active' : '' }}">
                    <i class="fa fa-briefcase"></i>
                    <span>Payments</span>  
                </a>
                <a href="{{ route('panel.referral') }}" class="nav-link {{ request()->is('panel/referral*') ? 'active' : '' }}">
                    <i class="fa fa-exchange"></i>
                    <span>Referrals</span> 
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
                
                {{-- <a href="{{ config('app.url') }}panel/business" class="nav-link">
                    <i class="fa fa-briefcase"></i>
                    <span>Business</span>
                </a> --}}
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

        {{-- Logout confirmation modal --}}
        <div id="panelLogoutModal" class="panel-logout-modal" role="dialog" aria-modal="true" aria-labelledby="panelLogoutModalTitle" aria-hidden="true">
            <div class="panel-logout-modal-backdrop"></div>
            <div class="panel-logout-modal-dialog">
                <div class="panel-logout-modal-content">
                    <div class="panel-logout-modal-icon">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                    </div>
                    <h3 id="panelLogoutModalTitle" class="panel-logout-modal-title">Log out?</h3>
                    <p class="panel-logout-modal-text">Are you sure you want to leave? You will need to sign in again.</p>
                    <div class="panel-logout-modal-actions">
                        <button type="button" class="panel-logout-btn panel-logout-btn-cancel" data-dismiss="modal">Cancel</button>
                        <button type="button" class="panel-logout-btn panel-logout-btn-confirm" id="panelLogoutConfirmBtn">Log out</button>
                    </div>
                </div>
            </div>
        </div>

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
        var modal = document.getElementById('panelLogoutModal');
        if (!modal) return;
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeLogoutModal() {
        var modal = document.getElementById('panelLogoutModal');
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    function doLogout() {
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

    $(function () {
        var $modal = $('#panelLogoutModal');
        $modal.find('.panel-logout-modal-backdrop, [data-dismiss="modal"]').on('click', function () {
            closeLogoutModal();
        });
        $('#panelLogoutConfirmBtn').on('click', function () {
            closeLogoutModal();
            doLogout();
        });
        $(document).on('keydown', function (e) {
            if (e.key === 'Escape' && $modal.hasClass('is-open')) {
                closeLogoutModal();
            }
        }); 
    });
</script>
@livewireScripts

</html>
