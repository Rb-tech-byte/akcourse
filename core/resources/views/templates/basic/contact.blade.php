@extends($activeTemplate . 'layouts.frontend')
@section('content')

    @php
        $contactContent = @getContent('contact_us.content', true)->data_values;
        $contactElements = getContent('contact_us.element', orderById: true);
        $socialIcons = getContent('social_icon.element', orderById: true);

        // Fetch WhatsApp extension credentials if active for dynamic linking
        $waExtension = \App\Models\Extension::where('act', 'whatsapp-chat')->where('status', \App\Constants\Status::ENABLE)->first();
        $waPhone = @$waExtension->shortcode->phone_number->value ?? '255755000000';
        $waMessage = @$waExtension->shortcode->default_message->value ?? 'Hello AK23 Academy! I have an inquiry about the vocal mixing courses and bundle download.';
        $waUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $waPhone) . '?text=' . urlencode($waMessage);
    @endphp

    <!-- Clean Light Canvas Section -->
    <section class="ak23-clean-contact-page">
        <div class="container">
            <!-- Header Banner -->
            <div class="ak23-clean-header">
                <div class="ak23-badge-gold">
                    <i class="las la-headset"></i> @lang('SUPPORT & INQUIRIES • AK23 ACADEMY')
                </div>
                <h1 class="ak23-clean-title">
                    {{ __(@$contactContent->heading_one ?? 'Contact & Academic Support') }}
                </h1>
                <p class="ak23-clean-subtitle">
                    {{ __(@$contactContent->subheading_one ?? 'Have questions about the AK23 vocal mixing course series, direct zip package downloads, or plugin licenses? Reach out directly.') }}
                </p>
            </div>

            <div class="row g-4 align-items-start">
                <!-- Left: Compact Native Ticket Card (Clean White) -->
                <div class="col-lg-7">
                    <div class="ak23-clean-card">
                        <div class="ak23-card-title-box">
                            <div class="ak23-card-icon-pill">
                                <i class="las la-paper-plane"></i>
                            </div>
                            <div>
                                <h3 class="ak23-card-heading-clean">
                                    {{ __(@$contactContent->heading_two ?? 'Send Us a Direct Message') }}
                                </h3>
                                <p class="ak23-card-sub-clean">
                                    {{ __(@$contactContent->subheading_two ?? 'Submit an inquiry ticket below and our instructors will get back to you promptly.') }}
                                </p>
                            </div>
                        </div>

                        <form class="verify-gcaptcha" method="POST" action="">
                            @csrf
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="ak23-clean-label">
                                        <i class="las la-user"></i> @lang('Your Name')
                                    </label>
                                    <input name="name" type="text"
                                        class="ak23-clean-input"
                                        placeholder="@lang('Full Name')"
                                        value="{{ old('name', @$user->fullname) }}"
                                        @if ($user && $user->profile_complete) readonly @endif required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="ak23-clean-label">
                                        <i class="las la-envelope"></i> @lang('Email Address')
                                    </label>
                                    <input name="email" type="email"
                                        class="ak23-clean-input"
                                        placeholder="@lang('you@example.com')"
                                        value="{{ old('email', @$user->email) }}"
                                        @if ($user) readonly @endif required>
                                </div>
                                <div class="col-12">
                                    <label class="ak23-clean-label">
                                        <i class="las la-tag"></i> @lang('Subject')
                                    </label>
                                    <input name="subject" type="text"
                                        class="ak23-clean-input"
                                        placeholder="@lang('e.g. Vocal mixing course question or download inquiry')"
                                        value="{{ old('subject') }}"
                                        required>
                                </div>
                                <div class="col-12">
                                    <label class="ak23-clean-label">
                                        <i class="las la-comment-alt"></i> @lang('Message')
                                    </label>
                                    <textarea name="message" wrap="off"
                                        class="ak23-clean-input"
                                        rows="4"
                                        style="resize: vertical; min-height: 110px;"
                                        placeholder="@lang('Describe your inquiry or question in detail...')"
                                        required>{{ old('message') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <x-captcha />
                                </div>
                                <div class="col-12">
                                    <button class="ak23-clean-btn-submit w-100" type="submit">
                                        <i class="las la-paper-plane"></i> @lang('Send Support Ticket')
                                    </button>
                                </div>
                            </div>
                        </form>

                        @if (@$contactContent->response_time)
                            <div class="ak23-clean-sla-note">
                                <i class="las la-shield-alt text--gold"></i> {{ __($contactContent->response_time) }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right: Priority WhatsApp & CMS Dynamic Channels -->
                <div class="col-lg-5">
                    <!-- WhatsApp Priority Quick Chat Card -->
                    <div class="ak23-clean-wa-card">
                        <div class="ak23-clean-wa-top">
                            <div class="ak23-clean-wa-icon">
                                <i class="lab la-whatsapp"></i>
                            </div>
                            <div>
                                <h3 class="ak23-clean-wa-title">
                                    @lang('WhatsApp Live Chat')
                                    <span class="ak23-clean-wa-badge"><i class="las la-bolt"></i> @lang('Fastest')</span>
                                </h3>
                                <span class="ak23-clean-wa-subtitle">@lang('Direct Studio Support')</span>
                            </div>
                        </div>
                        <p class="ak23-clean-wa-desc">
                            @lang('Need instant assistance with course access, MP4 downloads, or Selcom payment confirmation? Chat directly with an AK23 engineer.')
                        </p>
                        <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="ak23-clean-wa-btn">
                            <i class="lab la-whatsapp" style="font-size: 19px;"></i> @lang('Chat on WhatsApp Now')
                        </a>
                    </div>

                    <!-- CMS Dynamic Contact Channels -->
                    @if ($contactElements && $contactElements->count() > 0)
                        <div class="ak23-clean-channels-grid">
                            @foreach ($contactElements as $element)
                                @php
                                    $heading = @$element->data_values->heading;
                                    $subheading = @$element->data_values->subheading;
                                    $address = @$element->data_values->contact_address;
                                    $actionUrl = @$element->data_values->action_url;
                                    $icon = @$element->data_values->icon;

                                    if (!$actionUrl) {
                                        if (filter_var($address, FILTER_VALIDATE_EMAIL)) {
                                            $actionUrl = 'mailto:' . $address;
                                        } elseif (preg_match('/^[0-9+\s\-]+$/', $address)) {
                                            $actionUrl = 'tel:' . preg_replace('/[^\d+]/', '', $address);
                                        } else {
                                            $actionUrl = '#';
                                        }
                                    }
                                @endphp
                                <a href="{{ $actionUrl }}" class="ak23-clean-channel-item" @if(Str::startsWith($actionUrl, 'http')) target="_blank" rel="noopener noreferrer" @endif>
                                    <div class="ak23-clean-channel-icon">
                                        @php echo $icon @endphp
                                    </div>
                                    <div class="ak23-clean-channel-content">
                                        <div class="ak23-clean-channel-heading">{{ __($heading) }}</div>
                                        @if ($address)
                                            <div class="ak23-clean-channel-addr">{{ $address }}</div>
                                        @endif
                                        @if ($subheading)
                                            <div class="ak23-clean-channel-sub">{{ __($subheading) }}</div>
                                        @endif
                                    </div>
                                    <div class="ak23-clean-channel-arrow">
                                        <i class="las la-angle-right"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <!-- Working Hours & Studio SLA Banner -->
                    @if (@$contactContent->working_hours)
                        <div class="ak23-clean-hours-card">
                            <div class="ak23-clean-hours-title">
                                <i class="las la-clock"></i> @lang('Working Hours')
                            </div>
                            <p class="ak23-clean-hours-text">
                                {{ __($contactContent->working_hours) }}
                            </p>
                            @if (@$contactContent->response_time)
                                <div class="ak23-clean-hours-sla">
                                    <i class="las la-stopwatch text--gold"></i> {{ __($contactContent->response_time) }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Social Icons (Clean Styled Pills, Zero Blue) -->
                    @if ($socialIcons && $socialIcons->count() > 0)
                        <div class="ak23-clean-social-row">
                            <span class="ak23-clean-social-label">
                                @lang('Connect With Us')
                            </span>
                            <div class="ak23-clean-social-pills">
                                @foreach ($socialIcons as $social)
                                    <a href="{{ @$social->data_values->url }}" target="_blank" rel="noopener noreferrer" class="ak23-clean-social-pill" title="{{ @$social->data_values->title }}">
                                        @php echo @$social->data_values->social_icon @endphp
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Clean White Contact Page Dedicated High-Priority Styles -->
    <style>
    /* ==========================================================================
       AK23 CLEAN WHITE CONTACT PAGE STYLES (ZERO BLUE, LUXURY GOLD ACCENTS)
       ========================================================================== */
    .ak23-clean-contact-page {
        background: #F8FAFC !important;
        color: #0F172A !important;
        padding: 50px 0 80px;
        min-height: 80vh;
        border-bottom: 1px solid #E2E8F0;
    }

    /* Neutralize all blue anchor colors inside contact page */
    .ak23-clean-contact-page a,
    .ak23-clean-contact-page a:visited {
        text-decoration: none !important;
        color: inherit;
    }

    .ak23-clean-header {
        margin-bottom: 34px;
        text-align: left;
    }

    .ak23-badge-gold {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #FEF3C7 !important;
        border: 1px solid #FDE68A !important;
        border-radius: 20px;
        padding: 5px 14px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #B45309 !important;
        margin-bottom: 12px;
    }

    .ak23-clean-title {
        font-size: 38px !important;
        font-weight: 800 !important;
        line-height: 1.15;
        color: #0F172A !important;
        letter-spacing: -0.025em;
        margin-bottom: 10px;
    }

    .ak23-clean-subtitle {
        font-size: 15px !important;
        color: #475569 !important;
        max-width: 680px;
        line-height: 1.6;
        margin: 0;
    }

    /* Left: Native White Card */
    .ak23-clean-card {
        background: #FFFFFF !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 16px !important;
        padding: 34px 30px !important;
        box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.05), 0 4px 6px -2px rgba(15, 23, 42, 0.02) !important;
    }

    .ak23-card-title-box {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 24px;
    }

    .ak23-card-icon-pill {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #FEF3C7;
        color: #D97706;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .ak23-card-heading-clean {
        font-size: 19px !important;
        font-weight: 800 !important;
        color: #0F172A !important;
        margin: 0 0 4px !important;
    }

    .ak23-card-sub-clean {
        font-size: 13px !important;
        color: #64748B !important;
        margin: 0 !important;
        line-height: 1.5;
    }

    .ak23-clean-label {
        color: #1E293B !important;
        font-size: 11px !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
        display: flex !important;
        align-items: center;
        gap: 6px;
        margin-bottom: 7px !important;
    }

    .ak23-clean-label i {
        color: #D97706;
        font-size: 14px;
    }

    .ak23-clean-input {
        width: 100% !important;
        background: #F8FAFC !important;
        border: 1px solid #CBD5E1 !important;
        border-radius: 8px !important;
        color: #0F172A !important;
        font-size: 14px !important;
        padding: 12px 16px !important;
        outline: none !important;
        transition: all 0.2s ease !important;
    }

    .ak23-clean-input:focus {
        background: #FFFFFF !important;
        border-color: #FBAF43 !important;
        box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.25) !important;
    }

    .ak23-clean-input::placeholder {
        color: #94A3B8 !important;
    }

    .ak23-clean-btn-submit {
        background: linear-gradient(135deg, #FBAF43 0%, #E29828 100%) !important;
        color: #0A0A0E !important;
        font-weight: 800 !important;
        font-size: 14.5px !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 14px 24px !important;
        box-shadow: 0 4px 16px rgba(251, 175, 67, 0.35) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
    }

    .ak23-clean-btn-submit:hover {
        background: linear-gradient(135deg, #FFC107 0%, #F59E0B 100%) !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 22px rgba(251, 175, 67, 0.45) !important;
    }

    .ak23-clean-sla-note {
        font-size: 12px;
        color: #64748B;
        text-align: center;
        margin-top: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .ak23-clean-sla-note i {
        color: #D97706;
    }

    /* Right: WhatsApp Priority Card (Clean Green Mint) */
    .ak23-clean-wa-card {
        background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%) !important;
        border: 1px solid #86EFAC !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        margin-bottom: 16px !important;
        box-shadow: 0 4px 18px rgba(34, 197, 94, 0.12) !important;
    }

    .ak23-clean-wa-top {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 10px;
    }

    .ak23-clean-wa-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #25D366 !important;
        color: #FFFFFF !important;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.35);
    }

    .ak23-clean-wa-title {
        font-size: 16px !important;
        font-weight: 800 !important;
        color: #15803D !important;
        margin: 0 !important;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ak23-clean-wa-badge {
        font-size: 10px;
        background: #BBF7D0;
        color: #166534;
        padding: 2px 8px;
        border-radius: 10px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .ak23-clean-wa-subtitle {
        font-size: 11.5px;
        color: #166534;
        display: block;
        margin-top: 2px;
    }

    .ak23-clean-wa-desc {
        font-size: 13px !important;
        color: #14532D !important;
        line-height: 1.5;
        margin: 0 0 14px 0 !important;
    }

    .ak23-clean-wa-btn {
        background: #25D366 !important;
        color: #FFFFFF !important;
        font-size: 13.5px !important;
        font-weight: 800 !important;
        padding: 12px 20px !important;
        border-radius: 8px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        text-decoration: none !important;
        box-shadow: 0 4px 14px rgba(37, 211, 102, 0.35) !important;
        transition: all 0.2s ease !important;
    }

    .ak23-clean-wa-btn:hover {
        background: #1EBE5D !important;
        color: #FFFFFF !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(37, 211, 102, 0.45) !important;
    }

    /* Right: CMS Channels List */
    .ak23-clean-channels-grid {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 14px;
    }

    .ak23-clean-channel-item {
        background: #FFFFFF !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 12px !important;
        padding: 13px 18px !important;
        display: flex !important;
        align-items: center !important;
        gap: 14px !important;
        text-decoration: none !important;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.02) !important;
        transition: all 0.2s ease !important;
    }

    .ak23-clean-channel-item:hover {
        border-color: #FBAF43 !important;
        background: #FFFFFF !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(251, 175, 67, 0.15) !important;
    }

    .ak23-clean-channel-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #FEF3C7;
        color: #D97706;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .ak23-clean-channel-content {
        flex-grow: 1;
    }

    .ak23-clean-channel-heading {
        font-size: 13.5px !important;
        font-weight: 700 !important;
        color: #0F172A !important;
        margin-bottom: 2px !important;
    }

    .ak23-clean-channel-addr {
        font-size: 13px !important;
        font-weight: 700 !important;
        color: #D97706 !important;
        margin-bottom: 1px !important;
    }

    .ak23-clean-channel-sub {
        font-size: 11.5px !important;
        color: #64748B !important;
        margin: 0 !important;
    }

    .ak23-clean-channel-arrow {
        color: #CBD5E1;
        font-size: 14px;
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .ak23-clean-channel-item:hover .ak23-clean-channel-arrow {
        color: #D97706;
        transform: translateX(2px);
    }

    /* Working Hours & SLA Card */
    .ak23-clean-hours-card {
        background: #FFFFFF !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 12px !important;
        padding: 14px 18px !important;
        margin-bottom: 16px !important;
    }

    .ak23-clean-hours-title {
        font-size: 11px !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
        color: #B45309 !important;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
    }

    .ak23-clean-hours-text {
        font-size: 12.5px !important;
        color: #334155 !important;
        margin: 0 !important;
        line-height: 1.5;
        font-weight: 500;
    }

    .ak23-clean-hours-sla {
        margin-top: 6px;
        font-size: 11.5px;
        color: #64748B;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Social Community Row (Zero Blue Pills) */
    .ak23-clean-social-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 4px;
    }

    .ak23-clean-social-label {
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
        color: #64748B !important;
    }

    .ak23-clean-social-pills {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ak23-clean-social-pill {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #FFFFFF !important;
        border: 1px solid #E2E8F0 !important;
        color: #475569 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 15px !important;
        text-decoration: none !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }

    .ak23-clean-social-pill:hover {
        background: #FEF3C7 !important;
        border-color: #FBAF43 !important;
        color: #B45309 !important;
        transform: translateY(-2px);
    }
    </style>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection
