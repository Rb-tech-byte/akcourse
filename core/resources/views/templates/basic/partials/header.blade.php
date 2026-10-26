@php
    $cartLength = (int)cartCount();
    $user = auth()->user();
    $navItems = getHeaderNavItems();
    $logoDark = siteLogo('dark');
    $logoLight = siteLogo();
    $languages = \App\Models\Language::all();
    $currentLocale = session('lang') ?: config('app.locale');
    $activeLang = $languages->where('code', $currentLocale)->first() ?: $languages->first();
@endphp

<style>
/* Defensive Header Layout Rules (Zero Vertical Wrapping Guarantee) */
.ak23-header-inner { display: flex !important; align-items: center !important; justify-content: space-between !important; flex-wrap: nowrap !important; }
.ak23-header-right { display: flex !important; align-items: center !important; gap: 8px !important; flex-shrink: 0 !important; }
.ak23-login-link { white-space: nowrap !important; }
.ak23-cart-btn { white-space: nowrap !important; }
@media (max-width: 767.98px) {
  .ak23-header-right .ak23-login-link { display: none !important; }
  .ak23-cart-btn { width: 40px !important; height: 40px !important; padding: 0 !important; border-radius: 50% !important; justify-content: center !important; }
  .ak23-cart-btn-label, .ak23-cart-pill, .ak23-cart-btn .la-arrow-right { display: none !important; }
  .ak23-cart-icon-wrap { font-size: 20px !important; }
}
@media (max-width: 575.98px) {
  .ak23-brand img, .ak23-brand-img { height: 26px !important; max-width: 140px !important; }
}

/* Navigation Dropdowns */
.ak23-nav-item { position: relative; list-style: none; }
.ak23-has-dropdown { position: relative; }
.ak23-dropdown-arrow { font-size: 11px; margin-left: 4px; transition: transform 0.2s ease; opacity: 0.7; }
.ak23-has-dropdown:hover .ak23-dropdown-arrow { transform: rotate(180deg); opacity: 1; }
.ak23-dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  min-width: 220px;
  background: #121319;
  border: 1px solid rgba(255, 255, 255, 0.12);
  border-radius: 12px;
  padding: 8px 0;
  margin: 6px 0 0;
  list-style: none;
  box-shadow: 0 16px 36px rgba(0, 0, 0, 0.85);
  opacity: 0;
  visibility: hidden;
  transform: translateY(6px);
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
  z-index: 1050;
  pointer-events: none;
}
.ak23-dropdown-menu::before {
  content: '';
  position: absolute;
  top: -12px;
  left: 0;
  right: 0;
  height: 12px;
}
.ak23-has-dropdown:hover .ak23-dropdown-menu {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
  pointer-events: auto;
}
.ak23-dropdown-link {
  display: block;
  padding: 8px 18px;
  color: #CBD5E1;
  font-size: 13px;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.15s ease;
  white-space: nowrap;
}
.ak23-dropdown-link:hover,
.ak23-dropdown-link.active {
  color: #FBAF43;
  background: rgba(251, 175, 67, 0.1);
  padding-left: 22px;
}

/* Mobile Drawer Sub-Links */
.ak23-drawer-sub-pills {
  display: flex;
  flex-direction: column;
  gap: 3px;
  padding: 4px 12px 10px 58px;
}
.ak23-drawer-sub-link {
  color: #94A3B8;
  font-size: 12px;
  font-weight: 500;
  text-decoration: none !important;
  padding: 4px 10px;
  border-radius: 6px;
  background: rgba(255, 255, 255, 0.03);
  transition: all 0.15s ease;
  display: inline-flex;
  align-items: center;
}
.ak23-drawer-sub-link:hover,
.ak23-drawer-sub-link.active {
  color: #FBAF43;
  background: rgba(251, 175, 67, 0.1);
}
</style>

<!-- =========================================================================
     AK23 MAIN NAVBAR (RESPONSIVE & MOBILE-FIRST)
     ========================================================================= -->
