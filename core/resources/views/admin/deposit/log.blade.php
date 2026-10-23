@extends('admin.layouts.app')

@section('panel')

    @php
        $gateways = \App\Models\Gateway::all();
    @endphp

    <!-- Print Header (Visible ONLY on PDF / Print Export) -->
    <div class="d-none d-print-block mb-3 p-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1 text-dark fw-bold">{{ gs('site_name') }} &middot; {{ __($pageTitle) }} Report</h3>
                <p class="text-muted mb-0 small">Export generated on {{ now()->format('F d, Y - H:i:s') }} &middot; Filter: {{ request()->search ? 'Search: '.request()->search : 'All' }}</p>
            </div>
            <div class="text-end">
                <span class="badge bg-dark text-white px-3 py-2 fs-12">Total Records: {{ $deposits->total() }}</span>
            </div>
        </div>
    </div>

    <!-- Summary Widgets (Compact on Desktop & Mobile) -->
    @if(isset($successful) && (request()->routeIs('admin.deposit.list') || request()->routeIs('admin.deposit.method')))
        <div class="row g-2 mb-3 d-print-none">
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.deposit.successful') }}" class="text-decoration-none">
                    <div class="p-2 p-md-3 rounded-3 border bg-white shadow-sm d-flex align-items-center gap-2 border-start border-4 border-success">
                        <i class="las la-check-circle text-success fs-24 flex-shrink-0"></i>
                        <div class="overflow-hidden">
                            <span class="text-muted fs-11 text-uppercase fw-bold d-block text-truncate">Successful</span>
                            <span class="fw-bold text-dark fs-14">{{ showAmount($successful) }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.deposit.pending') }}" class="text-decoration-none">
                    <div class="p-2 p-md-3 rounded-3 border bg-white shadow-sm d-flex align-items-center gap-2 border-start border-4 border-warning">
                        <i class="las la-hourglass-half text-warning fs-24 flex-shrink-0"></i>
                        <div class="overflow-hidden">
                            <span class="text-muted fs-11 text-uppercase fw-bold d-block text-truncate">Pending</span>
                            <span class="fw-bold text-dark fs-14">{{ showAmount($pending) }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.deposit.rejected') }}" class="text-decoration-none">
                    <div class="p-2 p-md-3 rounded-3 border bg-white shadow-sm d-flex align-items-center gap-2 border-start border-4 border-danger">
                        <i class="las la-times-circle text-danger fs-24 flex-shrink-0"></i>
                        <div class="overflow-hidden">
                            <span class="text-muted fs-11 text-uppercase fw-bold d-block text-truncate">Rejected</span>
                            <span class="fw-bold text-dark fs-14">{{ showAmount($rejected) }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.deposit.initiated') }}" class="text-decoration-none">
                    <div class="p-2 p-md-3 rounded-3 border bg-white shadow-sm d-flex align-items-center gap-2 border-start border-4 border-dark">
                        <i class="las la-wallet text-dark fs-24 flex-shrink-0"></i>
                        <div class="overflow-hidden">
                            <span class="text-muted fs-11 text-uppercase fw-bold d-block text-truncate">Initiated</span>
                            <span class="fw-bold text-dark fs-14">{{ showAmount($initiated) }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <!-- Main First Card (Compact, Responsive, Native) -->
            <div class="card ak23-compact-card border shadow-sm">
                <!-- Card Header with Actions & Export Tools -->
                <div class="card-header bg-white py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="las la-credit-card text-primary fs-20"></i>
                        <h6 class="card-title mb-0 fs-15 fw-bold text-dark">{{ __($pageTitle) }}</h6>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill fs-11">
                            {{ $deposits->total() }} Payments (4 / page)
                        </span>
                    </div>

                    <div class="d-flex align-items-center flex-wrap gap-2 d-print-none">
                        <!-- Export PDF Button -->
                        <button type="button" class="btn btn-sm btn-outline-danger px-2 py-1 fs-12" onclick="ak23PrintReport('{{ __($pageTitle) }}')">
                            <i class="las la-file-pdf"></i> <span class="d-none d-sm-inline">Export PDF</span>
                        </button>
                        <!-- Export CSV Button -->
                        <button type="button" class="btn btn-sm btn-outline-success px-2 py-1 fs-12" onclick="ak23ExportCsv('paymentsTable', 'payments-export')">
                            <i class="las la-file-excel"></i> <span class="d-none d-sm-inline">Export CSV</span>
                        </button>
                    </div>
                </div>

                <!-- Integrated Compact Filter Bar -->
                <div class="card-body bg-light border-bottom p-2 p-md-3 d-print-none">
                    <form method="GET" action="" id="paymentFilterForm">
                        <div class="row g-2 align-items-end">
                            <!-- Search -->
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label fs-11 fw-bold text-muted mb-1">@lang('Search TRX / Username')</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="las la-search text-muted"></i></span>
                                    <input type="search" name="search" value="{{ request()->search }}" class="form-control form-control-sm border-start-0" placeholder="Transaction number, student username..." autocomplete="off">
                                </div>
                            </div>

                            <!-- Gateway / Method Filter -->
                            <div class="col-6 col-sm-6 col-lg-3">
                                <label class="form-label fs-11 fw-bold text-muted mb-1">@lang('Payment Gateway')</label>
                                <select name="method" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">@lang('All Gateways (Selcom & Mobile Money)')</option>
                                    @foreach($gateways as $gw)
                                        <option value="{{ $gw->alias }}" @selected(request()->method == $gw->alias)>{{ __($gw->name) }}</option>
                                    @endforeach
                                    <option value="5000" @selected(request()->method == '5000')>@lang('Google Pay')</option>
                                </select>
                            </div>

                            <!-- Date Range -->
                            <div class="col-6 col-sm-4 col-lg-2">
                                <label class="form-label fs-11 fw-bold text-muted mb-1">@lang('Date Range')</label>
                                <input name="date" type="text" class="datepicker-here form-control form-control-sm bg-white date-range" placeholder="Date range" value="{{ request()->date }}" autocomplete="off">
                            </div>

                            <!-- Per Page Selector (Default 4) -->
                            <div class="col-6 col-sm-4 col-lg-1">
                                <label class="form-label fs-11 fw-bold text-muted mb-1">@lang('Per Page')</label>
                                <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="4" @selected(request()->get('per_page', 4) == 4)>4</option>
                                    <option value="8" @selected(request()->get('per_page') == 8)>8</option>
                                    <option value="12" @selected(request()->get('per_page') == 12)>12</option>
                                    <option value="20" @selected(request()->get('per_page') == 20)>20</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-6 col-sm-4 col-lg-2 d-flex gap-1">
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
                        <table class="table table-hover table-sm align-middle mb-0 ak23-compact-table" id="paymentsTable">
                            <thead class="table-light fs-12 text-uppercase text-muted border-bottom">
                                <tr>
                                    <th class="ps-3 py-2" style="min-width: 200px;">@lang('Gateway & TRX')</th>
                                    <th class="py-2">@lang('Initiated Date')</th>
                                    <th class="py-2">@lang('Student / User')</th>
                                    <th class="py-2">@lang('Amount')</th>
                                    <th class="py-2">@lang('Conversion')</th>
                                    <th class="py-2">@lang('Status')</th>
                                    <th class="text-end pe-3 py-2 no-export d-print-none">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="fs-13">
                                @forelse($deposits as $deposit)
                                    <tr>
                                        <!-- Gateway & Transaction -->
                                        <td class="ps-3 py-2">
                                            <span class="fw-bold text-dark">
                                                @if($deposit->method_code < 5000)
                                                    {{ __(@$deposit->gateway->name ?: 'Selcom / Mobile Money') }}
                                                @else
                                                    @lang('Google Pay')
                                                @endif
                                            </span>
                                            <div class="fs-11 text-muted">
                                                TRX: <code class="text-primary">{{ $deposit->trx }}</code>
                                            </div>
                                        </td>

                                        <!-- Initiated Date -->
                                        <td class="py-2">
                                            <span class="text-dark">{{ showDateTime($deposit->created_at, 'd M, Y H:i') }}</span>
                                            <div class="fs-11 text-muted">{{ diffForHumans($deposit->created_at) }}</div>
                                        </td>

                                        <!-- User -->
                                        <td class="py-2">
                                            <span class="fw-semibold text-dark">{{ $deposit->user->fullname ?? 'Student' }}</span>
                                            <br>
                                            <span class="fs-11 text-muted"><span>@</span>{{ $deposit->user->username ?? 'student' }}</span>
                                        </td>

                                        <!-- Amount -->
                                        <td class="py-2">
                                            <span class="fw-bold text-dark">{{ showAmount($deposit->amount) }}</span>
                                            @if($deposit->charge > 0)
                                                <div class="fs-11 text-danger" title="Fee">+ {{ showAmount($deposit->charge) }}</div>
                                            @endif
                                        </td>

                                        <!-- Conversion -->
                                        <td class="py-2">
                                            <span class="fw-semibold text-dark">{{ showAmount($deposit->final_amount, currencyFormat:false) }} {{ __($deposit->method_currency) }}</span>
                                            <div class="fs-11 text-muted">Rate: 1 {{ gs('cur_text') }} = {{ showAmount($deposit->rate, currencyFormat:false) }} {{ __($deposit->method_currency) }}</div>
                                        </td>

                                        <!-- Status Badge -->
                                        <td class="py-2">
                                            @php echo $deposit->statusBadge @endphp
                                        </td>

                                        <!-- Action -->
                                        <td class="text-end pe-3 py-2 no-export d-print-none">
                                            <a href="{{ route('admin.deposit.details', $deposit->id) }}" class="btn btn-sm btn-outline-primary py-1 px-2 fs-12">
                                                <i class="las la-eye"></i> @lang('Details')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center py-4" colspan="100%">
                                            <i class="las la-receipt fs-2 text-secondary d-block mb-1"></i>
                                            @lang('No payment records found')
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Compact Pagination Footer (4 items per page default) -->
                @if ($deposits->hasPages())
                    <div class="card-footer bg-white border-top py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2 d-print-none">
                        <div class="fs-12 text-muted">
                            Showing {{ $deposits->firstItem() ?? 0 }} to {{ $deposits->lastItem() ?? 0 }} of {{ $deposits->total() }} entries
                        </div>
                        <div class="ak23-pagination-compact">
                            {{ $deposits->links() }}
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
        $('#paymentFilterForm').submit();
    });

    datePicker.on('cancel.daterangepicker', function() {
        $(this).val('');
        $('#paymentFilterForm').submit();
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
