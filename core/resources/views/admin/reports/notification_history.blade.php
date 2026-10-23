@extends('admin.layouts.app')

@section('panel')
<div class="row gy-4">

    <!-- 4 Native Compact Metric Cards -->
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Total Dispatched</span>
                        <h4 class="metric-val text-white mt-1">{{ number_format($totalNotifications) }}</h4>
                        <span class="badge bg-dark text-gold mt-1">Multi-Channel Alerts</span>
                    </div>
                    <div class="metric-icon-wrap gold">
                        <i class="las la-bullhorn"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Today: <strong class="text-white">{{ number_format($todayCount) }}</strong></span>
                    <span>Status: <strong class="text-success">Live Queue</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Email Transmissions</span>
                        <h4 class="metric-val text-white mt-1">{{ number_format($emailCount) }}</h4>
                        <span class="badge bg-primary mt-1">SMTP &amp; Mailgun</span>
                    </div>
                    <div class="metric-icon-wrap blue">
                        <i class="las la-envelope"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Channel Share: <strong class="text-white">{{ $totalNotifications > 0 ? round(($emailCount / $totalNotifications) * 100, 1) : 0 }}%</strong></span>
                    <span>Protocol: <strong class="text-white">HTML/MIME</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">SMS Dispatches</span>
                        <h4 class="metric-val text-white mt-1">{{ number_format($smsCount) }}</h4>
                        <span class="badge bg-success mt-1">Twilio &amp; Nexmo</span>
                    </div>
                    <div class="metric-icon-wrap green">
                        <i class="las la-sms"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Channel Share: <strong class="text-white">{{ $totalNotifications > 0 ? round(($smsCount / $totalNotifications) * 100, 1) : 0 }}%</strong></span>
                    <span>Gateway: <strong class="text-white">Active</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Push &amp; In-App Alerts</span>
                        <h4 class="metric-val text-white mt-1">{{ number_format($pushCount) }}</h4>
                        <span class="badge bg-purple mt-1">FCM &amp; WebSockets</span>
                    </div>
                    <div class="metric-icon-wrap purple">
                        <i class="las la-bell"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Channel Share: <strong class="text-white">{{ $totalNotifications > 0 ? round(($pushCount / $totalNotifications) * 100, 1) : 0 }}%</strong></span>
                    <span>Delivery: <strong class="text-success">Instant</strong></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Table Card -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="card-title mb-0 d-flex align-items-center gap-2">
                        <i class="las la-history text-gold"></i> @lang('Notification Audit Records')
                    </h6>
                    <span class="badge bg-dark text-gold fs-11">Page {{ $logs->currentPage() }} of {{ $logs->lastPage() }}</span>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <x-search-form placeholder="Search recipient, sender, subject..." dateSearch='yes' />
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>@lang('Recipient / User')</th>
                                <th>@lang('Channel & Sender')</th>
                                <th>@lang('Subject / Title')</th>
                                <th>@lang('Sent At')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
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
                                                    <span class="fs-11 text-muted">@<span>{{ $log->user->username }}</span> &bull; {{ $log->sent_to }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar-sm bg-dark">
                                                    <i class="las la-globe text-gold"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-white fs-13 d-block">@lang('Direct / Guest')</span>
                                                    <span class="fs-11 text-muted">{{ $log->sent_to }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->notification_type == 'email')
                                            <span class="badge bg-primary-subtle text-primary mb-1">
                                                <i class="las la-envelope"></i> @lang('Email')
                                            </span>
                                        @elseif($log->notification_type == 'sms')
                                            <span class="badge bg-success-subtle text-success mb-1">
                                                <i class="las la-sms"></i> @lang('SMS')
                                            </span>
                                        @else
                                            <span class="badge bg-purple-subtle text-purple mb-1">
                                                <i class="las la-bell"></i> @lang('Push')
                                            </span>
                                        @endif
                                        <br>
                                        <span class="fs-11 text-muted">via {{ __($log->sender) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-white fs-13 d-block">
                                            {{ $log->subject ? __($log->subject) : __('System Notification') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="d-block fs-12 text-white">{{ showDateTime($log->created_at, 'd M, Y - h:i A') }}</span>
                                        <span class="fs-11 text-muted">{{ diffForHumans($log->created_at) }}</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-gold notifyDetail" 
                                            data-type="{{ $log->notification_type }}" 
                                            @if($log->notification_type == 'email') 
                                                data-message="{{ route('admin.report.email.details', $log->id) }}" 
                                            @else 
                                                data-message="{{ $log->message }}" 
                                                @if($log->image) data-image="{{ asset(getFilePath('push') . '/' . $log->image) }}" @endif 
                                            @endif 
                                            data-sent_to="{{ $log->sent_to }}">
                                            <i class="las la-eye"></i> @lang('Inspect')
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="las la-bell-slash text-gold fs-24 d-block mb-2"></i>
                                        @lang('No notification logs recorded yet.')
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($logs->hasPages())
                <div class="card-footer bg-transparent py-3 border-top border-dark-subtle">
                    {{ paginateLinks($logs) }}
                </div>
            @endif
        </div>
    </div>

</div>

<!-- Inspection Modal -->
<div class="modal fade" id="notifyDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content ak23-modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="modal-icon-badge">
                        <i class="las la-envelope-open-text text-gold"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white mb-0">@lang('Dispatched Message Preview')</h5>
                        <p class="text-muted fs-12 mb-0">Sent to: <span class="sent_to text-gold fw-bold"></span></p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <div class="detail border border-dark rounded p-3 bg-darker" style="min-height: 200px;"></div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
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

.bg-purple { background-color: #805ad5 !important; color: #fff !important; }
.bg-purple-subtle { background-color: rgba(128, 90, 213, 0.15) !important; }
.text-purple { color: #b794f4 !important; }

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
.ak23-modal-content {
    background: #181a20;
    border: 1px solid rgba(251, 175, 67, 0.25);
    border-radius: 12px;
}
.modal-icon-badge {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: rgba(251, 175, 67, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}
.bg-darker { background: #12141a !important; }
</style>
@endpush

@push('script')
<script>
    (function($) {
        "use strict";
        $('.notifyDetail').on('click', function() {
            var message = '';
            if ($(this).data('image')) {
                message += '<img src="' + $(this).data('image') + '" class="w-100 mb-2 rounded" alt="image">';
            }
            message += $(this).data('message');
            var sent_to = $(this).data('sent_to');
            var modal = $('#notifyDetailModal');
            if ($(this).data('type') == 'email') {
                var iframe = '<iframe src="' + message + '" height="500" width="100%" class="border-0 rounded" title="Email Preview"></iframe>';
                modal.find('.detail').html(iframe);
            } else {
                modal.find('.detail').html('<div class="p-2 text-white fs-13 lh-base">' + message + '</div>');
            }
            modal.find('.sent_to').text(sent_to);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush
