@extends($activeTemplate . 'layouts.portal')

@section('content')

<!-- =========================================================================
     HERO BANNER: 2FA SECURITY
     ========================================================================= -->
<div class="ak23-portal-hero">
    <div class="ak23-portal-hero-left">
        <div class="ak23-portal-hero-tag">
            <i class="las la-shield-alt me-1"></i> ACCOUNT SECURITY & AUTHENTICATION &middot; {{ strtoupper(auth()->user()->username ?? 'STUDENT') }}
        </div>
        <h1 class="ak23-portal-hero-title">Two-Factor Authentication (2FA)</h1>
        <p class="ak23-portal-hero-sub">
            Add an extra layer of protection to your student account, purchased studio courses, sound stems, and software license keys using TOTP authenticator apps.
        </p>
    </div>
    @if (auth()->user()->ts)
        <div class="ak23-badge-delivered px-3 py-2 fs-13 d-inline-flex align-items-center gap-1 shadow-sm">
            <i class="las la-check-double fs-15"></i>
            <span>2FA Protection Active</span>
        </div>
    @else
        <div class="ak23-badge-pending px-3 py-2 fs-13 d-inline-flex align-items-center gap-1 shadow-sm">
            <i class="las la-exclamation-triangle fs-15"></i>
            <span>2FA Recommended</span>
        </div>
    @endif
</div>

<!-- =========================================================================
     STAT TILES (4 TILES)
     ========================================================================= -->
<div class="ak23-portal-stats-grid">
    <!-- Stat 1: 2FA Status -->
    <div class="ak23-portal-stat-card">
        <div class="ak23-portal-stat-icon" style="background: @if(auth()->user()->ts) rgba(16, 185, 129, 0.12); color: #10B981; @else rgba(245, 158, 11, 0.12); color: #F59E0B; @endif">
            <i class="las @if(auth()->user()->ts) la-shield-alt @else la-lock-open @endif"></i>
        </div>
        <div>
            <div class="ak23-portal-stat-val fs-18">
                @if (auth()->user()->ts)
                    <span class="text-success">Enabled</span>
                @else
                    <span class="text-warning">Disabled</span>
                @endif
            </div>
            <p class="ak23-portal-stat-label">Security Shield</p>
        </div>
    </div>

    <!-- Stat 2: Protocol -->
    <div class="ak23-portal-stat-card">
        <div class="ak23-portal-stat-icon" style="background: rgba(59, 130, 246, 0.12); color: #3B82F6;">
            <i class="las la-qrcode"></i>
        </div>
        <div>
            <div class="ak23-portal-stat-val fs-18">TOTP 6-Digit</div>
            <p class="ak23-portal-stat-label">Algorithm (RFC 6238)</p>
        </div>
    </div>

    <!-- Stat 3: Scope -->
    <div class="ak23-portal-stat-card">
        <div class="ak23-portal-stat-icon" style="background: rgba(251, 175, 67, 0.12); color: #E29828;">
            <i class="las la-key"></i>
        </div>
        <div>
            <div class="ak23-portal-stat-val fs-18">Licenses & Logins</div>
            <p class="ak23-portal-stat-label">Protection Scope</p>
        </div>
    </div>

    <!-- Stat 4: Student Account -->
    <div class="ak23-portal-stat-card">
        <div class="ak23-portal-stat-icon" style="background: rgba(168, 85, 247, 0.12); color: #A855F7;">
            <i class="las la-user-shield"></i>
        </div>
        <div>
            <div class="ak23-portal-stat-val fs-18 text-truncate" style="max-width: 140px;">{{ auth()->user()->username }}</div>
            <p class="ak23-portal-stat-label">Protected User</p>
        </div>
    </div>
</div>

<!-- =========================================================================
     2FA WORKFLOW SECTIONS
     ========================================================================= -->
