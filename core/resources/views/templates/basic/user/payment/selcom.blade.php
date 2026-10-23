@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="ak23-checkout-wrapper py-5">
    <div class="container" style="max-width: 650px;">
        <div class="ak23-mobile-money-card text-center p-4 p-md-5">
            <div class="ak23-phone-icon-box mx-auto mb-3">
                <i class="las la-mobile-alt fs-24 text-primary"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">Selcom Mobile Money Checkout</h3>
            <p class="text-muted fs-14 mb-4">Lipa kwa M-Pesa, Mixx (Tigo), Airtel Money au HaloPesa.</p>

            <div class="card bg-light border-0 p-3 mb-4 rounded-3 text-start">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Transaction ID:</span>
                    <strong class="text-dark">{{ $deposit->trx }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Total Amount:</span>
                    <strong class="text-primary fs-16">TZS {{ number_format($deposit->final_amount) }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Target Phone:</span>
                    <strong class="text-dark">{{ $deposit->detail['phone'] ?? $deposit->user->mobile }}</strong>
                </div>
            </div>

            <div class="ak23-push-badge mb-4">
                <span class="spinner-border spinner-border-sm text-success me-2" role="status"></span>
                <span class="ak23-push-text">PUSH USSD SENT TO PHONE</span>
            </div>

            <p class="text-muted fs-13 mb-4">
                Tafadhali ingiza PIN yako kwenye simu kukamilisha malipo. Baada ya kuweka PIN, bonyeza kitufe hapa chini kuthibitisha.
            </p>

            <button type="button" class="btn ak23-btn-pay w-100 mb-3" id="btnCheckStatus">
                <i class="las la-sync me-2"></i> Check Payment Status
            </button>

            <a href="{{ route('order.checkout') }}" class="btn btn-link text-muted fs-13">
                &larr; Back to Order
            </a>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    $('#btnCheckStatus').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Checking...');

        $.ajax({
            url: "{{ route('order.check_status', ['trx' => $deposit->trx]) }}",
            method: 'GET',
            success: function(res) {
                if (res.paid) {
                    notify('success', 'Payment verified successfully!');
                    setTimeout(function() {
                        window.location.href = res.redirect_url;
                    }, 1000);
                } else {
                    btn.prop('disabled', false).html('<i class="las la-sync me-2"></i> Check Payment Status');
                    notify('info', res.message || 'Payment is still pending user PIN authorization.');
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="las la-sync me-2"></i> Check Payment Status');
                notify('error', 'Network error. Please try again.');
            }
        });
    });
});
</script>
@endpush
