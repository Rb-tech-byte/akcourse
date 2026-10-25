@extends($activeTemplate . 'layouts.frontend')

@section('content')

@php
    $productPrice = (float)($product->price ?: 140000);
    $priceFormatted = number_format($productPrice);
    $skuCode = $product->sku ?: ($product->slug ?: 'AK23-PROD-' . $product->id);
    $softwareName = $product->software_name ?: 'Full Studio Plugin Suite';
    $rawEdition = $product->software_edition;
    $softwareEdition = (!$rawEdition || str_contains($rawEdition, '[Confirm')) ? 'Perpetual Commercial License Included' : $rawEdition;
    $stageLabel = $product->stage_label ?: 'Studio Production Edition';
    $rating = number_format((float)($product->avg_rating ?: 4.9), 1);
    $reviewCount = $product->total_review ?: 24;
    $salesCount = $product->total_sold ?: 142;
    $hasVideo = !empty($product->preview_video) || !empty($product->demo_url);
    $hasAudio = !empty($product->audio_temp_file) || (@$product->category->file_type === 'audio');
    $videoSrc = !empty($product->preview_video) ? getImage(getFilePath('previewVideo') . '/' . productFilePath($product, 'preview_video')) : ($product->demo_url ?: '');
    $audioSrc = !empty($product->audio_temp_file) ? asset(getFilePath('previewFile') . '/' . productFilePath($product, 'temp_audio_file') . '/' . $product->audio_temp_file) : '';
    $posterImg = $product->thumbnail_url ?: getImage(getFilePath('productPreview') . '/' . productFilePath($product, 'preview_image'), getFileSize('productPreview'));
@endphp

