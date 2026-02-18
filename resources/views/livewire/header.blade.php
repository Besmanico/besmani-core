<div>
    <header id="masthead" class="site-header">
        <nav id="primary-navigation" class="site-navigation">
            <div class="container">

                <a href="{{ url('/') }}" class="site-title logo-header">
                    <div class="navbar-header" style="z-index: 2;
  position: relative;">

                        <a href="{{ url('/') }}" class="site-title logo-header">
                            <img src="{{ config('app.url') }}assets-file/img/logo.png" alt="logo">
                        </a>

                    </div><!-- /.navbar-header -->
                </a>
                @php
                    $mainUser = Auth::guard('mainUsers')->user();
                    $CartCount = CartCount();
                @endphp

                <div class="collapse navbar-collapse navbar-collapse-center" id="agency-navbar-collapse"
                    style="position: relative;">

                    <ul class="nav navbar-nav desktop-nav">
                        <li><a href="https://beauty.besmani.com/" target="_blank">Beauty</a></li>
                        <li><a href="https://beauty.besmani.com/category" target="_blank">Market</a></li>
                        <li><a href="{{ config('app.url') }}services">Services</a></li>
                        <li><a href="{{ config('app.url') }}besmo">Technology</a></li>
                        {{-- <li><a href="{{ config('app.url') }}orders">ORDERS</a></li> --}}
                        {{-- <li><a href="{{ config('app.url') }}design-style">DESIGN STYLE</a></li>
                        <li><a href="{{ config('app.url') }}portfolios">PORTFOLIO</a></li> --}}
                        {{-- <li><a href="{{ config('app.url') }}careers">CAREERS</a></li> --}}
                        {{-- <li><a href="{{ config('app.url') }}aboutus">About us</a></li>
                        <li><a href="{{ config('app.url') }}contactus">Contact us</a></li> --}}

                        @if ($mainUser)
                            <li><a href="{{ config('app.url') }}panel" style="color: #fe0001 !important;">Dashboard</a></li>
                        @endif




                        @if ($mainUser)
                            <li class="welcome-user user-dropdown">
                                <a href="#" id="welcome-message" class="dropdown-toggle">
                                    <b class="WuserName" style="color: #237D29;">{{ $mainUser->fl_name }}</b>
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
                                            <span>Dashboard</span>
                                        </a>
                                        {{-- <a href="#" class="dropdown-item">
                                            <i class="fa fa-cog"></i>
                                            <span>Settings</span>
                                        </a> --}}
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item logout-item" onclick="logout()">
                                            <i class="fa fa-sign-out"></i>
                                            <span>Logout</span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @else
                            {{-- <li class="login-item">
                                <a href="{{ route('login') }}" class="btn-login">Login</a>
                            </li> --}}
                            <li class="login-item-side" onclick="openLoginModal()"><a>SIGN IN</a></li>
                        @endif
                        <li class="basket-item" style="position: absolute; right: 0;">
                            <a href="{{ config('app.url') }}cart" class="basket-link">
                                <i class="fa fa-shopping-cart"></i>

                                <span class="basket-badge">
                                    {{ CartCount() }}
                                </span>

                            </a>
                        </li>
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

    <!-- Custom Mobile Navigation HTML -->
    <div class="mobile-nav-overlay"></div>
    <div class="custom-mobile-nav">
        <div class="mobile-nav-close">&times;</div>

        <div class="nav-item">
            <a href="{{ config('app.url') }}services" class="nav-link">SERVICES</a>
        </div>
        {{-- <div class="nav-item">
            <a href="{{ config('app.url') }}orders" class="nav-link">ORDERS</a>
        </div> --}}
        {{-- <div class="nav-item">
            <a href="{{ config('app.url') }}design-style" class="nav-link">DESIGN STYLE</a>
        </div> --}}
        {{-- <div class="nav-item">
            <a href="{{ config('app.url') }}portfolios" class="nav-link">PORTFOLIO</a>
        </div> --}}

        <div class="nav-item">
            <a href="{{ config('app.url') }}aboutus" class="nav-link">ABOUT US</a>
        </div>
        <div class="nav-item">
            <a href="{{ config('app.url') }}contactus" class="nav-link">CONTACT US</a>
        </div>
        <div class="nav-item">
            <a href="{{ config('app.url') }}cart" class="nav-link basket-link">
                <i class="fa fa-shopping-cart"></i>

                <span class="basket-badge">{{ CartCount() }}</span>

            </a>
        </div>
        <div class="nav-item">
            <a onclick="openLoginModal()" class="nav-link">SIGN IN</a>
        </div>

        @if ($mainUser)
            <div class="mobile-user-panel">
                <div class="mobile-user-header">
                    <div class="avatar">
                        <i class="fa fa-user-circle"></i>
                    </div>
                    <div class="details">
                        <span class="name">{{ $mainUser->fl_name }} {{ $mainUser->last_name }}</span>
                        <span class="email">{{ $mainUser->email }}</span>
                    </div>
                </div>
                <div class="mobile-user-links">
                    <a href="{{ config('app.url') }}panel" class="mobile-link">
                        <i class="fa fa-user"></i>
                        <span>Dashboard</span>
                    </a>
                    {{-- <a href="#" class="mobile-link">
                        <i class="fa fa-cog"></i>
                        <span>Settings</span>
                    </a> --}}
                    <a href="#" class="mobile-link logout" onclick="logout()">
                        <i class="fa fa-sign-out"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        @else
            {{-- <div class="nav-item">
                <a href="{{ route('login') }}" class="nav-link login-link">Login</a>
            </div> --}}
        @endif
    </div>


</div>
