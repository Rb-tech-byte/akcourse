@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="row justify-content-center gy-4 my-4">
    <div class="col-lg-7 col-md-10">
        <div class="card custom--card" style="background: #17181F; border: 1px solid rgba(251, 175, 67, 0.25); border-radius: 14px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            <div class="card-header d-flex align-items-center justify-content-between p-4" style="background: rgba(255,255,255,0.02); border-bottom: 1px solid rgba(255,255,255,0.07);">
                <div class="d-flex align-items-center gap-3">
                    @if(@$deposit->gateway->image)
                        <img src="{{ getImage(getFilePath('gateway') . '/' . $deposit->gateway->image) }}" 
                             alt="{{ __($deposit->gateway->name) }}" 
                             style="max-height: 42px; border-radius: 8px; background: #fff; padding: 4px;">
                    @else
                        <div style="width: 42px; height: 42px; border-radius: 8px; background: rgba(251,175,67,0.15); color: #FBAF43; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                            <i class="las la-wallet font-24"></i>
                        </div>
                    @endif
                    <div>
                        <h5 class="card-title text-white mb-0" style="font-weight: 700;">
                            {{ __(@$deposit->gateway->name ?? 'Payment Gateway') }}
                        </h5>
                        <small class="text-muted">@lang('Secure Automated Checkout')</small>
                    </div>
                </div>
                <span class="badge" style="background: rgba(251, 175, 67, 0.15); color: #FBAF43; border: 1px solid rgba(251, 175, 67, 0.3); padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                    <i class="las la-shield-alt me-1"></i> @lang('Encrypted')
                </span>
            </div>

            <div class="card-body p-4 p-md-5">
                <!-- Amount Payable Hero Box -->
                <div class="p-4 rounded-3 text-center mb-4" style="background: linear-gradient(135deg, rgba(251, 175, 67, 0.08) 0%, rgba(255, 255, 255, 0.02) 100%); border: 1px solid rgba(251, 175, 67, 0.2);">
                    <span class="text-uppercase text-muted" style="font-size: 11px; letter-spacing: 1.5px; font-weight: 600;">
                        @lang('TOTAL AMOUNT PAYABLE')
                    </span>
                    <h2 class="my-2" style="color: #FBAF43; font-weight: 800; font-size: 34px;">
                        {{ showAmount($deposit->final_amount, currencyFormat: false) }} 
                        <span style="font-size: 20px; color: #fff;">{{ __($deposit->method_currency) }}</span>
                    </h2>
                    @if($deposit->method_currency != gs('cur_text'))
                        <div class="text-muted small">
                            <i class="las la-exchange-alt"></i> 1 {{ gs('cur_text') }} = {{ showAmount($deposit->rate, currencyFormat: false) }} {{ __($deposit->method_currency) }}
                        </div>
                    @endif
                </div>

                <!-- Transaction Reference Box -->
                <div class="mb-4 p-3 rounded-2 d-flex align-items-center justify-content-between" style="background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255,255,255,0.06);">
                    <div>
                        <div class="text-muted small text-uppercase" style="font-size: 10px; letter-spacing: 1px;">@lang('ORDER REFERENCE / TRX CODE')</div>
                        <div class="text-white font-weight-bold" style="font-family: monospace; font-size: 16px; letter-spacing: 1px;">
                            #{{ $deposit->trx }}
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-light copy-trx-btn" data-trx="{{ $deposit->trx }}" style="border-radius: 6px;" title="@lang('Copy Transaction Reference')">
                        <i class="las la-copy me-1"></i> @lang('Copy')
                    </button>
                </div>

                @php
                    $params = (array) (@$data->params ?? []);
                @endphp

                <!-- Gateway Merchant / Account Information -->
                @if(!empty($params))
                    <div class="mb-4">
                        <h6 class="text-white mb-3" style="font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="las la-info-circle me-1" style="color: #FBAF43;"></i> @lang('Payment Instructions & Details')
                        </h6>
                        <div class="list-group list-group-flush rounded-3" style="border: 1px solid rgba(255,255,255,0.08); overflow: hidden;">
                            @foreach($params as $key => $val)
                                @if(!empty($val) && !in_array($key, ['secret_key', 'private_key', 'api_secret', 'password', 'passkey', 'auth_token']))
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-3 py-2" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05); color: #ccc;">
                                        <span class="text-capitalize small font-weight-bold">{{ __(keyToTitle($key)) }}</span>
                                        <span class="text-white font-weight-bold" style="font-family: monospace;">{{ $val }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(@$deposit->gateway->description)
                    <div class="alert alert-info p-3 mb-4" style="background: rgba(251, 175, 67, 0.08); border: 1px solid rgba(251, 175, 67, 0.2); color: #ddd; border-radius: 8px;">
                        {!! __($deposit->gateway->description) !!}
                    </div>
                @endif

                <!-- Payment Confirmation Form -->
                <form action="{{ route('user.deposit.dynamic.verify', $deposit->trx) }}" method="POST">
                    @csrf
                    <div class="form-group mb-4">
                        <label class="form-label text-white small" style="letter-spacing: 0.5px; font-weight: 600;">
                            @lang('Transaction ID / Mobile Money Reference Code') <span class="text-muted">(@lang('Optional'))</span>
                        </label>
                        <input type="text" name="user_reference" class="form-control" placeholder="e.g. 9JA20KLP89 / Receipt number" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: #fff; padding: 12px 14px; border-radius: 8px;">
                        <small class="text-muted d-block mt-1">
                            @lang('After sending payment from your mobile wallet or bank, click confirm below to verify.')
                        </small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-lg w-100 py-3 text-dark font-weight-bold" style="background: #FBAF43; border: none; border-radius: 8px; font-weight: 700; letter-spacing: 0.5px; box-shadow: 0 4px 15px rgba(251,175,67,0.3);">
                            <i class="las la-check-circle me-1"></i> @lang('I Have Paid - Confirm Payment')
                        </button>
                        <a href="{{ route('user.deposit.index') }}" class="btn btn-link text-muted mt-2 text-decoration-none">
                            <i class="las la-arrow-left me-1"></i> @lang('Choose Another Payment Method')
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    (function($) {
        "use strict";
        $('.copy-trx-btn').on('click', function() {
            var trx = $(this).data('trx');
            var temp = $("<input>");
            $("body").append(temp);
            temp.val(trx).select();
            document.execCommand("copy");
            temp.remove();
            $(this).html('<i class="las la-check me-1"></i> @lang("Copied!")');
            var $btn = $(this);
            setTimeout(function() {
                $btn.html('<i class="las la-copy me-1"></i> @lang("Copy")');
            }, 2000);
        });
    })(jQuery);
</script>
@endpush
@endsection
