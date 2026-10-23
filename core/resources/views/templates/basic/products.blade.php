@extends($activeTemplate . 'layouts.frontend')

@section('content')

<!-- =========================================================================
     COMPACT CATALOG BAR & FILTERS (HERO SECTION EXCLUSIVE TO HOME PAGE)
     ========================================================================= -->
<div class="ak23-catalog-top-bar">
    <div class="ak23-container">
        <div class="ak23-catalog-bar-inner">
            <div class="ak23-catalog-bar-meta">
                <span class="ak23-catalog-bar-tag">COURSES & SOFTWARE PACKAGES</span>
                <h1 class="ak23-catalog-bar-title">All Training Programs</h1>
            </div>

        </div>
    </div>
</div>

<!-- =========================================================================
     SERIES CATALOG LIST
     ========================================================================= -->
<div class="ak23-catalog-body">
    <div class="ak23-container">
        @foreach($catalog as $sIndex => $item)
            @php
                $series = $item['series'];
                $courses = $item['courses'];
                $bundle = $item['bundle'];
                $seriesSeqPadded = str_pad($series->sequence_no, 2, '0', STR_PAD_LEFT);
                $isSeriesActive = !request('series') || request('series') === 'all' || request('series') === $series->slug;
            @endphp

            <section class="ak23-series-section series-group-{{ $series->slug }}" id="series-{{ $series->slug }}" style="{{ $isSeriesActive ? '' : 'display: none;' }}">
                <!-- Series Section Header -->
                <div class="ak23-series-header">
                    <div>
                        <div class="ak23-series-tag">PRODUCT {{ $seriesSeqPadded }}</div>
                        <h2 class="ak23-series-title">{{ $series->title }}</h2>
                        <p class="ak23-series-desc">{{ $series->description }}</p>
                    </div>

                    <a href="{{ route('home') }}" class="ak23-btn-series-link">
                        <span>View series page</span>
                        <i class="las la-arrow-right"></i>
                    </a>
                </div>

                <!-- Complete Package Banner -->
                @if($bundle)
                    @php
                        $bundleTitle = $bundle->title ?: ($bundle->name ?? 'Complete Series');
                        $bundlePrice = $bundle->price ?: ($bundle->price_minor ?? 480000);
                        $bundleSku = $bundle->sku ?: ('AK23-BUNDLE-' . strtoupper($series->slug));
                    @endphp
                    <div class="ak23-complete-pkg-banner">
                        <div>
                            <div class="ak23-pkg-banner-badge">COMPLETE PACKAGE</div>
                            <h3 class="ak23-pkg-banner-title">{{ $bundleTitle }}</h3>
                            <p class="ak23-pkg-banner-desc">{{ $bundle->description }}</p>
                        </div>

                        <div class="ak23-pkg-banner-right">
                            <span class="ak23-pkg-banner-price">
                                TSH {{ number_format($bundlePrice) }}
                            </span>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-outline-warning ak23-btn-add-cart px-3" data-sku="{{ $bundleSku }}" title="Add Bundle to Cart">
                                    <i class="las la-cart-plus fs-18"></i>
                                </button>
                                <a href="{{ route('order.checkout', ['sku' => $bundleSku]) }}" class="ak23-btn-pkg-get">
                                    <span>Get complete series</span>
                                    <i class="las la-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Course Cards Grid -->
                <div class="ak23-course-grid">
                    @foreach($courses as $course)
                        @php
                            $seqPadded = str_pad($course->sequence_no ?: 1, 2, '0', STR_PAD_LEFT);
                            $stageSlug = Str::slug($course->stage_label ?: 'beginner');
                            $stageBadgeClass = match($stageSlug) {
                                'getting-started' => 'stage-getting-started',
                                'beginner' => 'stage-beginner',
                                'developing-your-skills' => 'stage-developing',
                                'intermediate' => 'stage-intermediate',
                                'advanced-chain-building' => 'stage-advanced',
                                'studio-workflow' => 'stage-advanced',
                                'building-musical-ideas' => 'stage-developing',
                                'developing-your-sound' => 'stage-developing',
                                'creative-production' => 'stage-intermediate',
                                'complete-your-beat' => 'stage-advanced',
                                default => 'stage-beginner'
                            };
                            $licenseLabel = ($series->slug === 'vocal-mixing') ? 'PLUGIN LICENSE INCLUDED' : 'SOFTWARE LICENSE INCLUDED';
                            $courseTitle = $course->title ?: ($course->name ?? 'Course');
                            $coursePrice = $course->price ?: ($course->price_minor ?? 140000);
                            $courseSku = $course->sku ?: ($course->slug ?: 'AK23-COURSE-' . $course->id);
                            $lessonCount = $course->lesson_count ?: 8;
                            $videoMin = $course->video_duration_seconds ? round($course->video_duration_seconds / 60) : 120;
                        @endphp

                        <div class="ak23-course-card">
                            <!-- Visual Top with SVG Graphic -->
                            <div class="ak23-course-visual">
                                <span class="ak23-seq-circle">{{ $seqPadded }}</span>
                                <span class="ak23-stage-badge {{ $stageBadgeClass }}">{{ $course->stage_label ?: 'Beginner' }}</span>

                                @if($series->slug === 'vocal-mixing')
                                    @if($course->sequence_no == 1)
                                        <svg width="270" height="135" viewBox="0 0 270 135" fill="none"><rect x="15" y="15" width="240" height="105" rx="6" fill="#13131D" stroke="#252535"/><text x="30" y="34" fill="#D9A742" font-size="9" font-weight="800" letter-spacing="1">TUNED // PITCH ENGINE</text><line x1="30" y1="42" x2="240" y2="42" stroke="#252535"/><line x1="30" y1="60" x2="240" y2="60" stroke="#1C1C28" stroke-dasharray="2 2"/><path d="M30 75 Q60 45 90 65 T150 55 T210 70 T240 50" fill="none" stroke="#D9A742" stroke-width="2"/><circle cx="55" cy="100" r="10" fill="#191924" stroke="#D9A742" stroke-width="1.5"/><circle cx="115" cy="100" r="10" fill="#191924" stroke="#D9A742" stroke-width="1.5"/><circle cx="175" cy="100" r="10" fill="#191924" stroke="#D9A742" stroke-width="1.5"/></svg>
                                    @elseif($course->sequence_no == 2)
                                        <svg width="270" height="135" viewBox="0 0 270 135" fill="none"><rect x="15" y="15" width="240" height="105" rx="6" fill="#13131D" stroke="#252535"/><text x="30" y="34" fill="#DE784D" font-size="9" font-weight="800" letter-spacing="1">VOXITY // MULTI-FX UNIT</text><line x1="30" y1="42" x2="240" y2="42" stroke="#252535"/><circle cx="135" cy="72" r="22" fill="#171724" stroke="#DE784D" stroke-width="1.5" stroke-dasharray="3 2"/><circle cx="135" cy="72" r="12" fill="#DE784D" fill-opacity="0.2"/><rect x="40" y="55" width="12" height="35" rx="2" fill="#191926" stroke="#28283C"/><rect x="42" y="65" width="8" height="6" rx="1" fill="#DE784D"/><circle cx="195" cy="72" r="12" fill="#191924" stroke="#DE784D" stroke-width="1.5"/></svg>
                                    @elseif($course->sequence_no == 3)
                                        <svg width="270" height="135" viewBox="0 0 270 135" fill="none"><rect x="15" y="15" width="240" height="105" rx="6" fill="#13131D" stroke="#252535"/><text x="30" y="34" fill="#9B77D2" font-size="9" font-weight="800" letter-spacing="1">XVOX PRO // VOCAL STRIP</text><line x1="30" y1="42" x2="240" y2="42" stroke="#252535"/><rect x="30" y="52" width="38" height="42" rx="3" fill="#181826" stroke="#2E2840"/><rect x="74" y="52" width="38" height="42" rx="3" fill="#181826" stroke="#2E2840"/><rect x="118" y="52" width="38" height="42" rx="3" fill="#181826" stroke="#9B77D2" stroke-width="1.5"/><rect x="124" y="62" width="26" height="4" fill="#9B77D2"/><text x="124" y="85" fill="#9B77D2" font-size="7" font-weight="700">COMP</text></svg>
                                    @elseif($course->sequence_no == 4)
                                        <svg width="270" height="135" viewBox="0 0 270 135" fill="none"><rect x="15" y="15" width="240" height="105" rx="6" fill="#13131D" stroke="#252535"/><text x="30" y="34" fill="#ED7C66" font-size="9" font-weight="800" letter-spacing="1">NECTAR 4 // VOCAL ASSISTANT</text><line x1="30" y1="42" x2="240" y2="42" stroke="#252535"/><path d="M30 78 Q75 40 120 75 T190 60 T240 82" fill="none" stroke="#ED7C66" stroke-width="2"/><circle cx="85" cy="52" r="4" fill="#ED7C66"/><circle cx="160" cy="68" r="4" fill="#ED7C66"/></svg>
                                    @else
                                        <svg width="270" height="135" viewBox="0 0 270 135" fill="none"><rect x="15" y="15" width="240" height="105" rx="6" fill="#13131D" stroke="#252535"/><text x="30" y="34" fill="#4FA6C9" font-size="9" font-weight="800" letter-spacing="1">WAVES 17 // STUDIO RACK</text><line x1="30" y1="42" x2="240" y2="42" stroke="#252535"/><rect x="30" y="52" width="80" height="40" rx="3" fill="#0E171C" stroke="#1D303B"/><path d="M45 82 Q70 60 95 82" fill="none" stroke="#284858"/><line x1="70" y1="84" x2="80" y2="62" stroke="#4FA6C9" stroke-width="1.5"/><circle cx="135" cy="65" r="10" fill="#18232A" stroke="#4FA6C9" stroke-width="1.5"/><circle cx="170" cy="65" r="10" fill="#18232A" stroke="#4FA6C9" stroke-width="1.5"/></svg>
                                    @endif
                                @elseif($series->slug === 'audio-editing-software')
                                    <!-- DAW Station SVG -->
                                    <svg width="270" height="135" viewBox="0 0 270 135" fill="none">
                                        <rect x="15" y="15" width="240" height="105" rx="6" fill="#111119" stroke="#232332"/>
                                        <text x="30" y="34" fill="{{ $course->accent_color ?: '#F0A62F' }}" font-size="9" font-weight="800" letter-spacing="1">{{ $course->short_name ?: 'DAW' }} // WORKSTATION</text>
                                        <line x1="30" y1="42" x2="240" y2="42" stroke="#232332"/>
                                        <!-- Timeline Tracks -->
                                        <rect x="30" y="52" width="210" height="14" rx="2" fill="#161622"/>
                                        <rect x="50" y="54" width="70" height="10" rx="2" fill="{{ $course->accent_color ?: '#F0A62F' }}" fill-opacity="0.6"/>
                                        <rect x="135" y="54" width="80" height="10" rx="2" fill="{{ $course->accent_color ?: '#F0A62F' }}" fill-opacity="0.3"/>
                                        <rect x="30" y="70" width="210" height="14" rx="2" fill="#161622"/>
                                        <rect x="75" y="72" width="110" height="10" rx="2" fill="{{ $course->accent_color ?: '#F0A62F' }}" fill-opacity="0.5"/>
                                        <!-- Fader Knobs -->
                                        <circle cx="50" cy="104" r="8" fill="#1A1A26" stroke="{{ $course->accent_color ?: '#F0A62F' }}" stroke-width="1.5"/>
                                        <circle cx="90" cy="104" r="8" fill="#1A1A26" stroke="{{ $course->accent_color ?: '#F0A62F' }}" stroke-width="1.5"/>
                                        <circle cx="130" cy="104" r="8" fill="#1A1A26" stroke="#2D2D3D" stroke-width="1.5"/>
                                        <rect x="165" y="96" width="60" height="14" rx="3" fill="#181824" stroke="#2D2D3D"/>
                                    </svg>
                                @else
                                    <!-- Beat Making Synth / Drum Pad SVG -->
                                    <svg width="270" height="135" viewBox="0 0 270 135" fill="none">
                                        <rect x="15" y="15" width="240" height="105" rx="6" fill="#111119" stroke="#232332"/>
                                        <text x="30" y="34" fill="{{ $course->accent_color ?: '#F0A62F' }}" font-size="9" font-weight="800" letter-spacing="1">{{ $course->short_name ?: 'BEATS' }} // PRODUCTION LAB</text>
                                        <line x1="30" y1="42" x2="240" y2="42" stroke="#232332"/>
                                        <!-- 4x2 MPC Pads -->
                                        <rect x="30" y="50" width="22" height="20" rx="3" fill="#191924" stroke="#2C2C3E"/>
                                        <rect x="58" y="50" width="22" height="20" rx="3" fill="#191924" stroke="{{ $course->accent_color ?: '#F0A62F' }}" stroke-width="1.5"/>
                                        <rect x="86" y="50" width="22" height="20" rx="3" fill="#191924" stroke="#2C2C3E"/>
                                        <rect x="114" y="50" width="22" height="20" rx="3" fill="#191924" stroke="#2C2C3E"/>
                                        <rect x="30" y="76" width="22" height="20" rx="3" fill="#191924" stroke="{{ $course->accent_color ?: '#F0A62F' }}" stroke-width="1.5"/>
                                        <rect x="58" y="76" width="22" height="20" rx="3" fill="#191924" stroke="#2C2C3E"/>
                                        <rect x="86" y="76" width="22" height="20" rx="3" fill="#191924" stroke="#2C2C3E"/>
                                        <rect x="114" y="76" width="22" height="20" rx="3" fill="#191924" stroke="#2C2C3E"/>
                                        <!-- Step Sequencer & Knobs -->
                                        <circle cx="165" cy="65" r="12" fill="#181826" stroke="{{ $course->accent_color ?: '#F0A62F' }}" stroke-width="1.5"/>
                                        <circle cx="205" cy="65" r="12" fill="#181826" stroke="{{ $course->accent_color ?: '#F0A62F' }}" stroke-width="1.5"/>
                                        <rect x="145" y="88" width="85" height="14" rx="2" fill="#161622" stroke="#29293A"/>
                                    </svg>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="ak23-course-body">
                                <h3 class="ak23-course-title">{{ $courseTitle }}</h3>
                                <p class="ak23-course-outcome">{{ $course->learning_outcome ?: 'Professional music production mastery with studio stems and software package.' }}</p>

                                <div class="ak23-course-meta">
                                    {{ $lessonCount }} LESSONS &bull; {{ $videoMin }} MIN
                                </div>

                                <a href="javascript:void(0)" class="ak23-preview-link">
                                    <i class="las la-play"></i>
                                    <span>PREVIEW COURSE</span>
                                </a>

                                <div class="ak23-license-pill">
                                    <i class="las la-shield-alt"></i>
                                    <span>{{ $licenseLabel }}</span>
                                </div>

                                <div class="ak23-software-section">
                                    <div class="ak23-software-tag">LICENSED SOFTWARE INCLUDED</div>
                                    @php
                                        $ed = $course->software_edition;
                                        if (!$ed || str_contains($ed, '[Confirm')) {
                                            $ed = 'Studio Edition';
                                        }
                                    @endphp
                                    <p class="ak23-software-name">{{ $course->software_name ? $course->software_name . ' — ' . $ed : 'Matching Studio Software License Included' }}</p>
                                </div>

                                <!-- Card Footer -->
                                <div class="ak23-course-card-footer">
                                    <div class="ak23-price-group">
                                        <span class="ak23-price-val">
                                            TSH {{ number_format($coursePrice) }}
                                        </span>
                                        <span class="ak23-price-label">TOTAL PACKAGE PRICE</span>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-outline-warning ak23-btn-add-cart p-2" data-sku="{{ $courseSku }}" title="Add to Cart">
                                            <i class="las la-cart-plus fs-18"></i>
                                        </button>
                                        <a href="{{ route('order.checkout', ['sku' => $courseSku]) }}" class="ak23-card-btn">
                                            <span>Get course + software</span>
                                            <i class="las la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</div>

