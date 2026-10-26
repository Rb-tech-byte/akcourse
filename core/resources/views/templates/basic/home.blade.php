@extends($activeTemplate . 'layouts.frontend')

@section('content')

<!-- =========================================================================
     MAIN BODY CONTAINER (SEARCH, NOTICE & PRODUCT CATALOG)
     ========================================================================= -->
<main class="ak-home-main py-4">
    <div class="container">
        
        <!-- Centered Search Bar -->
        <div class="ak-search-wrapper mb-4">
            <form action="{{ route('home') }}" method="GET" class="ak-search-form">
                <i class="las la-search ak-search-icon"></i>
                <input type="text" 
                       name="search" 
                       class="ak-search-input" 
                       placeholder="Search products..." 
                       value="{{ request('search') }}">
            </form>
        </div>

        <!-- Maarifa23 Main Title Heading -->
        <div class="text-center mb-3">
            <div class="ak23-studio-badge mb-2">
                <i class="las la-graduation-cap text-warning me-1"></i>
                <span>MAARIFA23 STUDIO ACADEMY &amp; SOFTWARE</span>
            </div>
            <h1 class="ak-main-heading">
                JIFUNZE PRODUCTION NA PAKUA <span class="ak23-gold-gradient">STUDIO MUSIC TOOLS</span> ZAKO
            </h1>
        </div>

        <!-- Maarifa23 Studio Guidance Banner (Dismissible & Aligned) -->
        <div class="ak-notice-banner mb-4" id="ak23NoticeBanner">
            <div class="ak-notice-content">
                <i class="las la-shield-alt ak-notice-icon"></i>
                <span>
                    <strong>Mwongozo wa Maarifa23:</strong> Katika kila Software na Plugin nimekuwekea video ya maelekezo ya jinsi ya ku-install na kuanza kutumia. Password ya files zote ni 
                    <span class="ak-pwd-badge" id="akNoticePwd" title="@lang('Bofya ku-copy password')">
                        <code class="ak-pwd-text">maarifa23.com</code>
                        <i class="las la-copy ak-pwd-icon"></i>
                        <span class="ak-pwd-toast" id="akPwdToast">Copied!</span>
                    </span>
                </span>
            </div>
            <button type="button" class="ak-notice-close" id="akNoticeClose" aria-label="@lang('Funga')" title="@lang('Funga mwongozo')">
                <i class="las la-times"></i>
            </button>
        </div>

        <!-- Primary Products Catalog Grid (4 Columns per Row) -->
        <div class="row g-3 g-md-4 mb-4" id="mainProductGrid">
            @forelse($products as $product)
                @php
                    $prodTitle = $product->title ?: $product->name;
                    $prodPrice = (float)$product->price;
                    $prodSku   = $product->sku ?: ('AK23-PROD-' . $product->id);
                    $prodUrl   = route('product.details', $product->slug);
                    $catName   = $product->category ? $product->category->name : 'MACOS';
                    $prodDate  = $product->created_at ? $product->created_at->format('M d, Y') : 'Sep 17, 2026';
                @endphp
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6">
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
                <div class="col-12 text-center py-5">
                    <i class="las la-box-open fs-1 text-muted"></i>
                    <p class="text-muted mt-2">No products found. Try adjusting your search query.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination Controls (Aligned with Product Grid, Luxury Studio Theme) -->
        @if($products->hasPages())
            <div class="ak-pagination-wrapper my-4">
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
                <h2 class="ak-section-title">Trending This Week</h2>
            </div>

            <div class="row g-3 g-md-4 mb-4">
                @foreach($trendingProducts as $tProduct)
                    @php
                        $tTitle = $tProduct->title ?: $tProduct->name;
                        $tPrice = (float)$tProduct->price;
                        $tSku   = $tProduct->sku ?: ('AK23-TREND-' . $tProduct->id);
                        $tUrl   = route('product.details', $tProduct->slug);
                        $tCat   = $tProduct->category ? $tProduct->category->name : 'Trending';
                    @endphp
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6">
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
    max-width: 560px;
    margin: 0 auto;
}
.ak-search-form {
    position: relative;
    width: 100%;
}
.ak-search-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #FBAF43;
    font-size: 18px;
    pointer-events: none;
}
.ak-search-input {
    width: 100%;
    height: 48px;
    background: #111118;
    border: 1.5px solid rgba(255, 255, 255, 0.1);
    border-radius: 999px;
    padding: 0 20px 0 46px;
    font-size: 14px;
    color: #FFFFFF;
    outline: none;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35);
    transition: all 0.2s ease;
}
.ak-search-input:focus {
    border-color: #FBAF43;
    background: #151520;
    box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.2);
}
.ak-search-input::placeholder {
    color: #8E8EA2;
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
    padding-top: 64%; /* Reduced height on top for square-like overall card */
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
    top: 8px;
    right: 8px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #FBAF43;
    border: none;
    color: #09090D;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
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
    padding: 10px 12px 12px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    background: #111118;
}
.ak-card-title {
    font-size: 13px;
    font-weight: 700;
    line-height: 1.3;
    margin: 0 0 4px 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 34px;
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
    font-size: 13.5px;
    font-weight: 800;
    color: #FBAF43;
    margin-bottom: 2px;
}
.ak-card-meta {
    font-size: 10.5px;
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
});
</script>
@endpush
