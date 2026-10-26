@extends($activeTemplate . 'layouts.frontend')

@section('content')

<!-- =========================================================================
     AKDOWNLOADS SUB-NAVIGATION BAR (CATEGORIES)
     ========================================================================= -->
<nav class="ak-subnav-bar">
    <div class="container-fluid px-lg-5">
        <ul class="ak-subnav-list">
            <li>
                <a href="{{ route('home') }}" class="ak-subnav-link {{ !request('category') && !request('sub_category') ? 'active' : '' }}">
                    @lang('All Categories')
                </a>
            </li>
            @foreach($categories as $category)
                <li>
                    <a href="{{ route('home', ['category' => $category->id]) }}" 
                       class="ak-subnav-link {{ request('category') == $category->id ? 'active' : '' }}">
                        {{ __($category->name) }}
                    </a>
                </li>
                @if($category->subCategories && $category->subCategories->isNotEmpty())
                    @foreach($category->subCategories as $subCat)
                        <li>
                            <a href="{{ route('home', ['sub_category' => $subCat->id]) }}" 
                               class="ak-subnav-link {{ request('sub_category') == $subCat->id ? 'active' : '' }}">
                                {{ __($subCat->name) }}
                            </a>
                        </li>
                    @endforeach
                @endif
            @endforeach
        </ul>
    </div>
</nav>

<!-- =========================================================================
     MAIN BODY CONTAINER (SEARCH, NOTICE & PRODUCT CATALOG)
     ========================================================================= -->
<main class="ak-home-main py-4">
    <div class="container">
        
        <!-- Centered Search Bar -->
        <div class="ak-search-wrapper mb-4">
            <form action="{{ route('products') }}" method="GET" class="ak-search-form">
                <i class="las la-search ak-search-icon"></i>
                <input type="text" 
                       name="search" 
                       class="ak-search-input" 
                       placeholder="Search products..." 
                       value="{{ request('search') }}">
            </form>
        </div>

        <!-- Main Title Heading -->
        <div class="text-center mb-3">
            <h1 class="ak-main-heading">DOWNLOAD YOUR FAVOURITE MUSIC TOOLS</h1>
        </div>

        <!-- Warning / Information Notice Banner -->
        <div class="ak-notice-banner mb-4">
            <div class="ak-notice-content">
                <i class="las la-exclamation-circle ak-notice-icon"></i>
                <span>
                    <strong>Muhimu!</strong> Katika kila Software na Plugin nimekuwekea video ya maelekezo ya jinsi ya ku-install na kuanza kutumia. Kwa files zote password ni, <a href="https://www.akdownloads.com" target="_blank" class="ak-notice-link">www.akdownloads.com</a>
                </span>
            </div>
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
                                @if($product->is_free)
                                    <span class="text-success">FREE</span>
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

        <!-- Pagination Controls matching akdownloads -->
        @if($products->hasPages())
            <div class="d-flex justify-content-center mb-5">
                {{ $products->links() }}
            </div>
        @endif

        <!-- =====================================================================
             TRENDING THIS WEEK SECTION
             ===================================================================== -->
        @if($trendingProducts->isNotEmpty())
            <div class="ak-section-header text-center mb-4 mt-5">
                <h2 class="ak-section-title">Trending This Week</h2>
            </div>

            <div class="row g-3 g-md-4 mb-5">
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
                                    <span>TSH {{ number_format($tPrice) }}</span>
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

        <!-- =====================================================================
             TOP CREATORS SECTION (MATCHING AKDOWNLOADS)
             ===================================================================== -->
        <div class="ak-section-header text-center mb-4 mt-5">
            <h2 class="ak-section-title">Top Creators</h2>
        </div>

        <div class="row g-3 g-md-4 mb-5 justify-content-center">
            @php
                $creatorShowcase = [
                    [
                        'name' => 'O.Righty',
                        'initials' => 'A&T',
                        'products' => '2 Products',
                        'bg' => 'linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%)',
                        'url' => route('products') . '?search=O.Righty',
                    ],
                    [
                        'name' => 'RENATUS KAYANDA',
                        'initials' => 'RK',
                        'products' => '1 Products',
                        'bg' => 'linear-gradient(135deg, #065F46 0%, #10B981 100%)',
                        'url' => route('products') . '?search=Renatus',
                    ],
                    [
                        'name' => 'Wolinetrn',
                        'initials' => 'WN',
                        'products' => '1 Products',
                        'bg' => 'linear-gradient(135deg, #7C2D12 0%, #F59E0B 100%)',
                        'url' => route('products') . '?search=Wolinetrn',
                    ],
                    [
                        'name' => 'Shortkeys Bosco',
                        'initials' => 'S',
                        'products' => '1 Products',
                        'bg' => 'linear-gradient(135deg, #831843 0%, #EC4899 100%)',
                        'url' => route('products') . '?search=Shortkeys',
                    ]
                ];
            @endphp

            @foreach($creatorShowcase as $creator)
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="ak-creator-card">
                        <!-- Circular Avatar -->
                        <div class="ak-creator-avatar" style="background: {{ $creator['bg'] }};">
                            <span>{{ $creator['initials'] }}</span>
                        </div>

                        <!-- Name -->
                        <h4 class="ak-creator-name">{{ $creator['name'] }}</h4>

                        <!-- Products Count -->
                        <div class="ak-creator-meta">
                            <i class="las la-music"></i>
                            <span>{{ $creator['products'] }}</span>
                        </div>

                        <!-- Social Link Icon -->
                        <div class="ak-creator-socials">
                            <a href="{{ $creator['url'] }}" aria-label="Website"><i class="las la-globe"></i></a>
                            <a href="{{ $creator['url'] }}" aria-label="Instagram"><i class="lab la-instagram"></i></a>
                        </div>

                        <!-- View Store Button -->
                        <a href="{{ $creator['url'] }}" class="ak-creator-btn">
                            View Store
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</main>

