<div class="col-lg-4 ps-xxl-5">
    <div class="common-sidebar">
        @include($activeTemplate . 'partials.add_to_cart')

        @include($activeTemplate . 'partials.author_profile')
        @include($activeTemplate . 'user.product.attribute')
    </div>
    @php
        echo getAds('300x250');
    @endphp
</div>
