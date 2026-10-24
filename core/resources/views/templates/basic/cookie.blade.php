@extends($activeTemplate . 'layouts.frontend')
@section('content')

@php
    $allPolicies = $allPolicies ?? \App\Models\Frontend::where('data_keys', 'policy_pages.element')->get();
@endphp

<div class="m23-policy-page py-5" style="background: #0A0B10; min-height: 80vh;">
    <div class="container py-2 py-md-4">

        <!-- Breadcrumb & Page Header -->
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('home') }}" class="text-muted fs-12 text-decoration-none">@lang('Home')</a>
                <span class="text-muted fs-11">&bull;</span>
                <span class="text-muted fs-12">@lang('Legal & Governance')</span>
                <span class="text-muted fs-11">&bull;</span>
                <span class="text-warning fs-12 fw-semibold">{{ __($pageTitle) }}</span>
            </div>

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1 rounded-pill fs-11 text-uppercase fw-bold letter-spacing-1 mb-2">
                        <i class="las la-cookie-bite me-1"></i> MAARIFA23 ACADEMY &middot; COOKIE GOVERNANCE
                    </span>
                    <h1 class="text-white fw-bold mb-1 fs-28">
                        {{ __($pageTitle) }}
                    </h1>
                    <p class="text-muted fs-14 mb-0">
                        How Maarifa23 utilizes minimal essential cookies to keep student portals secure.
                    </p>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1-5 fs-12 text-white-50" onclick="window.print();">
                        <i class="las la-print me-1"></i> @lang('Print / Save PDF')
                    </button>
                    <a href="{{ route('contact') }}" class="btn btn-sm btn--base rounded-pill px-3 py-1-5 fs-12 fw-bold text-decoration-none">
                        <i class="las la-headset me-1"></i> @lang('Need Help?')
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column: Navigation & Trust Cards -->
            <div class="col-lg-4 col-md-5">
                <div class="sticky-top" style="top: 90px; z-index: 10;">
                    
                    <!-- Policy Navigation Card -->
                    <div class="p-3 rounded-3 border mb-3" style="background: #121420; border-color: rgba(255,255,255,0.08) !important;">
                        <span class="text-muted fs-11 text-uppercase fw-bold letter-spacing-1 d-block mb-2 ps-2">
                            @lang('LEGAL DOCUMENTS')
                        </span>

                        <div class="d-flex flex-column gap-1">
                            @foreach($allPolicies as $p)
                                @php
                                    $icon = match($p->slug) {
                                        'privacy-policy' => 'la-user-shield',
                                        'terms-of-service' => 'la-file-contract',
                                        'refund-policy' => 'la-undo-alt',
                                        default => 'la-balance-scale'
                                    };
                                @endphp
                                <a href="{{ route('policy.pages', $p->slug) }}" 
                                   class="d-flex align-items-center justify-content-between px-3 py-2-5 rounded-2 text-decoration-none transition-all text-secondary hover-policy-link"
                                   style="border-left: 3px solid transparent;">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="las {{ $icon }} fs-18 text-muted"></i>
                                        <span class="fs-13 fw-semibold">{{ __($p->data_values->title) }}</span>
                                    </div>
                                    <i class="las la-angle-right fs-12 text-muted"></i>
                                </a>
                            @endforeach

                            <!-- Active Cookie Policy Link -->
                            <a href="{{ route('cookie.policy') }}" 
                               class="d-flex align-items-center justify-content-between px-3 py-2-5 rounded-2 text-decoration-none transition-all text-white"
                               style="background: rgba(245, 158, 11, 0.12); border-left: 3px solid #F59E0B;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="las la-cookie-bite fs-18 text-warning"></i>
                                    <span class="fs-13 fw-semibold">@lang('Cookie Policy')</span>
                                </div>
                                <span class="badge bg-warning text-dark fs-10 px-2 py-0-5 rounded-pill fw-bold">Active</span>
                            </a>
                        </div>
                    </div>

                    <!-- Fast Verification Portal Box -->
                    <div class="p-3 rounded-3 border text-center" style="background: #10121D; border-color: rgba(245, 158, 11, 0.25) !important;">
                        <i class="las la-certificate text-warning fs-28 mb-1 d-block"></i>
                        <span class="text-white fs-13 fw-bold d-block mb-1">Verify License or Certificate</span>
                        <p class="text-muted fs-11 mb-2">
                            Confirm authenticity of any course purchase key or student certificate number.
                        </p>
                        <a href="{{ route('license.verify') }}" class="btn btn-sm btn-outline-warning w-100 rounded-pill fs-12 fw-bold">
                            @lang('Open Registry Portal') <i class="las la-arrow-right ms-1"></i>
                        </a>
                    </div>

                </div>
            </div>

            <!-- Right Column: Native Card Cookie Body -->
            <div class="col-lg-8 col-md-7">
                <div class="m23-native-card p-4 p-md-5 rounded-3 border" style="background: #11131E; border-color: rgba(255,255,255,0.08) !important; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                    
                    <!-- Card Top Header -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pb-3 mb-4 border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 34px; height: 34px; background: rgba(245, 158, 11, 0.12); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.25);">
                                <i class="las la-cookie fs-18"></i>
                            </span>
                            <div>
                                <span class="text-white fw-bold fs-15 d-block leading-tight">{{ __($pageTitle) }}</span>
                                <span class="text-muted fs-11">Document ID: M23-POL-COOKIE</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 fs-11 text-muted">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                <i class="las la-circle fs-8 me-1"></i> Active Policy
                            </span>
                            <span>&bull;</span>
                            <span>Revised October 2026</span>
                        </div>
                    </div>

                    <!-- Cookie Content Body -->
                    <div class="m23-policy-content">
                        @php
                            echo $cookie->data_values->description;
                        @endphp
                    </div>

                    <!-- Card Bottom Sign-off -->
                    <div class="mt-5 pt-4 border-top" style="border-color: rgba(255,255,255,0.08) !important;">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 rounded-3" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);">
                            <div>
                                <span class="text-white fs-13 fw-bold d-block">Questions about your data?</span>
                                <span class="text-muted fs-11">Our privacy team can assist you with your cookie preferences.</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="mailto:support@maarifa23.com" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1-5 fs-12 text-white-50">
                                    <i class="las la-envelope me-1"></i> support@maarifa23.com
                                </a>
                                <a href="{{ route('contact') }}" class="btn btn-sm btn--base rounded-pill px-3 py-1-5 fs-12 fw-bold">
                                    <i class="las la-headset me-1"></i> @lang('Contact Support')
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('style')
<style>
/* Policy Page Scoped Styling */
.m23-policy-page {
    color: #CBD5E1;
}

.m23-policy-content h5, 
.m23-policy-content h6 {
    color: #FFFFFF;
    font-weight: 700;
}

.m23-policy-content p {
    color: #94A3B8;
    line-height: 1.75;
    font-size: 13.5px;
}

.m23-policy-content ul {
    color: #94A3B8;
}

.m23-policy-content strong {
    color: #F1F5F9;
}

.m23-policy-content a {
    color: #F59E0B;
}

.hover-policy-link:hover {
    background: rgba(255, 255, 255, 0.04);
    color: #FFFFFF !important;
}

@media print {
    .m23-policy-page {
        background: #FFFFFF !important;
        color: #000000 !important;
    }
    .col-lg-4, .sticky-top, header, footer, .btn {
        display: none !important;
    }
    .col-lg-8 {
        width: 100% !important;
    }
    .m23-native-card {
        background: #FFFFFF !important;
        border: none !important;
        box-shadow: none !important;
        color: #000000 !important;
    }
    .m23-policy-content p, .m23-policy-content li {
        color: #222222 !important;
    }
    .m23-policy-content strong, .m23-policy-content h5 {
        color: #000000 !important;
    }
}
</style>
@endpush
