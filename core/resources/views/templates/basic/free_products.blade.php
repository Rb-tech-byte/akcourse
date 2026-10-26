@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $freeProduct = getContent('free_product.content', true);
    @endphp
    <section class="latest-template pt-60 pb-120">
        <div class="container">
            <div class="section-heading d-flex">
                <div class="col-md-9 text-start">
                    <h5 class="section-heading__title">{{ __(@$freeProduct->data_values->title) }}</h5>
                </div>
                <div class="col-md-3">
                    <x-search-form inputClass="form--control form--control--sm search" btn="btn--base btn--sm" />
                </div>
            </div>
            <ul class="nav custom--tab nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-all-items-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-all-items" type="button" role="tab" aria-controls="pills-all-items"
                        aria-selected="true">@lang('All Items')</button>
                </li>
                @foreach ($categories as $category)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-{{ $category->id }}-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-{{ $category->id }}" type="button" role="tab"
                            aria-controls="pills-{{ $category->id }}"
                            aria-selected="false">{{ __($category->name) }}</button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-all-items" role="tabpanel"
                    aria-labelledby="pills-all-items-tab" tabindex="0">
                    <div class="row gy-4">
                        @foreach ($products as $product)
                            <div class="col-lg-3 col-sm-6 col-xsm-6">
                                <x-product :product="$product" />
                            </div>
                        @endforeach
                    </div>
                    @if ($products->hasPages())
                        <div class="pt-30">
                            {{ paginateLinks($products) }}
                        </div>
                    @endif
                </div>

                @foreach ($categories as $category)
                    <div class="tab-pane fade" id="pills-{{ $category->id }}" role="tabpanel"
                        aria-labelledby="pills-{{ $category->id }}-tab" tabindex="0">
                        <div class="row gy-4">
                            @forelse ($category->products->take(8) as $product)
                                <div class="col-lg-3 col-sm-6 col-xsm-6">
                                    <x-product :product="$product" />
                                </div>
                            @empty
                                <x-empty-list title="No product found" />
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@push('style')
<style>
.latest-template {
    background: #09090D !important;
    padding: 40px 0 60px !important;
}
.section-heading__title {
    color: #FFFFFF !important;
    font-weight: 800;
}
.custom--tab.nav-pills .nav-link {
    background: #111118 !important;
    border: 1px solid rgba(255, 255, 255, 0.07) !important;
    color: #8E8EA2 !important;
    border-radius: 8px !important;
}
.custom--tab.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #FBAF43 0%, #E29828 100%) !important;
    color: #09090D !important;
    font-weight: 800;
    border-color: #FBAF43 !important;
}
.product-card {
    background: #111118 !important;
    border: 1px solid rgba(255, 255, 255, 0.07) !important;
    border-radius: 10px !important;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35);
    transition: transform 0.2s ease, border-color 0.2s ease;
}
.product-card:hover {
    transform: translateY(-2px);
    border-color: rgba(251, 175, 67, 0.35) !important;
}
.product-card__thumb {
    position: relative;
    width: 100%;
    padding-top: 64%;
    height: 0;
    overflow: hidden;
    background: #09090D;
}
.product-card__thumb .link {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.product-card__thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.product-card__content {
    background: #111118 !important;
    border: none !important;
    padding: 12px 14px 14px !important;
}
.product-card__title a {
    color: #FFFFFF !important;
}
.product-card__title a:hover {
    color: #FBAF43 !important;
}
.product-card__price {
    color: #22C55E !important;
    font-weight: 800;
}
</style>
@endpush
@endsection
