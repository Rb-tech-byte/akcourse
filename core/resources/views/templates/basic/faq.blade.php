@extends($activeTemplate . 'layouts.frontend')
@section('content')

    @php
        $waExtension = \App\Models\Extension::where('act', 'whatsapp-chat')->where('status', \App\Constants\Status::ENABLE)->first();
        $waPhone = @$waExtension->shortcode->phone_number->value ?? '255755000000';
        $waMessage = @$waExtension->shortcode->default_message->value ?? 'Hello Maarifa23 Academy! I have an inquiry about the vocal mixing courses and bundle download.';
        $waUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $waPhone) . '?text=' . urlencode($waMessage);

        // Merge akFaqs with any CMS faqs if needed
        $allFaqs = collect();
        if (isset($akFaqs) && $akFaqs->count() > 0) {
            foreach ($akFaqs as $f) {
                $allFaqs->push((object)[
                    'question' => $f->question,
                    'answer'   => $f->answer,
                ]);
            }
        }
        if (isset($cmsFaqs) && $cmsFaqs->count() > 0) {
            foreach ($cmsFaqs as $cf) {
                $allFaqs->push((object)[
                    'question' => $cf->data_values->question,
                    'answer'   => $cf->data_values->answer,
                ]);
            }
        }

        $totalCount = $allFaqs->count();
        $halfCount = ceil($totalCount / 2);
        $col1Faqs = $allFaqs->slice(0, $halfCount);
        $col2Faqs = $allFaqs->slice($halfCount);
    @endphp

    <!-- Dedicated Clean FAQ Page -->
    <section class="ak23-clean-faq-page">
        <div class="container">
            <!-- Header -->
            <div class="ak23-faq-page-header text-center">
                <div class="ak23-badge-gold">
                    <i class="las la-question-circle"></i> @lang('MAARIFA23 ACADEMY • HELP CENTER')
                </div>
                <h1 class="ak23-clean-title">
                    Frequently Asked <span style="color: #FBAF43;">Questions</span>
                </h1>
                <p class="ak23-clean-subtitle mx-auto">
                    {{ __(@$faqContent->subheading ?? 'Everything you need to know about vocal mixing training, DAW compatibility, software licenses, downloads, and payments.') }}
                </p>

                <!-- Search Input Bar -->
                <div class="ak23-faq-search-box mx-auto mt-4">
                    <i class="las la-search ak23-faq-search-icon"></i>
                    <input type="text" id="faqSearchInput" class="ak23-faq-search-input" placeholder="@lang('Type keywords (e.g. plugins, download, Cubase, Selcom, licenses)...')" onkeyup="filterFaqs()">
                    <button type="button" class="ak23-faq-search-btn" onclick="filterFaqs()">@lang('Search')</button>
                </div>
            </div>

            <!-- 2-Column Responsive Compact Grid -->
            <div class="row g-3 mt-2" id="faqGridContainer">
                <!-- Left Column -->
                <div class="col-lg-6">
                    <div class="ak23-clean-faq-list">
                        @foreach($col1Faqs as $index => $faq)
                            <div class="ak23-clean-faq-card {{ $index === 0 ? 'active' : '' }}" data-question="{{ strtolower($faq->question) }}" data-answer="{{ strtolower($faq->answer) }}">
                                <div class="ak23-clean-faq-header" onclick="toggleDedicatedFaq(this)">
                                    <h3 class="ak23-clean-faq-q">{{ $faq->question }}</h3>
                                    <i class="las la-angle-down ak23-clean-faq-chevron"></i>
                                </div>
                                <div class="ak23-clean-faq-body" style="{{ $index === 0 ? 'display: block;' : '' }}">
                                    <p class="ak23-clean-faq-a">{{ $faq->answer }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-6">
                    <div class="ak23-clean-faq-list">
                        @foreach($col2Faqs as $index => $faq)
                            <div class="ak23-clean-faq-card" data-question="{{ strtolower($faq->question) }}" data-answer="{{ strtolower($faq->answer) }}">
                                <div class="ak23-clean-faq-header" onclick="toggleDedicatedFaq(this)">
                                    <h3 class="ak23-clean-faq-q">{{ $faq->question }}</h3>
                                    <i class="las la-angle-down ak23-clean-faq-chevron"></i>
                                </div>
                                <div class="ak23-clean-faq-body">
                                    <p class="ak23-clean-faq-a">{{ $faq->answer }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- No Results Message -->
            <div id="noFaqResults" class="text-center py-4 d-none">
                <p class="text-muted" style="font-size: 14px;">@lang('No questions matched your search. Try different keywords or reach out below.')</p>
            </div>

            <!-- Bottom Support Assistance Card -->
            <div class="ak23-faq-support-card mt-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="ak23-card-icon-pill">
                        <i class="las la-headset"></i>
                    </div>
                    <div>
                        <h4 class="ak23-support-card-title">@lang('Still have an unanswered question?')</h4>
                        <p class="ak23-support-card-desc">@lang('Our vocal mixing instructors and technical support engineers are online to help you directly.')</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="ak23-clean-wa-btn" style="padding: 10px 18px; font-size: 13px;">
                        <i class="lab la-whatsapp" style="font-size: 18px;"></i> @lang('Chat on WhatsApp')
                    </a>
                    <a href="{{ route('contact') }}" class="ak23-clean-btn-submit" style="padding: 10px 18px; font-size: 13px; text-decoration: none !important;">
                        <i class="las la-envelope"></i> @lang('Submit Support Ticket')
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Dedicated Clean FAQ Styles -->
    <style>
    .ak23-clean-faq-page {
        background: #09090D !important;
        color: #FFFFFF !important;
        padding: 40px 0 60px;
    }

    .ak23-faq-page-header {
        margin-bottom: 30px;
    }

    .ak23-clean-title {
        color: #FFFFFF !important;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .ak23-clean-subtitle {
        color: #8E8EA2 !important;
        font-size: 14.5px;
        max-width: 650px;
    }

    .ak23-badge-gold {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(251, 175, 67, 0.12);
        border: 1px solid rgba(251, 175, 67, 0.28);
        color: #FBAF43;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 14px;
        border-radius: 20px;
        margin-bottom: 12px;
    }

    .ak23-faq-search-box {
        position: relative;
        max-width: 600px;
        width: 100%;
        background: #111118;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 5px 6px 5px 16px;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.35);
        transition: all 0.2s ease;
    }

    .ak23-faq-search-box:focus-within {
        border-color: #FBAF43;
        box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.2);
    }

    .ak23-faq-search-icon {
        color: #FBAF43;
        font-size: 18px;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .ak23-faq-search-input {
        border: none !important;
        background: transparent !important;
        color: #FFFFFF !important;
        font-size: 13.5px !important;
        width: 100%;
        outline: none !important;
        padding: 7px 0;
    }

    .ak23-faq-search-input::placeholder {
        color: #64647A;
    }

    .ak23-faq-search-btn {
        background: linear-gradient(135deg, #FBAF43 0%, #E29828 100%);
        color: #09090D;
        font-size: 12px;
        font-weight: 800;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .ak23-faq-search-btn:hover {
        background: #FFC107;
        transform: translateY(-1px);
    }

    .ak23-clean-faq-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .ak23-clean-faq-card {
        background: #111118 !important;
        border: 1px solid rgba(255, 255, 255, 0.07) !important;
        border-radius: 10px !important;
        overflow: hidden;
        transition: all 0.2s ease;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.25);
    }

    .ak23-clean-faq-card:hover {
        border-color: rgba(255, 255, 255, 0.18) !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.4);
    }

    .ak23-clean-faq-card.active {
        border-color: #FBAF43 !important;
        background: #13131E !important;
        box-shadow: 0 4px 18px rgba(251, 175, 67, 0.08) !important;
    }

    .ak23-clean-faq-header {
        padding: 15px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
        gap: 12px;
    }

    .ak23-clean-faq-q {
        font-size: 13.5px !important;
        font-weight: 700 !important;
        color: #FFFFFF !important;
        margin: 0 !important;
        line-height: 1.45;
    }

    .ak23-clean-faq-card.active .ak23-clean-faq-q {
        color: #FBAF43 !important;
    }

    .ak23-clean-faq-chevron {
        color: #71718A;
        font-size: 15px;
        transition: transform 0.2s ease, color 0.2s ease;
        flex-shrink: 0;
    }

    .ak23-clean-faq-card.active .ak23-clean-faq-chevron {
        transform: rotate(180deg);
        color: #FBAF43;
    }

    .ak23-clean-faq-body {
        padding: 0 18px 16px;
        display: none;
    }

    .ak23-clean-faq-a {
        font-size: 13px !important;
        line-height: 1.65;
        color: #A0A0B2 !important;
        margin: 0;
    }

    /* Support Card */
    .ak23-faq-support-card {
        background: #111118 !important;
        border: 1px solid rgba(255, 255, 255, 0.07) !important;
        border-radius: 10px !important;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 18px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .ak23-card-icon-pill {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: rgba(251, 175, 67, 0.12);
        color: #FBAF43;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        border: 1px solid rgba(251, 175, 67, 0.25);
    }

    .ak23-support-card-title {
        font-size: 15px !important;
        font-weight: 800 !important;
        color: #FFFFFF !important;
        margin: 0 0 2px 0;
    }

    .ak23-support-card-desc {
        font-size: 12.5px !important;
        color: #8E8EA2 !important;
        margin: 0;
    }

    .ak23-clean-wa-btn {
        background: #25D366 !important;
        color: #FFFFFF !important;
        border-radius: 8px !important;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none !important;
    }

    .ak23-clean-btn-submit {
        background: linear-gradient(135deg, #FBAF43 0%, #E29828 100%) !important;
        color: #09090D !important;
        border-radius: 8px !important;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    </style>

    @push('script')
    <script>
    function toggleDedicatedFaq(headerEl) {
        const card = headerEl.closest('.ak23-clean-faq-card');
        const body = card.querySelector('.ak23-clean-faq-body');
        const isActive = card.classList.contains('active');

        // Close other items
        document.querySelectorAll('.ak23-clean-faq-card').forEach(c => {
            if (c !== card) {
                c.classList.remove('active');
                const b = c.querySelector('.ak23-clean-faq-body');
                if (b) $(b).slideUp(200);
            }
        });

        if (isActive) {
            card.classList.remove('active');
            $(body).slideUp(200);
        } else {
            card.classList.add('active');
            $(body).slideDown(200);
        }
    }

    function filterFaqs() {
        const query = document.getElementById('faqSearchInput').value.toLowerCase().trim();
        const cards = document.querySelectorAll('.ak23-clean-faq-card');
        let matchedCount = 0;

        cards.forEach(card => {
            const q = card.getAttribute('data-question') || '';
            const a = card.getAttribute('data-answer') || '';
            if (!query || q.includes(query) || a.includes(query)) {
                card.style.display = 'block';
                matchedCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const noResults = document.getElementById('noFaqResults');
        if (noResults) {
            if (matchedCount === 0) {
                noResults.classList.remove('d-none');
            } else {
                noResults.classList.add('d-none');
            }
        }
    }
    </script>
    @endpush

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @if ($sec !== 'faq')
                @include($activeTemplate . 'sections.' . $sec)
            @endif
        @endforeach
    @endif

@endsection
