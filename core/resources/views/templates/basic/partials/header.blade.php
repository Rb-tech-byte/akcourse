@php
    $cartLength = (int)cartCount();
    $user = auth()->user();
    $navItems = getHeaderNavItems();
    $logoDark = siteLogo('dark');
@endphp

<!-- =========================================================================
     AK23 MAIN NAVBAR (RESPONSIVE & MOBILE-FIRST)
     ========================================================================= -->
<header class="ak23-header" id="header">
    <div class="ak23-container">
        <div class="ak23-header-inner">
            <!-- Brand Logo -->
            <a class="ak23-brand" href="{{ route('home') }}" aria-label="{{ gs('site_name') }}">
                <img src="{{ $logoDark }}" alt="{{ gs('site_name') }}" class="ak23-brand-img">
            </a>

            <!-- Desktop Navigation Links (>= 992px) -->
            <ul class="ak23-nav-links d-none d-lg-flex mb-0">
                @foreach($navItems as $item)
                    <li>
                        <a href="{{ $item['url'] }}" class="ak23-nav-link {{ $item['active'] ? 'active' : '' }}" @if(isset($item['series'])) data-series="{{ $item['series'] }}" @endif>
                            {{ __($item['title']) }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <!-- Header Right Action Group -->
            <div class="ak23-header-right">
                <!-- Desktop Auth Link (>= 768px) -->
                @guest
                    <a href="{{ route('user.login') }}" class="ak23-login-link d-none d-md-inline-flex">
                        <i class="las la-user"></i>
                        <span>@lang('Login')</span>
                    </a>
                @else
                    <a href="{{ route('user.home') }}" class="ak23-login-link d-none d-md-inline-flex" title="{{ $user->fullname ?: $user->username }}">
                        <i class="las la-user-circle"></i>
                        <span>@lang('My Portal')</span>
                    </a>
                @endguest

                <!-- Modern Shopping Cart Button (Mobile & Desktop Native) -->
                <a href="{{ route('cart.index') }}" class="ak23-cart-btn {{ $cartLength > 0 ? 'has-items' : '' }}" aria-label="@lang('Shopping Cart')">
                    <span class="ak23-cart-icon-wrap">
                        <i class="las la-shopping-bag"></i>
                        <span class="ak23-cart-badge {{ $cartLength > 0 ? '' : 'd-none' }}">{{ $cartLength }}</span>
                    </span>
                    <span class="ak23-cart-btn-label d-none d-sm-inline">@lang('Cart')</span>
                    <span class="ak23-cart-pill d-none d-md-inline {{ $cartLength > 0 ? '' : 'd-none' }}">{{ $cartLength }}</span>
                </a>

                <!-- Mobile Menu Drawer Toggle (< 992px) -->
                <button class="ak23-drawer-toggle d-lg-none" type="button" id="ak23DrawerToggle" aria-label="@lang('Open Navigation Menu')">
                    <span class="ak23-hamburger-line"></span>
                    <span class="ak23-hamburger-line"></span>
                    <span class="ak23-hamburger-line"></span>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- =========================================================================
     MOBILE MENU DRAWER (SLIDE-OVER APPLIKE NAVIGATION)
     ========================================================================= -->
<div class="ak23-drawer-backdrop" id="ak23DrawerBackdrop"></div>

<aside class="ak23-drawer" id="ak23MobileDrawer" aria-label="@lang('Mobile Navigation')">
    <!-- Drawer Header -->
    <div class="ak23-drawer-header">
        <a class="ak23-drawer-brand" href="{{ route('home') }}">
            <img src="{{ $logoDark }}" alt="{{ gs('site_name') }}">
        </a>
        <button type="button" class="ak23-drawer-close" id="ak23DrawerClose" aria-label="@lang('Close navigation menu')">
            <i class="las la-times"></i>
        </button>
    </div>

    <div class="ak23-drawer-body">
        <!-- 1. User Profile or Guest Login CTA Card -->
        @guest
            <div class="ak23-drawer-auth-card">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="ak23-drawer-auth-avatar">
                        <i class="las la-user"></i>
                    </div>
                    <div>
                        <div class="ak23-drawer-auth-title">@lang('Student Account')</div>
                        <div class="ak23-drawer-auth-desc">@lang('Sign in to access your offline courses and software licenses.')</div>
                    </div>
                </div>
                <div class="ak23-drawer-auth-actions">
                    <a href="{{ route('user.login') }}" class="ak23-drawer-btn-login">
                        <i class="las la-sign-in-alt me-1"></i> @lang('Log In')
                    </a>
                    <a href="{{ route('user.register') }}" class="ak23-drawer-btn-register">
                        <i class="las la-user-plus me-1"></i> @lang('Register')
                    </a>
                </div>
            </div>
        @else
            @php
                $initials = strtoupper(substr($user->firstname ?: $user->username, 0, 1) . substr($user->lastname ?: '', 0, 1));
            @endphp
            <div class="ak23-drawer-user-card">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="ak23-drawer-user-avatar">
                        <span>{{ $initials ?: 'ST' }}</span>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="ak23-drawer-user-name text-truncate">{{ $user->fullname ?: $user->username }}</div>
                        <div class="ak23-drawer-user-email text-truncate">{{ $user->email }}</div>
                        <span class="ak23-drawer-user-badge"><i class="las la-check-circle me-1"></i> @lang('Verified Student')</span>
                    </div>
                </div>
                <div class="ak23-drawer-user-actions">
                    <a href="{{ route('user.home') }}" class="ak23-drawer-btn-dashboard">
                        <i class="las la-th-large me-1"></i> @lang('My Courses & Downloads')
                    </a>
                    <a href="{{ route('user.logout') }}" class="ak23-drawer-btn-logout" title="@lang('Logout')">
                        <i class="las la-power-off"></i>
                    </a>
                </div>
            </div>
        @endguest

        <!-- 2. Course Categories & Series Navigation -->
        <div class="ak23-drawer-section-label">
            <i class="las la-graduation-cap me-1"></i> @lang('STUDIO COURSES & SERIES')
        </div>
        <div class="ak23-drawer-nav-list">
            @foreach($navItems as $item)
                @php
                    $seriesKey = $item['series'] ?? '';
                    $iconClass = match($seriesKey) {
                        'vocal-mixing' => 'la-microphone-alt',
                        'audio-editing-software' => 'la-sliders-h',
                        'beat-making' => 'la-drum',
                        default => 'la-compact-disc'
                    };
                    $badgeText = match($seriesKey) {
                        'vocal-mixing' => '5 Courses + Plugins',
                        'audio-editing-software' => '6 DAWs Bundled',
                        'beat-making' => '6 Courses + Kits',
                        default => 'Full Catalog'
                    };
                @endphp
                <a href="{{ $item['url'] }}" class="ak23-drawer-nav-item {{ $item['active'] ? 'active' : '' }}">
                    <div class="ak23-drawer-nav-icon">
                        <i class="las {{ $iconClass }}"></i>
                    </div>
                    <div class="ak23-drawer-nav-text">
                        <span class="ak23-drawer-nav-title">{{ __($item['title']) }}</span>
                        <span class="ak23-drawer-nav-sub">{{ $badgeText }}</span>
                    </div>
                    <i class="las la-angle-right ak23-drawer-nav-chevron"></i>
                </a>
            @endforeach
        </div>

        <!-- 3. Quick Access Utilities -->
        <div class="ak23-drawer-section-label mt-4">
            <i class="las la-compass me-1"></i> @lang('QUICK ACCESS')
        </div>
        <div class="ak23-drawer-nav-list">
            <a href="{{ route('cart.index') }}" class="ak23-drawer-nav-item">
                <div class="ak23-drawer-nav-icon">
                    <i class="las la-shopping-bag"></i>
                </div>
                <div class="ak23-drawer-nav-text">
                    <span class="ak23-drawer-nav-title">@lang('Shopping Cart')</span>
                    <span class="ak23-drawer-nav-sub">@lang('Offline master packages & licenses')</span>
                </div>
                <span class="ak23-drawer-pill-gold {{ $cartLength > 0 ? '' : 'd-none' }}">{{ $cartLength }}</span>
                <i class="las la-angle-right ak23-drawer-nav-chevron {{ $cartLength > 0 ? 'd-none' : '' }}"></i>
            </a>

            @auth
                <a href="{{ route('user.order.list') }}" class="ak23-drawer-nav-item">
                    <div class="ak23-drawer-nav-icon">
                        <i class="las la-receipt"></i>
                    </div>
                    <div class="ak23-drawer-nav-text">
                        <span class="ak23-drawer-nav-title">@lang('My Orders & Receipts')</span>
                    </div>
                    <i class="las la-angle-right ak23-drawer-nav-chevron"></i>
                </a>

                <a href="{{ route('ticket.index') }}" class="ak23-drawer-nav-item">
                    <div class="ak23-drawer-nav-icon">
                        <i class="las la-headset"></i>
                    </div>
                    <div class="ak23-drawer-nav-text">
                        <span class="ak23-drawer-nav-title">@lang('Student Support Desk')</span>
                    </div>
                    <i class="las la-angle-right ak23-drawer-nav-chevron"></i>
                </a>
            @endauth

            <a href="{{ route('faq') }}" class="ak23-drawer-nav-item">
                <div class="ak23-drawer-nav-icon">
                    <i class="las la-question-circle"></i>
                </div>
                <div class="ak23-drawer-nav-text">
                    <span class="ak23-drawer-nav-title">@lang('FAQ & System Help')</span>
                </div>
                <i class="las la-angle-right ak23-drawer-nav-chevron"></i>
            </a>

            <a href="{{ route('contact') }}" class="ak23-drawer-nav-item">
                <div class="ak23-drawer-nav-icon">
                    <i class="las la-envelope"></i>
                </div>
                <div class="ak23-drawer-nav-text">
                    <span class="ak23-drawer-nav-title">@lang('Contact Studio Team')</span>
                </div>
                <i class="las la-angle-right ak23-drawer-nav-chevron"></i>
            </a>
        </div>
    </div>

    <!-- Drawer Footer: WhatsApp Support & Assurance -->
    <div class="ak23-drawer-footer">
        <a href="https://wa.me/255752945293?text={{ urlencode('Hello Maarifa23, I need help with course download and licenses.') }}" target="_blank" class="ak23-drawer-whatsapp-btn" rel="noopener noreferrer">
            <i class="lab la-whatsapp"></i>
            <div class="flex-grow-1 text-start">
                <div class="ak23-drawer-whatsapp-title">@lang('WhatsApp Studio Support')</div>
                <div class="ak23-drawer-whatsapp-sub">@lang('Direct Studio Support (Dar es Salaam)')</div>
            </div>
            <span class="ak23-drawer-online-dot" title="@lang('Online')"></span>
        </a>
    </div>
</aside>

<!-- =========================================================================
     DRAWER INTERACTIVITY JAVASCRIPT
     ========================================================================= -->
<script>
(function() {
    function initAk23Drawer() {
        const toggleBtn = document.getElementById('ak23DrawerToggle');
        const closeBtn = document.getElementById('ak23DrawerClose');
        const backdrop = document.getElementById('ak23DrawerBackdrop');
        const drawer = document.getElementById('ak23MobileDrawer');

        if (!drawer || !backdrop) return;

        function openDrawer() {
            drawer.classList.add('is-open');
            backdrop.classList.add('is-visible');
            document.body.classList.add('ak23-drawer-open');
            if (toggleBtn) toggleBtn.classList.add('is-active');
        }

        function closeDrawer() {
            drawer.classList.remove('is-open');
            backdrop.classList.remove('is-visible');
            document.body.classList.remove('ak23-drawer-open');
            if (toggleBtn) toggleBtn.classList.remove('is-active');
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (drawer.classList.contains('is-open')) {
                    closeDrawer();
                } else {
                    openDrawer();
                }
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                closeDrawer();
            });
        }

        backdrop.addEventListener('click', closeDrawer);

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
                closeDrawer();
            }
        });

        // Close on nav link click inside drawer
        drawer.querySelectorAll('.ak23-drawer-nav-item, .ak23-drawer-btn-login, .ak23-drawer-btn-register, .ak23-drawer-btn-dashboard').forEach(function(link) {
            link.addEventListener('click', function() {
                setTimeout(closeDrawer, 150);
            });
        });
    }

    // Global Cart Badge Synchronizer (Invoked on AJAX Cart Operations)
    window.updateAk23CartCount = function(count) {
        const num = parseInt(count) || 0;
        document.querySelectorAll('.ak23-cart-count').forEach(function(el) {
            el.textContent = num;
        });

        const badges = document.querySelectorAll('.ak23-cart-badge, .ak23-cart-pill, .ak23-drawer-pill-gold');
        const chevrons = document.querySelectorAll('.ak23-drawer-nav-item .ak23-drawer-nav-chevron');
        const cartBtns = document.querySelectorAll('.ak23-cart-btn');

        if (num > 0) {
            badges.forEach(function(b) {
                b.textContent = num;
                b.classList.remove('d-none');
            });
            cartBtns.forEach(function(btn) {
                btn.classList.add('has-items');
            });
        } else {
            badges.forEach(function(b) {
                b.classList.add('d-none');
            });
            cartBtns.forEach(function(btn) {
                btn.classList.remove('has-items');
            });
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAk23Drawer);
    } else {
        initAk23Drawer();
    }
})();
</script>
