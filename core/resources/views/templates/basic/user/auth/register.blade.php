@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @php
        $credentials = gs('socialite_credentials');
        $policyPages = getContent('policy_pages.element', false, null, true);
    @endphp

    @include($activeTemplate . 'user.auth.auth_style')

    <section class="ak23-auth-section">
        <!-- Ambient Studio Glows -->
        <div class="ak23-auth-glow ak23-auth-glow-green"></div>
        <div class="ak23-auth-glow-gold"></div>

        <div class="ak23-auth-container">
            <div class="ak23-auth-grid">
                <!-- Left Column: Studio Showcase -->
                <div class="ak23-auth-showcase">
                    <div class="ak23-auth-badge">
                        <span class="ak23-auth-badge-dot"></span>
                        <span>{{ gs('site_name') }} Academy &middot; Student Registration</span>
                    </div>

                    <h1 class="ak23-auth-hero-title">
                        Master Vocal Mixing <span>Like a Pro</span>.
                    </h1>

                    <p class="ak23-auth-hero-sub">
                        Create your student account to unlock full HD video archives, uncompressed multitrack stems, and production-ready DAW templates.
                    </p>

                    <div class="ak23-auth-features">
                        <!-- Feature 1 -->
                        <div class="ak23-auth-feature-item">
                            <div class="ak23-auth-feature-icon">
                                <i class="las la-sliders-h"></i>
                            </div>
                            <div class="ak23-auth-feature-content">
                                <h6>3 Major DAWs Supported</h6>
                                <p>Pre-configured mixing templates for Steinberg Cubase, FL Studio, and Avid Pro Tools.</p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="ak23-auth-feature-item">
                            <div class="ak23-auth-feature-icon gold">
                                <i class="las la-download"></i>
                            </div>
                            <div class="ak23-auth-feature-content">
                                <h6>Offline Studio Learning</h6>
                                <p>Download complete course modules and project files directly to your studio workstation without buffering.</p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="ak23-auth-feature-item">
                            <div class="ak23-auth-feature-icon blue">
                                <i class="las la-certificate"></i>
                            </div>
                            <div class="ak23-auth-feature-content">
                                <h6>Official Software Licenses</h6>
                                <p>Get genuine software licenses and installers delivered straight to your dedicated student portal.</p>
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
                            <strong>1,200+ music producers</strong> enrolled in {{ gs('site_name') }} audio engineering programs.
                        </div>
                    </div>
                </div>

                <!-- Right Column: Modern Registration Card -->
                <div>
                    <div class="ak23-auth-card">
                        <div class="ak23-auth-card-header">
                            <div class="ak23-auth-card-topmark">
                                <div class="ak23-auth-mark-icon">
                                    <img src="{{ siteLogo('dark') }}" alt="{{ gs('site_name') }}" style="height: 28px; max-width: 150px; object-fit: contain;">
                                </div>
                                <span class="ak23-auth-mark-label">Student Registration</span>
                            </div>
                            <h2 class="ak23-auth-card-title">Create Student Account</h2>
                            <p class="ak23-auth-card-sub">Fill in your information below to get instant access to studio kits.</p>
                        </div>

                        {{-- Referral Banner if present --}}
                        @if (session()->get('reference'))
                            @php
                                $referUser = App\Models\User::where('username', session()->get('reference'))->first();
                            @endphp
                            @if ($referUser)
                                <div class="ak23-referral-banner">
                                    <i class="las la-user-friends ak23-referral-icon"></i>
                                    <div class="ak23-referral-text">
                                        @lang('You were invited by') <strong>{{ $referUser->fullname }}</strong>
                                    </div>
                                </div>
                            @endif
                        @endif

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
                            <div class="ak23-social-divider">
                                <span>or register with email</span>
                            </div>
                        @endif

                        <form action="{{ route('user.register') }}" method="POST" class="ak23-auth-form verify-gcaptcha">
                            @csrf

                            <!-- Name Grid (First & Last) -->
                            <div class="ak23-form-grid-2">
                                <div class="ak23-form-group">
                                    <label for="firstname" class="ak23-form-label">
                                        <span>@lang('First Name') <span class="ak23-req-star">*</span></span>
                                    </label>
                                    <div class="ak23-input-wrapper">
                                        <i class="las la-user ak23-input-icon"></i>
                                        <input type="text" id="firstname" name="firstname" 
                                               value="{{ old('firstname', @$user->firstname) }}" 
                                               class="ak23-input" placeholder="e.g. Amina" required>
                                    </div>
                                </div>

                                <div class="ak23-form-group">
                                    <label for="lastname" class="ak23-form-label">
                                        <span>@lang('Last Name') <span class="ak23-req-star">*</span></span>
                                    </label>
                                    <div class="ak23-input-wrapper">
                                        <i class="las la-user ak23-input-icon"></i>
                                        <input type="text" id="lastname" name="lastname" 
                                               value="{{ old('lastname', @$user->lastname) }}" 
                                               class="ak23-input" placeholder="e.g. Mushi" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div class="ak23-form-group">
                                <label for="email" class="ak23-form-label">
                                    <span>@lang('E-Mail Address') <span class="ak23-req-star">*</span></span>
                                </label>
                                <div class="ak23-input-wrapper">
                                    <i class="las la-envelope ak23-input-icon"></i>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                                           class="ak23-input checkUser" placeholder="e.g. amina@example.com" required>
                                </div>
                            </div>

                            <!-- Password Grid -->
                            <div class="ak23-form-grid-2">
                                <div class="ak23-form-group">
                                    <label for="password" class="ak23-form-label">
                                        <span>@lang('Password') <span class="ak23-req-star">*</span></span>
                                    </label>
                                    <div class="ak23-input-wrapper">
                                        <i class="las la-lock ak23-input-icon"></i>
                                        <input type="password" id="password" name="password" 
                                               class="ak23-input ak23-input-pw" placeholder="••••••••" required>
                                        <button type="button" class="ak23-pw-toggle" aria-label="Toggle password visibility">
                                            <i class="las la-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="ak23-form-group">
                                    <label for="password_confirmation" class="ak23-form-label">
                                        <span>@lang('Confirm Password') <span class="ak23-req-star">*</span></span>
                                    </label>
                                    <div class="ak23-input-wrapper">
                                        <i class="las la-shield-alt ak23-input-icon"></i>
                                        <input type="password" id="password_confirmation" name="password_confirmation" 
                                               class="ak23-input ak23-input-pw" placeholder="••••••••" required>
                                        <button type="button" class="ak23-pw-toggle" aria-label="Toggle password visibility">
                                            <i class="las la-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Captcha -->
                            <x-captcha />

                            <!-- Agreement to Policies -->
                            @if (gs('agree'))
                                <div class="ak23-auth-meta-row">
                                    <label class="ak23-checkbox-label" for="agree">
                                        <input type="checkbox" id="agree" name="agree" class="ak23-checkbox" 
                                               @checked(old('agree')) required>
                                        <span class="ak23-policy-text">
                                            @lang('I agree with')
                                            @foreach ($policyPages as $policy)
                                                <a href="{{ route('policy.pages', $policy->slug) }}" target="_blank">
                                                    {{ __($policy->data_values->title) }}
                                                </a>@if (!$loop->last), @endif
                                            @endforeach
                                        </span>
                                    </label>
                                </div>
                            @endif

                            <!-- Submit Button -->
                            <button type="submit" class="ak23-auth-submit-btn" id="recaptcha">
                                <span>@lang('Create Student Account')</span>
                                <i class="las la-arrow-right"></i>
                            </button>

                            <!-- Switch to Login -->
                            <div class="ak23-auth-footer">
                                <p class="ak23-auth-switch-text">
                                    @lang('Already have a student account?')
                                    <a href="{{ route('user.login') }}" class="ak23-auth-switch-link">
                                        @lang('Sign In here')
                                    </a>
                                </p>
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

    <!-- Modal for Existing Account -->
    <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-18 fw-bold text-white mb-0">
                        <i class="las la-user-check text-success me-2"></i>@lang('Account Already Exists')
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <p class="text-center mb-0 fs-15 text-white-50">
                        @lang('An account with this email address already exists in Maarifa23 Academy.')
                    </p>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-dark btn-sm px-3" data-bs-dismiss="modal">@lang('Close')</button>
                    <a href="{{ route('user.login') }}" class="btn btn-success btn-sm px-4 fw-bold">@lang('Sign In Now')</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            // Check if user already exists via AJAX
            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';

                if (!value) return;

                var data = {
                    email: value,
                    _token: token
                };

                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $('#existModalCenter').modal('show');
                    }
                });
            });

            // Password show/hide toggle
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
