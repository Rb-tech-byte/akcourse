@extends($activeTemplate . 'layouts.frontend')

@section('content')

@php
    $productTitle = @$product->course_title ?: (@$product->title ?: @$product->name);
    $softwareName = @$product->software_name ?: 'Full Studio Plugin Suite';
    $rawEdition = @$product->software_edition;
    $softwareEdition = (!$rawEdition || str_contains($rawEdition, '[Confirm')) ? 'Perpetual Commercial License Included' : $rawEdition;
    $stageLabel = @$product->stage_label ?: 'Studio Production Edition';
    $skuCode = @$product->sku ?: ($sku ?: 'AK23-PROD-CHECKOUT');
    $subtotal = $subtotal ?? 140000;
    $discount = $discount ?? 0;
    $total = $total ?? max(0, $subtotal - $discount);
    $priceFormatted = number_format($total);
    $subtotalFormatted = number_format($subtotal);
@endphp

<div class="ak23-clean-checkout-wrapper py-3 py-lg-4">
    <div class="container" style="max-width: 980px;">

        <!-- Top Verified Gateway Header Bar -->
        <div class="ak23-clean-topbar mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2 gap-sm-3">
                    <span class="ak23-clean-pill-verified">
                        <i class="las la-shield-alt text-warning me-1"></i> @lang('Verified Gateway')
                    </span>
                    <div>
                        <div class="ak23-clean-tech-brand">AK23 TECHNOLOGY</div>
                        <h2 class="ak23-clean-secure-heading mb-0">@lang('SECURE CHECKOUT')</h2>
                    </div>
                </div>

                <div class="ak23-clean-order-badge">
                    {{ $orderNumber ?? '#1376' }}
                </div>
            </div>
        </div>

        <!-- Dynamic Notice Area -->
        <div id="checkoutNoticeArea"></div>

        <div class="row g-3 g-lg-4 align-items-start">
            <!-- Left Column: Payment Method Selection -->
            <div class="col-lg-7">
                <form action="{{ route('order.process', ['sku' => $skuCode]) }}" method="POST" id="checkoutForm">
                    @csrf
                    <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="mobile_money">
                    <input type="hidden" name="operator" id="selectedOperator" value="MPESA">
                    <input type="hidden" name="gateway" id="selectedGateway" value="801">
                    <input type="hidden" name="currency" value="TZS">
                    <input type="hidden" name="sku" value="{{ $skuCode }}">
                    <input type="hidden" name="terms" value="1">
                    <input type="hidden" name="email" id="emailHiddenInput" value="{{ $userEmail ?? (auth()->check() ? auth()->user()->email : 'info@wolinet.com') }}">
                    <input type="hidden" name="address" value="{{ $userAddress ?? (auth()->check() ? auth()->user()->address : 'Ali Hassan Mwinyi Rd, Kijitonyama') }}">
                    <input type="hidden" name="city" value="{{ $userCity ?? (auth()->check() ? auth()->user()->city : 'Dar es Salaam') }}">
                    <input type="hidden" name="country_name" value="{{ $userCountry ?? (auth()->check() ? auth()->user()->country_name : 'Tanzania') }}">

                    <!-- =========================================================================
                         CARD 1: MOBILE MONEY (DEFAULT ACTIVE)
                         ========================================================================= -->
                    <div class="ak23-clean-card active mb-3" id="cardMobileMoney" data-method="mobile_money">
                        <!-- Header -->
                        <div class="ak23-clean-card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2 gap-sm-3">
                                <div class="ak23-clean-icon-box orange">
                                    <i class="las la-mobile-alt"></i>
                                </div>
                                <div>
                                    <h4 class="ak23-clean-card-title mb-0">@lang('Mobile Money')</h4>
                                    <span class="ak23-clean-card-sub">@lang('Bofya kulipa kwa mitandao ya simu:')</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="ak23-clean-push-badge">
                                    @lang('PUSH USSD') <span class="ak23-clean-dot"></span>
                                </span>
                                <span class="ak23-clean-radio-btn active" id="radioMobile"></span>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="ak23-clean-card-body mt-3" id="bodyMobileMoney">
                            <!-- 4 Operators Row -->
                            <div class="ak23-clean-op-grid mb-3">
                                <div class="ak23-clean-op-box active" data-operator="MPESA" title="Vodacom M-Pesa">
                                    <img src="{{ asset('assets/Payment-icon/M-pesa.png') }}" alt="M-Pesa" class="ak23-clean-op-img">
                                </div>
                                <div class="ak23-clean-op-box" data-operator="MIXX" title="Mixx by Yas (Tigo)">
                                    <img src="{{ asset('assets/Payment-icon/mixx.png') }}" alt="Mixx" class="ak23-clean-op-img">
                                </div>
                                <div class="ak23-clean-op-box" data-operator="AIRTEL" title="Airtel Money">
                                    <img src="{{ asset('assets/Payment-icon/airtel.png') }}" alt="Airtel" class="ak23-clean-op-img">
                                </div>
                                <div class="ak23-clean-op-box" data-operator="HALOPESA" title="HaloPesa">
                                    <img src="{{ asset('assets/Payment-icon/halopesa.png') }}" alt="HaloPesa" class="ak23-clean-op-img">
                                </div>
                            </div>

                            <!-- Phone Number Input -->
                            <div class="mb-2">
                                <label class="ak23-clean-label" for="phoneInput">@lang('Phone Number')</label>
                                <input type="tel" 
                                       name="phone" 
                                       id="phoneInput" 
                                       class="form-control ak23-clean-input" 
                                       placeholder="0XX XXX XXX XXX" 
                                       value="{{ $userPhone ?? (auth()->check() ? auth()->user()->mobile : '255747003357') }}" 
                                       required>
                            </div>

                            <!-- License Delivery Strip (Auto-Loaded Email) -->
                            <div class="ak23-clean-delivery-strip d-flex align-items-center justify-content-between mb-3 px-2 py-1">
                                <span class="fs-11 text-muted">
                                    <i class="las la-envelope text-warning me-1"></i> @lang('License deliver to:') <strong class="text-white" id="displayEmail">{{ $userEmail ?? (auth()->check() ? auth()->user()->email : 'info@wolinet.com') }}</strong>
                                </span>
                                <a href="javascript:void(0)" class="text-warning fs-11 text-decoration-none fw-bold" id="btnToggleEmailEdit">@lang('Change')</a>
                            </div>

                            <!-- Optional Expandable Email Field -->
                            <div id="emailEditContainer" class="mb-3" style="display: none;">
                                <label class="ak23-clean-label" for="emailInput">@lang('Delivery Email Address')</label>
                                <input type="email" 
                                       id="emailInput" 
                                       class="form-control ak23-clean-input" 
                                       placeholder="info@wolinet.com" 
                                       value="{{ $userEmail ?? (auth()->check() ? auth()->user()->email : 'info@wolinet.com') }}">
                            </div>

                            <!-- Orange Pay Button (Matches Sample Screenshot) -->
                            <button type="submit" class="ak23-clean-pay-btn" id="btnSubmitMobile">
                                <i class="las la-lock"></i>
                                <span>@lang('Pay TZS') <strong class="ak23-btn-pay-amount">{{ $priceFormatted }}</strong> @lang('via Mobile Money')</span>
                            </button>
                        </div>
                    </div>

                    <!-- =========================================================================
                         CARD 2: CREDIT / DEBIT CARD
                         ========================================================================= -->
                    <div class="ak23-clean-card mb-3" id="cardCreditCard" data-method="card">
                        <!-- Header -->
                        <div class="ak23-clean-card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2 gap-sm-3">
                                <div class="ak23-clean-icon-box blue">
                                    <i class="las la-credit-card"></i>
                                </div>
                                <div>
                                    <h4 class="ak23-clean-card-title mb-0">@lang('Credit / Debit Card')</h4>
                                    <span class="ak23-clean-card-sub">@lang('Visa & Mastercard')</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ asset('assets/Payment-icon/visa.png') }}" alt="Visa" class="ak23-clean-card-brand">
                                <span class="ak23-clean-radio-btn" id="radioCard"></span>
                            </div>
                        </div>

                        <!-- Body (Hidden by default) -->
                        <div class="ak23-clean-card-body mt-3" id="bodyCreditCard" style="display: none;">
                            <div class="mb-2">
                                <label class="ak23-clean-label" for="cardNameInput">@lang('Cardholder Name')</label>
                                <input type="text" 
                                       name="card_name" 
                                       id="cardNameInput" 
                                       class="form-control ak23-clean-input" 
                                       placeholder="e.g. Alex M. Kaseja" 
                                       value="{{ $userName ?? (auth()->check() ? auth()->user()->fullname : 'Alex M. Kaseja') }}">
                            </div>

                            <div class="mb-2">
                                <label class="ak23-clean-label" for="cardNumberInput">@lang('Card Number')</label>
                                <div class="position-relative">
                                    <input type="text" 
                                           name="card_number" 
                                           id="cardNumberInput" 
                                           class="form-control ak23-clean-input pe-5" 
                                           placeholder="1234 5678 9012 3456" 
                                           maxlength="23">
                                    <span class="position-absolute end-0 top-50 translate-middle-y me-3">
                                        <img src="{{ asset('assets/Payment-icon/visa.png') }}" alt="Card" style="height: 16px; width: auto;">
                                    </span>
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="ak23-clean-label" for="cardExpInput">@lang('Expiration Date')</label>
                                    <input type="text" 
                                           name="card_exp" 
                                           id="cardExpInput" 
                                           class="form-control ak23-clean-input" 
                                           placeholder="MM / YY" 
                                           maxlength="7">
                                </div>
                                <div class="col-6">
                                    <label class="ak23-clean-label" for="cardCvvInput">@lang('Security Code (CVV)')</label>
                                    <input type="password" 
                                           name="card_cvv" 
                                           id="cardCvvInput" 
                                           class="form-control ak23-clean-input" 
                                           placeholder="CVC" 
                                           maxlength="4">
                                </div>
                            </div>

                            <!-- Card Orange Pay Button -->
                            <button type="submit" class="ak23-clean-pay-btn" id="btnSubmitCard">
                                <i class="las la-lock"></i>
                                <span>@lang('Pay TZS') <strong class="ak23-btn-pay-amount">{{ $priceFormatted }}</strong> @lang('via Card')</span>
                            </button>
                        </div>
                    </div>

                    <!-- =========================================================================
                         CARD 3: SCAN TO PAY
                         ========================================================================= -->
                    <div class="ak23-clean-card mb-3" id="cardScanPay" data-method="scan">
                        <!-- Header -->
                        <div class="ak23-clean-card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2 gap-sm-3">
                                <div class="ak23-clean-icon-box purple">
                                    <i class="las la-qrcode"></i>
                                </div>
                                <div>
                                    <h4 class="ak23-clean-card-title mb-0">@lang('Scan to Pay')</h4>
                                    <span class="ak23-clean-card-sub">@lang('Selcom Pay / Gift Code')</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="ak23-clean-tip-badge">@lang('NEW TIP')</span>
                                <span class="ak23-clean-radio-btn" id="radioScan"></span>
                            </div>
                        </div>

                        <!-- Body (Hidden by default) -->
                        <div class="ak23-clean-card-body mt-3 text-center" id="bodyScanPay" style="display: none;">
                            <p class="text-muted fs-12 mb-2">@lang('Scan QR code with your mobile banking app or enter Selcom Pay Till code.')</p>
                            <div class="p-3 ak23-till-box rounded-3 d-inline-block border mb-3">
                                <i class="las la-qrcode fs-1 text-white"></i>
                                <div class="fw-bold fs-13 text-white mt-1">SELCOM TILL: 7470033</div>
                            </div>
                            <button type="button" class="ak23-clean-pay-btn" id="btnSubmitScan">
                                <i class="las la-lock"></i>
                                <span>@lang('Pay TZS') <strong class="ak23-btn-pay-amount">{{ $priceFormatted }}</strong> @lang('via Selcom Pay')</span>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Footer Trust and Navigation (Matching Sample) -->
                <div class="ak23-clean-footer mt-2">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="las la-check-circle text-success fs-15"></i>
                        <span class="fw-bold fs-11 text-white letter-spacing-1">@lang('PCI-DSS COMPLIANT GATEWAY')</span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 text-muted fs-12 mb-2">
                        <a href="{{ route('cart.index') }}" class="text-warning text-decoration-none fw-bold fs-12">
                            &larr; @lang('Return to Cart')
                        </a>
                        <div class="d-flex align-items-center gap-1">
                            <i class="las la-shield-alt text-muted fs-14"></i>
                            <span>@lang('PCI-DSS COMPLIANT')</span>
                        </div>
                    </div>

                    <div class="text-center text-muted fs-11">
                        <i class="las la-info-circle me-1"></i> @lang('Selection is final once you proceed to authorization.')
                    </div>
                </div>
            </div>

            <!-- Right Column: Order Summary Card (Matching Sample) -->
            <div class="col-lg-5">
                <div class="ak23-clean-summary-card">
                    <!-- Title & Ref -->
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="ak23-clean-summary-title mb-0">@lang('Order Summary')</h3>
                        <span class="ak23-clean-summary-ref">{{ $orderNumber ?? '#1376' }}</span>
                    </div>

                    <!-- Items Included in this Order -->
                    @if(!empty($isCartCheckout) && isset($cartItems) && $cartItems->isNotEmpty())
                        <div class="ak23-clean-items-box mb-3">
                            @foreach($cartItems as $cItem)
                                <div class="ak23-clean-summary-item p-2 mb-2 rounded-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                                        <div class="ak23-clean-item-mini-icon flex-shrink-0 text-warning" style="font-size: 18px;">
                                            <i class="las la-compact-disc"></i>
                                        </div>
                                        <div class="text-truncate">
                                            <div class="fw-bold fs-12 text-white text-truncate">{{ $cItem->title }}</div>
                                            <div class="text-muted fs-10 text-truncate">
                                                {{ $cItem->category ?: 'Audio Course' }} &middot; @lang('One-Time Lifetime License & Purchase Certificate Included')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fw-bold fs-12 text-white flex-shrink-0 ms-2">
                                        TZS {{ number_format($cItem->price + $cItem->buyer_fee) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="ak23-clean-summary-item p-2 mb-3 rounded-3">
                            <div class="fw-bold fs-13 text-white text-truncate">{{ $productTitle }}</div>
                            <div class="text-muted fs-11">{{ $stageLabel }} &middot; {{ $softwareEdition }}</div>
                        </div>
                    @endif

                    <!-- Subtotal Row -->
                    <div class="d-flex justify-content-between align-items-center py-2 text-muted fs-13">
                        <span>@lang('Subtotal')</span>
                        <span class="text-white fw-bold" id="checkoutSubtotalDisplay">TZS {{ $subtotalFormatted }}</span>
                    </div>

                    <!-- Processing Fee Row -->
                    <div class="d-flex justify-content-between align-items-center py-2 text-muted fs-13">
                        <span>@lang('Processing Fee')</span>
                        <span class="text-success fw-bold">@lang('FREE')</span>
                    </div>

                    <!-- Discount Row -->
                    <div class="d-flex justify-content-between align-items-center py-2 text-muted fs-13 {{ $discount > 0 ? '' : 'd-none' }}" id="checkoutDiscountRow">
                        <span class="d-flex align-items-center gap-1">
                            @lang('Coupon Discount')
                            <span class="badge bg-success text-white fs-10" id="checkoutCouponBadge">{{ $couponCode ?? '' }}</span>
                        </span>
                        <span class="text-danger fw-bold" id="checkoutDiscountDisplay">-TZS {{ number_format($discount) }}</span>
                    </div>

                    <!-- Promo Code Accordion on Checkout -->
                    <div class="ak23-checkout-coupon-section my-2 pt-2 border-top" style="border-color: rgba(255, 255, 255, 0.08) !important;">
                        <a href="javascript:void(0)" class="fs-11 text-decoration-none text-muted fw-bold d-flex align-items-center justify-content-between" id="btnToggleCheckoutPromo">
                            <span><i class="las la-tag text-warning me-1"></i> @lang('Have a promo code?')</span>
                            <i class="las la-angle-down" id="promoToggleIcon"></i>
                        </a>
                        <div id="checkoutPromoCollapse" class="mt-2 {{ !empty($couponCode) ? 'd-none' : 'd-none' }}">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control text-uppercase font-monospace fs-11" id="checkoutPromoInput" placeholder="COUPON CODE" value="{{ $couponCode ?? '' }}">
                                <button class="btn btn-warning fw-bold px-3 text-dark fs-11" type="button" id="btnApplyCheckoutPromo">
                                    @lang('Apply')
                                </button>
                            </div>
                            <div id="checkoutPromoMsg" class="fs-10 mt-1 d-none"></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-1 fs-11 text-success {{ !empty($couponCode) ? '' : 'd-none' }}" id="checkoutActiveCouponRow">
                            <span><i class="las la-check-circle me-1"></i> Promo: <strong id="checkoutActiveCodeText">{{ $couponCode ?? '' }}</strong></span>
                            <a href="javascript:void(0)" class="text-danger fw-bold text-decoration-none" id="btnRemoveCheckoutPromo">@lang('Remove')</a>
                        </div>
                    </div>

                    <hr class="my-3" style="border-color: rgba(255, 255, 255, 0.08);">

                    <!-- Total Amount Block -->
                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <div>
                            <div class="fw-bold text-white fs-14">@lang('Total Amount')</div>
                            <div class="text-muted fs-11">@lang('Local taxes included')</div>
                        </div>

                        <div class="text-end">
                            <div class="ak23-clean-total-price" id="checkoutTotalDisplay">
                                TZS {{ $priceFormatted }}
                            </div>
                        </div>
                    </div>

                    <!-- Authorized by Selcom Box -->
                    <div class="ak23-clean-selcom-box p-3 rounded-3">
                        <div class="fw-bold text-success fs-12 mb-1">
                            @lang('Authorized by Selcom.')
                        </div>
                        <div class="text-success fs-11 d-flex align-items-center gap-1" style="opacity: 0.9;">
                            <i class="las la-info-circle"></i> @lang('Your sensitive data is never stored.')
                        </div>
                    </div>

                    <!-- Authenticated Customer Profile Capsule -->
                    @if(auth()->check())
                        <div class="mt-3 p-2 ak23-profile-capsule rounded-3 border d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2 overflow-hidden">
                                <div class="ak23-clean-avatar">
                                    {{ strtoupper(substr($userName ?? auth()->user()->username, 0, 1)) }}
                                </div>
                                <div class="text-truncate">
                                    <div class="fw-bold fs-11 text-white text-truncate">{{ $userName ?? auth()->user()->fullname }}</div>
                                    <div class="text-muted fs-10 text-truncate">{{ $userEmail ?? auth()->user()->email }}</div>
                                </div>
                            </div>
                            <a href="{{ route('user.profile.setting') }}" class="btn btn-sm text-warning border-0 fs-11 p-0 fw-bold" title="@lang('Edit Profile')">
                                @lang('Edit')
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
<style>
/* AK23 Studio Dark Theme - Compact Native Fit */
.ak23-clean-checkout-wrapper {
    background-color: #09090D !important;
}

.ak23-clean-topbar {
    background: #111118 !important;
    border: 1px solid rgba(255, 255, 255, 0.07) !important;
    border-radius: 10px;
    padding: 12px 18px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
}

.ak23-clean-pill-verified {
    background: rgba(251, 175, 67, 0.12);
    border: 1px solid rgba(251, 175, 67, 0.28);
    color: #FBAF43;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
}

.ak23-clean-tech-brand {
    font-size: 10px;
    font-weight: 800;
    color: #FBAF43;
    letter-spacing: 0.8px;
    line-height: 1.1;
}

.ak23-clean-secure-heading {
    font-size: 16px;
    font-weight: 900;
    color: #FFFFFF;
    letter-spacing: -0.2px;
    line-height: 1.1;
}

.ak23-clean-order-badge {
    background: #FBAF43;
    color: #09090D;
    font-size: 13px;
    font-weight: 800;
    padding: 4px 14px;
    border-radius: 8px;
}

/* Pay Cards (Explicit Block Layout to prevent flex collision) */
.ak23-clean-card {
    display: block !important;
    background: #111118 !important;
    border: 1px solid rgba(255, 255, 255, 0.07) !important;
    border-radius: 10px !important;
    padding: 16px 20px !important;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
    cursor: pointer !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2) !important;
    width: 100% !important;
}

.ak23-clean-card:hover {
    border-color: rgba(255, 255, 255, 0.16) !important;
    transform: translateY(-1px);
}

.ak23-clean-card.active {
    border: 1.5px solid #FBAF43 !important;
    background: #13131D !important;
    box-shadow: 0 6px 24px rgba(251, 175, 67, 0.08) !important;
}

.ak23-clean-card-header {
    width: 100% !important;
}

.ak23-clean-icon-box {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.ak23-clean-icon-box.orange {
    background: rgba(251, 175, 67, 0.12);
    color: #FBAF43;
    border: 1px solid rgba(251, 175, 67, 0.25);
}

.ak23-clean-icon-box.blue {
    background: rgba(59, 130, 246, 0.12);
    color: #60A5FA;
    border: 1px solid rgba(59, 130, 246, 0.25);
}

.ak23-clean-icon-box.purple {
    background: rgba(168, 85, 247, 0.12);
    color: #C084FC;
    border: 1px solid rgba(168, 85, 247, 0.25);
}

.ak23-clean-card-title {
    font-size: 14.5px;
    font-weight: 800;
    color: #FFFFFF !important;
    line-height: 1.2;
}

.ak23-clean-card-sub {
    font-size: 11.5px;
    color: #8E8EA2 !important;
}

.ak23-clean-push-badge {
    background: rgba(34, 197, 94, 0.12);
    color: #22C55E;
    border: 1px solid rgba(34, 197, 94, 0.25);
    font-weight: 700;
    font-size: 11px;
    border-radius: 20px;
    padding: 3px 10px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}

.ak23-clean-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #22C55E;
    display: inline-block;
}

.ak23-clean-tip-badge {
    background: rgba(251, 175, 67, 0.12);
    color: #FBAF43;
    border: 1px solid rgba(251, 175, 67, 0.25);
    font-size: 10px;
    font-weight: 800;
    border-radius: 12px;
    padding: 2px 8px;
}

.ak23-clean-radio-btn {
    width: 15px;
    height: 15px;
    border-radius: 50%;
    border: 1.5px solid rgba(255, 255, 255, 0.2);
    display: inline-block;
    transition: all 0.2s;
    flex-shrink: 0;
}

.ak23-clean-card.active .ak23-clean-radio-btn,
.ak23-clean-radio-btn.active {
    border-color: #FBAF43 !important;
    background: #FBAF43 !important;
}

.ak23-clean-card-brand {
    height: 18px;
    width: auto;
    object-fit: contain;
}

/* Operator Grid (4 Columns) */
.ak23-clean-op-grid {
    display: grid !important;
    grid-template-columns: repeat(4, 1fr) !important;
    gap: 10px !important;
    width: 100% !important;
}

.ak23-clean-op-box {
    background: #161622 !important;
    border: 1.5px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 10px !important;
    height: 52px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    transition: all 0.15s ease !important;
    padding: 6px 8px !important;
}

.ak23-clean-op-box:hover {
    border-color: rgba(255, 255, 255, 0.2) !important;
    background: #1A1A28 !important;
}

.ak23-clean-op-box.active {
    border-color: #FBAF43 !important;
    background: rgba(251, 175, 67, 0.1) !important;
    box-shadow: 0 0 12px rgba(251, 175, 67, 0.15) !important;
}

.ak23-clean-op-img {
    max-height: 34px !important;
    max-width: 85% !important;
    object-fit: contain !important;
}

/* Form Inputs Studio Dark */
.ak23-clean-label {
    display: block;
    font-size: 11.5px;
    font-weight: 600;
    color: #A0A0B2;
    margin-bottom: 5px;
}

.ak23-clean-input {
    background: #161622 !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: #FFFFFF !important;
    border-radius: 8px !important;
    padding: 10px 14px !important;
    font-size: 13.5px !important;
    font-weight: 500 !important;
    width: 100% !important;
}

.ak23-clean-input:focus {
    border-color: #FBAF43 !important;
    box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.15) !important;
    background: #161622 !important;
    color: #FFFFFF !important;
}

