@extends($activeTemplate . 'layouts.portal')

@section('content')

<!-- =========================================================================
     COMPACT HERO BANNER (STUDENT DASHBOARD)
     ========================================================================= -->
<div class="ak23-portal-hero mb-4" style="padding: 24px 28px; background: linear-gradient(135deg, #10131A 0%, #171B24 100%); border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.08);">
    <div class="ak23-portal-hero-left">
        <div class="ak23-portal-hero-tag mb-2" style="font-size: 11px; font-weight: 800; letter-spacing: 0.1em; color: #FBAF43;">
            <i class="las la-compact-disc me-1"></i> STUDENT PORTAL &middot; {{ strtoupper($user->username ?? 'STUDENT') }}
        </div>
        <h1 class="ak23-portal-hero-title mb-2" style="font-size: 24px; font-weight: 800; color: #FFFFFF; letter-spacing: -0.01em;">
            My Courses & Downloads
        </h1>
        <p class="ak23-portal-hero-sub mb-0" style="font-size: 13px; color: #94A3B8; max-width: 650px; line-height: 1.5;">
            Access and download your complete bundled course packages (.ZIP), inspect bundled software licenses, and review your order receipts.
        </p>
    </div>
    <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
        <a href="{{ route('products') }}" class="btn btn-sm btn--base rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2">
            <span>@lang('Browse All Courses')</span>
            <i class="las la-arrow-right"></i>
        </a>
    </div>
</div>

<!-- =========================================================================
     COMPACT METRIC CARDS (4 TILES)
     ========================================================================= -->
<div class="row g-3 mb-4">
    <!-- Stat 1: Courses owned -->
    <div class="col-6 col-lg-3">
        <div class="p-3 rounded-3 bg-white border h-100 d-flex align-items-center gap-3 shadow-xs">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(59, 130, 246, 0.1); color: #3B82F6; font-size: 22px;">
                <i class="las la-book-open"></i>
            </div>
            <div>
                <div class="fw-bold text-dark fs-20 lh-1 mb-1">{{ $coursesCount }}</div>
                <div class="text-muted fs-12 fw-medium">Courses Owned</div>
            </div>
        </div>
    </div>

    <!-- Stat 2: Downloads Ready -->
    <div class="col-6 col-lg-3">
        <div class="p-3 rounded-3 bg-white border h-100 d-flex align-items-center gap-3 shadow-xs">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(251, 175, 67, 0.12); color: #E29828; font-size: 22px;">
                <i class="las la-file-archive"></i>
            </div>
            <div>
                <div class="fw-bold text-dark fs-20 lh-1 mb-1">{{ $downloadsCount }}</div>
                <div class="text-muted fs-12 fw-medium">ZIP Packages Ready</div>
            </div>
        </div>
    </div>

    <!-- Stat 3: Software Licenses -->
    <div class="col-6 col-lg-3">
        <div class="p-3 rounded-3 bg-white border h-100 d-flex align-items-center gap-3 shadow-xs">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(168, 85, 247, 0.1); color: #A855F7; font-size: 22px;">
                <i class="las la-key"></i>
            </div>
            <div>
                <div class="fw-bold text-dark fs-20 lh-1 mb-1">{{ $licensesCount }}</div>
                <div class="text-muted fs-12 fw-medium">Licenses Included</div>
            </div>
        </div>
    </div>

    <!-- Stat 4: Orders Completed -->
    <div class="col-6 col-lg-3">
        <div class="p-3 rounded-3 bg-white border h-100 d-flex align-items-center gap-3 shadow-xs">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(34, 197, 94, 0.1); color: #22C55E; font-size: 22px;">
                <i class="las la-receipt"></i>
            </div>
            <div>
                <div class="fw-bold text-dark fs-20 lh-1 mb-1">{{ $ordersCount }}</div>
                <div class="text-muted fs-12 fw-medium">Completed Orders</div>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================================
     SECTION 1: PURCHASED COURSES & BUNDLES (COMPACT NATIVE CARDS)
     ========================================================================= -->