@endsection

@push('script')
<script>
    // Series filter linked to header navigation
    $(document).ready(function() {
        function applySeriesFilter(seriesSlug) {
            if (!seriesSlug || seriesSlug === 'all') {
                $('.ak23-series-section').fadeIn(200);
                $('.ak23-nav-link').removeClass('active');
                $('.ak23-nav-link[data-series="all"], .ak23-nav-link:not([data-series])').filter(function() {
                    return $(this).text().trim() === 'All Courses';
                }).addClass('active');
            } else {
                $('.ak23-series-section').hide();
                const $target = $('.series-group-' + seriesSlug);
                if ($target.length) {
                    $target.fadeIn(200);
                } else {
                    $('.ak23-series-section').fadeIn(200);
                }
                $('.ak23-nav-link').removeClass('active');
                $('.ak23-nav-link[data-series="' + seriesSlug + '"]').addClass('active');
            }
        }

        // Intercept clicks on header navigation series links when already on catalog page for instant response
        $(document).on('click', '.ak23-nav-link', function(e) {
            const href = $(this).attr('href');
            if (!href) return;
            try {
                const targetUrl = new URL(href, window.location.origin);
                const currentPath = window.location.pathname.replace(/\/$/, '');
                const targetPath = targetUrl.pathname.replace(/\/$/, '');

                if (currentPath === targetPath) {
                    e.preventDefault();
                    const series = targetUrl.searchParams.get('series') || 'all';
                    window.history.pushState({ series: series }, '', href);
                    applySeriesFilter(series);
                    $('html, body').animate({ scrollTop: 0 }, 200);
                }
            } catch (err) {}
        });

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const series = urlParams.get('series') || 'all';
            applySeriesFilter(series);
        });

        // Add to Cart via AJAX (Deduplicated event handler)
        $(document).off('click', '.ak23-btn-add-cart').on('click', '.ak23-btn-add-cart', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            const btn = $(this);
            const sku = btn.data('sku');
            const originalHtml = btn.html();
            const token = $('meta[name="csrf-token"]').attr('content') || "{{ csrf_token() }}";

            btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i>');

            $.ajax({
                url: "{{ route('cart.quick_add') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: {
                    _token: token,
                    sku: sku
                },
                success: function(res) {
                    btn.prop('disabled', false).html('<i class="las la-check text-success"></i>');
                    setTimeout(() => { btn.html(originalHtml); }, 2200);
                    notify(res.status || 'success', res.message);
                    if (res.cart_count !== undefined) {
                        $('.ak23-cart-count').text(res.cart_count);
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html(originalHtml);
                    let errMsg = 'Could not add product to cart.';
                    if (xhr.status === 419) {
                        errMsg = 'Session expired. Please refresh the page to continue.';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errMsg = xhr.responseJSON.message;
                    }
                    notify('error', errMsg);
                }
            });
        });
    });
</script>
@endpush
