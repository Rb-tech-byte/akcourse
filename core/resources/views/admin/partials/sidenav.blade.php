@php
    $currentRoute = request()->route() ? request()->route()->getName() : '';

    // Auto-detect if any secondary item is active in each category
    $academySubActive = request()->routeIs('admin.category.*') || request()->routeIs('admin.review.*') || request()->routeIs('admin.comment.*');
    $commerceSubActive = request()->routeIs('admin.deposit.pending') || request()->routeIs('admin.deposit.approved') || request()->routeIs('admin.deposit.successful') || request()->routeIs('admin.deposit.rejected') || request()->routeIs('admin.deposit.initiated') || request()->routeIs('admin.coupon.*') || request()->routeIs('admin.campaign.*') || request()->routeIs('admin.gateway.*');
    $systemSubActive = request()->routeIs('admin.ticket.*') || request()->routeIs('admin.subscriber.*') || request()->routeIs('admin.setting.system') || request()->routeIs('admin.system.*') || request()->routeIs('admin.report.*');

    $isAnySubActive = $academySubActive || $commerceSubActive || $systemSubActive;
@endphp

<div class="sidebar bg--dark ak23-sidebar">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner ak23-sidebar-inner">
        <!-- Logo & Brand Header -->
        <div class="sidebar__logo p-0 border-0">
            <div class="ak23-admin-logo-box">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none flex-grow-1">
                    <img src="{{ siteLogo('dark') }}" alt="{{ gs('site_name') }}" class="m23-sidebar-brand-img" style="height: 32px; max-width: 165px; width: auto; object-fit: contain;">
                </a>
                <!-- Sleek Core/All View Toggle Icon Button -->
                <button type="button" class="ak23-view-toggle-btn {{ $isAnySubActive ? 'all-active' : '' }}" id="ak23GlobalViewToggle" title="Toggle Core View (AKCOURSE.png) / All Components">
                    <i class="las la-sliders-h"></i>
                </button>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="sidebar__menu-wrapper ak23-menu-scroller">
            <ul class="sidebar__menu ak23-menu-list">

                <!-- ==================== OVERVIEW ==================== -->
                <li class="sidebar__menu-header ak23-menu-header">
                    <span>OVERVIEW</span>
                </li>
                
                <li class="sidebar-menu-item {{ request()->has('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link ak23-nav-item">
                        <i class="menu-icon las la-th-large"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                
                <li class="sidebar-menu-item {{ request()->routeIs('admin.dashboard') && !request()->has('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link ak23-nav-item">
                        <i class="menu-icon las la-chart-bar"></i>
                        <span class="menu-title">Analytics</span>
                    </a>
                </li>

                <!-- ==================== COURSE CATALOG ==================== -->
                <li class="sidebar__menu-header ak23-menu-header">
                    <span>COURSE CATALOG</span>
                </li>

                <li class="sidebar-menu-item {{ request()->routeIs('admin.product.all') ? 'active' : '' }}">
                    <a href="{{ route('admin.product.all') }}" class="nav-link ak23-nav-item">
                        <i class="menu-icon las la-boxes"></i>
                        <span class="menu-title">All Courses</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ request()->routeIs('admin.product.approved') ? 'active' : '' }}">
                    <a href="{{ route('admin.product.approved') }}" class="nav-link ak23-nav-item">
                        <i class="menu-icon las la-check-circle"></i>
                        <span class="menu-title">Published Courses</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ request()->routeIs('admin.product.pending') ? 'active' : '' }}">
                    <a href="{{ route('admin.product.pending') }}" class="nav-link ak23-nav-item">
                        <i class="menu-icon las la-hourglass-half"></i>
                        <span class="menu-title">Pending Review</span>
                        @if(($pendingProductsCount ?? 0) > 0)
                            <span class="ak23-badge-pill ak23-badge-amber ms-auto">
                                {{ $pendingProductsCount }}
                            </span>
                        @endif
                    </a>
                </li>

                <li class="sidebar-menu-item {{ request()->routeIs('admin.category.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.category.index') }}" class="nav-link ak23-nav-item">
                        <i class="menu-icon las la-layer-group"></i>
                        <span class="menu-title">Course Categories</span>
                    </a>
                </li>

                <!-- Reviews & Comments (Dropdown) -->
                <li class="sidebar-menu-item ak23-dropdown-item {{ request()->routeIs('admin.review.*') || request()->routeIs('admin.comment.*') ? 'open active-dropdown' : '' }}">
                    <a href="javascript:void(0)" class="nav-link ak23-nav-item ak23-dropdown-trigger">
                        <i class="menu-icon las la-comments"></i>
                        <span class="menu-title">Reviews & Comments</span>
                        <i class="las la-angle-down ms-auto ak23-sub-chevron"></i>
                    </a>
                    <div class="ak23-dropdown-submenu {{ request()->routeIs('admin.review.*') || request()->routeIs('admin.comment.*') ? 'show' : '' }}">
                        <ul>
                            <li class="{{ request()->routeIs('admin.review.index') ? 'active-sub' : '' }}">
                                <a href="{{ route('admin.review.index') }}">Reviews</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.comment.index') ? 'active-sub' : '' }}">
                                <a href="{{ route('admin.comment.index') }}">Comments</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- ==================== COMMERCE ==================== -->
                <li class="sidebar__menu-header ak23-menu-header d-flex justify-content-between align-items-center">
                    <span>COMMERCE</span>
                    <button type="button" class="ak23-header-more-btn {{ $commerceSubActive ? 'open' : '' }}" data-target="#ak23-commerce-sub" title="More Commerce tools">
                        <i class="las la-ellipsis-h"></i>
                    </button>
                </li>

                <li class="sidebar-menu-item {{ request()->routeIs('admin.sell.items') ? 'active' : '' }}">
                    <a href="{{ route('admin.sell.items') }}" class="nav-link ak23-nav-item">
                        <i class="menu-icon las la-box"></i>
                        <span class="menu-title">Orders</span>
                        <span class="ak23-badge-pill ak23-badge-green ms-auto">
                            {{ $paidOrdersCount ?? 0 }}
                        </span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ request()->routeIs('admin.deposit.list') && !request()->routeIs('admin.deposit.pending') ? 'active' : '' }}">
                    <a href="{{ route('admin.deposit.list') }}" class="nav-link ak23-nav-item">
                        <i class="menu-icon las la-credit-card"></i>
                        <span class="menu-title">Payments</span>
                        <span class="ak23-badge-pill ak23-badge-amber ms-auto" style="font-size: 10px; font-weight: 600;">Selcom</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.all') }}" class="nav-link ak23-nav-item">
                        <i class="menu-icon las la-user-graduate"></i>
                        <span class="menu-title">Students</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ request()->routeIs('admin.sell.refunded') ? 'active' : '' }}">
                    <a href="{{ route('admin.sell.refunded') }}" class="nav-link ak23-nav-item">
                        <i class="menu-icon las la-check-circle"></i>
                        <span class="menu-title">Entitlements</span>
                        @if(($refundedItemsCount ?? 0) > 0)
                            <span class="ak23-badge-pill ak23-badge-amber ms-auto">
                                {{ $refundedItemsCount }}
                            </span>
                        @endif
                    </a>
                </li>

                <!-- COMMERCE Secondary Components (Show only when needed) -->
                <div class="ak23-secondary-group {{ $commerceSubActive ? 'show' : '' }}" id="ak23-commerce-sub">
                    <!-- Gateways & Deposits (Dropdown) -->
                    <li class="sidebar-menu-item ak23-dropdown-item {{ (request()->routeIs('admin.deposit.*') && !request()->routeIs('admin.deposit.list')) || request()->routeIs('admin.gateway.*') ? 'open active-dropdown' : '' }}">
                        <a href="javascript:void(0)" class="nav-link ak23-nav-item ak23-dropdown-trigger">
                            <i class="menu-icon las la-file-invoice-dollar"></i>
                            <span class="menu-title">Gateways & Deposits</span>
                            <i class="las la-angle-down ms-auto ak23-sub-chevron"></i>
                        </a>
                        <div class="ak23-dropdown-submenu {{ (request()->routeIs('admin.deposit.*') && !request()->routeIs('admin.deposit.list')) || request()->routeIs('admin.gateway.*') ? 'show' : '' }}">
                            <ul>
                                <li class="{{ request()->routeIs('admin.gateway.*') ? 'active-sub' : '' }}">
                                    <a href="{{ route('admin.gateway.automatic.index') }}">Payment Gateways</a>
                                </li>
                                <li class="{{ request()->routeIs('admin.deposit.pending') ? 'active-sub' : '' }}">
                                    <a href="{{ route('admin.deposit.pending') }}">Pending Payments</a>
                                </li>
                                <li class="{{ request()->routeIs('admin.deposit.approved') ? 'active-sub' : '' }}">
                                    <a href="{{ route('admin.deposit.approved') }}">Approved Payments</a>
                                </li>
                                <li class="{{ request()->routeIs('admin.deposit.successful') ? 'active-sub' : '' }}">
                                    <a href="{{ route('admin.deposit.successful') }}">Successful Payments</a>
                                </li>
                                <li class="{{ request()->routeIs('admin.deposit.rejected') ? 'active-sub' : '' }}">
                                    <a href="{{ route('admin.deposit.rejected') }}">Rejected Payments</a>
                                </li>
                                <li class="{{ request()->routeIs('admin.deposit.initiated') ? 'active-sub' : '' }}">
                                    <a href="{{ route('admin.deposit.initiated') }}">Initiated Payments</a>
                                </li>
                                <li class="{{ request()->routeIs('admin.deposit.list') ? 'active-sub' : '' }}">
                                    <a href="{{ route('admin.deposit.list') }}">All Payments</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Coupons -->
                    <li class="sidebar-menu-item {{ request()->routeIs('admin.coupon.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.coupon.index') }}" class="nav-link ak23-nav-item">
                            <i class="menu-icon las la-percentage"></i>
                            <span class="menu-title">Coupons</span>
                        </a>
                    </li>

                    <!-- Campaigns -->
                    <li class="sidebar-menu-item {{ request()->routeIs('admin.campaign.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.campaign.index') }}" class="nav-link ak23-nav-item">
                            <i class="menu-icon las la-bullseye"></i>
                            <span class="menu-title">Campaigns</span>
                        </a>
                    </li>
                </div>

                <!-- ==================== SYSTEM ==================== -->
                <li class="sidebar__menu-header ak23-menu-header">
                    <span>SYSTEM</span>
                </li>

                <!-- 1. System Setting (Central Hub for General Settings, REST/RPC API & Webhooks) -->
                <li class="sidebar-menu-item {{ request()->routeIs('admin.setting.*') || request()->routeIs('admin.system.setting') ? 'active' : '' }}">
                    <a href="{{ route('admin.setting.system') }}" class="nav-link ak23-nav-item">
                        <i class="menu-icon las la-sliders-h"></i>
                        <span class="menu-title">System Setting</span>
                    </a>
                </li>

                <!-- 2. Support Ticket (Dropdown) -->
                <li class="sidebar-menu-item ak23-dropdown-item {{ request()->routeIs('admin.ticket.*') ? 'open active-dropdown' : '' }}">
                    <a href="javascript:void(0)" class="nav-link ak23-nav-item ak23-dropdown-trigger">
                        <i class="menu-icon las la-headset"></i>
                        <span class="menu-title">Support Ticket</span>
                        @if(($pendingTicketCount ?? 0) > 0)
                            <span class="ak23-badge-pill ak23-badge-amber ms-auto me-1">
                                {{ $pendingTicketCount }}
                            </span>
                        @endif
                        <i class="las la-angle-down ms-auto ak23-sub-chevron"></i>
                    </a>
                    <div class="ak23-dropdown-submenu {{ request()->routeIs('admin.ticket.*') ? 'show' : '' }}">
                        <ul>
                            <li class="{{ request()->routeIs('admin.ticket.pending') ? 'active-sub' : '' }}">
                                <a href="{{ route('admin.ticket.pending') }}" class="d-flex align-items-center justify-content-between">
                                    <span>Pending Ticket</span>
                                    @if(($pendingTicketCount ?? 0) > 0)
                                        <span class="badge bg-warning-subtle text-warning px-1-5 py-0-5 rounded-pill" style="font-size: 10px;">{{ $pendingTicketCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('admin.ticket.closed') ? 'active-sub' : '' }}">
                                <a href="{{ route('admin.ticket.closed') }}">Closed Ticket</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.ticket.answered') ? 'active-sub' : '' }}">
                                <a href="{{ route('admin.ticket.answered') }}">Answered Ticket</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.ticket.index') ? 'active-sub' : '' }}">
                                <a href="{{ route('admin.ticket.index') }}">All Ticket</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- 3. Subscribers -->
                <li class="sidebar-menu-item {{ request()->routeIs('admin.subscriber.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.subscriber.index') }}" class="nav-link ak23-nav-item">
                        <i class="menu-icon las la-envelope-open-text"></i>
                        <span class="menu-title">Subscribers</span>
                    </a>
                </li>

                <!-- 4. Extra (Dropdown) -->
                <li class="sidebar-menu-item ak23-dropdown-item {{ request()->routeIs('admin.system.*') ? 'open active-dropdown' : '' }}">
                    <a href="javascript:void(0)" class="nav-link ak23-nav-item ak23-dropdown-trigger">
                        <i class="menu-icon las la-server"></i>
                        <span class="menu-title">Extra</span>
                        <i class="las la-angle-down ms-auto ak23-sub-chevron"></i>
                    </a>
                    <div class="ak23-dropdown-submenu {{ request()->routeIs('admin.system.*') ? 'show' : '' }}">
                        <ul>
                            <li class="{{ request()->routeIs('admin.system.info') ? 'active-sub' : '' }}">
                                <a href="{{ route('admin.system.info') }}">Application</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.system.server.info') ? 'active-sub' : '' }}">
                                <a href="{{ route('admin.system.server.info') }}">Server</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.system.optimize') ? 'active-sub' : '' }}">
                                <a href="{{ route('admin.system.optimize') }}">Cache</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.system.update') ? 'active-sub' : '' }}">
                                <a href="{{ route('admin.system.update') }}">Update</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- 5. Report (Dropdown) -->
                <li class="sidebar-menu-item ak23-dropdown-item {{ request()->routeIs('admin.report.*') ? 'open active-dropdown' : '' }}">
                    <a href="javascript:void(0)" class="nav-link ak23-nav-item ak23-dropdown-trigger">
                        <i class="menu-icon las la-file-invoice"></i>
                        <span class="menu-title">Report</span>
                        <i class="las la-angle-down ms-auto ak23-sub-chevron"></i>
                    </a>
                    <div class="ak23-dropdown-submenu {{ request()->routeIs('admin.report.*') ? 'show' : '' }}">
                        <ul>
                            <li class="{{ request()->routeIs('admin.report.transaction') ? 'active-sub' : '' }}">
                                <a href="{{ route('admin.report.transaction') }}">Transaction History</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.report.login.history') ? 'active-sub' : '' }}">
                                <a href="{{ route('admin.report.login.history') }}">Login History</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.report.notification.history') ? 'active-sub' : '' }}">
                                <a href="{{ route('admin.report.notification.history') }}">Notification History</a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>

        <!-- Pinned Bottom Profile Box -->
        <div class="ak23-admin-profile-box">
            <div class="ak23-admin-profile-avatar"><span>DA</span></div>
            <div class="ak23-admin-profile-info">
                <div class="ak23-admin-profile-name">Super Admin</div>
                <div class="ak23-admin-profile-role">Administrator</div>
            </div>
            <a href="{{ route('admin.logout') }}" class="ak23-admin-profile-arrow" title="Logout">
                <i class="las la-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
