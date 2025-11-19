<footer id="colophon" class="site-footer">
    <div class="container">
        <div class="row ">

            <div class="text-center lg-hidden mb-sm">
                <a href="{{ config(key: 'app.url') }}" class=" logo-header "><img
                        src="{{ config('app.url') }}assets-file/img/logo-footer.png" alt="logo"></a>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4  ">

                <ul class="list-unstyled contact-links">
                    <li><i class="fa fa-envelope" aria-hidden="true"></i><a
                            href="mailto:Besmanico@gmail.com">Besmanico@gmail.com</a></li>
                    <li>
                        <i class="fa fa-phone" aria-hidden="true"></i>
                        <a href="tel:+19494328383">
                            +1 949 432 8383
                        </a>
                    </li>
                    <li><i class="fa fa-whatsapp" aria-hidden="true"></i><a href="https://wa/me/+19494328383"
                            target="_blank">+1 949 432 8383</a></li>
                    <li>
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        <a >
                            Irvine, CA USA &nbsp;
                        </a>
                    </li>
                </ul>
            </div>



            <div class="col-lg-4 col-md-4 col-sm-4 text-center ">
                <a href="{{ config(key: 'app.url') }}" class=" logo-header sm-hidden"><img
                        src="{{ config('app.url') }}assets-file/img/logo-footer.png" alt="logo"></a>

                <p class="txt-footer sm-hidden" style="margin-top: 12px; ">A professional group focused on
                    <br> creative and results-driven solutions.
                </p>
                <p class="txt-footer sm-hidden" style="margin-top: 5px; "> 24 / 7 </p>

                {{-- <p class="txt-footer" style=" margin-top: 11px;">© 2025, All rights reserved </p> --}}

                {{-- <p class="txt-footer" style="margin-top: 5px;">We measure our success by the results we drive for our clients.</p> --}}
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 sm-hidden ">

                <ul class="list-unstyled">
                    <li class="text-center"><a href="{{ config('app.url') }}contactus">Contact Us</a></li>
                    <li class="text-center"><a href="{{ config('app.url') }}aboutus">About Us</a></li>

                    <li class="text-center"><a href="{{ config('app.url') }}services">Services</a></li>
                    <li class="text-center"><a href="{{ config('app.url') }}careers">Careers</a></li>
                </ul>
            </div>



        </div>
    </div>
    <div class="copyright">
        <div class="container">
            <div class="row">

                <div class="col-xs-12">
                    <div class="text-center">

                        <p>© 2025, All rights reserved </p>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.copyright -->
</footer><!-- /#footer -->
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