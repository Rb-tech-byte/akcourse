@extends($activeTemplate . 'layouts.frontend')

@section('content')

@php
    $allCourses = collect($catalog)->flatMap(fn($i) => $i['courses']);
    $allBundles = collect($catalog)->map(fn($i) => $i['bundle'])->filter();
    $totalCoursesCount = $allCourses->count();
    $totalBundlesCount = $allBundles->count();
@endphp

<!-- =========================================================================
     COMPACT HERO & REAL-TIME SEARCH / FILTER CONTROL BAR
     ========================================================================= -->
<div class="ak23-catalog-top-bar">
    <div class="ak23-container">
        <!-- Top Row: Page Title & Real-Time Search -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <div>
                <span class="ak23-catalog-bar-tag"><i class="las la-graduation-cap text-warning me-1"></i> AUDIO PRODUCTION CURRICULUM</span>
                <h1 class="ak23-catalog-bar-title mb-1">Courses &amp; Production Software</h1>
                <p class="text-muted fs-13 mb-0">Hands-on studio workflows paired with genuine software licenses, multitrack stems, and lifetime access.</p>
            </div>

            <!-- Real-time Live Search Input -->
            <div class="ak23-search-box">
                <i class="las la-search search-icon"></i>
                <input type="text" id="ak23LiveSearch" class="form-control" placeholder="Search by course title, DAW, plugin, or topic..." autocomplete="off">
                <button type="button" id="ak23ClearSearch" class="clear-btn d-none" title="Clear search">
                    <i class="las la-times-circle"></i>
                </button>
            </div>
        </div>

        <!-- Filter Controls Row: Category Pills, Level Filter, Sort & Live Count -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2 border-top" style="border-color: rgba(255, 255, 255, 0.08) !important;">
            <!-- Category Series Filter Pills -->
            <div class="d-flex align-items-center gap-2 flex-wrap" id="ak23SeriesFilterPills">
                <button type="button" class="btn btn-sm ak23-filter-btn {{ !request('series') && !request('category') ? 'active' : '' }}" data-series="all">
                    <span>All Courses ({{ $totalCoursesCount }})</span>
                </button>
                @foreach($catalog as $cItem)
                    @php
                        $cSeries = $cItem['series'];
                        $isPillActive = request('series') === $cSeries->slug 
                            || request('category') === $cSeries->slug 
                            || (string)request('category') === (string)$cSeries->id;
                    @endphp
                    <button type="button" class="btn btn-sm ak23-filter-btn {{ $isPillActive ? 'active' : '' }}" 
                            data-series="{{ $cSeries->slug }}" 
                            data-category-id="{{ $cSeries->id }}">
                        <span>{{ $cSeries->title }} ({{ count($cItem['courses']) }})</span>
                    </button>
                @endforeach
                <!-- Bundles Quick Filter Pill -->
                <button type="button" class="btn btn-sm ak23-filter-btn {{ request('series') === 'bundles' ? 'active' : '' }}" data-series="bundles">
                    <span><i class="las la-cubes me-1"></i>Series Bundles ({{ $totalBundlesCount }})</span>
                </button>
            </div>

            <!-- Right Controls: Level Filter, Sort Dropdown & Match Counter -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Level Filter -->
                <select id="ak23LevelFilter" class="form-select form-select-sm ak23-select-filter" style="width: auto;" aria-label="Filter by level">
                    <option value="all">All Levels</option>
                    <option value="getting-started">Getting Started</option>
                    <option value="beginner">Beginner</option>
                    <option value="developing-your-skills">Developing Skills</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>

                <!-- Sort Dropdown -->
                <select id="ak23SortFilter" class="form-select form-select-sm ak23-select-filter" style="width: auto;" aria-label="Sort courses">
                    <option value="default">Default Order</option>
                    <option value="price-asc">Price: Low to High</option>
                    <option value="price-desc">Price: High to Low</option>
                    <option value="title-asc">Title: A to Z</option>
                    <option value="lessons-desc">Lessons: High to Low</option>
                </select>

                <!-- Live Results Count -->
                <div class="text-muted fs-12 px-2" id="ak23LiveCount">
                    Showing <strong class="text-white" id="ak23CountNum">{{ $totalCoursesCount }}</strong> courses
                </div>
            </div>
        </div>

        <!-- Active Filters Chips Bar (Shown when any filter is active) -->
        <div id="ak23ActiveFiltersBar" class="d-flex align-items-center gap-2 flex-wrap pt-2 mt-2 border-top d-none" style="border-color: rgba(255, 255, 255, 0.05) !important;">
            <span class="text-muted fs-11 text-uppercase fw-bold letter-spacing-1">Active Filters:</span>
            <div id="ak23ChipsContainer" class="d-flex align-items-center gap-2 flex-wrap"></div>
            <button type="button" class="btn btn-link text-warning p-0 fs-11 text-decoration-none ms-1" id="ak23ClearAllChipsBtn">
                <i class="las la-times me-1"></i>Reset All
            </button>
        </div>
    </div>
</div>

<!-- =========================================================================
     CATALOG BODY (STRICTLY 3 PER ROW WITH 6-PER-PAGE PAGINATION)
     ========================================================================= -->
