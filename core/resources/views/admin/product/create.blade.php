@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-12">
            <!-- Main First Card (Compact, Responsive, Native) -->
            <div class="card ak23-compact-card border shadow-sm">
                <!-- Card Header -->
                <div class="card-header bg-white py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="las la-plus-circle text-primary fs-20"></i>
                        <h6 class="card-title mb-0 fs-15 fw-bold text-dark">@lang('Create New Course / Masterclass')</h6>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1 fs-11">
                            @lang('New Draft')
                        </span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('admin.product.all') }}" class="btn btn-xs btn-outline-secondary d-flex align-items-center gap-1">
                            <i class="las la-arrow-left"></i>
                            <span>@lang('All Courses')</span>
                        </a>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.product.save') }}" method="POST" enctype="multipart/form-data" id="courseCreateForm">
                    @csrf
                    <div class="card-body p-2 p-md-3">
                        <div class="row g-3">
                            <!-- Left Column: Primary Course Details -->
                            <div class="col-12 col-lg-8">
                                <!-- Title -->
                                <div class="form-group mb-3">
                                    <label class="form-label fs-13 fw-bold text-dark mb-1">
                                        @lang('Course Title') <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="title" class="form-control form-control-sm" 
                                           value="{{ old('title') }}" 
                                           placeholder="e.g. Vocal Mixing Masterclass: Modern Afrobeats & Trap" required>
                                </div>

                                <!-- Category & Sub-category -->
                                <div class="row g-2 mb-3">
                                    <div class="col-12 col-sm-6">
                                        <label class="form-label fs-13 fw-bold text-dark mb-1">
                                            @lang('Series / Category') <span class="text-danger">*</span>
                                        </label>
                                        <select name="category_id" id="category_id" class="form-select form-select-sm select2" required>
                                            <option value="">@lang('Select Series / Category')</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                                    {{ __($category->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label class="form-label fs-13 fw-bold text-dark mb-1">
                                            @lang('Sub-category')
                                        </label>
                                        <select name="sub_category_id" id="sub_category_id" class="form-select form-select-sm select2">
                                            <option value="">@lang('Select Sub-category')</option>
                                            @foreach($categories as $category)
                                                @foreach($category->subCategories as $sub)
                                                    <option value="{{ $sub->id }}" data-category="{{ $category->id }}" @selected(old('sub_category_id') == $sub->id)>
                                                        {{ __($sub->name) }} ({{ __($category->name) }})
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- AK23 Course & Software License Metadata -->
                                <div class="bg-light p-3 rounded border mb-3">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="las la-certificate text-warning fs-18"></i>
                                        <span class="fs-13 fw-bold text-dark">Course &amp; Software License Specifications</span>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-12 col-sm-4">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Course SKU</label>
                                            <input type="text" name="sku" class="form-control form-control-sm font-monospace" value="{{ old('sku') }}" placeholder="AK23-COURSE-...">
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Matching Software Name</label>
                                            <input type="text" name="software_name" class="form-control form-control-sm" value="{{ old('software_name') }}" placeholder="e.g. Voxity, Nectar 4, Cubase">
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Software Edition / License</label>
                                            <input type="text" name="software_edition" class="form-control form-control-sm" value="{{ old('software_edition') }}" placeholder="e.g. Standard Studio Edition">
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-12 col-sm-4">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Stage Label</label>
                                            <input type="text" name="stage_label" class="form-control form-control-sm" value="{{ old('stage_label', 'Beginner') }}" placeholder="e.g. Getting Started, Beginner, Advanced">
                                        </div>
                                        <div class="col-6 col-sm-2">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Seq No</label>
                                            <input type="number" name="sequence_no" class="form-control form-control-sm" value="{{ old('sequence_no', 1) }}">
                                        </div>
                                        <div class="col-6 col-sm-3">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Lessons Count</label>
                                            <input type="number" name="lesson_count" class="form-control form-control-sm" value="{{ old('lesson_count', 8) }}">
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Video Duration (Sec)</label>
                                            <input type="number" name="video_duration_seconds" class="form-control form-control-sm" value="{{ old('video_duration_seconds', 7200) }}">
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Package Type</label>
                                            <select name="product_type" class="form-select form-select-sm">
                                                <option value="course" @selected(old('product_type') == 'course')>Individual Course</option>
                                                <option value="bundle" @selected(old('product_type') == 'bundle')>Complete Series Bundle</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Card Accent Color</label>
                                            <div class="input-group input-group-sm">
                                                <input type="color" class="form-control form-control-color form-control-sm" id="accentColorPicker" value="{{ old('accent_color', '#FBAF43') }}" onchange="document.getElementById('accentColorHex').value = this.value">
                                                <input type="text" name="accent_color" id="accentColorHex" class="form-control form-control-sm font-monospace" value="{{ old('accent_color', '#FBAF43') }}" placeholder="#FBAF43">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Learning Outcome Headline</label>
                                            <input type="text" name="learning_outcome" class="form-control form-control-sm" value="{{ old('learning_outcome') }}" placeholder="e.g. Build your first simple vocal-processing workflow.">
                                        </div>
                                    </div>
                                </div>

                                <!-- Pricing Grid -->
                                <div class="row g-2 mb-3 align-items-center bg-light p-2 rounded border">
                                    <div class="col-12 col-sm-4">
                                        <label class="form-label fs-12 fw-bold text-dark mb-1">
                                            @lang('Package Price') (TZS)
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white">TZS</span>
                                            <input type="number" step="any" name="price" id="coursePrice" 
                                                   class="form-control form-control-sm" 
                                                   value="{{ old('price', '140000') }}" placeholder="140000">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <label class="form-label fs-12 fw-bold text-dark mb-1">
                                            @lang('Commercial License Price') (TZS)
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white">TZS</span>
                                            <input type="number" step="any" name="price_cl" id="coursePriceCl" 
                                                   class="form-control form-control-sm" 
                                                   value="{{ old('price_cl', '250000') }}" placeholder="250000">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4 pt-sm-3">
                                        <div class="form-check form-switch d-flex align-items-center gap-2">
                                            <input class="form-check-input" type="checkbox" name="is_free" value="1" id="is_free" @checked(old('is_free'))>
                                            <label class="form-check-label fs-12 fw-bold text-dark" for="is_free">
                                                @lang('100% Free Course')
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preview Video / Walkthrough URL -->
                                <div class="form-group mb-3">
                                    <label class="form-label fs-13 fw-bold text-dark mb-1">
                                        @lang('Preview Video / Audio Walkthrough URL')
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white text-muted">
                                            <i class="las la-video"></i>
                                        </span>
                                        <input type="url" name="demo_url" class="form-control form-control-sm" 
                                               value="{{ old('demo_url') }}" 
                                               placeholder="https://www.youtube.com/watch?v=... or direct MP4/HLS stream URL">
                                    </div>
                                    <small class="text-muted fs-11">@lang('Interactive video or audio walkthrough displayed in course player preview')</small>
                                </div>

                                <!-- Tags -->
                                <div class="form-group mb-3">
                                    <label class="form-label fs-13 fw-bold text-dark mb-1">
                                        @lang('Keywords & Tags (comma-separated)')
                                    </label>
                                    <input type="text" name="tags" class="form-control form-control-sm" 
                                           value="{{ old('tags', 'Vocal Mixing, Auto-Tune, FL Studio, Waves, Afrobeats') }}" 
                                           placeholder="Vocal Mixing, Pro Tools, Compression, Presets">
                                </div>

                                <!-- Description & Curriculum -->
                                <div class="form-group mb-2">
                                    <label class="form-label fs-13 fw-bold text-dark mb-1">
                                        @lang('Course Description & Curriculum Breakdown') <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="description" rows="10" class="form-control nicEdit" 
                                              placeholder="Comprehensive curriculum breakdown, lessons list, stems overview...">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <!-- Right Column: Media, Assets & Publishing Rail -->
                            <div class="col-12 col-lg-4">
                                <!-- Course Thumbnail Box -->
                                <div class="card mb-3 border rounded-3 bg-light">
                                    <div class="card-header bg-white py-2 px-3 border-bottom d-flex align-items-center justify-content-between">
                                        <span class="fs-12 fw-bold text-dark">
                                            <i class="las la-image text-primary me-1"></i>@lang('Course Thumbnail') <span class="text-danger">*</span>
                                        </span>
                                        <span class="badge bg-light text-muted border fs-10">600x400</span>
                                    </div>
                                    <div class="card-body p-2 text-center">
                                        <div class="image-upload-preview mb-2 d-flex align-items-center justify-content-center rounded bg-dark border" 
                                             style="height: 140px; overflow: hidden;">
                                            <img id="thumbPreview" src="{{ asset('assets/images/default.png') }}" 
                                                 alt="Thumbnail Preview" 
                                                 style="max-height: 130px; max-width: 100%; object-fit: contain;">
                                        </div>
                                        <input type="file" name="thumbnail" id="thumbnailInput" class="form-control form-control-sm bg-white" accept=".png, .jpg, .jpeg" required>
                                        <small class="text-muted fs-11 d-block mt-1">@lang('Recommended: 600x400px (JPG/PNG)')</small>
                                    </div>
                                </div>

                                <!-- Course Cover / Wide Banner Box -->
                                <div class="card mb-3 border rounded-3 bg-light">
                                    <div class="card-header bg-white py-2 px-3 border-bottom d-flex align-items-center justify-content-between">
                                        <span class="fs-12 fw-bold text-dark">
                                            <i class="las la-photo-video text-info me-1"></i>@lang('Wide Cover Banner')
                                        </span>
                                        <span class="badge bg-light text-muted border fs-10">1200x500</span>
                                    </div>
                                    <div class="card-body p-2 text-center">
                                        <div class="image-upload-preview mb-2 d-flex align-items-center justify-content-center rounded bg-dark border" 
                                             style="height: 120px; overflow: hidden;">
                                            <img id="coverPreview" src="{{ asset('assets/images/default.png') }}" 
                                                 alt="Cover Preview" 
                                                 style="max-height: 110px; max-width: 100%; object-fit: cover;">
                                        </div>
                                        <input type="file" name="preview_image" id="coverInput" class="form-control form-control-sm bg-white" accept=".png, .jpg, .jpeg">
                                        <small class="text-muted fs-11 d-block mt-1">@lang('Wide banner displayed on course overview')</small>
                                    </div>
                                </div>

                                <!-- Master Asset / Download Package (.zip) -->
                                <div class="card mb-3 border rounded-3 bg-light">
                                    <div class="card-header bg-white py-2 px-3 border-bottom d-flex align-items-center justify-content-between">
                                        <span class="fs-12 fw-bold text-dark">
                                            <i class="las la-file-archive text-warning me-1"></i>@lang('Download Package (.zip)')
                                        </span>
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle fs-10">Archive</span>
                                    </div>
                                    <div class="card-body p-2">
                                        <input type="file" name="main_file" class="form-control form-control-sm bg-white" accept=".zip,.rar,.tar.gz">
                                        <small class="text-muted fs-11 d-block mt-1">
                                            <i class="las la-info-circle me-1"></i>@lang('Complete bundled package (.zip archive with project files, stems & presets)')
                                        </small>
                                    </div>
                                </div>

                                <!-- Visibility & Publishing Options -->
                                <div class="card border rounded-3 bg-light">
                                    <div class="card-header bg-white py-2 px-3 border-bottom">
                                        <span class="fs-12 fw-bold text-dark">
                                            <i class="las la-sliders-h text-secondary me-1"></i>@lang('Publishing Settings')
                                        </span>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="form-group mb-2">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">@lang('Initial Status')</label>
                                            <select name="status" class="form-select form-select-sm bg-white">
                                                <option value="1" selected>@lang('Approved / Published')</option>
                                                <option value="0">@lang('Pending Review / Draft')</option>
                                            </select>
                                        </div>

                                        <div class="form-check form-switch mt-2 pt-1 border-top">
                                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" @checked(old('is_featured'))>
                                            <label class="form-check-label fs-12 fw-bold text-dark" for="is_featured">
                                                @lang('Feature on Home Page')
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer: Sticky or Clean Submit Bar -->
                    <div class="card-footer bg-white border-top py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <span class="text-muted fs-12">
                            <i class="las la-shield-alt text-success me-1"></i>@lang('Safe & instant publishing to catalog')
                        </span>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('admin.product.all') }}" class="btn btn-sm btn-outline-secondary">
                                @lang('Cancel')
                            </a>
                            <button type="submit" class="btn btn-sm btn--primary px-4 fw-bold">
                                <i class="las la-check-circle me-1"></i> @lang('Save & Publish Course')
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
    /* Native First Card Styles */
    .ak23-compact-card {
        border-radius: 8px;
        overflow: hidden;
    }
    .btn-xs {
        padding: 0.2rem 0.45rem;
        font-size: 0.75rem;
        border-radius: 4px;
        line-height: 1.2;
    }
    .fs-10 {
        font-size: 10px !important;
    }

    /* Mobile Minimum Screen Adjustments */
    @media (max-width: 768px) {
        .ak23-compact-card .card-header {
            padding: 8px 12px;
        }
        .image-upload-preview {
            height: 120px !important;
        }
    }
</style>
@endpush

@push('script')
    <script>
        (function($){
            "use strict";

            // Free Course Toggle Handler
            $('#is_free').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#coursePrice').val('0.00').prop('readonly', true);
                    $('#coursePriceCl').val('0.00').prop('readonly', true);
                } else {
                    $('#coursePrice').prop('readonly', false);
                    $('#coursePriceCl').prop('readonly', false);
                }
            });

            // Live image preview
            $('#thumbnailInput').on('change', function(){
                const file = this.files[0];
                if(file){
                    const reader = new FileReader();
                    reader.onload = function(e){
                        $('#thumbPreview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });

            $('#coverInput').on('change', function(){
                const file = this.files[0];
                if(file){
                    const reader = new FileReader();
                    reader.onload = function(e){
                        $('#coverPreview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });

        })(jQuery);
    </script>
@endpush
