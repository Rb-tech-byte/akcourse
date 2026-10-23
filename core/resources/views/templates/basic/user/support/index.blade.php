@extends($activeTemplate . 'layouts.portal')

@section('content')

<!-- =========================================================================
     HERO BANNER: SUPPORT TICKETS
     ========================================================================= -->
<div class="ak23-portal-hero">
    <div class="ak23-portal-hero-left">
        <div class="ak23-portal-hero-tag">
            <i class="las la-headset me-1"></i> STUDENT SUPPORT &amp; HELP DESK &middot; {{ strtoupper(auth()->user()->username ?? 'STUDENT') }}
        </div>
        <h1 class="ak23-portal-hero-title">Support Inquiries &amp; Tickets</h1>
        <p class="ak23-portal-hero-sub">
            Direct communication channel with AK23 audio engineers, instructors, and technical specialists for licensing, video downloads, and course questions.
        </p>
    </div>
    <a href="{{ route('ticket.open') }}" class="ak23-portal-hero-cta">
        <i class="las la-plus-circle"></i>
        <span>Open New Ticket</span>
    </a>
</div>

<!-- =========================================================================
     STAT CARDS (4 TILES)
     ========================================================================= -->
<div class="ak23-portal-stats-grid">
    <!-- Stat 1: Total -->
    <div class="ak23-portal-stat-card">
        <div class="ak23-portal-stat-icon" style="background: rgba(59, 130, 246, 0.12); color: #3B82F6;">
            <i class="las la-ticket-alt"></i>
        </div>
        <div>
            <div class="ak23-portal-stat-val">{{ $totalTickets ?? $supports->total() }}</div>
            <p class="ak23-portal-stat-label">Total Inquiries</p>
        </div>
    </div>

    <!-- Stat 2: Active / Open -->
    <div class="ak23-portal-stat-card">
        <div class="ak23-portal-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #10B981;">
            <i class="las la-envelope-open-text"></i>
        </div>
        <div>
            <div class="ak23-portal-stat-val text-success">{{ $openTickets ?? 0 }}</div>
            <p class="ak23-portal-stat-label">Open &amp; Pending</p>
        </div>
    </div>

    <!-- Stat 3: Answered -->
    <div class="ak23-portal-stat-card">
        <div class="ak23-portal-stat-icon" style="background: rgba(251, 175, 67, 0.12); color: #E29828;">
            <i class="las la-reply"></i>
        </div>
        <div>
            <div class="ak23-portal-stat-val text-warning">{{ $answeredTickets ?? 0 }}</div>
            <p class="ak23-portal-stat-label">Staff Answered</p>
        </div>
    </div>

    <!-- Stat 4: Closed -->
    <div class="ak23-portal-stat-card">
        <div class="ak23-portal-stat-icon" style="background: rgba(100, 116, 139, 0.12); color: #64748B;">
            <i class="las la-check-circle"></i>
        </div>
        <div>
            <div class="ak23-portal-stat-val">{{ $closedTickets ?? 0 }}</div>
            <p class="ak23-portal-stat-label">Resolved Tickets</p>
        </div>
    </div>
</div>

<!-- =========================================================================
     TICKETS LIST TABLE CARD
     ========================================================================= -->
<div class="row">
    <div class="col-12">
        <div class="ak23-portal-card">
            <div class="ak23-portal-card-header">
                <div>
                    <h2 class="ak23-portal-card-title">All Support Inquiries</h2>
                    <p class="ak23-portal-card-sub">Track active conversations and review responses from the AK23 technical staff.</p>
                </div>
                <a href="{{ route('ticket.open') }}" class="btn btn--base btn--sm">
                    <i class="las la-plus me-1"></i> New Inquiry
                </a>
            </div>

            @if ($supports->count() > 0)
                <div class="table-responsive">
                    <table class="ak23-orders-table">
                        <thead>
                            <tr>
                                <th>Ticket / Subject</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Last Reply</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($supports as $support)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-light text-dark font-monospace border fs-11">#{{ $support->ticket }}</span>
                                            <a href="{{ route('ticket.view', $support->ticket) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ __($support->subject) }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($support->priority == Status::PRIORITY_LOW)
                                            <span class="badge bg-light text-muted border">Low</span>
                                        @elseif($support->priority == Status::PRIORITY_MEDIUM)
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Medium</span>
                                        @elseif($support->priority == Status::PRIORITY_HIGH)
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">High Priority</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($support->status == Status::TICKET_OPEN)
                                            <span class="ak23-badge-delivered">
                                                <i class="las la-dot-circle me-1"></i> Open
                                            </span>
                                        @elseif($support->status == Status::TICKET_ANSWER)
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                                <i class="las la-reply me-1"></i> Answered
                                            </span>
                                        @elseif($support->status == Status::TICKET_REPLY)
                                            <span class="ak23-badge-pending">
                                                <i class="las la-clock me-1"></i> Customer Reply
                                            </span>
                                        @elseif($support->status == Status::TICKET_CLOSE)
                                            <span class="badge bg-light text-muted border px-2 py-1">
                                                <i class="las la-check me-1"></i> Closed
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">
                                            <i class="las la-clock me-1"></i> {{ diffForHumans($support->last_reply) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('ticket.view', $support->ticket) }}" class="btn btn-outline--base btn--sm">
                                            <i class="las la-comments me-1"></i> View Conversation
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($supports->hasPages())
                    <div class="pt-4 border-top mt-3">
                        {{ paginateLinks($supports) }}
                    </div>
                @endif
            @else
                <div class="text-center py-5 px-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 68px; height: 68px; background: #F1F5F9; color: #94A3B8; font-size: 32px;">
                        <i class="las la-headset"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">No Support Tickets Yet</h5>
                    <p class="text-muted fs-13 mb-4 mx-auto" style="max-width: 480px;">
                        Have questions about course files, stem downloads, DAW project templates, or software licenses? Our technical support desk is available to assist you.
                    </p>
                    <a href="{{ route('ticket.open') }}" class="btn btn--base px-4">
                        <i class="las la-plus me-1"></i> Open Your First Support Ticket
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
