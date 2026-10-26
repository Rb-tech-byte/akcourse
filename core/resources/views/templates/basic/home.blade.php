@extends($activeTemplate . 'layouts.frontend')

@section('content')

<!-- =========================================================================
     MAIN BODY CONTAINER (SEARCH, NOTICE & PRODUCT CATALOG)
     ========================================================================= -->
<main class="ak-home-main py-4">
    <div class="container">
        
        <!-- Centered Search Bar with Real-Time Floating Results -->
        <div class="ak-search-wrapper mb-4" id="akHomeSearchWrapper">
            <form action="{{ route('home') }}" method="GET" class="ak-search-form" id="akHomeSearchForm" autocomplete="off">
                <i class="las la-search ak-search-icon" id="akSearchIcon"></i>
                <div class="ak-search-spinner d-none" id="akSearchSpinner">
                    <i class="las la-circle-notch la-spin"></i>
                </div>
                <input type="text" 
                       id="akHomeSearchInput"
                       name="search" 
                       class="ak-search-input" 
                       placeholder="@lang('Search courses, DAWs, plugins, or software...')" 
                       value="{{ request('search') }}"
                       autocomplete="off"
                       spellcheck="false">
                <button type="button" class="ak-search-clear {{ request('search') ? '' : 'd-none' }}" id="akSearchClear" aria-label="@lang('Clear search')" title="@lang('Clear')">
                    <i class="las la-times"></i>
                </button>

                <!-- Floating Live Search Dropdown -->
                <div class="ak-search-dropdown d-none" id="akSearchDropdown">
                    <div class="ak-search-dropdown-header">
                        <span class="ak-search-count-text">
                            <i class="las la-bolt text-warning me-1"></i> <span id="akDropdownCount">0</span> @lang('Quick results')
                        </span>
                        <span class="ak-search-esc-hint"><kbd>ESC</kbd> @lang('to close')</span>
                    </div>

                    <div class="ak-search-dropdown-body" id="akDropdownList">
                        <!-- Populated dynamically via JS -->
                    </div>

                    <div class="ak-search-dropdown-footer" id="akDropdownFooter">
                        <a href="{{ route('products') }}" class="ak-search-view-all-link" id="akDropdownViewAll">
                            <span>@lang('View all courses in Catalog')</span>
                            <i class="las la-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Maarifa23 Main Title Heading -->
        <div class="text-center mb-3">
            <div class="ak23-studio-badge mb-2">
                <i class="las la-graduation-cap text-warning me-1"></i>
                <span>@lang('MAARIFA23 STUDIO ACADEMY & SOFTWARE')</span>
            </div>
            <h1 class="ak-main-heading">
                @lang('LEARN PRODUCTION & DOWNLOAD YOUR') <span class="ak23-gold-gradient">@lang('STUDIO MUSIC TOOLS')</span>
            </h1>
        </div>

        <!-- Maarifa23 Studio Guidance Banner (Dismissible & Aligned) -->
        <div class="ak-notice-banner mb-4" id="ak23NoticeBanner">
            <div class="ak-notice-content">
                <i class="las la-shield-alt ak-notice-icon"></i>
                <span>
                    <strong>@lang('Maarifa23 Guide'):</strong> @lang('Every Software and Plugin comes with a step-by-step video tutorial on installation and usage. Password for all files is') 
                    <span class="ak-pwd-badge" id="akNoticePwd" title="@lang('Click to copy password')">
                        <code class="ak-pwd-text">maarifa23.com</code>
                        <i class="las la-copy ak-pwd-icon"></i>
                        <span class="ak-pwd-toast" id="akPwdToast">@lang('Copied!')</span>
                    </span>
                </span>
            </div>
            <button type="button" class="ak-notice-close" id="akNoticeClose" aria-label="@lang('Close')" title="@lang('Close guide')">
                <i class="las la-times"></i>
            </button>
        </div>

        <!-- Live Real-Time Search Status Banner -->
        <div class="ak-search-live-status d-none mb-3" id="akSearchLiveStatus">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="ak-live-badge">
                        <i class="las la-search text-warning me-1"></i> @lang('Quick Search'): <strong id="akLiveQueryText" class="text-white"></strong>
                    </span>
                    <span class="text-secondary fs-13">(<span id="akLiveMatchCount">0</span> @lang('courses found'))</span>
                </div>
                <button type="button" class="ak-live-reset-btn" id="akLiveResetBtn">
                    <i class="las la-times-circle me-1"></i> @lang('Clear · Show All')
                </button>
            </div>
        </div>

        <!-- Primary Products Catalog Grid (3 Cards per Row with Clear Spacing) -->
        <div class="ak-products-grid-3 mb-4" id="mainProductGrid">
            @forelse($products as $product)
                @php
                    $prodTitle = $product->title ?: $product->name;
                    $prodPrice = (float)$product->price;
                    $prodSku   = $product->sku ?: ('AK23-PROD-' . $product->id);
                    $prodUrl   = route('product.details', $product->slug);
                    $catName   = $product->category ? $product->category->name : 'MACOS';
                    $prodDate  = $product->created_at ? $product->created_at->format('M d, Y') : 'Sep 17, 2026';
                @endphp
                <div class="ak-card-item">
                    <div class="ak-card">
                        <!-- Thumbnail Wrapper with Cart Button -->
                        <div class="ak-card-thumb">
                            <a href="{{ $prodUrl }}">
                                <img src="{{ $product->thumbnail_url }}" alt="{{ $prodTitle }}" class="ak-card-img" loading="lazy">
                            </a>
                            <button type="button" 
                                    class="ak-card-cart-btn ak23-btn-add-cart" 
                                    data-sku="{{ $prodSku }}" 
                                    title="Add to Cart">
                                <i class="las la-cart-plus"></i>
                            </button>
                        </div>

                        <!-- Card Body -->
                        <div class="ak-card-body">
                            <h3 class="ak-card-title" title="{{ $prodTitle }}">
                                <a href="{{ $prodUrl }}">{{ $prodTitle }}</a>
                            </h3>
                            <div class="ak-card-price">
                                @if($product->is_free || $prodPrice <= 0)
                                    <span class="text-success fw-bold">FREE</span>
                                @else
                                    <span>TSH {{ number_format($prodPrice) }}</span>
                                @endif
                            </div>
                            <div class="ak-card-meta">
                                <span>{{ strtoupper($catName) }}</span>
                                <span class="mx-1">&middot;</span>
                                <span>{{ $prodDate }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="ak-grid-empty text-center py-5">
                    <i class="las la-box-open fs-1 text-muted"></i>
                    <p class="text-muted mt-2">No products found. Try adjusting your search query.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination Controls (Aligned with Product Grid, Luxury Studio Theme) -->
        @if($products->hasPages())
            <div class="ak-pagination-wrapper my-4" id="akHomePaginationWrapper">
                <div class="ak-pagination-info">
                    @lang('Showing') <span class="ak-pagination-num">{{ $products->firstItem() }}</span> 
                    @lang('to') <span class="ak-pagination-num">{{ $products->lastItem() }}</span> 
                    @lang('of') <span class="ak-pagination-num">{{ $products->total() }}</span> @lang('results')
                </div>

                <div class="ak-pagination-nav">
                    {{-- Previous Page Link --}}
                    @if ($products->onFirstPage())
                        <span class="ak-page-link disabled" aria-disabled="true">&lsaquo;</span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="ak-page-link" rel="prev" aria-label="@lang('Previous')">&lsaquo;</a>
                    @endif

                    @php
                        $cur   = $products->currentPage();
                        $last  = $products->lastPage();
                        $start = max(1, $cur - 2);
                        $end   = min($last, $cur + 2);
                    @endphp

                    @if ($start > 1)
                        <a href="{{ $products->url(1) }}" class="ak-page-link">1</a>
                        @if ($start > 2)
                            <span class="ak-page-link dots">&hellip;</span>
                        @endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        @if ($i == $cur)
                            <span class="ak-page-link active" aria-current="page">{{ $i }}</span>
                        @else
                            <a href="{{ $products->url($i) }}" class="ak-page-link">{{ $i }}</a>
                        @endif
                    @endfor

                    @if ($end < $last)
                        @if ($end < $last - 1)
                            <span class="ak-page-link dots">&hellip;</span>
                        @endif
                        <a href="{{ $products->url($last) }}" class="ak-page-link">{{ $last }}</a>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="ak-page-link" rel="next" aria-label="@lang('Next')">&rsaquo;</a>
                    @else
                        <span class="ak-page-link disabled" aria-disabled="true">&rsaquo;</span>
                    @endif
                </div>
            </div>
        @endif

        <!-- =====================================================================
             TRENDING THIS WEEK SECTION
             ===================================================================== -->
        @if($trendingProducts->isNotEmpty())
            <div class="ak-section-header text-center mb-4 mt-5">
                <h2 class="ak-section-title">@lang('Trending This Week')</h2>
            </div>

            <div class="ak-products-grid-3 mb-4">
                @foreach($trendingProducts as $tProduct)
                    @php
                        $tTitle = $tProduct->title ?: $tProduct->name;
                        $tPrice = (float)$tProduct->price;
                        $tSku   = $tProduct->sku ?: ('AK23-TREND-' . $tProduct->id);
                        $tUrl   = route('product.details', $tProduct->slug);
                        $tCat   = $tProduct->category ? $tProduct->category->name : 'Trending';
                    @endphp
                    <div class="ak-card-item">
                        <div class="ak-card">
                            <div class="ak-card-thumb">
                                <a href="{{ $tUrl }}">
                                    <img src="{{ $tProduct->thumbnail_url }}" alt="{{ $tTitle }}" class="ak-card-img" loading="lazy">
                                </a>
                                <button type="button" 
                                        class="ak-card-cart-btn ak23-btn-add-cart" 
                                        data-sku="{{ $tSku }}" 
                                        title="Add to Cart">
                                    <i class="las la-cart-plus"></i>
                                </button>
                            </div>
                            <div class="ak-card-body">
                                <h3 class="ak-card-title" title="{{ $tTitle }}">
                                    <a href="{{ $tUrl }}">{{ $tTitle }}</a>
                                </h3>
                                <div class="ak-card-price">
                                    @if($tProduct->is_free || $tPrice <= 0)
                                        <span class="text-success fw-bold">FREE</span>
                                    @else
                                        <span>TSH {{ number_format($tPrice) }}</span>
                                    @endif
                                </div>
                                <div class="ak-card-meta">
                                    <span>{{ $tCat }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</main>

@endsection

@push('style')
<style>
/* ==========================================================================
   MAARIFA23 STUDIO DARK THEME & COMPACT NATIVE FIT
   ========================================================================== */

/* Main Body Background */
.ak-home-main {
    background-color: #09090D;
    padding: 24px 0 44px;
}

/* Centered Search Bar */
.ak-search-wrapper {
    max-width: 620px;
    margin: 0 auto;
    position: relative;
}
.ak-search-form {
    position: relative;
    width: 100%;
}
.ak-search-icon,
.ak-search-spinner {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #FBAF43;
    font-size: 18px;
    pointer-events: none;
    z-index: 2;
}
.ak-search-spinner {
    animation: akSearchSpin 0.75s linear infinite;
}
@keyframes akSearchSpin {
    from { transform: translateY(-50%) rotate(0deg); }
    to { transform: translateY(-50%) rotate(360deg); }
}
.ak-search-clear {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.08);
    border: none;
    border-radius: 50%;
    width: 26px;
    height: 26px;
    color: #94A3B8;
    font-size: 14px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
    z-index: 2;
}
.ak-search-clear:hover {
    background: rgba(255, 255, 255, 0.18);
    color: #FFFFFF;
}
.ak-search-input {
    width: 100%;
    height: 52px;
    background: #111118;
    border: 1.5px solid rgba(255, 255, 255, 0.1);
    border-radius: 999px;
    padding: 0 46px 0 48px;
    font-size: 14.5px;
    color: #FFFFFF;
    outline: none;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.45);
    transition: all 0.2s ease;
}
.ak-search-input:focus {
    border-color: #FBAF43;
    background: #141620;
    box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.2), 0 8px 24px rgba(0, 0, 0, 0.5);
}
.ak-search-input::placeholder {
    color: #8E8EA2;
}

