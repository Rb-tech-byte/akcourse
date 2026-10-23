@extends('admin.layouts.app')
@section('panel')

    <!-- Print Header (Visible ONLY on PDF / Print Export) -->
    <div class="d-none d-print-block mb-3 p-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1 text-dark fw-bold">{{ gs('site_name') }} &middot; {{ __($pageTitle) }} Report</h3>
                <p class="text-muted mb-0 small">Export generated on {{ now()->format('F d, Y - H:i:s') }} &middot; Filter: {{ request()->search ? 'Search: '.request()->search : 'All' }}</p>
            </div>
            <div class="text-end">
                <span class="badge bg-dark text-white px-3 py-2 fs-12">Total Students: {{ $users->total() }}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Main First Card (Compact, Responsive, Native) -->
            <div class="card ak23-compact-card border shadow-sm">
                <!-- Card Header with Actions & Export Tools -->
                <div class="card-header bg-white py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="las la-user-graduate text-primary fs-20"></i>
                        <h6 class="card-title mb-0 fs-15 fw-bold text-dark">{{ __($pageTitle) }}</h6>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill fs-11">
                            {{ $users->total() }} Students (4 / page)
                        </span>
                    </div>

                    <div class="d-flex align-items-center flex-wrap gap-2 d-print-none">
                        <!-- Export PDF Button -->
                        <button type="button" class="btn btn-sm btn-outline-danger px-2 py-1 fs-12" onclick="ak23PrintReport('{{ __($pageTitle) }}')">
                            <i class="las la-file-pdf"></i> <span class="d-none d-sm-inline">Export PDF</span>
                        </button>
                        <!-- Export CSV Button -->
                        <button type="button" class="btn btn-sm btn-outline-success px-2 py-1 fs-12" onclick="ak23ExportCsv('usersTable', 'students-export')">
                            <i class="las la-file-excel"></i> <span class="d-none d-sm-inline">Export CSV</span>
                        </button>
                    </div>
                </div>

                <!-- Integrated Compact Filter Bar -->
                <div class="card-body bg-light border-bottom p-2 p-md-3 d-print-none">
                    <form method="GET" action="" id="usersFilterForm">
                        <div class="row g-2 align-items-end">
                            <!-- Search -->
                            <div class="col-12 col-sm-6 col-lg-5">
                                <label class="form-label fs-11 fw-bold text-muted mb-1">@lang('Search Name / Username / Email')</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="las la-search text-muted"></i></span>
                                    <input type="search" name="search" value="{{ request()->search }}" class="form-control form-control-sm border-start-0" placeholder="Username, email, phone..." autocomplete="off">
                                </div>
                            </div>

                            <!-- Date Range -->
                            <div class="col-6 col-sm-3 col-lg-3">
                                <label class="form-label fs-11 fw-bold text-muted mb-1">@lang('Joined Date')</label>
                                <input name="date" type="text" class="datepicker-here form-control form-control-sm bg-white date-range" placeholder="Date range" value="{{ request()->date }}" autocomplete="off">
                            </div>

                            <!-- Per Page Selector (Default 4) -->
                            <div class="col-6 col-sm-3 col-lg-2">
                                <label class="form-label fs-11 fw-bold text-muted mb-1">@lang('Per Page')</label>
                                <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="4" @selected(request()->get('per_page', 4) == 4)>4</option>
                                    <option value="8" @selected(request()->get('per_page') == 8)>8</option>
                                    <option value="12" @selected(request()->get('per_page') == 12)>12</option>
                                    <option value="20" @selected(request()->get('per_page') == 20)>20</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-12 col-sm-12 col-lg-2 d-flex gap-1">
                                <button type="submit" class="btn btn-sm btn--primary flex-grow-1 py-1 fs-12">
                                    <i class="las la-filter"></i> @lang('Filter')
                                </button>
                                <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary py-1 fs-12" title="Reset filters">
                                    <i class="las la-undo"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Compact Table -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle mb-0 ak23-compact-table" id="usersTable">
                            <thead class="table-light fs-12 text-uppercase text-muted border-bottom">
                                <tr>
                                    <th class="ps-3 py-2" style="min-width: 220px;">@lang('Student Name')</th>
                                    <th class="py-2">@lang('Email & Contact')</th>
                                    <th class="py-2">@lang('Country')</th>
                                    <th class="py-2">@lang('Joined Date')</th>
                                    <th class="py-2">@lang('Balance')</th>
                                    <th class="text-end pe-3 py-2 no-export d-print-none">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="fs-13">
                                @forelse($users as $user)
                                    <tr>
                                        <!-- User / Student -->
                                        <td class="ps-3 py-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar avatar-sm rounded-circle bg-light border text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 13px;">
                                                    {{ strtoupper(substr($user->firstname ?? $user->username, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark d-block">{{ $user->fullname }}</span>
                                                    <a href="{{ route('admin.users.detail', $user->id) }}" class="text-primary fs-11 text-decoration-none">
                                                        <span>@</span>{{ $user->username }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Email & Mobile -->
                                        <td class="py-2">
                                            <span class="text-dark">{{ $user->email }}</span>
                                            <div class="fs-11 text-muted">{{ $user->mobileNumber ?? 'No mobile' }}</div>
                                        </td>

                                        <!-- Country -->
                                        <td class="py-2">
                                            <span class="badge bg-light text-dark border px-2 py-1 fs-11">
                                                {{ $user->country_code ?? 'TZ' }} &middot; {{ @$user->country_name ?? 'Tanzania' }}
                                            </span>
                                        </td>

                                        <!-- Joined At -->
                                        <td class="py-2">
                                            <span class="text-dark">{{ showDateTime($user->created_at, 'd M, Y') }}</span>
                                            <div class="fs-11 text-muted">{{ diffForHumans($user->created_at) }}</div>
                                        </td>

                                        <!-- Balance -->
                                        <td class="py-2">
                                            <span class="fw-bold text-dark">{{ showAmount($user->balance) }}</span>
                                        </td>

                                        <!-- Action -->
                                        <td class="text-end pe-3 py-2 no-export d-print-none">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.users.detail', $user->id) }}" class="btn btn-outline-primary py-1 px-2 fs-12">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
                                                @if (request()->routeIs('admin.users.kyc.pending'))
                                                    <a href="{{ route('admin.users.kyc.details', $user->id) }}" target="_blank" class="btn btn-outline-dark py-1 px-2 fs-12">
                                                        <i class="las la-user-check"></i> @lang('KYC')
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center py-4" colspan="100%">
                                            <i class="las la-user-slash fs-2 text-secondary d-block mb-1"></i>
                                            @lang('No students found matching criteria')
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Compact Pagination Footer (4 items per page default) -->
                @if ($users->hasPages())
                    <div class="card-footer bg-white border-top py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2 d-print-none">
                        <div class="fs-12 text-muted">
                            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries
                        </div>
                        <div class="ak23-pagination-compact">
                            {{ $users->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('style')
<style>
/* Native Compact Card & Mobile Optimizations */
.ak23-compact-card {
    border-radius: 10px;
    background: #fff;
}
.ak23-compact-table thead th {
    font-size: 11px;
    letter-spacing: 0.5px;
    background: #f8fafc;
}
.ak23-compact-table td {
    vertical-align: middle;
}
.ak23-pagination-compact .pagination {
    margin-bottom: 0;
    gap: 3px;
}
.ak23-pagination-compact .page-link {
    padding: 3px 9px;
    font-size: 12px;
    border-radius: 6px;
}

/* Mobile Screen Native Adaptability (Under 768px) */
@media (max-width: 768px) {
    .ak23-compact-card .card-header {
        flex-direction: column;
        align-items: flex-start !important;
    }
    .ak23-compact-card .card-header > div:last-child {
        width: 100%;
        justify-content: space-between;
    }
    .ak23-compact-table {
        min-width: 600px;
    }
}

/* High Fidelity Print Stylesheet for Instant PDF Generation */
@media print {
    body {
        background: #fff !important;
        color: #000 !important;
    }
    .sidebar, .navbar-wrapper, .d-print-none, .res-sidebar-open-btn, .breadcrumb-wrapper {
        display: none !important;
    }
    .page-wrapper {
        padding: 0 !important;
        margin: 0 !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .table {
        border: 1px solid #dee2e6 !important;
        width: 100% !important;
    }
    .table th, .table td {
        border: 1px solid #dee2e6 !important;
        padding: 6px !important;
    }
}
</style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush

@push('script')
<script>
(function($) {
    "use strict";

    // Date range picker initialization
    const datePicker = $('.date-range').daterangepicker({
        autoUpdateInput: false,
        locale: { cancelLabel: 'Clear' },
        showDropdowns: true,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(30, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    datePicker.on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        $('#usersFilterForm').submit();
    });

    datePicker.on('cancel.daterangepicker', function() {
        $(this).val('');
        $('#usersFilterForm').submit();
    });

    // Global Export Tools
    window.ak23PrintReport = function(title) {
        window.print();
    };

    window.ak23ExportCsv = function(tableId, filename) {
        let csv = [];
        let rows = document.querySelectorAll('#' + tableId + ' tr');
        for (let i = 0; i < rows.length; i++) {
            let row = [], cols = rows[i].querySelectorAll('td:not(.no-export), th:not(.no-export)');
            for (let j = 0; j < cols.length; j++) {
                let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/"/g, '""').trim();
                row.push('"' + data + '"');
            }
            if(row.length > 0) csv.push(row.join(','));
        }
        let csvFile = new Blob([csv.join('\n')], {type: 'text/csv;charset=utf-8;'});
        let downloadLink = document.createElement('a');
        downloadLink.download = (filename || 'export') + '-' + new Date().toISOString().slice(0, 10) + '.csv';
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = 'none';
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    };

})(jQuery);
</script>
@endpush