<div class="ak23-portal-card mb-4" id="courses-section">
    <div class="ak23-portal-card-header pb-3 mb-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h2 class="ak23-portal-card-title mb-1 fs-18 fw-bold">
                <i class="las la-graduation-cap text-warning me-2 fs-20"></i>Purchased Courses & Digital Packages
            </h2>
            <p class="ak23-portal-card-sub mb-0 text-muted fs-13">
                Full offline video packages (.ZIP) with matching software licenses, project stems, and presets.
            </p>
        </div>
        <a href="{{ route('products') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fs-12">
            <span>Explore All Catalog</span>
            <i class="las la-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-3">
        @forelse($purchasedItems as $item)
            <div class="col-md-6 col-xl-4">
                <div class="p-3 rounded-3 border bg-white h-100 d-flex flex-column justify-content-between shadow-xs position-relative" style="border-top: 3px solid #FBAF43 !important;">
                    <div>
                        <!-- Category and Access Badges -->
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge px-2 py-1 rounded-pill fs-11 fw-bold" style="background: rgba(15, 23, 42, 0.06); color: #0f172a;">
                                {{ __($item->category_name ?? 'Audio Course') }}
                            </span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0-5 rounded-pill fs-11">
                                <i class="las la-check-circle me-1"></i> Lifetime Access
                            </span>
                        </div>

                        <!-- Course Title -->
                        <h3 class="fw-bold text-dark mb-2 fs-15 lh-sm">
                            <a href="{{ route('product.details', $item->product_slug) }}" class="text-dark text-decoration-none">
                                {{ __($item->product_title) }}
                            </a>
                        </h3>

                        <!-- Compact Specs Strip -->
                        <div class="d-flex align-items-center justify-content-between text-muted fs-11 mb-2 pb-2 border-bottom">
                            <span>Code: <code class="text-primary fw-bold">{{ $item->purchase_code }}</code></span>
                            <span>{{ showDateTime($item->created_at, 'd M, Y') }}</span>
                        </div>

                        <!-- Feature highlights -->
                        <div class="p-2 rounded bg-light border mb-3 fs-11 text-secondary">
                            <div class="d-flex align-items-center gap-1 mb-1">
                                <i class="las la-check text-success"></i>
                                <span>Complete Master Package (.ZIP)</span>
                            </div>
                            @if(!empty($item->software_name))
                                <div class="d-flex align-items-center gap-1 text-dark fw-semibold">
                                    <i class="las la-key text-warning"></i>
                                    <span>{{ $item->software_name }} license included</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-2 d-flex gap-2">
                        <a href="{{ route('user.author.product.download', $item->purchase_code) }}" class="btn btn-sm btn--base flex-grow-1 text-center rounded-pill py-1-5 fw-semibold fs-12">
                            <i class="las la-download me-1"></i> Download .ZIP
                        </a>
                        <a href="{{ route('product.details', $item->product_slug) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1-5 fs-12" title="View Course Details">
                            <i class="las la-external-link-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-4 text-center text-muted">
                <i class="las la-box-open fs-1 text-secondary mb-2 d-block"></i>
                <p class="mb-2 fs-14">No purchased courses found yet.</p>
                <a href="{{ route('products') }}" class="btn btn--base btn-sm rounded-pill px-4">Browse Courses</a>
            </div>
        @endforelse
    </div>
</div>

<!-- =========================================================================
     SECTION 2: SOFTWARE LICENSES & ACTIVATION (COMPACT TABLE)
     ========================================================================= -->
