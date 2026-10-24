<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ gs()->siteName(__($pageTitle)) }} - Student Portal</title>
    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}" />
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon & Touch Icons -->
    <link rel="shortcut icon" href="{{ siteFavicon() }}" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ siteFavicon() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ siteFavicon() }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/logo_icon/apple-touch-icon.png') }}?v={{ file_exists(base_path('../assets/images/logo_icon/apple-touch-icon.png')) ? filemtime(base_path('../assets/images/logo_icon/apple-touch-icon.png')) : time() }}">
    <meta name="theme-color" content="#0C0E17">

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/portal.css') }}?v={{ time() }}">
    @stack('style-lib')
    @stack('style')
</head>

<body class="ak23-portal-body">

<div class="ak23-portal-wrapper">
    <!-- =========================================================================
         LEFT SIDEBAR (MAARIFA23 STUDENT PORTAL)
         ========================================================================= -->
    <aside class="ak23-portal-sidebar">
        <!-- Brand Header -->
        <a href="{{ route('home') }}" class="ak23-portal-brand text-decoration-none d-flex flex-column gap-1">
            <img src="{{ siteLogo('dark') }}" alt="{{ gs('site_name') }}" class="ak23-portal-logo-img" style="height: 28px; max-width: 175px; width: auto; object-fit: contain;">
            <span class="ak23-portal-brand-subtitle text-muted fs-10 fw-bold letter-spacing-1 ps-1" style="font-size: 10px; letter-spacing: 0.1em; opacity: 0.7;">STUDENT PORTAL</span>
        </a>

        <!-- Navigation Menu -->
        <div class="ak23-portal-menu-wrapper">
            <!-- Group 1: STUDIO KITS & OFFLINE DOWNLOADS -->
            <div class="ak23-portal-menu-group">
                <div class="ak23-portal-group-label">STUDIO KITS & DOWNLOADS</div>

                <a href="{{ route('user.home') }}" class="ak23-portal-nav-item {{ request()->routeIs('user.home') ? 'active' : '' }}">
                    <i class="las la-th-large"></i>
                    <span>My Dashboard</span>
                </a>

                <a href="{{ route('user.home') }}#courses-section" class="ak23-portal-nav-item">
                    <i class="las la-compact-disc"></i>
                    <span>Purchased Courses</span>
                    <span class="ak23-portal-pill blue">{{ $coursesCount ?? 3 }}</span>
                </a>

                <a href="{{ route('user.home') }}#downloads-section" class="ak23-portal-nav-item">
                    <i class="las la-download"></i>
                    <span>Offline Downloads</span>
                    <span class="ak23-portal-pill green">{{ $downloadsCount ?? 12 }}</span>
                </a>

                <a href="{{ route('user.home') }}#licenses-section" class="ak23-portal-nav-item">
                    <i class="las la-key"></i>
                    <span>Software Licenses</span>
                    <span class="ak23-portal-pill amber">{{ $licensesCount ?? 3 }}</span>
                </a>
            </div>

            <!-- Group 2: ACCOUNT -->
            <div class="ak23-portal-menu-group">
                <div class="ak23-portal-group-label">ACCOUNT</div>

                <a href="{{ route('user.order.list') }}" class="ak23-portal-nav-item {{ request()->routeIs('user.order.*') ? 'active' : '' }}">
                    <i class="las la-credit-card"></i>
                    <span>My Orders</span>
                </a>

                <a href="{{ route('user.profile.setting') }}" class="ak23-portal-nav-item {{ request()->routeIs('user.profile.*') ? 'active' : '' }}">
                    <i class="las la-user"></i>
                    <span>Profile</span>
                </a>

                <a href="{{ route('ticket.index') }}" class="ak23-portal-nav-item {{ request()->routeIs('ticket*') ? 'active' : '' }}">
                    <i class="las la-envelope"></i>
                    <span>Support</span>
                </a>

                <!-- Merged Full Stack Capabilities -->
                <a href="{{ route('user.transactions') }}" class="ak23-portal-nav-item {{ request()->routeIs('user.transactions') ? 'active' : '' }}">
                    <i class="las la-exchange-alt"></i>
                    <span>Transactions</span>
                </a>

                <a href="{{ route('user.twofactor') }}" class="ak23-portal-nav-item {{ request()->routeIs('user.twofactor*') ? 'active' : '' }}">
                    <i class="las la-lock"></i>
                    <span>2FA Security</span>
                </a>

                @if(Route::has('user.kyc.data'))
                <a href="{{ route('user.kyc.data') }}" class="ak23-portal-nav-item {{ request()->routeIs('user.kyc*') ? 'active' : '' }}">
                    <i class="las la-id-card"></i>
                    <span>KYC Verification</span>
                </a>
                @endif
            </div>
        </div>

        <!-- Need Help Card -->
        <div class="ak23-portal-help-card">
            <div class="ak23-portal-help-icon">
                <i class="las la-envelope"></i>
            </div>
            <div class="ak23-portal-help-title">Need help?</div>
            <p class="ak23-portal-help-desc">
                Contact the Maarifa23 support team about a course, download, or license.
            </p>
            <a href="{{ route('ticket.open') }}" class="ak23-portal-help-btn">Get support</a>
        </div>

        <!-- User Profile Footer -->
        @php
            $portalUser = auth()->user();
            $portalUserName = $portalUser ? ($portalUser->fullname ?: $portalUser->username) : 'Student';
            $portalUserInitials = $portalUser ? strtoupper(substr($portalUser->firstname ?: $portalUser->username, 0, 1) . substr($portalUser->lastname ?: '', 0, 1)) : 'ST';
        @endphp
        <div class="ak23-portal-user-footer">
            <div class="ak23-portal-avatar-circle">
                <span>{{ $portalUserInitials }}</span>
            </div>
            <div class="ak23-portal-user-info">
                <div class="ak23-portal-user-name" title="{{ $portalUserName }}">{{ Str::limit($portalUserName, 16) }}</div>
                <div class="ak23-portal-user-sub">Student account</div>
            </div>
            <a href="{{ route('user.logout') }}" class="ak23-portal-logout-link" title="Logout">
                <i class="las la-arrow-right"></i>
            </a>
        </div>
    </aside>

    <!-- =========================================================================
         MAIN PORTAL CONTENT AREA
         ========================================================================= -->
    <div class="ak23-portal-main">
        <!-- Top Navbar -->
        <header class="ak23-portal-topbar">
            <!-- Mobile Toggle -->
            <button class="ak23-portal-sidebar-toggle d-lg-none" type="button" id="portalSidebarToggle">
                <i class="las la-bars"></i>
            </button>

            <!-- Search Field -->
            <div class="ak23-portal-search-box">
                <i class="las la-search"></i>
                <input type="text" placeholder="Search your courses and downloads..." readonly onclick="window.location.href='{{ route('products') }}'">
                <span class="ak23-portal-kbd">⌘K</span>
            </div>

            <!-- Topbar Utilities -->
            <div class="ak23-portal-top-utils">
                <a href="{{ route('ticket.index') }}" class="ak23-portal-util-btn" title="Messages & Support">
                    <i class="las la-envelope"></i>
                </a>

                <div class="ak23-portal-top-avatar" title="{{ $portalUserName }}">
                    <span>{{ $portalUserInitials }}</span>
                </div>
            </div>
        </header>

        <!-- Body Content -->
        <main class="ak23-portal-content">
            @yield('content')
        </main>

        <!-- Custom Student Portal Dedicated Footer (Zero Home Dependencies) -->
        <footer class="ak23-portal-footer">
            <div class="ak23-portal-footer-content">
                <div class="ak23-portal-footer-left">
                    <span class="ak23-portal-status-pill">
                        <span class="ak23-portal-status-dot"></span>
                        @lang('System Operational')
                    </span>
                    <span class="ak23-portal-footer-copy">
                        &copy; {{ date('Y') }} {{ gs('site_name') }} Student Portal &middot; @lang('All rights reserved.')
                    </span>
                </div>
                <div class="ak23-portal-footer-links">
                    <a href="{{ route('products') }}">@lang('Course Catalog')</a>
                    <a href="{{ route('user.order.list') }}">@lang('My Orders')</a>
                    <a href="{{ route('ticket.index') }}">@lang('Support')</a>
                    <a href="{{ route('faq') }}">@lang('FAQ')</a>
                    <a href="{{ route('policy.pages', 'privacy-policy') }}">@lang('License Terms')</a>
                </div>
            </div>
        </footer>
    </div>
</div>

<script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
<script>
    $('#portalSidebarToggle').on('click', function() {
        $('.ak23-portal-sidebar').toggleClass('show');
    });
</script>
@stack('script-lib')
@stack('script')

@include('partials.notify')

@php echo loadExtension('whatsapp-chat') @endphp

</body>
</html>
