@extends('admin.layouts.app')
@section('panel')

    @php
        $allCategories = \App\Models\Category::active()->get();
    @endphp

    <!-- Print Header (Visible ONLY on PDF / Print Export) -->
    <div class="d-none d-print-block mb-3 p-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1 text-dark fw-bold">{{ gs('site_name') }} &middot; {{ __($pageTitle) }}</h3>
                <p class="text-muted mb-0 small">Export generated on {{ now()->format('F d, Y - H:i:s') }} &middot; Filter: {{ request()->search ? 'Search: '.request()->search : 'All' }}</p>
            </div>
            <div class="text-end">
                <span class="badge bg-dark text-white px-3 py-2 fs-12">Total Items: {{ $products->total() }}</span>
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
                        <i class="las la-boxes text-primary fs-20"></i>
                        <h6 class="card-title mb-0 fs-15 fw-bold text-dark">{{ __($pageTitle) }}</h6>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill fs-11">
                            {{ $products->total() }} Courses (4 / page)
                        </span>
                    </div>

                    <div class="d-flex align-items-center flex-wrap gap-2 d-print-none">
                        <!-- Export PDF Button -->
                        <button type="button" class="btn btn-sm btn-outline-danger px-2 py-1 fs-12" onclick="ak23PrintReport('{{ __($pageTitle) }}')">
                            <i class="las la-file-pdf"></i> <span class="d-none d-sm-inline">Export PDF</span>
                        </button>
                        <!-- Export CSV Button -->
                        <button type="button" class="btn btn-sm btn-outline-success px-2 py-1 fs-12" onclick="ak23ExportCsv('coursesTable', 'courses-export')">
                            <i class="las la-file-excel"></i> <span class="d-none d-sm-inline">Export CSV</span>
                        </button>
                        <!-- New Course Button -->
                        <a href="{{ route('admin.product.create') }}" class="btn btn-sm btn--primary px-3 py-1 fs-12 fw-semibold">
                            <i class="las la-plus"></i> @lang('New Course')
                        </a>
                    </div>
                </div>

                <!-- Integrated Compact Filter Bar -->
                <div class="card-body bg-light border-bottom p-2 p-md-3 d-print-none">
                    <form method="GET" action="" id="courseFilterForm">
                        <div class="row g-2 align-items-end">
                            <!-- Search -->
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label fs-11 fw-bold text-muted mb-1">@lang('Course / Author Search')</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="las la-search text-muted"></i></span>
                                    <input type="search" name="search" value="{{ request()->search }}" class="form-control form-control-sm border-start-0" placeholder="Title, slug, author..." autocomplete="off">
                                </div>
                            </div>

                            <!-- Category Filter -->
                            <div class="col-6 col-sm-6 col-lg-2">
                                <label class="form-label fs-11 fw-bold text-muted mb-1">@lang('Category')</label>
                                <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">@lang('All Categories')</option>
                                    @foreach($allCategories as $cat)
                                        <option value="{{ $cat->id }}" @selected(request()->category_id == $cat->id)>{{ __($cat->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Type Filter -->
                            <div class="col-6 col-sm-4 col-lg-2">
                                <label class="form-label fs-11 fw-bold text-muted mb-1">@lang('Pricing')</label>
                                <select name="is_free" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">@lang('All Pricing')</option>
                                    <option value="0" @selected(request()->is_free === '0')>@lang('Paid Only')</option>
                                    <option value="1" @selected(request()->is_free === '1')>@lang('Free Only')</option>
                                </select>
                            </div>

                            <!-- Date Range -->
                            <div class="col-6 col-sm-4 col-lg-2">
                                <label class="form-label fs-11 fw-bold text-muted mb-1">@lang('Upload Date')</label>
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
                            <div class="col-12 col-sm-12 col-lg-2 d-flex gap-1">
                                <button type="submit" class="btn btn-sm btn--primary flex-grow-1 py-1 fs-12">
                                    <i class="las la-filter"></i> @lang('Filter')
                                </button>
                                <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary py-1 fs-12" title="Reset all filters">
                                    <i class="las la-undo"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Compact Table / Mobile Native Cards -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle mb-0 ak23-compact-table" id="coursesTable">
                            <thead class="table-light fs-12 text-uppercase text-muted border-bottom">
                                <tr>
                                    <th class="ps-3 py-2" style="min-width: 240px;">@lang('Course Details')</th>
                                    <th class="py-2">@lang('Author')</th>
                                    <th class="py-2">@lang('Category')</th>
                                    <th class="py-2">@lang('Price ($)')</th>
                                    <th class="py-2">@lang('Status')</th>
                                    <th class="py-2">@lang('Rating')</th>
                                    <th class="text-end pe-3 py-2 no-export d-print-none">@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody class="fs-13">
                                @forelse($products as $product)
                                    <tr>
                                        <!-- Course thumbnail & title -->
                                        <td class="ps-3 py-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="ak23-thumb-box rounded-2 overflow-hidden flex-shrink-0 bg-dark" style="width: 48px; height: 36px;">
                                                    <img src="{{ getImage(getFilePath('productThumbnail') . '/' . productFilePath($product, 'thumbnail')) }}" alt="{{ $product->title }}" class="w-100 h-100" style="object-fit: cover;">
                                                </div>
                                                <div class="overflow-hidden">
                                                    <div class="d-flex align-items-center gap-1">
                                                        <a href="{{ route('product.details', $product->slug) }}" target="_blank" class="fw-bold text-dark text-decoration-none d-block text-truncate" style="max-width: 220px;" title="{{ $product->title }}">
                                                            {{ __($product->title) }}
                                                        </a>
                                                        @if($product->product_type == 'bundle')
                                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-1 rounded fs-10">Bundle</span>
                                                        @endif
                                                    </div>
                                                    <span class="text-muted fs-11">
                                                        <span class="font-monospace text-secondary fs-10 me-1">{{ $product->sku }}</span> &middot;
                                                        {{ showDateTime($product->created_at, 'd M, Y') }}
                                                        @if($product->demo_url)
                                                            &middot; <a href="{{ $product->demo_url }}" target="_blank" class="text-primary text-decoration-none" title="Live Preview"><i class="las la-play-circle"></i> Preview</a>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Author -->
                                        <td class="py-2">
                                            <span class="fw-semibold text-dark">{{ __(@$product->author->fullname ?: 'Admin') }}</span>
                                            <br>
                                            <span class="text-muted fs-11"><span>@</span>{{ $product->author->username ?? 'admin' }}</span>
                                        </td>

                                        <!-- Category -->
                                        <td class="py-2">
                                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 rounded-pill fs-11">
                                                {{ __(@$product->category->name ?? 'Unassigned') }}
                                            </span>
                                            @if(@$product->subCategory && $product->subCategory->name)
                                                <div class="fs-11 text-muted">{{ __($product->subCategory->name) }}</div>
                                            @endif
                                        </td>

                                        <!-- Price -->
                                        <td class="py-2">
                                            @if(!$product->is_free)
                                                <span class="fw-bold text-dark">{{ showAmount($product->price) }}</span>
                                                <div class="fs-11 text-success"><i class="las la-certificate"></i> Lifetime License</div>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill fs-11">@lang('Free')</span>
                                            @endif
                                        </td>

                                        <!-- Status Badge -->
                                        <td class="py-2">
                                            @php echo $product->statusBadge @endphp
                                        </td>

                                        <!-- Rating -->
                                        <td class="py-2">
                                            <div class="d-flex align-items-center gap-1">
                                                <i class="las la-star text-warning fs-13"></i>
                                                <span class="fw-semibold text-dark">{{ number_format($product->avg_rating, 1) }}</span>
                                            </div>
                                            @if($product->is_featured)
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-1 rounded fs-10">Featured</span>
                                            @endif
                                        </td>

                                        <!-- Actions -->
                                        <td class="text-end pe-3 py-2 no-export d-print-none">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-outline-info py-1 px-2" title="Edit Course">
                                                    <i class="las la-pen"></i>
                                                </a>
                                                <a href="{{ route('admin.product.details', $product->slug) }}" class="btn btn-outline-primary py-1 px-2" title="View Details">
                                                    <i class="las la-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-{{ $product->is_featured ? 'danger' : 'warning' }} py-1 px-2 confirmationBtn" data-question="@lang('Are you sure to ' . ($product->is_featured ? 'unfeature' : 'feature') . ' this course?')" data-action="{{ route('admin.product.feature.toggle', $product->id) }}" title="{{ $product->is_featured ? 'Unfeature' : 'Feature' }}">
                                                    <i class="las la-{{ $product->is_featured ? 'eye-slash' : 'star' }}"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger py-1 px-2 confirmationBtn" data-question="@lang('Are you sure to delete this course permanently?')" data-action="{{ route('admin.product.delete', $product->id) }}" title="Delete Course">
                                                    <i class="las la-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center py-4" colspan="100%">
                                            <i class="las la-box-open fs-2 text-secondary d-block mb-1"></i>
                                            @lang('No courses found matching criteria')
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Compact Pagination Footer (4 items per page default) -->
                @if ($products->hasPages())
                    <div class="card-footer bg-white border-top py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2 d-print-none">
                        <div class="fs-12 text-muted">
                            Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} entries
                        </div>
                        <div class="ak23-pagination-compact">
                            {{ $products->links() }}
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
        $('#courseFilterForm').submit();
    });

    datePicker.on('cancel.daterangepicker', function() {
        $(this).val('');
        $('#courseFilterForm').submit();
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
