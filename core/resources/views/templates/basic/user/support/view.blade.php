@php
    $extends = auth()->check() ? 'portal' : 'frontend';
    $section = 'content';
@endphp

@extends($activeTemplate . "layouts.$extends")

@section($section)

@guest
<section class="py-120">
    <div class="container">
@endguest

<!-- =========================================================================
     HERO BANNER: TICKET DETAILS
     ========================================================================= -->
<div class="ak23-portal-hero">
    <div class="ak23-portal-hero-left">
        <div class="ak23-portal-hero-tag">
            <i class="las la-headset me-1"></i> TICKET #{{ $myTicket->ticket }} &middot;
            @if ($myTicket->priority == Status::PRIORITY_LOW)
                LOW PRIORITY
            @elseif($myTicket->priority == Status::PRIORITY_MEDIUM)
                MEDIUM PRIORITY
            @elseif($myTicket->priority == Status::PRIORITY_HIGH)
                HIGH PRIORITY
            @endif
        </div>
        <h1 class="ak23-portal-hero-title fs-28">{{ $myTicket->subject }}</h1>
        <p class="ak23-portal-hero-sub">
            Opened on {{ showDateTime($myTicket->created_at, 'M d, Y @ h:i A') }} &middot; Last updated {{ diffForHumans($myTicket->last_reply) }}
        </p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('ticket.index') }}" class="ak23-portal-hero-cta" style="background: rgba(255, 255, 255, 0.12); color: #FFFFFF !important; border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: none;">
            <i class="las la-list"></i>
            <span>Ticket List</span>
        </a>
        @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
            <button class="btn btn-outline-danger btn--sm px-3 py-2 confirmationBtn" type="button" data-question="@lang('Are you sure to close this ticket?')" data-action="{{ route('ticket.close', $myTicket->id) }}">
                <i class="las la-times-circle me-1"></i> @lang('Close Ticket')
            </button>
        @endif
    </div>
</div>

<!-- =========================================================================
     TICKET STATUS STRIP
     ========================================================================= -->
