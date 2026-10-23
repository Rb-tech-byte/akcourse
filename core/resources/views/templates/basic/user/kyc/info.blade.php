@extends($activeTemplate . 'layouts.portal')

@section('content')

<!-- =========================================================================
     HERO BANNER: KYC IDENTITY & COMPLIANCE
     ========================================================================= -->
<div class="ak23-portal-hero">
    <div class="ak23-portal-hero-left">
        <div class="ak23-portal-hero-tag">
            <i class="las la-id-card me-1"></i> IDENTITY & COMPLIANCE VERIFICATION &middot; {{ strtoupper($user->username ?? 'STUDENT') }}
        </div>
        <h1 class="ak23-portal-hero-title">Student KYC Verification</h1>
        <p class="ak23-portal-hero-sub">
            Review your student verification credentials, compliance status, and certified documents required for course diplomas and commercial software licenses.
        </p>
    </div>
    @if ($user->kv == Status::KYC_UNVERIFIED)
        <a href="{{ route('user.kyc.form') }}" class="ak23-portal-hero-cta">
            <i class="las la-file-upload"></i>
            <span>Submit Documents</span>
        </a>
    @elseif($user->kv == Status::KYC_PENDING)
        <div class="ak23-badge-pending px-3 py-2 fs-13 d-inline-flex align-items-center gap-1 shadow-sm">
            <i class="las la-clock fs-15"></i>
            <span>Verification In Review</span>
        </div>
    @else
        <div class="ak23-badge-delivered px-3 py-2 fs-13 d-inline-flex align-items-center gap-1 shadow-sm">
            <i class="las la-check-circle fs-15"></i>
            <span>Verified Student Account</span>
        </div>
    @endif
</div>

<!-- =========================================================================
     STAT CARDS (4 TILES)
     ========================================================================= -->
<div class="ak23-portal-stats-grid">
    <!-- Stat 1: Status -->
    <div class="ak23-portal-stat-card">
        <div class="ak23-portal-stat-icon" style="background: @if($user->kv == Status::KYC_VERIFIED) rgba(16, 185, 129, 0.12); color: #10B981; @elseif($user->kv == Status::KYC_PENDING) rgba(245, 158, 11, 0.12); color: #F59E0B; @else rgba(239, 68, 68, 0.12); color: #EF4444; @endif">
            <i class="las @if($user->kv == Status::KYC_VERIFIED) la-shield-alt @elseif($user->kv == Status::KYC_PENDING) la-hourglass-half @else la-exclamation-circle @endif"></i>
        </div>
        <div>
            <div class="ak23-portal-stat-val fs-18">
                @if ($user->kv == Status::KYC_VERIFIED)
                    <span class="text-success">Verified</span>
                @elseif($user->kv == Status::KYC_PENDING)
                    <span class="text-warning">In Review</span>
                @else
                    <span class="text-danger">Unverified</span>
                @endif
            </div>
            <p class="ak23-portal-stat-label">Verification Status</p>
        </div>
    </div>

    <!-- Stat 2: Document Count -->
    <div class="ak23-portal-stat-card">
        <div class="ak23-portal-stat-icon" style="background: rgba(251, 175, 67, 0.12); color: #E29828;">
            <i class="las la-file-alt"></i>
        </div>
        <div>
            <div class="ak23-portal-stat-val">{{ $documentCount ?? 0 }}</div>
            <p class="ak23-portal-stat-label">Verified Documents</p>
        </div>
    </div>

    <!-- Stat 3: Student Account Type -->
    <div class="ak23-portal-stat-card">
        <div class="ak23-portal-stat-icon" style="background: rgba(59, 130, 246, 0.12); color: #3B82F6;">
            <i class="las la-user-graduate"></i>
        </div>
        <div>
            <div class="ak23-portal-stat-val fs-18 text-truncate" style="max-width: 140px;">{{ $user->username }}</div>
            <p class="ak23-portal-stat-label">Student Profile</p>
        </div>
    </div>

    <!-- Stat 4: 2FA Security Level -->
    <div class="ak23-portal-stat-card">
        <div class="ak23-portal-stat-icon" style="background: rgba(168, 85, 247, 0.12); color: #A855F7;">
            <i class="las la-lock"></i>
        </div>
        <div>
            <div class="ak23-portal-stat-val fs-18">
                @if ($user->ts)
                    <span class="text-success">2FA Active</span>
                @else
                    <span class="text-muted">Standard</span>
                @endif
            </div>
            <p class="ak23-portal-stat-label">Security Tier</p>
        </div>
    </div>
</div>

<!-- =========================================================================
     COMPLIANCE STATUS BANNER
     ========================================================================= -->
