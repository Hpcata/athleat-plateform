<?php
$setting = \App\Models\SiteSettings::where('page_id', 'general')->where('meta_key', 'header_headermenu')->first();
$headerData = json_decode($setting['meta_value'], true);
$auth = auth()->guard('web')->check();
?>

@if (Route::is('front.profile') || Route::is('front.plans.details') || Route::is('front.my-plans'))
    <!-- Mobile Menu Overlay -->
    <?php $user = auth()->user();?>
    <div class="mobile-menu-overlay" id="mobile-menu-overlay" onclick="toggleMobileMenu()"
        style=" position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.6); z-index:1999;">
    </div>
    <header class="mobile-header">
        <img src="{{ frontAssets('images/logo.svg') }}" alt="athleat logo" class="mobile-logo-img" width="140"height="30" />
        <button class="mobile-menu-toggle" aria-label="Toggle mobile menu" onclick="toggleMobileMenu()"
            style="background: none; border: none; color: #fff; cursor: pointer;margin: 0 !important;">
            <span id="mobile-menu-icon">
                <!-- This will be replaced by JS -->
                 <img src="{{ frontAssets('images/bars.svg') }}" alt="hamburger" class=""  id="hamburger-icon"/>
                <span id="close-icon" style="display:none;"><img src="{{ frontAssets('images/cross.svg') }}" alt="hamburger" class=""  /></span>
            </span>
        </button>
    </header>

    <!-- Mobile Menu Markup -->
    <div class="mobile-menu" id="mobile-menu" style="z-index:2000;">
        <ul class="mobile-menu-list">
            @if (Auth::check() && Auth::guard('web')->user()->is_superadmin == 0)
                @php
                    $userId = Auth::guard('web')->user()->id;
                    $userPlan = \App\Models\UserPlan::with([
                        'plan',
                    ])->where('user_id', $userId)->first();

                    $myPlanUrl = route('front.my-plans');
                    // if (isset($userPlan)) {
                    //     $myPlanUrl = route('front.plans.details', ['id' => $userPlan->plan->id, 'user_id' => $userPlan->user->id]);
                    // }
                @endphp
                <li class="mobile-menu-link"><a
                        href="{{ route('front.profile', ['id' => Auth::guard('web')->user()->id]) }}"
                        onclick="toggleMobileMenu()"
                        style="color: #fff; text-decoration: none; display: block; padding: 8px 16px;">Home</a>
                </li>
                <li class="mobile-menu-link"><a
                        href="{{ $myPlanUrl }}"
                        onclick="toggleMobileMenu()"
                        style="color: #fff; text-decoration: none; display: block; padding: 8px 16px;">My Plan</a>
                </li>
            @endif
            <li class="mobile-menu-link coming-soon-popup"><a href="#" onclick="toggleMobileMenu()"
                style="color: #fff; text-decoration: none; display: block; padding: 8px 16px;">Challenges and
                Rewards</a>
            </li>
            <li>
                <div class="mobile-menu-divider" style="height:1px; background:#555; margin: 12px 16px;"></div>
            </li>
            <li><a href="#" id="scanner-btn" class="scanner-btn">Supplement Scanner</a></li>
            <li><a href="#" class="coming-soon-popup">Level-Up Library</a></li>
            <li><a href="#" onclick="openBookingAndModal()">BioHealth Passport</a></li>
            <li>
                <div class="mobile-menu-divider" style="height:1px; background:#555; margin: 12px 16px;"></div>
            </li>
            @if (Auth::check() && Auth::guard('web')->user()->is_superadmin == 0)
                <li class="mobile-menu-link">
                    <form id="logout-form-mobile" action="{{ route('front.logout') }}" method="POST"
                        style="display: none;">@csrf</form>
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit(); toggleMobileMenu();"
                        style="color: #fff; text-decoration: none; display: block; padding: 8px 16px;">Sign out</a>
                </li>
            @else
                <li class="mobile-menu-link"><a href="#" onclick="toggleMobileMenu()"
                        style="color: #fff; text-decoration: none; display: block; padding: 8px 16px;">Sign in</a></li>
            @endif
        </ul>
    </div>
    @if (Auth::check() && Auth::guard('web')->user()->is_superadmin == 0)
        <header class="header">
            <div class="header-content">
                <div class="logo">
                    <a href="{{ route('front.index') }}">
                    <img src="{{ frontAssets('images/logo.svg') }}" alt="Athleat Logo" class="logo-img" width="142"
                        height="30" />
                    </a>
                </div>
                @php
                    $userId = Auth::guard('web')->user()->id;
                    $userPlan = \App\Models\UserPlan::with([
                        'plan',
                    ])->where('user_id', $userId)->first();

                    $myPlanUrl = route('front.my-plans');
                    // if (isset($userPlan)) {
                    //     $myPlanUrl = route('front.plans.details', ['id' => $userPlan->plan->id, 'user_id' => $userPlan->user->id]);
                    // }
                @endphp
                <nav class="nav-center">
                    <a class="text-decoration-none nav-item" href="{{ route('front.profile', ['id' => Auth::guard('web')->user()->id]) }}">Home</a>
                    <span class="nav-item coming-soon-popup">Challenges and Rewards</span>
                    <div class="nav-item dropdown">
                        <span>Resources <i class="fas fa-chevron-down"></i></span>
                        <div class="dropdown-content">
                            <a href="#" id="scanner-btn" class="scanner-btn">Supplement Scanner</a>
                            <a href="#" class="coming-soon-popup">Level-Up Library</a>
                            <a href="#" onclick="openBookingAndModal()">BioHealth
                                Passport</a>
                        </div>
                    </div>
                </nav>
                <div class="nav-right">
                    <div class="nav-item dropdown">
                        <div class="nav-end">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                fill="none">
                                <path
                                    d="M9 9C10.1935 9 11.3381 8.52589 12.182 7.68198C13.0259 6.83807 13.5 5.69347 13.5 4.5C13.5 3.30653 13.0259 2.16193 12.182 1.31802C11.3381 0.474106 10.1935 0 9 0C7.80653 0 6.66193 0.474106 5.81802 1.31802C4.97411 2.16193 4.5 3.30653 4.5 4.5C4.5 5.69347 4.97411 6.83807 5.81802 7.68198C6.66193 8.52589 7.80653 9 9 9ZM7.39336 10.6875C3.93047 10.6875 1.125 13.493 1.125 16.9559C1.125 17.5324 1.59258 18 2.16914 18H15.8309C16.4074 18 16.875 17.5324 16.875 16.9559C16.875 13.493 14.0695 10.6875 10.6066 10.6875H7.39336Z"
                                    fill="white" />
                            </svg>
                            <span>My Account <i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div class="dropdown-content">
                            <a href="{{ $myPlanUrl }}">My Plan</a>
                            <form id="logout-form" action="{{ route('front.logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                            <a class="p-2 dropdown-item" style="padding:0.75rem 1rem !important;" href="#"
                                onclick="handleLogout(event)">
                                Sign Out
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    @else
        @php $id = request()->route('id'); @endphp
        <header class="header">
            <div class="header-content">
                <div class="logo">
                    <img src="{{ frontAssets('images/logo.svg') }}" alt="Athleat Logo" class="logo-img" width="142"
                        height="30" />
                </div>
                    <nav class="nav-center">
                        <a class="text-decoration-none nav-item" href="{{ route('front.profile', ['id' => $id]) }}?admin_view=1">Home</a>
                        <span class="nav-item coming-soon-popup">Challenges and Rewards</span>
                        <div class="nav-item dropdown">
                            <span>Resources <i class="fas fa-chevron-down"></i></span>
                            <div class="dropdown-content">
                                <a href="#" id="scanner-btn" class="scanner-btn">Supplement Scanner</a>
                                <a href="#" class="coming-soon-popup">Level-Up Library</a>
                                <a href="#" onclick="openBookingAndModal()">BioHealth
                                    Passport</a>
                            </div>
                        </div>
                    </nav>
                    <div class="nav-right">
                        <button class="btn-login mob-hide" id="login" href="#"
                        onclick="openSingupFreePopup(true)">Log in</button>
                        <button class="btn-signup" id="show-new-signup-modal" onclick="openSingupFreePopup()">
                            Sign up for free
                        </button>
                    </div>
                </div>
        </header>
    @endif