.ak23-clean-input::placeholder {
    color: #64647A !important;
}

.ak23-clean-delivery-strip {
    background: #161622;
    border-radius: 8px;
    border: 1px dashed rgba(255, 255, 255, 0.12);
}

.ak23-till-box {
    background: #161622;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
}

/* Pay Button */
.ak23-clean-pay-btn {
    background: linear-gradient(135deg, #FBAF43 0%, #E29828 100%) !important;
    color: #09090D !important;
    font-weight: 800 !important;
    font-size: 14.5px !important;
    border-radius: 8px !important;
    padding: 12px 20px !important;
    border: none !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    box-shadow: 0 4px 16px rgba(251, 175, 67, 0.25) !important;
    cursor: pointer !important;
    width: 100% !important;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
}

.ak23-clean-pay-btn:hover {
    background: linear-gradient(135deg, #FFBA54 0%, #FBAF43 100%) !important;
    box-shadow: 0 6px 20px rgba(251, 175, 67, 0.35) !important;
    transform: translateY(-1px);
    color: #000000 !important;
}

.ak23-clean-pay-btn:active {
    transform: translateY(0);
}

/* Order Summary Card */
.ak23-clean-summary-card {
    background: #111118 !important;
    border: 1px solid rgba(255, 255, 255, 0.07) !important;
    border-radius: 10px !important;
    padding: 22px !important;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.3) !important;
}

.ak23-clean-summary-title {
    font-size: 16px !important;
    font-weight: 800 !important;
    color: #FFFFFF !important;
}

.ak23-clean-summary-ref {
    color: #FBAF43 !important;
    font-size: 13px !important;
    font-weight: 700 !important;
}

.ak23-clean-summary-item {
    background: #161622 !important;
    border: 1px solid rgba(255, 255, 255, 0.07) !important;
}

.ak23-profile-capsule {
    background: #161622 !important;
    border: 1px solid rgba(255, 255, 255, 0.07) !important;
}

.ak23-clean-total-price {
    color: #FBAF43 !important;
    font-size: 26px !important;
    font-weight: 900 !important;
    letter-spacing: -0.5px !important;
    line-height: 1 !important;
}

.ak23-clean-selcom-box {
    background: rgba(34, 197, 94, 0.08) !important;
    border: 1px solid rgba(34, 197, 94, 0.22) !important;
}

.ak23-clean-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #FBAF43;
    color: #09090D;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 800;
    flex-shrink: 0;
}

