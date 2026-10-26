@extends($activeTemplate . 'layouts.frontend')

@section('content')

@php
    $subtotal = $subtotal ?? 0;
    if ($subtotal == 0 && isset($cartItems)) {
        foreach ($cartItems as $item) {
            $subtotal += ($item->price + $item->buyer_fee);
        }
    }
    $discount = $discount ?? (float)session('coupon_total', 0);
    $total = $total ?? max(0, $subtotal - $discount);
    $couponCode = $couponCode ?? session('coupon_code');
    $subtotalFormatted = number_format($subtotal);
    $totalFormatted = number_format($total);
@endphp

<div class="ak23-cart-wrapper py-3 py-lg-4">
    <div class="container" style="max-width: 1060px;">

        <!-- Cart Header Top Bar -->
        <div class="ak23-cart-topbar mb-3 p-3 rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: #111118; border: 1px solid rgba(255,255,255,0.08);">
            <div class="d-flex align-items-center gap-3">
                <div class="ak23-cart-icon-circle">
                    <i class="las la-shopping-cart"></i>
                </div>
                <div>
                    <h2 class="ak23-cart-page-title mb-0 text-white">@lang('Shopping Cart')</h2>
                    <span class="text-muted fs-12">
                        <span id="cartHeaderCount">{{ count($cartItems) }}</span> @lang('item(s) selected for studio download')
                    </span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('courses') }}" class="btn btn-sm ak23-btn-outline-gold rounded-pill px-3 fs-12 fw-bold">
                    <i class="las la-plus me-1"></i> @lang('Add More Courses')
                </a>
            </div>
        </div>

        <div class="row g-4 align-items-start">
            <!-- Left Column: Cart Items List -->
            <div class="col-lg-7 col-xl-8">
                <div class="ak23-cart-items-container" id="cartItemsList">
                    @forelse ($cartItems as $cartItem)
                        @php
                            $itemTotal = $cartItem->price + $cartItem->buyer_fee;
                            $thumb = $cartItem->product ? $cartItem->product->thumbnail_url : null;
                        @endphp
                        <div class="ak23-cart-item-card mb-3 p-3 p-sm-4 rounded-3" style="background: #111118; border: 1px solid rgba(255,255,255,0.07);" id="cart-item-row-{{ $cartItem->id }}" data-id="{{ $cartItem->id }}" data-price="{{ $itemTotal }}">
                            <div class="d-flex align-items-start gap-3">
                                <!-- Thumbnail / Visual Icon -->
                                <div class="ak23-cart-thumb-box flex-shrink-0">
                                    @if($thumb)
                                        <img src="{{ $thumb }}" alt="{{ $cartItem->title }}" class="ak23-cart-thumb-img rounded-2">
                                    @else
                                        <div class="ak23-cart-thumb-placeholder rounded-2">
                                            <i class="las la-compact-disc"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Details -->
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex align-items-start justify-content-between gap-2">
                                        <div>
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill fs-10 px-2 mb-1">
                                                {{ $cartItem->category ?: 'Audio Course' }}
                                            </span>
                                            <h5 class="ak23-cart-item-title mb-1 text-truncate">
                                                <a href="{{ $cartItem->product ? route('product.details', $cartItem->product->slug) : '#' }}" class="text-white text-decoration-none fw-bold hover-gold">
                                                    {{ $cartItem->title }}
                                                </a>
                                            </h5>
                                            <div class="text-muted fs-11 mb-2">
                                                <i class="las la-check-circle text-success me-1"></i> @lang('Full Masterclass Lessons + Matching Software License Included')
                                            </div>
                                        </div>

                                        <!-- Remove Item Action -->
                                        <button type="button" 
                                                class="btn btn-sm btn-dark border text-danger rounded-circle ak23-btn-remove-item deleteCartItem" 
                                                data-id="{{ $cartItem->id }}" 
                                                data-product-id="{{ $cartItem->product_id }}"
                                                data-action="{{ route('cart.delete', $cartItem->product_id ?: $cartItem->id) }}"
                                                title="@lang('Remove from cart')">
                                            <i class="las la-trash-alt fs-16"></i>
                                        </button>
                                    </div>

                                    <!-- Price & License Controls -->
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-2 border-top" style="border-color: rgba(255,255,255,0.05) !important;">
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fs-11 px-2 py-1">
                                                <i class="las la-certificate me-1"></i> @lang('One-Time Lifetime License Included')
                                            </span>
                                        </div>

                                        <div class="text-end">
                                            <span class="ak23-item-price-tag text-warning fw-bold fs-15" id="item-price-display-{{ $cartItem->id }}">
                                                TZS {{ number_format($cartItem->product->price ?? $itemTotal) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse

                    <!-- Empty Cart State -->
                    <div class="ak23-empty-cart-card text-center p-5 rounded-3 {{ count($cartItems) == 0 ? '' : 'd-none' }}" style="background: #111118; border: 1px solid rgba(255,255,255,0.07);" id="emptyCartView">
                        <div class="ak23-empty-cart-icon mb-3 text-warning">
                            <i class="las la-shopping-basket fs-48"></i>
                        </div>
                        <h4 class="fw-bold text-white mb-2">@lang('Your Studio Cart is Empty')</h4>
                        <p class="text-muted fs-13 mb-4" style="max-width: 420px; margin-left: auto; margin-right: auto;">
                            @lang('Browse our complete vocal mixing masterclasses, DAW workflows, and licensed software packages to get started.')
                        </p>
                        <a href="{{ route('courses') }}" class="btn ak23-btn-checkout fw-bold px-4 py-2 rounded-3 fs-14">
                            <i class="las la-compass me-1"></i> @lang('Explore Courses & Software') &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Live Order Summary -->
            <div class="col-lg-5 col-xl-4">
                <div class="ak23-cart-summary-card p-4 rounded-3 sticky-top" style="top: 85px; background: #111118; border: 1.5px solid rgba(251, 175, 67, 0.35); box-shadow: 0 10px 35px rgba(0,0,0,0.45);">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
                        <h4 class="fw-bold fs-16 text-white mb-0">@lang('Order Summary')</h4>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fs-11 px-2">
                            @lang('Instant Access')
                        </span>
                    </div>

                    <!-- Items Count -->
                    <div class="d-flex justify-content-between align-items-center py-2 text-muted fs-13">
                        <span>@lang('Total Courses & Kits')</span>
                        <span class="text-white fw-bold" id="summaryItemsCount">{{ count($cartItems) }}</span>
                    </div>

                    <!-- Subtotal Row -->
                    <div class="d-flex justify-content-between align-items-center py-2 text-muted fs-13">
                        <span>@lang('Subtotal')</span>
                        <span class="text-warning fw-bold" id="cartSubtotalDisplay">TZS {{ $subtotalFormatted }}</span>
                    </div>

                    <!-- Processing Fee Row -->
                    <div class="d-flex justify-content-between align-items-center py-2 text-muted fs-13">
                        <span>@lang('Processing Fee')</span>
                        <span class="text-success fw-bold">@lang('FREE')</span>
                    </div>

                    <!-- Discount Row -->
                    <div class="d-flex justify-content-between align-items-center py-2 text-muted fs-13 {{ $discount > 0 ? '' : 'd-none' }}" id="discountRow">
                        <span class="d-flex align-items-center gap-1">
                            @lang('Coupon Discount')
                            <span class="badge bg-success text-white fs-10" id="cartAppliedCouponBadge">{{ $couponCode ?? '' }}</span>
                        </span>
                        <span class="text-danger fw-bold" id="discountDisplay">-TZS {{ number_format($discount) }}</span>
                    </div>

                    <!-- Promo / Coupon Code Box -->
                    <div class="ak23-coupon-box my-3 p-3 rounded-2" style="background: #161622; border: 1px solid rgba(255,255,255,0.08);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="fs-12 fw-bold text-white mb-0 d-flex align-items-center gap-1">
                                <i class="las la-tag text-warning"></i> @lang('Have a Promo Code?')
                            </label>
                            <span class="fs-10 text-muted">Try <strong class="text-warning">AK23LAUNCH</strong></span>
                        </div>

                        <!-- Applied Coupon Pill -->
                        <div id="cartAppliedPill" class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-success-subtle border border-success-subtle mb-2 text-nowrap {{ !empty($couponCode) ? '' : 'd-none' }}">
                            <div class="d-flex align-items-center gap-1 overflow-hidden">
                                <i class="las la-check-circle text-success fs-14"></i>
                                <span class="fs-11 fw-bold text-success text-nowrap" id="cartActiveCouponCode">{{ $couponCode ?? '' }}</span>
                                <span class="badge bg-success text-white fs-9 px-1">APPLIED</span>
                            </div>
                            <button type="button" class="btn btn-sm text-danger p-0 text-decoration-none fs-11 fw-bold ms-2" id="btnRemoveCartCoupon">
                                <i class="las la-times"></i> @lang('Remove')
                            </button>
                        </div>

                        <!-- Input Form -->
                        <div id="cartCouponInputGroup" class="input-group input-group-sm {{ !empty($couponCode) ? 'd-none' : '' }}">
                            <input type="text" class="form-control text-uppercase font-monospace fs-12 bg-dark text-white border-secondary" id="cartCouponInput" placeholder="ENTER CODE" value="{{ $couponCode ?? '' }}" maxlength="30">
                            <button class="btn btn-warning fw-bold px-3 text-dark fs-12" type="button" id="btnApplyCartCoupon">
                                @lang('Apply')
                            </button>
                        </div>
                        <div id="cartCouponMsg" class="fs-11 mt-1 d-none"></div>
                    </div>

                    <hr class="my-3" style="border-color: rgba(255,255,255,0.08);">

                    <!-- Grand Total Block -->
                    <div class="d-flex justify-content-between align-items-end mb-4">
                        <div>
                            <div class="fw-bold text-white fs-14">@lang('Total Amount')</div>
                            <div class="text-muted fs-11">@lang('All software licenses included')</div>
                        </div>

                        <div class="text-end">
                            <div class="ak23-grand-total text-warning" id="cartTotalDisplay">
                                TZS {{ $totalFormatted }}
                            </div>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <a href="{{ route('order.checkout') }}" 
                       class="btn ak23-btn-checkout w-100 py-3 rounded-3 fw-bold fs-15 d-flex align-items-center justify-content-center gap-2 mb-3 {{ count($cartItems) == 0 ? 'disabled' : '' }}" 
                       id="btnProceedCheckout">
                        <i class="las la-lock fs-16"></i>
                        <span>@lang('Proceed to Checkout') &rarr;</span>
                    </a>

                    <!-- Security & Trust Notice -->
                    <div class="p-3 rounded-2 text-center" style="background: rgba(34, 197, 94, 0.08); border: 1px solid rgba(34, 197, 94, 0.2);">
                        <div class="d-flex align-items-center justify-content-center gap-2 text-success fw-bold fs-12 mb-1">
                            <i class="las la-shield-alt fs-16"></i>
                            <span>@lang('Official Selcom Protected Gateway')</span>
                        </div>
                        <p class="text-muted fs-11 mb-0">
                            @lang('Instant automated USSD push & Card verification. Immediate download unlock.')
                        </p>
                    </div>

                    <!-- Return Link -->
                    <div class="text-center mt-3">
                        <a href="{{ route('courses') }}" class="text-muted text-decoration-none fs-12 hover-gold">
                            &larr; @lang('Continue browsing catalog')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
<style>
/* Maarifa23 Studio Dark Cart Styling */
.ak23-cart-wrapper {
    background-color: #09090D !important;
}

.ak23-cart-icon-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(251, 175, 67, 0.15);
    color: #FBAF43;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 800;
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

.ak23-cart-page-title {
    font-size: 18px;
    font-weight: 800;
    color: #FFFFFF;
}

.ak23-cart-thumb-box {
    width: 60px;
    height: 60px;
}

.ak23-cart-thumb-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
}

.ak23-cart-thumb-placeholder {
    width: 60px;
    height: 60px;
    background: #111119;
    color: #FBAF43;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.ak23-cart-item-title {
    font-size: 14px;
    line-height: 1.3;
}

.ak23-btn-remove-item {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    background: rgba(239, 68, 68, 0.1) !important;
    color: #EF4444 !important;
    border: 1px solid rgba(239, 68, 68, 0.2) !important;
}

.ak23-btn-remove-item:hover {
    background: #EF4444 !important;
    color: #FFFFFF !important;
}

.ak23-grand-total {
    color: #FBAF43 !important;
    font-size: 24px;
    font-weight: 800;
    letter-spacing: -0.5px;
    line-height: 1;
}

.ak23-btn-checkout {
    background: linear-gradient(135deg, #FBAF43 0%, #E29828 100%);
    color: #09090D !important;
    border: none;
    box-shadow: 0 4px 18px rgba(251, 175, 67, 0.35);
    transition: all 0.2s ease;
}

.ak23-btn-checkout:hover {
    background: linear-gradient(135deg, #FFBE5C 0%, #FBAF43 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 22px rgba(251, 175, 67, 0.5);
}

.hover-gold:hover {
    color: #FBAF43 !important;
}

.ak23-cart-item-title {
    font-size: 14.5px;
    line-height: 1.3;
}

.ak23-btn-remove-item {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.ak23-btn-remove-item:hover {
    background: #FEE2E2 !important;
    color: #DC2626 !important;
}

.ak23-empty-cart-icon {
    font-size: 56px;
    color: #D1D5DB;
}

.ak23-grand-total {
    color: #FF7A00 !important;
    font-size: 24px;
    font-weight: 900;
    letter-spacing: -0.5px;
    line-height: 1;
}

.ak23-btn-checkout {
    background: #FFA000;
    background: linear-gradient(180deg, #FFA726 0%, #FB8C00 100%);
    color: #000000 !important;
    border: none;
    box-shadow: 0 4px 12px rgba(251, 140, 0, 0.25);
    transition: all 0.2s ease;
}

.ak23-btn-checkout:hover {
    background: linear-gradient(180deg, #FFB74D 0%, #FFA726 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(251, 140, 0, 0.35);
}

.cursor-pointer {
    cursor: pointer;
}
</style>
@endpush

@push('script')
<script>
$(document).ready(function() {
    // Real-Time Item Removal via AJAX
    $(document).on('click', '.deleteCartItem', function(e) {
        e.preventDefault();
        const btn = $(this);
        const cartId = btn.data('id');
        const actionUrl = btn.data('action');
        const itemRow = $(`#cart-item-row-${cartId}`);

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        $.ajax({
            url: actionUrl,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || "{{ csrf_token() }}",
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(res) {
                if (res.status === 'success') {
                    // Smoothly remove item row
                    itemRow.slideUp(200, function() {
                        $(this).remove();
                    });

                    // Update Real-Time Costs
                    $('#cartSubtotalDisplay').text(res.formatted_subtotal);
                    $('#cartTotalDisplay').text(res.formatted_total);
                    $('#summaryItemsCount').text(res.cart_count);
                    $('#cartHeaderCount').text(res.cart_count);

                    // Update coupon state
                    if (res.has_coupon && res.discount > 0) {
                        $('#discountRow').removeClass('d-none');
                        $('#discountDisplay').text(res.formatted_discount);
                        $('#cartAppliedCouponBadge').text(res.coupon_code);
                        $('#cartActiveCouponCode').text(res.coupon_code);
                        $('#cartAppliedPill').removeClass('d-none');
                        $('#cartCouponInputGroup').addClass('d-none');
                    } else {
                        $('#discountRow').addClass('d-none');
                        $('#cartAppliedPill').addClass('d-none');
                        $('#cartCouponInputGroup').removeClass('d-none');
                    }

                    // Sync Global Navbar Badges
                    if (typeof window.updateAk23CartCount === 'function') {
                        window.updateAk23CartCount(res.cart_count);
                    } else {
                        $('.ak23-cart-count, .cart-button__qty').text(res.cart_count);
                    }

                    notify('success', res.message);

                    // Show empty state if cart count reaches 0
                    if (res.cart_count === 0) {
                        $('#btnProceedCheckout').addClass('disabled');
                        $('#emptyCartView').removeClass('d-none').hide().fadeIn(300);
                    }
                } else {
                    btn.prop('disabled', false).html('<i class="las la-trash-alt fs-16"></i>');
                    notify('error', res.message || 'Could not remove item.');
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="las la-trash-alt fs-16"></i>');
                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error removing item.';
                notify('error', msg);
            }
        });
    });


    // Apply Promo Code in Real Time
    $('#btnApplyCartCoupon').on('click', function() {
        const code = $('#cartCouponInput').val().trim();
        const btn = $(this);
        const msgBox = $('#cartCouponMsg');

        if (!code) {
            msgBox.removeClass('d-none text-success').addClass('text-danger').text('Please enter a coupon code.');
            return;
        }

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
        msgBox.addClass('d-none');

        $.ajax({
            url: "{{ route('cart.apply.coupon') }}",
            method: 'POST',
            data: {
                coupon_code: code,
                _token: $('meta[name="csrf-token"]').attr('content') || "{{ csrf_token() }}"
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || "{{ csrf_token() }}",
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(res) {
                btn.prop('disabled', false).text('Apply');
                if (res.success || res.status === 'success') {
                    $('#cartSubtotalDisplay').text(res.formatted_subtotal);
                    $('#discountDisplay').text(res.formatted_discount);
                    $('#cartAppliedCouponBadge').text(res.coupon_code);
                    $('#discountRow').removeClass('d-none');
                    $('#cartTotalDisplay').text(res.formatted_total);

                    $('#cartActiveCouponCode').text(res.coupon_code);
                    $('#cartAppliedPill').removeClass('d-none');
                    $('#cartCouponInputGroup').addClass('d-none');

                    notify('success', res.message || 'Coupon applied successfully!');
                } else {
                    msgBox.removeClass('d-none text-success').addClass('text-danger').text(res.message || 'Could not apply coupon.');
                    notify('error', res.message || 'Could not apply coupon.');
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).text('Apply');
                const err = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Coupon validation failed.';
                msgBox.removeClass('d-none text-success').addClass('text-danger').text(err);
                notify('error', err);
            }
        });
    });

    // Remove Promo Code in Real Time
    $('#btnRemoveCartCoupon').on('click', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('cart.remove.coupon') }}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || "{{ csrf_token() }}",
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(res) {
                $('#discountRow').addClass('d-none');
                $('#cartAppliedPill').addClass('d-none');
                $('#cartCouponInputGroup').removeClass('d-none');
                $('#cartSubtotalDisplay').text(res.formatted_subtotal);
                $('#cartTotalDisplay').text(res.formatted_total);
                $('#cartCouponInput').val('');
                notify('info', 'Coupon removed.');
            },
            error: function() {
                notify('error', 'Could not remove coupon.');
            }
        });
    });
});
</script>
@endpush