@else
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom homepage-navbar">
        <div class="container-homepage">
            <a class="navbar-brand" href="{{ route('front.index') }}">
                <img src="{{ frontAssets('images/logo.svg') }}" alt="ATHLEAT Fuel Logo" />
            </a>
            <div class="mob-btn-wrap">
                <button class="me-0 btn-login web-hide" onclick="openSingupFreePopup(true)">Log in</button>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    style="border: none">
                    <span class="menu-icon" style="color: white">
                        <img href="{{ route('front.index') }}" src="{{ frontAssets('images/bars.svg') }}" alt="ATHLEAT Fuel Logo" class="bars-icon" />
                        <img href="{{ route('front.index') }}" src="{{ frontAssets('images/cross.svg') }}" alt="Menu" class="cross-icon" />
                    </span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="mx-auto navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('front.index') }}">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Services
                            <svg width="10" height="7" viewBox="0 0 10 7" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 1.5L5 5.5L9 1.5" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </a>
                        <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('front.training.nutrition.plan') }}">Training Nutrition Plan</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('front.competition.plan') }}">Competition Plan</a>
                        <li>
                            <a class="dropdown-item" href="{{ route('front.injury.recovery.plan') }}">Injury & Recovery Plan</a>
                        </li>
                        <li><a class="scroll-to-plans dropdown-item competition-plan-link row2" href="#">Pre & Post Surgery Plan</a></li>
                        <li><a class="dropdown-item" href="{{ route('front.consultations') }}">Private Consultations</a></li>
                        <li><a class="scroll-to-plans dropdown-item competition-plan-link row2" href="">Clubs and Group bookings</a></li>
                        </ul>
                    </li>
                </ul>

                <div class="d-flex">
                    @if (Auth::check())
                        <a href="{{ route('front.profile', ['id' => Auth::guard('web')?->user()?->id]) }}"
                            class="btn btn-signup mob-hide">
                            My Account
                        </a>
                    @else
                        <button class="btn-login mob-hide" id="login" href="#"
                            onclick="openSingupFreePopup(true)">Log in</button>
                        <button class="btn-signup" id="show-new-signup-modal" onclick="openSingupFreePopup()">
                            Sign up for free
                        </button>
                    @endif
                    <button class="ms-2 btn-login web-hide">Virtual Kez</button>
                </div>
            </div>
        </div>
    </nav>
@endif

@include('front.modal.single-signup')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons/css/flag-icons.min.css">
<script>
    window.otpRoutes = {
        sendOtp: "{{ route('front.otp.send') }}",
        verifyOtp: "{{ route('front.otp.verify') }}",
        resendOtp: "{{ route('front.otp.resend') }}",
        registerWithOtp: "{{ route('front.otp.register') }}"
    }
</script>
<script src="{!! frontAssets('js/jquery-3.6.min.js') !!}"></script>
<script src="{!! frontAssets('js/otp-registration.js') !!}"></script>
<script src="{!! frontAssets('js/single-signup.js') !!}"></script>

<script>
    function handleLogout(event) {
        event.preventDefault();

        // Attempt logout via POST
        try {
            // Check for existing CSRF token
            const csrfToken = document.querySelector('input[name="_token"]').value;
            if (csrfToken) {
                document.getElementById('logout-form').submit();
            } else {
                // If no CSRF token found (likely session expired)
                window.location.href = "{{ route('front.logout.guest') }}";
            }
        } catch (e) {
            // Fallback in case of any error
            window.location.href = "{{ route('front.logout.guest') }}";
        }
    }

    $(document).ready(function() {
        $('#login').on('click', function() {
            // $('#loginModal').modal('show');
        })
        $('#login-form').submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting the normal way

            // Disable the Submit Button to avoid multiple clicks
            $('#login-submit').prop('disabled', true);

            // Get the form data
            var email = $('#login-email').val();
            var password = $('#login-password').val();

            // Send the data to the backend for validation
            $.ajax({
                url: '{{ route('front.login') }}', // This is the route for handling login (update with your actual route if different)
                method: 'POST',
                data: {
                    email: email,
                    password: password,
                    _token: '{{ csrf_token() }}' // CSRF token for protection
                },
                success: function(response) {
                    if (response.success) {
                        if (response.message == 'Plan not purchased.') {
                            alert('Please complete your profile.');
                        }
                        // If login is successful, redirect to the given URL
                        window.location.href = response.redirect_url;
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON;

                    // Show error messages for validation errors
                    if (response.message) {
                        if (response.message == 'CSRF token mismatch.') {
                            $('#login-error').text(
                                'Your session has expired. Please reload the page and login again.'
                            );
                        } else {
                            $('#login-error').text(response
                                .message); // Display error message in #login-error div
                        }
                    } else {
                        $('#login-error').text(
                            'An error occurred. Please try again.'
                            ); // General error message
                    }

                    $('#login-submit').prop('disabled', false); // Re-enable submit button
                }
            });
        });

        $('#forgot-password').on('click', function(e) {
            e.preventDefault();
            $('#loginModal').modal('hide');
            $('#forgotPasswordModal').modal('show'); // Show the modal
        });

        $('#forgotPasswordForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('front.password.request') }}",
                method: 'POST',
                data: {
                    email: $('#email').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert('Password reset link has been sent to your email.');
                    $('#forgotPasswordModal').modal('hide');
                },
                error: function(xhr) {
                    alert('Failed to send reset link. Please check your email address.');
                }
            });
        });
    });
