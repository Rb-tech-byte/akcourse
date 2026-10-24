@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @php
        $credentials = gs('socialite_credentials');
    @endphp

    @include($activeTemplate . 'user.auth.auth_style')

    <section class="ak23-auth-section">
        <!-- Ambient Studio Glows -->
        <div class="ak23-auth-glow ak23-auth-glow-gold"></div>

        <div class="ak23-auth-container">
            <div class="ak23-auth-grid">
                <!-- Studio Showcase (Desktop: Left | Mobile: Bottom) -->
                <div class="ak23-auth-showcase">
                    <div class="ak23-auth-badge">
                        <span class="ak23-auth-badge-dot"></span>
                        <span>{{ gs('site_name') }} Academy &middot; Student Portal</span>
                    </div>

                    <h1 class="ak23-auth-hero-title">
                        Access Your <span>Studio Kits</span> &amp; Vocal Courses.
                    </h1>

                    <p class="ak23-auth-hero-sub">
                        Log in to download complete multitrack vocal stems, DAW mixing templates, and high-bitrate offline video lessons directly to your studio rig.
                    </p>

                    <div class="ak23-auth-features">
                        <!-- Feature 1 -->
                        <div class="ak23-auth-feature-item">
                            <div class="ak23-auth-feature-icon">
                                <i class="las la-compact-disc"></i>
                            </div>
                            <div class="ak23-auth-feature-content">
                                <h6>Complete Offline Studio Kits</h6>
                                <p>Raw uncompressed WAV stems, project files, and preset chains for Cubase, FL Studio, and Pro Tools.</p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="ak23-auth-feature-item">
                            <div class="ak23-auth-feature-icon gold">
                                <i class="las la-key"></i>
                            </div>
                            <div class="ak23-auth-feature-content">
                                <h6>Software Licenses &amp; Installers</h6>
                                <p>Offline software packages, license deliveries, and step-by-step studio installation guides.</p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="ak23-auth-feature-item">
                            <div class="ak23-auth-feature-icon blue">
                                <i class="las la-bolt"></i>
                            </div>
                            <div class="ak23-auth-feature-content">
                                <h6>Instant Direct Downloads</h6>
                                <p>Uncapped high-speed downloads designed for local offline studio learning without buffering.</p>
                            </div>
                        </div>
                    </div>

                    <div class="ak23-auth-eq-strip">
                        <div class="ak23-eq-bars">
                            <span class="ak23-eq-bar"></span>
                            <span class="ak23-eq-bar"></span>
                            <span class="ak23-eq-bar"></span>
                            <span class="ak23-eq-bar"></span>
                            <span class="ak23-eq-bar"></span>
                        </div>
                        <div class="ak23-auth-proof-text">
                            <strong>1,200+ music producers</strong> mastering vocal mixing across East Africa.
                        </div>
                    </div>
                </div>

                <!-- Clean Responsive Auth Card (Desktop: Right | Mobile: Top) -->
                <div class="ak23-auth-card-col">
                    <div class="ak23-auth-card">
                        <div class="ak23-auth-card-header">
                            <div class="ak23-auth-card-badge">
                                <i class="las la-shield-alt"></i>
                                <span>@lang('Student Authentication')</span>
                            </div>
                            <h2 class="ak23-auth-card-title">@lang('Welcome Back')</h2>
                            <p class="ak23-auth-card-sub">@lang('Enter your credentials to access your courses, kits and downloads.')</p>
                        </div>

                        {{-- Social Login (if enabled) --}}
                        @if ($credentials && ($credentials->google->status == Status::ENABLE || $credentials->facebook->status == Status::ENABLE || $credentials->linkedin->status == Status::ENABLE))
                            <div class="ak23-social-grid">
                                @if ($credentials->google->status == Status::ENABLE)
                                    <a href="{{ route('user.social.login', 'google') }}" class="ak23-social-btn">
                                        <i class="lab la-google" style="color: #EA4335;"></i>
                                        <span>Google</span>
                                    </a>
                                @endif
                                @if ($credentials->facebook->status == Status::ENABLE)
                                    <a href="{{ route('user.social.login', 'facebook') }}" class="ak23-social-btn">
                                        <i class="lab la-facebook-f" style="color: #1877F2;"></i>
                                        <span>Facebook</span>
                                    </a>
                                @endif
                                @if ($credentials->linkedin->status == Status::ENABLE)
                                    <a href="{{ route('user.social.login', 'linkedin') }}" class="ak23-social-btn">
                                        <i class="lab la-linkedin-in" style="color: #0A66C2;"></i>
                                        <span>LinkedIn</span>
                                    </a>
                                @endif
                            </div>
                            <div class="ak23-auth-divider">
                                <span>@lang('or continue with email')</span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('user.login') }}" class="ak23-auth-form verify-gcaptcha">
                            @csrf

                            <!-- Username or Email -->
                            <div class="ak23-form-group">
                                <label for="username" class="ak23-form-label">
                                    <span>@lang('Username or Email')</span>
                                    <span class="ak23-req-star">*</span>
                                </label>
                                <div class="ak23-input-wrapper">
                                    <i class="las la-user ak23-input-icon"></i>
                                    <input type="text" id="username" name="username" value="{{ old('username') }}" 
                                           class="ak23-input" placeholder="e.g. producer@example.com or username" 
                                           required autofocus autocomplete="username">
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="ak23-form-group">
                                <div class="ak23-form-label-row">
                                    <label for="password" class="ak23-form-label">
                                        <span>@lang('Password')</span>
                                        <span class="ak23-req-star">*</span>
                                    </label>
                                    <a href="{{ route('user.password.request') }}" class="ak23-auth-forgot">
                                        @lang('Forgot password?')
                                    </a>
                                </div>
                                <div class="ak23-input-wrapper">
                                    <i class="las la-lock ak23-input-icon"></i>
                                    <input type="password" id="password" name="password" 
                                           class="ak23-input ak23-input-pw" placeholder="••••••••" 
                                           required autocomplete="current-password">
                                    <button type="button" class="ak23-pw-toggle" aria-label="Toggle password visibility">
                                        <i class="las la-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Captcha & Remember Row -->
                            <x-captcha />

                            <div class="ak23-auth-meta-row">
                                <label class="ak23-checkbox-label" for="remember">
                                    <input type="checkbox" name="remember" id="remember" class="ak23-checkbox" 
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <span>@lang('Remember me on this studio device')</span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="ak23-auth-submit-btn" id="recaptcha">
                                <span>@lang('Sign In to Account')</span>
                                <i class="las la-arrow-right"></i>
                            </button>

                            <!-- Switch to Register -->
                            <div class="ak23-auth-footer">
                                <div class="ak23-auth-switch-text">
                                    <span>@lang('New to Maarifa23?')</span>
                                    <a href="{{ route('user.register') }}" class="ak23-auth-switch-link">
                                        @lang('Create a student account')
                                    </a>
                                </div>
                                <a href="{{ route('products') }}" class="ak23-auth-back-link">
                                    <i class="las la-arrow-left"></i>
                                    <span>@lang('Browse Studio Catalog')</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            // Password toggle handler
            $('.ak23-pw-toggle').on('click', function(e) {
                e.preventDefault();
                const $btn = $(this);
                const $input = $btn.siblings('.ak23-input');
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