<div class="ak23-catalog-body">
    <div class="ak23-container">
        <!-- Target Anchor for Smooth Page Switch Scrolling -->
        <div id="ak23CatalogTopTarget"></div>

        <!-- Zero Results Fallback Banner -->
        <div id="ak23NoResults" class="ak23-no-results text-center py-5 d-none">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 64px; height: 64px; background: rgba(251, 175, 67, 0.1); color: #FBAF43; font-size: 28px;">
                <i class="las la-search"></i>
            </div>
            <h4 class="text-white fw-bold mb-1">No courses found matching your search</h4>
            <p class="text-muted fs-13 mb-3 mx-auto" style="max-width: 460px;">
                We couldn't find any courses matching <span class="text-warning fw-bold" id="ak23QueryDisplay">""</span>. Try searching by DAW (e.g., Cubase, FL Studio, Ableton) or specific skills like vocal tuning or beat arrangement.
            </p>
            <button type="button" class="btn btn-sm btn--base rounded-pill px-4 py-2" id="ak23ResetBtn">
                <i class="las la-redo me-1"></i> Reset All Filters
            </button>
        </div>

        <!-- Category Information & Bundle Highlights (Shown when a category is selected) -->
        <div id="ak23CategoryHeaderWrapper" class="mb-4 d-none">
            @foreach($catalog as $item)
                @php
                    $series = $item['series'];
                    $bundle = $item['bundle'];
                @endphp
                <div class="ak23-category-summary-box series-summary-{{ $series->slug }} d-none" data-series-slug="{{ $series->slug }}">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <div>
                            <span class="ak23-series-track-badge"><i class="las la-layer-group me-1"></i> CURRICULUM TRACK</span>
                            <h2 class="ak23-series-title mb-1">{{ $series->title }}</h2>
                            <p class="ak23-series-desc mb-0">{{ $series->description }}</p>
                        </div>
                        <a href="{{ route('products') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fs-12 ak23-view-all-trigger">
                            <i class="las la-th-large me-1"></i> View All Courses
                        </a>
                    </div>

                    @if($bundle)
                        @php
                            $bundleTitle = $bundle->title ?: ($bundle->name ?? 'Complete Series');
                            $bundlePrice = (float)($bundle->price ?: 480000);
                            $bundleSku = $bundle->sku ?: ('AK23-BUNDLE-' . strtoupper($series->slug));
                        @endphp
                        <div class="ak23-complete-pkg-banner">
                            <div>
                                <div class="ak23-pkg-banner-badge"><i class="las la-cubes me-1"></i> COMPLETE SERIES BUNDLE</div>
                                <h3 class="ak23-pkg-banner-title">
                                    <a href="{{ route('product.details', $bundle->slug) }}" class="text-white text-decoration-none">
                                        {{ $bundleTitle }}
                                    </a>
                                </h3>
                                <p class="ak23-pkg-banner-desc mb-0">{{ $bundle->description }}</p>
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
                                        <span>Enroll in Complete Series</span>
                                        <i class="las la-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Standalone Series Bundles View (Active when Series Bundles tab is clicked) -->
        <div id="ak23BundlesGridWrapper" class="d-none mb-4">
            <div class="mb-3">
                <span class="ak23-series-track-badge"><i class="las la-cubes me-1"></i> ALL-IN-ONE PACKAGES</span>
                <h2 class="ak23-series-title mb-1">Complete Curriculum Bundles</h2>
                <p class="ak23-series-desc mb-0">Get the full course track and included perpetual software licenses at a significant bundled discount.</p>
            </div>
            <div class="ak23-course-grid-3">
                @foreach($catalog as $item)
                    @php
                        $bundle = $item['bundle'];
                    @endphp
                    @if($bundle)
                        @php
                            $bTitle = $bundle->title ?: ($bundle->name ?? 'Complete Series');
                            $bPrice = (float)($bundle->price ?: 480000);
                            $bSku = $bundle->sku ?: ('AK23-BUNDLE-' . $bundle->id);
                            $bUrl = route('product.details', $bundle->slug);
                            $bAccent = $bundle->accent_color ?: '#FBAF43';
                        @endphp
                        <div class="ak23-card-minimal ak23-bundle-card-item"
                             data-url="{{ $bUrl }}"
                             data-title="{{ strtolower($bTitle) }}"
                             data-price="{{ $bPrice }}"
                             data-series="{{ $item['series']->slug }}"
                             tabindex="0"
                             role="link"
                             aria-label="{{ $bTitle }}">

                            <div class="ak23-card-visual-compact" style="height: 120px;">
                                <span class="ak23-pill-stage" style="border-color: #FBAF4355; color: #FBAF43; background: rgba(251, 175, 67, 0.12);">
                                    FULL TRACK BUNDLE
                                </span>
                                <svg viewBox="0 0 360 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="ak23-hardware-rack-svg" preserveAspectRatio="none">
                                    <rect width="360" height="120" fill="#12131F"/>
                                    <circle cx="180" cy="60" r="90" fill="{{ $bAccent }}" fill-opacity="0.12"/>
                                    <line x1="20" y1="24" x2="340" y2="24" stroke="#222436" stroke-width="1"/>
                                    <line x1="20" y1="96" x2="340" y2="96" stroke="#222436" stroke-width="1"/>
                                    <text x="180" y="55" fill="#FFFFFF" font-size="12" font-weight="800" text-anchor="middle" letter-spacing="1">
                                        {{ strtoupper($item['series']->title) }}
                                    </text>
                                    <text x="180" y="74" fill="{{ $bAccent }}" font-size="9" font-weight="700" text-anchor="middle" letter-spacing="1.5">
                                        ALL COURSES &amp; SOFTWARE LICENSES INCLUDED
                                    </text>
                                </svg>
                            </div>

                            <div class="ak23-card-body-compact">
                                <h3 class="ak23-card-title-compact" title="{{ $bTitle }}">
                                    <a href="{{ $bUrl }}" class="ak23-card-title-link stretched-link text-white text-decoration-none">
                                        {{ $bTitle }}
                                    </a>
                                </h3>
                                <p class="ak23-card-outcome-compact">
                                    {{ $bundle->description }}
                                </p>
                                <div class="ak23-card-footer-compact">
                                    <div>
                                        <span class="ak23-price-sub">BUNDLE SAVINGS</span>
                                        <span class="ak23-price-tag">TSH {{ number_format($bPrice) }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1 ak23-card-actions-wrapper">
                                        <button type="button" class="btn btn-sm btn-outline-warning ak23-btn-add-cart ak23-action-btn rounded-circle p-0 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" data-sku="{{ $bSku }}" title="Add to Cart">
                                            <i class="las la-cart-plus fs-16"></i>
                                        </button>
                                        <a href="{{ route('order.checkout', ['sku' => $bSku]) }}" class="btn btn-sm btn--base ak23-btn-enroll ak23-action-btn rounded-pill px-3 py-1 fs-12 fw-semibold d-inline-flex align-items-center gap-1">
                                            <span>Enroll</span>
                                            <i class="las la-arrow-right fs-10"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Main Course Grid: Strictly 3 Columns per Row, 6 Courses per Page -->
        <div class="ak23-course-grid-3" id="ak23CourseGrid">
            @foreach($catalog as $cItem)
                @php
                    $series = $cItem['series'];
                    $courses = $cItem['courses'];
                @endphp
                @foreach($courses as $course)
                    @php
                        $stageSlug = Str::slug($course->stage_label ?: 'beginner');
                        $courseTitle = $course->title ?: $course->name;
                        $coursePrice = (float)$course->price;
                        $courseSku = $course->sku ?: ('AK23-COURSE-' . $course->id);
                        $lessonCount = $course->lesson_count ?: 8;
                        $videoMin = $course->video_duration_seconds ? round($course->video_duration_seconds / 60) : 120;
                        $accentColor = $course->accent_color ?: '#FBAF43';
                        $hasCustomThumb = !empty($course->thumbnail) && $course->thumbnail !== 'default.png' && file_exists(public_path(getFilePath('productThumbnail') . '/' . productFilePath($course, 'thumbnail')));
                        $detailUrl = route('product.details', $course->slug);
                        $searchHaystack = strtolower($courseTitle . ' ' . $course->software_name . ' ' . $course->software_edition . ' ' . $course->stage_label . ' ' . $course->learning_outcome . ' ' . $courseSku . ' ' . $series->title . ' ' . $series->slug);
                    @endphp

                    <div class="ak23-card-minimal ak23-course-item"
                         data-url="{{ $detailUrl }}"
                         data-search="{{ $searchHaystack }}"
                         data-title="{{ strtolower($courseTitle) }}"
                         data-software="{{ strtolower($course->software_name) }}"
                         data-stage="{{ $stageSlug }}"
                         data-series="{{ $series->slug }}"
                         data-category-id="{{ $series->id }}"
                         data-price="{{ $coursePrice }}"
                         data-seq="{{ (int)($course->sequence_no ?: 99) }}"
                         data-lessons="{{ (int)$lessonCount }}"
                         tabindex="0"
                         role="link"
                         aria-label="{{ $courseTitle }}">

                        <!-- Header Visual: Custom Image or Dynamic Studio Hardware Rack Vector -->
                        <div class="ak23-card-visual-compact">
                            <!-- Track & Series Identifier Badge (Human Copy) -->
                            <span class="ak23-pill-category" style="color: {{ $accentColor }}; background: {{ $accentColor }}18; border-color: {{ $accentColor }}33;">
                                {{ $series->title }}
                            </span>
                            <!-- Difficulty Level Badge -->
                            <span class="ak23-pill-stage" style="border-color: {{ $accentColor }}44; color: {{ $accentColor }}; background: {{ $accentColor }}18;">
                                {{ strtoupper($course->stage_label ?: 'COURSE') }}
                            </span>

                            @if($hasCustomThumb)
                                <img src="{{ getImage(getFilePath('productThumbnail') . '/' . productFilePath($course, 'thumbnail')) }}" alt="{{ $courseTitle }}" class="ak23-card-img-compact">
                            @else
                                <!-- Dynamic Studio Audio Hardware Vector Graphic -->
                                <svg viewBox="0 0 360 136" fill="none" xmlns="http://www.w3.org/2000/svg" class="ak23-hardware-rack-svg" preserveAspectRatio="none">
                                    <rect width="360" height="136" fill="#10111A"/>
                                    <defs>
                                        <radialGradient id="glow-{{ $course->id }}" cx="50%" cy="50%" r="50%">
                                            <stop offset="0%" stop-color="{{ $accentColor }}" stop-opacity="0.16"/>
                                            <stop offset="100%" stop-color="{{ $accentColor }}" stop-opacity="0"/>
                                        </radialGradient>
                                        <linearGradient id="meter-{{ $course->id }}" x1="0%" y1="0%" x2="100%" y2="0%">
                                            <stop offset="0%" stop-color="#20BF6B"/>
                                            <stop offset="70%" stop-color="#FBAF43"/>
                                            <stop offset="100%" stop-color="#EB3B5A"/>
                                        </linearGradient>
                                    </defs>
                                    <rect width="360" height="136" fill="url(#glow-{{ $course->id }})"/>
                                    <line x1="16" y1="28" x2="344" y2="28" stroke="#1D1F2D" stroke-width="1"/>
                                    <line x1="16" y1="108" x2="344" y2="108" stroke="#1D1F2D" stroke-width="1"/>
                                    <circle cx="12" cy="12" r="3" fill="#1A1C28" stroke="#2A2C3E" stroke-width="1"/>
                                    <circle cx="348" cy="12" r="3" fill="#1A1C28" stroke="#2A2C3E" stroke-width="1"/>
                                    <circle cx="12" cy="124" r="3" fill="#1A1C28" stroke="#2A2C3E" stroke-width="1"/>
                                    <circle cx="348" cy="124" r="3" fill="#1A1C28" stroke="#2A2C3E" stroke-width="1"/>
                                    
                                    <!-- Dynamic Software Screen -->
                                    <rect x="24" y="38" width="180" height="26" rx="4" fill="#0C0D14" stroke="#222436"/>
                                    <circle cx="34" cy="51" r="2.5" fill="{{ $accentColor }}"/>
                                    <text x="44" y="55" fill="{{ $accentColor }}" font-size="9" font-family="monospace" font-weight="700" letter-spacing="1">
                                        {{ strtoupper(Str::limit($course->software_name ?: 'STUDIO WORKSTATION', 22)) }}
                                    </text>
                                    
                                    <!-- Waveform Spectrum Trace -->
                                    <path d="M214 51 Q 230 36, 245 51 T 275 51 T 305 42 T 335 51" fill="none" stroke="{{ $accentColor }}" stroke-width="2" stroke-linecap="round"/>
                                    
                                    <!-- Studio Hardware Knobs -->
                                    <g transform="translate(36, 76)">
                                        <circle cx="14" cy="14" r="12" fill="#151724" stroke="#2A2C40" stroke-width="1.5"/>
                                        <line x1="14" y1="4" x2="14" y2="10" stroke="{{ $accentColor }}" stroke-width="2" stroke-linecap="round"/>
                                        <text x="14" y="35" fill="#6A6C7E" font-size="7.5" font-weight="700" text-anchor="middle">INPUT</text>
                                    </g>
                                    <g transform="translate(76, 76)">
                                        <circle cx="14" cy="14" r="12" fill="#151724" stroke="#2A2C40" stroke-width="1.5"/>
                                        <line x1="14" y1="4" x2="14" y2="10" stroke="{{ $accentColor }}" stroke-width="2" stroke-linecap="round"/>
                                        <text x="14" y="35" fill="#6A6C7E" font-size="7.5" font-weight="700" text-anchor="middle">OUTPUT</text>
                                    </g>
                                    
                                    <!-- Master VU Meter -->
                                    <g transform="translate(136, 80)">
                                        <rect width="110" height="12" rx="3" fill="#0C0D14" stroke="#222436"/>
                                        <rect x="3" y="3" width="75" height="6" rx="1.5" fill="url(#meter-{{ $course->id }})"/>
                                        <text x="55" y="24" fill="#6A6C7E" font-size="7" font-weight="700" text-anchor="middle">MASTER VU &middot; 48kHz</text>
                                    </g>
                                    
                                    <!-- Studio Specs Badge -->
                                    <g transform="translate(260, 80)">
                                        <rect width="84" height="18" rx="4" fill="{{ $accentColor }}15" stroke="{{ $accentColor }}40"/>
                                        <text x="302" y="92" fill="{{ $accentColor }}" font-size="8" font-weight="800" text-anchor="middle">24-BIT / 48kHz</text>
                                    </g>
                                </svg>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="ak23-card-body-compact">
                            <!-- Title with Stretched Link for Full Clickability -->
                            <h3 class="ak23-card-title-compact" title="{{ $courseTitle }}">
                                <a href="{{ $detailUrl }}" class="ak23-card-title-link stretched-link text-white text-decoration-none">
                                    {{ $courseTitle }}
                                </a>
                            </h3>

                            <!-- Included Software Tag & Lifetime License -->
                            <div class="ak23-card-soft-pill text-truncate" title="{{ $course->software_name }} &bull; One-Time Lifetime License & Official Purchase Certificate Included">
                                <i class="las la-certificate" style="color: {{ $accentColor }}; font-size: 13px;"></i>
                                <span class="text-white fw-semibold text-truncate">{{ $course->software_name ?: 'Studio Software' }}</span>
                                <span class="text-success fs-10 ms-auto text-truncate fw-semibold">Lifetime Lic + Purchase Cert</span>
                            </div>

                            <!-- Outcome / Brief Description -->
                            <p class="ak23-card-outcome-compact">
                                {{ $course->learning_outcome ?: $course->description }}
                            </p>

                            <!-- Specs Bar -->
                            <div class="ak23-card-specs-bar">
                                <span><i class="las la-play-circle text-warning me-1"></i>{{ $lessonCount }} Lessons</span>
                                <span><i class="las la-clock text-warning me-1"></i>{{ $videoMin }} Min</span>
                                <span class="text-success"><i class="las la-award me-1"></i>Lifetime License</span>
                            </div>

                            <!-- Card Footer: Price & Direct Actions -->
                            <div class="ak23-card-footer-compact">
                                <div>
                                    <span class="ak23-price-sub">COURSE PRICE</span>
                                    @if($course->is_free)
                                        <span class="ak23-price-tag text-success">FREE</span>
                                    @else
                                        <span class="ak23-price-tag">
                                            TSH {{ number_format($coursePrice) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="d-flex align-items-center gap-1 ak23-card-actions-wrapper">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-warning ak23-btn-add-cart ak23-action-btn rounded-circle p-0 d-inline-flex align-items-center justify-content-center" 
                                            style="width: 32px; height: 32px;"
                                            data-sku="{{ $courseSku }}" 
                                            title="Add to Cart">
                                        <i class="las la-cart-plus fs-16"></i>
                                    </button>
                                    <a href="{{ route('order.checkout', ['sku' => $courseSku]) }}" 
                                       class="btn btn-sm btn--base ak23-btn-enroll ak23-action-btn rounded-pill px-3 py-1 fs-12 fw-semibold d-inline-flex align-items-center gap-1">
                                        <span>Enroll</span>
                                        <i class="las la-arrow-right fs-10"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

        <!-- =====================================================================
             PAGINATION CONTROLS (AT LEAST 6 ITEMS PER PAGE)
             ===================================================================== -->
        <div id="ak23PaginationWrapper" class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-4 mt-2 border-top" style="border-color: rgba(255, 255, 255, 0.08) !important;">
            <div class="text-muted fs-13" id="ak23PaginationSummary">
                Showing <strong>1–6</strong> of <strong>{{ $totalCoursesCount }}</strong> courses
            </div>

            <nav aria-label="Course catalog page navigation">
                <ul class="pagination pagination-sm mb-0 gap-1" id="ak23PaginationList">
                    <!-- Dynamic page numbers rendered by JS -->
                </ul>
            </nav>
        </div>

    </div>
</div>

@endsection

@push('style')
<style>
/* Catalog Top Bar & Layout */
.ak23-catalog-top-bar {
    padding: 32px 0 20px;
    background: radial-gradient(circle at 50% 0%, rgba(251, 175, 67, 0.08) 0%, transparent 60%), #0D0E15;
    border-bottom: 1px solid rgba(255, 255, 255, 0.07);
}

.ak23-catalog-bar-tag {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.12em;
    color: #FBAF43;
    text-transform: uppercase;
    display: inline-block;
    margin-bottom: 4px;
}

.ak23-catalog-bar-title {
    font-size: 26px;
    font-weight: 800;
    letter-spacing: -0.02em;
    color: #FFFFFF;
}

.ak23-catalog-body {
    padding: 34px 0 64px;
    background: #0A0B10;
    min-height: 60vh;
}

/* 3 Courses Per Row Grid - Strictly 3 Columns on Desktop */
.ak23-course-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 22px;
    margin-bottom: 30px;
}

@media (max-width: 991px) {
    .ak23-course-grid-3 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }
}

@media (max-width: 640px) {
    .ak23-course-grid-3 {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}

/* Clean, Minimal, Native Card with Simple Edge & Stretched Link */
.ak23-card-minimal {
    background: #12131D;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1), 
                border-color 0.22s cubic-bezier(0.16, 1, 0.3, 1), 
                box-shadow 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    cursor: pointer;
    position: relative;
    user-select: none;
}

.ak23-card-minimal:hover {
    transform: translateY(-4px);
    border-color: rgba(251, 175, 67, 0.45);
    box-shadow: 0 14px 32px -8px rgba(0, 0, 0, 0.65), 0 0 0 1px rgba(251, 175, 67, 0.18);
}

.ak23-card-minimal:active {
    transform: translateY(-1px);
}

.ak23-card-minimal:focus-within {
    border-color: #FBAF43;
    box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.2);
}

/* Header Visual: 136px height */
.ak23-card-visual-compact {
    height: 136px;
    background: linear-gradient(180deg, #161824 0%, #0E0F16 100%);
    position: relative;
    overflow: hidden;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    display: flex;
    align-items: center;
    justify-content: center;
}

.ak23-card-img-compact {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s ease;
}

.ak23-card-minimal:hover .ak23-card-img-compact {
    transform: scale(1.05);
}

.ak23-hardware-rack-svg {
    width: 100%;
    height: 100%;
    display: block;
}

/* Badges on Visual Header */
.ak23-pill-category {
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
    backdrop-filter: blur(6px);
    border: 1px solid transparent;
    z-index: 2;
    max-width: 55%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ak23-pill-stage {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 3px 9px;
    border-radius: 20px;
    font-size: 9.5px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    backdrop-filter: blur(6px);
    border: 1px solid transparent;
    z-index: 2;
}

/* Card Body */
.ak23-card-body-compact {
    padding: 16px 18px 14px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.ak23-card-title-compact {
    font-size: 15.5px;
    font-weight: 700;
    color: #FFFFFF;
    line-height: 1.35;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 42px;
}

.ak23-card-title-link {
    color: #FFFFFF !important;
    transition: color 0.18s ease;
}

.ak23-card-minimal:hover .ak23-card-title-link {
    color: #FBAF43 !important;
}

/* Software Included Pill */
.ak23-card-soft-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.07);
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11.5px;
    margin-bottom: 8px;
    color: #C0C0D0;
    max-width: 100%;
}

.ak23-card-outcome-compact {
    font-size: 12px;
    line-height: 1.5;
    color: #8C8D9E;
    margin-bottom: 12px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 36px;
}

.ak23-card-specs-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 11px;
    color: #A0A1B2;
    padding: 6px 0;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    margin-bottom: 14px;
}

/* Card Footer: Action buttons elevated above stretched-link */
.ak23-card-footer-compact {
    margin-top: auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.ak23-card-actions-wrapper {
    position: relative;
    z-index: 5;
}

.ak23-action-btn {
    position: relative;
    z-index: 5;
}

.ak23-price-tag {
    font-size: 15.5px;
    font-weight: 800;
    color: #FBAF43;
    letter-spacing: -0.01em;
}

.ak23-price-sub {
    font-size: 9px;
    font-weight: 700;
    color: #6E7082;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    display: block;
    line-height: 1;
    margin-bottom: 2px;
}

/* Stretched Link native card clicking */
.stretched-link::after {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1;
    content: "";
}

/* Live Search Box */
.ak23-search-box {
    position: relative;
    max-width: 360px;
    width: 100%;
}

.ak23-search-box input {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 20px;
    color: #FFFFFF;
    font-size: 12.5px;
    padding: 7px 32px 7px 34px;
    transition: all 0.2s ease;
}

.ak23-search-box input:focus {
    background: rgba(255, 255, 255, 0.08);
    border-color: #FBAF43;
    box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.15);
    color: #FFFFFF;
}

.ak23-search-box .search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #7A7C8E;
    font-size: 14px;
    pointer-events: none;
}

