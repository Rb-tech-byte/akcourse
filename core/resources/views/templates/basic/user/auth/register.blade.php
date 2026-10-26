@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @php
        $policyPages = getContent('policy_pages.element', false, null, true);
    @endphp

    <section class="ak-auth-wrapper py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-6 col-md-8 col-sm-10 col-12">
                    <div class="ak-simple-auth-card text-center">
                        <!-- Top Icon Badge -->
                        <div class="ak-auth-icon-circle mx-auto mb-3">
                            <i class="las la-user-plus"></i>
                        </div>

                        <!-- Title & Description (Matching akdownloads.com/register.php) -->
                        <h2 class="ak-auth-title mb-2">Join the {{ gs('site_name') }} community</h2>
                        <p class="ak-auth-subtext mb-4">
                            Create your account instantly with your Google profile. No long forms, no phone verification required upfront.
                        </p>

                        <!-- Primary Big Google Sign-Up Button -->
                        <a href="{{ route('user.social.login', 'google') }}" class="ak-google-btn mb-2">
                            <svg class="ak-google-icon" viewBox="0 0 48 48">
                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                            </svg>
                            <span>Create account with Google</span>
                        </a>

                        <p class="ak-google-caption text-muted mb-4">
                            Fast, secure, and one-click account creation.
                        </p>

                        <!-- Switch to Login Link -->
                        <div class="ak-auth-switch-box pt-3 border-top mb-3">
                            <span class="text-muted">Already have an account?</span>
                            <a href="{{ route('user.login') }}" class="ak-auth-link ms-1 fw-bold">Sign In</a>
                        </div>

                        <!-- Fallback / Expandable Email Registration Toggle -->
                        <div class="mb-3">
                            <button type="button" class="btn btn-link ak-toggle-email-btn" id="toggleEmailRegisterBtn">
                                <i class="las la-envelope me-1"></i> Or register with email
                            </button>
                        </div>

                        <!-- Expandable Manual Form (hidden by default) -->
                        <div id="manualEmailRegisterForm" class="d-none text-start pt-2">
                            <form method="POST" action="{{ route('user.register') }}" class="verify-gcaptcha">
                                @csrf
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label for="firstname" class="form-label fs-13 text-muted">First Name</label>
                                        <input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" class="form-control ak-custom-input" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="lastname" class="form-label fs-13 text-muted">Last Name</label>
                                        <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" class="form-control ak-custom-input" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label fs-13 text-muted">Email Address</label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control ak-custom-input" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label fs-13 text-muted">Password</label>
                                    <input type="password" id="password" name="password" class="form-control ak-custom-input" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label fs-13 text-muted">Confirm Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control ak-custom-input" required>
                                </div>

                                <x-captcha />

                                <button type="submit" class="btn btn-warning w-100 fw-bold py-2 mt-2">
                                    Create Account
                                </button>
                            </form>
                        </div>

                        <!-- WhatsApp Support Link (Matching akdownloads) -->
                        <div class="mt-4 pt-3 border-top text-center">
                            <a href="https://wa.me/255763312251?text=Hello!%20I%20need%20assistance%20on%20AK23DOWNLOADS." target="_blank" class="ak-whatsapp-help-btn">
                                <i class="lab la-whatsapp"></i> WhatsApp Help
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
<style>
/* ==========================================================================
   AKDOWNLOADS SIMPLIFIED AUTHENTICATION CARD
   ========================================================================== */
.ak-auth-wrapper {
    background-color: #0E0F13;
    min-height: calc(100vh - 160px);
    display: flex;
    align-items: center;
}
.ak-simple-auth-card {
    background: #14161E;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 18px;
    padding: 38px 30px;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.6);
}
.ak-auth-icon-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(251, 175, 67, 0.12);
    border: 1.5px solid rgba(251, 175, 67, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    color: #FBAF43;
}
.ak-auth-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 22px;
    font-weight: 800;
    color: #FFFFFF;
    letter-spacing: -0.01em;
}
.ak-auth-subtext {
    font-size: 13.5px;
    line-height: 1.55;
    color: #94A3B8;
}
.ak-google-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 13px 20px;
    color: #1E293B !important;
    font-size: 15px;
    font-weight: 700;
    text-decoration: none !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.2s ease;
    width: 100%;
}
.ak-google-btn:hover {
    background: #F8FAFC;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    color: #0F172A !important;
}
.ak-google-icon {
    width: 22px;
    height: 22px;
    flex-shrink: 0;
}
.ak-google-caption {
    font-size: 12px;
}
.ak-auth-link {
    color: #FBAF43;
    text-decoration: none;
    transition: color 0.15s ease;
}
.ak-auth-link:hover {
    color: #FFC978;
    text-decoration: underline;
}
.ak-toggle-email-btn {
    font-size: 12px;
    color: #64748B;
    text-decoration: none;
    padding: 0;
}
.ak-toggle-email-btn:hover {
    color: #94A3B8;
}
.ak-custom-input {
    background: #0A0B0E !important;
    border: 1px solid #23252E !important;
    border-radius: 9px !important;
    color: #FFFFFF !important;
    padding: 10px 14px !important;
    font-size: 14px !important;
}
.ak-custom-input:focus {
    border-color: #FBAF43 !important;
    box-shadow: 0 0 0 2px rgba(251, 175, 67, 0.2) !important;
}
.ak-whatsapp-help-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #10B981;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}
.ak-whatsapp-help-btn:hover {
    color: #34D399;
}
.ak-whatsapp-help-btn i {
    font-size: 18px;
}
</style>
@endpush

@push('script')
<script>
    (function($) {
        "use strict";
        $('#toggleEmailRegisterBtn').on('click', function(e) {
            e.preventDefault();
            $('#manualEmailRegisterForm').toggleClass('d-none');
        });
    })(jQuery);
</script>
@endpush