<div class="row g-4">
    @if (!auth()->user()->ts)
        <!-- Left: Setup Key & QR Code -->
        <div class="col-lg-6">
            <div class="ak23-portal-card h-100">
                <div class="ak23-portal-card-header">
                    <div>
                        <h2 class="ak23-portal-card-title">1. Link Authenticator App</h2>
                        <p class="ak23-portal-card-sub">Scan the QR code below using Google Authenticator, Authy, or Microsoft Authenticator.</p>
                    </div>
                </div>

                <div class="text-center my-3">
                    <div class="d-inline-block p-3 rounded-3 bg-white border shadow-sm">
                        <img src="{{ $qrCodeUrl }}" alt="2FA QR Code" class="img-fluid" style="width: 180px; height: 180px;">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-dark fs-13 mb-1">
                        <i class="las la-key me-1 text-warning"></i> Manual Secret Setup Key
                    </label>
                    <div class="input-group">
                        <input type="text" value="{{ $secret }}" class="form-control font-monospace bg-light fs-13 referralURL" readonly id="secretKeyInput">
                        <button class="btn btn-outline-secondary" type="button" id="copySecretBtn" title="Copy key">
                            <i class="las la-copy"></i> Copy
                        </button>
                    </div>
                    <small class="text-muted fs-11 mt-1 d-block">
                        If you cannot scan the QR code, manually enter this secret key into your authenticator app.
                    </small>
                </div>

                <div class="p-3 rounded-3 bg-light border mt-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="las la-mobile text-dark fs-18"></i>
                        <h6 class="mb-0 fw-bold fs-13 text-dark">Don't have an authenticator app?</h6>
                    </div>
                    <p class="text-muted fs-12 mb-2">
                        Download Google Authenticator for free on your mobile device to generate time-sensitive 6-digit codes.
                    </p>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank" class="btn btn-outline-dark btn--sm fs-11">
                            <i class="lab la-google-play me-1"></i> Google Play
                        </a>
                        <a href="https://apps.apple.com/app/google-authenticator/id388497605" target="_blank" class="btn btn-outline-dark btn--sm fs-11">
                            <i class="lab la-apple me-1"></i> App Store
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Verification & Enable Form -->
        <div class="col-lg-6">
            <div class="ak23-portal-card h-100">
                <div class="ak23-portal-card-header">
                    <div>
                        <h2 class="ak23-portal-card-title">2. Enter 6-Digit Code</h2>
                        <p class="ak23-portal-card-sub">Type the current verification code displayed in your authenticator app to confirm setup.</p>
                    </div>
                </div>

                <form action="{{ route('user.twofactor.enable') }}" method="POST" class="disableSubmission mt-2">
                    @csrf
                    <input type="hidden" name="key" value="{{ $secret }}">

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark fs-13 mb-2">
                            Authenticator One-Time Password (OTP) <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="code" class="form-control form-control-lg text-center font-monospace fw-bold fs-20" placeholder="000 000" maxlength="6" pattern="[0-9]*" inputmode="numeric" required autocomplete="off">
                        <small class="text-muted fs-12 mt-1 d-block text-center">
                            Enter the 6-digit number shown on your mobile device.
                        </small>
                    </div>

                    <div class="p-3 rounded-3 border mb-4" style="background: #F0FDF4; border-color: #BBF7D0 !important;">
                        <div class="d-flex gap-2">
                            <i class="las la-shield-alt text-success fs-20 flex-shrink-0"></i>
                            <div class="fs-12 text-muted">
                                <strong class="text-dark d-block mb-1">What happens next?</strong>
                                Once enabled, each login and sensitive license action will prompt you for a temporary code generated by your mobile app.
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn--base w-100 py-2 fs-15">
                        <i class="las la-lock me-1"></i> Activate 2FA Security
                    </button>
                </form>
            </div>
        </div>
    @else
        <!-- 2FA Enabled State -->
        <div class="col-lg-6">
            <div class="ak23-portal-card h-100">
                <div class="ak23-portal-card-header">
                    <div>
                        <h2 class="ak23-portal-card-title">2FA Protection Active</h2>
                        <p class="ak23-portal-card-sub">Your student portal account is currently secured with two-factor authentication.</p>
                    </div>
                    <span class="ak23-badge-delivered">
                        <i class="las la-check-circle me-1"></i> Active
                    </span>
                </div>

                <div class="p-4 rounded-3 text-center my-3" style="background: #F0FDF4; border: 1px solid #BBF7D0;">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 64px; height: 64px; background: #DCFCE7; color: #16A34A; font-size: 32px;">
                        <i class="las la-user-shield"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Account Highly Secured</h5>
                    <p class="text-muted fs-13 mb-0 mx-auto" style="max-width: 420px;">
                        Google Authenticator verification is required whenever logging into your AK23 account or unlocking commercial software license deliveries.
                    </p>
                </div>

                <div class="p-3 rounded-3 bg-light border mt-3">
                    <h6 class="fs-13 fw-bold text-dark mb-2"><i class="las la-info-circle me-1 text-primary"></i> Security Best Practices</h6>
                    <ul class="text-muted fs-12 ps-3 mb-0">
                        <li class="mb-1">Ensure your mobile device clock is synced accurately with network time.</li>
                        <li class="mb-1">Do not share your authenticator codes or screenshots with anyone.</li>
                        <li>AK23 Academy support will never ask you for your 2FA security code.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="ak23-portal-card h-100">
                <div class="ak23-portal-card-header">
                    <div>
                        <h2 class="ak23-portal-card-title text-danger">Disable 2FA Security</h2>
                        <p class="ak23-portal-card-sub">Turn off two-step verification if you are changing devices or apps.</p>
                    </div>
                </div>

                <div class="p-3 rounded-3 border mb-4" style="background: #FEF2F2; border-color: #FECACA !important;">
                    <div class="d-flex gap-2">
                        <i class="las la-exclamation-triangle text-danger fs-20 flex-shrink-0"></i>
                        <div class="fs-12 text-muted">
                            <strong class="text-danger d-block mb-1">Warning: Reduced Account Defense</strong>
                            Disabling 2FA leaves your studio courses, vocal stems, and licenses protected only by your password. We strongly advise keeping 2FA enabled.
                        </div>
                    </div>
                </div>

                <form action="{{ route('user.twofactor.disable') }}" method="POST" class="disableSubmission">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark fs-13 mb-2">
                            Enter Current Authenticator Code <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="code" class="form-control form-control-lg text-center font-monospace fw-bold fs-20" placeholder="000 000" maxlength="6" pattern="[0-9]*" inputmode="numeric" required autocomplete="off">
                        <small class="text-muted fs-12 mt-1 d-block text-center">
                            Confirm authorization by typing the 6-digit code from your app.
                        </small>
                    </div>

                    <button type="submit" class="btn btn-outline-danger w-100 py-2 fs-15">
                        <i class="las la-unlock me-1"></i> Deactivate 2FA Security
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>

@endsection

@push('script')
<script>
    (function($) {
        "use strict";
        $('#copySecretBtn').on('click', function() {
            var copyText = document.getElementById("secretKeyInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value).then(function() {
                var originalHtml = $('#copySecretBtn').html();
                $('#copySecretBtn').html('<i class="las la-check text-success"></i> Copied!');
                setTimeout(function() {
                    $('#copySecretBtn').html(originalHtml);
                }, 2500);
            }).catch(function() {
                document.execCommand("copy");
            });
        });
    })(jQuery);
</script>
@endpush