.ak23-search-box .clear-btn {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #8E90A2;
    font-size: 14px;
    padding: 0;
    cursor: pointer;
    line-height: 1;
}

/* Series Pills & Select Filters */
.ak23-filter-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #A0A0B0 !important;
    border-radius: 20px;
    padding: 5px 12px;
    font-size: 11.5px;
    font-weight: 600;
    transition: all 0.2s ease;
    text-decoration: none !important;
    cursor: pointer;
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

.ak23-select-filter {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #C0C0D0;
    font-size: 11.5px;
    border-radius: 20px;
    padding: 5px 28px 5px 12px;
}

.ak23-select-filter:focus {
    background: #14151D;
    color: #FFFFFF;
    border-color: #FBAF43;
}

/* Active Filter Chips */
.ak23-filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(251, 175, 67, 0.12);
    border: 1px solid rgba(251, 175, 67, 0.3);
    color: #FBAF43;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.ak23-filter-chip .chip-remove {
    cursor: pointer;
    opacity: 0.8;
}

.ak23-filter-chip .chip-remove:hover {
    opacity: 1;
}

/* Complete Series Package Banner */
.ak23-complete-pkg-banner {
    background: linear-gradient(90deg, #181928 0%, #12131F 100%);
    border: 1px solid rgba(251, 175, 67, 0.35);
    border-radius: 12px;
    padding: 20px 24px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-top: 12px;
}

.ak23-pkg-banner-badge {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.1em;
    color: #FBAF43;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.ak23-pkg-banner-title {
    font-size: 18px;
    font-weight: 800;
    margin-bottom: 4px;
}

.ak23-pkg-banner-desc {
    font-size: 12.5px;
    color: #9A9BAA;
    max-width: 600px;
}

.ak23-pkg-banner-price {
    font-size: 20px;
    font-weight: 800;
    color: #FBAF43;
}

.ak23-btn-pkg-get {
    background: #FBAF43;
    color: #0F0F14;
    font-weight: 700;
    font-size: 12.5px;
    padding: 8px 16px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.ak23-btn-pkg-get:hover {
    background: #fdbd5d;
    color: #0F0F14;
}

/* Category Track Summary Header */
.ak23-series-track-badge {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.1em;
    color: #FBAF43;
    text-transform: uppercase;
    display: inline-block;
    margin-bottom: 4px;
}

.ak23-series-title {
    font-size: 20px;
    font-weight: 800;
    color: #FFFFFF;
}

.ak23-series-desc {
    font-size: 12.5px;
    color: #8C8D9E;
}

/* Pagination Bar Styling */
.pagination .page-link {
    background: #13141F;
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #C0C0D0;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.18s ease;
    box-shadow: none !important;
}

.pagination .page-link:hover {
    background: rgba(251, 175, 67, 0.15);
    border-color: rgba(251, 175, 67, 0.45);
    color: #FBAF43;
}

.pagination .page-item.active .page-link {
    background: #FBAF43;
    border-color: #FBAF43;
    color: #0F0F14;
    font-weight: 800;
}

.pagination .page-item.disabled .page-link {
    background: #0E0F16;
    border-color: rgba(255, 255, 255, 0.05);
    color: #555768;
    pointer-events: none;
}
</style>
@endpush

@push('script')
<script>
    $(document).ready(function() {
        "use strict";

        const urlParams = new URLSearchParams(window.location.search);
        
        // Parse initial URL parameters with category & series synonyms
        let rawSeries = urlParams.get('series') || urlParams.get('category') || urlParams.get('cat') || 'all';
        const categoryIdMap = {
            '1': 'vocal-mixing',
            '2': 'audio-editing-software',
            '3': 'beat-making'
        };
        if (categoryIdMap[rawSeries]) {
            rawSeries = categoryIdMap[rawSeries];
        }

        let currentSeries = rawSeries;
        let currentSearch = urlParams.get('search') || urlParams.get('q') || '';
        let currentLevel = urlParams.get('level') || urlParams.get('stage') || 'all';
        let currentSort = urlParams.get('sort') || 'default';
        let currentPage = parseInt(urlParams.get('page')) || 1;
        const itemsPerPage = 6; // At least 6 items per page (strictly 2 full rows of 3)

        // Set initial values into controls
        if (currentSearch) {
            $('#ak23LiveSearch').val(currentSearch);
            $('#ak23ClearSearch').removeClass('d-none');
        }
        if (currentLevel !== 'all') {
            $('#ak23LevelFilter').val(currentLevel);
        }
        if (currentSort !== 'default') {
            $('#ak23SortFilter').val(currentSort);
        }
        if (currentSeries !== 'all') {
            $('#ak23SeriesFilterPills .ak23-filter-btn').removeClass('active');
            $('#ak23SeriesFilterPills .ak23-filter-btn[data-series="' + currentSeries + '"]').addClass('active');
        }

        // 1. Fully Clickable Card - Direct click navigation backup for non-interactive areas
        $(document).on('click', '.ak23-card-minimal', function(e) {
            if ($(e.target).closest('.ak23-action-btn, .ak23-card-actions-wrapper, a, button, input, select').length) {
                return;
            }
            const targetUrl = $(this).data('url');
            if (targetUrl) {
                window.location.href = targetUrl;
            }
        });

        // Keyboard Enter navigation on focused card
        $(document).on('keydown', '.ak23-card-minimal', function(e) {
            if (e.key === 'Enter') {
                if ($(e.target).closest('.ak23-action-btn, .ak23-card-actions-wrapper, a, button, input, select').length) {
                    return;
                }
                const targetUrl = $(this).data('url');
                if (targetUrl) {
                    window.location.href = targetUrl;
                }
            }
        });

        // 2. Sorting Handler
        function applySortOrder() {
            const $grid = $('#ak23CourseGrid');
            const $cards = $grid.children('.ak23-course-item').get();

            $cards.sort(function(a, b) {
                const $a = $(a);
                const $b = $(b);

                if (currentSort === 'price-asc') {
                    return parseFloat($a.data('price')) - parseFloat($b.data('price'));
                } else if (currentSort === 'price-desc') {
                    return parseFloat($b.data('price')) - parseFloat($a.data('price'));
                } else if (currentSort === 'title-asc') {
                    return ($a.data('title') || '').localeCompare($b.data('title') || '');
                } else if (currentSort === 'lessons-desc') {
                    return parseInt($b.data('lessons')) - parseInt($a.data('lessons'));
                } else {
                    return parseInt($a.data('seq')) - parseInt($b.data('seq'));
                }
            });

            $.each($cards, function(i, card) {
                $grid.append(card);
            });
        }

        // 3. Update Active Filter Chips Bar
        function updateActiveFilterChips() {
            const $container = $('#ak23ChipsContainer');
            $container.empty();

            let hasChips = false;

            if (currentSearch.trim().length > 0) {
                hasChips = true;
                $container.append(`
                    <span class="ak23-filter-chip">
                        <span>Search: "${currentSearch.trim()}"</span>
                        <i class="las la-times chip-remove" data-action="clear-search"></i>
                    </span>
                `);
            }

            if (currentSeries !== 'all') {
                hasChips = true;
                const seriesTitle = $('#ak23SeriesFilterPills .ak23-filter-btn[data-series="' + currentSeries + '"] span').text().split('(')[0].trim() || currentSeries;
                $container.append(`
                    <span class="ak23-filter-chip">
                        <span>Category: ${seriesTitle}</span>
                        <i class="las la-times chip-remove" data-action="clear-series"></i>
                    </span>
                `);
            }

            if (currentLevel !== 'all') {
                hasChips = true;
                const levelTitle = $('#ak23LevelFilter option:selected').text();
                $container.append(`
                    <span class="ak23-filter-chip">
                        <span>Level: ${levelTitle}</span>
                        <i class="las la-times chip-remove" data-action="clear-level"></i>
                    </span>
                `);
            }

            if (currentSort !== 'default') {
                hasChips = true;
                const sortTitle = $('#ak23SortFilter option:selected').text();
                $container.append(`
                    <span class="ak23-filter-chip">
                        <span>Sort: ${sortTitle}</span>
                        <i class="las la-times chip-remove" data-action="clear-sort"></i>
                    </span>
                `);
            }

            if (hasChips) {
                $('#ak23ActiveFiltersBar').removeClass('d-none');
            } else {
                $('#ak23ActiveFiltersBar').addClass('d-none');
            }
        }

        // 4. Render Pagination Navigation Controls
        function renderPagination(totalMatches) {
            const totalPages = Math.ceil(totalMatches / itemsPerPage);
            const $wrapper = $('#ak23PaginationWrapper');
            const $list = $('#ak23PaginationList');
            const $summary = $('#ak23PaginationSummary');

            if (totalMatches === 0) {
                $wrapper.addClass('d-none');
                return;
            }

            // Ensure currentPage is within bounds
            if (currentPage > totalPages) {
                currentPage = 1;
            }

            // Calculate start and end index
            const startIndex = (currentPage - 1) * itemsPerPage + 1;
            const endIndex = Math.min(currentPage * itemsPerPage, totalMatches);

            if (totalMatches <= itemsPerPage) {
                $summary.html(`Showing all <strong>${totalMatches}</strong> courses`);
                $wrapper.removeClass('d-none');
                $list.empty();
                return;
            }

            $summary.html(`Showing <strong>${startIndex}–${endIndex}</strong> of <strong>${totalMatches}</strong> courses`);
            $wrapper.removeClass('d-none');
            $list.empty();

            // Previous Button
            const prevDisabled = currentPage === 1 ? 'disabled' : '';
            $list.append(`
                <li class="page-item ${prevDisabled}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                        <i class="las la-angle-left"></i> Prev
                    </a>
                </li>
            `);

            // Page Number Buttons
            for (let p = 1; p <= totalPages; p++) {
                const activeClass = p === currentPage ? 'active' : '';
                $list.append(`
                    <li class="page-item ${activeClass}">
                        <a class="page-link" href="#" data-page="${p}">${p}</a>
                    </li>
                `);
            }

            // Next Button
            const nextDisabled = currentPage === totalPages ? 'disabled' : '';
            $list.append(`
                <li class="page-item ${nextDisabled}">
                    <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
                        Next <i class="las la-angle-right"></i>
                    </a>
                </li>
            `);
        }

        // 5. Real-Time Catalog Filtering & Pagination Engine
        function runCatalogFilter(resetToPageOne = false) {
            if (resetToPageOne) {
                currentPage = 1;
            }

            const query = currentSearch.trim().toLowerCase();

            // If bundles tab is selected
            if (currentSeries === 'bundles') {
                $('#ak23CourseGrid').addClass('d-none');
                $('#ak23CategoryHeaderWrapper').addClass('d-none');
                $('#ak23BundlesGridWrapper').removeClass('d-none');
                $('#ak23PaginationWrapper').addClass('d-none');

                let visibleBundles = 0;
                $('.ak23-bundle-card-item').each(function() {
                    const $b = $(this);
                    const bTitle = $b.data('title') || '';
                    if (!query || bTitle.includes(query)) {
                        $b.show();
                        visibleBundles++;
                    } else {
                        $b.hide();
                    }
                });

                $('#ak23CountNum').text(visibleBundles);
                if (visibleBundles === 0) {
                    $('#ak23QueryDisplay').text(query || 'bundles');
                    $('#ak23NoResults').removeClass('d-none');
                } else {
                    $('#ak23NoResults').addClass('d-none');
                }

                updateActiveFilterChips();
                return;
            }

            // Normal courses view
            $('#ak23BundlesGridWrapper').addClass('d-none');
            $('#ak23CourseGrid').removeClass('d-none');

            // Category summary header handling
            if (currentSeries !== 'all') {
                $('#ak23CategoryHeaderWrapper').removeClass('d-none');
                $('.ak23-category-summary-box').addClass('d-none');
                $('.ak23-category-summary-box[data-series-slug="' + currentSeries + '"]').removeClass('d-none');
            } else {
                $('#ak23CategoryHeaderWrapper').addClass('d-none');
                $('.ak23-category-summary-box').addClass('d-none');
            }

            // Apply card ordering
            applySortOrder();

            // Collect all matching courses
            const matchingCards = [];

            $('.ak23-course-item').each(function() {
                const $card = $(this);
                const cardSearch = $card.data('search') || '';
                const cardStage = $card.data('stage') || '';
                const cardSeries = $card.data('series') || '';
                const cardCatId = String($card.data('category-id'));

                const matchesSeries = (currentSeries === 'all' 
                    || currentSeries === cardSeries 
                    || currentSeries === cardCatId);
                const matchesQuery = !query || cardSearch.includes(query);
                const matchesLevel = (currentLevel === 'all' || cardStage.includes(currentLevel));

                if (matchesSeries && matchesQuery && matchesLevel) {
                    matchingCards.push($card);
                } else {
                    $card.hide();
                }
            });

            const totalMatches = matchingCards.length;

            // Apply 6-per-page pagination
            const totalPages = Math.ceil(totalMatches / itemsPerPage) || 1;
            if (currentPage > totalPages) {
                currentPage = 1;
            }

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;

            $.each(matchingCards, function(index, $card) {
                if (index >= startIndex && index < endIndex) {
                    $card.show();
                } else {
                    $card.hide();
                }
            });

            // Update live counter
            $('#ak23CountNum').text(totalMatches);

            // Empty state display
            if (totalMatches === 0) {
                $('#ak23QueryDisplay').text(query || 'selected criteria');
                $('#ak23NoResults').removeClass('d-none');
                $('#ak23PaginationWrapper').addClass('d-none');
            } else {
                $('#ak23NoResults').addClass('d-none');
                renderPagination(totalMatches);
            }

            // Update active chips
            updateActiveFilterChips();
        }

        // Pagination Click Handler
        $(document).on('click', '#ak23PaginationList .page-link', function(e) {
            e.preventDefault();
            const targetPage = parseInt($(this).data('page'));
            if (!isNaN(targetPage) && targetPage !== currentPage && targetPage >= 1) {
                currentPage = targetPage;
                runCatalogFilter(false);

                // Smooth scroll to catalog target
                $('html, body').animate({
                    scrollTop: $('#ak23CatalogTopTarget').offset().top - 90
                }, 220);

                // Update URL page param
                const url = new URL(window.location.href);
                url.searchParams.set('page', currentPage);
                window.history.pushState({ page: currentPage }, '', url.toString());
            }
        });

        // Search Input Event Handler (Instant Real-time)
        $('#ak23LiveSearch').on('input', function() {
            currentSearch = $(this).val();
            if (currentSearch.length > 0) {
                $('#ak23ClearSearch').removeClass('d-none');
            } else {
                $('#ak23ClearSearch').addClass('d-none');
            }
            runCatalogFilter(true);
        });

        // Clear Search Button
        $('#ak23ClearSearch').on('click', function() {
            $('#ak23LiveSearch').val('').trigger('input').focus();
        });

        // Series Filter Pills Click
        $('#ak23SeriesFilterPills .ak23-filter-btn').on('click', function(e) {
            e.preventDefault();
            const series = $(this).data('series') || 'all';
            currentSeries = series;

            $('#ak23SeriesFilterPills .ak23-filter-btn').removeClass('active');
            $(this).addClass('active');

            const url = new URL(window.location.href);
            if (series === 'all') {
                url.searchParams.delete('series');
                url.searchParams.delete('category');
            } else {
                url.searchParams.set('series', series);
                url.searchParams.delete('category');
            }
            url.searchParams.delete('page');
            window.history.pushState({ series: series }, '', url.toString());

            runCatalogFilter(true);
        });

        // "View All Courses" trigger in Category Summary Box
        $(document).on('click', '.ak23-view-all-trigger', function(e) {
            e.preventDefault();
            $('#ak23SeriesFilterPills .ak23-filter-btn[data-series="all"]').trigger('click');
        });

        // Level Filter Dropdown
        $('#ak23LevelFilter').on('change', function() {
            currentLevel = $(this).val();
            runCatalogFilter(true);
        });

        // Sort Filter Dropdown
        $('#ak23SortFilter').on('change', function() {
            currentSort = $(this).val();
            runCatalogFilter(false);
        });

        // Chip Remove Actions
        $(document).on('click', '.chip-remove', function() {
            const action = $(this).data('action');
            if (action === 'clear-search') {
                $('#ak23ClearSearch').trigger('click');
            } else if (action === 'clear-series') {
                $('#ak23SeriesFilterPills .ak23-filter-btn[data-series="all"]').trigger('click');
            } else if (action === 'clear-level') {
                $('#ak23LevelFilter').val('all').trigger('change');
            } else if (action === 'clear-sort') {
                $('#ak23SortFilter').val('default').trigger('change');
            }
        });

        // Clear All Chips Button & Reset Button
        $('#ak23ClearAllChipsBtn, #ak23ResetBtn').on('click', function() {
            currentSearch = '';
            currentSeries = 'all';
            currentLevel = 'all';
            currentSort = 'default';
            currentPage = 1;

            $('#ak23LiveSearch').val('');
            $('#ak23ClearSearch').addClass('d-none');
            $('#ak23LevelFilter').val('all');
            $('#ak23SortFilter').val('default');

            $('#ak23SeriesFilterPills .ak23-filter-btn').removeClass('active');
            $('#ak23SeriesFilterPills .ak23-filter-btn[data-series="all"]').addClass('active');

            const url = new URL(window.location.href);
            url.searchParams.delete('series');
            url.searchParams.delete('category');
            url.searchParams.delete('search');
            url.searchParams.delete('level');
            url.searchParams.delete('sort');
            url.searchParams.delete('page');
            window.history.pushState({}, '', url.toString());

            runCatalogFilter(true);
        });

        // Add to Cart via AJAX (Deduplicated with stopPropagation)
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
                    notify(res.status || 'success', res.message);
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
                    notify('error', errMsg);
                }
            });
        });

        // Initial catalog filter and pagination run on load
        runCatalogFilter(false);
    });
</script>
@endpush