<!-- sidebar end -->

<style>
/* ==========================================================================
   AK23 SIDEBAR STYLING - EXACT 1:1 ALIGNMENT WITH AKCOURSE.png
   ========================================================================== */

/* Main Sidebar Container */
.sidebar.ak23-sidebar {
    background-color: #0C0E14 !important;
    border-right: 1px solid rgba(255, 255, 255, 0.06) !important;
    width: 240px !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    bottom: 0 !important;
    z-index: 999 !important;
    overflow: hidden !important;
}

.ak23-sidebar-inner {
    display: flex !important;
    flex-direction: column !important;
    height: 100vh !important;
    overflow: hidden !important;
}

/* Brand Header */
.ak23-admin-logo-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 16px 12px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.m23-sidebar-brand-img {
    height: 32px;
    max-width: 165px;
    width: auto;
    object-fit: contain;
    display: block;
}

.ak23-admin-brand-title {
    color: #FFFFFF;
    font-size: 15px;
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.01em;
}

.ak23-admin-brand-subtitle {
    color: #556075;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    margin-top: 1px;
}

/* Global View Toggle Button (Subtle & Compact) */
.ak23-view-toggle-btn {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(255, 255, 255, 0.03);
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.15s ease;
}

.ak23-view-toggle-btn:hover {
    color: #FFFFFF;
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(255, 255, 255, 0.16);
}