<div class="row mb-4">
    <div class="col-12">
        @if ($user->kv == Status::KYC_VERIFIED)
            <div class="p-3 p-md-4 rounded-3 border d-flex align-items-center gap-3" style="background: #F0FDF4; border-color: #BBF7D0 !important;">
                <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background: #DCFCE7; color: #16A34A; font-size: 24px;">
                    <i class="las la-check-circle"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1 text-dark fw-bold">Identity Verification Approved</h6>
                    <p class="mb-0 text-muted fs-13">
                        Your identity has been validated under AK23 Academy guidelines. You are cleared for all certified diplomas, software license deliveries, and offline kit access.
                    </p>
                </div>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-12 fw-bold d-none d-md-inline-block">
                    Full Compliance
                </span>
            </div>
        @elseif($user->kv == Status::KYC_PENDING)
            <div class="p-3 p-md-4 rounded-3 border d-flex align-items-center gap-3" style="background: #FFFBEB; border-color: #FDE68A !important;">
                <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background: #FEF3C7; color: #D97706; font-size: 24px;">
                    <i class="las la-hourglass-half"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1 text-dark fw-bold">KYC Verification Under Review</h6>
                    <p class="mb-0 text-muted fs-13">
                        Your submitted documentation is being processed by our compliance team. Verification is normally finalized within 1 to 2 business hours.
                    </p>
                </div>
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fs-12 fw-bold d-none d-md-inline-block">
                    Review In Progress
                </span>
            </div>
        @else
            <div class="p-3 p-md-4 rounded-3 border d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3" style="background: #FFF7ED; border-color: #FED7AA !important;">
                <div class="d-flex align-items-start gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background: #FFEDD5; color: #EA580C; font-size: 24px;">
                        <i class="las la-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-dark fw-bold">Identity Verification Required</h6>
                        <p class="mb-0 text-muted fs-13">
                            Please complete student identity verification to unlock certified course certificates, stems, and commercial software license deliveries.
                        </p>
                        @if ($user->kyc_rejection_reason)
                            <div class="mt-2 p-2 rounded bg-white border border-danger-subtle text-danger fs-12">
                                <i class="las la-times-circle me-1"></i>
                                <strong>Previous Rejection Reason:</strong> {{ $user->kyc_rejection_reason }}
                            </div>
                        @endif
                    </div>
                </div>
                <a href="{{ route('user.kyc.form') }}" class="btn btn--base btn--sm px-4 text-nowrap flex-shrink-0">
                    <i class="las la-arrow-right me-1"></i> Complete Verification
                </a>
            </div>
        @endif
    </div>
</div>

<!-- =========================================================================
     SUBMITTED KYC DETAILS & DOCUMENTS
     ========================================================================= -->
<div class="row">
    <div class="col-12">
        <div class="ak23-portal-card">
            <div class="ak23-portal-card-header">
                <div>
                    <h2 class="ak23-portal-card-title">Submitted Verification Details</h2>
                    <p class="ak23-portal-card-sub">Recorded data and compliance proof stored securely in your encrypted profile.</p>
                </div>
                @if ($user->kv == Status::KYC_UNVERIFIED)
                    <a href="{{ route('user.kyc.form') }}" class="btn btn--base btn--sm">
                        <i class="las la-upload me-1"></i> Upload Documents
                    </a>
                @endif
            </div>

            @if (!empty($kycData))
                <div class="table-responsive">
                    <table class="ak23-orders-table">
                        <thead>
                            <tr>
                                <th>Field / Requirement</th>
                                <th>Submitted Information</th>
                                <th>Type</th>
                                <th class="text-end">Verification Proof</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kycData as $val)
                                @continue(!$val->value)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: #F1F5F9; color: #475569; font-size: 14px;">
                                                <i class="las @if($val->type == 'file') la-file @else la-check @endif"></i>
                                            </div>
                                            <span class="fw-bold text-dark">{{ __($val->name) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($val->type == 'checkbox')
                                            <span class="badge bg-light text-dark border">{{ implode(', ', (array) $val->value) }}</span>
                                        @elseif($val->type == 'file')
                                            <span class="text-muted fs-12"><i class="las la-paperclip me-1"></i>Attached Document</span>
                                        @else
                                            <span class="fw-medium text-dark">{{ __($val->value) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-muted border text-uppercase fs-11">{{ $val->type }}</span>
                                    </td>
                                    <td class="text-end">
                                        @if ($val->type == 'file')
                                            <a href="{{ route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) }}" class="btn btn-outline--base btn--sm">
                                                <i class="las la-download me-1"></i> @lang('Download Document')
                                            </a>
                                        @else
                                            <span class="text-success fs-13 fw-semibold">
                                                <i class="las la-check-circle me-1"></i> Recorded
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 px-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 68px; height: 68px; background: #F1F5F9; color: #94A3B8; font-size: 32px;">
                        <i class="las la-id-card-alt"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">No Verification Records Found</h5>
                    <p class="text-muted fs-13 mb-4 mx-auto" style="max-width: 480px;">
                        You have not submitted your student identity verification yet. Verification allows you to receive official course completion certificates, soundbank project rights, and offline license keys.
                    </p>
                    <a href="{{ route('user.kyc.form') }}" class="btn btn--base px-4">
                        <i class="las la-arrow-right me-1"></i> Submit KYC Verification Form
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
