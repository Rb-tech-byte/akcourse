@extends($activeTemplate . 'layouts.portal')

@section('content')

<!-- =========================================================================
     HERO BANNER: OPEN TICKET
     ========================================================================= -->
<div class="ak23-portal-hero">
    <div class="ak23-portal-hero-left">
        <div class="ak23-portal-hero-tag">
            <i class="las la-headset me-1"></i> STUDENT SUPPORT &amp; HELP DESK &middot; {{ strtoupper(auth()->user()->username ?? 'STUDENT') }}
        </div>
        <h1 class="ak23-portal-hero-title">Open Support Ticket</h1>
        <p class="ak23-portal-hero-sub">
            Submit a direct inquiry to AK23 sound engineers, instructors, and technical specialists. Attach project stems, screenshots, or logs for expedited support.
        </p>
    </div>
    <a href="{{ route('ticket.index') }}" class="ak23-portal-hero-cta" style="background: rgba(255, 255, 255, 0.12); color: #FFFFFF !important; border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: none;">
        <i class="las la-list"></i>
        <span>All Tickets</span>
    </a>
</div>

<!-- =========================================================================
     CREATE TICKET FORM
     ========================================================================= -->
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="ak23-portal-card">
            <div class="ak23-portal-card-header">
                <div>
                    <h2 class="ak23-portal-card-title">Inquiry Details</h2>
                    <p class="ak23-portal-card-sub">Please provide specific details so our technical engineers can assist you swiftly.</p>
                </div>
                <span class="ak23-badge-delivered">
                    <i class="las la-bolt me-1"></i> Fast Dispatch
                </span>
            </div>

            <form action="{{ route('ticket.store') }}" class="disableSubmission" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold text-dark fs-13 mb-1">
                            @lang('Subject') <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="subject" value="{{ old('subject') }}" class="form-control" placeholder="e.g. Issue downloading Course 2 Stem Stems / License key" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark fs-13 mb-1">
                            @lang('Priority') <span class="text-danger">*</span>
                        </label>
                        <select name="priority" class="form-select select2" data-minimum-results-for-search="-1" required>
                            <option value="3">@lang('High Priority (Urgent)')</option>
                            <option value="2" selected>@lang('Medium Priority (Standard)')</option>
                            <option value="1">@lang('Low Priority (General Question)')</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold text-dark fs-13 mb-1">
                            @lang('Message / Inquiry Description') <span class="text-danger">*</span>
                        </label>
                        <textarea name="message" id="inputMessage" rows="7" class="form-control" placeholder="Describe your question or technical challenge in detail..." required>{{ old('message') }}</textarea>
                    </div>

                    <div class="col-12 pt-2 border-top mt-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                            <div>
                                <label class="form-label fw-bold text-dark fs-13 mb-0">
                                    <i class="las la-paperclip me-1"></i> @lang('Attachments (Screenshots, DAW Crash Logs, Receipts)')
                                </label>
                                <div class="text-muted fs-11">
                                    @lang('Max 5 files | Max upload size: '.convertToReadableSize(ini_get('upload_max_filesize')) .' | Allowed: .jpg, .jpeg, .png, .pdf, .doc, .docx')
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn--sm addAttachment">
                                <i class="las la-plus me-1"></i> @lang('Add Another File')
                            </button>
                        </div>

                        <div class="row g-2 fileUploadsContainer mt-2">
                            <div class="col-md-6 removeFileInput">
                                <div class="input-group">
                                    <input type="file" name="attachments[]" class="form-control form-control-sm" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx">
                                    <button type="button" class="btn btn-outline-danger btn-sm removeFile" title="Remove"><i class="las la-times"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 pt-4 border-top mt-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <a href="{{ route('ticket.index') }}" class="btn btn-outline-secondary px-4">
                            @lang('Cancel')
                        </a>
                        <button class="btn btn--base px-4 py-2" type="submit">
                            <i class="las la-paper-plane me-1"></i> @lang('Submit Support Ticket')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('style')
<style>
    .ak23-portal-card .form-control,
    .ak23-portal-card .form-select {
        border-radius: 8px;
        border: 1px solid #CBD5E1;
        padding: 9px 14px;
        font-size: 13.5px;
    }
    .ak23-portal-card .form-control:focus,
    .ak23-portal-card .form-select:focus {
        border-color: #FBAF43;
        box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.18);
    }
</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        var fileAdded = 1;
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