/* Floating Real-Time Search Results Dropdown */
.ak-search-dropdown {
    position: absolute;
    top: calc(100% + 10px);
    left: 0;
    right: 0;
    background: #13151F;
    border: 1.5px solid rgba(251, 175, 67, 0.35);
    border-radius: 18px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.85), 0 0 30px rgba(251, 175, 67, 0.12);
    overflow: hidden;
    z-index: 1050;
    backdrop-filter: blur(14px);
    animation: akDropdownFade 0.18s ease-out;
}
@keyframes akDropdownFade {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}
.ak-search-dropdown-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 18px;
    background: rgba(255, 255, 255, 0.02);
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    font-size: 12px;
    color: #94A3B8;
}
.ak-search-esc-hint kbd {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 4px;
    padding: 2px 6px;
    font-size: 10px;
    color: #CBD5E1;
}
.ak-search-dropdown-body {
    max-height: 380px;
    overflow-y: auto;
    overscroll-behavior: contain;
}
.ak-search-dropdown-body::-webkit-scrollbar {
    width: 6px;
}
.ak-search-dropdown-body::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.2);
}
.ak-search-dropdown-body::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.15);
    border-radius: 4px;
}
.ak-search-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 11px 18px;
    text-decoration: none;
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    transition: all 0.15s ease;
    color: inherit;
}
.ak-search-item:hover,
.ak-search-item.highlighted {
    background: rgba(251, 175, 67, 0.1);
    border-left: 3px solid #FBAF43;
    padding-left: 21px;
}
.ak-search-item-img {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid rgba(255, 255, 255, 0.08);
    flex-shrink: 0;
    background: #0A0B0E;
}
.ak-search-item-info {
    flex-grow: 1;
    min-width: 0;
    text-align: left;
}
.ak-search-item-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #F1F5F9;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ak-search-item:hover .ak-search-item-title,
.ak-search-item.highlighted .ak-search-item-title {
    color: #FBAF43;
}
.ak-search-item-meta {
    font-size: 11px;
    color: #94A3B8;
    margin-top: 3px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.ak-search-item-badge {
    background: rgba(251, 175, 67, 0.12);
    border-radius: 4px;
    padding: 1px 6px;
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: #FBAF43;
}
.ak-search-item-price {
    font-size: 13px;
    font-weight: 800;
    color: #FBAF43;
    flex-shrink: 0;
    white-space: nowrap;
}
.ak-search-dropdown-footer {
    padding: 11px 18px;
    background: rgba(255, 255, 255, 0.02);
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    text-align: center;
}
.ak-search-view-all-link {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 12.5px;
    font-weight: 700;
    color: #FBAF43;
    text-decoration: none !important;
    transition: all 0.15s ease;
}
.ak-search-view-all-link:hover {
    color: #FFC978;
    gap: 11px;
}
.ak-search-match {
    background: rgba(251, 175, 67, 0.28);
    color: #FFD580;
    padding: 0 3px;
    border-radius: 3px;
    font-weight: 800;
}

/* In-Page Real-Time Live Status Bar */
.ak-search-live-status {
    background: rgba(251, 175, 67, 0.06);
    border: 1px solid rgba(251, 175, 67, 0.22);
    border-radius: 12px;
    padding: 10px 18px;
}
.ak-live-badge {
    font-size: 13px;
    color: #CBD5E1;
}
.ak-live-reset-btn {
    background: transparent;
    border: none;
    color: #FBAF43;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.15s ease;
    padding: 0;
}
.ak-live-reset-btn:hover {
    color: #FFC978;
    text-decoration: underline;
}

/* Main Heading */
.ak23-studio-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(251, 175, 67, 0.08);
    border: 1px solid rgba(251, 175, 67, 0.25);
    border-radius: 20px;
    padding: 4px 14px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.08em;
    color: #FBAF43;
    text-transform: uppercase;
}
.ak23-gold-gradient {
    background: linear-gradient(135deg, #FBAF43 0%, #F59E0B 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.ak-main-heading {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 22px;
    font-weight: 800;
    color: #F1F5F9;
    letter-spacing: -0.01em;
    margin: 8px 0;
}
@media (min-width: 768px) {
    .ak-main-heading {
        font-size: 26px;
    }
}

/* Notice Banner */
.ak-notice-banner {
    max-width: 860px;
    margin: 0 auto;
    background: rgba(17, 17, 24, 0.95);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(251, 175, 67, 0.25);
    border-radius: 10px;
    padding: 10px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
    transition: all 0.25s ease;
}
.ak-notice-content {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #CBD5E1;
    line-height: 1.5;
    text-align: left;
    flex: 1;
}
.ak-notice-icon {
    font-size: 22px;
    color: #FBAF43;
    flex-shrink: 0;
}
.ak-pwd-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(251, 175, 67, 0.12);
    border: 1px solid rgba(251, 175, 67, 0.35);
    border-radius: 6px;
    padding: 2px 8px;
    cursor: pointer;
    transition: all 0.15s ease;
    position: relative;
    margin-left: 4px;
    user-select: none;
}
.ak-pwd-badge:hover {
    background: rgba(251, 175, 67, 0.22);
    border-color: #FBAF43;
    transform: translateY(-1px);
}
.ak-pwd-text {
    color: #FBAF43;
    font-weight: 700;
    font-family: monospace;
    font-size: 12.5px;
    background: transparent;
    padding: 0;
}
.ak-pwd-icon {
    font-size: 13px;
    color: #FBAF43;
}
.ak-pwd-toast {
    position: absolute;
    top: -26px;
    left: 50%;
    transform: translateX(-50%);
    background: #10B981;
    color: #FFFFFF;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 4px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s ease;
    white-space: nowrap;
}
.ak-pwd-toast.show {
    opacity: 1;
}
.ak-notice-close {
    background: transparent;
    border: none;
    color: #94A3B8;
    font-size: 18px;
    cursor: pointer;
    padding: 4px 6px;
    line-height: 1;
    border-radius: 6px;
    transition: all 0.15s ease;
    flex-shrink: 0;
}
.ak-notice-close:hover {
    color: #FFFFFF;
    background: rgba(255, 255, 255, 0.08);
}

