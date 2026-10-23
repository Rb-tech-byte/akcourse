@extends($activeTemplate . 'layouts.frontend')

@section('content')

<!-- =========================================================================
     COMPACT CATALOG BAR & FILTERS
     ========================================================================= -->
<div class="ak23-catalog-top-bar">
    <div class="ak23-container">
        <div class="ak23-catalog-bar-inner d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="ak23-catalog-bar-meta">
                <span class="ak23-catalog-bar-tag">COURSES &amp; SOFTWARE PACKAGES</span>
                <h1 class="ak23-catalog-bar-title">All Training Programs</h1>
            </div>

            <!-- Series Quick Navigation Pills -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('products') }}" class="btn btn-sm ak23-filter-btn {{ !request('series') || request('series') === 'all' ? 'active' : '' }}" data-series="all">
                    <span>All Programs ({{ collect($catalog)->sum(fn($i) => count($i['courses'])) }})</span>
                </a>
                @foreach($catalog as $cItem)
                    <a href="{{ route('products', ['series' => $cItem['series']->slug]) }}" class="btn btn-sm ak23-filter-btn {{ request('series') === $cItem['series']->slug ? 'active' : '' }}" data-series="{{ $cItem['series']->slug }}">
                        <span>{{ $cItem['series']->title }} ({{ count($cItem['courses']) }})</span>
                    </a>
                @endforeach
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

                    <a href="{{ route('products', ['series' => $series->slug]) }}" class="ak23-btn-series-link">
                        <span>Filter this series</span>
                        <i class="las la-arrow-right"></i>
                    </a>
                </div>

                <!-- Complete Package Banner -->
                @if($bundle)
                    @php
                        $bundleTitle = $bundle->title ?: ($bundle->name ?? 'Complete Series');
                        $bundlePrice = (float)($bundle->price ?: 480000);
                        $bundleSku = $bundle->sku ?: ('AK23-BUNDLE-' . strtoupper($series->slug));
                    @endphp
                    <div class="ak23-complete-pkg-banner">
                        <div>
                            <div class="ak23-pkg-banner-badge">COMPLETE PACKAGE</div>
                            <h3 class="ak23-pkg-banner-title">
                                <a href="{{ route('product.details', $bundle->slug) }}" class="text-white text-decoration-none">
                                    {{ $bundleTitle }}
                                </a>
                            </h3>
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
                    @forelse($courses as $course)
                        @php
                            $seqPadded = str_pad($course->sequence_no ?: 1, 2, '0', STR_PAD_LEFT);
                            $stageSlug = Str::slug($course->stage_label ?: 'beginner');
                            $stageBadgeClass = match($stageSlug) {
                                'getting-started' => 'stage-getting-started',
                                'beginner' => 'stage-beginner',
                                'developing-your-skills' => 'stage-developing',
                                'intermediate' => 'stage-intermediate',
                                'advanced-chain-building', 'advanced' => 'stage-advanced',
                                'studio-workflow' => 'stage-advanced',
                                'building-musical-ideas' => 'stage-developing',
                                'developing-your-sound' => 'stage-developing',
                                'creative-production' => 'stage-intermediate',
                                'complete-your-beat' => 'stage-advanced',
                                default => 'stage-beginner'
                            };
                            $licenseLabel = ($series->slug === 'vocal-mixing') ? 'PLUGIN LICENSE INCLUDED' : 'SOFTWARE LICENSE INCLUDED';
                            $courseTitle = $course->title;
                            $coursePrice = (float)$course->price;
                            $courseSku = $course->sku ?: ('AK23-COURSE-' . $course->id);
                            $lessonCount = $course->lesson_count ?: 8;
                            $videoMin = $course->video_duration_seconds ? round($course->video_duration_seconds / 60) : 120;
                            $accentColor = $course->accent_color ?: '#FBAF43';
                            $hasCustomThumb = !empty($course->thumbnail) && $course->thumbnail !== 'default.png' && file_exists(public_path(getFilePath('productThumbnail') . '/' . productFilePath($course, 'thumbnail')));
                        @endphp

                        <div class="ak23-course-card">
                            <!-- Visual Top with Image or Dynamic DAW Graphics -->
                            <div class="ak23-course-visual">
                                <span class="ak23-seq-circle">{{ $seqPadded }}</span>
                                <span class="ak23-stage-badge {{ $stageBadgeClass }}">{{ $course->stage_label ?: 'Studio Masterclass' }}</span>

                                @if($hasCustomThumb)
                                    <img src="{{ getImage(getFilePath('productThumbnail') . '/' . productFilePath($course, 'thumbnail')) }}" alt="{{ $courseTitle }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <!-- Dynamic CMS Studio Hardware/Plugin Visual Rack -->
                                    <svg width="270" height="135" viewBox="0 0 270 135" fill="none">
                                        <rect x="15" y="15" width="240" height="105" rx="6" fill="#111119" stroke="#232332"/>
                                        <text x="30" y="34" fill="{{ $accentColor }}" font-size="8.5" font-weight="800" letter-spacing="1">
                                            {{ strtoupper(Str::limit($course->software_name ?: 'STUDIO SUITE', 26)) }}
                                        </text>
                                        <line x1="30" y1="42" x2="240" y2="42" stroke="#232332"/>
                                        <!-- Dynamic Waveform & EQ Curve -->
                                        <path d="M30 75 Q65 42 100 68 T170 55 T215 75 T240 60" fill="none" stroke="{{ $accentColor }}" stroke-width="2"/>
                                        <!-- Control Dials & Meters -->
                                        <circle cx="50" cy="100" r="9" fill="#181824" stroke="{{ $accentColor }}" stroke-width="1.5"/>
                                        <circle cx="85" cy="100" r="9" fill="#181824" stroke="{{ $accentColor }}" stroke-width="1.5"/>
                                        <circle cx="120" cy="100" r="9" fill="#181824" stroke="#2D2D3D" stroke-width="1.5"/>
                                        <rect x="150" y="93" width="75" height="14" rx="2" fill="#161622" stroke="#28283A"/>
                                        <rect x="153" y="96" width="38" height="8" rx="1" fill="{{ $accentColor }}" fill-opacity="0.8"/>
                                    </svg>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="ak23-course-body">
                                <h3 class="ak23-course-title">
                                    <a href="{{ route('product.details', $course->slug) }}" class="text-white text-decoration-none">
                                        {{ $courseTitle }}
                                    </a>
                                </h3>
                                <p class="ak23-course-outcome">
                                    {{ $course->learning_outcome ?: 'Professional music production mastery with studio stems and software package.' }}
                                </p>

                                <div class="ak23-course-meta">
                                    {{ $lessonCount }} LESSONS &bull; {{ $videoMin }} MIN
                                </div>

                                <a href="{{ route('product.details', $course->slug) }}" class="ak23-preview-link">
                                    <i class="las la-play-circle fs-14 me-1"></i>
                                    <span>PREVIEW COURSE</span>
                                </a>

                                <div class="ak23-license-pill">
                                    <i class="las la-shield-alt"></i>
                                    <span>{{ $licenseLabel }}</span>
                                </div>

                                <div class="ak23-software-section">
                                    <div class="ak23-software-tag">LICENSED SOFTWARE INCLUDED</div>
                                    <p class="ak23-software-name">
                                        {{ $course->software_name ? $course->software_name . ' — ' . ($course->software_edition ?: 'Studio Edition') : 'Matching Studio Software License Included' }}
                                    </p>
                                </div>

                                <!-- Card Footer -->
                                <div class="ak23-course-card-footer">
                                    <div class="ak23-price-group">
                                        @if($course->is_free)
                                            <span class="ak23-price-val text-success">@lang('FREE')</span>
                                        @else
                                            <span class="ak23-price-val">
                                                TSH {{ number_format($coursePrice) }}
                                            </span>
                                        @endif
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
                    @empty
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">No courses published in this series yet.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        @endforeach
    </div>
</div>

@endsection

@push('style')
<style>
.ak23-filter-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #A0A0B0 !important;
    border-radius: 20px;
    padding: 6px 14px;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.2s ease;
    text-decoration: none !important;
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
    // Series filter linked to header navigation & pill buttons
    $(document).ready(function() {
        function applySeriesFilter(seriesSlug) {
            if (!seriesSlug || seriesSlug === 'all') {
                $('.ak23-series-section').fadeIn(200);
                $('.ak23-filter-btn').removeClass('active');
                $('.ak23-filter-btn[data-series="all"]').addClass('active');
            } else {
                $('.ak23-series-section').hide();
                const $target = $('.series-group-' + seriesSlug);
                if ($target.length) {
                    $target.fadeIn(200);
                } else {
                    $('.ak23-series-section').fadeIn(200);
                }
                $('.ak23-filter-btn').removeClass('active');
                $('.ak23-filter-btn[data-series="' + seriesSlug + '"]').addClass('active');
            }
        }

        // Handle filter buttons click
        $(document).on('click', '.ak23-filter-btn', function(e) {
            e.preventDefault();
            const series = $(this).data('series') || 'all';
            const url = new URL(window.location.href);
            if (series === 'all') {
                url.searchParams.delete('series');
            } else {
                url.searchParams.set('series', series);
            }
            window.history.pushState({ series: series }, '', url.toString());
            applySeriesFilter(series);
        });

        // Intercept clicks on header navigation series links when already on catalog page
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

        // Add to Cart via AJAX
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
