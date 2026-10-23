@extends($activeTemplate . 'layouts.portal')

@section('content')

<!-- =========================================================================
     HERO BANNER: TRANSACTIONS
     ========================================================================= -->
<div class="ak23-portal-hero">
    <div class="ak23-portal-hero-left">
        <div class="ak23-portal-hero-tag">
            <i class="las la-wallet me-1"></i> ACCOUNT STATEMENTS &middot; {{ strtoupper(auth()->user()->username ?? 'STUDENT') }}
        </div>
        <h1 class="ak23-portal-hero-title">Transaction History</h1>
        <p class="ak23-portal-hero-sub">
            Review detailed financial records, course purchase debits, gateway deposits, and account balance logs.
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="ak23-portal-hero-cta showFilterBtn" style="border: none;">
            <i class="las la-filter"></i>
            <span>Filter Transactions</span>
        </button>
    </div>
</div>

<!-- =========================================================================
     FILTER ACCORDION CARD
     ========================================================================= -->
<div class="row mb-4">
    <div class="col-12">
        <div class="ak23-portal-card responsive-filter-card" style="display: {{ request()->all() ? 'block' : 'none' }};">
            <div class="ak23-portal-card-header mb-3">
                <h3 class="ak23-portal-card-title fs-15"><i class="las la-filter me-1 text-warning"></i> Search &amp; Filter Logs</h3>
                <button type="button" class="btn btn-sm btn-outline-secondary showFilterBtn"><i class="las la-times"></i> Close</button>
            </div>
            <form>
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fs-12 fw-bold text-dark mb-1">@lang('Transaction Number / Trx')</label>
                        <input type="text" name="search" value="{{ request()->search }}" class="form-control" placeholder="Search TRX code...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fs-12 fw-bold text-dark mb-1">@lang('Type')</label>
                        <select name="trx_type" class="form-select select2" data-minimum-results-for-search="-1">
                            <option value="">@lang('All Transactions')</option>
                            <option value="+" @selected(request()->trx_type == '+')>@lang('Plus (+)')</option>
                            <option value="-" @selected(request()->trx_type == '-')>@lang('Minus (-)')</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fs-12 fw-bold text-dark mb-1">@lang('Remark')</label>
                        <select class="form-select select2" name="remark" data-minimum-results-for-search="-1">
                            <option value="">@lang('Any Category')</option>
                            @foreach ($remarks as $remark)
                                <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>
                                    {{ __(keyToTitle($remark->remark)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button class="btn btn--base w-100 py-2"><i class="las la-search me-1"></i> @lang('Filter')</button>
                        <a href="{{ route('user.transactions') }}" class="btn btn-outline-secondary py-2" title="Reset"><i class="las la-redo"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =========================================================================
     TRANSACTIONS TABLE
     ========================================================================= -->
<div class="row">
    <div class="col-12">
        <div class="ak23-portal-card">
            <div class="ak23-portal-card-header">
                <div>
                    <h2 class="ak23-portal-card-title">All Statements</h2>
                    <p class="ak23-portal-card-sub">Real-time ledger updates reflecting payments, deposits, and refunds.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="ak23-orders-table">
                    <thead>
                        <tr>
                            <th>@lang('Transaction Code')</th>
                            <th>@lang('Date & Time')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Post Balance')</th>
                            <th>@lang('Details / Remarks')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                            <tr>
                                <td>
                                    <span class="font-monospace fw-bold text-dark fs-12">#{{ $trx->trx }}</span>
                                </td>
                                <td>
                                    <div class="fs-12 text-dark">{{ showDateTime($trx->created_at, 'M d, Y @ h:i A') }}</div>
                                    <small class="text-muted fs-11">{{ diffForHumans($trx->created_at) }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold fs-13 @if ($trx->trx_type == '+') text-success @else text-danger @endif">
                                        {{ $trx->trx_type }} {{ showAmount($trx->amount) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark fs-13">{{ showAmount($trx->post_balance) }}</span>
                                </td>
                                <td>
                                    <span class="fs-13 text-secondary">{{ __($trx->details) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center py-5" colspan="100%">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 52px; height: 52px; background: #F1F5F9; color: #94A3B8; font-size: 24px;">
                                        <i class="las la-receipt"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">No Transactions Found</h6>
                                    <p class="text-muted fs-12 mb-0">Your account does not have any recorded transaction entries matching the search criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($transactions->hasPages())
                <div class="pt-4 border-top mt-3">
                    {{ paginateLinks($transactions) }}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    "use strict";
    (function($) {
        $('.showFilterBtn').on('click', function() {
            $('.responsive-filter-card').slideToggle(200);
        });
    })(jQuery);
</script>
@endpush
