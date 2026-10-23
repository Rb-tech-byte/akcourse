@extends($activeTemplate . 'layouts.portal')

@section('content')

<!-- =========================================================================
     HERO BANNER: CHANGE PASSWORD
     ========================================================================= -->
<div class="ak23-portal-hero">
    <div class="ak23-portal-hero-left">
        <div class="ak23-portal-hero-tag">
            <i class="las la-key me-1"></i> SECURITY &amp; CREDENTIALS &middot; {{ strtoupper(auth()->user()->username ?? 'STUDENT') }}
        </div>
        <h1 class="ak23-portal-hero-title">Change Password</h1>
        <p class="ak23-portal-hero-sub">
            Update your account password to ensure your studio assets, downloads, and software licenses stay safe.
        </p>
    </div>
    <a href="{{ route('user.twofactor') }}" class="ak23-portal-hero-cta" style="background: rgba(255, 255, 255, 0.12); color: #FFFFFF !important; border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: none;">
        <i class="las la-lock"></i>
        <span>2FA Security</span>
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="ak23-portal-card">
            <div class="ak23-portal-card-header">
                <div>
                    <h2 class="ak23-portal-card-title">@lang('Update Account Password')</h2>
                    <p class="ak23-portal-card-sub">Choose a strong, unique password with at least 8 characters.</p>
                </div>
            </div>

            <form method="post" class="disableSubmission">
                @csrf
                <div class="form-group mb-3">
                    <label class="form-label fw-bold text-dark fs-13 mb-1">@lang('Current Password') <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="current_password" required autocomplete="current-password" placeholder="Enter existing password">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label fw-bold text-dark fs-13 mb-1">@lang('New Password') <span class="text-danger">*</span></label>
                    <input type="password" class="form-control @if (gs('secure_password')) secure-password @endif" name="password" required autocomplete="new-password" placeholder="Enter new strong password">
                </div>
                <div class="form-group mb-4">
                    <label class="form-label fw-bold text-dark fs-13 mb-1">@lang('Confirm New Password') <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Re-type new password">
                </div>
                <button type="submit" class="btn btn--base w-100 py-2">
                    <i class="las la-check-circle me-1"></i> @lang('Update Password')
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
