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
                                    @if($bundle->video_embed_info)
                                        <button type="button" class="btn btn-outline-warning btn-trigger-preview px-3" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#previewModal"
                                                data-preview-url="{{ $bundle->video_embed_info->embed_url }}" 
                                                data-preview-type="{{ $bundle->video_embed_info->type }}" 
                                                data-preview-title="{{ $bundleTitle }}" 
                                                title="Watch Preview Video">
                                            <i class="las la-play me-1"></i> Preview
                                        </button>
                                    @endif
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
            <div class="ak23-course-grid-4">
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
                        @endphp
                        <div class="ak23-card-sample ak23-bundle-card-item"
                             data-url="{{ $bUrl }}"
                             data-title="{{ strtolower($bTitle) }}"
                             data-price="{{ $bPrice }}"
                             data-series="{{ $item['series']->slug }}"
                             tabindex="0"
                             role="link"
                             aria-label="{{ $bTitle }}">

                            <!-- Thumbnail Area with Floating Top-Right Cart Button -->
                            <div class="ak23-sample-thumb-wrap">
                                <a href="{{ $bUrl }}" class="ak23-thumb-link">
                                    <img src="{{ $bundle->thumbnail_url }}" alt="{{ $bTitle }}" class="ak23-sample-thumb-img" loading="lazy">
                                </a>
                                <button type="button" 
                                        class="ak23-sample-cart-btn ak23-btn-add-cart" 
                                        data-sku="{{ $bSku }}" 
                                        title="Add Bundle to Cart">
                                    <i class="las la-cart-plus"></i>
                                </button>
                                @if($bundle->video_embed_info)
                                    <button type="button" 
                                            class="ak23-sample-preview-pill btn-trigger-preview" 
                                            data-bs-toggle="modal"
                                            data-bs-target="#previewModal"
                                            data-preview-url="{{ $bundle->video_embed_info->embed_url }}" 
                                            data-preview-type="{{ $bundle->video_embed_info->type }}" 
                                            data-preview-title="{{ $bTitle }}" 
                                            title="Preview Available - Click to Play">
                                        <span class="preview-pulse-dot"></span>
                                        <i class="las {{ $bundle->video_embed_info->type === 'audio' || $bundle->video_embed_info->type === 'soundcloud' ? 'la-headphones' : 'la-play' }}"></i>
                                        <span>{{ $bundle->video_embed_info->type === 'audio' || $bundle->video_embed_info->type === 'soundcloud' ? 'Audio Preview' : 'Preview' }}</span>
                                    </button>
                                @endif
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
                                    <span class="ak23-meta-tag">{{ strtoupper($item['series']->title) }}</span>
                                    <span class="ak23-meta-sep">&middot;</span>
                                    <span class="ak23-meta-date">COMPLETE BUNDLE</span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Main Course Grid: 4 Columns per Row, matching sample -->
        <div class="ak23-course-grid-4" id="ak23CourseGrid">
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
                        $detailUrl = route('product.details', $course->slug);
                        $searchHaystack = strtolower($courseTitle . ' ' . $course->software_name . ' ' . $course->software_edition . ' ' . $course->stage_label . ' ' . $course->learning_outcome . ' ' . $courseSku . ' ' . $series->title . ' ' . $series->slug);
                    @endphp

                    <div class="ak23-card-sample ak23-course-item"
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
                            @if($course->video_embed_info)
                                <button type="button" 
                                        class="ak23-sample-preview-pill btn-trigger-preview" 
                                        data-bs-toggle="modal"
                                        data-bs-target="#previewModal"
                                        data-preview-url="{{ $course->video_embed_info->embed_url }}" 
                                        data-preview-type="{{ $course->video_embed_info->type }}" 
                                        data-preview-title="{{ $courseTitle }}" 
                                        title="Preview Available - Click to Play">
                                    <span class="preview-pulse-dot"></span>
                                    <i class="las {{ $course->video_embed_info->type === 'audio' || $course->video_embed_info->type === 'soundcloud' ? 'la-headphones' : 'la-play' }}"></i>
                                    <span>{{ $course->video_embed_info->type === 'audio' || $course->video_embed_info->type === 'soundcloud' ? 'Audio Preview' : 'Preview' }}</span>
                                </button>
                            @endif
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
                                <span class="ak23-meta-tag">{{ strtoupper($course->software_name ?: ($series->title ?: 'MACOS')) }}</span>
                                <span class="ak23-meta-sep">&middot;</span>
                                <span class="ak23-meta-date">{{ $course->updated_at ? $course->updated_at->format('M d, Y') : ($course->created_at ? $course->created_at->format('M d, Y') : 'Sep 17, 2026') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

        <!-- =====================================================================
             PAGINATION CONTROLS (AT LEAST 6 ITEMS PER PAGE)
             ===================================================================== -->
        <div id="ak23PaginationWrapper" class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-4 mt-3" style="border-top: 1px solid #CBD5E1 !important;">
            <div class="fs-13" id="ak23PaginationSummary" style="color: #475569; font-weight: 500;">
                Showing <strong class="text-dark">1–12</strong> of <strong class="text-dark">{{ $totalCoursesCount }}</strong> courses
            </div>

            <nav aria-label="Course catalog page navigation">
                <ul class="pagination pagination-sm mb-0" id="ak23PaginationList">
                    <!-- Dynamic page numbers rendered by JS -->
                </ul>
            </nav>
        </div>

    </div>
</div>

<!-- Video Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background: #111118; border: 1px solid #2B2B3C; border-radius: 12px; color: #fff;">
            <div class="modal-header border-bottom border-secondary" style="border-color: #232332 !important;">
                <h5 class="modal-title" id="previewModalLabel" style="font-size: 15px; font-weight: 700; color: #F0A62F;">
                    <i class="las la-play-circle me-1"></i> @lang('Course Preview')
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; background: #000;" id="previewVideoContainer">
                    <!-- Dynamic preview injected via JS -->
                </div>
            </div>
            <div class="modal-footer border-top border-secondary justify-content-between" style="border-color: #232332 !important; padding: 14px 20px;">
                <span style="font-size: 12.5px; color: #9A9AA6;">Downloadable high-res course files provided after purchase.</span>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
<style>
/* Catalog Top Bar & Layout */
/* Floating Preview Video/Audio Pill Badge */
.ak23-sample-preview-pill {
    position: absolute;
    bottom: 10px;
    right: 10px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 11px;
    border-radius: 20px;
    background: rgba(17, 17, 24, 0.92);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(251, 175, 67, 0.7);
    color: #FBAF43;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    cursor: pointer;
    z-index: 5;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    line-height: 1;
}

.ak23-sample-preview-pill:hover {
    background: #FBAF43;
    color: #0F0F14 !important;
    border-color: #FBAF43;
    transform: translateY(-2px) scale(1.03);
    box-shadow: 0 6px 16px rgba(251, 175, 67, 0.4);
}

.ak23-sample-preview-pill .preview-pulse-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #FBAF43;
    box-shadow: 0 0 6px #FBAF43;
    animation: ak23PulseDot 1.6s infinite ease-in-out;
}

.ak23-sample-preview-pill:hover .preview-pulse-dot {
    background: #0F0F14;
    box-shadow: 0 0 6px rgba(15, 15, 20, 0.5);
}

@keyframes ak23PulseDot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.35; transform: scale(0.75); }
}

