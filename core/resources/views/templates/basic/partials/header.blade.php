@php
    $cartLength = cartCount();
    $user = auth()->user();
    $navItems = getHeaderNavItems();
@endphp

<header class="ak23-header" id="header">
    <div class="ak23-container">
        <div class="ak23-header-inner">
            <!-- Brand Logo (Backend Managed from General Settings) -->
            <a class="ak23-brand" href="{{ route('home') }}" aria-label="{{ gs('site_name') }}">
                @php
                    $logoDark = siteLogo('dark');
                @endphp
                <img src="{{ $logoDark }}" alt="{{ gs('site_name') }}" style="height: 32px; max-width: 200px; width: auto; object-fit: contain;">
            </a>

            <!-- Desktop Nav Links (Fully Dynamic Backend Driven) -->
            <ul class="ak23-nav-links d-none d-lg-flex">
                @foreach($navItems as $item)
                    <li>
                        <a href="{{ $item['url'] }}" class="ak23-nav-link {{ $item['active'] ? 'active' : '' }}" @if(isset($item['series'])) data-series="{{ $item['series'] }}" @endif>
                            {{ __($item['title']) }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <!-- Header Right Utilities -->
            <div class="ak23-header-right">
                @guest
                    <a href="{{ route('user.login') }}" class="ak23-login-link">
                        <i class="las la-user"></i>
                        <span>@lang('Login')</span>
                    </a>
                @else
                    <a href="{{ route('user.home') }}" class="ak23-login-link" title="{{ $user->fullname ?: $user->username }}">
                        <i class="las la-user-circle"></i>
                        <span>@lang('My Dashboard')</span>
                    </a>
                @endguest

                <a href="{{ route('cart.index') }}" class="ak23-cart-btn" aria-label="@lang('Shopping Cart')">
                    <span class="ak23-cart-btn-label">@lang('Cart') &middot; <strong class="ak23-cart-count fw-bold">{{ $cartLength }}</strong></span>
                    <i class="las la-arrow-right"></i>
                </a>

                <!-- Mobile Toggle Button -->
                <button class="ak23-mobile-toggle d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#ak23MobileNav" aria-controls="ak23MobileNav" aria-expanded="false" aria-label="@lang('Toggle navigation')">
                    <i class="las la-bars"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Collapse Menu (Dynamic Backend Driven) -->
        <div class="collapse d-lg-none mt-3 pt-3 border-top border-dark" id="ak23MobileNav">
            <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                @foreach($navItems as $item)
                    <li>
                        <a href="{{ $item['url'] }}" class="ak23-nav-link {{ $item['active'] ? 'active' : '' }} py-2 d-block" @if(isset($item['series'])) data-series="{{ $item['series'] }}" @endif>
                            {{ __($item['title']) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</header>
