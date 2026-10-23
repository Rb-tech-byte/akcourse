@extends('admin.layouts.app')

@section('panel')

@push('topBar')
    @include('admin.gateways.top_bar')
@endpush

    <!-- Print Header (Visible ONLY on PDF / Print Export) -->
    <div class="d-none d-print-block mb-3 p-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1 text-dark fw-bold">{{ gs('site_name') }} &middot; @lang('Manual Gateways Report')</h3>
                <p class="text-muted mb-0 small">Export generated on {{ now()->format('F d, Y - H:i:s') }} &middot; Filter: {{ request()->search ? 'Search: '.request()->search : 'All' }}</p>
            </div>
            <div class="text-end">
                <span class="badge bg-dark text-white px-3 py-2 fs-12">Total Gateways: {{ $gateways->total() }}</span>
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
                        <i class="las la-wallet text-warning fs-20"></i>
                        <h6 class="card-title mb-0 fs-15 fw-bold text-dark">@lang('Manual Gateways')</h6>
                        <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-2 py-1 fs-11">
                            {{ $gateways->total() }}
                        </span>
                    </div>

                    <!-- Toolbar Actions: Print, CSV, Add New -->
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <button type="button" onclick="ak23PrintReport('@lang('Manual Payment Gateways')')" class="btn btn-xs btn-outline-secondary d-flex align-items-center gap-1">
                            <i class="las la-print"></i>
                            <span class="d-none d-sm-inline">@lang('PDF / Print')</span>
                        </button>
                        <button type="button" onclick="ak23ExportCsv('manual-gateways-table', 'manual-gateways-report')" class="btn btn-xs btn-outline-secondary d-flex align-items-center gap-1">
                            <i class="las la-file-csv"></i>
                            <span class="d-none d-sm-inline">@lang('Export CSV')</span>
                        </button>
                        <a href="{{ route('admin.gateway.manual.create') }}" class="btn btn-xs btn--primary d-flex align-items-center gap-1">
                            <i class="las la-plus"></i>
                            <span>@lang('Add New Manual Gateway')</span>
                        </a>
                    </div>
                </div>

                <!-- Integrated Filter Bar (inside card, compact, mobile responsive) -->
                <div class="card-body bg-light border-bottom p-2 p-md-3 d-print-none">
                    <form action="{{ route('admin.gateway.manual.index') }}" method="GET" class="row g-2 align-items-center">
                        <!-- Search Keyword -->
                        <div class="col-12 col-md-5 col-lg-5">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0 text-muted">
                                    <i class="las la-search"></i>
                                </span>
                                <input type="text" name="search" class="form-control form-control-sm bg-white border-start-0 ps-0" 
                                       placeholder="@lang('Search gateway name, currency, code...')" 
                                       value="{{ request()->search }}">
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-6 col-md-3 col-lg-3">
                            <select name="status" class="form-select form-select-sm bg-white">
                                <option value="">@lang('All Statuses')</option>
                                <option value="active" @selected(request()->status == 'active')>@lang('Active / Enabled')</option>
                                <option value="inactive" @selected(request()->status == 'inactive')>@lang('Disabled')</option>
                            </select>
                        </div>

                        <!-- Per Page (Default: 4 per page) -->
                        <div class="col-6 col-md-2 col-lg-2">
                            <select name="per_page" class="form-select form-select-sm bg-white" onchange="this.form.submit()">
                                <option value="4" @selected(request()->get('per_page', 4) == 4)>4 @lang('/ page')</option>
                                <option value="8" @selected(request()->get('per_page') == 8)>8 @lang('/ page')</option>
                                <option value="12" @selected(request()->get('per_page') == 12)>12 @lang('/ page')</option>
                                <option value="20" @selected(request()->get('per_page') == 20)>20 @lang('/ page')</option>
                                <option value="50" @selected(request()->get('per_page') == 50)>50 @lang('/ page')</option>
                            </select>
                        </div>

                        <!-- Actions -->
                        <div class="col-12 col-md-2 col-lg-2 d-flex gap-1 justify-content-end">
                            <button type="submit" class="btn btn-sm btn--primary flex-grow-1 flex-md-grow-0">
                                <i class="las la-filter"></i> @lang('Filter')
                            </button>
                            @if(request()->anyFilled(['search', 'status', 'per_page']))
                                <a href="{{ route('admin.gateway.manual.index') }}" class="btn btn-sm btn-outline-secondary" title="@lang('Reset Filters')">
                                    <i class="las la-undo"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Compact Table -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle mb-0 ak23-compact-table" id="manual-gateways-table">
                            <thead class="table-light fs-12 text-uppercase text-muted border-bottom">
                                <tr>
                                    <th class="ps-3" style="min-width: 250px;">@lang('Gateway')</th>
                                    <th class="text-center" style="min-width: 140px;">@lang('Method Code')</th>
                                    <th class="text-center" style="min-width: 120px;">@lang('Status')</th>
                                    <th class="text-end pe-3 d-print-none" style="min-width: 150px;">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="fs-13">
                                @forelse($gateways as $gateway)
                                    <tr>
                                        <!-- Gateway Info -->
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center gap-2">
                                                @if($gateway->image)
                                                    <img src="{{ getImage(getFilePath('gateway') . '/' . $gateway->image) }}" 
                                                         alt="{{ $gateway->name }}" 
                                                         class="rounded border bg-white p-1"
                                                         style="width: 38px; height: 38px; object-fit: contain;">
                                                @else
                                                    <div class="rounded border d-flex align-items-center justify-content-center bg-warning-subtle text-warning fw-bold"
                                                         style="width: 38px; height: 38px;">
                                                        <i class="las la-wallet fs-18"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <a href="{{ route('admin.gateway.manual.edit', $gateway->alias) }}" class="fw-bold text-dark text-decoration-none hover-primary d-block">
                                                        {{ __($gateway->name) }}
                                                    </a>
                                                    <span class="text-muted fs-11">
                                                        {{ \Illuminate\Support\Str::limit(strip_tags($gateway->description), 50, '...') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Method Code -->
                                        <td class="text-center">
                                            <span class="badge bg-light text-muted border font-monospace px-2 py-1 fs-12">
                                                #{{ $gateway->code }}
                                            </span>
                                        </td>

                                        <!-- Status -->
                                        <td class="text-center">
                                            @php echo $gateway->statusBadge; @endphp
                                        </td>

                                        <!-- Actions -->
                                        <td class="text-end pe-3 d-print-none">
                                            <div class="d-flex align-items-center justify-content-end gap-1">
                                                <a href="{{ route('admin.gateway.manual.edit', $gateway->alias) }}" 
                                                   class="btn btn-xs btn-outline--primary" 
                                                   title="@lang('Edit Gateway')">
                                                    <i class="la la-pencil"></i>
                                                    <span class="d-none d-md-inline ms-1">@lang('Edit')</span>
                                                </a>

                                                @if($gateway->status == \App\Constants\Status::DISABLE)
                                                    <button class="btn btn-xs btn-outline-success confirmationBtn" 
                                                            data-question="@lang('Are you sure to enable this gateway?')" 
                                                            data-action="{{ route('admin.gateway.manual.status', $gateway->id) }}"
                                                            title="@lang('Enable')">
                                                        <i class="la la-eye"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-xs btn-outline-danger confirmationBtn" 
                                                            data-question="@lang('Are you sure to disable this gateway?')" 
                                                            data-action="{{ route('admin.gateway.manual.status', $gateway->id) }}"
                                                            title="@lang('Disable')">
                                                        <i class="la la-eye-slash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center py-4 text-muted">
                                            <i class="las la-inbox fs-32 d-block mb-1 text-secondary opacity-50"></i>
                                            @lang('No manual gateways found.')
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card Footer: Pagination (4 per page default) -->
                @if($gateways->hasPages())
                    <div class="card-footer bg-white border-top py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2 d-print-none">
                        <div class="text-muted fs-12">
                            @lang('Showing') <span class="fw-bold">{{ $gateways->firstItem() ?? 0 }}</span> @lang('to') <span class="fw-bold">{{ $gateways->lastItem() ?? 0 }}</span> @lang('of') <span class="fw-bold">{{ $gateways->total() }}</span> @lang('gateways')
                        </div>
                        <div class="ak23-pagination-wrap">
                            {{ paginateLinks($gateways) }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('style')
<style>
    /* Native First Card & Compact Table Styles */
    .ak23-compact-card {
        border-radius: 8px;
        overflow: hidden;
    }
    .ak23-compact-table th {
        font-weight: 600;
        letter-spacing: 0.3px;
        padding-top: 9px;
        padding-bottom: 9px;
        background-color: #f8fafc;
    }
    .ak23-compact-table td {
        padding-top: 8px;
        padding-bottom: 8px;
    }
    .btn-xs {
        padding: 0.2rem 0.45rem;
        font-size: 0.75rem;
        border-radius: 4px;
        line-height: 1.2;
    }
    .hover-primary:hover {
        color: #FBAF43 !important;
    }

    /* Print & PDF Export Styling */
    @media print {
        body {
            background: #fff !important;
            font-size: 11px !important;
        }
        .sidebar, .navbar-wrapper, .d-print-none, .res-sidebar-open-btn, .breadcrumb-wrapper, .modal, .topTap {
            display: none !important;
        }
        .page-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .ak23-compact-card {
            border: none !important;
            box-shadow: none !important;
        }
        .table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        .table td, .table th {
            padding: 4px 6px !important;
            border: 1px solid #dee2e6 !important;
            font-size: 10px !important;
        }
    }

    /* Mobile Minimum Screen Adjustments */
    @media (max-width: 768px) {
        .ak23-compact-card .card-header {
            padding: 8px 12px;
        }
        .ak23-compact-table {
            font-size: 12px;
        }
    }
</style>
@endpush

@push('script')
<script>
    (function($) {
        "use strict";

        // Print / PDF Report Generator
        window.ak23PrintReport = function(title) {
            window.print();
        };

        // CSV Export Engine
        window.ak23ExportCsv = function(tableId, filename) {
            var csv = [];
            var rows = document.querySelectorAll('#' + tableId + ' tr');

            for (var i = 0; i < rows.length; i++) {
                var row = [];
                var cols = rows[i].querySelectorAll('td, th');

                var limit = cols.length;
                if (cols.length > 1 && rows[i].querySelector('.d-print-none')) {
                    limit = cols.length - 1;
                }

                for (var j = 0; j < limit; j++) {
                    var text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/\s+/g, ' ').trim();
                    text = text.replace(/"/g, '""');
                    row.push('"' + text + '"');
                }

                if (row.length > 0) {
                    csv.push(row.join(','));
                }
            }

            var csvContent = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv.join('\n'));
            var link = document.createElement('a');
            link.setAttribute('href', csvContent);
            link.setAttribute('download', filename + '_' + new Date().toISOString().slice(0, 10) + '.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };

    })(jQuery);
</script>
@endpush