.ak23-sample-preview-btn {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(17, 17, 24, 0.88);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(251, 175, 67, 0.55);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FBAF43;
    font-size: 15px;
    cursor: pointer;
    z-index: 5;
    transition: all 0.2s ease;
    padding: 0;
}
.ak23-sample-preview-btn:hover {
    background: #FBAF43;
    color: #0E0E15 !important;
    border-color: #FBAF43;
    transform: scale(1.15);
    box-shadow: 0 4px 14px rgba(251, 175, 67, 0.5);
}
.ak23-sample-preview-btn:active {
    transform: scale(0.95);
}

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
    background: #F1F5F9;
    min-height: 60vh;
}

/* 4 Courses Per Row Grid - Exactly matching sample */
.ak23-course-grid-3,
.ak23-course-grid-4 {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 24px;
    margin-bottom: 34px;
}

@media (max-width: 1200px) {
    .ak23-course-grid-3,
    .ak23-course-grid-4 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
    }
}

@media (max-width: 860px) {
    .ak23-course-grid-3,
    .ak23-course-grid-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }
}

@media (max-width: 540px) {
    .ak23-course-grid-3,
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

/* Pagination Bar Styling - Custom High-End Controls */
#ak23PaginationList {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    flex-wrap: nowrap !important;
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
}

