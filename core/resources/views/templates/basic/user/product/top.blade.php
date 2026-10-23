<div class="product-details-top">

    <h5 class="product-details__title">{{ __($product->title) }}</h5>
    <div class="product-details-top__inner flex-between gap-3 align-items-start">
        <ul class="custom-tab">
            <li class="custom-tab__item {{ menuActive('product.details') }}">
                <a href="{{ route('product.details', $product->slug) }}" class="custom-tab__link">@lang('Description')</a>
            </li>
            @if ($product->total_review >= gs('min_reviews'))
                <li class="custom-tab__item {{ menuActive('product.reviews') }}">
                    <a href="{{ route('product.reviews', $product->slug) }}" class="custom-tab__link">
                        @lang('Reviews')
                        <span class="notification">{{ $product->total_review }}</span>
                    </a>
                </li>
            @endif

            <li class="custom-tab__item {{ menuActive('product.comments') }}">
                <a href="{{ route('product.comments', $product->slug) }}" class="custom-tab__link">
                    @lang('Comments')
                    <span class="notification">{{ @$product->comments_count }}</span>
                </a>
            </li>
            @if (gs('changelog'))
                @if ($product->status == Status::PRODUCT_APPROVED || $product->my_product)
                    @if (count($product->changelogs) > 0 && $product->product_updated == Status::PRODUCT_NO_UPDATE)
                        <li class="custom-tab__item {{ menuActive('product.changelog') }}">
                            <a href="{{ route('product.changelog', $product->slug) }}" class="custom-tab__link">
                                @lang('Changelog')
                            </a>
                        </li>
                    @endif
                @endif
            @endif

        </ul>
        @if ($product->status == Status::PRODUCT_APPROVED)
            <div class="product-details-top__right flex-align">
                @include($activeTemplate . 'user.product.social_share')
                @if (@$product->total_review >= gs('min_reviews'))
                    <div class="rating-list">
                        @php echo displayRating($product->avg_rating);  @endphp
                        <span class="rating-list__text"> ({{ $product->total_review }})</span>
                    </div>
                @endif
                <span class="sales d-block d-lg-none">@lang('Sale') {{ @$product->total_sold }}</span>
            </div>
        @endif
    </div>
</div>
