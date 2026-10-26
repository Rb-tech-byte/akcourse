@extends($activeTemplate . 'layouts.frontend')

@section('content')

@php
    $isFree = $product->is_free || ((float)$product->price <= 0);
    $productPrice = (float)($product->price ?: ($isFree ? 0 : 140000));
    $priceFormatted = number_format($productPrice);
    $skuCode = $product->sku ?: ($product->slug ?: 'AK23-PROD-' . $product->id);
    $softwareName = $product->software_name ?: 'Complete Beat Making Suite';
    $rawEdition = $product->software_edition;
    $softwareEdition = (!$rawEdition || str_contains($rawEdition, '[Confirm')) ? 'All Production Tools & Drum Kits Included' : $rawEdition;
    $stageLabel = $product->stage_label ?: 'Complete Series';
    $rating = number_format((float)($product->avg_rating ?: 4.9), 1);
    $reviewCount = $product->total_review ?: 24;
    $salesCount = $product->total_sold ?: 142;
    $embedInfo = $product->video_embed_info;
    $hasVideo = !empty($product->preview_video) || !empty($product->demo_url);
    $audioSrc = !empty($product->audio_temp_file) ? asset(getFilePath('previewFile') . '/' . productFilePath($product, 'temp_audio_file') . '/' . $product->audio_temp_file) : ((@$embedInfo->type === 'audio') ? $embedInfo->raw_url : '');
    $hasAudio = !empty($audioSrc) || (@$embedInfo->type === 'audio') || (@$embedInfo->type === 'soundcloud');
    $videoSrc = !empty($product->preview_video) ? getImage(getFilePath('previewVideo') . '/' . productFilePath($product, 'preview_video')) : ($product->demo_url ?: '');
    $posterImg = $product->thumbnail_url ?: getImage(getFilePath('productPreview') . '/' . productFilePath($product, 'preview_image'), getFileSize('productPreview'));
    $seriesModules = $seriesCourses ?? collect();
    $totalStandaloneValue = $seriesModules->sum(function($item) { return (float)($item->price ?: 135000); }) ?: 870000;
@endphp