.ak23-view-toggle-btn.all-active {
    background: rgba(251, 175, 67, 0.18);
    color: #FBAF43;
    border-color: rgba(251, 175, 67, 0.35);
}

/* Scroller Area */
.ak23-menu-scroller {
    flex: 1 1 auto !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    padding: 6px 6px 12px !important;
}

.ak23-menu-scroller::-webkit-scrollbar {
    width: 3px;
}
.ak23-menu-scroller::-webkit-scrollbar-track {
    background: transparent;
}
.ak23-menu-scroller::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}
.ak23-menu-scroller::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.25);
}

.ak23-menu-list {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
}

/* Category Headers */
.ak23-menu-header {
    font-size: 9.5px !important;
    font-weight: 800 !important;
    letter-spacing: 0.12em !important;
    text-transform: uppercase !important;
    color: #475569 !important;
    padding: 8px 10px 2px 10px !important;
    margin: 0 !important;
}

.ak23-header-more-btn {
    border: none;
    background: transparent;
    color: #475569;
    font-size: 13px;
    padding: 0 4px;
    cursor: pointer;
    line-height: 1;
    border-radius: 4px;
    transition: all 0.15s ease;
}

.ak23-header-more-btn:hover {
    color: #94A3B8;
    background: rgba(255, 255, 255, 0.05);
}

