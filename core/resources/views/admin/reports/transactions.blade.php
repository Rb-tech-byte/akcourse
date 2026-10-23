@extends('admin.layouts.app')

@section('panel')
<div class="row gy-4">

    <!-- 4 Native Compact Metric Cards -->
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Total Transactions</span>
                        <h4 class="metric-val text-white mt-1">{{ number_format($totalCount) }}</h4>
                        <span class="badge bg-dark text-gold mt-1">Audit Log Entries</span>
                    </div>
                    <div class="metric-icon-wrap gold">
                        <i class="las la-receipt"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Active Accounts: <strong class="text-white">{{ number_format($uniqueUsersCount) }}</strong></span>
                    <span>Status: <strong class="text-success">Live</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Total Credit Inflow (+)</span>
                        <h4 class="metric-val text-success mt-1">+{{ showAmount($totalCredit) }}</h4>
                        <span class="badge bg-success-subtle text-success mt-1">Credit Operations</span>
                    </div>
                    <div class="metric-icon-wrap green">
                        <i class="las la-arrow-down"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Currency: <strong class="text-white">{{ __(gs('cur_text')) }}</strong></span>
                    <span>Type: <strong class="text-success">Credit (+)</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Total Debit Outflow (-)</span>
                        <h4 class="metric-val text-danger mt-1">-{{ showAmount($totalDebit) }}</h4>
                        <span class="badge bg-danger-subtle text-danger mt-1">Debit Operations</span>
                    </div>
                    <div class="metric-icon-wrap red">
                        <i class="las la-arrow-up"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Net Balance: <strong class="{{ $netFlow >= 0 ? 'text-success' : 'text-danger' }}">{{ showAmount($netFlow) }}</strong></span>
                    <span>Type: <strong class="text-danger">Debit (-)</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Transacting Users</span>
                        <h4 class="metric-val text-white mt-1">{{ number_format($uniqueUsersCount) }}</h4>
                        <span class="badge bg-info mt-1">Verified Customers</span>
                    </div>
                    <div class="metric-icon-wrap blue">
                        <i class="las la-users"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Scope: <strong class="text-white">{{ $userId ? 'Single User' : 'Global Platform' }}</strong></span>
                    <span>Integrity: <strong class="text-success">100%</strong></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="col-12">
        <div class="card border-0 shadow-sm ak23-filter-card">
            <div class="card-body p-3">
                <form action="" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fs-12 text-muted mb-1">@lang('TRX ID or Username')</label>
                            <input type="search" name="search" value="{{ request()->search }}" class="form-control form-control-sm" placeholder="@lang('Search TRX ID, username, email...')">
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <label class="form-label fs-12 text-muted mb-1">@lang('Type')</label>
                            <select name="trx_type" class="form-control form-control-sm select2" data-minimum-results-for-search="-1">
                                <option value="">@lang('All Types')</option>
                                <option value="+" @selected(request()->trx_type == '+')>@lang('Credit (+)')</option>
                                <option value="-" @selected(request()->trx_type == '-')>@lang('Debit (-)')</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <label class="form-label fs-12 text-muted mb-1">@lang('Remark Category')</label>
                            <select class="form-control form-control-sm select2" data-minimum-results-for-search="-1" name="remark">
                                <option value="">@lang('All Remarks')</option>
                                @foreach($remarks as $remark)
                                    <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>{{ __(keyToTitle($remark->remark)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label class="form-label fs-12 text-muted mb-1">@lang('Date Range')</label>
                            <input name="date" type="search" class="datepicker-here form-control form-control-sm bg--white pe-2 date-range" placeholder="@lang('Start - End Date')" autocomplete="off" value="{{ request()->date }}">
                        </div>
                        <div class="col-lg-2 col-md-6 d-flex gap-2">
                            <button class="btn btn-gold btn-sm w-100"><i class="las la-filter"></i> @lang('Filter')</button>
                            <a href="{{ route('admin.report.transaction') }}" class="btn btn-outline-secondary btn-sm" title="@lang('Reset')"><i class="las la-redo"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Transactions Table Card -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom">
                <h6 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="las la-list text-gold"></i> @lang('Recorded Transaction Logs')
                </h6>
                <span class="badge bg-dark text-gold fs-11">Page {{ $transactions->currentPage() }} of {{ $transactions->lastPage() }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>@lang('Customer / User')</th>
                                <th>@lang('TRX Reference')</th>
                                <th>@lang('Timestamp')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Post Balance')</th>
                                <th>@lang('Remark & Details')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                                <tr>
                                    <td>
                                        @if($trx->user)
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar-sm">
                                                    {{ strtoupper(substr($trx->user->username, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <a href="{{ route('admin.users.detail', $trx->user_id) }}" class="fw-bold text-white fs-13 d-block">
                                                        {{ $trx->user->fullname }}
                                                    </a>
                                                    <span class="fs-11 text-muted">@<span>{{ $trx->user->username }}</span></span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="badge bg-dark text-muted">@lang('System / Deleted')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <code class="fw-bold fs-12 text-gold">{{ $trx->trx }}</code>
                                    </td>
                                    <td>
                                        <span class="d-block fs-12 text-white">{{ showDateTime($trx->created_at, 'd M, Y - h:i A') }}</span>
                                        <span class="fs-11 text-muted">{{ diffForHumans($trx->created_at) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold fs-13 {{ $trx->trx_type == '+' ? 'text-success' : 'text-danger' }}">
                                            {{ $trx->trx_type }} {{ showAmount($trx->amount) }} {{ __(gs('cur_text')) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fs-12 text-white fw-semibold">{{ showAmount($trx->post_balance) }} {{ __(gs('cur_text')) }}</span>
                                    </td>
                                    <td>
                                        @if($trx->remark)
                                            <span class="badge bg-dark text-gold fs-11 text-capitalize me-1">{{ keyToTitle($trx->remark) }}</span>
                                        @endif
                                        <span class="fs-12 text-muted">{{ __($trx->details) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="las la-receipt text-gold fs-24 d-block mb-2"></i>
                                        @lang('No transaction records found matching the criteria.')
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($transactions->hasPages())
                <div class="card-footer bg-transparent py-3 border-top border-dark-subtle">
                    {{ paginateLinks($transactions) }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/datepicker.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('style')
<style>
.ak23-metric-card, .ak23-filter-card {
    background: #181a20;
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 10px;
}
.metric-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #a0aec0;
    font-weight: 600;
}
.metric-val {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0;
}
.metric-icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}
.metric-icon-wrap.gold { background: rgba(251, 175, 67, 0.12); color: #FBAF43; }
.metric-icon-wrap.green { background: rgba(56, 161, 105, 0.12); color: #38a169; }
.metric-icon-wrap.red { background: rgba(229, 62, 62, 0.12); color: #e53e3e; }
.metric-icon-wrap.blue { background: rgba(49, 130, 206, 0.12); color: #3182ce; }

.user-avatar-sm {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #252a37;
    border: 1px solid rgba(251, 175, 67, 0.3);
    color: #FBAF43;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
}
</style>
@endpush

@push('script')
<script>
    (function($){
        "use strict";
        if(!$('.datepicker-here').val()){
            $('.datepicker-here').datepicker();
        }
    })(jQuery)
</script>
@endpush
