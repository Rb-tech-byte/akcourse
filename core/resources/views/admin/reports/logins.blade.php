@extends('admin.layouts.app')

@section('panel')
<div class="row gy-4">

    <!-- 4 Native Compact Metric Cards -->
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Total Login Sessions</span>
                        <h4 class="metric-val text-white mt-1">{{ number_format($totalLogins) }}</h4>
                        <span class="badge bg-dark text-gold mt-1">Audit Trail Logins</span>
                    </div>
                    <div class="metric-icon-wrap gold">
                        <i class="las la-sign-in-alt"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Scope: <strong class="text-white">{{ isset($ip) ? 'IP ' . $ip : 'Global' }}</strong></span>
                    <span>Status: <strong class="text-success">Tracking Active</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Unique Users</span>
                        <h4 class="metric-val text-white mt-1">{{ number_format($uniqueUsers) }}</h4>
                        <span class="badge bg-primary mt-1">Active Accounts</span>
                    </div>
                    <div class="metric-icon-wrap blue">
                        <i class="las la-user-check"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Authentication: <strong class="text-white">Session Guard</strong></span>
                    <span>Security: <strong class="text-success">Verified</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Distinct IP Addresses</span>
                        <h4 class="metric-val text-white mt-1">{{ number_format($uniqueIps) }}</h4>
                        <span class="badge bg-info mt-1">Geo-Location Nodes</span>
                    </div>
                    <div class="metric-icon-wrap green">
                        <i class="las la-network-wired"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Lookup: <strong class="text-white">IPv4 / IPv6</strong></span>
                    <span>Anonymity: <strong class="text-white">Audited</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Leading Client Environment</span>
                        <h4 class="metric-val text-white mt-1">{{ @$topBrowser->browser ?? 'Chrome' }}</h4>
                        <span class="badge bg-purple mt-1">{{ @$topOs->os ?? 'Windows' }} OS</span>
                    </div>
                    <div class="metric-icon-wrap purple">
                        <i class="las la-laptop"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>User-Agent: <strong class="text-white">Parsed</strong></span>
                    <span>Fingerprint: <strong class="text-success">Valid</strong></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Logins Table Card -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="card-title mb-0 d-flex align-items-center gap-2">
                        <i class="las la-user-clock text-gold"></i> @lang('User Authentication Audit History')
                    </h6>
                    <span class="badge bg-dark text-gold fs-11">Page {{ $loginLogs->currentPage() }} of {{ $loginLogs->lastPage() }}</span>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    @if(request()->routeIs('admin.report.login.history'))
                        <x-search-form placeholder="Search username, email, IP, city..." dateSearch='yes' />
                    @elseif(isset($ip))
                        <a href="{{ route('admin.report.login.history') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="las la-arrow-left"></i> @lang('All Logins')
                        </a>
                        <a href="https://www.ip2location.com/{{ $ip }}" target="_blank" class="btn btn-outline-gold btn-sm">
                            <i class="las la-external-link-alt"></i> @lang('Lookup IP') {{ $ip }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>@lang('Customer / User')</th>
                                <th>@lang('Login Timestamp')</th>
                                <th>@lang('IP Address')</th>
                                <th>@lang('Geo Location')</th>
                                <th>@lang('Browser & Platform OS')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loginLogs as $log)
                                <tr>
                                    <td>
                                        @if($log->user)
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar-sm">
                                                    {{ strtoupper(substr($log->user->username, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <a href="{{ route('admin.users.detail', $log->user_id) }}" class="fw-bold text-white fs-13 d-block">
                                                        {{ $log->user->fullname }}
                                                    </a>
                                                    <span class="fs-11 text-muted">@<span>{{ $log->user->username }}</span></span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="badge bg-dark text-muted">@lang('Deleted Account')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="d-block fs-12 text-white">{{ showDateTime($log->created_at, 'd M, Y - h:i A') }}</span>
                                        <span class="fs-11 text-muted">{{ diffForHumans($log->created_at) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.report.login.ipHistory', [$log->user_ip]) }}" class="badge bg-dark border border-gold text-gold fs-12 text-decoration-none" title="@lang('Filter by this IP')">
                                            <i class="las la-globe-americas"></i> {{ $log->user_ip }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="fs-13 text-white fw-semibold d-block">
                                            {{ $log->city ? __($log->city) : __('Local') }}
                                        </span>
                                        <span class="fs-11 text-muted">
                                            <i class="las la-map-marker-alt text-gold"></i> {{ $log->country ? __($log->country) : __('Network Gateway') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-dark text-white fs-11 me-1">
                                            <i class="lab la-chrome text-gold"></i> {{ $log->browser ? __($log->browser) : __('Browser') }}
                                        </span>
                                        <span class="badge bg-dark-subtle text-muted fs-11">
                                            <i class="lab la-windows"></i> {{ $log->os ? __($log->os) : __('OS') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="las la-user-slash text-gold fs-24 d-block mb-2"></i>
                                        @lang('No login history records found.')
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($loginLogs->hasPages())
                <div class="card-footer bg-transparent py-3 border-top border-dark-subtle">
                    {{ paginateLinks($loginLogs) }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

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