.ak23-header-more-btn.open {
    color: #FBAF43;
}

/* Standard Nav Item */
.sidebar__menu .sidebar-menu-item {
    margin-bottom: 1px !important;
}

.sidebar__menu .sidebar-menu-item > a.ak23-nav-item {
    display: flex !important;
    align-items: center !important;
    gap: 11px !important;
    padding: 5.5px 10px !important;
    margin: 1px 2px !important;
    border-radius: 7px !important;
    font-size: 12.5px !important;
    font-weight: 500 !important;
    color: #94A3B8 !important;
    text-decoration: none !important;
    background-color: transparent !important;
    border: 1px solid transparent !important;
    transition: all 0.15s ease !important;
}

.sidebar__menu .sidebar-menu-item > a.ak23-nav-item i.menu-icon {
    font-size: 16px !important;
    width: 18px !important;
    text-align: center !important;
    color: #64748B !important;
    margin-right: 0 !important;
    transition: color 0.15s ease !important;
}

.sidebar__menu .sidebar-menu-item > a.ak23-nav-item:hover {
    background-color: rgba(255, 255, 255, 0.04) !important;
    color: #FFFFFF !important;
}
.sidebar__menu .sidebar-menu-item > a.ak23-nav-item:hover i.menu-icon {
    color: #FFFFFF !important;
}