<div class="ak23-marketplace-product py-4 py-lg-5">
    <div class="container" style="max-width: 1220px;">

        <!-- =========================================================================
             BREADCRUMB & METADATA BAR
             ========================================================================= -->
        <div class="ak23-prod-breadcrumb mb-3">
            <a href="{{ route('home') }}" class="text-muted text-decoration-none">@lang('Home')</a>
            <span class="mx-2 text-muted">/</span>
            <a href="{{ route('products') }}" class="text-muted text-decoration-none">@lang('Products & Courses')</a>
            @if($product->category)
                <span class="mx-2 text-muted">/</span>
                <span class="text-warning">{{ __($product->category->name) }}</span>
            @endif
        </div>

        <!-- Product Title & Verified Badges -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge px-3 py-1 rounded-pill" style="background: rgba(251, 175, 67, 0.15); color: #FBAF43; font-weight: 700; font-size: 11px; letter-spacing: 0.5px;">
                        <i class="las la-certificate me-1"></i> {{ strtoupper($stageLabel) }}
                    </span>
                    <span class="badge px-3 py-1 rounded-pill text-white" style="background: #1B1B26; border: 1px solid rgba(255,255,255,0.08); font-size: 11px;">
                        SKU: {{ $skuCode }}
                    </span>
                </div>
                <h1 class="text-white fw-bold mb-1" style="font-size: clamp(24px, 3.5vw, 36px); letter-spacing: -0.5px;">
                    {{ __($product->title) }}
                </h1>
                <div class="d-flex align-items-center flex-wrap gap-3 mt-2 text-muted" style="font-size: 13px;">
                    <div class="d-flex align-items-center text-warning">
                        <i class="las la-star"></i>
                        <i class="las la-star"></i>
                        <i class="las la-star"></i>
                        <i class="las la-star"></i>
                        <i class="las la-star"></i>
                        <span class="ms-1 fw-bold text-white">{{ $rating }}</span>
                        <span class="ms-1 text-muted">({{ $reviewCount }} @lang('reviews'))</span>
                    </div>
                    <span>&bull;</span>
                    <span><i class="las la-download text-success me-1"></i>{{ $salesCount }} @lang('orders placed')</span>
                    <span>&bull;</span>
                    <span class="text-success"><i class="las la-check-circle me-1"></i>@lang('Verified Studio License')</span>
                </div>
            </div>

            <!-- Share Buttons -->
            <div class="d-flex align-items-center gap-2">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm ak23-btn-icon" title="@lang('Share on Facebook')">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($product->title) }}" target="_blank" class="btn btn-sm ak23-btn-icon" title="@lang('Share on X')">
                    <i class="fab fa-x-twitter"></i>
                </a>
                <button type="button" class="btn btn-sm ak23-btn-icon copyLinkBtn" data-url="{{ url()->current() }}" title="@lang('Copy Product Link')">
                    <i class="las la-link"></i>
                </button>
            </div>
        </div>

        <div class="row g-4 align-items-start">
            <!-- =========================================================================
                 LEFT COLUMN: VIDEO DEMO TRAILER & AUDIO WAVES PREVIEW
                 ========================================================================= -->
            <div class="col-lg-8">
                <div class="ak23-player-card mb-4">
                    <!-- Media Switcher Tabs -->
                    <div class="ak23-player-tabs d-flex align-items-center justify-content-between p-3 border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
                        <ul class="nav nav-pills gap-2" id="mediaPreviewTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link ak23-nav-pill active" id="tab-video" data-bs-toggle="pill" data-bs-target="#preview-video-pane" type="button" role="tab">
                                    <i class="las la-play-circle me-1 fs-16"></i> @lang('Video Demo Player (Trailer)')
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link ak23-nav-pill" id="tab-audio" data-bs-toggle="pill" data-bs-target="#preview-audio-pane" type="button" role="tab">
                                    <i class="las la-wave-square me-1 fs-16"></i> @lang('Audio Waves Preview')
                                </button>
                            </li>
                        </ul>

                        <span class="ak23-media-badge d-none d-md-inline-flex align-items-center gap-1">
                            <span class="ak23-live-dot"></span> HD DEMO PREVIEW
                        </span>
                    </div>

                    <div class="tab-content" id="mediaPreviewTabContent">
                        <!-- PANE 1: VIDEO DEMO TRAILER -->
                        <div class="tab-pane fade show active" id="preview-video-pane" role="tabpanel">
                            <div class="ak23-video-wrapper position-relative">
                                @if(!empty($videoSrc))
                                    <video id="ak23-main-video-player" playsinline controls poster="{{ $posterImg }}" class="w-100" style="max-height: 480px; object-fit: contain; background: #000;">
                                        <source src="{{ $videoSrc }}" type="video/mp4">
                                        @lang('Your browser does not support the video tag.')
                                    </video>
                                @else
                                    <!-- Fallback Cinematic Showcase Trailer Screen -->
                                    <div class="ak23-video-placeholder position-relative overflow-hidden" style="height: 420px; background: #0b0c10;">
                                        <img src="{{ $posterImg }}" alt="{{ $product->title }}" class="w-100 h-100" style="object-fit: cover; opacity: 0.65; filter: brightness(0.7);">
                                        
                                        <!-- Overlay Action -->
                                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center text-center p-4">
                                            <div class="ak23-play-pulse-btn mb-3" id="btnPlayDemoPrompt">
                                                <i class="las la-play fs-28"></i>
                                            </div>
                                            <h4 class="text-white fw-bold mb-1">{{ __($product->title) }}</h4>
                                            <p class="text-warning mb-3 fs-13">@lang('Official Product Demonstration & Trailer Available')</p>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge px-3 py-1 bg-dark text-white border border-secondary rounded-pill fs-12">
                                                    <i class="las la-clock me-1"></i> HD Video Guide
                                                </span>
                                                <span class="badge px-3 py-1 bg-dark text-white border border-secondary rounded-pill fs-12">
                                                    <i class="las la-compact-disc me-1"></i> Studio Stems Included
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- PANE 2: AUDIO WAVES PREVIEW (WAVESURFER) -->
                        <div class="tab-pane fade" id="preview-audio-pane" role="tabpanel">
                            <div class="p-4 p-md-5" style="background: #0E0E15;">
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div>
                                        <h5 class="text-white fw-bold mb-1">
                                            <i class="las la-headphones text-warning me-2"></i>@lang('Studio Stem & Audio Waveform Preview')
                                        </h5>
                                        <p class="text-muted fs-13 mb-0">
                                            @lang('High-fidelity 48kHz audio preview. Listen to the unprocessed stems and final processing.')
                                        </p>
                                    </div>
                                    <span class="badge bg-dark border border-secondary text-warning px-3 py-2 rounded-pill fs-12">
                                        48kHz &middot; 24-Bit
                                    </span>
                                </div>

                                <!-- Audio Player Box -->
                                <div class="ak23-waveform-card p-4 rounded-4" style="background: #15151F; border: 1px solid rgba(255,255,255,0.08);">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <button type="button" class="btn ak23-btn-play-circle" id="ak23AudioPlayBtn">
                                            <i class="las la-play fs-22" id="ak23AudioPlayIcon"></i>
                                        </button>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between text-muted fs-12 mb-1">
                                                <span id="ak23AudioCurTime" class="text-white fw-semibold">00:00</span>
                                                <span id="ak23AudioTotTime">02:45</span>
                                            </div>
                                            <!-- WaveSurfer Canvas Mount -->
                                            <div id="ak23-product-waveform" data-audio-url="{{ $audioSrc }}" style="min-height: 60px;"></div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between text-muted fs-12 pt-2 border-top" style="border-color: rgba(255,255,255,0.05) !important;">
                                        <span><i class="las la-sliders-h text-warning me-1"></i> Interactive Waveform &amp; Seekbar</span>
                                        <span>Click waveform anywhere to scrub</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Description & In-Depth Details -->
                <div class="ak23-details-content-card p-4 p-md-5 rounded-4 mb-4" style="background: #13131A; border: 1px solid rgba(255,255,255,0.07);">
                    <h3 class="text-white fw-bold mb-3 fs-20 border-bottom pb-3" style="border-color: rgba(255,255,255,0.08) !important;">
                        <i class="las la-info-circle text-warning me-2"></i>@lang('Product Overview & Details')
                    </h3>

                    <div class="ak23-prose text-light" style="line-height: 1.8; font-size: 15px;">
                        @php echo html_entity_decode($product->description); @endphp
                    </div>

                    <!-- What's in the Box Grid -->
                    <div class="mt-4 pt-4 border-top" style="border-color: rgba(255,255,255,0.08) !important;">
                        <h4 class="text-white fw-bold mb-3 fs-16">
                            <i class="las la-box-open text-warning me-2"></i>@lang('What Is Included In This Package:')
                        </h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background: #1A1A24; border: 1px solid rgba(255,255,255,0.06);">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="las la-video text-warning fs-28"></i>
                                        <div>
                                            <div class="text-white fw-bold fs-14">Full HD Video Guide</div>
                                            <div class="text-muted fs-12">Step-by-step masterclass demonstrations</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background: #1A1A24; border: 1px solid rgba(255,255,255,0.06);">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="las la-key text-success fs-28"></i>
                                        <div>
                                            <div class="text-white fw-bold fs-14">Software License Included</div>
                                            <div class="text-muted fs-12">Official perpetual license activation key</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background: #1A1A24; border: 1px solid rgba(255,255,255,0.06);">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="las la-file-audio text-info fs-28"></i>
                                        <div>
                                            <div class="text-white fw-bold fs-14">Studio Audio Stems</div>
                                            <div class="text-muted fs-12">Raw multitracks &amp; processed stems (.WAV)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background: #1A1A24; border: 1px solid rgba(255,255,255,0.06);">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="las la-sliders-h text-primary fs-28"></i>
                                        <div>
                                            <div class="text-white fw-bold fs-14">Mixing Presets &amp; Chains</div>
                                            <div class="text-muted fs-12">Plug-and-play presets for all major DAWs</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- =========================================================================
                 RIGHT COLUMN: ORDER PLACEMENT ACTION & SPECIFICATIONS
                 ========================================================================= -->
            <div class="col-lg-4">
                <div class="ak23-sidebar-order-box p-4 rounded-4 sticky-top" style="top: 100px; background: #13131A; border: 1.5px solid rgba(251, 175, 67, 0.35); box-shadow: 0 10px 40px rgba(0,0,0,0.4);">
                    <!-- Price Tag Header -->
                    <div class="d-flex align-items-baseline justify-content-between mb-3 pb-3 border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
                        <div>
                            <span class="text-muted fs-12 d-block text-uppercase fw-bold letter-spacing-1">@lang('Direct Price')</span>
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="text-warning fw-extrabold" style="font-size: 32px; letter-spacing: -0.5px;">
                                    TZS {{ $priceFormatted }}
                                </span>
                            </div>
                        </div>
                        <span class="badge px-3 py-2 rounded-pill bg-success-subtle text-success border border-success-subtle fs-12 fw-bold">
                            <i class="las la-bolt me-1"></i> Instant Delivery
                        </span>
                    </div>

                    <!-- Direct Order Placement Button (Order Flow Step 2) -->
                    <div class="mb-3">
                        <a href="{{ route('order.checkout', ['sku' => $skuCode]) }}" class="btn ak23-btn-order-now w-100 py-3 text-center rounded-3 fw-bold fs-16 d-flex align-items-center justify-content-center gap-2">
                            <i class="las la-shopping-bag fs-20"></i>
                            <span>@lang('Order Now & Instant Delivery')</span>
                        </a>
                        <small class="text-muted d-block text-center mt-2 fs-12">
                            <i class="las la-lock text-warning me-1"></i> @lang('Direct Checkout with Mobile Money & Cards')
                        </small>
                    </div>

                    <!-- Secondary Action: Add to Cart (Quick Add AJAX) -->
                    <div class="mb-4">
                        <button type="button" class="btn ak23-btn-add-cart w-100 py-2 rounded-3 text-white fw-semibold fs-14 d-flex align-items-center justify-content-center gap-2" data-id="{{ $product->id }}" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);">
                            <i class="las la-cart-plus fs-18"></i>
                            <span>@lang('Add to Cart')</span>
                        </button>
                    </div>

                    <!-- Product Specifications List -->
                    <div class="ak23-specs-list border-top pt-3" style="border-color: rgba(255,255,255,0.08) !important;">
                        <h6 class="text-white fw-bold mb-3 fs-13 text-uppercase letter-spacing-1">
                            @lang('Product Specifications')
                        </h6>

                        <div class="d-flex justify-content-between py-2 border-bottom fs-13 text-muted" style="border-color: rgba(255,255,255,0.05) !important;">
                            <span>@lang('Software:')</span>
                            <strong class="text-white">{{ $softwareName }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom fs-13 text-muted" style="border-color: rgba(255,255,255,0.05) !important;">
                            <span>@lang('License Model:')</span>
                            <strong class="text-success">@lang('One-Time Lifetime (No Renewal)')</strong>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom fs-13 text-muted" style="border-color: rgba(255,255,255,0.05) !important;">
                            <span>@lang('Certification:')</span>
                            <strong class="text-warning">@lang('Auto-Generated Course Purchase Certificate')</strong>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom fs-13 text-muted" style="border-color: rgba(255,255,255,0.05) !important;">
                            <span>@lang('Format:')</span>
                            <strong class="text-white">VST3, AU, AAX (64-Bit)</strong>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom fs-13 text-muted" style="border-color: rgba(255,255,255,0.05) !important;">
                            <span>@lang('Compatibility:')</span>
                            <strong class="text-white">Windows &amp; macOS</strong>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom fs-13 text-muted" style="border-color: rgba(255,255,255,0.05) !important;">
                            <span>@lang('Delivery:')</span>
                            <strong class="text-warning">ZIP + Key &amp; Purchase Cert</strong>
                        </div>
                    </div>

                    <!-- Trust Box -->
                    <div class="mt-4 p-3 rounded-3" style="background: rgba(34, 197, 94, 0.08); border: 1px solid rgba(34, 197, 94, 0.2);">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="las la-shield-alt text-success fs-18"></i>
                            <span class="text-white fw-bold fs-13">100% Virus-Free &amp; DAW Certified</span>
                        </div>
                        <p class="mb-0 text-muted fs-12">
                            Clean installer packages verified against all DAW environments. 24/7 technical activation support.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('style')
<style>
/* Marketplace Single Product Styling */
.ak23-marketplace-product {
    background-color: #09090D;
    min-height: 85vh;
}

.ak23-prod-breadcrumb {
    font-size: 13px;
}

.ak23-btn-icon {
    width: 36px;
    height: 36px;
    background: #171722;
    border: 1px solid rgba(255,255,255,0.1);
    color: #9E9EB2;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.ak23-btn-icon:hover {
    background: #FBAF43;
    color: #121316;
    border-color: #FBAF43;
}

.ak23-player-card {
    background: #13131A;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 16px;
    overflow: hidden;
}

.ak23-nav-pill {
    background: transparent !important;
    color: #9E9EB2 !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
    border-radius: 30px !important;
    padding: 8px 18px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    transition: all 0.2s ease !important;
}

.ak23-nav-pill.active,
.ak23-nav-pill:hover {
    background: #FBAF43 !important;
    color: #09090D !important;
    border-color: #FBAF43 !important;
}

.ak23-media-badge {
    font-size: 11px;
    font-weight: 800;
    color: #FBAF43;
    letter-spacing: 0.8px;
}

.ak23-live-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22C55E;
    box-shadow: 0 0 8px #22C55E;
    display: inline-block;
}

