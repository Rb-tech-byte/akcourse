@extends('admin.layouts.app')

@section('panel')
<div class="row gy-4">

    <!-- 4 Native Compact Metric Cards -->
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Total Subscribers</span>
                        <h4 class="metric-val text-white mt-1">{{ number_format($totalSubscribers) }}</h4>
                        <span class="badge bg-dark text-gold mt-1">Newsletter Audience</span>
                    </div>
                    <div class="metric-icon-wrap gold">
                        <i class="las la-envelope-open"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Audience Reach: <strong class="text-white">Active</strong></span>
                    <span>Status: <strong class="text-success">Verified</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Registered Members</span>
                        <h4 class="metric-val text-white mt-1">{{ number_format($registeredCount) }}</h4>
                        <span class="badge bg-primary mt-1">Linked User Accounts</span>
                    </div>
                    <div class="metric-icon-wrap blue">
                        <i class="las la-user-check"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Member Ratio: <strong class="text-white">{{ $totalSubscribers > 0 ? round(($registeredCount / $totalSubscribers) * 100, 1) : 0 }}%</strong></span>
                    <span>Conversion: <strong class="text-success">High</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Guest Subscribers</span>
                        <h4 class="metric-val text-white mt-1">{{ number_format($guestCount) }}</h4>
                        <span class="badge bg-info mt-1">Lead Prospects</span>
                    </div>
                    <div class="metric-icon-wrap green">
                        <i class="las la-user-clock"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Prospect Ratio: <strong class="text-white">{{ $totalSubscribers > 0 ? round(($guestCount / $totalSubscribers) * 100, 1) : 0 }}%</strong></span>
                    <span>Targeting: <strong class="text-white">Email Only</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">30-Day New Signups</span>
                        <h4 class="metric-val text-success mt-1">+{{ number_format($recentGrowth) }}</h4>
                        <span class="badge bg-success-subtle text-success mt-1">Monthly Growth</span>
                    </div>
                    <div class="metric-icon-wrap purple">
                        <i class="las la-chart-line"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Velocity: <strong class="text-white">Active</strong></span>
                    <span>Retention: <strong class="text-success">98%</strong></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscribers Table Card -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="card-title mb-0 d-flex align-items-center gap-2">
                        <i class="las la-mail-bulk text-gold"></i> @lang('Subscriber Registry')
                    </h6>
                    <span class="badge bg-dark text-gold fs-11">Page {{ $subscribers->currentPage() }} of {{ $subscribers->lastPage() }}</span>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <form action="" method="GET" class="d-flex gap-2">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="search" name="search" class="form-control" placeholder="@lang('Search subscriber email...')" value="{{ request()->search }}">
                            <button class="btn btn-gold"><i class="las la-search"></i></button>
                        </div>
                    </form>
                    <a href="{{ route('admin.subscriber.send.email') }}" class="btn btn-gold btn-sm">
                        <i class="las la-paper-plane"></i> @lang('Send Broadcast Email')
                    </a>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>@lang('Subscriber Email')</th>
                                <th>@lang('Account Status (DB Join)')</th>
                                <th>@lang('Subscription Date')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscribers as $subscriber)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="subscriber-icon-wrap">
                                                <i class="las la-envelope text-gold"></i>
                                            </div>
                                            <div>
                                                <span class="fw-bold text-white fs-13 d-block">{{ $subscriber->email }}</span>
                                                <span class="fs-11 text-muted">ID: #{{ $subscriber->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($subscriber->registered_user_id)
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="badge bg-primary-subtle text-primary">
                                                    <i class="las la-user-check"></i> @lang('Registered Member')
                                                </span>
                                                <a href="{{ route('admin.users.detail', $subscriber->registered_user_id) }}" class="fs-12 text-white fw-semibold ms-1" title="@lang('View Profile')">
                                                    {{ $subscriber->firstname }} {{ $subscriber->lastname }} (@<span>{{ $subscriber->username }}</span>)
                                                </a>
                                            </div>
                                        @else
                                            <span class="badge bg-dark text-muted">
                                                <i class="las la-user-secret"></i> @lang('Guest Visitor')
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="d-block fs-12 text-white">{{ showDateTime($subscriber->created_at, 'd M, Y - h:i A') }}</span>
                                        <span class="fs-11 text-muted">{{ diffForHumans($subscriber->created_at) }}</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                            data-question="@lang('Are you sure you want to permanently remove this subscriber from the mailing list?')"
                                            data-action="{{ route('admin.subscriber.remove', $subscriber->id) }}">
                                            <i class="las la-trash"></i> @lang('Remove')
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="las la-envelope-open-text text-gold fs-24 d-block mb-2"></i>
                                        @lang('No subscribers found in registry.')
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($subscribers->hasPages())
                <div class="card-footer bg-transparent py-3 border-top border-dark-subtle">
                    {{ paginateLinks($subscribers) }}
                </div>
            @endif
        </div>
    </div>

</div>

<x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.subscriber.send.email') }}" class="btn btn-sm btn-outline--primary"><i class="las la-paper-plane"></i> @lang('Send Email')</a>
@endpush

@push('style')
<style>
.ak23-metric-card {
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
    color: #ffffff;
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
.metric-icon-wrap.blue { background: rgba(49, 130, 206, 0.12); color: #3182ce; }
.metric-icon-wrap.green { background: rgba(56, 161, 105, 0.12); color: #38a169; }
.metric-icon-wrap.purple { background: rgba(128, 90, 213, 0.12); color: #805ad5; }

.subscriber-icon-wrap {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #252a37;
    border: 1px solid rgba(251, 175, 67, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}
</style>
@endpush
