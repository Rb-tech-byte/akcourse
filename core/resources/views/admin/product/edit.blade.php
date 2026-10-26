@extends('admin.layouts.app')

@section('panel')

    <!-- Print Header (Visible ONLY on PDF / Print Export) -->
    <div class="d-none d-print-block mb-3 p-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1 text-dark fw-bold">{{ gs('site_name') }} &middot; @lang('Course Specification Sheet')</h3>
                <p class="text-muted mb-0 small">Export generated on {{ now()->format('F d, Y - H:i:s') }} &middot; Course #{{ $product->id }}: {{ __($product->title) }}</p>
            </div>
            <div class="text-end">
                <span class="badge bg-dark text-white px-3 py-2 fs-12">
                    {{ $product->status == 1 ? __('Published') : __('Draft / Pending') }}
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Main First Card (Compact, Responsive, Native) -->
            <div class="card ak23-compact-card border shadow-sm">
                <!-- Card Header -->
                <div class="card-header bg-white py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="las la-edit text-primary fs-20"></i>
                        <h6 class="card-title mb-0 fs-15 fw-bold text-dark">
                            @lang('Edit Course') <span class="badge bg-secondary-subtle text-secondary font-monospace border px-2 py-0 fs-11">#{{ $product->id }}</span>
                        </h6>
                        <span class="badge {{ $product->status == 1 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle' }} rounded-pill px-2 py-1 fs-11">
                            {{ $product->status == 1 ? __('Published') : __('Pending Review') }}
                        </span>
                    </div>

                    <!-- Actions: Preview Live, Print / PDF Spec, Back -->
                    <div class="d-flex align-items-center gap-2 flex-wrap d-print-none">
                        <a href="{{ route('product.details', $product->slug) }}" target="_blank" class="btn btn-xs btn-outline-info d-flex align-items-center gap-1" title="@lang('View Live on Store')">
                            <i class="las la-external-link-alt"></i>
                            <span class="d-none d-sm-inline">@lang('Preview Live')</span>
                        </a>
                        <button type="button" onclick="window.print()" class="btn btn-xs btn-outline-secondary d-flex align-items-center gap-1" title="@lang('Print Course Spec Sheet')">
                            <i class="las la-print"></i>
                            <span class="d-none d-sm-inline">@lang('PDF / Print')</span>
                        </button>
                        <a href="{{ route('admin.product.all') }}" class="btn btn-xs btn-outline-secondary d-flex align-items-center gap-1">
                            <i class="las la-arrow-left"></i>
                            <span>@lang('All Courses')</span>
                        </a>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.product.save', $product->id) }}" method="POST" enctype="multipart/form-data" id="courseEditForm">
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
                                           value="{{ old('title', $product->title) }}" required>
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
                                                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
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
                                                    <option value="{{ $sub->id }}" data-category="{{ $category->id }}" @selected(old('sub_category_id', $product->sub_category_id) == $sub->id)>
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
                                            <input type="text" name="sku" class="form-control form-control-sm font-monospace" value="{{ old('sku', $product->sku) }}" placeholder="AK23-COURSE-...">
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Matching Software Name</label>
                                            <input type="text" name="software_name" class="form-control form-control-sm" value="{{ old('software_name', $product->software_name) }}" placeholder="e.g. Voxity, Nectar 4, Cubase">
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Software Edition / License</label>
                                            <input type="text" name="software_edition" class="form-control form-control-sm" value="{{ old('software_edition', $product->software_edition) }}" placeholder="e.g. Standard Studio Edition">
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-12 col-sm-4">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Stage Label</label>
                                            <input type="text" name="stage_label" class="form-control form-control-sm" value="{{ old('stage_label', $product->stage_label) }}" placeholder="e.g. Getting Started, Beginner, Advanced">
                                        </div>
                                        <div class="col-6 col-sm-2">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Seq No</label>
                                            <input type="number" name="sequence_no" class="form-control form-control-sm" value="{{ old('sequence_no', $product->sequence_no ?: 1) }}">
                                        </div>
                                        <div class="col-6 col-sm-3">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Lessons Count</label>
                                            <input type="number" name="lesson_count" class="form-control form-control-sm" value="{{ old('lesson_count', $product->lesson_count ?: 8) }}">
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Video Duration (Sec)</label>
                                            <input type="number" name="video_duration_seconds" class="form-control form-control-sm" value="{{ old('video_duration_seconds', $product->video_duration_seconds ?: 7200) }}">
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Package Type</label>
                                            <select name="product_type" class="form-select form-select-sm">
                                                <option value="course" @selected(old('product_type', $product->product_type) == 'course')>Individual Course</option>
                                                <option value="bundle" @selected(old('product_type', $product->product_type) == 'bundle')>Complete Series Bundle</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Card Accent Color</label>
                                            <div class="input-group input-group-sm">
                                                <input type="color" class="form-control form-control-color form-control-sm" id="accentColorPicker" value="{{ old('accent_color', $product->accent_color ?: '#FBAF43') }}" onchange="document.getElementById('accentColorHex').value = this.value">
                                                <input type="text" name="accent_color" id="accentColorHex" class="form-control form-control-sm font-monospace" value="{{ old('accent_color', $product->accent_color ?: '#FBAF43') }}" placeholder="#FBAF43">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">Learning Outcome Headline</label>
                                            <input type="text" name="learning_outcome" class="form-control form-control-sm" value="{{ old('learning_outcome', $product->learning_outcome) }}" placeholder="e.g. Build your first simple vocal-processing workflow.">
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
                                                   value="{{ old('price', (int)$product->price) }}" placeholder="140000">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <label class="form-label fs-12 fw-bold text-dark mb-1">
                                            @lang('License Model')
                                        </label>
                                        <div class="p-1 px-2 rounded bg-light border text-success fs-12 d-flex align-items-center gap-1" style="min-height: 31px;">
                                            <i class="las la-certificate"></i>
                                            <span class="fw-semibold">@lang('One-Time Lifetime License Included')</span>
                                        </div>
                                        <input type="hidden" name="price_cl" value="0">
                                    </div>
                                    <div class="col-12 col-sm-4 pt-sm-3">
                                        <div class="form-check form-switch d-flex align-items-center gap-2">
                                            <input class="form-check-input" type="checkbox" name="is_free" value="1" id="is_free" @checked(old('is_free', $product->is_free))>
                                            <label class="form-check-label fs-12 fw-bold text-dark" for="is_free">
                                                @lang('100% Free Course')
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preview Video / Walkthrough URL -->
                                <div class="card mb-3 border rounded-3 bg-light shadow-sm">
                                    <div class="card-header bg-white py-2 px-3 border-bottom d-flex align-items-center justify-content-between">
                                        <span class="fs-12 fw-bold text-dark">
                                            <i class="las la-play-circle text-danger me-1 fs-15"></i>@lang('Preview Video / Audio Walkthrough')
                                        </span>
                                        <div id="videoProviderBadge">
                                            @if($product->video_embed_info)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle fs-10">
                                                    <i class="las la-check-circle me-1"></i>{{ $product->video_embed_info->provider }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-muted fs-10">
                                                    @lang('No Preview Set')
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="input-group input-group-sm mb-1">
                                            <span class="input-group-text bg-white text-muted">
                                                <i class="las la-video"></i>
                                            </span>
                                            <input type="text" name="demo_url" id="demoUrlInput" class="form-control form-control-sm bg-white" 
                                                   value="{{ old('demo_url', $product->demo_url) }}" 
                                                   placeholder="Paste YouTube, Vimeo, Google Drive, or direct MP4/Audio URL">
                                            
                                            <button type="button" class="btn btn-outline-danger btn-sm px-2 fw-semibold" id="btnAdminPreviewVideo" title="@lang('Live Video Preview')">
                                                <i class="las la-play me-1"></i>@lang('Preview')
                                            </button>
                                            
                                            <a href="{{ $product->demo_url ?: '#' }}" target="_blank" 
                                               class="btn btn-outline-secondary btn-sm {{ empty($product->demo_url) ? 'd-none' : '' }}" 
                                               id="btnAdminOpenUrl" title="@lang('Open link in new tab')">
                                                <i class="las la-external-link-alt"></i>
                                            </a>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mt-1">
                                            <small class="text-muted fs-10">
                                                <i class="las la-info-circle me-1"></i>@lang('Supports YouTube, Vimeo, Google Drive, MP4, and Audio walkthroughs.')
                                            </small>
                                            <span class="text-muted fs-10">
                                                @lang('Instant live playback available')
                                            </span>
                                        </div>

                                        <!-- Inline Live Player Preview Box -->
                                        <div id="adminVideoInlineBox" class="mt-2 rounded-2 overflow-hidden border bg-black d-none" style="position: relative;">
                                            <div class="p-1 px-2 bg-dark d-flex align-items-center justify-content-between">
                                                <span class="fs-11 text-white fw-bold" id="inlinePlayerTitle">Live Preview Player</span>
                                                <button type="button" class="btn-close btn-close-white p-1" style="font-size: 8px;" id="btnCloseInlinePlayer" aria-label="Close"></button>
                                            </div>
                                            <div id="adminPlayerContainer" style="min-height: 220px; max-height: 320px; position: relative; background: #000;">
                                                <!-- Dynamic player injected via JS -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tags -->
                                <div class="form-group mb-3">
                                    <label class="form-label fs-13 fw-bold text-dark mb-1">
                                        @lang('Keywords & Tags (comma-separated)')
                                    </label>
                                    <input type="text" name="tags" class="form-control form-control-sm" 
                                           value="{{ old('tags', is_array($product->tags) ? implode(', ', $product->tags) : (is_string($product->tags) ? $product->tags : '')) }}" 
                                           placeholder="Vocal Mixing, Pro Tools, Compression, Presets">
                                </div>

                                <!-- Description & Curriculum -->
                                <div class="form-group mb-2">
                                    <label class="form-label fs-13 fw-bold text-dark mb-1">
                                        @lang('Course Description & Curriculum Breakdown') <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="description" rows="10" class="form-control nicEdit" 
                                              placeholder="Comprehensive curriculum breakdown, lessons list, stems overview...">{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>

                            <!-- Right Column: Media, Assets & Publishing Rail -->
                            <div class="col-12 col-lg-4">
                                <!-- Course Thumbnail Box -->
                                <div class="card mb-3 border rounded-3 bg-light">
                                    <div class="card-header bg-white py-2 px-3 border-bottom d-flex align-items-center justify-content-between">
                                        <span class="fs-12 fw-bold text-dark">
                                            <i class="las la-image text-primary me-1"></i>@lang('Course Thumbnail')
                                        </span>
                                        <span class="badge bg-light text-muted border fs-10">600x400</span>
                                    </div>
                                    <div class="card-body p-2 text-center">
                                        <div class="image-upload-preview mb-2 d-flex align-items-center justify-content-center rounded bg-dark border" 
                                             style="height: 140px; overflow: hidden;">
                                            <img id="thumbPreview" 
                                                 src="{{ $product->thumbnail_url }}" 
                                                 alt="{{ $product->title }}" 
                                                 style="max-height: 130px; max-width: 100%; object-fit: contain;">
                                        </div>
                                        <input type="file" name="thumbnail" id="thumbnailInput" class="form-control form-control-sm bg-white" accept=".png, .jpg, .jpeg">
                                        <small class="text-muted fs-11 d-block mt-1">@lang('Leave empty to preserve existing thumbnail')</small>
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
                                            <img id="coverPreview" 
                                                 src="{{ $product->thumbnail_url }}" 
                                                 alt="{{ $product->title }} Cover" 
                                                 style="max-height: 110px; max-width: 100%; object-fit: cover;">
                                        </div>
                                        <input type="file" name="preview_image" id="coverInput" class="form-control form-control-sm bg-white" accept=".png, .jpg, .jpeg">
                                        <small class="text-muted fs-11 d-block mt-1">@lang('Leave empty to preserve existing banner')</small>
                                    </div>
                                </div>

                                <!-- Course Download Distribution Package (ZIP Upload & Cloud URL) -->
                                <div class="card mb-3 border rounded-3 bg-light shadow-sm">
                                    <div class="card-header bg-white py-2 px-3 border-bottom d-flex align-items-center justify-content-between">
                                        <span class="fs-12 fw-bold text-dark">
                                            <i class="las la-cloud-download-alt text-primary me-1"></i>@lang('Course Download & Files')
                                        </span>
                                        <div class="d-flex align-items-center gap-1">
                                            @if($product->file && $product->download_url)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle fs-10">
                                                    <i class="las la-check-double me-1"></i>ZIP + Cloud Link
                                                </span>
                                            @elseif($product->file)
                                                <span class="badge bg-info-subtle text-info border border-info-subtle fs-10">
                                                    <i class="las la-file-archive me-1"></i>Local ZIP
                                                </span>
                                            @elseif($product->download_url)
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-10">
                                                    <i class="las la-link me-1"></i>Cloud Link
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle fs-10">
                                                    @lang('No File Set')
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <!-- Option 1: Direct File Archive (.zip, .rar, etc.) -->
                                        <div class="p-2 rounded bg-white border mb-2">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <label class="fs-11 fw-bold text-dark mb-0 d-flex align-items-center">
                                                    <i class="las la-file-archive text-warning me-1 fs-14"></i>
                                                    @lang('Option 1: Upload Archive (.zip, .rar)')
                                                </label>
                                                @if($product->file)
                                                    <span class="badge bg-success-subtle text-success fs-10">
                                                        <i class="las la-check-circle"></i> Ready
                                                    </span>
                                                @endif
                                            </div>

                                            @if($product->file)
                                                <div class="alert alert-light border py-1 px-2 mb-2 d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center gap-1 text-truncate">
                                                        <i class="las la-file-alt text-success fs-14"></i>
                                                        <span class="text-dark fs-11 font-monospace text-truncate">{{ $product->file }}</span>
                                                    </div>
                                                    <div class="form-check form-check-inline m-0">
                                                        <input class="form-check-input" type="checkbox" name="remove_main_file" value="1" id="removeFileCheck">
                                                        <label class="form-check-label fs-10 text-danger fw-semibold" for="removeFileCheck">
                                                            @lang('Remove')
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif

                                            <input type="file" name="main_file" class="form-control form-control-sm bg-light" accept=".zip,.rar,.tar.gz,.7z">
                                            <small class="text-muted fs-10 d-block mt-1">
                                                <i class="las la-info-circle me-1"></i>@lang('Upload local archive (.zip/.rar) for direct student download')
                                            </small>
                                        </div>

                                        <!-- Option 2: External Cloud URL (Google Drive, Dropbox, Mega, etc.) -->
                                        <div class="p-2 rounded bg-white border">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <label class="fs-11 fw-bold text-dark mb-0 d-flex align-items-center" for="downloadUrlInput">
                                                    <i class="lab la-google-drive text-success me-1 fs-14"></i>
                                                    @lang('Option 2: Cloud / External Link (Google Drive)')
                                                </label>
                                                @if($product->download_url)
                                                    <a href="{{ $product->download_url }}" target="_blank" class="badge bg-primary text-white text-decoration-none fs-10" title="Test Link">
                                                        <i class="las la-external-link-alt me-1"></i>Test URL
                                                    </a>
                                                @endif
                                            </div>

                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-light text-muted"><i class="las la-link"></i></span>
                                                <input type="text" name="download_url" id="downloadUrlInput" 
                                                       class="form-control form-control-sm bg-light" 
                                                       placeholder="https://drive.google.com/file/d/... or Dropbox/Mega link" 
                                                       value="{{ old('download_url', $product->download_url) }}">
                                                @if($product->download_url)
                                                    <a href="{{ $product->download_url }}" target="_blank" class="btn btn-outline-secondary" title="Open share link">
                                                        <i class="las la-external-link-alt"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <small class="text-muted fs-10 d-block mt-1">
                                                <i class="las la-info-circle me-1"></i>@lang('Paste Google Drive, Dropbox, Mega, or OneDrive shareable link')
                                            </small>
                                        </div>

                                        <!-- Note: Both work simultaneously -->
                                        <div class="mt-2 text-muted fs-10 d-flex align-items-center gap-1">
                                            <i class="las la-shield-alt text-primary"></i>
                                            <span>@lang('Both options can be used together. Students can download either the direct .zip or via Google Drive mirror.')</span>
                                        </div>
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
                                            <label class="form-label fs-12 fw-bold text-dark mb-1">@lang('Catalog Status')</label>
                                            <select name="status" class="form-select form-select-sm bg-white">
                                                <option value="1" @selected(old('status', $product->status) == 1)>@lang('Approved / Published')</option>
                                                <option value="0" @selected(old('status', $product->status) == 0)>@lang('Pending Review / Draft')</option>
                                            </select>
                                        </div>

                                        <div class="form-check form-switch mt-2 pt-1 border-top">
                                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" @checked(old('is_featured', $product->is_featured))>
                                            <label class="form-check-label fs-12 fw-bold text-dark" for="is_featured">
                                                @lang('Feature on Home Page')
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer bg-white border-top py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2 d-print-none">
                        <span class="text-muted fs-12">
                            <i class="las la-history text-muted me-1"></i>@lang('Last updated'): {{ $product->updated_at ? $product->updated_at->diffForHumans() : 'Never' }}
                        </span>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('admin.product.all') }}" class="btn btn-sm btn-outline-secondary">
                                @lang('Cancel')
                            </a>
                            <button type="submit" class="btn btn-sm btn--primary px-4 fw-bold">
                                <i class="las la-save me-1"></i> @lang('Update Course')
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

    /* Print & PDF Export Styling */
    @media print {
        body {
            background: #fff !important;
            font-size: 11px !important;
        }
        .sidebar, .navbar-wrapper, .d-print-none, .res-sidebar-open-btn, .breadcrumb-wrapper, .modal {
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

            // Live Video / Walkthrough URL Resolver & Inline Player
            function resolveEmbedUrl(rawUrl) {
                if (!rawUrl) return null;
                rawUrl = rawUrl.trim();
                if (!rawUrl) return null;
                if (!/^https?:\/\//i.test(rawUrl)) {
                    rawUrl = 'https://' + rawUrl;
                }

                // 1. YouTube
                var ytMatch = rawUrl.match(/(?:youtube(?:-nocookie)?\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts|live)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i);
                if (ytMatch && ytMatch[1]) {
                    return {
                        type: 'youtube',
                        provider: 'YouTube',
                        embedUrl: 'https://www.youtube-nocookie.com/embed/' + ytMatch[1] + '?autoplay=1&rel=0&modestbranding=1',
                        isEmbed: true,
                        rawUrl: rawUrl
                    };
                }

                // 2. Vimeo
                var vimeoMatch = rawUrl.match(/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)/i);
                if (vimeoMatch && vimeoMatch[3]) {
                    return {
                        type: 'vimeo',
                        provider: 'Vimeo',
                        embedUrl: 'https://player.vimeo.com/video/' + vimeoMatch[3] + '?autoplay=1&title=0&byline=0',
                        isEmbed: true,
                        rawUrl: rawUrl
                    };
                }

                // 3. Google Drive
                var driveMatch = rawUrl.match(/(?:drive|docs)\.google\.com\/(?:file\/d\/|open\?id=|uc\?(?:.*&)?id=)([a-zA-Z0-9_-]+)/i);
                if (driveMatch && driveMatch[1]) {
                    return {
                        type: 'gdrive',
                        provider: 'Google Drive',
                        embedUrl: 'https://drive.google.com/file/d/' + driveMatch[1] + '/preview',
                        isEmbed: true,
                        rawUrl: rawUrl
                    };
                }

                // 4. Loom
                var loomMatch = rawUrl.match(/loom\.com\/(?:share|embed)\/([a-zA-Z0-9_-]+)/i);
                if (loomMatch && loomMatch[1]) {
                    return {
                        type: 'loom',
                        provider: 'Loom',
                        embedUrl: 'https://www.loom.com/embed/' + loomMatch[1],
                        isEmbed: true,
                        rawUrl: rawUrl
                    };
                }

                // 5. Streamable
                var stMatch = rawUrl.match(/streamable\.com\/(?:e\/)?([a-zA-Z0-9_-]+)/i);
                if (stMatch && stMatch[1]) {
                    return {
                        type: 'streamable',
                        provider: 'Streamable',
                        embedUrl: 'https://streamable.com/e/' + stMatch[1],
                        isEmbed: true,
                        rawUrl: rawUrl
                    };
                }

                // 6. SoundCloud
                if (/soundcloud\.com\/[^\/]+\/[^\/\s]+/i.test(rawUrl)) {
                    return {
                        type: 'soundcloud',
                        provider: 'SoundCloud',
                        embedUrl: 'https://w.soundcloud.com/player/?url=' + encodeURIComponent(rawUrl) + '&color=%23f0a62f&auto_play=false&show_teaser=true',
                        isEmbed: true,
                        rawUrl: rawUrl
                    };
                }

                // 7. Audio
                if (/\.(mp3|wav|m4a|ogg|aac|flac)(\?.*)?$/i.test(rawUrl)) {
                    return {
                        type: 'audio',
                        provider: 'Audio Walkthrough',
                        embedUrl: rawUrl,
                        isEmbed: false,
                        rawUrl: rawUrl
                    };
                }

                // 8. Direct Video
                return {
                    type: 'video',
                    provider: 'Direct Video (MP4)',
                    embedUrl: rawUrl,
                    isEmbed: false,
                    rawUrl: rawUrl
                };
            }

            function updateProviderBadge() {
                var url = $('#demoUrlInput').val();
                var info = resolveEmbedUrl(url);
                if (info) {
                    $('#videoProviderBadge').html('<span class="badge bg-success-subtle text-success border border-success-subtle fs-10"><i class="las la-check-circle me-1"></i>' + info.provider + '</span>');
                    $('#btnAdminOpenUrl').attr('href', info.rawUrl).removeClass('d-none');
                } else {
                    $('#videoProviderBadge').html('<span class="badge bg-secondary-subtle text-muted fs-10">No Preview Set</span>');
                    $('#btnAdminOpenUrl').addClass('d-none');
                }
            }

            $('#demoUrlInput').on('input change paste', function() {
                setTimeout(updateProviderBadge, 100);
            });

            $('#btnAdminPreviewVideo').on('click', function() {
                var url = $('#demoUrlInput').val();
                var info = resolveEmbedUrl(url);
                if (!info) {
                    alert('Please enter a YouTube, Vimeo, Google Drive, Loom, Streamable, SoundCloud, or direct video/audio URL first.');
                    $('#demoUrlInput').focus();
                    return;
                }

                var container = $('#adminPlayerContainer');
                container.empty();
                $('#inlinePlayerTitle').text(info.provider + ' Live Player');

                if (info.isEmbed) {
                    var h = (info.type === 'soundcloud') ? '166px' : '260px';
                    container.html('<iframe src="' + info.embedUrl + '" style="width: 100%; height: ' + h + '; border: 0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
                } else if (info.type === 'audio') {
                    container.html('<div class="p-4 text-center"><audio controls autoplay class="w-100" style="max-width: 450px;"><source src="' + info.embedUrl + '"></audio></div>');
                } else {
                    container.html('<video controls autoplay playsinline style="width: 100%; max-height: 260px; background: #000;"><source src="' + info.embedUrl + '" type="video/mp4"></video>');
                }

                $('#adminVideoInlineBox').removeClass('d-none');
            });

            $('#btnCloseInlinePlayer').on('click', function() {
                $('#adminPlayerContainer').empty();
                $('#adminVideoInlineBox').addClass('d-none');
            });

        })(jQuery);
    </script>
@endpush