.ak23-play-pulse-btn {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    background: #FBAF43;
    color: #09090D;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 0 0 rgba(251, 175, 67, 0.6);
    animation: ak23Pulse 2s infinite;
    cursor: pointer;
    transition: transform 0.2s;
}

.ak23-play-pulse-btn:hover {
    transform: scale(1.1);
}

@keyframes ak23Pulse {
    0% { box-shadow: 0 0 0 0 rgba(251, 175, 67, 0.6); }
    70% { box-shadow: 0 0 0 20px rgba(251, 175, 67, 0); }
    100% { box-shadow: 0 0 0 0 rgba(251, 175, 67, 0); }
}

.ak23-btn-play-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #FBAF43;
    color: #09090D;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.2s;
}

.ak23-btn-play-circle:hover {
    background: #FFBE5C;
    transform: scale(1.05);
}

.ak23-btn-order-now {
    background: linear-gradient(135deg, #FBAF43 0%, #E29828 100%);
    color: #09090D !important;
    box-shadow: 0 6px 20px rgba(251, 175, 67, 0.35);
    border: none;
    transition: all 0.25s ease;
}

.ak23-btn-order-now:hover {
    background: linear-gradient(135deg, #FFBE5C 0%, #FBAF43 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(251, 175, 67, 0.5);
}

.ak23-btn-add-cart:hover {
    background: rgba(255,255,255,0.12) !important;
    border-color: rgba(255,255,255,0.25) !important;
}
</style>
@endpush

@push('script-lib')
<script src="{{ asset('assets/global/js/wavesurfer.min.js') }}"></script>
@endpush

@push('script')
<script>
(function($) {
    "use strict";

    // Copy link helper
    $('.copyLinkBtn').on('click', function() {
        const url = $(this).data('url');
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(() => {
                notify('success', 'Product link copied to clipboard!');
            });
        }
    });

    // Audio Waves WaveSurfer Initialization
    let wavesurfer = null;
    let isAudioPlaying = false;
    const waveEl = document.getElementById('ak23-product-waveform');

    function initWaveSurfer() {
        if (!waveEl || wavesurfer) return;

        try {
            wavesurfer = WaveSurfer.create({
                container: '#ak23-product-waveform',
                waveColor: 'rgba(255, 255, 255, 0.25)',
                progressColor: '#FBAF43',
                cursorColor: '#FFBE5C',
                barWidth: 3,
                barRadius: 2,
                cursorWidth: 2,
                height: 60,
                responsive: true,
                backend: 'MediaElement'
            });

            const audioUrl = $(waveEl).data('audio-url');
            if (audioUrl && audioUrl.length > 5) {
                wavesurfer.load(audioUrl);
            } else {
                // Generate simulated waveform peaks for demo purposes
                wavesurfer.load('data:audio/wav;base64,UklGRiQAAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQAAAAA=');
            }

            wavesurfer.on('ready', function() {
                const dur = wavesurfer.getDuration();
                $('#ak23AudioTotTime').text(formatTime(dur || 165));
            });

            wavesurfer.on('audioprocess', function() {
                const cur = wavesurfer.getCurrentTime();
                $('#ak23AudioCurTime').text(formatTime(cur));
            });

            wavesurfer.on('finish', function() {
                isAudioPlaying = false;
                $('#ak23AudioPlayIcon').removeClass('la-pause').addClass('la-play');
            });
        } catch (e) {
            console.log('WaveSurfer initialization note:', e);
        }
    }

    function formatTime(sec) {
        const m = Math.floor(sec / 60);
        const s = Math.floor(sec % 60);
        return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    }

    // Audio Play/Pause Button
    $('#ak23AudioPlayBtn').on('click', function() {
        if (!wavesurfer) {
            initWaveSurfer();
        }

        if (wavesurfer) {
            if (isAudioPlaying) {
                wavesurfer.pause();
                isAudioPlaying = false;
                $('#ak23AudioPlayIcon').removeClass('la-pause').addClass('la-play');
            } else {
                wavesurfer.play();
                isAudioPlaying = true;
                $('#ak23AudioPlayIcon').removeClass('la-play').addClass('la-pause');
            }
        }
    });

    // Init WaveSurfer when Audio tab is activated
    $('#tab-audio').on('shown.bs.tab', function() {
        initWaveSurfer();
    });

    // Play demo prompt click
    $('#btnPlayDemoPrompt').on('click', function() {
        // Switch to audio tab or prompt user
        const audioTab = new bootstrap.Tab(document.getElementById('tab-audio'));
        audioTab.show();
        setTimeout(function() {
            $('#ak23AudioPlayBtn').trigger('click');
        }, 300);
    });

    // Quick-Add to Cart with CSRF protection and deduplication
    $(document).off('click', '.ak23-btn-add-cart').on('click', '.ak23-btn-add-cart', function(e) {
        e.preventDefault();
        const btn = $(this);
        const productId = btn.data('id');
        const originalText = btn.html();

        btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i> Adding...');

        const token = $('meta[name="csrf-token"]').attr('content') || "{{ csrf_token() }}";

        $.ajax({
            url: "{{ route('cart.quick_add') }}",
            type: "POST",
            data: {
                product_id: productId,
                _token: token
            },
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(resp) {
                btn.prop('disabled', false).html('<i class="las la-check text-success"></i> Added!');
                setTimeout(() => { btn.html(originalText); }, 2000);

                if (resp && resp.cart_count !== undefined) {
                    if (typeof window.updateAk23CartCount === 'function') {
                        window.updateAk23CartCount(resp.cart_count);
                    } else {
                        $('.ak23-cart-count').text(resp.cart_count);
                    }
                }
                notify(resp.status || 'success', resp.message || 'Added to cart!');
            },
            error: function(xhr) {
                btn.prop('disabled', false).html(originalText);
                let err = 'Could not add item to cart.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    err = xhr.responseJSON.message;
                }
                notify('error', err);
            }
        });
    });

})(jQuery);
</script>
@endpush