<div class="ak23-marketplace-product py-3 py-lg-4">
    <div class="container" style="max-width: 1220px;">

        <!-- =========================================================================
             BREADCRUMB & METADATA BAR
             ========================================================================= -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <nav class="ak23-prod-breadcrumb" aria-label="breadcrumb">
                <a href="{{ route('home') }}" class="text-muted text-decoration-none">@lang('Home')</a>
                <span class="mx-2 text-muted">/</span>
                <a href="{{ route('products') }}" class="text-muted text-decoration-none">@lang('Courses')</a>
                @if($product->category)
                    <span class="mx-2 text-muted">/</span>
                    <a href="{{ route('products', ['search' => $product->category->name]) }}" class="text-warning text-decoration-none">{{ __($product->category->name) }}</a>
                @endif
                <span class="mx-2 text-muted">/</span>
                <span class="text-white-50">{{ strLimit(__($product->title), 32) }}</span>
            </nav>

            <!-- Share Buttons -->
            <div class="d-flex align-items-center gap-2">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm ak23-btn-icon" title="@lang('Share on Facebook')">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($product->title) }}" target="_blank" class="btn btn-sm ak23-btn-icon" title="@lang('Share on X')">
                    <i class="fab fa-x-twitter"></i>
                </a>
                <button type="button" class="btn btn-sm ak23-btn-icon copyLinkBtn" data-url="{{ url()->current() }}" title="@lang('Copy Link')">
                    <i class="las la-link"></i>
                </button>
            </div>
        </div>

        <!-- Product Title & Verified Badges Header -->
        <div class="ak23-product-header mb-4 p-3 p-md-4 rounded-3" style="background: #111118; border: 1px solid rgba(255,255,255,0.06);">
            <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                <span class="badge px-3 py-1 rounded-pill" style="background: rgba(251, 175, 67, 0.15); color: #FBAF43; font-weight: 700; font-size: 11px; letter-spacing: 0.5px;">
                    <i class="las la-layer-group me-1"></i> {{ strtoupper($stageLabel) }}
                </span>
                <span class="badge px-3 py-1 rounded-pill text-white-50" style="background: #1B1B26; border: 1px solid rgba(255,255,255,0.08); font-size: 11px;">
                    SKU: {{ $skuCode }}
                </span>
                @if($isFree)
                    <span class="badge px-3 py-1 rounded-pill" style="background: rgba(34, 197, 94, 0.15); color: #22C55E; font-weight: 700; font-size: 11px; border: 1px solid rgba(34, 197, 94, 0.3);">
                        <i class="las la-gift me-1"></i> 100% FREE MASTERCLASS BUNDLE
                    </span>
                @endif
            </div>

            <h1 class="text-white fw-bold mb-2" style="font-size: clamp(22px, 3vw, 32px); letter-spacing: -0.5px; line-height: 1.25;">
                {{ __($product->title) }}
            </h1>

            <div class="d-flex align-items-center flex-wrap gap-3 text-muted" style="font-size: 13px;">
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
                <span><i class="las la-user-graduate text-info me-1"></i>{{ $salesCount }} @lang('enrolled creators')</span>
                <span>&bull;</span>
                @if($seriesModules->isNotEmpty())
                    <span class="text-warning"><i class="las la-book-open me-1"></i>{{ $seriesModules->count() }} @lang('Comprehensive Modules')</span>
                    <span>&bull;</span>
                @endif
                <span class="text-success"><i class="las la-check-circle me-1"></i>@lang('Studio Certified Masterclass')</span>
            </div>
        </div>

        <div class="row g-4 align-items-start">
            <!-- =========================================================================
                 LEFT COLUMN: VIDEO PLAYER, DETAILS & CURRICULUM
                 ========================================================================= -->
            <div class="col-lg-8">

                <!-- Native Compact Video/Media Showcase Card -->
                <div class="ak23-player-card mb-4 rounded-3 overflow-hidden" style="background: #111118; border: 1px solid rgba(255,255,255,0.08);">
                    @if($hasAudio)
                        <!-- Switcher Tabs when Audio Stems are available -->
                        <div class="ak23-player-tabs d-flex align-items-center justify-content-between p-3 border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
                            <ul class="nav nav-pills gap-2" id="mediaPreviewTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link ak23-nav-pill active" id="tab-video" data-bs-toggle="pill" data-bs-target="#preview-video-pane" type="button" role="tab">
                                        <i class="las la-play-circle me-1 fs-16"></i> @lang('Video Trailer')
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
                    @else
                        <!-- Compact Header for Video Trailer -->
                        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom" style="border-color: rgba(255,255,255,0.08) !important; background: #151520;">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge rounded-pill" style="background: rgba(251, 175, 67, 0.18); color: #FBAF43; font-weight: 700; font-size: 11px;">
                                    <i class="las la-play-circle me-1"></i> @lang('OFFICIAL TRAILER')
                                </span>
                                <span class="text-white-50 fs-12 d-none d-sm-inline">@lang('Masterclass Series Demonstration')</span>
                            </div>
                            <span class="text-success fs-12 fw-semibold d-flex align-items-center gap-1">
                                <span class="ak23-live-dot"></span> HD 1080p
                            </span>
                        </div>
                    @endif

                    <div class="tab-content" id="mediaPreviewTabContent">
                        <!-- PANE 1: VIDEO DEMO TRAILER -->
                        <div class="tab-pane fade show active" id="preview-video-pane" role="tabpanel">
                            <div class="ak23-video-wrapper position-relative overflow-hidden" style="background: #000;">
                                @if($embedInfo && $embedInfo->type === 'soundcloud')
                                    <!-- SoundCloud Embedded Player -->
                                    <div class="p-4 text-center" style="background: #0d0e15;">
                                        <div class="mb-2">
                                            <i class="las la-headphones text-warning" style="font-size: 40px;"></i>
                                        </div>
                                        <h6 class="text-white fw-bold mb-3">{{ __($product->title) }}</h6>
                                        <div class="mx-auto" style="max-width: 650px;">
                                            <iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay" src="{{ $embedInfo->embed_url }}"></iframe>
                                        </div>
                                    </div>
                                @elseif($embedInfo && $embedInfo->is_embed)
                                    <!-- Embedded Player (YouTube / Vimeo / Loom / Streamable) - Native 16:9 with sleek max height -->
                                    <div class="ratio ratio-16x9" style="max-height: 400px;">
                                        <iframe src="{{ $embedInfo->embed_url }}" 
                                                title="{{ $product->title }} Preview" 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                                allowfullscreen 
                                                style="width: 100%; height: 100%; border: 0;">
                                        </iframe>
                                    </div>
                                @elseif($embedInfo && $embedInfo->type === 'audio')
                                    <!-- Audio Walkthrough Player -->
                                    <div class="p-4 text-center" style="background: #0d0e15;">
                                        <i class="las la-compact-disc text-warning fs-36 mb-2"></i>
                                        <h6 class="text-white fw-bold mb-1">{{ __($product->title) }}</h6>
                                        <p class="text-warning mb-3 fs-12">@lang('Audio Walkthrough & Stem Preview')</p>
                                        <div class="d-flex justify-content-center">
                                            <audio controls class="w-100" style="max-width: 480px;">
                                                <source src="{{ $embedInfo->embed_url }}">
                                                @lang('Your browser does not support the audio element.')
                                            </audio>
                                        </div>
                                    </div>
                                @elseif($embedInfo && $embedInfo->type === 'video')
                                    <!-- Direct HTML5 Video (.mp4 / webm) -->
                                    <video id="ak23-main-video-player" playsinline controls poster="{{ $posterImg }}" class="w-100" style="max-height: 400px; object-fit: contain; background: #000;">
                                        <source src="{{ $embedInfo->embed_url }}" type="video/mp4">
                                        @lang('Your browser does not support the video tag.')
                                    </video>
                                @elseif(!empty($videoSrc))
                                    <!-- Direct HTML5 Video Fallback -->
                                    <video id="ak23-main-video-player" playsinline controls poster="{{ $posterImg }}" class="w-100" style="max-height: 400px; object-fit: contain; background: #000;">
                                        <source src="{{ $videoSrc }}" type="video/mp4">
                                        @lang('Your browser does not support the video tag.')
                                    </video>
                                @else
                                    <!-- Fallback Cinematic Showcase Screen -->
                                    <div class="ak23-video-placeholder position-relative overflow-hidden" style="height: 360px; background: #0b0c10;">
                                        <img src="{{ $posterImg }}" alt="{{ $product->title }}" class="w-100 h-100" style="object-fit: cover; opacity: 0.65; filter: brightness(0.7);">
                                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center text-center p-3">
                                            <div class="ak23-play-pulse-btn mb-2" id="btnPlayDemoPrompt">
                                                <i class="las la-play fs-24"></i>
                                            </div>
                                            <h5 class="text-white fw-bold mb-1">{{ __($product->title) }}</h5>
                                            <p class="text-warning mb-2 fs-12">@lang('Full Masterclass Package Available')</p>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge px-3 py-1 bg-dark text-white border border-secondary rounded-pill fs-11">
                                                    <i class="las la-clock me-1"></i> HD Video Guide
                                                </span>
                                                <span class="badge px-3 py-1 bg-dark text-white border border-secondary rounded-pill fs-11">
                                                    <i class="las la-compact-disc me-1"></i> Studio Stems Included
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- PANE 2: AUDIO WAVES PREVIEW (WAVESURFER) -->
                        @if($hasAudio)
                        <div class="tab-pane fade" id="preview-audio-pane" role="tabpanel">
                            <div class="p-3 p-md-4" style="background: #0E0E15;">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <h6 class="text-white fw-bold mb-1">
                                            <i class="las la-headphones text-warning me-2"></i>@lang('Studio Stem & Audio Waveform Preview')
                                        </h6>
                                        <p class="text-muted fs-12 mb-0">
                                            @lang('High-fidelity 48kHz preview. Listen to raw sound design and final master.')
                                        </p>
                                    </div>
                                    <span class="badge bg-dark border border-secondary text-warning px-2 py-1 rounded-pill fs-11">
                                        48kHz &middot; 24-Bit
                                    </span>
                                </div>

                                <div class="ak23-waveform-card p-3 rounded-3" style="background: #15151F; border: 1px solid rgba(255,255,255,0.08);">
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <button type="button" class="btn ak23-btn-play-circle" id="ak23AudioPlayBtn">
                                            <i class="las la-play fs-20" id="ak23AudioPlayIcon"></i>
                                        </button>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between text-muted fs-11 mb-1">
                                                <span id="ak23AudioCurTime" class="text-white fw-semibold">00:00</span>
                                                <span id="ak23AudioTotTime">02:45</span>
                                            </div>
                                            <div id="ak23-product-waveform" data-audio-url="{{ $audioSrc }}" style="min-height: 50px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Product Description & Overview -->
                <div class="ak23-details-content-card p-3 p-md-4 rounded-3 mb-4" style="background: #111118; border: 1px solid rgba(255,255,255,0.06);">
                    <h3 class="text-white fw-bold mb-3 fs-18 border-bottom pb-2 d-flex align-items-center" style="border-color: rgba(255,255,255,0.08) !important;">
                        <i class="las la-info-circle text-warning me-2"></i>@lang('About This Masterclass Series')
                    </h3>

                    <div class="ak23-prose text-light mb-3" style="line-height: 1.7; font-size: 14px;">
                        @php echo html_entity_decode($product->description); @endphp
                    </div>

                    <!-- Compact What's Included 4-Feature Grid -->
                    <div class="mt-3 pt-3 border-top" style="border-color: rgba(255,255,255,0.08) !important;">
                        <h4 class="text-white fw-bold mb-3 fs-15">
                            <i class="las la-box-open text-warning me-2"></i>@lang('What Is Included In This Series:')
                        </h4>
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <div class="p-2 px-3 rounded-2 d-flex align-items-center gap-3" style="background: #171722; border: 1px solid rgba(255,255,255,0.05);">
                                    <div class="ak23-feature-icon-box text-warning"><i class="las la-video fs-20"></i></div>
                                    <div>
                                        <div class="text-white fw-bold fs-13">6 HD Video Masterclasses</div>
                                        <div class="text-muted fs-11">Comprehensive step-by-step studio workflow</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-2 px-3 rounded-2 d-flex align-items-center gap-3" style="background: #171722; border: 1px solid rgba(255,255,255,0.05);">
                                    <div class="ak23-feature-icon-box text-success"><i class="las la-key fs-20"></i></div>
                                    <div>
                                        <div class="text-white fw-bold fs-13">Production Software &amp; Plugins</div>
                                        <div class="text-muted fs-11">Official tools &amp; perpetual studio licenses</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-2 px-3 rounded-2 d-flex align-items-center gap-3" style="background: #171722; border: 1px solid rgba(255,255,255,0.05);">
                                    <div class="ak23-feature-icon-box text-info"><i class="las la-file-audio fs-20"></i></div>
                                    <div>
                                        <div class="text-white fw-bold fs-13">Studio Drum Kits &amp; Stems</div>
                                        <div class="text-muted fs-11">808s, one-shots, chords &amp; raw stems (.WAV)</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-2 px-3 rounded-2 d-flex align-items-center gap-3" style="background: #171722; border: 1px solid rgba(255,255,255,0.05);">
                                    <div class="ak23-feature-icon-box text-primary"><i class="las la-sliders-h fs-20"></i></div>
                                    <div>
                                        <div class="text-white fw-bold fs-13">Mixing Presets &amp; DAW Templates</div>
                                        <div class="text-muted fs-11">Plug-and-play channel strips &amp; beat mix chains</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- =========================================================================
                     SERIES CURRICULUM BREAKDOWN (THE 6 INCLUDED MODULES)
                     ========================================================================= -->
                @if($seriesModules->isNotEmpty())
                    <div class="ak23-curriculum-card p-3 p-md-4 rounded-3 mb-4" style="background: #111118; border: 1px solid rgba(255,255,255,0.06);">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 pb-2 border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
                            <div>
                                <h3 class="text-white fw-bold fs-17 mb-1 d-flex align-items-center">
                                    <i class="las la-list-ol text-warning me-2"></i>@lang('Course Curriculum & Included Modules')
                                </h3>
                                <p class="text-muted fs-12 mb-0">@lang('All 6 sequential learning stages are included in this complete package.')</p>
                            </div>
                            <span class="badge px-3 py-1 rounded-pill text-warning" style="background: rgba(251,175,67,0.12); border: 1px solid rgba(251,175,67,0.3); font-size: 11px;">
                                <i class="las la-check-double me-1"></i> {{ $seriesModules->count() }} @lang('Modules Included')
                            </span>
                        </div>

                        <div class="ak23-module-list d-flex flex-column gap-2">
                            @foreach($seriesModules as $idx => $mod)
                                <div class="ak23-module-item p-2 px-3 rounded-2 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: #161622; border: 1px solid rgba(255,255,255,0.05); transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="ak23-module-num rounded-circle d-flex align-items-center justify-content-center text-warning fw-bold fs-12" style="width: 28px; height: 28px; background: rgba(251,175,67,0.15); border: 1px solid rgba(251,175,67,0.3); flex-shrink: 0;">
                                            {{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('product.details', $mod->slug) }}" class="text-white fw-semibold text-decoration-none fs-13 d-block hover-gold">
                                                {{ __($mod->title) }}
                                            </a>
                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                <span class="badge rounded-pill text-white-50" style="background: #20202F; font-size: 10px;">
                                                    {{ $mod->stage_label ?: 'Stage ' . ($idx + 1) }}
                                                </span>
                                                <span class="text-muted fs-11">&bull; Standalone Value: TZS {{ number_format((float)($mod->price ?: 135000)) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fs-11 fw-semibold px-2 py-1">
                                            <i class="las la-check me-1"></i>@lang('Included in Series')
                                        </span>
                                        <a href="{{ route('product.details', $mod->slug) }}" class="btn btn-sm btn-outline-secondary py-1 px-2 text-white-50 fs-11" title="@lang('View Module Details')">
                                            <i class="las la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- =========================================================================
                 RIGHT COLUMN: NATIVE COMPACT ORDER / ENROLL CARD & SPECIFICATIONS
                 ========================================================================= -->
            <div class="col-lg-4">
                <div class="ak23-sidebar-order-box p-3 p-md-4 rounded-3 sticky-top" style="top: 85px; background: #111118; border: 1.5px solid rgba(251, 175, 67, 0.35); box-shadow: 0 10px 35px rgba(0,0,0,0.45);">
                    
                    <!-- Pricing Header -->
                    <div class="d-flex align-items-baseline justify-content-between mb-3 pb-3 border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
                        <div>
                            <span class="text-muted fs-11 d-block text-uppercase fw-bold letter-spacing-1">
                                {{ $isFree ? __('Series Access') : __('Direct Price') }}
                            </span>
                            <div class="d-flex align-items-baseline gap-2 mt-1">
                                @if($isFree)
                                    <span class="text-success fw-extrabold" style="font-size: 28px; letter-spacing: -0.5px;">
                                        FREE
                                    </span>
                                    <span class="text-muted text-decoration-line-through fs-13">
                                        TZS {{ number_format($totalStandaloneValue) }}
                                    </span>
                                @else
                                    <span class="text-warning fw-extrabold" style="font-size: 28px; letter-spacing: -0.5px;">
                                        TZS {{ $priceFormatted }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <span class="badge px-3 py-2 rounded-pill {{ $isFree ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle' }} fs-11 fw-bold">
                            @if($isFree)
                                <i class="las la-bolt me-1"></i> 100% Free Access
                            @else
                                <i class="las la-bolt me-1"></i> Instant Delivery
                            @endif
                        </span>
                    </div>

                    <!-- Primary Action CTA -->
                    <div class="mb-3">
                        @if($isFree)
                            @auth
                                @if(!empty($product->file) || !empty($product->download_url))
                                    <a href="{{ route('user.download.free.product', $product->slug) }}" class="btn ak23-btn-order-now w-100 py-3 text-center rounded-3 fw-bold fs-15 d-flex align-items-center justify-content-center gap-2 mb-2">
                                        <i class="las la-cloud-download-alt fs-20"></i>
                                        <span>@lang('Download Complete Bundle (Free)')</span>
                                    </a>
                                @endif
                            @endauth

                            <a href="{{ route('order.checkout', ['sku' => $skuCode]) }}" class="btn {{ (!auth()->check() || (empty($product->file) && empty($product->download_url))) ? 'ak23-btn-order-now' : 'ak23-btn-outline-gold' }} w-100 py-3 text-center rounded-3 fw-bold fs-15 d-flex align-items-center justify-content-center gap-2">
                                <i class="las la-graduation-cap fs-20"></i>
                                <span>@lang('Enroll & Get Instant Access')</span>
                            </a>
                            <small class="text-muted d-block text-center mt-2 fs-11">
                                <i class="las la-shield-alt text-success me-1"></i> @lang('No credit card required &bull; Direct access')
                            </small>
                        @else
                            <a href="{{ route('order.checkout', ['sku' => $skuCode]) }}" class="btn ak23-btn-order-now w-100 py-3 text-center rounded-3 fw-bold fs-15 d-flex align-items-center justify-content-center gap-2">
                                <i class="las la-shopping-bag fs-20"></i>
                                <span>@lang('Order Now & Instant Delivery')</span>
                            </a>
                            <small class="text-muted d-block text-center mt-2 fs-11">
                                <i class="las la-lock text-warning me-1"></i> @lang('Secure checkout with Mobile Money & Cards')
                            </small>

                            <!-- Secondary Action: Add to Cart -->
                            <div class="mt-2">
                                <button type="button" class="btn ak23-btn-add-cart w-100 py-2 rounded-3 text-white fw-semibold fs-13 d-flex align-items-center justify-content-center gap-2" data-id="{{ $product->id }}" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);">
                                    <i class="las la-cart-plus fs-16"></i>
                                    <span>@lang('Add to Cart')</span>
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Native Compact Specifications List (No Broken Text Wrapping) -->
                    <div class="ak23-specs-list border-top pt-3" style="border-color: rgba(255,255,255,0.08) !important;">
                        <h6 class="text-white fw-bold mb-2 fs-12 text-uppercase letter-spacing-1">
                            @lang('Product Specifications')
                        </h6>

                        @if($seriesModules->isNotEmpty())
                        <div class="ak23-spec-row d-flex align-items-center justify-content-between py-2 border-bottom fs-13" style="border-color: rgba(255,255,255,0.05) !important;">
                            <span class="ak23-spec-label"><i class="las la-book-open text-warning me-1"></i> @lang('Modules:')</span>
                            <span class="ak23-spec-val text-white fw-semibold">{{ $seriesModules->count() }} @lang('Full Video Courses')</span>
                        </div>
                        @endif

                        <div class="ak23-spec-row d-flex align-items-center justify-content-between py-2 border-bottom fs-13" style="border-color: rgba(255,255,255,0.05) !important;">
                            <span class="ak23-spec-label"><i class="las la-language text-warning me-1"></i> @lang('Language:')</span>
                            <span class="ak23-spec-val text-white fw-semibold">Swahili &amp; English</span>
                        </div>

                        <div class="ak23-spec-row d-flex align-items-center justify-content-between py-2 border-bottom fs-13" style="border-color: rgba(255,255,255,0.05) !important;">
                            <span class="ak23-spec-label"><i class="las la-layer-group text-warning me-1"></i> @lang('Level:')</span>
                            <span class="ak23-spec-val text-white fw-semibold">Beginner to Advanced</span>
                        </div>

                        <div class="ak23-spec-row d-flex align-items-center justify-content-between py-2 border-bottom fs-13" style="border-color: rgba(255,255,255,0.05) !important;">
                            <span class="ak23-spec-label"><i class="las la-compact-disc text-warning me-1"></i> @lang('Software:')</span>
                            <span class="ak23-spec-val text-white fw-semibold text-truncate ms-2" style="max-width: 175px;" title="{{ $softwareName }}">{{ $softwareName }}</span>
                        </div>

                        <div class="ak23-spec-row d-flex align-items-center justify-content-between py-2 border-bottom fs-13" style="border-color: rgba(255,255,255,0.05) !important;">
                            <span class="ak23-spec-label"><i class="las la-key text-warning me-1"></i> @lang('License:')</span>
                            <span class="ak23-spec-val text-success fw-semibold">@lang('Lifetime Unlimited Access')</span>
                        </div>

                        <div class="ak23-spec-row d-flex align-items-center justify-content-between py-2 border-bottom fs-13" style="border-color: rgba(255,255,255,0.05) !important;">
                            <span class="ak23-spec-label"><i class="las la-certificate text-warning me-1"></i> @lang('Certificate:')</span>
                            <span class="ak23-spec-val text-warning fw-semibold">@lang('Included Upon Completion')</span>
                        </div>

                        <div class="ak23-spec-row d-flex align-items-center justify-content-between py-2 fs-13">
                            <span class="ak23-spec-label"><i class="las la-download text-warning me-1"></i> @lang('Delivery:')</span>
                            <span class="ak23-spec-val text-white fw-semibold">@lang('Instant Direct Download (ZIP/RAR)')</span>
                        </div>
                    </div>

                    <!-- Trust Box -->
                    <div class="mt-3 p-3 rounded-2" style="background: rgba(34, 197, 94, 0.08); border: 1px solid rgba(34, 197, 94, 0.2);">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="las la-shield-alt text-success fs-18"></i>
                            <span class="text-white fw-bold fs-12">100% Virus-Free &amp; DAW Certified</span>
                        </div>
                        <p class="mb-0 text-muted fs-11" style="line-height: 1.5;">
                            All files and audio packages are scanned and verified. Includes 24/7 technical activation support.
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
/* Marketplace Single Product Native & Compact Styling */
.ak23-marketplace-product {
    background-color: #09090D;
}

.ak23-prod-breadcrumb {
    font-size: 13px;
}

.ak23-btn-icon {
    width: 32px;
    height: 32px;
    background: #171722;
    border: 1px solid rgba(255,255,255,0.1);
    color: #9E9EB2;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    transition: all 0.2s ease;
}

.ak23-btn-icon:hover {
    background: #FBAF43;
    color: #121316;
    border-color: #FBAF43;
}

.ak23-player-card {
    background: #111118;
    border: 1px solid rgba(255,255,255,0.08);
}

.ak23-nav-pill {
    background: transparent !important;
    color: #9E9EB2 !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
    border-radius: 30px !important;
    padding: 6px 14px !important;
    font-size: 12px !important;
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
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #22C55E;
    box-shadow: 0 0 6px #22C55E;
    display: inline-block;
}

.ak23-play-pulse-btn {
    width: 58px;
    height: 58px;
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
    transform: scale(1.08);
}

@keyframes ak23Pulse {
    0% { box-shadow: 0 0 0 0 rgba(251, 175, 67, 0.6); }
    70% { box-shadow: 0 0 0 16px rgba(251, 175, 67, 0); }
    100% { box-shadow: 0 0 0 0 rgba(251, 175, 67, 0); }
}

.ak23-btn-play-circle {
    width: 42px;
    height: 42px;
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
    box-shadow: 0 4px 18px rgba(251, 175, 67, 0.35);
    border: none;
    transition: all 0.2s ease;
}

.ak23-btn-order-now:hover {
    background: linear-gradient(135deg, #FFBE5C 0%, #FBAF43 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 22px rgba(251, 175, 67, 0.5);
}

.ak23-btn-outline-gold {
    background: transparent;
    border: 1px solid rgba(251, 175, 67, 0.5);
    color: #FBAF43 !important;
    transition: all 0.2s ease;
}

.ak23-btn-outline-gold:hover {
    background: rgba(251, 175, 67, 0.15);
    border-color: #FBAF43;
}

.ak23-btn-add-cart:hover {
    background: rgba(255,255,255,0.12) !important;
    border-color: rgba(255,255,255,0.25) !important;
}

.ak23-feature-icon-box {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    background: rgba(255,255,255,0.04);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* Specifications row layout fixes: NEVER wrap label mid-word */
.ak23-spec-label {
    white-space: nowrap !important;
    flex-shrink: 0;
    min-width: 105px;
    color: #8E8EA2;
    font-size: 13px;
}

.ak23-spec-val {
    text-align: right;
    font-size: 13px;
}

.ak23-module-item:hover {
    background: #1B1B2A !important;
    border-color: rgba(251, 175, 67, 0.3) !important;
}

.hover-gold:hover {
    color: #FBAF43 !important;
}
</style>
@endpush

@push('script-lib')
@if($hasAudio)
<script src="{{ asset('assets/global/js/wavesurfer.min.js') }}"></script>
@endif
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

    @if($hasAudio)
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
                height: 50,
                responsive: true,
                backend: 'MediaElement'
            });

            const audioUrl = $(waveEl).data('audio-url');
            if (audioUrl && audioUrl.length > 5) {
                wavesurfer.load(audioUrl);
            } else {
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

    $('#tab-audio').on('shown.bs.tab', function() {
        initWaveSurfer();
    });
    @endif

    // Quick-Add to Cart
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