.ak-home-main .container {
    max-width: 1280px;
}

/* 3 Cards Per Row Grid with Generous Spacing to Avoid Overcrowding */
.ak-products-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    column-gap: 30px;
    row-gap: 38px;
    margin-bottom: 44px;
}

@media (max-width: 991px) {
    .ak-products-grid-3 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        column-gap: 22px;
        row-gap: 28px;
    }
}

@media (max-width: 580px) {
    .ak-products-grid-3 {
        grid-template-columns: 1fr;
        row-gap: 24px;
    }
}

.ak-products-grid-3 .ak-grid-empty {
    grid-column: 1 / -1;
}

/* Product Cards Grid (Studio Dark Texture & Smooth Looks) */
.ak-card {
    background: #111118;
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35);
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
}
.ak-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(251, 175, 67, 0.3);
    border-color: rgba(251, 175, 67, 0.4);
}
.ak-card-thumb {
    position: relative;
    width: 100%;
    padding-top: 58%; /* Reduced height on top for square-like overall card */
    background: #09090D;
    overflow: hidden;
}
.ak-card-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}
.ak-card:hover .ak-card-img {
    transform: scale(1.04);
}
.ak-card-cart-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #FBAF43;
    border: none;
    color: #09090D;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    transition: transform 0.15s ease, background-color 0.15s ease;
    z-index: 2;
}
.ak-card-cart-btn:hover {
    background: #FFBE5C;
    transform: scale(1.1);
}

