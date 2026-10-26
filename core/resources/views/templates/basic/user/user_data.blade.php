@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @php
        $user = auth()->user();
        $policyPages = getContent('policy_pages.element', false, null, true);
    @endphp

    @include($activeTemplate . 'user.auth.auth_style')

    <section class="ak23-auth-section">
        <!-- Ambient Studio Lighting -->
        <div class="ak23-auth-glow ak23-auth-glow-gold"></div>

        <div class="ak23-auth-container">
            <div class="ak23-auth-grid">
                <!-- Studio Showcase Column (Desktop: Left | Mobile: Bottom) -->
                <div class="ak23-auth-showcase">
                    <div class="ak23-auth-badge">
                        <span class="ak23-auth-badge-dot"></span>
                        <span>{{ gs('site_name') }} Academy &middot; Step 2 of 2</span>
                    </div>

                    <h1 class="ak23-auth-hero-title">
                        Complete Your <span>Student Profile</span>.
                    </h1>

                    <p class="ak23-auth-hero-sub">
                        Welcome, <strong>{{ $user->fullname }}</strong>! Finalize your student credentials to unlock direct DAW stems, multitrack sessions, video masterclasses, and verified completion certificates.
                    </p>

                    <div class="ak23-auth-features">
                        <!-- Feature 1: Tanzania Optimized -->
                        <div class="ak23-auth-feature-item">
                            <div class="ak23-auth-feature-icon gold">
                                <i class="las la-globe-africa"></i>
                            </div>
                            <div class="ak23-auth-feature-content">
                                <h6>Tanzania Mobile &amp; M-Pesa Ready</h6>
                                <p>Pre-configured with Tanzania (+255) dial code for seamless SMS updates and instant mobile checkout reconciliation.</p>
                            </div>
                        </div>

                        <!-- Feature 2: Auto-Feed from Storage -->
                        <div class="ak23-auth-feature-item">
                            <div class="ak23-auth-feature-icon">
                                <i class="las la-bolt"></i>
                            </div>
                            <div class="ak23-auth-feature-content">
                                <h6>Auto-Feed Browser Storage</h6>
                                <p>Your device remembers details across sessions with 1-click auto-fill, eliminating repetitive manual typing.</p>
                            </div>
                        </div>

                        <!-- Feature 3: Verified Student Credentials -->
                        <div class="ak23-auth-feature-item">
                            <div class="ak23-auth-feature-icon blue">
                                <i class="las la-certificate"></i>
                            </div>
                            <div class="ak23-auth-feature-content">
                                <h6>Official Student ID &amp; Certificate</h6>
                                <p>Your unique username and contact details are linked directly to your course progression and digital certificates.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Audio Studio Status -->
                    <div class="ak23-auth-eq-strip">
                        <div class="ak23-eq-bars">
                            <span class="ak23-eq-bar"></span>
                            <span class="ak23-eq-bar"></span>
                            <span class="ak23-eq-bar"></span>
                            <span class="ak23-eq-bar"></span>
                            <span class="ak23-eq-bar"></span>
                        </div>
                        <div class="ak23-auth-proof-text">
                            Signed in as <strong>{{ $user->email }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Auth Form Card Column -->
                <div class="ak23-auth-card-col">
                    <div class="ak23-auth-card">
                        <!-- Card Header -->
                        <div class="ak23-auth-card-header">
                            <div class="ak23-auth-card-badge">
                                <i class="las la-user-shield"></i>
                                <span>Account Setup</span>
                            </div>
                            <h2 class="ak23-auth-card-title">@lang('Profile Details')</h2>
                            <p class="ak23-auth-card-sub">
                                @lang('Provide your contact and location details below or use 1-click auto-feed.')
                            </p>
                        </div>

                        <!-- ⚡ Auto-Feed & Quick-Fill Toolbar -->
                        <div class="ak23-feed-toolbar">
                            <div class="ak23-feed-status-row">
                                <span class="ak23-feed-pill" id="storageStatusBadge">
                                    <span class="ak23-feed-dot"></span>
                                    <span id="storageStatusText">@lang('Checking saved browser storage...')</span>
                                </span>
                                <button type="button" class="ak23-feed-clear-btn d-none" id="btn-clear-storage" title="@lang('Clear saved profile from browser')">
                                    <i class="las la-trash-alt"></i> @lang('Clear Saved')
                                </button>
                            </div>

                            <div class="ak23-feed-btn-grid">
                                <button type="button" class="ak23-feed-btn ak23-feed-btn-primary" id="btn-autofeed-storage">
                                    <i class="las la-history"></i>
                                    <span>@lang('⚡ Auto-Feed Saved Details')</span>
                                </button>
                                <button type="button" class="ak23-feed-btn ak23-feed-btn-tz" id="btn-quickfill-tz">
                                    <span>🇹🇿 @lang('Quick-Fill Tanzania Preset')</span>
                                </button>
                            </div>
                        </div>

                        <!-- Profile Form -->
                        <form method="POST" action="{{ route('user.data.submit') }}" id="userDataForm" class="ak23-auth-form">
                            @csrf

                            <!-- Username Field -->
                            <div class="ak23-form-group">
                                <div class="ak23-form-label-row">
                                    <label class="ak23-form-label" for="username">
                                        @lang('Username') <span class="ak23-req-star">*</span>
                                    </label>
                                    <button type="button" class="ak23-suggest-link" id="btn-random-username" title="@lang('Cycle suggested unique usernames')">
                                        <i class="las la-sync-alt"></i> @lang('Suggest Another')
                                    </button>
                                </div>
                                <div class="ak23-input-wrapper">
                                    <i class="las la-at ak23-input-icon"></i>
                                    <input type="text"
                                           class="ak23-input checkUser"
                                           id="username"
                                           name="username"
                                           value="{{ old('username', $user->username ?? $suggestedUsername) }}"
                                           placeholder="e.g. john_doe23"
                                           minlength="6"
                                           autocomplete="username"
                                           required>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <small class="ak23-field-hint">@lang('At least 6 characters (small letters, numbers &amp; underscore)')</small>
                                    <small class="usernameExist"></small>
                                </div>
                            </div>

                            <!-- Country & Mobile Row -->
                            <div class="row gx-3">
                                <!-- Country Field (Default Tanzania) -->
                                <div class="col-sm-6 ak23-form-group">
                                    <label class="ak23-form-label" for="country">
                                        @lang('Country') <span class="ak23-req-star">*</span>
                                    </label>
                                    <div class="ak23-input-wrapper ak23-select-wrapper">
                                        <i class="las la-flag ak23-input-icon"></i>
                                        <select name="country" id="country" class="ak23-input select2" autocomplete="country-name" required>
                                            @foreach ($countries as $key => $country)
                                                <option @selected($country->country == old('country', $user->country_name ?? $defaultCountry ?? 'Tanzania') || $key == old('country_code', $user->country_code ?? $mobileCode ?? 'TZ'))
                                                        data-mobile_code="{{ $country->dial_code }}"
                                                        value="{{ $country->country }}"
                                                        data-code="{{ $key }}">
                                                    {{ __($country->country) }} (+{{ $country->dial_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Mobile Field (Auto-Prefix) -->
                                <div class="col-sm-6 ak23-form-group">
                                    <label class="ak23-form-label" for="mobile">
                                        @lang('Mobile Number') <span class="ak23-req-star">*</span>
                                    </label>
                                    <div class="ak23-input-group">
                                        <span class="ak23-input-group-badge mobile-code">+{{ $defaultDialCode ?? '255' }}</span>
                                        <input type="hidden" name="mobile_code" value="{{ old('mobile_code', $defaultDialCode ?? '255') }}">
                                        <input type="hidden" name="country_code" value="{{ old('country_code', $defaultCountryCode ?? 'TZ') }}">
                                        <input type="tel"
                                               class="ak23-input ak23-input-attached checkUser"
                                               id="mobile"
                                               name="mobile"
                                               value="{{ old('mobile', $user->mobile) }}"
                                               placeholder="712 345 678"
                                               autocomplete="tel-national"
                                               required>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="ak23-field-hint">@lang('Without country code (e.g. 7XXXXXXXX)')</small>
                                        <small class="mobileExist"></small>
                                    </div>
                                </div>
                            </div>

                            <!-- Address & City Row -->
                            <div class="row gx-3">
                                <div class="col-sm-6 ak23-form-group">
                                    <label class="ak23-form-label" for="address">
                                        @lang('Street Address')
                                    </label>
                                    <div class="ak23-input-wrapper">
                                        <i class="las la-map-marker ak23-input-icon"></i>
                                        <input type="text"
                                               class="ak23-input"
                                               id="address"
                                               name="address"
                                               value="{{ old('address', $user->address) }}"
                                               placeholder="e.g. Kinondoni / Posta"
                                               autocomplete="street-address">
                                    </div>
                                </div>

                                <div class="col-sm-6 ak23-form-group">
                                    <label class="ak23-form-label" for="city">
                                        @lang('City')
                                    </label>
                                    <div class="ak23-input-wrapper">
                                        <i class="las la-city ak23-input-icon"></i>
                                        <input type="text"
                                               class="ak23-input"
                                               id="city"
                                               name="city"
                                               value="{{ old('city', $user->city) }}"
                                               placeholder="e.g. Dar es Salaam"
                                               autocomplete="address-level2">
                                    </div>
                                </div>
                            </div>

                            <!-- State & Zip Row -->
                            <div class="row gx-3">
                                <div class="col-sm-6 ak23-form-group">
                                    <label class="ak23-form-label" for="state">
                                        @lang('State / Region')
                                    </label>
                                    <div class="ak23-input-wrapper">
                                        <i class="las la-map ak23-input-icon"></i>
                                        <input type="text"
                                               class="ak23-input"
                                               id="state"
                                               name="state"
                                               value="{{ old('state', $user->state) }}"
                                               placeholder="e.g. Dar es Salaam"
                                               autocomplete="address-level1">
                                    </div>
                                </div>

                                <div class="col-sm-6 ak23-form-group">
                                    <label class="ak23-form-label" for="zip">
                                        @lang('Postal / Zip Code')
                                    </label>
                                    <div class="ak23-input-wrapper">
                                        <i class="las la-mail-bulk ak23-input-icon"></i>
                                        <input type="text"
                                               class="ak23-input"
                                               id="zip"
                                               name="zip"
                                               value="{{ old('zip', $user->zip) }}"
                                               placeholder="e.g. 11101"
                                               autocomplete="postal-code">
                                    </div>
                                </div>
                            </div>

                            <!-- Auto-save persistence toggle -->
                            <div class="ak23-feed-save-toggle">
                                <label class="ak23-checkbox-label">
                                    <input type="checkbox" id="rememberDetails" checked>
                                    <span class="ak23-checkbox-custom"></span>
                                    <span class="ak23-checkbox-text">
                                        @lang('Remember my details in this browser storage for faster future checkout')
                                    </span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="ak23-auth-submit-btn mt-3" id="btnSubmitProfile">
                                <span>@lang('Complete Setup &amp; Enter Dashboard')</span>
                                <i class="las la-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Floating Auto-Feed Toast Notification -->
    <div id="ak23-feed-toast" class="ak23-feed-toast"></div>
@endsection

@push('style')
    <style>
        /* ==========================================================================
           AK23 AUTO-FEED & USER DATA PROFILE EXTENSIONS
           ========================================================================== */
        .ak23-feed-toolbar {
            background: rgba(22, 23, 30, 0.75);
            border: 1px solid rgba(251, 175, 67, 0.2);
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 22px;
            backdrop-filter: blur(8px);
        }

        .ak23-feed-status-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            gap: 10px;
        }

        .ak23-feed-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 12px;
            font-weight: 600;
            color: #94A3B8;
        }

        .ak23-feed-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #FBAF43;
            box-shadow: 0 0 8px rgba(251, 175, 67, 0.7);
            flex-shrink: 0;
        }

        .ak23-feed-dot.active {
            background-color: #10B981;
            box-shadow: 0 0 8px rgba(16, 185, 129, 0.7);
        }

        .ak23-feed-clear-btn {
            background: transparent;
            border: none;
            color: #EF4444;
            font-size: 11.5px;
            font-weight: 600;
            padding: 0;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .ak23-feed-clear-btn:hover {
            color: #F87171;
            text-decoration: underline;
        }

        .ak23-feed-btn-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        @media (max-width: 575px) {
            .ak23-feed-btn-grid {
                grid-template-columns: 1fr;
            }
        }

        .ak23-feed-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 12px;
            border-radius: 10px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            text-align: center;
        }

        .ak23-feed-btn-primary {
            background: rgba(251, 175, 67, 0.12);
            border: 1px solid rgba(251, 175, 67, 0.35);
            color: #FBAF43;
        }

        .ak23-feed-btn-primary:hover,
        .ak23-feed-btn-primary.ak23-has-data {
            background: linear-gradient(135deg, rgba(251, 175, 67, 0.22) 0%, rgba(226, 152, 40, 0.18) 100%);
            border-color: #FBAF43;
            color: #FFFFFF;
            transform: translateY(-1px);
        }

        .ak23-feed-btn-tz {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #E2E8F0;
        }

        .ak23-feed-btn-tz:hover {
            background: rgba(45, 55, 72, 0.9);
            border-color: rgba(251, 175, 67, 0.4);
            color: #FFFFFF;
            transform: translateY(-1px);
        }

        /* Suggest Link */
        .ak23-suggest-link {
            background: transparent;
            border: none;
            color: #FBAF43;
            font-size: 11.5px;
            font-weight: 700;
            padding: 0;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s ease;
        }

        .ak23-suggest-link:hover {
            color: #FFC978;
            transform: scale(1.03);
        }

        .ak23-field-hint {
            color: #64748B;
            font-size: 11.5px;
        }

        /* Select2 Dark Integration */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            background: #0E0F14 !important;
            border: 1px solid #23252E !important;
            border-radius: 11px !important;
            height: 48px !important;
            display: flex !important;
            align-items: center !important;
            padding-left: 42px !important;
            padding-right: 14px !important;
            transition: all 0.2s ease !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #FFFFFF !important;
            font-size: 15px !important;
            line-height: 48px !important;
            padding-left: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 48px !important;
            right: 12px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #64748B transparent transparent transparent !important;
        }

        .select2-container--default.select2-container--open .select2-selection--single,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #FBAF43 !important;
            box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.18) !important;
            background: #13141A !important;
            outline: none !important;
        }

        .select2-dropdown {
            background: #15161C !important;
            border: 1px solid #2A2C38 !important;
            border-radius: 12px !important;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.8) !important;
            z-index: 99999 !important;
        }

        .select2-search--dropdown {
            padding: 8px !important;
        }

        .select2-search--dropdown .select2-search__field {
            background: #0E0F14 !important;
            border: 1px solid #23252E !important;
            border-radius: 8px !important;
            color: #FFFFFF !important;
            padding: 8px 12px !important;
            outline: none !important;
        }

        .select2-results__option {
            padding: 9px 14px !important;
            font-size: 13.5px !important;
            color: #CBD5E1 !important;
        }

        .select2-results__option--highlighted[aria-selected] {
            background-color: #FBAF43 !important;
            color: #000000 !important;
            font-weight: 700 !important;
        }

        .select2-results__option[aria-selected=true] {
            background-color: rgba(251, 175, 67, 0.15) !important;
            color: #FBAF43 !important;
        }

        /* Input Group with Dial Code Prefix */
        .ak23-input-group {
            display: flex;
            align-items: stretch;
            width: 100%;
        }

        .ak23-input-group-badge {
            background: #181A22;
            border: 1px solid #23252E;
            border-right: none;
            border-top-left-radius: 11px;
            border-bottom-left-radius: 11px;
            color: #FBAF43;
            font-weight: 700;
            font-size: 14px;
            padding: 0 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            letter-spacing: 0.04em;
        }

        .ak23-input-attached {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            padding-left: 14px !important;
        }

        /* Checkbox Styling */
        .ak23-feed-save-toggle {
            margin: 18px 0 6px;
            padding-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
        }

        .ak23-checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            margin: 0;
            user-select: none;
        }

        .ak23-checkbox-label input {
            display: none;
        }

        .ak23-checkbox-custom {
            width: 18px;
            height: 18px;
            border-radius: 5px;
            background: #0E0F14;
            border: 1px solid #333644;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .ak23-checkbox-label input:checked + .ak23-checkbox-custom {
            background: #FBAF43;
            border-color: #FBAF43;
        }

        .ak23-checkbox-label input:checked + .ak23-checkbox-custom::after {
            content: "\f00c";
            font-family: "Line Awesome Free";
            font-weight: 900;
            font-size: 11px;
            color: #000000;
        }

        .ak23-checkbox-text {
            font-size: 12.5px;
            color: #94A3B8;
            line-height: 1.4;
        }

        /* Field Highlights on Auto-Feed */
        .ak23-highlight-feed {
            animation: ak23PulseHighlight 1.2s ease-out;
        }

        @keyframes ak23PulseHighlight {
            0% {
                border-color: #FBAF43 !important;
                box-shadow: 0 0 0 4px rgba(251, 175, 67, 0.45) !important;
                background: rgba(251, 175, 67, 0.1) !important;
            }
            100% {
                border-color: #23252E !important;
                box-shadow: none !important;
                background: #0E0F14 !important;
            }
        }

        /* Floating Toast Notification */
        .ak23-feed-toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 999999;
            background: linear-gradient(135deg, #1C1D24 0%, #121318 100%);
            border: 1px solid rgba(251, 175, 67, 0.4);
            border-radius: 12px;
            padding: 14px 18px;
            color: #FFFFFF;
            font-size: 13.5px;
            font-weight: 600;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            gap: 10px;
            opacity: 0;
            transform: translateY(20px);
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .ak23-feed-toast.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            const STORAGE_KEY = 'maarifa_user_profile';
            const ALT_STORAGE_KEY = 'akcourse_user_data';

            // 1. Sync Dial Code & Country selection (Default: Tanzania, +255, TZ)
            function updateCountryDialCode() {
                var $selected = $('select[name=country] :selected');
                if (!$selected.length || !$selected.data('mobile_code')) {
                    // Fallback select Tanzania if somehow unselected
                    $('select[name=country] option[data-code="TZ"]').prop('selected', true);
                    $selected = $('select[name=country] :selected');
                }

                var dialCode = String($selected.data('mobile_code') || '255');
                var code = String($selected.data('code') || 'TZ');

                $('input[name=mobile_code]').val(dialCode);
                $('input[name=country_code]').val(code);
                $('.mobile-code').text('+' + dialCode);

                sanitizePhoneInput();
            }

            // Ensure Tanzania is default if no value chosen
            if (!$('select[name=country]').val()) {
                $('select[name=country]').val('Tanzania');
            }
            updateCountryDialCode();

            $('select[name=country]').on('change', function() {
                updateCountryDialCode();
                var mobileVal = $('input[name=mobile]').val();
                if (mobileVal) {
                    checkUser(mobileVal, 'mobile');
                }
                saveFormToStorage();
            });

            // 2. Local Phone Sanitizer (Strips redundant 0 or 255 for Tanzania)
            function sanitizePhoneInput() {
                var dialCode = $('input[name=mobile_code]').val();
                var $mobile = $('input[name=mobile]');
                var val = $mobile.val();
                if (!val) return;

                var clean = val.replace(/\D/g, '');
                if (dialCode === '255') {
                    if (clean.startsWith('255') && clean.length > 9) {
                        clean = clean.substring(3);
                    }
                    if (clean.startsWith('0')) {
                        clean = clean.substring(1);
                    }
                }
                if (clean !== val) {
                    $mobile.val(clean);
                }
            }

            $('input[name=mobile]').on('input blur', function() {
                sanitizePhoneInput();
                var val = $(this).val();
                if (val) {
                    checkUser(val, 'mobile');
                }
                saveFormToStorage();
            });

            // 3. Username Suggester & Live Checker
            const usernamePresets = [
                '{{ $suggestedUsername }}',
                '{{ strtolower(preg_replace("/[^a-z0-9_]/", "", str_replace([" ", "-", "."], "_", ($user->firstname ?? "sound") . ($user->lastname ?? "producer")))) }}',
                '{{ strtolower(preg_replace("/[^a-z0-9_]/", "", str_replace([" ", "-", "."], "_", ($user->firstname ?? "studio") . "_audio"))) }}',
                '{{ strtolower(preg_replace("/[^a-z0-9_]/", "", str_replace([" ", "-", "."], "_", ($user->firstname ?? "music") . "_255"))) }}',
                '{{ strtolower(preg_replace("/[^a-z0-9_]/", "", str_replace([" ", "-", "."], "_", "producer_" . ($user->firstname ?? "student")))) }}'
            ].filter(function(item, index, self) {
                return item && item.length >= 6 && /^[a-z0-9_]+$/.test(item) && self.indexOf(item) === index;
            });

            var suggestCursor = 0;
            $('#btn-random-username').on('click', function(e) {
                e.preventDefault();
                suggestCursor = (suggestCursor + 1) % usernamePresets.length;
                var nextUser = usernamePresets[suggestCursor];
                $('input[name=username]').val(nextUser);
                highlightField('input[name=username]');
                checkUser(nextUser, 'username');
                showToast('Suggested username applied: @' + nextUser);
                saveFormToStorage();
            });

            $('input[name=username]').on('input blur', function() {
                var clean = $(this).val().toLowerCase().replace(/[^a-z0-9_]/g, '');
                if (clean !== $(this).val()) {
                    $(this).val(clean);
                }
                if (clean.length >= 3) {
                    checkUser(clean, 'username');
                }
                saveFormToStorage();
            });

            // 4. Browser Storage Engine (Auto-Feed & Persistence)
            function getStoredData() {
                try {
                    var raw = localStorage.getItem(STORAGE_KEY) || localStorage.getItem(ALT_STORAGE_KEY);
                    if (raw) {
                        return JSON.parse(raw);
                    }
                } catch(e) {
                    console.warn('Storage read failed:', e);
                }
                return null;
            }

            function checkStorageStatus() {
                var data = getStoredData();
                var $statusText = $('#storageStatusText');
                var $statusDot = $('.ak23-feed-dot');
                var $clearBtn = $('#btn-clear-storage');
                var $feedBtn = $('#btn-autofeed-storage');

                if (data && (data.mobile || data.city || data.address || data.username)) {
                    var countryLabel = data.country || 'Tanzania';
                    $statusText.html('<span class="text-success"><i class="las la-check-circle"></i> Saved profile in storage (' + countryLabel + ')</span>');
                    $statusDot.addClass('active');
                    $clearBtn.removeClass('d-none');
                    $feedBtn.addClass('ak23-has-data');
                    return true;
                } else {
                    $statusText.html('<span class="text-muted"><i class="las la-info-circle"></i> Auto-save ready (stores your inputs)</span>');
                    $statusDot.removeClass('active');
                    $clearBtn.addClass('d-none');
                    $feedBtn.removeClass('ak23-has-data');
                    return false;
                }
            }

            function saveFormToStorage() {
                if (!$('#rememberDetails').is(':checked')) {
                    return;
                }

                var data = {
                    username: $('input[name=username]').val(),
                    country: $('select[name=country]').val(),
                    country_code: $('input[name=country_code]').val(),
                    mobile_code: $('input[name=mobile_code]').val(),
                    mobile: $('input[name=mobile]').val(),
                    address: $('input[name=address]').val(),
                    state: $('input[name=state]').val(),
                    zip: $('input[name=zip]').val(),
                    city: $('input[name=city]').val(),
                    saved_at: new Date().toISOString()
                };

                try {
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
                    localStorage.setItem(ALT_STORAGE_KEY, JSON.stringify(data));
                    checkStorageStatus();
                } catch(e) {
                    console.warn('Storage write failed:', e);
                }
            }

            function applyDataToForm(data, successNotice) {
                if (!data) return;

                if (data.country) {
                    $('select[name=country]').val(data.country).trigger('change');
                }
                if (data.username && !$('input[name=username]').val()) {
                    $('input[name=username]').val(data.username);
                    highlightField('input[name=username]');
                }
                if (data.mobile) {
                    $('input[name=mobile]').val(data.mobile);
                    sanitizePhoneInput();
                    highlightField('input[name=mobile]');
                }
                if (data.address) {
                    $('input[name=address]').val(data.address);
                    highlightField('input[name=address]');
                }
                if (data.city) {
                    $('input[name=city]').val(data.city);
                    highlightField('input[name=city]');
                }
                if (data.state) {
                    $('input[name=state]').val(data.state);
                    highlightField('input[name=state]');
                }
                if (data.zip) {
                    $('input[name=zip]').val(data.zip);
                    highlightField('input[name=zip]');
                }

                updateCountryDialCode();

                if ($('input[name=username]').val()) {
                    checkUser($('input[name=username]').val(), 'username');
                }
                if ($('input[name=mobile]').val()) {
                    checkUser($('input[name=mobile]').val(), 'mobile');
                }

                showToast(successNotice || 'Details auto-fed from browser storage!');
            }

            // Click: Auto-Feed Button
            $('#btn-autofeed-storage').on('click', function(e) {
                e.preventDefault();
                var data = getStoredData();
                if (data && (data.mobile || data.city || data.address || data.username)) {
                    applyDataToForm(data, '⚡ Profile restored from saved browser storage!');
                } else {
                    showToast('No saved profile found yet. Use Quick-Fill Tanzania or fill below to auto-save.');
                }
            });

            // Click: Quick-Fill Tanzania Preset
            $('#btn-quickfill-tz').on('click', function(e) {
                e.preventDefault();
                var tzPreset = {
                    country: 'Tanzania',
                    city: 'Dar es Salaam',
                    state: 'Dar es Salaam',
                    zip: '11101',
                    address: 'Posta / CBD'
                };
                applyDataToForm(tzPreset, '🇹🇿 Tanzania regional defaults applied! Enter your phone number.');
                $('input[name=mobile]').focus();
                saveFormToStorage();
            });

            // Click: Clear Saved Storage
            $('#btn-clear-storage').on('click', function(e) {
                e.preventDefault();
                localStorage.removeItem(STORAGE_KEY);
                localStorage.removeItem(ALT_STORAGE_KEY);
                checkStorageStatus();
                showToast('Saved browser profile cleared.');
            });

            // Auto-feed on page load if inputs are empty
            $(document).ready(function() {
                var hasStored = checkStorageStatus();
                var isMobileBlank = !$('input[name=mobile]').val();
                var isAddressBlank = !$('input[name=address]').val() && !$('input[name=city]').val();

                if (hasStored && (isMobileBlank || isAddressBlank)) {
                    var data = getStoredData();
                    applyDataToForm(data, '⚡ Profile auto-fed from your saved browser storage!');
                }

                // Initial validation check on pre-filled username
                var initialUser = $('input[name=username]').val();
                if (initialUser && initialUser.length >= 4) {
                    checkUser(initialUser, 'username');
                }
            });

            // Listen to any changes to keep storage updated
            $('input').on('input change', function() {
                saveFormToStorage();
            });

            // 5. Visual Highlight and Toast Utility
            function highlightField(selector) {
                var $el = $(selector);
                $el.addClass('ak23-highlight-feed');
                setTimeout(function() {
                    $el.removeClass('ak23-highlight-feed');
                }, 1200);
            }

            function showToast(msg) {
                var $toast = $('#ak23-feed-toast');
                $toast.html('<i class="las la-bolt text-warning"></i> ' + msg).addClass('show');
                clearTimeout(window.__toastTimer);
                window.__toastTimer = setTimeout(function() {
                    $toast.removeClass('show');
                }, 3500);
            }

            // 6. User Existence Checker
            function checkUser(value, name) {
                var url = '{{ route('user.checkUser') }}';
                var token = '{{ csrf_token() }}';
                var data = { _token: token };

                if (name == 'mobile') {
                    data.mobile = `${value}`;
                    data.mobile_code = $('.mobile-code').text().replace('+', '');
                }
                if (name == 'username') {
                    data.username = value;
                }

                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $(`.${response.type}Exist`).html('<span class="text-danger"><i class="las la-exclamation-circle"></i> ' + response.field + ' already exists</span>');
                    } else {
                        if (name == 'username' && value.length >= 6) {
                            $(`.${response.type}Exist`).html('<span class="text-success"><i class="las la-check-circle"></i> @lang('Username available')</span>');
                        } else if (name == 'mobile' && value.length >= 7) {
                            $(`.${response.type}Exist`).html('<span class="text-success"><i class="las la-check-circle"></i> @lang('Mobile number valid')</span>');
                        } else {
                            $(`.${response.type}Exist`).html('');
                        }
                    }
                });
            }
        })(jQuery);
    </script>
@endpush
