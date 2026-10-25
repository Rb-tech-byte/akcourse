@extends($activeTemplate . 'layouts.frontend')

@section('content')

<!-- =========================================================================
     HERO SECTION
     ========================================================================= -->
<section class="ak23-hero">
    <div class="ak23-container">
        <div class="row align-items-center g-5">
            <!-- Hero Left Column -->
            <div class="col-lg-6">
                <div class="ak23-hero-badge">
                    <span class="dot"></span>
                    <span>AK23 VOCAL MIXING SERIES</span>
                </div>

                <h1 class="ak23-hero-title">
                    Learn to Mix <br class="d-none d-md-inline">
                    Vocals. <br class="d-none d-md-inline">
                    <span class="ak23-text-gold">One Plugin at a <br class="d-none d-md-inline">Time.</span>
                </h1>

                <p class="ak23-hero-subtitle">
                    {{ @$akSeries->description ?: 'Start with simple vocal processing and build your skills through five practical courses. Choose one course or get the complete series—with matching plugin licenses included.' }}
                </p>

                <div class="ak23-hero-cta">
                    <a href="#courses" class="ak23-btn-gold">
                        <span>Explore Courses</span>
                        <i class="las la-arrow-right"></i>
                    </a>
                    <a href="#previewModal" data-bs-toggle="modal" class="ak23-btn-ghost">
                        <span>Watch Preview</span>
                    </a>
                </div>

                <!-- 3 Benefit Cards -->
                <div class="ak23-benefit-cards">
                    <div class="ak23-benefit-card">
                        <div class="ak23-benefit-title">Downloadable lessons</div>
                        <p class="ak23-benefit-desc">Learn offline</p>
                    </div>
                    <div class="ak23-benefit-card">
                        <div class="ak23-benefit-title">Plugin licenses included</div>
                        <p class="ak23-benefit-desc">No extra license purchase</p>
                    </div>
                    <div class="ak23-benefit-card">
                        <div class="ak23-benefit-title">Practical training</div>
                        <p class="ak23-benefit-desc">Follow each step</p>
                    </div>
                </div>
            </div>

            <!-- Hero Right Column: Video Preview Card -->
            <div class="col-lg-6">
                <div class="ak23-video-preview-card">
                    <div class="ak23-video-screen">
                        <div class="ak23-video-header-tag">
                            MAARIFA23 ACADEMY / SERIES PREVIEW
                        </div>

                        <!-- Waveform Centerpiece -->
                        <div class="ak23-waveform-centerpiece" id="heroWaveformBox">
                            @php
                                $heights = [18, 28, 42, 60, 48, 75, 88, 55, 32, 68, 85, 95, 70, 50, 78, 90, 65, 45, 82, 98, 74, 52, 35, 60, 80, 65, 40, 22];
                            @endphp
                            @foreach($heights as $h)
                                <div class="wf-bar" style="height: {{ $h }}%;"></div>
                            @endforeach

                            <button type="button" class="ak23-video-play-btn" id="heroPlayBtn" data-bs-toggle="modal" data-bs-target="#previewModal" aria-label="Play Preview">
                                <i class="las la-play" style="margin-left: 3px;"></i>
                            </button>
                        </div>

                        <!-- 4 DAW Channel Slots -->
                        <div class="ak23-daw-slots">
                            <div class="ak23-daw-slot">
                                <div class="ak23-daw-slot-bar active"></div>
                                <span style="font-size: 9px; color: #6D6D7E; font-weight: 700;">TUNER</span>
                            </div>
                            <div class="ak23-daw-slot">
                                <div class="ak23-daw-slot-bar active"></div>
                                <span style="font-size: 9px; color: #6D6D7E; font-weight: 700;">TONE</span>
                            </div>
                            <div class="ak23-daw-slot">
                                <div class="ak23-daw-slot-bar active"></div>
                                <span style="font-size: 9px; color: #6D6D7E; font-weight: 700;">DYN</span>
                            </div>
                            <div class="ak23-daw-slot">
                                <div class="ak23-daw-slot-bar active"></div>
                                <span style="font-size: 9px; color: #6D6D7E; font-weight: 700;">SPACE</span>
                            </div>
                        </div>

                        <!-- Playback Bar -->
                        <div class="ak23-video-controls-bar">
                            <button type="button" class="ak23-ctrl-btn" aria-label="Play">
                                <i class="las la-play"></i>
                            </button>
                            <div class="ak23-progress-container">
                                <div class="ak23-progress-fill"></div>
                            </div>
                            <span class="ak23-time-text">00:00 / 02:18</span>
                            <button type="button" class="ak23-ctrl-btn" aria-label="Volume">
                                <i class="las la-volume-up"></i>
                            </button>
                            <span class="ak23-cc-pill">CC</span>
                        </div>
                    </div>

                    <div class="ak23-video-note">
                        <span class="dot"></span>
                        <span>Preview &middot; Captions included</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- =========================================================================