#ak23PaginationList .page-item {
    margin: 0 !important;
}

#ak23PaginationList .page-item .page-link {
    width: auto !important;
    min-width: 38px !important;
    height: 38px !important;
    padding: 0 16px !important;
    white-space: nowrap !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    border-radius: 8px !important;
    background: #FFFFFF !important;
    border: 1px solid #CBD5E1 !important;
    color: #1E293B !important;
    text-decoration: none !important;
    transition: all 0.18s ease-in-out !important;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
    cursor: pointer !important;
    line-height: 1 !important;
}

#ak23PaginationList .page-item .page-link:hover {
    background: #FFFBEB !important;
    border-color: #FBAF43 !important;
    color: #B45309 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 3px 6px rgba(251, 175, 67, 0.2) !important;
}

#ak23PaginationList .page-item.active .page-link {
    background: #FBAF43 !important;
    border-color: #FBAF43 !important;
    color: #0F0F14 !important;
    font-weight: 700 !important;
    box-shadow: 0 2px 8px rgba(251, 175, 67, 0.35) !important;
    pointer-events: none !important;
}

#ak23PaginationList .page-item.disabled .page-link {
    background: #F8FAFC !important;
    border-color: #E2E8F0 !important;
    color: #94A3B8 !important;
    cursor: not-allowed !important;
    pointer-events: none !important;
    box-shadow: none !important;
    transform: none !important;
    opacity: 0.75 !important;
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
        const itemsPerPage = 12; // 12 items per page (3 full rows of 4 cards on desktop)

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
        $(document).on('click', '.ak23-card-sample, .ak23-card-minimal', function(e) {
            if ($(e.target).closest('.ak23-sample-cart-btn, .ak23-btn-add-cart, a, button, input, select').length) {
                return;
            }
            const targetUrl = $(this).data('url');
            if (targetUrl) {
                window.location.href = targetUrl;
            }
        });

        // Keyboard Enter navigation on focused card
        $(document).on('keydown', '.ak23-card-sample, .ak23-card-minimal', function(e) {
            if (e.key === 'Enter') {
                if ($(e.target).closest('.ak23-sample-cart-btn, .ak23-btn-add-cart, a, button, input, select').length) {
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
                $summary.html(`Showing all <strong class="text-dark">${totalMatches}</strong> courses`);
                $wrapper.removeClass('d-none');
                $list.empty();
                return;
            }

            $summary.html(`Showing <strong class="text-dark">${startIndex}–${endIndex}</strong> of <strong class="text-dark">${totalMatches}</strong> courses`);
            $wrapper.removeClass('d-none');
            $list.empty();

            // Previous Button
            const prevDisabled = currentPage === 1 ? 'disabled' : '';
            const prevAria = currentPage === 1 ? 'aria-disabled="true" tabindex="-1"' : '';
            $list.append(`
                <li class="page-item ${prevDisabled}">
                    <a class="page-link" href="javascript:void(0)" data-page="${currentPage - 1}" aria-label="Previous" ${prevAria}>
                        <i class="las la-angle-left"></i> Prev
                    </a>
                </li>
            `);

            // Page Number Buttons
            for (let p = 1; p <= totalPages; p++) {
                const activeClass = p === currentPage ? 'active' : '';
                const activeAria = p === currentPage ? 'aria-current="page"' : '';
                $list.append(`
                    <li class="page-item ${activeClass}">
                        <a class="page-link" href="javascript:void(0)" data-page="${p}" ${activeAria}>${p}</a>
                    </li>
                `);
            }

            // Next Button
            const nextDisabled = currentPage === totalPages ? 'disabled' : '';
            const nextAria = currentPage === totalPages ? 'aria-disabled="true" tabindex="-1"' : '';
            $list.append(`
                <li class="page-item ${nextDisabled}">
                    <a class="page-link" href="javascript:void(0)" data-page="${currentPage + 1}" aria-label="Next" ${nextAria}>
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
            const $item = $(this).closest('.page-item');
            if ($item.hasClass('disabled') || $item.hasClass('active')) {
                return false;
            }
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

        // Dynamic Video Preview Modal Handler
        $(document).on('click', '.btn-trigger-preview', function(e) {
            e.stopPropagation();
            var embedUrl = $(this).attr('data-preview-url');
            var type = $(this).attr('data-preview-type') || 'youtube';
            var title = $(this).attr('data-preview-title') || 'Course Preview Video';
            
            if (title) {
                $('#previewModalLabel').html('<i class="las la-play-circle me-1"></i> <span style="font-weight:400;color:#9A9AA6;">Now Playing:</span> ' + title);
            }
            
            var container = $('#previewVideoContainer');
            container.empty();
            
            if (!embedUrl) {
                container.html('<div class="d-flex flex-column align-items-center justify-content-center p-5 text-center text-muted" style="position: absolute; top:0; left: 0; width: 100%; height: 100%;"><i class="las la-play-circle text-warning mb-2" style="font-size: 48px;"></i><h6 class="text-white mb-1">Preview Video Available Soon</h6><p class="fs-12 text-muted mb-0">The studio walkthrough for this course will be available shortly.</p></div>');
                return;
            }
            
            if (type === 'soundcloud' || embedUrl.indexOf('soundcloud.com') !== -1) {
                container.html('<div class="p-4 p-md-5 text-center" style="position: absolute; top:0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;"><i class="las la-headphones text-warning mb-3" style="font-size: 48px;"></i><div class="w-100" style="max-width: 580px;"><iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay" src="' + embedUrl + '"></iframe></div></div>');
            } else if (type === 'youtube' || type === 'vimeo' || type === 'gdrive' || type === 'loom' || type === 'streamable' || embedUrl.indexOf('youtube') !== -1 || embedUrl.indexOf('vimeo') !== -1 || embedUrl.indexOf('drive.google') !== -1 || embedUrl.indexOf('loom.com') !== -1 || embedUrl.indexOf('streamable.com') !== -1) {
                container.html('<iframe id="previewModalIframe" style="position: absolute; top:0; left: 0; width: 100%; height: 100%; border:0;" src="' + embedUrl + '" title="' + title + '" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
            } else if (type === 'audio' || /\.(mp3|wav|m4a|ogg|aac|flac)(\?.*)?$/i.test(embedUrl)) {
                container.html('<div class="p-5 text-center" style="position: absolute; top:0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;"><i class="las la-compact-disc text-warning mb-3" style="font-size: 56px;"></i><audio controls autoplay class="w-100" style="max-width: 480px;"><source src="' + embedUrl + '"></audio></div>');
            } else {
                container.html('<video id="previewModalVideo" controls autoplay playsinline style="position: absolute; top:0; left: 0; width: 100%; height: 100%; object-fit: contain;"><source src="' + embedUrl + '" type="video/mp4"></video>');
            }
        });

        // Stop playback when preview modal is closed
        $('#previewModal').on('hidden.bs.modal', function () {
            $('#previewVideoContainer').empty();
        });

        // Initial catalog filter and pagination run on load
        runCatalogFilter(false);
    });
</script>
@endpush