@if($licenses->isNotEmpty())
<div class="ak23-portal-card mb-4" id="licenses-section">
    <div class="ak23-portal-card-header pb-2 mb-3 border-bottom d-flex align-items-center justify-content-between">
        <div>
            <h2 class="ak23-portal-card-title mb-1 fs-18 fw-bold">
                <i class="las la-key text-primary me-2 fs-20"></i>Software Licenses Included
            </h2>
            <p class="ak23-portal-card-sub mb-0 text-muted fs-13">
                Commercial and educational plugin licenses bundled with your course packages.
            </p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="fs-12 text-muted text-uppercase">
                    <th class="ps-3 py-2">Software Package</th>
                    <th class="py-2">Associated Course</th>
                    <th class="py-2">License Status</th>
                    <th class="pe-3 py-2 text-end">Activation Delivery</th>
                </tr>
            </thead>
            <tbody class="fs-13">
                @foreach($licenses as $lic)
                    <tr>
                        <td class="ps-3 py-2-5">
                            <div class="fw-bold text-dark">{{ $lic->product_name }}</div>
                            <span class="text-muted fs-11">{{ $lic->edition ?: 'Full Commercial Edition' }}</span>
                        </td>
                        <td class="py-2-5">
                            <span class="text-secondary">{{ $lic->course_title }}</span>
                        </td>
                        <td class="py-2-5">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill fs-11">
                                <i class="las la-check me-1"></i> Included with Course
                            </span>
                        </td>
                        <td class="pe-3 py-2-5 text-end text-muted fs-12">
                            <i class="las la-archive text-warning me-1"></i> Inside course .ZIP bundle
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- =========================================================================
     SECTION 3: RECENT ORDERS (COMPACT TABLE)
     ========================================================================= -->
<div class="ak23-portal-card mb-4" id="orders-section">
    <div class="ak23-portal-card-header pb-2 mb-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h2 class="ak23-portal-card-title mb-1 fs-18 fw-bold">
                <i class="las la-receipt text-success me-2 fs-20"></i>Recent Orders & Invoices
            </h2>
            <p class="ak23-portal-card-sub mb-0 text-muted fs-13">Verified digital payment transactions and invoice receipts.</p>
        </div>
        <a href="{{ route('user.order.list') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fs-12">
            <span>View All Orders</span>
            <i class="las la-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="table-responsive">
        <table class="ak23-orders-table table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="fs-12 text-muted text-uppercase">
                    <th class="ps-3 py-2">Order / Transaction</th>
                    <th class="py-2">Date</th>
                    <th class="py-2">Items</th>
                    <th class="py-2">Total Amount</th>
                    <th class="py-2">Status</th>
                    <th class="pe-3 py-2 text-end">Action</th>
                </tr>
            </thead>
            <tbody class="fs-13">
                @forelse($recentOrders as $order)
                    <tr>
                        <td class="ps-3 py-2-5">
                            <div class="fw-bold text-dark fs-13">#{{ $order->trx ?: $order->id }}</div>
                            <span class="text-muted fs-11">Digital course bundle payment</span>
                        </td>
                        <td class="py-2-5">
                            <span class="text-secondary fs-12">{{ showDateTime($order->created_at, 'd M, Y H:i') }}</span>
                        </td>
                        <td class="py-2-5">
                            <span class="badge bg-light text-dark border fs-11">{{ $order->total_items ?? 1 }} Course(s)</span>
                        </td>
                        <td class="py-2-5">
                            <span class="fw-bold text-dark fs-13">
                                TSH {{ number_format($order->amount) }}
                            </span>
                        </td>
                        <td class="py-2-5">
                            @if(($order->payment_status ?? 0) == 1)
                                <span class="ak23-badge-paid"><i class="las la-check me-1"></i> Paid</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border px-2 py-0-5 rounded-pill fs-11">Pending</span>
                            @endif
                        </td>
                        <td class="pe-3 py-2-5 text-end">
                            <a href="{{ route('user.order.details', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fs-12">
                                <i class="las la-file-invoice me-1"></i> Receipt
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No recent orders recorded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
