@extends('admin.layouts.app')

@section('panel')
    @push('topBar')
        @include('admin.product.categories_top_bar')
    @endpush

    <!-- Print Header (Visible ONLY on PDF / Print Export) -->
    <div class="d-none d-print-block mb-3 p-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1 text-dark fw-bold">{{ gs('site_name') }} &middot; Course Categories Report</h3>
                <p class="text-muted mb-0 small">Export generated on {{ now()->format('F d, Y - H:i:s') }}</p>
            </div>
            <div class="text-end">
                <span class="badge bg-dark text-white px-3 py-2 fs-12">Total: {{ $categories->total() }}</span>
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
                        <i class="las la-layer-group text-primary fs-20"></i>
                        <h6 class="card-title mb-0 fs-15 fw-bold text-dark">{{ __($pageTitle) }}</h6>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill fs-11">
                            {{ $categories->total() }} Categories (4 / page)
                        </span>
                    </div>

                    <div class="d-flex align-items-center flex-wrap gap-2 d-print-none">
                        <!-- Export PDF Button -->
                        <button type="button" class="btn btn-sm btn-outline-danger px-2 py-1 fs-12" onclick="ak23PrintReport('Course Categories')">
                            <i class="las la-file-pdf"></i> <span class="d-none d-sm-inline">Export PDF</span>
                        </button>
                        <!-- Export CSV Button -->
                        <button type="button" class="btn btn-sm btn-outline-success px-2 py-1 fs-12" onclick="ak23ExportCsv('categoriesTable', 'categories-export')">
                            <i class="las la-file-excel"></i> <span class="d-none d-sm-inline">Export CSV</span>
                        </button>
                        <!-- Add New Category Button -->
                        <button class="btn btn-sm btn--primary px-3 py-1 fs-12 fw-semibold addBtn">
                            <i class="las la-plus"></i> @lang('Add Category')
                        </button>
                    </div>
                </div>

                <!-- Integrated Compact Filter Bar -->
                <div class="card-body bg-light border-bottom p-2 p-md-3 d-print-none">
                    <form method="GET" action="" id="categoryFilterForm">
                        <div class="row g-2 align-items-end">
                            <!-- Search -->
                            <div class="col-12 col-sm-6 col-lg-5">
                                <label class="form-label fs-11 fw-bold text-muted mb-1">@lang('Search Category')</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="las la-search text-muted"></i></span>
                                    <input type="search" name="search" value="{{ request()->search }}" class="form-control form-control-sm border-start-0" placeholder="Category name..." autocomplete="off">
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div class="col-6 col-sm-3 col-lg-3">
                                <label class="form-label fs-11 fw-bold text-muted mb-1">@lang('Status')</label>
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">@lang('All Status')</option>
                                    <option value="1" @selected(request()->status === '1')>@lang('Active Only')</option>
                                    <option value="0" @selected(request()->status === '0')>@lang('Inactive Only')</option>
                                </select>
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
                        <table class="table table-hover table-sm align-middle mb-0 ak23-compact-table" id="categoriesTable">
                            <thead class="table-light fs-12 text-uppercase text-muted border-bottom">
                                <tr>
                                    <th class="ps-3 py-2" style="min-width: 220px;">@lang('Category Name')</th>
                                    <th class="py-2">@lang('Personal Fee')</th>
                                    <th class="py-2">@lang('Commercial Fee')</th>
                                    <th class="py-2">@lang('Extended Fee')</th>
                                    <th class="py-2">@lang('Status')</th>
                                    <th class="py-2">@lang('Featured')</th>
                                    <th class="text-end pe-3 py-2 no-export d-print-none">@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody class="fs-13">
                                @forelse ($categories as $category)
                                    <tr>
                                        <!-- Name & Image -->
                                        <td class="ps-3 py-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="ak23-thumb-box rounded-2 overflow-hidden flex-shrink-0 bg-dark" style="width: 44px; height: 34px;">
                                                    <img src="{{ getImage(getFilePath('category') . '/' . @$category->image) }}" alt="{{ $category->name }}" class="w-100 h-100" style="object-fit: cover;">
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark d-block">{{ __($category->name) }}</span>
                                                    <span class="text-muted fs-11">Type: {{ strtoupper($category->file_type ?? 'DIGITAL') }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Personal Fee -->
                                        <td class="py-2">
                                            <span class="fw-semibold text-dark">{{ showAmount($category->personal_buyer_fee) }}</span>
                                        </td>

                                        <!-- Commercial Fee -->
                                        <td class="py-2">
                                            <span class="fw-semibold text-dark">{{ showAmount($category->commercial_buyer_fee) }}</span>
                                        </td>

                                        <!-- Extended Fee -->
                                        <td class="py-2">
                                            <span class="fw-semibold text-dark">{{ showAmount($category->twelve_month_extended_fee) }}</span>
                                        </td>

                                        <!-- Status Badge -->
                                        <td class="py-2">
                                            @php echo $category->statusBadge @endphp
                                        </td>

                                        <!-- Featured Badge -->
                                        <td class="py-2">
                                            @php echo $category->featuredBadge @endphp
                                        </td>

                                        <!-- Actions -->
                                        <td class="text-end pe-3 py-2 no-export d-print-none">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-info py-1 px-2 editButton"
                                                        data-id="{{ $category->id }}"
                                                        data-name="{{ __($category->name) }}"
                                                        data-status="{{ $category->status }}"
                                                        data-personal_buyer_fee="{{ getAmount($category->personal_buyer_fee) }}"
                                                        data-commercial_buyer_fee="{{ getAmount($category->commercial_buyer_fee) }}"
                                                        data-twelve_month_extended_fee="{{ getAmount($category->twelve_month_extended_fee) }}"
                                                        data-description="{{ $category->description }}"
                                                        data-file_type="{{ $category->file_type }}"
                                                        data-file_size="{{ $category->file_size }}"
                                                        data-preview_file_types="{{ json_encode($category->preview_file_types) }}"
                                                        data-action="{{ route('admin.category.update', $category->id) }}"
                                                        data-image="{{ getImage(getFilePath('category') . '/' . @$category->image) }}"
                                                        title="Edit Category">
                                                    <i class="las la-pen"></i>
                                                </button>
                                                <a href="{{ route('admin.category.seo', $category->id) }}" class="btn btn-outline-secondary py-1 px-2" title="SEO Settings">
                                                    <i class="las la-cog"></i>
                                                </a>
                                                @if ($category->status)
                                                    <button type="button" class="btn btn-outline-danger py-1 px-2 confirmationBtn" data-question="@lang('Are you sure to inactive this category?')" data-action="{{ route('admin.category.active.toggle', $category->id) }}" title="Make Inactive">
                                                        <i class="las la-eye-slash"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-outline-success py-1 px-2 confirmationBtn" data-question="@lang('Are you sure to active this category?')" data-action="{{ route('admin.category.active.toggle', $category->id) }}" title="Make Active">
                                                        <i class="las la-eye"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-outline-danger py-1 px-2 confirmationBtn" data-question="@lang('Are you sure to delete this series category?')" data-action="{{ route('admin.category.delete', $category->id) }}" title="Delete Category">
                                                    <i class="las la-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center py-4" colspan="100%">
                                            <i class="las la-folder-open fs-2 text-secondary d-block mb-1"></i>
                                            @lang('No categories found matching criteria')
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Compact Pagination Footer (4 items per page default) -->
                @if ($categories->hasPages())
                    <div class="card-footer bg-white border-top py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2 d-print-none">
                        <div class="fs-12 text-muted">
                            Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }} entries
                        </div>
                        <div class="ak23-pagination-compact">
                            {{ $categories->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Category Create / Edit Modal -->
    <div id="categoryModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header py-2 px-3">
                    <h6 class="modal-title fs-15 fw-bold m-0"></h6>
                    <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-3">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <div class="form-group mb-0">
                                    <label class="imageLabel fw-bold fs-12 mb-1">@lang('Category Image')</label>
                                    <x-image-uploader class="w-100" type="category" />
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group mb-2">
                                    <label class="fw-bold fs-12 mb-1">@lang('Category Name')</label>
                                    <input type="text" class="form-control form-control-sm" name="name" required placeholder="e.g. Afrobeats Mixing">
                                </div>
                                <div class="form-group mb-2">
                                    <label class="fw-bold fs-12 mb-1">@lang('Personal License Buyer Fee')</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">{{ gs('cur_sym') }}</span>
                                        <input class="form-control" type="number" step="any" name="personal_buyer_fee" required>
                                        <span class="input-group-text">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>
                                <div class="form-group mb-2">
                                    <label class="fw-bold fs-12 mb-1">@lang('Commercial License Buyer Fee')</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">{{ gs('cur_sym') }}</span>
                                        <input class="form-control" type="number" step="any" name="commercial_buyer_fee" required>
                                        <span class="input-group-text">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="fw-bold fs-12 mb-1">@lang('12 Month Extended Fee')</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">{{ gs('cur_sym') }}</span>
                                        <input class="form-control" type="number" step="any" name="twelve_month_extended_fee" required>
                                        <span class="input-group-text">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-0">
                                    <label class="fw-bold fs-12 mb-1">@lang('Primary Media / File Type')</label>
                                    <select class="form-select form-select-sm" name="file_type" id="fileType">
                                        <option value="script">@lang('Digital Download / Bundled ZIP Archive')</option>
                                        <option value="audio">@lang('Audio Stem Walkthrough')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 hidden-group" id="fileSizeGroup">
                                <div class="form-group mb-0">
                                    <label class="fw-bold fs-12 mb-1">@lang('Max Upload Limit (MB)')</label>
                                    <input type="number" class="form-control form-control-sm" name="file_size" step="any">
                                </div>
                            </div>

                            <div class="col-md-6 hidden-group" id="previewFileTypesGroup">
                                <div class="form-group mb-0">
                                    <label class="fw-bold fs-12 mb-1">@lang('Allowed Preview File Types')</label>
                                    <select name="preview_file_types[]" class="form-control select2-auto-tokenize" multiple="multiple">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer py-2 px-3">
                        <button type="submit" class="btn btn--primary w-100 py-2 fs-13">@lang('Save Category')</button>
                    </div>
                </form>
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
.hidden-group {
    display: none;
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
    .sidebar, .navbar-wrapper, .d-print-none, .res-sidebar-open-btn, .breadcrumb-wrapper, .modal {
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

@push('script')
<script>
(function($) {
    "use strict";

    let modal = $('#categoryModal');
    let defaultImage = `{{ getImage(getFilePath('category'), getFileSize('category')) }}`;

    $('.addBtn').on('click', function() {
        $('select[name="preview_file_types[]"]').empty();
        modal.find('.modal-title').text(`@lang('Add Category')`);
        modal.find('.image-upload-preview').css('background-image', `url(${defaultImage})`);
        modal.find('form').attr('action', `{{ route('admin.category.store') }}`);
        modal.find('[name=image]').attr('required', true);
        modal.find('.imageLabel').addClass('required');
        modal.modal('show');
    });

    $('.editButton').on('click', function() {
        let data = $(this).data();
        $('select[name="preview_file_types[]"]').empty();

        modal.find('.modal-title').text(`@lang('Edit Category')`);
        modal.find('[name=name]').val(data.name);
        modal.find('[name=file_type]').val(data.file_type).trigger('change');
        modal.find('[name=file_size]').val(data.file_size);
        if (data.preview_file_types) {
            data.preview_file_types.forEach(function(option) {
                var optionElement = `<option value="${option}" selected>${option}</option>`;
                $('select[name="preview_file_types[]"]').append(optionElement);
            });
        }
        modal.find('[name=personal_buyer_fee]').val(data.personal_buyer_fee);
        modal.find('[name=commercial_buyer_fee]').val(data.commercial_buyer_fee);
        modal.find('[name=twelve_month_extended_fee]').val(data.twelve_month_extended_fee);
        modal.find('.image-upload-preview').css('background-image', `url(${data.image})`);
        modal.find('form').attr('action', data.action);
        modal.find('[name=image]').removeAttr('required');
        modal.modal('show');
    });

    modal.on('hidden.bs.modal', function() {
        modal.find('form')[0].reset();
    });

    function toggleFileFields() {
        if ($('#fileType').val() === 'audio') {
            $('#fileSizeGroup').removeClass('hidden-group');
            $('#previewFileTypesGroup').removeClass('hidden-group');
        } else {
            $('#fileSizeGroup').addClass('hidden-group');
            $('#previewFileTypesGroup').addClass('hidden-group');
            $("[name='preview_file_types[]']").val(null);
            $('[name="file_size"]').val(null);
        }
    }

    toggleFileFields();
    $('#fileType').on('change', function() {
        toggleFileFields();
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
