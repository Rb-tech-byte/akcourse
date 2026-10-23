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
                    Learn to Mix<br>
                    Vocals.<br>
                    <span class="ak23-text-gold">One Plugin at a<br>Time.</span>
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
                            AK23 ACADEMY / SERIES PREVIEW
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
                <strong>New to vocal mixing?</strong> Begin with Tuned Plugins to build a simple workflow, then move through the series in order.
            </p>
        </div>

        <!-- Course Cards Grid -->
        <div class="ak23-course-grid">
            @forelse($akCourses as $course)
                @php
                    $seqPadded = str_pad($course->sequence_no, 2, '0', STR_PAD_LEFT);
                    $stageClass = match($course->sequence_no) {
                        1 => 'stage-getting-started',
                        2 => 'stage-beginner',
                        3 => 'stage-developing',
                        4 => 'stage-intermediate',
                        5 => 'stage-advanced',
                        default => 'stage-beginner',
                    };
                    $accentColor = $course->accent_color ?: '#F0A62F';
                @endphp
                <div class="ak23-course-card">
                    <!-- Visual Mockup Top -->
                    <div class="ak23-course-visual">
                        <span class="ak23-seq-circle">{{ $seqPadded }}</span>
                        <span class="ak23-stage-badge {{ $stageClass }}">{{ $course->stage_label }}</span>

                        <!-- SVG Plugin Graphic Matching Mockup -->
                        @if($course->sequence_no == 1)
                            <!-- Tuned Plugins SVG -->
                            <svg width="270" height="135" viewBox="0 0 270 135" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="15" y="15" width="240" height="105" rx="6" fill="#13131D" stroke="#252535" stroke-width="1"/>
                                <text x="30" y="34" fill="#D9A742" font-size="9" font-weight="800" letter-spacing="1">TUNED // PITCH ENGINE</text>
                                <line x1="30" y1="42" x2="240" y2="42" stroke="#252535" stroke-width="1"/>
                                <!-- Grid lines -->
                                <line x1="30" y1="60" x2="240" y2="60" stroke="#1C1C28" stroke-dasharray="2 2"/>
                                <line x1="30" y1="80" x2="240" y2="80" stroke="#1C1C28" stroke-dasharray="2 2"/>
                                <!-- Wave curve -->
                                <path d="M30 75 Q60 45 90 65 T150 55 T210 70 T240 50" fill="none" stroke="#D9A742" stroke-width="2"/>
                                <!-- Knobs -->
                                <circle cx="55" cy="100" r="10" fill="#191924" stroke="#D9A742" stroke-width="1.5"/>
                                <circle cx="115" cy="100" r="10" fill="#191924" stroke="#D9A742" stroke-width="1.5"/>
                                <circle cx="175" cy="100" r="10" fill="#191924" stroke="#D9A742" stroke-width="1.5"/>
                                <circle cx="225" cy="100" r="8" fill="#191924" stroke="#2F2F40" stroke-width="1.5"/>
                            </svg>
                        @elseif($course->sequence_no == 2)
                            <!-- Cymatics Voxity SVG -->
                            <svg width="270" height="135" viewBox="0 0 270 135" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="15" y="15" width="240" height="105" rx="6" fill="#13131D" stroke="#252535" stroke-width="1"/>
                                <text x="30" y="34" fill="#DE784D" font-size="9" font-weight="800" letter-spacing="1">VOXITY // MULTI-FX UNIT</text>
                                <line x1="30" y1="42" x2="240" y2="42" stroke="#252535" stroke-width="1"/>
                                <!-- Circular visualizer -->
                                <circle cx="135" cy="72" r="22" fill="#171724" stroke="#DE784D" stroke-width="1.5" stroke-dasharray="3 2"/>
                                <circle cx="135" cy="72" r="12" fill="#DE784D" fill-opacity="0.2"/>
                                <!-- Sliders & knobs -->
                                <rect x="40" y="55" width="12" height="35" rx="2" fill="#191926" stroke="#28283C"/>
                                <rect x="42" y="65" width="8" height="6" rx="1" fill="#DE784D"/>
                                <rect x="70" y="55" width="12" height="35" rx="2" fill="#191926" stroke="#28283C"/>
                                <rect x="72" y="72" width="8" height="6" rx="1" fill="#DE784D"/>
                                <circle cx="195" cy="72" r="12" fill="#191924" stroke="#DE784D" stroke-width="1.5"/>
                                <circle cx="230" cy="72" r="10" fill="#191924" stroke="#2F2F40" stroke-width="1.5"/>
                            </svg>
                        @elseif($course->sequence_no == 3)
                            <!-- Nuro Audio Xvox Pro SVG -->
                            <svg width="270" height="135" viewBox="0 0 270 135" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="15" y="15" width="240" height="105" rx="6" fill="#13131D" stroke="#252535" stroke-width="1"/>
                                <text x="30" y="34" fill="#9B77D2" font-size="9" font-weight="800" letter-spacing="1">XVOX PRO // VOCAL STRIP</text>
                                <line x1="30" y1="42" x2="240" y2="42" stroke="#252535" stroke-width="1"/>
                                <!-- Module blocks -->
                                <rect x="30" y="52" width="38" height="42" rx="3" fill="#181826" stroke="#2E2840"/>
                                <rect x="74" y="52" width="38" height="42" rx="3" fill="#181826" stroke="#2E2840"/>
                                <rect x="118" y="52" width="38" height="42" rx="3" fill="#181826" stroke="#9B77D2" stroke-width="1.5"/>
                                <rect x="162" y="52" width="38" height="42" rx="3" fill="#181826" stroke="#2E2840"/>
                                <rect x="206" y="52" width="34" height="42" rx="3" fill="#181826" stroke="#2E2840"/>
                                <!-- VU Bars -->
                                <rect x="36" y="60" width="26" height="3" fill="#9B77D2"/>
                                <rect x="80" y="68" width="26" height="3" fill="#9B77D2"/>
                                <rect x="124" y="62" width="26" height="4" fill="#9B77D2"/>
                                <text x="124" y="85" fill="#9B77D2" font-size="7" font-weight="700">COMP</text>
                            </svg>
                        @elseif($course->sequence_no == 4)
                            <!-- iZotope Nectar 4 SVG -->
                            <svg width="270" height="135" viewBox="0 0 270 135" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="15" y="15" width="240" height="105" rx="6" fill="#13131D" stroke="#252535" stroke-width="1"/>
                                <text x="30" y="34" fill="#ED7C66" font-size="9" font-weight="800" letter-spacing="1">NECTAR 4 // VOCAL ASSISTANT</text>
                                <line x1="30" y1="42" x2="240" y2="42" stroke="#252535" stroke-width="1"/>
                                <!-- Dynamic EQ curve -->
                                <path d="M30 78 Q75 40 120 75 T190 60 T240 82" fill="none" stroke="#ED7C66" stroke-width="2"/>
                                <circle cx="85" cy="52" r="4" fill="#ED7C66"/>
                                <circle cx="160" cy="68" r="4" fill="#ED7C66"/>
                                <circle cx="210" cy="74" r="4" fill="#ED7C66"/>
                                <!-- Bottom buttons -->
                                <rect x="30" y="94" width="45" height="14" rx="2" fill="#1D1D2C"/>
                                <rect x="82" y="94" width="45" height="14" rx="2" fill="#1D1D2C"/>
                                <rect x="134" y="94" width="45" height="14" rx="2" fill="#1D1D2C"/>
                                <rect x="186" y="94" width="54" height="14" rx="2" fill="#ED7C66" fill-opacity="0.2" stroke="#ED7C66" stroke-width="0.8"/>
                            </svg>
                        @else
                            <!-- Waves Ultimate 17 SVG -->
                            <svg width="270" height="135" viewBox="0 0 270 135" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="15" y="15" width="240" height="105" rx="6" fill="#13131D" stroke="#252535" stroke-width="1"/>
                                <text x="30" y="34" fill="#4FA6C9" font-size="9" font-weight="800" letter-spacing="1">WAVES 17 // STUDIO RACK</text>
                                <line x1="30" y1="42" x2="240" y2="42" stroke="#252535" stroke-width="1"/>
                                <!-- Analog VU meter -->
                                <rect x="30" y="52" width="80" height="40" rx="3" fill="#0E171C" stroke="#1D303B"/>
                                <path d="M45 82 Q70 60 95 82" fill="none" stroke="#284858"/>
                                <line x1="70" y1="84" x2="80" y2="62" stroke="#4FA6C9" stroke-width="1.5"/>
                                <!-- Rack screws & knobs -->
                                <circle cx="135" cy="65" r="10" fill="#18232A" stroke="#4FA6C9" stroke-width="1.5"/>
                                <circle cx="170" cy="65" r="10" fill="#18232A" stroke="#4FA6C9" stroke-width="1.5"/>
                                <circle cx="205" cy="65" r="10" fill="#18232A" stroke="#4FA6C9" stroke-width="1.5"/>
                                <circle cx="232" cy="65" r="7" fill="#131A1F" stroke="#25353F"/>
                                <line x1="125" y1="95" x2="240" y2="95" stroke="#1D303B" stroke-dasharray="3 3"/>
                            </svg>
                        @endif
                    </div>

                    <!-- Card Body -->
                    <div class="ak23-course-body">
                        <h3 class="ak23-course-title">{{ $course->title }}</h3>
                        <p class="ak23-course-outcome">{{ $course->learning_outcome }}</p>

                        <div class="ak23-course-meta">
                            {{ $course->lesson_count ? $course->lesson_count . ' LESSONS' : '[LESSON COUNT]' }} &bull; {{ $course->video_duration_seconds ? round($course->video_duration_seconds / 60) . ' MIN' : '[VIDEO DURATION]' }}
                        </div>

                        <a href="javascript:void(0)" class="ak23-preview-link" data-bs-toggle="modal" data-bs-target="#previewModal">
                            <i class="las la-play"></i>
                            <span>PREVIEW COURSE</span>
                        </a>

                        <div class="ak23-license-pill">
                            <i class="las la-shield-alt"></i>
                            <span>PLUGIN LICENSE INCLUDED</span>
                        </div>

                        <div class="ak23-software-section">
                            <div class="ak23-software-tag">LICENSED SOFTWARE INCLUDED</div>
                            <p class="ak23-software-name">{{ $course->title }} &mdash; {{ $course->software_edition }}</p>
                        </div>

                        <!-- Card Footer -->
                        <div class="ak23-course-card-footer">
                            <div class="ak23-price-group">
                                <span class="ak23-price-val">
                                    {{ $course->price_minor ? 'TSH ' . number_format($course->price_minor) : 'TSH [PRICE]' }}
                                </span>
                                <span class="ak23-price-label">TOTAL PACKAGE PRICE</span>
                            </div>

                            <a href="{{ route('cart.index') }}" class="ak23-card-btn">
                                <span>Get course + software</span>
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Courses are currently being updated.</p>
                </div>
            @endforelse
        </div>

        <!-- =========================================================================
             COMPLETE SERIES BUNDLE CARD ("BEST FOR THE FULL JOURNEY")
             ========================================================================= -->
        <div class="ak23-bundle-card">
            <div class="row g-4 align-items-center">
                <!-- Left Bundle Column -->
                <div class="col-lg-7">
                    <div class="ak23-bundle-badge">
                        <i class="las la-star"></i>
                        <span>BEST FOR THE FULL JOURNEY</span>
                    </div>

                    <h3 class="ak23-bundle-title">
                        Get the Complete<br>
                        <span class="ak23-text-gold">Vocal Mixing Series</span>
                    </h3>

                    <p class="ak23-bundle-desc">
                        Follow the full sequence from your first vocal workflow to a complete multi-plugin chain—without needing to add any individual course packages.
                    </p>

                    <!-- 5 Courses Checklist Box -->
                    <div class="ak23-bundle-checklist-box">
                        @foreach($akCourses as $bc)
                            <div class="ak23-bundle-course-row">
                                <div class="ak23-bundle-course-left">
                                    <span class="ak23-bundle-course-seq">{{ str_pad($bc->sequence_no, 2, '0', STR_PAD_LEFT) }}</span>
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
                            <div class="ak23-bundle-price-header">COMPLETE SERIES TOTAL</div>
                            <div class="ak23-bundle-price-val">
                                {{ @$akBundle->price_minor ? 'TSH ' . number_format($akBundle->price_minor) : 'TSH [SERIES PRICE]' }}
                            </div>
                            <div class="ak23-bundle-price-sub">ONE PACKAGE &middot; NO DUPLICATE ADD-ONS NEEDED</div>

                            <div class="ak23-included-title">Everything included</div>
                            <ul class="ak23-included-list">
                                <li class="ak23-included-item">
                                    <i class="las la-check"></i>
                                    <span>All five downloadable video courses</span>
                                </li>
                                <li class="ak23-included-item">
                                    <i class="las la-check"></i>
                                    <span>Five corresponding licensed software packages</span>
                                </li>
                                <li class="ak23-included-item">
                                    <i class="las la-check"></i>
                                    <span>[Confirmed practice files and resources]</span>
                                </li>
                                <li class="ak23-included-item">
                                    <i class="las la-check"></i>
                                    <span>Software installation and activation guidance</span>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <div class="ak23-bundle-notice">
                                <div class="ak23-bundle-notice-title">
                                    <i class="las la-shield-alt"></i>
                                    <span>Licenses are included</span>
                                </div>
                                <p class="ak23-bundle-notice-text">
                                    Exact editions and license terms will be shown before checkout.
                                </p>
                            </div>

                            <a href="{{ route('cart.index') }}" class="ak23-bundle-cta-btn">
                                <span>Get the complete series</span>
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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
                <div class="ak23-audio-pill">AUDIO FILES [ADD CONFIRMED EXAMPLES]</div>
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
    $waUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $waPhone) . '?text=' . urlencode('Hello AK23 Academy! I have a question about the vocal mixing courses.');
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
                    <i class="las la-play-circle me-1"></i> AK23 Academy &middot; Vocal Mixing Series Preview
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

    // Interactive Demo Audio Player Simulation
    $(document).ready(function() {
        $('.audio-demo-trigger').on('click', function() {
            const btn = $(this);
            const icon = btn.find('i');
            const trackType = btn.data('track');
            const waveformBox = btn.closest('.ak23-track-card').find('.ak23-track-waveform-box');

            if (icon.hasClass('la-play')) {
                // Pause any other playing
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
    });
</script>
@endpush