<!-- =========================================================================
     COURSE SELECTION AREA ("CHOOSE YOUR COURSE")
     ========================================================================= -->
<section class="ak23-courses-section" id="courses">
    <div class="ak23-container">
        <!-- Section Header -->
        <span class="ak23-section-tag">CHOOSE YOUR COURSE</span>
        <h2 class="ak23-section-title">Start simple. Build your skills.</h2>
        <p class="ak23-section-desc">
            Every course includes downloadable training and the matching plugin license. Pick one course, or get the complete series.
        </p>

        <!-- Start Here Banner -->
        <div class="ak23-start-here-banner">
            <span class="ak23-start-here-tag">START HERE</span>
            <p class="ak23-start-here-text">
                <strong>New to vocal mixing or beat production?</strong> Choose any individual course or get the complete series bundle with software license included.
            </p>
        </div>

        <!-- Series Filter Pills -->
        <div class="ak23-series-pills d-flex flex-wrap align-items-center gap-2 mb-4" id="homeSeriesPills">
            <button type="button" class="ak23-filter-btn active" data-series="all">
                <span>All Courses ({{ $allCourses->count() }})</span>
            </button>
            @foreach($catalog as $cItem)
                <button type="button" class="ak23-filter-btn" data-series="{{ $cItem['series']->slug }}">
                    <span>{{ $cItem['series']->name }} ({{ $cItem['courses']->count() }})</span>
                </button>
            @endforeach
            <button type="button" class="ak23-filter-btn" data-series="bundles">
                <span>Series Bundles ({{ $allBundles->count() }})</span>
            </button>
        </div>

        <!-- Course Cards Grid: 4 Columns per Row, matching sample -->
        <div class="ak23-course-grid-4" id="homeCourseGrid">
            @foreach($catalog as $cItem)
                @php
                    $series = $cItem['series'];
                    $courses = $cItem['courses'];
                @endphp
                @foreach($courses as $course)
                    @php
                        $courseTitle = $course->title ?: $course->name;
                        $coursePrice = (float)$course->price;
                        $courseSku = $course->sku ?: ('AK23-COURSE-' . $course->id);
                        $lessonCount = $course->lesson_count ?: 8;
                        $detailUrl = route('product.details', $course->slug);
                        $swName = $course->software_name ?: ($series->name ?: 'MACOS');
                    @endphp

                    <div class="ak23-card-sample ak23-home-course-item"
                         data-url="{{ $detailUrl }}"
                         data-series="{{ $series->slug }}"
                         data-category-id="{{ $series->id }}"
                         data-title="{{ strtolower($courseTitle) }}"
                         data-price="{{ $coursePrice }}"
                         tabindex="0"
                         role="link"
                         aria-label="{{ $courseTitle }}">

                        <!-- Thumbnail Area with Floating Top-Right Cart Button -->
                        <div class="ak23-sample-thumb-wrap">
                            <a href="{{ $detailUrl }}" class="ak23-thumb-link">
                                <img src="{{ $course->thumbnail_url }}" alt="{{ $courseTitle }}" class="ak23-sample-thumb-img" loading="lazy">
                            </a>
                            <button type="button" 
                                    class="ak23-sample-cart-btn ak23-btn-add-cart" 
                                    data-sku="{{ $courseSku }}" 
                                    title="Add to Cart">
                                <i class="las la-cart-plus"></i>
                            </button>
                        </div>

                        <!-- Card Body matching provided sample image -->
                        <div class="ak23-sample-card-body">
                            <h3 class="ak23-sample-card-title" title="{{ $courseTitle }}">
                                <a href="{{ $detailUrl }}" class="text-decoration-none">
                                    {{ $courseTitle }}
                                </a>
                            </h3>

                            <div class="ak23-sample-card-price">
                                @if($course->is_free)
                                    <span class="text-success">FREE</span>
                                @else
                                    <span>TSH {{ number_format($coursePrice) }}</span>
                                @endif
                            </div>

                            <div class="ak23-sample-card-meta">
                                <span class="ak23-meta-tag">{{ strtoupper($swName) }}</span>
                                <span class="ak23-meta-sep">&middot;</span>
                                <span class="ak23-meta-date">{{ $course->lesson_count ? $course->lesson_count . ' LESSONS' : ($course->updated_at ? $course->updated_at->format('M d, Y') : 'Sep 17, 2026') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

        <!-- Series Bundles Grid (shown when Bundles filter is selected) -->
        <div class="ak23-course-grid-4 d-none" id="homeBundlesGrid">
            @foreach($allBundles as $b)
                @php
                    $bTitle = $b->title ?: ($b->name ?? 'Complete Series');
                    $bPrice = (float)($b->price ?: 480000);
                    $bSku = $b->sku ?: ('AK23-BUNDLE-' . $b->id);
                    $bUrl = route('product.details', $b->slug);
                @endphp
                <div class="ak23-card-sample ak23-bundle-card-item"
                     data-url="{{ $bUrl }}"
                     tabindex="0"
                     role="link"
                     aria-label="{{ $bTitle }}">

                    <!-- Thumbnail Area with Floating Top-Right Cart Button -->
                    <div class="ak23-sample-thumb-wrap">
                        <a href="{{ $bUrl }}" class="ak23-thumb-link">
                            <img src="{{ $b->thumbnail_url }}" alt="{{ $bTitle }}" class="ak23-sample-thumb-img" loading="lazy">
                        </a>
                        <button type="button" 
                                class="ak23-sample-cart-btn ak23-btn-add-cart" 
                                data-sku="{{ $bSku }}" 
                                title="Add Bundle to Cart">
                            <i class="las la-cart-plus"></i>
                        </button>
                    </div>

                    <div class="ak23-sample-card-body">
                        <h3 class="ak23-sample-card-title" title="{{ $bTitle }}">
                            <a href="{{ $bUrl }}" class="text-decoration-none">
                                {{ $bTitle }}
                            </a>
                        </h3>

                        <div class="ak23-sample-card-price">
                            <span>TSH {{ number_format($bPrice) }}</span>
                        </div>

                        <div class="ak23-sample-card-meta">
                            <span class="ak23-meta-tag">{{ strtoupper($b->category ? $b->category->name : 'ALL-IN-ONE') }}</span>
                            <span class="ak23-meta-sep">&middot;</span>
                            <span class="ak23-meta-date">COMPLETE BUNDLE</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- =========================================================================
             COMPLETE SERIES BUNDLE CARD ("BEST FOR THE FULL JOURNEY")
             ========================================================================= -->
        @if($akBundle)
        <div class="ak23-bundle-card mt-5" id="homeBundleSpotlight">
            <div class="row g-4 align-items-center">
                <!-- Left Bundle Column -->
                <div class="col-lg-7">
                    <div class="ak23-bundle-badge">
                        <i class="las la-star"></i>
                        <span>BEST FOR THE FULL JOURNEY</span>
                    </div>

                    <h3 class="ak23-bundle-title">
                        Get the Complete<br>
                        <span class="ak23-text-gold">{{ $akBundle->title }}</span>
                    </h3>

                    <p class="ak23-bundle-desc">
                        Follow the full sequence from your first vocal workflow to a complete multi-plugin chain—without needing to add separate individual course packages.
                    </p>

                    <!-- Courses Checklist Box -->
                    <div class="ak23-bundle-checklist-box">
                        @foreach($akCourses as $bc)
                            <div class="ak23-bundle-course-row">
                                <div class="ak23-bundle-course-left">
                                    <span class="ak23-bundle-course-seq">{{ str_pad($bc->sequence_no ?: ($loop->index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                    <div>
                                        <span class="ak23-bundle-course-name">{{ $bc->title }}</span>
                                        <span class="ak23-bundle-course-stage ms-2">{{ $bc->stage_label }}</span>
                                    </div>
                                </div>
                                <i class="las la-check ak23-bundle-check-icon"></i>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Bundle Column -->
                <div class="col-lg-5">
                    <div class="ak23-bundle-right ps-lg-4">
                        <div>
                            <div class="ak23-bundle-price-header">COMPLETE PACKAGE TOTAL</div>
                            <div class="ak23-bundle-price-val">
                                TSH {{ number_format($akBundle->price) }}
                            </div>
                            <div class="ak23-bundle-price-sub">ONE PACKAGE &middot; ALL COURSES &amp; SOFTWARE LICENSES INCLUDED</div>

                            <div class="ak23-included-title">Everything included</div>
                            <ul class="ak23-included-list">
                                <li class="ak23-included-item">
                                    <i class="las la-check"></i>
                                    <span>All {{ $akCourses->count() }} downloadable studio video courses</span>
                                </li>
                                <li class="ak23-included-item">
                                    <i class="las la-check"></i>
                                    <span>{{ $akCourses->count() }} corresponding genuine licensed software packages</span>
                                </li>
                                <li class="ak23-included-item">
                                    <i class="las la-check"></i>
                                    <span>Multitrack studio practice stems and resources included</span>
                                </li>
                                <li class="ak23-included-item">
                                    <i class="las la-check"></i>
                                    <span>Step-by-step software installation and license activation guidance</span>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <div class="ak23-bundle-notice">
                                <div class="ak23-bundle-notice-title">
                                    <i class="las la-shield-alt"></i>
                                    <span>Lifetime Licenses Included</span>
                                </div>
                                <p class="ak23-bundle-notice-text">
                                    Instant download access and genuine activation keys delivered immediately.
                                </p>
                            </div>

                            <button type="button" 
                                    class="ak23-bundle-cta-btn ak23-btn-add-cart w-100 justify-content-center border-0"
                                    data-sku="{{ $akBundle->sku ?: 'AK23-BUNDLE-VOCAL' }}">
                                <span>Get the complete series</span>
                                <i class="las la-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Explore Full Catalog CTA -->
        <div class="text-center mt-5 pt-2">
            <a href="{{ route('products') }}" class="btn btn-outline-warning rounded-pill px-4 py-2 fs-13 fw-bold">
                <i class="las la-layer-group me-1"></i> Explore Full Catalog ({{ $allCourses->count() }} Courses &amp; Bundles)
            </a>
        </div>
    </div>
</section>


<!-- =========================================================================
     PROGRESSION SECTION ("02 / WHAT YOU'LL LEARN")
     ========================================================================= -->
<section class="ak23-progression">
    <div class="ak23-container">
        <span class="ak23-section-tag">02 / WHAT YOU'LL LEARN</span>
        <h2 class="ak23-section-title">From raw vocal to finished chain.</h2>
        <p class="ak23-section-desc">
            Build one practical skill at a time. Every step connects to the next, so the process stays clear and manageable.
        </p>

        @php
            $progSteps = @$akSiteContent['progression'] ?: [
                'Understand the tools',
                'Control vocal levels',
                'Shape tone',
                'Add space & character',
                'Build a complete chain'
            ];
        @endphp

        <div class="ak23-steps-container">
            @foreach($progSteps as $idx => $step)
                <div class="ak23-step-card">
                    <div class="ak23-step-top">
                        <span class="ak23-step-seq">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        @if($idx < count($progSteps) - 1)
                            <i class="las la-arrow-right ak23-step-arrow"></i>
                        @endif
                    </div>
                    <p class="ak23-step-text">{{ $step }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>


<!-- =========================================================================
     AUDIO COMPARISON SECTION ("HEAR THE DIFFERENCE")
     ========================================================================= -->
<section class="ak23-comparison">
    <div class="ak23-container">
        <div class="row align-items-center g-4">
            <!-- Left Info -->
            <div class="col-lg-5">
                <span class="ak23-section-tag">HEAR THE DIFFERENCE</span>
                <h2 class="ak23-section-title">Raw vs. processed vocal</h2>
                <p class="ak23-section-desc">
                    Use matched playback levels to focus on the changes in control, tone, and space—not just loudness.
                </p>
                <div class="ak23-audio-pill"><i class="las la-volume-up me-1"></i> INTERACTIVE AUDIO DEMO &middot; REAL A/B COMPARISON</div>
            </div>

            <!-- Right Audio Players -->
            <div class="col-lg-7">
                <!-- Track 1: Raw Vocal -->
                <div class="ak23-track-card">
                    <button type="button" class="ak23-track-play audio-demo-trigger" data-track="raw" aria-label="Play Raw Vocal">
                        <i class="las la-play"></i>
                    </button>
                    <div class="ak23-track-meta">
                        <div class="ak23-track-title">RAW VOCAL</div>
                        <p class="ak23-track-sub">Matched level &middot; Unprocessed</p>
                    </div>
                    <div class="ak23-track-waveform-box" id="waveformRaw">
                        @php
                            $barsRaw = [8, 14, 22, 16, 28, 34, 18, 12, 24, 30, 20, 14, 26, 32, 22, 16, 28, 34, 18, 10, 22, 28, 16, 8, 18, 26, 14, 10];
                        @endphp
                        @foreach($barsRaw as $b)
                            <div class="bar" style="height: {{ $b }}px;"></div>
                        @endforeach
                    </div>
                    <div class="ak23-track-time">0:28</div>
                </div>

                <!-- Track 2: Processed Vocal -->
                <div class="ak23-track-card">
                    <button type="button" class="ak23-track-play audio-demo-trigger" data-track="processed" aria-label="Play Processed Vocal">
                        <i class="las la-play"></i>
                    </button>
                    <div class="ak23-track-meta">
                        <div class="ak23-track-title">PROCESSED VOCAL</div>
                        <p class="ak23-track-sub">Matched level &middot; Processed</p>
                    </div>
                    <div class="ak23-track-waveform-box" id="waveformProcessed">
                        @php
                            $barsProc = [14, 24, 34, 28, 36, 38, 32, 20, 32, 38, 30, 24, 36, 38, 32, 26, 36, 38, 30, 18, 30, 36, 26, 16, 28, 36, 22, 14];
                        @endphp
                        @foreach($barsProc as $b)
                            <div class="bar" style="height: {{ $b }}px;"></div>
                        @endforeach
                    </div>
                    <div class="ak23-track-time">0:28</div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- =========================================================================
     PURCHASE & ACCESS SECTION ("03 / PURCHASE & ACCESS")
     ========================================================================= -->
<section class="ak23-how-it-works">
    <div class="ak23-container">
        <span class="ak23-section-tag">03 / PURCHASE & ACCESS</span>
        <h2 class="ak23-section-title">Choose. Pay. Start learning.</h2>
        <p class="ak23-section-desc">
            A straightforward digital purchase with no shipping address or physical equipment required.
        </p>

        <!-- 3 Step Cards -->
        <div class="ak23-how-grid">
            <div class="ak23-how-card">
                <div class="ak23-how-num">01</div>
                <h3 class="ak23-how-title">Choose your package</h3>
                <p class="ak23-how-desc">
                    Select one course with its matching license, or choose the complete series.
                </p>
            </div>

            <div class="ak23-how-card">
                <div class="ak23-how-num">02</div>
                <h3 class="ak23-how-title">Complete payment</h3>
                <p class="ak23-how-desc">
                    Review compatibility, license terms, taxes, and your final total before paying.
                </p>
            </div>

            <div class="ak23-how-card">
                <div class="ak23-how-num">03</div>
                <h3 class="ak23-how-title">Download & activate</h3>
                <p class="ak23-how-desc">
                    Download lessons for offline learning and follow the separate software activation instructions.
                </p>
            </div>
        </div>

        <!-- Separation Notice Banner -->
        <div class="ak23-separation-notice">
            <i class="las la-exclamation-triangle ak23-separation-icon"></i>
            <p class="ak23-separation-text">
                <strong>Two parts, clearly separated.</strong> Promotional previews play here on the website. Full course videos are downloaded for offline learning; licensed software is delivered with separate installation and activation guidance.
            </p>
        </div>
    </div>
</section>


<!-- =========================================================================
     FAQ SECTION ("04 / FAQ")
     ========================================================================= -->
@php
    $waExt = \App\Models\Extension::where('act', 'whatsapp-chat')->where('status', \App\Constants\Status::ENABLE)->first();
    $waPhone = @$waExt->shortcode->phone_number->value ?? '255755000000';
    $waUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $waPhone) . '?text=' . urlencode('Hello Maarifa23 Academy! I have a question about the vocal mixing courses.');
    $totalFaqs = $akFaqs->count();
    $halfFaqs = ceil($totalFaqs / 2);
    $faqCol1 = $akFaqs->slice(0, $halfFaqs);
    $faqCol2 = $akFaqs->slice($halfFaqs);
@endphp

<section class="ak23-faq-section" id="faq">
    <div class="ak23-container">
        <!-- Compact Section Header -->
        <div class="ak23-faq-header-top text-center mb-4">
            <span class="ak23-section-tag"><i class="las la-question-circle"></i> 04 / FREQUENTLY ASKED QUESTIONS</span>
            <h2 class="ak23-faq-compact-title">
                Good questions. <span class="ak23-text-gold">Clear answers.</span>
            </h2>
            <p class="ak23-faq-compact-sub">
                Everything you need to know about course access, DAW compatibility, software licenses, and Selcom payment.
            </p>
        </div>

        <!-- 2-Column Compact Accordion Grid -->
        <div class="row g-3">
            <!-- Left Accordion Column -->
            <div class="col-lg-6">
                <div class="ak23-faq-accordion-col">
                    @forelse($faqCol1 as $index => $faq)
                        <div class="ak23-faq-item {{ $index === 0 ? 'active' : '' }}">
                            <div class="ak23-faq-header" onclick="toggleFaq(this)">
                                <h3 class="ak23-faq-question">{{ $faq->question }}</h3>
                                <i class="las la-angle-down ak23-faq-chevron"></i>
                            </div>
                            <div class="ak23-faq-body" style="{{ $index === 0 ? 'display: block;' : '' }}">
                                <p class="ak23-faq-answer">{{ $faq->answer }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">FAQs are currently being updated.</div>
                    @endforelse
                </div>
            </div>

            <!-- Right Accordion Column -->
            <div class="col-lg-6">
                <div class="ak23-faq-accordion-col">
                    @foreach($faqCol2 as $index => $faq)
                        <div class="ak23-faq-item">
                            <div class="ak23-faq-header" onclick="toggleFaq(this)">
                                <h3 class="ak23-faq-question">{{ $faq->question }}</h3>
                                <i class="las la-angle-down ak23-faq-chevron"></i>
                            </div>
                            <div class="ak23-faq-body">
                                <p class="ak23-faq-answer">{{ $faq->answer }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Compact Bottom Support Bar -->
        <div class="ak23-faq-bottom-bar">
            <div class="ak23-faq-help-left">
                <div class="ak23-faq-help-icon">
                    <i class="las la-headset"></i>
                </div>
                <div>
                    <h4 class="ak23-faq-help-title">Still have questions before enrolling?</h4>
                    <p class="ak23-faq-help-desc">Our vocal mixing instructors and sound engineers are online to assist you.</p>
                </div>
            </div>
            <div class="ak23-faq-help-actions">
                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="ak23-btn-wa-sm">
                    <i class="lab la-whatsapp"></i> Chat on WhatsApp
                </a>
                <a href="{{ route('contact') }}" class="ak23-btn-contact-sm">
                    <i class="las la-envelope"></i> Contact Support
                </a>
            </div>
        </div>
    </div>
</section>


<!-- =========================================================================
     VIDEO PREVIEW MODAL
     ========================================================================= -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background: #111118; border: 1px solid #2B2B3C; border-radius: 12px; color: #fff;">
            <div class="modal-header border-bottom border-secondary" style="border-color: #232332 !important;">
                <h5 class="modal-title" id="previewModalLabel" style="font-size: 15px; font-weight: 700; color: #F0A62F;">
                    <i class="las la-play-circle me-1"></i> Maarifa23 Academy &middot; Vocal Mixing Series Preview
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; background: #000;">
                    <iframe style="position: absolute; top:0; left: 0; width: 100%; height: 100%; border:0;" 
                            src="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ?autoplay=0" 
                            title="Course Preview Video" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                    </iframe>
                </div>
            </div>
            <div class="modal-footer border-top border-secondary justify-content-between" style="border-color: #232332 !important; padding: 14px 20px;">
                <span style="font-size: 12.5px; color: #9A9AA6;">Downloadable high-res course files provided after purchase.</span>
                <a href="#courses" data-bs-dismiss="modal" class="ak23-btn-gold" style="padding: 8px 18px; font-size: 12px;">
                    <span>View Courses</span>
                    <i class="las la-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
<style>
/* 4 Courses Per Row Grid - Exactly matching sample */
.ak23-course-grid-4 {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 24px;
    margin-bottom: 34px;
}

@media (max-width: 1200px) {
    .ak23-course-grid-4 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
    }
}

@media (max-width: 860px) {
    .ak23-course-grid-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }
}

@media (max-width: 540px) {
    .ak23-course-grid-4 {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}

/* Sample Card: Crisp White Card with Rounded Corners & Soft Shadow */
.ak23-card-sample {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1), 
                box-shadow 0.22s cubic-bezier(0.16, 1, 0.3, 1), 
                border-color 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    cursor: pointer;
    user-select: none;
}

.ak23-card-sample:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 32px -4px rgba(0, 0, 0, 0.12), 0 0 0 1px rgba(251, 175, 67, 0.25);
    border-color: #FBAF43;
}

.ak23-card-sample:active {
    transform: translateY(-2px);
}

/* Thumbnail Area with Aspect Ratio & Overflow Hidden */
.ak23-sample-thumb-wrap {
    position: relative;
    width: 100%;
    height: 185px;
    overflow: hidden;
    background: #0E0F16;
}

.ak23-thumb-link {
    display: block;
    width: 100%;
    height: 100%;
}

.ak23-sample-thumb-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.35s ease;
}

.ak23-card-sample:hover .ak23-sample-thumb-img {
    transform: scale(1.05);
}

/* Floating Circular Cart Button at Top-Right of thumbnail */
.ak23-sample-cart-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #FFFFFF;
    border: 1px solid rgba(0, 0, 0, 0.08);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.16);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #EA580C;
    font-size: 18px;
    cursor: pointer;
    z-index: 5;
    transition: all 0.2s ease;
    padding: 0;
}

.ak23-sample-cart-btn:hover {
    background: #EA580C;
    color: #FFFFFF !important;
    border-color: #EA580C;
    transform: scale(1.12);
    box-shadow: 0 4px 14px rgba(234, 88, 12, 0.4);
}

.ak23-sample-cart-btn:active {
    transform: scale(0.96);
}

/* Card Body */
.ak23-sample-card-body {
    padding: 16px 18px 16px;
    display: flex;
    flex-direction: column;
    flex: 1;
    background: #FFFFFF;
}

/* Title: Clean, High Contrast, 2 Lines */
.ak23-sample-card-title {
    font-family: inherit;
    font-size: 15.5px;
    font-weight: 700;
    line-height: 1.35;
    margin: 0 0 10px 0;
    min-height: 42px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.ak23-sample-card-title a {
    color: #0F172A !important;
    text-decoration: none;
    transition: color 0.18s ease;
}

.ak23-sample-card-title a:hover {
    color: #EA580C !important;
}

/* Price: Bright Amber/Orange */
.ak23-sample-card-price {
    font-size: 17.5px;
    font-weight: 800;
    color: #F59E0B; /* Bright golden amber matching sample */
    letter-spacing: -0.01em;
    margin-bottom: 14px;
    line-height: 1.2;
}

/* Meta: Subtle Gray Row */
.ak23-sample-card-meta {
    margin-top: auto;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    font-weight: 600;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.ak23-meta-tag {
    color: #64748B;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 60%;
}

.ak23-meta-sep {
    color: #94A3B8;
}

.ak23-meta-date {
    color: #94A3B8;
    white-space: nowrap;
}

/* Series Filter Buttons on Home */
.ak23-filter-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #A0A0B0 !important;
    border-radius: 20px;
    padding: 7px 16px;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.2s ease;
    text-decoration: none !important;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
}

.ak23-filter-btn:hover {
    background: rgba(251, 175, 67, 0.12);
    border-color: rgba(251, 175, 67, 0.4);
    color: #FBAF43 !important;
}

.ak23-filter-btn.active {
    background: #FBAF43;
    border-color: #FBAF43;
    color: #0F0F14 !important;
    font-weight: 700;
}
</style>
@endpush

@push('script')
<script>
    // FAQ Accordion Toggle
    function toggleFaq(headerEl) {
        const item = headerEl.parentElement;
        const body = item.querySelector('.ak23-faq-body');
        const isActive = item.classList.contains('active');

        // Close all items
        document.querySelectorAll('.ak23-faq-item').forEach(el => {
            el.classList.remove('active');
            const b = el.querySelector('.ak23-faq-body');
            if (b) $(b).slideUp(200);
        });

        if (!isActive) {
            item.classList.add('active');
            $(body).slideDown(200);
        }
    }

    $(document).ready(function() {
        // 1. Interactive Demo Audio Player Simulation
        $('.audio-demo-trigger').on('click', function() {
            const btn = $(this);
            const icon = btn.find('i');
            const trackType = btn.data('track');
            const waveformBox = btn.closest('.ak23-track-card').find('.ak23-track-waveform-box');

            if (icon.hasClass('la-play')) {
                $('.audio-demo-trigger i').removeClass('la-pause').addClass('la-play');
                $('.ak23-track-waveform-box .bar').css('opacity', '0.7');

                icon.removeClass('la-play').addClass('la-pause');
                waveformBox.find('.bar').each(function(i) {
                    $(this).css({
                        'opacity': '1',
                        'transition': 'height 0.2s ease'
                    });
                });
            } else {
                icon.removeClass('la-pause').addClass('la-play');
                waveformBox.find('.bar').css('opacity', '0.7');
            }
        });

        // 2. Fully Clickable Course Card Navigation
        $(document).on('click', '.ak23-card-sample', function(e) {
            if ($(e.target).closest('.ak23-sample-cart-btn, .ak23-btn-add-cart, a, button, input').length) {
                return;
            }
            const targetUrl = $(this).data('url');
            if (targetUrl) {
                window.location.href = targetUrl;
            }
        });

        $(document).on('keydown', '.ak23-card-sample', function(e) {
            if (e.key === 'Enter') {
                if ($(e.target).closest('.ak23-sample-cart-btn, .ak23-btn-add-cart, a, button, input').length) {
                    return;
                }
                const targetUrl = $(this).data('url');
                if (targetUrl) {
                    window.location.href = targetUrl;
                }
            }
        });

        // 3. Home Series Filter Pills Toggling
        $('#homeSeriesPills .ak23-filter-btn').on('click', function(e) {
            e.preventDefault();
            $('#homeSeriesPills .ak23-filter-btn').removeClass('active');
            $(this).addClass('active');

            const selectedSeries = $(this).data('series');

            if (selectedSeries === 'bundles') {
                $('#homeCourseGrid').addClass('d-none');
                $('#homeBundlesGrid').removeClass('d-none');
            } else {
                $('#homeBundlesGrid').addClass('d-none');
                $('#homeCourseGrid').removeClass('d-none');

                if (selectedSeries === 'all') {
                    $('.ak23-home-course-item').removeClass('d-none');
                } else {
                    $('.ak23-home-course-item').each(function() {
                        const itemSeries = $(this).data('series');
                        if (itemSeries === selectedSeries) {
                            $(this).removeClass('d-none');
                        } else {
                            $(this).addClass('d-none');
                        }
                    });
                }
            }
        });

        // 4. AJAX Quick Add to Cart
        $(document).off('click', '.ak23-btn-add-cart').on('click', '.ak23-btn-add-cart', function(e) {
            e.preventDefault();
            e.stopPropagation();
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
                    if (typeof notify === 'function') {
                        notify(res.status || 'success', res.message);
                    }
                    if (res.cart_count !== undefined) {
                        if (typeof window.updateAk23CartCount === 'function') {
                            window.updateAk23CartCount(res.cart_count);
                        } else {
                            $('.ak23-cart-count').text(res.cart_count);
                        }
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
                    if (typeof notify === 'function') {
                        notify('error', errMsg);
                    }
                }
            });
        });
    });
</script>
@endpush
