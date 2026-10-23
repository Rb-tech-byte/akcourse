@extends('admin.layouts.master')

@section('content')
    @include('admin.auth.auth_style')

    <div class="ak-admin-auth-wrapper">
        <!-- Ambient Audio Studio Glows -->
        <div class="ak-admin-glow ak-admin-glow-green"></div>
        <div class="ak-admin-glow ak-admin-glow-gold"></div>

        <div class="ak-admin-container ak-admin-single-center">
            <div class="ak-admin-card">
                <div class="ak-admin-card-header text-center">
                    <div class="ak-admin-topmark justify-content-center">
                        <div class="ak-admin-logo-mark">AK</div>
                        <span class="ak-admin-topmark-label">Account Recovery</span>
                    </div>
                    <h2 class="ak-admin-card-title">@lang('Reset Admin Password')</h2>
                    <p class="ak-admin-card-sub">@lang('Enter your registered admin email address and we will send you a password recovery code.')</p>
                </div>

                <form action="{{ route('admin.password.reset') }}" method="POST" class="ak-admin-form verify-gcaptcha">
                    @csrf

                    <!-- Email Input -->
                    <div class="ak-admin-form-group">
                        <label for="admin-email" class="ak-admin-label">
                            @lang('Email Address') <span class="ak-admin-req-star">*</span>
                        </label>
                        <div class="ak-admin-input-wrapper">
                            <i class="las la-envelope ak-admin-input-icon"></i>
                            <input type="email" id="admin-email" name="email" 
                                   value="{{ old('email') }}" 
                                   class="ak-admin-input" 
                                   placeholder="e.g. admin@akcourse.com" 
                                   required autofocus>
                        </div>
                    </div>

                    <!-- Captcha -->
                    <x-captcha />

                    <!-- Submit Button -->
                    <button type="submit" class="ak-admin-submit-btn" id="recaptcha">
                        <span>@lang('Send Password Reset Code')</span>
                        <i class="las la-paper-plane"></i>
                    </button>

                    <!-- Back to Login -->
                    <div class="ak-admin-card-footer">
                        <a href="{{ route('admin.login') }}" class="ak-admin-back-link">
                            <i class="las la-arrow-left"></i>
                            <span>@lang('Back to Admin Sign In')</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
