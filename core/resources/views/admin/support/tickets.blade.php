@extends('admin.layouts.app')

@section('panel')
<div class="row gy-4">

    <!-- 4 Native Compact Metric Cards -->
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">All Support Tickets</span>
                        <h4 class="metric-val text-white mt-1">{{ number_format($counts['all']) }}</h4>
                        <span class="badge bg-dark text-gold mt-1">Total Service Desk Volume</span>
                    </div>
                    <div class="metric-icon-wrap gold">
                        <i class="las la-headset"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Priority Escalations: <strong class="text-danger">{{ $counts['urgent'] }}</strong></span>
                    <span>System: <strong class="text-success">Live</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Pending Attention</span>
                        <h4 class="metric-val text-warning mt-1">{{ number_format($counts['pending']) }}</h4>
                        <span class="badge bg-warning-subtle text-warning mt-1">Awaiting Response</span>
                    </div>
                    <div class="metric-icon-wrap yellow">
                        <i class="las la-clock"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Queue Status: <strong class="{{ $counts['pending'] > 0 ? 'text-warning' : 'text-success' }}">{{ $counts['pending'] > 0 ? 'Action Needed' : 'Cleared' }}</strong></span>
                    <span>SLA: <strong class="text-white">Active</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Answered Tickets</span>
                        <h4 class="metric-val text-white mt-1">{{ number_format($counts['answered']) }}</h4>
                        <span class="badge bg-primary mt-1">Agent Replied</span>
                    </div>
                    <div class="metric-icon-wrap blue">
                        <i class="las la-reply-all"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Status: <strong class="text-white">Customer Pending</strong></span>
                    <span>Resolution: <strong class="text-success">In Progress</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Resolved &amp; Closed</span>
                        <h4 class="metric-val text-success mt-1">{{ number_format($counts['closed']) }}</h4>
                        <span class="badge bg-success-subtle text-success mt-1">Issue Resolved</span>
                    </div>
                    <div class="metric-icon-wrap green">
                        <i class="las la-check-circle"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Resolution Rate: <strong class="text-white">{{ $counts['all'] > 0 ? round(($counts['closed'] / $counts['all']) * 100, 1) : 0 }}%</strong></span>
                    <span>State: <strong class="text-success">Archived</strong></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs & Table Card -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <!-- Filter Tabs Header -->
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom flex-wrap gap-3 py-3">
                <div class="ticket-tab-group d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.ticket.index') }}" class="ticket-tab-pill {{ $activeTab == 'all' ? 'active' : '' }}">
                        <i class="las la-inbox"></i> @lang('All Tickets')
                        <span class="count-badge">{{ number_format($counts['all']) }}</span>
                    </a>
                    <a href="{{ route('admin.ticket.pending') }}" class="ticket-tab-pill {{ $activeTab == 'pending' ? 'active' : '' }}">
                        <i class="las la-hourglass-half"></i> @lang('Pending Attention')
                        <span class="count-badge warning">{{ number_format($counts['pending']) }}</span>
                    </a>
                    <a href="{{ route('admin.ticket.answered') }}" class="ticket-tab-pill {{ $activeTab == 'answered' ? 'active' : '' }}">
                        <i class="las la-reply"></i> @lang('Answered')
                        <span class="count-badge info">{{ number_format($counts['answered']) }}</span>
                    </a>
                    <a href="{{ route('admin.ticket.closed') }}" class="ticket-tab-pill {{ $activeTab == 'closed' ? 'active' : '' }}">
                        <i class="las la-check-double"></i> @lang('Closed')
                        <span class="count-badge success">{{ number_format($counts['closed']) }}</span>
                    </a>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <x-search-form placeholder="Search ticket #, subject, customer..." />
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>@lang('Ticket Details')</th>
                                <th>@lang('Customer / Requester')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Priority')</th>
                                <th>@lang('Last Activity')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="ticket-code-chip">#{{ $item->ticket }}</span>
                                            <div>
                                                <a href="{{ route('admin.ticket.view', $item->id) }}" class="fw-bold text-white fs-13 d-block text-truncate" style="max-width: 320px;" title="{{ $item->subject }}">
                                                    {{ $item->subject }}
                                                </a>
                                                <span class="fs-11 text-muted">Created {{ showDateTime($item->created_at, 'd M, Y') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->user_id && $item->user)
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar-sm">
                                                    {{ strtoupper(substr($item->user->username, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <a href="{{ route('admin.users.detail', $item->user_id) }}" class="fw-bold text-white fs-13 d-block">
                                                        {{ $item->user->fullname }}
                                                    </a>
                                                    <span class="fs-11 text-muted">@<span>{{ $item->user->username }}</span></span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar-sm bg-dark">
                                                    <i class="las la-user-secret text-gold"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-white fs-13 d-block">{{ $item->name }}</span>
                                                    <span class="fs-11 text-muted">{{ $item->email }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @php echo $item->statusBadge; @endphp
                                    </td>
                                    <td>
                                        @if($item->priority == Status::PRIORITY_LOW)
                                            <span class="badge bg-dark text-muted fs-11">
                                                <i class="las la-arrow-down"></i> @lang('Low')
                                            </span>
                                        @elseif($item->priority == Status::PRIORITY_MEDIUM)
                                            <span class="badge bg-warning-subtle text-warning fs-11">
                                                <i class="las la-minus"></i> @lang('Medium')
                                            </span>
                                        @elseif($item->priority == Status::PRIORITY_HIGH)
                                            <span class="badge bg-danger-subtle text-danger fs-11 fw-bold">
                                                <i class="las la-exclamation-triangle"></i> @lang('High Priority')
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="d-block fs-12 text-white">{{ diffForHumans($item->last_reply) }}</span>
                                        <span class="fs-11 text-muted">{{ showDateTime($item->last_reply, 'h:i A') }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.ticket.view', $item->id) }}" class="btn btn-sm btn-outline-gold">
                                            <i class="las la-reply"></i> @lang('Reply & Manage')
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="las la-ticket-alt text-gold fs-24 d-block mb-2"></i>
                                        @lang('No support tickets found in this queue.')
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($items->hasPages())
                <div class="card-footer bg-transparent py-3 border-top border-dark-subtle">
                    {{ paginateLinks($items) }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.ticket.index') }}" class="btn btn-outline--primary btn-sm"><i class="las la-inbox"></i> @lang('All Tickets')</a>
    <a href="{{ route('admin.ticket.pending') }}" class="btn btn-outline--warning btn-sm"><i class="las la-clock"></i> @lang('Pending Tickets')</a>
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
.metric-icon-wrap.yellow { background: rgba(236, 201, 75, 0.12); color: #ecc94b; }
.metric-icon-wrap.blue { background: rgba(49, 130, 206, 0.12); color: #3182ce; }
.metric-icon-wrap.green { background: rgba(56, 161, 105, 0.12); color: #38a169; }

.ticket-tab-pill {
    padding: 6px 14px;
    border-radius: 50px;
    background: #181a20;
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: #a0aec0;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
}
.ticket-tab-pill:hover {
    border-color: rgba(251, 175, 67, 0.4);
    color: #ffffff;
}
.ticket-tab-pill.active {
    background: rgba(251, 175, 67, 0.12);
    border-color: var(--upgrader-gold, #FBAF43);
    color: var(--upgrader-gold, #FBAF43);
}
.ticket-tab-pill .count-badge {
    padding: 2px 7px;
    border-radius: 50px;
    background: rgba(255, 255, 255, 0.08);
    font-size: 10px;
    font-weight: 700;
    color: #ffffff;
}
.ticket-tab-pill .count-badge.warning { background: rgba(236, 201, 75, 0.25); color: #ecc94b; }
.ticket-tab-pill .count-badge.info { background: rgba(49, 130, 206, 0.25); color: #63b3ed; }
.ticket-tab-pill .count-badge.success { background: rgba(56, 161, 105, 0.25); color: #68d391; }

.ticket-code-chip {
    padding: 3px 8px;
    border-radius: 6px;
    background: #12141a;
    border: 1px solid rgba(251, 175, 67, 0.3);
    font-family: 'Consolas', monospace;
    font-size: 11px;
    font-weight: 700;
    color: #FBAF43;
}
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
