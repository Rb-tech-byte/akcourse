@extends($activeTemplate . 'layouts.portal')

@section('content')

@php
    $user = auth()->user();
    $userInitials = strtoupper(substr($user->firstname ?: $user->username, 0, 1) . substr($user->lastname ?: '', 0, 1));
    $userAvatar = $user->image ? getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) : null;
    $addressVal = is_object($user->address) ? (@$user->address->address ?: '') : ($user->address ?: '');
    $cityVal    = is_object($user->address) ? (@$user->address->city ?: $user->city) : ($user->city ?: '');
    $stateVal   = is_object($user->address) ? (@$user->address->state ?: $user->state) : ($user->state ?: '');
    $zipVal     = is_object($user->address) ? (@$user->address->zip ?: $user->zip) : ($user->zip ?: '');
    $countryVal = is_object($user->address) ? (@$user->address->country ?: $user->country_name) : ($user->country_name ?: '');
@endphp

<!-- =========================================================================
     HERO BANNER: PROFILE SETTINGS
     ========================================================================= -->
<div class="ak23-portal-hero">
    <div class="ak23-portal-hero-left">
        <div class="ak23-portal-hero-tag">
            <i class="las la-user-cog me-1"></i> ACCOUNT SETTINGS &middot; {{ strtoupper($user->username ?? 'STUDENT') }}
        </div>
        <h1 class="ak23-portal-hero-title">Profile &amp; Preferences</h1>
        <p class="ak23-portal-hero-sub">
            Manage your student identity, studio location, personal contact information, and account preferences.
        </p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('user.change.password') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1">
            <i class="las la-key"></i>
            <span>Password</span>
        </a>
        <a href="{{ route('user.twofactor') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1">
            <i class="las la-shield-alt"></i>
            <span>2FA Security</span>
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Identity Snapshot & Quick Shortcuts -->
    <div class="col-lg-4 col-xl-3">
        <div class="ak23-portal-card text-center mb-4">
            <div class="position-relative d-inline-block mx-auto mb-3">
                @if ($userAvatar)
                    <img src="{{ $userAvatar }}" alt="{{ $user->username }}" id="avatarPreviewImg" class="rounded-circle border border-2 border-warning shadow-sm" style="width: 104px; height: 104px; object-fit: cover;">
                @else
                    <div id="avatarPlaceholder" class="rounded-circle d-flex align-items-center justify-content-center mx-auto border border-2 border-warning shadow-sm" style="width: 104px; height: 104px; background: #1B1E28; color: #FBAF43; font-size: 32px; font-weight: 800;">
                        {{ $userInitials ?: 'ST' }}
                    </div>
                    <img src="" alt="" id="avatarPreviewImg" class="rounded-circle border border-2 border-warning shadow-sm d-none" style="width: 104px; height: 104px; object-fit: cover;">
                @endif
            </div>

            <h3 class="fs-18 fw-bold text-dark mb-1">{{ $user->fullname ?: $user->username }}</h3>
            <div class="text-muted fs-12 mb-3">&#64;{{ $user->username }}</div>

            <div class="d-flex flex-wrap justify-content-center gap-1 mb-3">
                <span class="badge rounded-pill bg-light text-dark border px-2 py-1 fs-11">
                    <i class="las la-graduation-cap text-warning me-1"></i> Student
                </span>
                @if ($user->kv == 1)
                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                        <i class="las la-check-circle me-1"></i> KYC Verified
                    </span>
                @else
                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">
                        <i class="las la-clock me-1"></i> KYC Unverified
                    </span>
                @endif
                @if ($user->ts == 1)
                    <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11">
                        <i class="las la-shield-alt me-1"></i> 2FA Active
                    </span>
                @endif
            </div>

            <hr class="my-3 opacity-25">

            <div class="text-start fs-12 text-muted">
                <div class="d-flex align-items-center justify-content-between py-1 border-bottom border-light">
                    <span><i class="las la-envelope me-1 text-secondary"></i> Email</span>
                    <span class="text-dark fw-semibold" title="{{ $user->email }}">{{ Str::limit($user->email, 18) }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between py-1 border-bottom border-light">
                    <span><i class="las la-phone me-1 text-secondary"></i> Phone</span>
                    <span class="text-dark fw-semibold">{{ $user->mobile ? '+' . $user->dial_code . ' ' . $user->mobile : 'Not set' }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between py-1">
                    <span><i class="las la-calendar me-1 text-secondary"></i> Joined</span>
                    <span class="text-dark fw-semibold">{{ showDateTime($user->created_at, 'M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Security Navigation Card -->
        <div class="ak23-portal-card p-3">
            <div class="fw-bold fs-13 text-dark mb-2 px-1">
                <i class="las la-shield-alt text-warning me-1"></i> Security & Identity
            </div>
            <div class="list-group list-group-flush fs-13">
                <a href="{{ route('user.change.password') }}" class="list-group-item list-group-item-action border-0 px-2 py-2 rounded d-flex align-items-center justify-content-between">
                    <span><i class="las la-key text-muted me-2"></i> Change Password</span>
                    <i class="las la-angle-right text-muted fs-11"></i>
                </a>
                <a href="{{ route('user.twofactor') }}" class="list-group-item list-group-item-action border-0 px-2 py-2 rounded d-flex align-items-center justify-content-between">
                    <span><i class="las la-lock text-muted me-2"></i> Two-Factor Auth</span>
                    <i class="las la-angle-right text-muted fs-11"></i>
                </a>
                @if ($user->kv == 1)
                    <a href="{{ route('user.kyc.data') }}" class="list-group-item list-group-item-action border-0 px-2 py-2 rounded d-flex align-items-center justify-content-between">
                        <span><i class="las la-id-card text-muted me-2"></i> KYC Information</span>
                        <i class="las la-angle-right text-muted fs-11"></i>
                    </a>
                @else
                    <a href="{{ route('user.kyc.form') }}" class="list-group-item list-group-item-action border-0 px-2 py-2 rounded d-flex align-items-center justify-content-between">
                        <span><i class="las la-id-card text-muted me-2"></i> Submit KYC</span>
                        <i class="las la-angle-right text-muted fs-11"></i>
                    </a>
                @endif
                <a href="{{ route('ticket.index') }}" class="list-group-item list-group-item-action border-0 px-2 py-2 rounded d-flex align-items-center justify-content-between">
                    <span><i class="las la-headset text-muted me-2"></i> Support Center</span>
                    <i class="las la-angle-right text-muted fs-11"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Right Column: Settings Form Native Card -->
    <div class="col-lg-8 col-xl-9">
        <form action="{{ route('user.profile.setting.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Card 1: Personal Details -->
            <div class="ak23-portal-card mb-4">
                <div class="ak23-portal-card-header pb-3 mb-3 border-bottom">
                    <div>
                        <h2 class="ak23-portal-card-title fs-16 fw-bold">
                            <i class="las la-user-circle text-warning me-1"></i> Personal Information
                        </h2>
                        <p class="ak23-portal-card-sub fs-12 mb-0">Your primary name and studio identity details.</p>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label fw-bold text-dark fs-12 mb-1">
                            @lang('First Name') <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-sm" name="firstname" value="{{ old('firstname', $user->firstname) }}" required placeholder="e.g. John">
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label fw-bold text-dark fs-12 mb-1">
                            @lang('Last Name') <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-sm" name="lastname" value="{{ old('lastname', $user->lastname) }}" required placeholder="e.g. Doe">
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label fw-bold text-dark fs-12 mb-1">@lang('Username')</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-muted">&#64;</span>
                            <input type="text" class="form-control form-control-sm bg-light" value="{{ $user->username }}" readonly disabled>
                        </div>
                        <div class="form-text fs-11 text-muted">Fixed account handle.</div>
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label fw-bold text-dark fs-12 mb-1">@lang('Email Address')</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-muted"><i class="las la-envelope"></i></span>
                            <input type="email" class="form-control form-control-sm bg-light" value="{{ $user->email }}" readonly disabled>
                        </div>
                        <div class="form-text fs-11 text-muted">Associated with your course license keys.</div>
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label fw-bold text-dark fs-12 mb-1">@lang('Country')</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-muted"><i class="las la-globe"></i></span>
                            <input type="text" class="form-control form-control-sm bg-light" value="{{ $countryVal ?: 'Tanzania' }}" readonly disabled>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label fw-bold text-dark fs-12 mb-1">@lang('Mobile Phone')</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-muted"><i class="las la-phone"></i></span>
                            <input type="text" class="form-control form-control-sm bg-light" value="{{ $user->mobile ? '+' . $user->dial_code . ' ' . $user->mobile : 'Not set' }}" readonly disabled>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Studio Location & Address -->
            <div class="ak23-portal-card mb-4">
                <div class="ak23-portal-card-header pb-3 mb-3 border-bottom">
                    <div>
                        <h2 class="ak23-portal-card-title fs-16 fw-bold">
                            <i class="las la-map-marker-alt text-warning me-1"></i> Studio Location &amp; Address
                        </h2>
                        <p class="ak23-portal-card-sub fs-12 mb-0">Used for software invoice receipts and studio delivery.</p>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold text-dark fs-12 mb-1">@lang('Street Address')</label>
                        <input type="text" class="form-control form-control-sm" name="address" value="{{ old('address', $addressVal) }}" placeholder="e.g. Studio 4B, Samora Avenue">
                    </div>

                    <div class="col-sm-4">
                        <label class="form-label fw-bold text-dark fs-12 mb-1">@lang('City')</label>
                        <input type="text" class="form-control form-control-sm" name="city" value="{{ old('city', $cityVal) }}" placeholder="e.g. Dar es Salaam">
                    </div>

                    <div class="col-sm-4">
                        <label class="form-label fw-bold text-dark fs-12 mb-1">@lang('State / Region')</label>
                        <input type="text" class="form-control form-control-sm" name="state" value="{{ old('state', $stateVal) }}" placeholder="e.g. Ilala">
                    </div>

                    <div class="col-sm-4">
                        <label class="form-label fw-bold text-dark fs-12 mb-1">@lang('Postal Code')</label>
                        <input type="text" class="form-control form-control-sm" name="zip" value="{{ old('zip', $zipVal) }}" placeholder="e.g. 11101">
                    </div>
                </div>
            </div>

            <!-- Card 3: Profile Photo & Studio Notes -->
            <div class="ak23-portal-card mb-4">
                <div class="ak23-portal-card-header pb-3 mb-3 border-bottom">
                    <div>
                        <h2 class="ak23-portal-card-title fs-16 fw-bold">
                            <i class="las la-camera text-warning me-1"></i> Avatar &amp; Studio Bio
                        </h2>
                        <p class="ak23-portal-card-sub fs-12 mb-0">Upload a student profile image and notes about your music production setup.</p>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold text-dark fs-12 mb-1">@lang('Upload New Profile Photo')</label>
                        <input type="file" class="form-control form-control-sm" name="image" id="avatarFileInput" accept=".jpg,.jpeg,.png,.webp">
                        <div class="form-text fs-11 text-muted">
                            Supported: <code>.jpg</code>, <code>.png</code>, <code>.webp</code> &middot; Maximum size: 2MB. Recommended: square ratio (e.g. 350x300px).
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold text-dark fs-12 mb-1">@lang('Studio Bio / Production Notes')</label>
                        <textarea class="form-control form-control-sm" name="bio" rows="3" placeholder="Tell us about your DAW environment (Cubase, FL Studio, Pro Tools) or vocal mixing experience...">{{ old('bio', $user->bio) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Action Footer -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                <a href="{{ route('user.home') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-4 py-2 fs-13">
                    <i class="las la-arrow-left me-1"></i> @lang('Back to Dashboard')
                </a>
                <button type="submit" class="btn btn-sm btn--base rounded-pill px-4 py-2 fs-13 fw-semibold shadow-sm">
                    <i class="las la-check-circle me-1"></i> @lang('Save Profile Changes')
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('script')
<script>
    document.getElementById('avatarFileInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                const img = document.getElementById('avatarPreviewImg');
                const placeholder = document.getElementById('avatarPlaceholder');
                if (img) {
                    img.src = evt.target.result;
                    img.classList.remove('d-none');
                }
                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