.ak-card-body {
    padding: 13px 15px 15px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    background: #111118;
}
.ak-card-title {
    font-size: 14.5px;
    font-weight: 700;
    line-height: 1.35;
    margin: 0 0 6px 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 38px;
}
.ak-card-title a {
    color: #FFFFFF;
    text-decoration: none;
    transition: color 0.15s ease;
}
.ak-card-title a:hover {
    color: #FBAF43;
}
.ak-card-price {
    font-size: 15.5px;
    font-weight: 800;
    color: #FBAF43;
    margin-bottom: 6px;
}
.ak-card-meta {
    font-size: 11.5px;
    color: #8E8EA2;
    margin-top: auto;
}

/* Section Title */
.ak-section-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 22px;
    font-weight: 800;
    color: #F1F5F9;
    letter-spacing: -0.01em;
}

/* ==========================================================================
   PAGINATION STYLING (ALIGNED & CLEAR)
   ========================================================================== */
.ak-pagination-wrapper {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    padding: 16px 20px;
    background: #111118;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}
.ak-pagination-info {
    font-size: 13.5px;
    font-weight: 500;
    color: #94A3B8;
    display: flex;
    align-items: center;
    gap: 4px;
}
.ak-pagination-num {
    font-weight: 700;
    color: #FFFFFF;
}
.ak-pagination-nav {
    display: flex;
    align-items: center;
    gap: 6px;
    list-style: none;
    margin: 0;
    padding: 0;
}
.ak-page-link {
    min-width: 38px;
    height: 38px;
    padding: 0 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    color: #CBD5E1;
    font-size: 13.5px;
    font-weight: 600;
    text-decoration: none !important;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    user-select: none;
}
.ak-page-link:hover {
    background: rgba(251, 175, 67, 0.15);
    border-color: #FBAF43;
    color: #FBAF43;
    transform: translateY(-1px);
}
.ak-page-link.active {
    background: #FBAF43;
    border-color: #FBAF43;
    color: #0E0F13;
    font-weight: 800;
    box-shadow: 0 4px 12px rgba(251, 175, 67, 0.35);
}
.ak-page-link.disabled {
    opacity: 0.35;
    pointer-events: none;
    border-color: rgba(255, 255, 255, 0.05);
}
.ak-page-link.dots {
    border-color: transparent;
    background: transparent;
    pointer-events: none;
    color: #64748B;
}
@media (max-width: 575.98px) {
    .ak-pagination-wrapper {
        flex-direction: column;
        justify-content: center;
        text-align: center;
        gap: 12px;
        padding: 14px;
    }
    .ak-pagination-info {
        justify-content: center;
    }
    .ak-pagination-nav {
        justify-content: center;
        width: 100%;
        flex-wrap: wrap;
    }
}
</style>
@endpush

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const noticeBanner = document.getElementById('ak23NoticeBanner');
    const closeBtn     = document.getElementById('akNoticeClose');
    const pwdBadge     = document.getElementById('akNoticePwd');
    const pwdToast     = document.getElementById('akPwdToast');

    if (noticeBanner && localStorage.getItem('ak23_notice_dismissed') === '1') {
        noticeBanner.style.display = 'none';
    }

    if (closeBtn && noticeBanner) {
        closeBtn.addEventListener('click', function() {
            noticeBanner.style.opacity = '0';
            noticeBanner.style.transform = 'translateY(-6px)';
            setTimeout(function() {
                noticeBanner.style.display = 'none';
                localStorage.setItem('ak23_notice_dismissed', '1');
            }, 200);
        });
    }

    if (pwdBadge) {
        pwdBadge.addEventListener('click', function() {
            const pwdToCopy = 'maarifa23.com';
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(pwdToCopy).then(showToast);
            } else {
                const tempInput = document.createElement('input');
                tempInput.value = pwdToCopy;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                showToast();
            }

            function showToast() {
                if (pwdToast) {
                    pwdToast.classList.add('show');
                    setTimeout(function() {
                        pwdToast.classList.remove('show');
                    }, 1800);
                }
            }
        });
    }

    // =========================================================================
    // REAL-TIME SEARCH ENGINE (DROPDOWN + IN-PAGE GRID SYNC)
    // =========================================================================
    (function($) {
        if (typeof $ === 'undefined') return;

        const searchEndpoint = "{{ route('search.ajax') }}";
        const $wrapper       = $('#akHomeSearchWrapper');
        const $form          = $('#akHomeSearchForm');
        const $input         = $('#akHomeSearchInput, #search, input[name="search"]');
        const $clearBtn      = $('#akSearchClear');
        const $searchIcon    = $('#akSearchIcon');
        const $spinner       = $('#akSearchSpinner');
        const $dropdown      = $('#akSearchDropdown');
        const $dropdownBody  = $('#akDropdownList');
        const $dropdownCount = $('#akDropdownCount');
        const $dropdownFooter= $('#akDropdownFooter');
        const $dropdownViewAll = $('#akDropdownViewAll');
        const $liveStatus    = $('#akSearchLiveStatus');
        const $liveQuery     = $('#akLiveQueryText');
        const $liveCount     = $('#akLiveMatchCount');
        const $liveReset     = $('#akLiveResetBtn');
        const $productGrid   = $('#mainProductGrid');
        const $pagination    = $('#akHomePaginationWrapper');

        if (!$input.length) return;

        // Cache initial server-rendered grid HTML for instant restoration
        const initialGridHtml = $productGrid.html();
        let debounceTimer     = null;
        let activeIndex       = -1;
        let currentXHR        = null;

        function escapeHtml(str) {
            return $('<div>').text(str || '').html();
        }

        function highlightMatch(text, query) {
            if (!query || !text) return escapeHtml(text);
            const cleanQuery = query.trim().replace(/[-[\]{}()*+?.,\\^$|#\s]/g, '\\$&');
            if (!cleanQuery) return escapeHtml(text);
            const regex = new RegExp('(' + cleanQuery + ')', 'gi');
            return escapeHtml(text).replace(regex, '<mark class="ak-search-match">$1</mark>');
        }

        function renderDropdown(items, query, totalCount, allUrl) {
            $dropdownBody.empty();
            activeIndex = -1;

            if (!items || items.length === 0) {
                $dropdownCount.text('0');
                $dropdownBody.html(`
                    <div class="ak-search-empty py-4 text-center">
                        <i class="las la-search text-warning fs-1 mb-2 opacity-75"></i>
                        <p class="mb-1 text-white fw-bold">@lang('No courses found') "${escapeHtml(query)}"</p>
                        <span class="text-secondary fs-12">@lang('No products found. Try adjusting your search query.')</span>
                    </div>
                `);
                $dropdownFooter.addClass('d-none');
                $dropdown.removeClass('d-none');
                return;
            }

            $dropdownCount.text(totalCount);
            $dropdownViewAll.attr('href', allUrl);
            $dropdownFooter.removeClass('d-none');

            items.forEach(function(item, idx) {
                const priceClass = item.is_free ? 'text-success' : 'text-warning';
                const priceText  = item.price_formatted;
                const highlightedTitle = highlightMatch(item.title, query);

                const $row = $(`
                    <a href="${item.url}" class="ak-search-item" data-index="${idx}" data-url="${item.url}">
                        <img src="${item.thumbnail_url}" alt="${escapeHtml(item.title)}" class="ak-search-item-img" loading="lazy">
                        <div class="ak-search-item-info">
                            <div class="ak-search-item-title">${highlightedTitle}</div>
                            <div class="ak-search-item-meta">
                                <span class="ak-search-item-badge">${escapeHtml(item.category)}</span>
                                <span>&middot;</span>
                                <span>${escapeHtml(item.date)}</span>
                            </div>
                        </div>
                        <div class="ak-search-item-price ${priceClass}">${priceText}</div>
                        <i class="las la-angle-right text-muted fs-14 ms-2"></i>
                    </a>
                `);

                $dropdownBody.append($row);
            });

            $dropdown.removeClass('d-none');
        }

        function renderGridCards(items, query) {
            if (!items || items.length === 0) {
                $productGrid.html(`
                    <div class="ak-grid-empty text-center py-5 w-100" style="grid-column: 1 / -1;">
                        <i class="las la-box-open fs-1 text-warning mb-2 opacity-75"></i>
                        <h4 class="text-white fw-bold">@lang('No courses found')</h4>
                        <p class="text-secondary mt-1">@lang('No products found. Try adjusting your search query.')</p>
                        <button type="button" class="btn btn-sm btn-outline-warning rounded-pill mt-2 px-3" id="akGridResetBtn">
                            <i class="las la-sync me-1"></i> @lang('Show All Courses')
                        </button>
                    </div>
                `);
                $('#akGridResetBtn').on('click', resetSearch);
                return;
            }

            let html = '';
            items.forEach(function(item) {
                const priceHtml = item.is_free 
                    ? '<span class="text-success fw-bold">FREE</span>' 
                    : `<span>${item.price_formatted}</span>`;

                html += `
                    <div class="ak-card-item">
                        <div class="ak-card">
                            <div class="ak-card-thumb">
                                <a href="${item.url}">
                                    <img src="${item.thumbnail_url}" alt="${escapeHtml(item.title)}" class="ak-card-img" loading="lazy">
                                </a>
                                <button type="button" 
                                        class="ak-card-cart-btn ak23-btn-add-cart" 
                                        data-sku="${item.sku}" 
                                        title="Add to Cart">
                                    <i class="las la-cart-plus"></i>
                                </button>
                            </div>
                            <div class="ak-card-body">
                                <h3 class="ak-card-title" title="${escapeHtml(item.title)}">
                                    <a href="${item.url}">${escapeHtml(item.title)}</a>
                                </h3>
                                <div class="ak-card-price">
                                    ${priceHtml}
                                </div>
                                <div class="ak-card-meta">
                                    <span>${escapeHtml(item.category)}</span>
                                    <span class="mx-1">&middot;</span>
                                    <span>${escapeHtml(item.date)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            $productGrid.html(html);
        }

        function performSearch(query) {
            query = (query || '').trim();

            if (query.length === 0) {
                resetSearch();
                return;
            }

            $clearBtn.removeClass('d-none');
            $searchIcon.addClass('d-none');
            $spinner.removeClass('d-none');

            if (currentXHR) {
                currentXHR.abort();
            }

            currentXHR = $.ajax({
                url: searchEndpoint,
                type: 'GET',
                data: { search: query },
                dataType: 'json',
                success: function(resp) {
                    $spinner.addClass('d-none');
                    $searchIcon.removeClass('d-none');

                    if (resp && resp.success) {
                        renderDropdown(resp.data, query, resp.count, resp.all_url);
                        renderGridCards(resp.data, query);

                        // Show live status banner
                        $liveQuery.text(query);
                        $liveCount.text(resp.count);
                        $liveStatus.removeClass('d-none');

                        // Hide pagination while searching
                        if ($pagination.length) {
                            $pagination.addClass('d-none');
                        }
                    }
                },
                error: function(xhr, status) {
                    if (status !== 'abort') {
                        $spinner.addClass('d-none');
                        $searchIcon.removeClass('d-none');
                    }
                }
            });
        }

        function resetSearch() {
            $input.val('');
            $clearBtn.addClass('d-none');
            $spinner.addClass('d-none');
            $searchIcon.removeClass('d-none');
            $dropdown.addClass('d-none');
            $dropdownBody.empty();
            $liveStatus.addClass('d-none');

            // Restore original server-rendered grid
            $productGrid.html(initialGridHtml);

            // Restore pagination
            if ($pagination.length) {
                $pagination.removeClass('d-none');
            }
        }

        // Real-time input listener (200ms debounce)
        $input.on('input', function() {
            const val = $(this).val();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                performSearch(val);
            }, 200);
        });

        // Clear button
        $clearBtn.on('click', function(e) {
            e.preventDefault();
            resetSearch();
            $input.focus();
        });

        // Reset banner button
        $liveReset.on('click', function(e) {
            e.preventDefault();
            resetSearch();
            $input.focus();
        });

        // Re-open dropdown on focus if input has value
        $input.on('focus', function() {
            const val = $(this).val().trim();
            if (val.length > 0 && $dropdownBody.children().length > 0) {
                $dropdown.removeClass('d-none');
            }
        });

        // Keyboard navigation in search dropdown
        $input.on('keydown', function(e) {
            const $items = $dropdownBody.find('.ak-search-item');
            const count = $items.length;

            if (e.key === 'ArrowDown') {
                if (!$dropdown.is(':visible') && count > 0) {
                    $dropdown.removeClass('d-none');
                }
                if (count === 0) return;
                e.preventDefault();
                activeIndex = (activeIndex + 1) % count;
                $items.removeClass('highlighted');
                const $active = $items.eq(activeIndex).addClass('highlighted');
                if ($active.length && $active[0].scrollIntoView) {
                    $active[0].scrollIntoView({ block: 'nearest' });
                }
            } else if (e.key === 'ArrowUp') {
                if (count === 0) return;
                e.preventDefault();
                activeIndex = (activeIndex - 1 + count) % count;
                $items.removeClass('highlighted');
                const $active = $items.eq(activeIndex).addClass('highlighted');
                if ($active.length && $active[0].scrollIntoView) {
                    $active[0].scrollIntoView({ block: 'nearest' });
                }
            } else if (e.key === 'Enter') {
                if (activeIndex >= 0 && $items.eq(activeIndex).length) {
                    e.preventDefault();
                    window.location.href = $items.eq(activeIndex).attr('href');
                }
            } else if (e.key === 'Escape') {
                $dropdown.addClass('d-none');
                activeIndex = -1;
            }
        });

        // Close dropdown on click outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#akHomeSearchWrapper').length) {
                $dropdown.addClass('d-none');
                activeIndex = -1;
            }
        });

    })(jQuery);
});
</script>
@endpush
