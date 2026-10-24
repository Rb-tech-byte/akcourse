@extends('admin.layouts.master')

@section('content')
    @include('admin.auth.auth_style')

    <div class="ak-admin-auth-wrapper">
        <!-- Ambient Audio Studio Glows -->
        <div class="ak-admin-glow ak-admin-glow-green"></div>
        <div class="ak-admin-glow ak-admin-glow-gold"></div>

        <div class="ak-admin-container">
            <div class="ak-admin-grid">
                <!-- Left Column: Studio Command Showcase -->
                <div class="ak-admin-showcase">
                    <div class="ak-admin-badge">
                        <span class="ak-admin-badge-dot"></span>
                        <span>{{ gs('site_name') }} Academy &middot; Master Control</span>
                    </div>

                    <h1 class="ak-admin-hero-title">
                        Studio Academy <span>Control &amp; Operations</span>.
                    </h1>

                    <p class="ak-admin-hero-sub">
                        Secure administrative command center to manage audio mixing courses, distribute software licenses, supervise multitrack DAW stems, and monitor student analytics.
                    </p>

                    <div class="ak-admin-features">
                        <!-- Feature 1 -->
                        <div class="ak-admin-feature-card">
                            <div class="ak-admin-feature-icon">
                                <i class="las la-sliders-h"></i>
                            </div>
                            <div class="ak-admin-feature-content">
                                <h6>Course &amp; Multitrack Management</h6>
                                <p>Oversee Steinberg Cubase, FL Studio, and Pro Tools mixing courses, project templates, and high-bitrate video lessons.</p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="ak-admin-feature-card">
                            <div class="ak-admin-feature-icon gold">
                                <i class="las la-key"></i>
                            </div>
                            <div class="ak-admin-feature-content">
                                <h6>Software License Dispatcher</h6>
                                <p>Automated software license delivery, offline installer management, and student workstation verification.</p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="ak-admin-feature-card">
                            <div class="ak-admin-feature-icon blue">
                                <i class="las la-chart-line"></i>
                            </div>
                            <div class="ak-admin-feature-content">
                                <h6>Enrollment &amp; Financial Analytics</h6>
                                <p>Real-time order tracking, mobile carrier payments, student progress reports, and studio operational logs.</p>
                            </div>
                        </div>
                    </div>

                    <div class="ak-admin-status-strip">
                        <div class="ak-admin-eq-bars">
                            <span class="ak-admin-eq-bar"></span>
                            <span class="ak-admin-eq-bar"></span>
                            <span class="ak-admin-eq-bar"></span>
                            <span class="ak-admin-eq-bar"></span>
                            <span class="ak-admin-eq-bar"></span>
                        </div>
                        <div class="ak-admin-status-text">
                            <strong>Platform Core Online</strong> &middot; {{ gs('site_name') }} Media &amp; License Cloud Active
                        </div>
                    </div>
                </div>

                <!-- Right Column: Admin Authentication Card -->
                <div>
                    <div class="ak-admin-card">
                        <div class="ak-admin-card-header">
                            <div class="ak-admin-topmark">
                                <div class="ak-admin-logo-mark">
                                    <img src="{{ siteLogo('dark') }}" alt="{{ gs('site_name') }}" style="height: 28px; max-width: 150px; object-fit: contain;">
                                </div>
                                <span class="ak-admin-topmark-label">Master Administration</span>
                            </div>
                            <h2 class="ak-admin-card-title">@lang('Administrator Sign In')</h2>
                            <p class="ak-admin-card-sub">@lang('Enter your credentials to access the ' . gs('site_name') . ' command center.')</p>
                        </div>

                        <div class="ak-admin-security-pill">
                            <i class="las la-shield-alt"></i>
                            <span>Authorized personnel only &middot; Encrypted session</span>
                        </div>

                        <form action="{{ route('admin.login') }}" method="POST" class="ak-admin-form verify-gcaptcha">
                            @csrf

                            <!-- Username Input -->
                            <div class="ak-admin-form-group">
                                <label for="admin-username" class="ak-admin-label">
                                    @lang('Username') <span class="ak-admin-req-star">*</span>
                                </label>
                                <div class="ak-admin-input-wrapper">
                                    <i class="las la-user-shield ak-admin-input-icon"></i>
                                    <input type="text" id="admin-username" name="username" 
                                           value="{{ old('username') }}" 
                                           class="ak-admin-input" 
                                           placeholder="e.g. admin" 
                                           required autofocus>
                                </div>
                            </div>

                            <!-- Password Input -->
                            <div class="ak-admin-form-group">
                                <div class="ak-admin-label-row">
                                    <label for="admin-password" class="ak-admin-label">
                                        @lang('Password') <span class="ak-admin-req-star">*</span>
                                    </label>
                                    <a href="{{ route('admin.password.reset') }}" class="ak-admin-forgot-link">
                                        @lang('Forgot Password?')
                                    </a>
                                </div>
                                <div class="ak-admin-input-wrapper">
                                    <i class="las la-lock ak-admin-input-icon"></i>
                                    <input type="password" id="admin-password" name="password" 
                                           class="ak-admin-input ak-admin-password-field" 
                                           placeholder="••••••••" 
                                           required>
                                    <button type="button" class="ak-admin-pw-toggle" aria-label="Toggle password visibility">
                                        <i class="las la-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Captcha -->
                            <x-captcha />

                            <!-- Submit Button -->
                            <button type="submit" class="ak-admin-submit-btn" id="recaptcha">
                                <span>@lang('Authenticate & Enter Dashboard')</span>
                                <i class="las la-arrow-right"></i>
                            </button>

                            <!-- Footer / Back to site -->
                            <div class="ak-admin-card-footer">
                                <a href="{{ route('home') }}" class="ak-admin-back-link">
                                    <i class="las la-arrow-left"></i>
                                    <span>@lang('Return to AK23 Storefront')</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            // Password show/hide toggle
            $('.ak-admin-pw-toggle').on('click', function(e) {
                e.preventDefault();
                const $btn = $(this);
                const $input = $btn.siblings('.ak-admin-password-field');
                const $icon = $btn.find('i');
                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $icon.removeClass('la-eye').addClass('la-eye-slash');
                } else {
                    $input.attr('type', 'password');
                    $icon.removeClass('la-eye-slash').addClass('la-eye');
                }
            });
        })(jQuery);
    </script>
@endpush
