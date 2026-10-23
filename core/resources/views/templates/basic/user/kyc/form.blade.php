@extends($activeTemplate . 'layouts.portal')

@section('content')

<!-- =========================================================================
     HERO BANNER: KYC FORM
     ========================================================================= -->
<div class="ak23-portal-hero">
    <div class="ak23-portal-hero-left">
        <div class="ak23-portal-hero-tag">
            <i class="las la-id-card me-1"></i> IDENTITY & COMPLIANCE VERIFICATION &middot; {{ strtoupper(auth()->user()->username ?? 'STUDENT') }}
        </div>
        <h1 class="ak23-portal-hero-title">KYC Document Submission</h1>
        <p class="ak23-portal-hero-sub">
            Upload your valid identity documents to unlock official studio certification and commercial software licenses.
        </p>
    </div>
    <a href="{{ route('user.kyc.data') }}" class="ak23-portal-hero-cta" style="background: rgba(255, 255, 255, 0.12); color: #FFFFFF !important; border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: none;">
        <i class="las la-arrow-left"></i>
        <span>Back to KYC Details</span>
    </a>
</div>

<!-- =========================================================================
     KYC SUBMISSION FORM CARD
     ========================================================================= -->
<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">
        <div class="ak23-portal-card">
            <div class="ak23-portal-card-header">
                <div>
                    <h2 class="ak23-portal-card-title">Required Compliance Information</h2>
                    <p class="ak23-portal-card-sub">Please fill in all requested fields truthfully. All documents are securely encrypted.</p>
                </div>
                <span class="ak23-badge-pending">
                    <i class="las la-shield-alt me-1"></i> SSL 256-Bit Encrypted
                </span>
            </div>

            <form action="{{ route('user.kyc.submit') }}" method="post" enctype="multipart/form-data" class="disableSubmission">
                @csrf
                <div class="ak23-kyc-fields">
                    <x-viser-form frontend="true" identifier="act" identifierValue="kyc" />
                </div>

                <div class="form-group mt-4 pt-3 border-top d-flex align-items-center justify-content-between gap-3 flex-wrap">
                    <a href="{{ route('user.kyc.data') }}" class="btn btn-outline-secondary px-4">
                        @lang('Cancel')
                    </a>
                    <button type="submit" class="btn btn--base px-4 py-2">
                        <i class="las la-check-circle me-1"></i> @lang('Submit Documents for Verification')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('style')
<style>
    .ak23-kyc-fields .form-group {
        margin-bottom: 1.25rem;
    }
    .ak23-kyc-fields label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }
    .ak23-kyc-fields .form-control,
    .ak23-kyc-fields .form-select {
        border-radius: 8px;
        border: 1px solid #CBD5E1;
        padding: 9px 14px;
        font-size: 13.5px;
    }
    .ak23-kyc-fields .form-control:focus,
    .ak23-kyc-fields .form-select:focus {
        border-color: #FBAF43;
        box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.18);
    }
</style>
@endpush