@endsection

@push('style')
<style>
/* ==========================================================================
   AKDOWNLOADS SIMPLIFIED THEME & LAYOUT
   ========================================================================== */

/* Sub-Navigation Bar */
.ak-subnav-bar {
    background: #000000;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    overflow-x: auto;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}
.ak-subnav-bar::-webkit-scrollbar {
    display: none;
}
.ak-subnav-list {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 10px 0;
    margin: 0;
    list-style: none;
}
.ak-subnav-link {
    color: #CBD5E1;
    font-size: 12.5px;
    font-weight: 500;
    text-decoration: none;
    transition: color 0.15s ease;
    display: inline-block;
}
.ak-subnav-link:hover,
.ak-subnav-link.active {
    color: #FBAF43;
    font-weight: 700;
}

/* Main Body Background */
.ak-home-main {
    background-color: #0E0F13;
    min-height: 80vh;
}

/* Centered Search Bar */
.ak-search-wrapper {
    max-width: 540px;
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
    background: #FFFFFF;
    border: 1.5px solid #FBAF43;
    border-radius: 999px;
    padding: 0 20px 0 46px;
    font-size: 14.5px;
    color: #1E293B;
    outline: none;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    transition: all 0.2s ease;
}
.ak-search-input:focus {
    box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.25);
}
.ak-search-input::placeholder {
    color: #94A3B8;
}

/* Main Heading */
.ak-main-heading {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 24px;
    font-weight: 800;
    color: #F1F5F9;
    letter-spacing: -0.01em;
    margin: 10px 0;
}
@media (min-width: 768px) {
    .ak-main-heading {
        font-size: 28px;
    }
}

/* Notice Banner */
.ak-notice-banner {
    max-width: 820px;
    margin: 0 auto;
    background: rgba(251, 175, 67, 0.08);
    border: 1px solid rgba(251, 175, 67, 0.3);
    border-radius: 8px;
    padding: 12px 18px;
    text-align: center;
}
.ak-notice-content {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #E2E8F0;
    line-height: 1.5;
}
.ak-notice-icon {
    font-size: 18px;
    color: #FBAF43;
    flex-shrink: 0;
}
.ak-notice-link {
    color: #FBAF43;
    text-decoration: underline;
    font-weight: 700;
}
.ak-notice-link:hover {
    color: #FFC978;
}

/* Product Cards Grid (Matching akdownloads layout) */
.ak-card {
    background: #FFFFFF;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.ak-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
}
.ak-card-thumb {
    position: relative;
    width: 100%;
    padding-top: 100%; /* 1:1 Aspect Ratio */
    background: #000000;
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
    color: #000000;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.15s ease, background-color 0.15s ease;
    z-index: 2;
}
.ak-card-cart-btn:hover {
    background: #FFA126;
    transform: scale(1.1);
}

.ak-card-body {
    padding: 14px 12px 16px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}
.ak-card-title {
    font-size: 13.5px;
    font-weight: 700;
    line-height: 1.35;
    margin: 0 0 6px 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 36px;
}
.ak-card-title a {
    color: #0F172A;
    text-decoration: none;
    transition: color 0.15s ease;
}
.ak-card-title a:hover {
    color: #E29828;
}
.ak-card-price {
    font-size: 14.5px;
    font-weight: 800;
    color: #FBAF43;
    margin-bottom: 4px;
}
.ak-card-meta {
    font-size: 11px;
    color: #64748B;
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

/* Creator Card (Matching akdownloads dark cards) */
.ak-creator-card {
    background: #12141A;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    padding: 28px 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: transform 0.2s ease, border-color 0.2s ease;
}
.ak-creator-card:hover {
    transform: translateY(-3px);
    border-color: rgba(251, 175, 67, 0.3);
}
.ak-creator-avatar {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 800;
    color: #FFFFFF;
    margin-bottom: 14px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    border: 2px solid rgba(255, 255, 255, 0.2);
}
.ak-creator-name {
    font-size: 15px;
    font-weight: 800;
    color: #FFFFFF;
    margin: 0 0 6px 0;
}
.ak-creator-meta {
    font-size: 12px;
    color: #94A3B8;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 12px;
}
.ak-creator-socials {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
}
.ak-creator-socials a {
    color: #64748B;
    font-size: 16px;
    text-decoration: none;
    transition: color 0.15s ease;
}
.ak-creator-socials a:hover {
    color: #FBAF43;
}
.ak-creator-btn {
    display: inline-block;
    width: 100%;
    padding: 8px 16px;
    background: rgba(251, 175, 67, 0.1);
    border: 1px solid rgba(251, 175, 67, 0.3);
    border-radius: 8px;
    color: #FBAF43;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s ease;
}
.ak-creator-btn:hover {
    background: #FBAF43;
    color: #000000;
}
</style>
@endpush