#checkoutPromoInput {
    background: #161622 !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: #FFFFFF !important;
}
#checkoutPromoInput:focus {
    border-color: #FBAF43 !important;
}
</style>
@endpush

@push('script')
<script>
$(document).ready(function() {
    let activeMethod = 'mobile_money';

    function switchMethod(method) {
        activeMethod = method;
        $('#selectedPaymentMethod').val(method);
        $('.ak23-clean-card').removeClass('active');
        $('.ak23-clean-radio-btn').removeClass('active');
        $('#checkoutNoticeArea').empty();

        if (method === 'mobile_money') {
            $('#cardMobileMoney').addClass('active');
            $('#radioMobile').addClass('active');
            $('#bodyMobileMoney').slideDown(150);
            $('#bodyCreditCard').slideUp(150);
            $('#bodyScanPay').slideUp(150);
            $('#selectedGateway').val('801');
            $('#phoneInput').prop('required', true);
            $('#cardNameInput, #cardNumberInput, #cardExpInput, #cardCvvInput').prop('required', false);
        } else if (method === 'card') {
            $('#cardCreditCard').addClass('active');
            $('#radioCard').addClass('active');
            $('#bodyCreditCard').slideDown(150);
            $('#bodyMobileMoney').slideUp(150);
            $('#bodyScanPay').slideUp(150);
            $('#selectedGateway').val('802');
            $('#phoneInput').prop('required', false);
            $('#cardNameInput, #cardNumberInput, #cardExpInput, #cardCvvInput').prop('required', true);
        } else if (method === 'scan') {
            $('#cardScanPay').addClass('active');
            $('#radioScan').addClass('active');
            $('#bodyScanPay').slideDown(150);
            $('#bodyMobileMoney').slideUp(150);
            $('#bodyCreditCard').slideUp(150);
            $('#selectedGateway').val('801');
            $('#phoneInput, #cardNameInput, #cardNumberInput, #cardExpInput, #cardCvvInput').prop('required', false);
        }
    }

    // Click anywhere on payment card (unless clicking inputs/buttons) to toggle
    $('.ak23-clean-card').on('click', function(e) {
        if ($(e.target).closest('input, button, a, .ak23-clean-op-box').length) {
            return;
        }
        const method = $(this).data('method');
        switchMethod(method);
    });

    // Operator selection
    $('.ak23-clean-op-box').on('click', function(e) {
        e.stopPropagation();
        $('.ak23-clean-op-box').removeClass('active');
        $(this).addClass('active');
        $('#selectedOperator').val($(this).data('operator'));
    });

    // Auto-detect operator on phone input typing
    $('#phoneInput').on('input', function() {
        const val = $(this).val().replace(/[^0-9]/g, '');
        let prefix = '';
        if (val.startsWith('0')) {
            prefix = val.substring(1, 3);
        } else if (val.startsWith('255')) {
            prefix = val.substring(3, 5);
        }

        let op = 'MPESA';
        if (['74', '75', '76'].includes(prefix)) {
            op = 'MPESA';
        } else if (['65', '67', '71', '77'].includes(prefix)) {
            op = 'MIXX';
        } else if (['68', '69', '78'].includes(prefix)) {
            op = 'AIRTEL';
        } else if (['62', '61'].includes(prefix)) {
            op = 'HALOPESA';
        }
        $('.ak23-clean-op-box').removeClass('active');
        $('.ak23-clean-op-box[data-operator="' + op + '"]').addClass('active');
        $('#selectedOperator').val(op);
    });

    // Toggle email edit
    $('#btnToggleEmailEdit').on('click', function() {
        $('#emailEditContainer').slideToggle(150);
    });

    // Sync edited email to hidden input
    $('#emailInput').on('input', function() {
        const emailVal = $(this).val();
        $('#emailHiddenInput').val(emailVal);
        $('#displayEmail').text(emailVal);
    });

    // Card formatters
    $('#cardNumberInput').on('input', function() {
        let val = $(this).val().replace(/[^0-9]/g, '');
        let formatted = val.match(/.{1,4}/g)?.join(' ') || val;
        $(this).val(formatted);
    });

    $('#cardExpInput').on('input', function() {
        let val = $(this).val().replace(/[^0-9]/g, '');
        if (val.length >= 2) {
            val = val.substring(0, 2) + ' / ' + val.substring(2, 4);
        }
        $(this).val(val);
    });

    // Delegate notice card switch
    $(document).on('click', '#btnNoticeSwitchCard', function() {
        switchMethod('card');
    });

    // Submit handler
    $('#checkoutForm').on('submit', function(e) {
        e.preventDefault();

        const activeSubmitBtn = $(this).find('button[type="submit"]:visible');
        const originalText = activeSubmitBtn.html();
        activeSubmitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Processing...');
        $('#checkoutNoticeArea').empty();

        const formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            headers: { 
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest' 
            },
            success: function(res) {
                activeSubmitBtn.prop('disabled', false).html(originalText);

                if (res.success) {
                    if (res.payment_method === 'card') {
                        activeSubmitBtn.html('<i class="las la-check-circle text-success me-1"></i> Authorized! Redirecting...');
                        notify('success', res.message || 'Payment authorized successfully!');
                        setTimeout(function() {
                            window.location.href = res.redirect_url;
                        }, 800);
                    } else {
                        if (res.payment_gateway_url) {
                            window.location.href = res.payment_gateway_url;
                        } else {
                            notify('success', res.message);
                            $('#checkoutNoticeArea').html(`
                                <div class="p-3 rounded-3 mb-3" style="background: #ECFDF5; border: 1.5px solid #A7F3D0;">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="las la-check-circle text-success fs-18"></i>
                                        <strong class="text-success fs-13">USSD Push Dispatched via Selcom</strong>
                                    </div>
                                    <p class="text-dark fs-12 mb-0">${res.message}</p>
                                </div>
                            `);
                        }
                    }
                }
            },
            error: function(xhr) {
                activeSubmitBtn.prop('disabled', false).html(originalText);
                const resp = xhr.responseJSON || {};
                const msg = resp.message || 'Failed to process checkout.';

                if (xhr.status === 401 || (resp && resp.need_login)) {
                    notify('info', resp.message || 'Please log in or register to complete your order.');
                    setTimeout(function() {
                        window.location.href = resp.redirect_url || "{{ route('user.login') }}";
                    }, 600);
                    return;
                }

                if (resp.is_ip_error || (resp.resultcode && resp.resultcode == '403')) {
                    $('#checkoutNoticeArea').html(`
                        <div class="p-3 rounded-3 mb-3" style="background: #FEF2F2; border: 1.5px solid #FECACA;">
                            <div class="d-flex align-items-start gap-2">
                                <i class="las la-exclamation-circle text-danger fs-18 mt-1"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <strong class="text-danger fs-13">Selcom Notice: Server IP Whitelisting Required</strong>
                                        <span class="badge bg-danger text-white px-2 py-1 fs-10">Live API 403</span>
                                    </div>
                                    <p class="text-dark fs-12 mb-2">
                                        Selcom reported: <code>${msg}</code>. Push notifications require this server's public IP to be registered in your Selcom portal.
                                    </p>
                                    <button type="button" class="btn btn-sm btn-warning fw-bold fs-12 py-1 px-3" id="btnNoticeSwitchCard">
                                        <i class="las la-credit-card me-1"></i> Switch to Card Payment (Instant Delivery) &rarr;
                                    </button>
                                </div>
                            </div>
                        </div>
                    `);
                    notify('error', 'Selcom: ' + msg);
                } else {
                    notify('error', msg);
                    $('#checkoutNoticeArea').html(`
                        <div class="alert alert-danger p-3 rounded-3 mb-3 fs-13">
                            <strong>Payment Error:</strong> ${msg}
                        </div>
                    `);
                }
            }
        });
    });

    // Toggle Promo Input on Checkout
    $('#btnToggleCheckoutPromo').on('click', function() {
        const collapse = $('#checkoutPromoCollapse');
        const icon = $('#promoToggleIcon');
        if (collapse.hasClass('d-none')) {
            collapse.removeClass('d-none');
            icon.removeClass('la-angle-down').addClass('la-angle-up');
            $('#checkoutPromoInput').focus();
        } else {
            collapse.addClass('d-none');
            icon.removeClass('la-angle-up').addClass('la-angle-down');
        }
    });

    // Apply Promo Code on Checkout in Real Time
    $('#btnApplyCheckoutPromo').on('click', function() {
        const code = $('#checkoutPromoInput').val().trim();
        const btn = $(this);
        const msgBox = $('#checkoutPromoMsg');

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
                    $('#checkoutSubtotalDisplay').text(res.formatted_subtotal);
                    $('#checkoutDiscountDisplay').text(res.formatted_discount);
                    $('#checkoutCouponBadge').text(res.coupon_code);
                    $('#checkoutDiscountRow').removeClass('d-none');
                    $('#checkoutTotalDisplay').text(res.formatted_total);
                    $('.ak23-btn-pay-amount').text(res.formatted_total.replace('TZS ', ''));

                    $('#checkoutActiveCodeText').text(res.coupon_code);
                    $('#checkoutActiveCouponRow').removeClass('d-none');
                    $('#checkoutPromoCollapse').addClass('d-none');
                    $('#promoToggleIcon').removeClass('la-angle-up').addClass('la-angle-down');

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

    // Remove Promo Code on Checkout in Real Time
    $('#btnRemoveCheckoutPromo').on('click', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('cart.remove.coupon') }}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || "{{ csrf_token() }}",
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(res) {
                $('#checkoutDiscountRow').addClass('d-none');
                $('#checkoutActiveCouponRow').addClass('d-none');
                $('#checkoutSubtotalDisplay').text(res.formatted_subtotal);
                $('#checkoutTotalDisplay').text(res.formatted_total);
                $('.ak23-btn-pay-amount').text(res.formatted_total.replace('TZS ', ''));
                $('#checkoutPromoInput').val('');
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
