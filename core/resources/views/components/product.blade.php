<div class="product-card h-100 @if ($product->audio_temp_file) audio-card @endif ">
    <div class="product-card__thumb">
        @if ($product->audio_temp_file && in_array('mp3', $product->category->preview_file_types))
            <div class="audio-player-wrapper">
                <div class="d-flex align-items-center gap-1 audio-player-left">
                    <button id="play-button-{{ $product->id }}" class="play-button">
                        <i class="fas fa-play"></i>
                    </button>
                    <span id="current-time-{{ $product->id }}">00:00</span>
                </div>

                <div class="audio-player-middle" data-file-path="{{ asset(getFilePath('previewFile')) . '/' . productFilePath($product, 'temp_audio_file') . '/' . $product->audio_temp_file }}" id="waveform-{{ $product->id }}"></div>

                <div class="audio-player-time">
                    <span id="total-time-{{ $product->id }}">00:00</span>
                </div>
            </div>
        @else
            <a href="{{ route('product.details', $product->slug) }}" class="link" title="{{ __($product->title) }}">
                <img src="{{ $product->thumbnail_url }}"
                     alt="{{ __($product->title) }}" class="product-image">
            </a>
        @endif


        @if ($product->isTrending())
            <span class="icon" title="@lang('Trending')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 68 68" width="18" height="18" fill="currentColor">
                    <path d="m65.67 16.384-1.263 12.161c-.146 1.335-1.783 1.909-2.722.97l-2.983-2.983-18.784 18.784c-1.178 1.179-3.254 1.179-4.432 0L25.14 34.97 7.66 52.46c-.606.605-1.409.918-2.212.918s-1.606-.313-2.21-.917a3.141 3.141 0 0 1 0-4.433l19.69-19.691c1.179-1.179 3.255-1.179 4.423 0l10.356 10.346L54.28 22.11l-3.494-3.494c-.949-.949-.375-2.586.96-2.722l12.16-1.262a1.596 1.596 0 0 1 1.763 1.752z"/>
                </svg>
            </span>
        @endif
        <div class="collection-list">
            <x-product-save :product="$product" />
        </div>
    </div>
    <div class="product-card__content h-100">
        <div class="product-card__content-inner">
            <div class="product-card__top d-flex w-100 justify-content-between ">
                <div class="product-card-title-wrapper">
                    <h6 class="product-card__title">
                        <a href="{{ route('product.details', $product->slug) }}" class="link border-effect">
                            {{ __($product->title) }}
                        </a>
                    </h6>
                    <span class="product-card__author text-muted" style="font-size: 11.5px;">
                        <i class="las la-certificate text-success"></i> Maarifa23 Academy &middot; @lang('Lifetime License')
                    </span>
                </div>
                @if ($product->is_free)
                    <span class="product-card__price">@lang('Free')</span>
                @else
                    @php
                        $hasDiscount = $product->campaign_product_price;
                    @endphp

                    <span class="product-card__price">
                        @if ($hasDiscount[0])
                            <del>{{ showAmount($product->productPrice('personal')) }}</del>
                            <span class="text-success">{{ showAmount($hasDiscount[1]) }}</span>
                        @else
                            {{ showAmount($product->productPrice('personal')) }}
                        @endif
                    </span>
                @endif

            </div>
            <div class="collection-list list-style">
                <x-product-save :product="$product" />

            </div>
        </div>
        <div class="flex-between align-items-center">
            <div class="product-card__rating">
                @if (@$product->total_review >= gs('min_reviews'))
                    <div class="rating-list">
                        @php echo displayRating($product->avg_rating); @endphp
                    </div>
                @endif
                @if ($product->is_free)
                    <span class="product-card__sales">{{ $product->download_count ?: 0 }}
                        {{ __(str()->plural('Download', $product->download_count ?: 0)) }}</span>
                @else
                    <span class="product-card__sales">{{ $product->total_sold }}
                        {{ __(str()->plural('Sale', $product->total_sold)) }}</span>
                @endif
            </div>

            @if ($product->demo_url)
                <a href="{{ @$product->demo_url }}" target="_blank"
                   class="btn btn-outline--light btn--sm mt-1">@lang('Live Preview')</a>
            @endif
        </div>
    </div>
</div>