<div class="row mb-4">
    <div class="col-12">
        <div class="p-3 rounded-3 border d-flex align-items-center justify-content-between flex-wrap gap-3 bg-white shadow-xs">
            <div class="d-flex align-items-center gap-3">
                <span class="fs-13 fw-bold text-dark">Ticket Status:</span>
                @if ($myTicket->status == Status::TICKET_OPEN)
                    <span class="ak23-badge-delivered fs-12">
                        <i class="las la-dot-circle me-1"></i> Open &amp; Awaiting Response
                    </span>
                @elseif($myTicket->status == Status::TICKET_ANSWER)
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-12 px-3 py-1">
                        <i class="las la-reply me-1"></i> Staff Answered
                    </span>
                @elseif($myTicket->status == Status::TICKET_REPLY)
                    <span class="ak23-badge-pending fs-12">
                        <i class="las la-clock me-1"></i> Customer Replied
                    </span>
                @elseif($myTicket->status == Status::TICKET_CLOSE)
                    <span class="badge bg-light text-muted border fs-12 px-3 py-1">
                        <i class="las la-lock me-1"></i> Closed
                    </span>
                @endif
            </div>

            <div class="d-flex align-items-center gap-3 text-muted fs-12">
                <span><i class="las la-user me-1"></i> {{ $myTicket->name }}</span>
                <span><i class="las la-envelope me-1"></i> {{ $myTicket->email }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- =========================================================================
         REPLY FORM CARD (IF TICKET OPEN)
         ========================================================================= -->
    @if ($myTicket->status != Status::TICKET_CLOSE)
        <div class="col-12">
            <div class="ak23-portal-card">
                <div class="ak23-portal-card-header">
                    <div>
                        <h2 class="ak23-portal-card-title">Send a Reply</h2>
                        <p class="ak23-portal-card-sub">Post additional questions, updates, or attach project logs to this conversation.</p>
                    </div>
                </div>

                <form method="post" class="disableSubmission" action="{{ route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <textarea name="message" class="form-control" rows="4" placeholder="Write your reply here..." required>{{ old('message') }}</textarea>
                        </div>

                        <div class="col-12 pt-2 border-top mt-2">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                <div>
                                    <label class="form-label fw-bold text-dark fs-13 mb-0">
                                        <i class="las la-paperclip me-1"></i> @lang('Attachments')
                                    </label>
                                    <div class="text-muted fs-11">
                                        @lang('Max 5 files | Max upload: '.convertToReadableSize(ini_get('upload_max_filesize')) .' | Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-secondary btn--sm addAttachment">
                                    <i class="las la-plus me-1"></i> @lang('Add Attachment')
                                </button>
                            </div>

                            <div class="row g-2 fileUploadsContainer mt-2"></div>
                        </div>

                        <div class="col-12 pt-3 border-top d-flex justify-content-end">
                            <button class="btn btn--base px-4 py-2" type="submit">
                                <i class="las la-reply me-1"></i> @lang('Post Reply')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="col-12">
            <div class="p-4 rounded-3 border bg-light text-center">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 48px; height: 48px; background: #E2E8F0; color: #475569; font-size: 24px;">
                    <i class="las la-lock"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">This Ticket is Closed</h6>
                <p class="text-muted fs-13 mb-3 mx-auto" style="max-width: 480px;">
                    This conversation has been marked as resolved and replies are locked. If you need further help, please create a new support inquiry.
                </p>
                <a href="{{ route('ticket.open') }}" class="btn btn--base btn--sm px-3">
                    <i class="las la-plus me-1"></i> Open New Ticket
                </a>
            </div>
        </div>
    @endif

    <!-- =========================================================================
         CONVERSATION TIMELINE
         ========================================================================= -->
    <div class="col-12">
        <div class="ak23-portal-card">
            <div class="ak23-portal-card-header">
                <div>
                    <h2 class="ak23-portal-card-title">Conversation History</h2>
                    <p class="ak23-portal-card-sub">Messages exchanged between you and the AK23 technical staff.</p>
                </div>
                <span class="badge bg-light text-muted border">{{ count($messages) }} {{ Str::plural('Message', count($messages)) }}</span>
            </div>

            <div class="d-flex flex-column gap-3 mt-2">
                @foreach ($messages as $message)
                    @if ($message->admin_id == 0)
                        <!-- Student Message -->
                        <div class="p-3 p-md-4 rounded-3 border bg-white shadow-xs" style="border-left: 4px solid var(--portal-gold) !important;">
                            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold fs-12 text-dark" style="width: 32px; height: 32px; background: #FEF3C7;">
                                        {{ strtoupper(substr($message->ticket->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold fs-14 text-dark">{{ $message->ticket->name }}</h6>
                                        <span class="text-muted fs-11">Student</span>
                                    </div>
                                </div>
                                <span class="text-muted fs-12">
                                    <i class="las la-clock me-1"></i> {{ $message->created_at->format('M d, Y @ h:i A') }} ({{ diffForHumans($message->created_at) }})
                                </span>
                            </div>

                            <div class="text-secondary fs-13 lh-base mt-2" style="white-space: pre-wrap;">{{ $message->message }}</div>

                            @if ($message->attachments->count() > 0)
                                <div class="mt-3 pt-3 border-top">
                                    <span class="fs-12 fw-bold text-dark d-block mb-2"><i class="las la-paperclip me-1"></i>Attachments:</span>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($message->attachments as $k => $image)
                                            <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="btn btn-outline-secondary btn-sm fs-12">
                                                <i class="las la-file-download me-1"></i> Attachment {{ ++$k }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Staff Message -->
                        <div class="p-3 p-md-4 rounded-3 border shadow-xs" style="background: #F8FAFC; border-left: 4px solid #3B82F6 !important;">
                            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold fs-12 text-white" style="width: 32px; height: 32px; background: #2563EB;">
                                        AK
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6 class="mb-0 fw-bold fs-14 text-dark">{{ $message->admin->name ?? 'AK23 Technical Support' }}</h6>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-11">Staff</span>
                                        </div>
                                        <span class="text-muted fs-11">AK23 Audio Support Team</span>
                                    </div>
                                </div>
                                <span class="text-muted fs-12">
                                    <i class="las la-clock me-1"></i> {{ $message->created_at->format('M d, Y @ h:i A') }} ({{ diffForHumans($message->created_at) }})
                                </span>
                            </div>

                            <div class="text-dark fs-13 lh-base mt-2" style="white-space: pre-wrap;">{{ $message->message }}</div>

                            @if ($message->attachments->count() > 0)
                                <div class="mt-3 pt-3 border-top">
                                    <span class="fs-12 fw-bold text-dark d-block mb-2"><i class="las la-paperclip me-1"></i>Attachments:</span>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($message->attachments as $k => $image)
                                            <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="btn btn-outline-primary btn-sm fs-12">
                                                <i class="las la-file-download me-1"></i> Attachment {{ ++$k }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

@guest
    </div>
</section>
@endguest

<x-confirmation-modal frontend="true" btnClass="btn--md" />

@endsection

@push('style')
<style>
    .ak23-portal-card .form-control {
        border-radius: 8px;
        border: 1px solid #CBD5E1;
        padding: 9px 14px;
        font-size: 13.5px;
    }
    .ak23-portal-card .form-control:focus {
        border-color: #FBAF43;
        box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.18);
    }
</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        var fileAdded = 0;
        $('.addAttachment').on('click', function(){
            if (fileAdded >= 5) {
                return;
            }
            fileAdded++;
            if (fileAdded >= 5) {
                $(this).attr('disabled', true);
            }
            $(".fileUploadsContainer").append(`
                <div class="col-md-6 removeFileInput">
                    <div class="input-group">
                        <input type="file" name="attachments[]" class="form-control form-control-sm" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                        <button type="button" class="btn btn-outline-danger btn-sm removeFile" title="Remove"><i class="las la-times"></i></button>
                    </div>
                </div>
            `);
        });

        $(document).on('click', '.removeFile', function(){
            $('.addAttachment').removeAttr('disabled');
            fileAdded--;
            $(this).closest('.removeFileInput').remove();
        });
    })(jQuery);
</script>
@endpush