/* OVERRIDE ACTIVE WITH MAARIFA23 WARM GOLD (#FBAF43) */
.sidebar__menu .sidebar-menu-item.active > a.ak23-nav-item,
.sidebar__menu .sidebar-menu-item > a.ak23-nav-item.active {
    background: linear-gradient(90deg, rgba(251, 175, 67, 0.18) 0%, rgba(251, 175, 67, 0.05) 100%) !important;
    color: #FBAF43 !important;
    border: 1px solid rgba(251, 175, 67, 0.32) !important;
    font-weight: 700 !important;
    box-shadow: 0 0 14px rgba(251, 175, 67, 0.12) !important;
}

.sidebar__menu .sidebar-menu-item.active > a.ak23-nav-item i.menu-icon,
.sidebar__menu .sidebar-menu-item > a.ak23-nav-item.active i.menu-icon {
    color: #FBAF43 !important;
}

/* Badges (Pills) */
.ak23-badge-pill {
    font-size: 11px !important;
    font-weight: 700 !important;
    padding: 1px 7px !important;
    border-radius: 9999px !important;
    line-height: 1.35 !important;
}

.ak23-badge-amber {
    background: rgba(251, 175, 67, 0.15) !important;
    color: #FBAF43 !important;
    border: 1px solid rgba(251, 175, 67, 0.3) !important;
}

.ak23-badge-green {
    background: rgba(251, 175, 67, 0.15) !important;
    color: #FBAF43 !important;
    border: 1px solid rgba(251, 175, 67, 0.3) !important;
}

/* Secondary Container */
.ak23-secondary-group {
    display: none;
    padding-left: 2px;
}

.ak23-secondary-group.show {
    display: block;
}

/* Dropdown Accordion */
.ak23-sub-chevron {
    font-size: 10px;
    color: #64748B;
    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.ak23-dropdown-item.open > .ak23-nav-item .ak23-sub-chevron {
    transform: rotate(180deg);
}

.sidebar__menu .sidebar-menu-item.active-dropdown > a.ak23-nav-item {
    color: #FBAF43 !important;
    background: rgba(251, 175, 67, 0.08) !important;
    border: 1px solid rgba(251, 175, 67, 0.22) !important;
}

.sidebar__menu .sidebar-menu-item.active-dropdown > a.ak23-nav-item i.menu-icon {
    color: #FBAF43 !important;
}

.ak23-dropdown-submenu {
    display: none;
    margin: 3px 2px 5px 18px;
    padding-left: 10px;
    border-left: 1.5px solid rgba(251, 175, 67, 0.25);
}

.ak23-dropdown-submenu.show {
    display: block;
}

.ak23-dropdown-submenu ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.ak23-dropdown-submenu ul li a {
    display: block;
    padding: 5px 9px;
    font-size: 12px;
    color: #94A3B8;
    text-decoration: none !important;
    border-radius: 6px;
    transition: all 0.15s ease;
}

.ak23-dropdown-submenu ul li a:hover {
    color: #FFFFFF !important;
    background: rgba(255, 255, 255, 0.05) !important;
}

.ak23-dropdown-submenu ul li.active-sub a {
    color: #FBAF43 !important;
    font-weight: 700 !important;
    background: rgba(251, 175, 67, 0.14) !important;
    box-shadow: inset 2px 0 0 #FBAF43;
}

/* Bottom Profile Box */
.ak23-admin-profile-box {
    margin-top: auto !important;
    border-top: 1px solid rgba(255, 255, 255, 0.06) !important;
    padding: 12px 14px !important;
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    background: #121316 !important;
    flex-shrink: 0 !important;
}

.ak23-admin-profile-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #252015;
    border: 1px solid rgba(251, 175, 67, 0.4);
    color: #FBAF43;
    font-weight: 800;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.ak23-admin-profile-info {
    flex: 1;
    overflow: hidden;
}

.ak23-admin-profile-name {
    color: #FFFFFF;
    font-size: 12.5px;
    font-weight: 700;
    white-space: nowrap;
    line-height: 1.2;
}

.ak23-admin-profile-role {
    color: #64748B;
    font-size: 10.5px;
    margin-top: 1px;
}

.ak23-admin-profile-arrow {
    color: #64748B;
    font-size: 15px;
    text-decoration: none !important;
    transition: color 0.15s ease;
}

.ak23-admin-profile-arrow:hover {
    color: #FFFFFF;
}
</style>

@push('script')
<script>
$(document).ready(function() {
    var hasActiveSub = {{ $isAnySubActive ? 'true' : 'false' }};
    var savedMode = localStorage.getItem('ak23_nav_mode');

    if (!hasActiveSub && savedMode === 'all') {
        $('.ak23-secondary-group').addClass('show');
        $('.ak23-header-more-btn').addClass('open');
        $('#ak23GlobalViewToggle').addClass('all-active');
    }

    // Global View Toggle (Core vs All)
    $('#ak23GlobalViewToggle').on('click', function() {
        var isAll = $(this).hasClass('all-active');
        if (isAll) {
            $('.ak23-secondary-group').slideUp(180).removeClass('show');
            $('.ak23-header-more-btn').removeClass('open');
            $(this).removeClass('all-active');
            localStorage.setItem('ak23_nav_mode', 'core');
        } else {
            $('.ak23-secondary-group').slideDown(180).addClass('show');
            $('.ak23-header-more-btn').addClass('open');
            $(this).addClass('all-active');
            localStorage.setItem('ak23_nav_mode', 'all');
        }
    });

    // Per-section Header More Button
    $('.ak23-header-more-btn').on('click', function(e) {
        e.preventDefault();
        var target = $(this).data('target');
        var $targetEl = $(target);
        var isOpen = $(this).hasClass('open');

        if (isOpen) {
            $targetEl.slideUp(160).removeClass('show');
            $(this).removeClass('open');
        } else {
            $targetEl.slideDown(160).addClass('show');
            $(this).addClass('open');
        }
    });

    // Toggle dropdown accordions
    $('.ak23-dropdown-trigger').off('click').on('click', function(e) {
        e.preventDefault();
        var $item = $(this).closest('.ak23-dropdown-item');
        var $submenu = $item.find('.ak23-dropdown-submenu');

        if ($item.hasClass('open')) {
            $submenu.stop(true, true).slideUp(160, function() {
                $item.removeClass('open');
            });
        } else {
            $submenu.stop(true, true).slideDown(160, function() {
                $item.addClass('open');
            });
        }
    });

    // Auto-scroll to active nav item if off-screen on load
    setTimeout(function() {
        var $active = $('.sidebar-menu-item.active, .sidebar-menu-item.open, .active-sub').first();
        if ($active.length) {
            var scroller = document.querySelector('.ak23-menu-scroller');
            if (scroller) {
                var scrollerRect = scroller.getBoundingClientRect();
                var activeRect = $active[0].getBoundingClientRect();
                if (activeRect.bottom > scrollerRect.bottom || activeRect.top < scrollerRect.top) {
                    $active[0].scrollIntoView({ block: 'center', behavior: 'smooth' });
                }
            }
        }
    }, 150);
});
</script>
@endpush
