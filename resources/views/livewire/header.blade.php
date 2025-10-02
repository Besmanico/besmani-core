<div>
<header id="masthead" class="site-header">
        <nav id="primary-navigation" class="site-navigation">
            <div class="container">

                <div class="navbar-header" >
                   
                    <a href="{{ config('app.url') }}" class="site-title logo-header"><img src="{{ config('app.url') }}assets-file/img/logo.png" alt="logo"></a>

                </div><!-- /.navbar-header -->

                <div class="collapse navbar-collapse" id="agency-navbar-collapse">
 
                    <ul class="nav navbar-nav navbar-right">


                        <li class="active"><a href="{{ config('app.url') }}">HOME</a></li>
                        <li class=""><a href="{{ config('app.url') }}services">SERVICES</a></li>
                        <li class=""><a href="{{ config('app.url') }}careers">CAREERS</a></li>
                        <li class=""><a href="{{ config('app.url') }}aboutus">ABOUT US</a></li>
                        <li class=""><a href="{{ config('app.url') }}contactus">CONTACT US</a></li>
                        <li class="welcome-user user-dropdown" style="display: {{ Auth::guard('mainUsers')->check() ? 'block' : 'none' }};">
                            <a href="#" id="welcome-message" class="dropdown-toggle" style="color: #dc2626; font-weight: bold; position: relative;">
                                Welcome, <b class="WuserName">{{ Auth::guard('mainUsers')->check() ? Auth::guard('mainUsers')->user()->fl_name : 'User' }}</b>
                                <i class="fa fa-chevron-down dropdown-arrow"></i>
                            </a>
                            <div class="user-dropdown-menu">
                                <div class="dropdown-header">
                                    <div class="user-avatar">
                                        <i class="fa fa-user-circle"></i>
                                    </div>
                                    <div class="user-info">
                                        <span class="user-name">{{ Auth::guard('mainUsers')->check() ? Auth::guard('mainUsers')->user()->fl_name . ' ' . Auth::guard('mainUsers')->user()->last_name : 'User' }}</span>
                                        <span class="user-email">{{ Auth::guard('mainUsers')->check() ? Auth::guard('mainUsers')->user()->email : '' }}</span>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="dropdown-items">
                                    <a href="#" class="dropdown-item">
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
                        <li class=""><a href=""></a></li>
                        <li class=""><a href=""></a></li>
                        {{-- <li class="">
                            <a href="https://beauty.besmani.com/" target="_blank"  class="btn-login">   
                                <img src="{{ config('app.url') }}assets-file/img/beauty-logo.png" alt="Logo" style="width: 70px;">

                        
                        </a>
                    </li> --}}

                    </ul>

                </div>

            </div>   
        </nav><!-- /.site-navigation -->
    </header><!-- /#mastheaed -->

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

        /* User Dropdown Styles */
        .user-dropdown {
            position: relative;
        }

        .dropdown-toggle {
            display: flex;
            align-items: center;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .dropdown-toggle:hover {
            background-color: rgba(220, 38, 38, 0.1);
            text-decoration: none;
        }

        .dropdown-arrow {
            margin-left: 8px;
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .dropdown-toggle.active .dropdown-arrow {
            transform: rotate(180deg);
        }

        .user-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            min-width: 280px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .user-dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header {
            display: flex;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg, #dc2626, #ef4444);
            border-radius: 12px 12px 0 0;
            color: white;
        }

        .user-avatar {
            margin-right: 15px;
        }

        .user-avatar i {
            font-size: 40px;
            color: rgba(255, 255, 255, 0.9);
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .user-email {
            font-size: 13px;
            opacity: 0.8;
        }

        .dropdown-divider {
            height: 1px;
            background: rgba(0, 0, 0, 0.1);
            margin: 8px 0;
        }

        .dropdown-items {
            padding: 8px 0;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 14px;
        }

        .dropdown-item:hover {
            background-color: #f9fafb;
            color: #dc2626;
            text-decoration: none;
        }

        .dropdown-item i {
            margin-right: 12px;
            width: 16px;
            text-align: center;
            font-size: 14px;
        }

        .logout-item:hover {
            background-color: #fef2f2;
            color: #dc2626;
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

            // User Dropdown Toggle
            $('#welcome-message').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const dropdown = $('.user-dropdown-menu');
                const toggle = $(this);
                
                if (dropdown.hasClass('show')) {
                    dropdown.removeClass('show');
                    toggle.removeClass('active');
                } else {
                    dropdown.addClass('show');
                    toggle.addClass('active');
                }
            });

            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.user-dropdown').length) {
                    $('.user-dropdown-menu').removeClass('show');
                    $('#welcome-message').removeClass('active');
                }
            });

            // Prevent dropdown from closing when clicking inside
            $('.user-dropdown-menu').on('click', function(e) {
                e.stopPropagation();
            });
        });

        // Logout function
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('logout') }}';
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    </div> 