<header class="ak23-header" id="header">
    <div class="ak23-container">
        <div class="ak23-header-inner">
            <!-- Brand Logo -->
            <a class="ak23-brand" href="{{ route('home') }}" aria-label="{{ gs('site_name') }}">
                <img src="{{ $logoDark }}" alt="{{ gs('site_name') }}" class="ak23-brand-img ak23-logo-dark">
                <img src="{{ $logoLight }}" alt="{{ gs('site_name') }}" class="ak23-brand-img ak23-logo-light">
            </a>

            <!-- Desktop Navigation Links (>= 992px) -->
            <ul class="ak23-nav-links d-none d-lg-flex mb-0">
                @foreach($navItems as $item)
                    @if(!empty($item['sub_items']))
                        <li class="ak23-nav-item ak23-has-dropdown">
                            <a href="{{ $item['url'] }}" class="ak23-nav-link {{ $item['active'] ? 'active' : '' }}" @if(isset($item['series'])) data-series="{{ $item['series'] }}" @endif>
                                <span>{{ __($item['title']) }}</span>
                                <i class="las la-angle-down ak23-dropdown-arrow"></i>
                            </a>
                            <ul class="ak23-dropdown-menu">
                                <li>
                                    <a href="{{ $item['url'] }}" class="ak23-dropdown-link fw-bold">
                                        @lang('All') {{ __($item['title']) }}
                                    </a>
                                </li>
                                @foreach($item['sub_items'] as $subItem)
                                    <li>
                                        <a href="{{ $subItem['url'] }}" class="ak23-dropdown-link {{ $subItem['active'] ? 'active' : '' }}">
                                            {{ __($subItem['title']) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="ak23-nav-item">
                            <a href="{{ $item['url'] }}" class="ak23-nav-link {{ $item['active'] ? 'active' : '' }}" @if(isset($item['series'])) data-series="{{ $item['series'] }}" @endif>
                                {{ __($item['title']) }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>

            <!-- Header Right Action Group -->
            <div class="ak23-header-right">
                <!-- Theme Switcher Button -->
                <button type="button" class="ak23-theme-toggle" id="ak23ThemeToggle" aria-label="@lang('Toggle Theme')" title="@lang('Switch to Light Mode')">
                    <i class="las la-sun ak23-icon-sun"></i>
                    <i class="las la-moon ak23-icon-moon"></i>
                </button>

                <!-- Language Switcher Dropdown (Desktop >= 480px) -->
                @if($languages->count() > 1)
                    <div class="ak23-lang-wrap d-none d-sm-block">
                        <button type="button" class="ak23-lang-toggle" id="ak23LangBtn" aria-label="@lang('Language')" title="@lang('Language')">
                            <img src="{{ getImage(getFilePath('language') . '/' . @$activeLang->image, getFileSize('language')) }}" alt="{{ @$activeLang->name }}" class="ak23-lang-flag">
                            <span class="ak23-lang-code">{{ strtoupper($currentLocale) }}</span>
                            <i class="las la-angle-down ak23-lang-chevron"></i>
                        </button>
                        <div class="ak23-lang-dropdown" id="ak23LangDropdown">
                            @foreach($languages as $langItem)
                                <a href="{{ route('lang', $langItem->code) }}" class="ak23-lang-item {{ $currentLocale == $langItem->code ? 'active' : '' }}">
                                    <img src="{{ getImage(getFilePath('language') . '/' . $langItem->image, getFileSize('language')) }}" alt="{{ $langItem->name }}" class="ak23-lang-flag">
                                    <span class="ak23-lang-label">{{ $langItem->name }}</span>
                                    @if($currentLocale == $langItem->code)
                                        <i class="las la-check ak23-lang-check"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

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
            <img src="{{ $logoDark }}" alt="{{ gs('site_name') }}" class="ak23-logo-dark" style="height: 28px; width: auto; object-fit: contain;">
            <img src="{{ $logoLight }}" alt="{{ gs('site_name') }}" class="ak23-logo-light" style="height: 28px; width: auto; object-fit: contain;">
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
            <i class="las la-graduation-cap me-1"></i> @lang('STUDIO COURSES & CATEGORIES')
        </div>
        <div class="ak23-drawer-nav-list">
            @foreach($navItems as $item)
                @php
                    $seriesKey = $item['series'] ?? '';
                    $iconClass = match($seriesKey) {
                        'home' => 'la-home',
                        'all' => 'la-graduation-cap',
                        'vocal-mixing' => 'la-microphone-alt',
                        'audio-editing-software' => 'la-sliders-h',
                        'beat-making' => 'la-drum',
                        default => 'la-compact-disc'
                    };
                    $hasSubItems = !empty($item['sub_items']);
                    $badgeText = $hasSubItems 
                        ? count($item['sub_items']) . ' ' . __('Subcategories')
                        : ($item['type'] === 'catalog' ? __('Full Catalog') : ($item['type'] === 'home' ? __('Home Page') : __('Category')));
                @endphp
                <div class="ak23-drawer-nav-group mb-1">
                    <a href="{{ $item['url'] }}" class="ak23-drawer-nav-item {{ $item['active'] ? 'active' : '' }}">
                        <div class="ak23-drawer-nav-icon">
                            <i class="las {{ $iconClass }}"></i>
                        </div>
                        <div class="ak23-drawer-nav-text">
                            <span class="ak23-drawer-nav-title">{{ __($item['title']) }}</span>
                            <span class="ak23-drawer-nav-sub">{{ $badgeText }}</span>
                        </div>
                        <i class="las {{ $hasSubItems ? 'la-angle-down' : 'la-angle-right' }} ak23-drawer-nav-chevron"></i>
                    </a>
                    @if($hasSubItems)
                        <div class="ak23-drawer-sub-pills">
                            @foreach($item['sub_items'] as $subItem)
                                <a href="{{ $subItem['url'] }}" class="ak23-drawer-sub-link {{ $subItem['active'] ? 'active' : '' }}">
                                    <i class="las la-angle-right me-1 opacity-50"></i>{{ __($subItem['title']) }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
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

        <!-- 4. Drawer Settings & Preferences (Theme & Language) -->
        <div class="ak23-drawer-section-label mt-4">
            <i class="las la-sliders-h me-1"></i> @lang('PREFERENCES & DISPLAY')
        </div>
        <div class="ak23-drawer-settings-box">
            <div class="ak23-drawer-setting-item">
                <span class="ak23-drawer-setting-text">
                    <i class="las la-adjust me-2 text-warning fs-16"></i> @lang('Theme')
                </span>
                <div class="ak23-drawer-theme-pills">
                    <button type="button" class="ak23-drawer-theme-pill" data-theme-val="dark">
                        <i class="las la-moon"></i> <span>@lang('Dark')</span>
                    </button>
                    <button type="button" class="ak23-drawer-theme-pill" data-theme-val="light">
                        <i class="las la-sun"></i> <span>@lang('Light')</span>
                    </button>
                </div>
            </div>

            @if($languages->count() > 1)
                <div class="ak23-drawer-setting-item mt-3 pt-3 border-top-subtle">
                    <span class="ak23-drawer-setting-text">
                        <i class="las la-globe me-2 text-warning fs-16"></i> @lang('Language')
                    </span>
                    <div class="ak23-drawer-lang-pills">
                        @foreach($languages as $langItem)
                            <a href="{{ route('lang', $langItem->code) }}" class="ak23-drawer-lang-pill {{ $currentLocale == $langItem->code ? 'active' : '' }}">
                                <img src="{{ getImage(getFilePath('language') . '/' . $langItem->image, getFileSize('language')) }}" alt="{{ $langItem->name }}" class="ak23-drawer-flag">
                                <span>{{ $langItem->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
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
     DRAWER & THEME INTERACTIVITY JAVASCRIPT
     ========================================================================= -->
<script>
(function() {
    function initAk23Theme() {
        const currentTheme = localStorage.getItem('ak23_theme') || 'dark';
        applyTheme(currentTheme);

        function applyTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            if (theme === 'light') {
                document.documentElement.classList.add('theme-light');
                document.body.classList.add('theme-light');
            } else {
                document.documentElement.classList.remove('theme-light');
                document.body.classList.remove('theme-light');
            }
            localStorage.setItem('ak23_theme', theme);

            const themeBtn = document.getElementById('ak23ThemeToggle');
            if (themeBtn) {
                themeBtn.setAttribute('title', theme === 'light' ? "@lang('Switch to Dark Mode')" : "@lang('Switch to Light Mode')");
            }

            // Sync drawer theme pills
            document.querySelectorAll('.ak23-drawer-theme-pill').forEach(function(pill) {
                pill.classList.toggle('active', pill.getAttribute('data-theme-val') === theme);
            });
        }

        // Header theme toggle button click
        const themeBtn = document.getElementById('ak23ThemeToggle');
        if (themeBtn) {
            themeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const active = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
                applyTheme(active);
            });
        }

        // Drawer theme pills click
        document.querySelectorAll('.ak23-drawer-theme-pill').forEach(function(pill) {
            pill.addEventListener('click', function(e) {
                e.preventDefault();
                const targetTheme = this.getAttribute('data-theme-val');
                applyTheme(targetTheme);
            });
        });

        // Desktop language dropdown toggle
        const langWrap = document.querySelector('.ak23-lang-wrap');
        const langBtn  = document.getElementById('ak23LangBtn');
        const langDrop = document.getElementById('ak23LangDropdown');

        if (langBtn && langDrop && langWrap) {
            langBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                langWrap.classList.toggle('open');
                langDrop.classList.toggle('show');
            });

            document.addEventListener('click', function(e) {
                if (!langWrap.contains(e.target)) {
                    langWrap.classList.remove('open');
                    langDrop.classList.remove('show');
                }
            });
        }
    }

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
        document.addEventListener('DOMContentLoaded', function() {
            initAk23Drawer();
            initAk23Theme();
        });
    } else {
        initAk23Drawer();
        initAk23Theme();
    }
})();
</script>
