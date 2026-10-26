@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <section class="ak-auth-wrapper py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-6 col-md-8 col-sm-10 col-12">
                    <div class="ak-simple-auth-card text-center">
                        <!-- Top Icon Badge -->
                        <div class="ak-auth-icon-circle mx-auto mb-3">
                            <i class="las la-user-plus"></i>
                        </div>

                        <!-- Title & Description -->
                        <h2 class="ak-auth-title mb-2">@lang('Join') {{ gs('site_name') }}</h2>
                        <p class="ak-auth-subtext mb-4">
                            @lang('Create your student account in one click with Google. Instant verification, no lengthy registration forms or passwords required.')
                        </p>

                        <!-- Primary Big Google Sign-Up Button -->
                        <a href="{{ route('user.social.login', 'google') }}" class="ak-google-btn mb-3" id="googleRegisterBtn">
                            <svg class="ak-google-icon" viewBox="0 0 48 48">
                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                            </svg>
                            <span>@lang('Create Account with Google')</span>
                        </a>

                        <!-- Security / Speed Benefit Badges -->
                        <div class="ak-auth-benefits mb-4">
                            <div class="ak-benefit-item">
                                <i class="las la-bolt text-warning"></i>
                                <span>@lang('Instant Activation')</span>
                            </div>
                            <div class="ak-benefit-item">
                                <i class="las la-shield-alt text-success"></i>
                                <span>@lang('Google Verified')</span>
                            </div>
                            <div class="ak-benefit-item">
                                <i class="las la-key text-info"></i>
                                <span>@lang('No Passwords')</span>
                            </div>
                        </div>

                        <!-- Switch to Login Link -->
                        <div class="ak-auth-switch-box pt-3 border-top border-secondary border-opacity-10 mb-4">
                            <span class="text-muted fs-13">@lang('Already have an account?')</span>
                            <a href="{{ route('user.login') }}" class="ak-auth-link ms-1 fw-bold fs-13">@lang('Sign In')</a>
                        </div>

                        <!-- WhatsApp Support Link -->
                        <div class="pt-3 border-top border-secondary border-opacity-10 text-center">
                            <a href="https://wa.me/255763312251?text=Hello!%20I%20need%20assistance%20on%20{{ urlencode(gs('site_name')) }}." target="_blank" class="ak-whatsapp-help-btn">
                                <i class="lab la-whatsapp"></i> @lang('Need assistance? WhatsApp Support')
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
   GOOGLE-ONLY AUTHENTICATION CARD
   ========================================================================== */
.ak-auth-wrapper {
    background: radial-gradient(circle at 50% 20%, rgba(251, 175, 67, 0.05) 0%, #0E0F13 70%);
    min-height: calc(100vh - 160px);
    display: flex;
    align-items: center;
}
.ak-simple-auth-card {
    background: #14161E;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    padding: 42px 32px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.65);
}
.ak-auth-icon-circle {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: rgba(251, 175, 67, 0.12);
    border: 1.5px solid rgba(251, 175, 67, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #FBAF43;
}
.ak-auth-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 23px;
    font-weight: 800;
    color: #FFFFFF;
    letter-spacing: -0.01em;
}
.ak-auth-subtext {
    font-size: 13.5px;
    line-height: 1.6;
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
    padding: 14px 22px;
    color: #1E293B !important;
    font-size: 15.5px;
    font-weight: 700;
    text-decoration: none !important;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
    transition: all 0.2s ease;
    width: 100%;
}
.ak-google-btn:hover {
    background: #F8FAFC;
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.35);
    color: #0F172A !important;
}
.ak-google-icon {
    width: 24px;
    height: 24px;
    flex-shrink: 0;
}
.ak-auth-benefits {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    flex-wrap: wrap;
    padding: 10px 0;
}
.ak-benefit-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #94A3B8;
    background: rgba(255, 255, 255, 0.04);
    padding: 5px 11px;
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.05);
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