</script>

<script>
    function toggleMobileMenu() {
        console.log('toggleMobileMenu called');
        var menu = document.getElementById('mobile-menu');
        var overlay = document.getElementById('mobile-menu-overlay');
        var hamburgerIcon = document.getElementById('hamburger-icon');
        var closeIcon = document.getElementById('close-icon');
        var isOpen = menu.classList.contains('open');
        if (isOpen) {
            menu.classList.remove('open');
            overlay.classList.remove('open');
            document.body.style.overflow = '';
            hamburgerIcon.style.display = 'inline';
            closeIcon.style.display = 'none';
        } else {
            menu.classList.add('open');
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
            hamburgerIcon.style.display = 'none';
            closeIcon.style.display = 'inline';
        }
    }

    // Global function to clear consultation login flag
    window.clearConsultationLoginFlag = function() {
        sessionStorage.removeItem('loginTriggeredByConsultation');
    };

    function openSingupFreePopup(isLogin = false, isQuiz = false) {
        // Clear consultation login flag since this is not triggered by consultation booking
        if (typeof window.clearConsultationLoginFlag === 'function') {
            window.clearConsultationLoginFlag();
        }
        
        if (isQuiz) {
            $('#signupModalathlete .signup-login-h2-title').addClass('d-none');
            $('#signupModalathlete .quiz-h2-title').removeClass('d-none');
            $('#isFromQuizPopup').val(1);
        } else {
            $('#signupModalathlete .signup-login-h2-title').removeClass('d-none');
            $('#signupModalathlete .quiz-h2-title').addClass('d-none');

            // manage this
            $('#signupModalathlete .signup-login-h2-title .welcome-title').html(isLogin ? 'Welcome Back' : 'Welcome');

            if (isLogin) {
                $('#signupModalathlete #new-user-singup').removeClass('d-none');
                $('#signupModalathlete #existing-user-login').addClass('d-none');
            } else {
                $('#signupModalathlete #new-user-singup').addClass('d-none');
                $('#signupModalathlete #existing-user-login').removeClass('d-none');
            }
        }

        $('#signupModalathlete').modal('show');
    }

    function openBookingAndModal() {
        window.open('https://booking.biohealthpassport.com.au/kerry-obryan', '_blank');
        $('#myModal').show();
    }

    $(document).ready(function() {
        $(document).on('click', '#existing-user-login', function() {
            openSingupFreePopup(true);
        });

        $(document).on('click', '#new-user-singup', function() {
            openSingupFreePopup();
        });

        $('.scanner-btn').click(function() {
            location.href = "{{ route('front.supplement-scanner') }}";
        });
    });

    // Mobile menu toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const navbarToggler = document.querySelector('.navbar-toggler');
        const navbarCollapse = document.querySelector('.navbar-collapse');
        const navbar = document.querySelector('.homepage-navbar');
        const barsIcon = document.querySelector('.bars-icon');
        const crossIcon = document.querySelector('.cross-icon');

        if (navbarToggler && navbarCollapse) {
            // Custom click handler to control timing
            navbarToggler.addEventListener('click', function(e) {
                e.preventDefault();

                // Check if menu is currently open
                const isMenuOpen = navbarCollapse.classList.contains('show');

                if (!isMenuOpen) {
                    // Menu is closed, opening it
                    // 1. Immediately change background color
                    if (navbar) navbar.classList.add('menu-open');

                    // 2. Change icon immediately
                    if (barsIcon) barsIcon.style.display = 'none';
                    if (crossIcon) crossIcon.style.display = 'block';

                    // 3. Open menu after 0.1s delay
                    setTimeout(() => {
                        navbarCollapse.classList.add('show');
                    }, 100);
                } else {
                    // Menu is open, closing it
                    // 1. Immediately remove background color
                    if (navbar) navbar.classList.remove('menu-open');

                    // 2. Change icon immediately
                    if (barsIcon) barsIcon.style.display = 'block';
                    if (crossIcon) crossIcon.style.display = 'none';

                    // 3. Close menu after 0.1s delay
                    setTimeout(() => {
                        navbarCollapse.classList.remove('show');
                    }, 100);
                }
            });

            // Remove Bootstrap's default toggle behavior
            navbarToggler.removeAttribute('data-bs-toggle');
            navbarToggler.removeAttribute('data-bs-target');
        }

        // Function to update navbar background based on scroll position
        function updateNavbarBackground() {
            // Check for both navbar types: homepage navbar and consultations header
            const homepageNavbar = document.querySelector(".navbar-custom");
            const consultationsHeader = document.querySelector(".header");

            if (homepageNavbar) {
                // Homepage navbar
                if (window.scrollY > 50) {
                    homepageNavbar.style.background = "rgba(59, 59, 59, 1)";
                } else {
                    homepageNavbar.style.background = "transparent";
                }
            } else if (consultationsHeader) {
                // Consultations page header
                if (window.scrollY > 50) {
                    consultationsHeader.style.background = "rgba(59, 59, 59, 0.95)";
                    consultationsHeader.style.backdropFilter = "blur(10px)";
                } else {
                    consultationsHeader.style.background = "transparent";
                    consultationsHeader.style.backdropFilter = "none";
                }
            }
        }

        // Check if current URL is /training-nutrition-plan, /, or /about-us
        if (window.location.pathname === '/training-nutrition-plan' || window.location.pathname === '/' || window.location.pathname === '/about-us' || window.location.pathname === '/competition-plan' || window.location.pathname === '/injury-recovery-plan' || window.location.pathname === '/surgery-plan' || window.location.pathname === '/consultations') {

            // Check initial scroll position on page load
            updateNavbarBackground();

            // Smooth navbar background change on scroll
            window.addEventListener("scroll", updateNavbarBackground);
        } else {
            // For other pages, check if it's a homepage navbar or consultations header
            const homepageNavbar = document.querySelector(".navbar-custom");
            const consultationsHeader = document.querySelector(".header");

            if (homepageNavbar) {
                homepageNavbar.style.background = "#3b3b3b";
            } else if (consultationsHeader) {
                // Apply background to consultations header for other pages
                consultationsHeader.style.background = "rgba(59, 59, 59, 0.95)";
            }
        }

        // Custom select arrow rotation and enhanced styling
        const customSelects = document.querySelectorAll('.custom-select-wrapper select');
        customSelects.forEach(select => {
            // Add custom styling to options
            const options = select.querySelectorAll('option');
            options.forEach(option => {
                option.style.padding = '12px 16px';
                option.style.margin = '2px 0';
                option.style.fontFamily = '"Noto Sans"';
                option.style.fontSize = '16px';
                option.style.fontWeight = '400';
                option.style.color = '#3b3b3b';
                option.style.backgroundColor = '#fff';
                option.style.border = 'none';
                option.style.cursor = 'pointer';
                option.style.lineHeight = '1.5';
                option.style.minHeight = '44px';
            });

            select.addEventListener('change', function() {
                const arrow = this.nextElementSibling;
                if (arrow && arrow.classList.contains('custom-select-arrow')) {
                    arrow.style.transform = 'translateY(-50%) rotate(180deg)';
                    setTimeout(() => {
                        arrow.style.transform = 'translateY(-50%) rotate(0deg)';
                    }, 200);
                }
            });

            select.addEventListener('focus', function() {
                const arrow = this.nextElementSibling;
                if (arrow && arrow.classList.contains('custom-select-arrow')) {
                    arrow.style.transform = 'translateY(-50%) rotate(180deg)';
                }
            });

            select.addEventListener('blur', function() {
                const arrow = this.nextElementSibling;
                if (arrow && arrow.classList.contains('custom-select-arrow')) {
                    arrow.style.transform = 'translateY(-50%) rotate(0deg)';
                }
            });

            // Add click event to show dropdown with better styling
            select.addEventListener('click', function() {
                // Force the dropdown to open with better styling
                this.style.padding = '12px 16px';
                this.style.lineHeight = '1.5';
            });
        });

        document.querySelectorAll('.coming-soon-popup').forEach(function(card) {
            card.addEventListener('click', function(e) {
                var comingSoonModal = document.getElementById('comingSoonModal');
                if (comingSoonModal && typeof bootstrap !== 'undefined') {
                    e.preventDefault();
                    var modal = new bootstrap.Modal(comingSoonModal);
                    modal.show();
                }
            });
        });

         // Smooth scroll to plans section
        (function () {
            const SCROLL_KEY = 'scrollTargetPlans';
            const HOMEPATHS = ['/'];
            const isHome = () => HOMEPATHS.includes(window.location.pathname);

            function doScroll(row) {
                const section = document.querySelector('.choose-plan-section');
                if (!section) return false;

                const sticky = document.querySelector('.navbar.fixed-top, .navbar.sticky-top');
                const stickyH = sticky ? sticky.offsetHeight : 0;

                const extra = row === 'row2' ? window.innerHeight / 2 : 0; // reveal second row
                const top = section.getBoundingClientRect().top + window.scrollY - stickyH + extra;

                window.scrollTo({ top, behavior: 'smooth' });
                return true;
            }

            function scrollWithRetry(row, attempts = 15) {
                if (doScroll(row)) return;
                if (attempts <= 0) return;
                setTimeout(() => scrollWithRetry(row, attempts - 1), 100);
            }

            document.querySelectorAll('.scroll-to-plans').forEach((link) => {
                link.addEventListener('click', function (e) {
                e.preventDefault();

                const navCol = document.querySelector('.navbar-collapse');
                if (navCol && navCol.classList.contains('show')) {
                    document.querySelector('.navbar-toggler')?.click();
                }

                const row = link.classList.contains('row2') ? 'row2' : 'row1';

                if (!isHome()) {
                    sessionStorage.setItem(SCROLL_KEY, row);
                    window.location.assign('/'); // redirect to homepage
                } else {
                    scrollWithRetry(row);
                }
                });
            });

            // After redirect to home, perform the scroll
            if (isHome()) {
                const row = sessionStorage.getItem(SCROLL_KEY);
                if (row) {
                sessionStorage.removeItem(SCROLL_KEY);
                window.addEventListener('load', () => {
                    requestAnimationFrame(() => requestAnimationFrame(() => scrollWithRetry(row)));
                });
                }
            }
            })();
});
</script>

