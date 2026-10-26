@extends('admin.layouts.app')
@section('panel')

    @php
        $previewSrc = $product->thumbnail_url ?: ($product->preview_image ? getImage(getFilePath('productPreview') . '/' . productFilePath($product, 'preview_image'), getFileSize('productPreview')) : getImage(getFilePath('productPreview') . '/default.png'));
    @endphp

    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="row gy-4">
                <!-- Left Column: Visual Media & Audio Player -->
                <div class="col-md-5">
                    <div class="card border shadow-sm h-100">
                        <div class="card-header bg-white py-2 px-3 border-bottom d-flex align-items-center justify-content-between">
                            <h6 class="card-title m-0 fs-14 fw-bold text-dark">{{ __($product->title) }}</h6>
                            <span class="badge {{ $product->product_type == 'bundle' ? 'bg-warning-subtle text-warning-emphasis border border-warning-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle' }} rounded-pill px-2 py-1 fs-11">
                                {{ $product->product_type == 'bundle' ? __('Complete Bundle') : __('Course') }}
                            </span>
                        </div>
                        <div class="card-body p-3">
                            @if ($product->audio_temp_file && @$product->category->file_type == 'audio' && in_array('mp3', $product->category->preview_file_types ?? []))
                                <div class="audio-player-wrapper border rounded-3 p-3 bg-light mb-3">
                                    <div class="d-flex align-items-center gap-1 audio-player-left">
                                        <button id="play-button" class="play-button">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <span id="current-time">00:00</span>
                                    </div>

                                    <div class="audio-player-middle"
                                         data-file-path="{{ asset(getFilePath('previewFile')) . '/' . productFilePath($product, 'temp_audio_file') . '/' . $product->audio_temp_file }}"
                                         id="waveform"></div>

                                    <div class="audio-player-time">
                                        <span id="total-time">00:00</span>
                                    </div>
                                </div>
                            @else
                                <div class="image-upload rounded-3 overflow-hidden border bg-dark mb-3" style="max-height: 260px;">
                                    <img src="{{ $previewSrc }}" alt="{{ $product->title }}" class="w-100 h-100 object-fit-cover" style="max-height: 260px;" />
                                </div>
                            @endif

                            <div class="p-3 bg-light rounded-3 border">
                                <span class="fs-12 fw-bold text-dark d-block mb-1">
                                    <i class="las la-bullseye text-warning me-1"></i> @lang('Learning Outcome')
                                </span>
                                <p class="text-muted fs-12 mb-0">
                                    {{ $product->learning_outcome ?: __('Master professional vocal balance and acoustics in this complete studio training program.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Course Specifications & Details -->
                <div class="col-md-7">
                    <div class="card border shadow-sm">
                        <div class="card-header bg-white py-2 px-3 border-bottom d-flex align-items-center justify-content-between">
                            <span class="fs-13 fw-bold text-dark">
                                <i class="las la-info-circle text-primary me-1"></i> @lang('Course & Software Specifications')
                            </span>
                            <span class="font-monospace fs-11 text-muted">{{ $product->sku ?: 'NO-SKU' }}</span>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush fs-13">
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-2 px-3">
                                    <span class="fw-bold text-dark">@lang('Series / Category')</span>
                                    <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 rounded-pill">
                                        {{ __(@$product->category->name ?: 'Unassigned') }}
                                    </span>
                                </li>
                                @if(@$product->subCategory && $product->subCategory->name)
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-2 px-3">
                                        <span class="fw-bold text-dark">@lang('Subcategory')</span>
                                        <span class="text-muted">{{ __($product->subCategory->name) }}</span>
                                    </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-2 px-3">
                                    <span class="fw-bold text-dark">@lang('Included Software')</span>
                                    <span class="fw-semibold text-dark">
                                        {{ $product->software_name ?: __('Studio Suite') }}
                                        <span class="text-muted fs-11">({{ $product->software_edition ?: __('Studio License') }})</span>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-2 px-3">
                                    <span class="fw-bold text-dark">@lang('Stage / Level')</span>
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-pill">
                                        {{ $product->stage_label ?: __('Beginner') }} (Seq #{{ $product->sequence_no ?: 1 }})
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-2 px-3">
                                    <span class="fw-bold text-dark">@lang('Curriculum Structure')</span>
                                    <span class="text-dark fw-semibold">
                                        {{ $product->lesson_count ?: 8 }} @lang('Lessons') &middot; {{ round(($product->video_duration_seconds ?: 7200) / 60) }} @lang('Min Duration')
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-2 px-3">
                                    <span class="fw-bold text-dark">@lang('Accent Color')</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="rounded-circle border" style="width: 16px; height: 16px; background-color: {{ $product->accent_color ?: '#FBAF43' }}; display: inline-block;"></span>
                                        <span class="font-monospace fs-11">{{ $product->accent_color ?: '#FBAF43' }}</span>
                                    </div>
                                </li>
                                @if (!$product->is_free)
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-2 px-3">
                                        <span class="fw-bold text-dark">@lang('Package Price')</span>
                                        <span class="fw-bold text-dark fs-14">{{ showAmount($product->price) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-2 px-3">
                                        <span class="fw-bold text-dark">@lang('License Policy')</span>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                            <i class="las la-certificate me-1"></i> @lang('One-Time Lifetime License Included')
                                        </span>
                                    </li>
                                @else
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-2 px-3">
                                        <span class="fw-bold text-dark">@lang('Price')</span>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">@lang('Free')</span>
                                    </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-2 px-3">
                                    <span class="fw-bold text-dark">@lang('Status')</span>
                                    @php echo $product->statusBadge; @endphp
                                </li>
                                @if($product->demo_url || $product->preview_video)
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-2 px-3">
                                        <div>
                                            <span class="fw-bold text-dark d-block">@lang('Preview / Demo Video')</span>
                                            @if($product->video_embed_info)
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle fs-10">
                                                    <i class="las la-play-circle me-1"></i>{{ $product->video_embed_info->provider }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($product->video_embed_info)
                                                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 fs-11" data-bs-toggle="modal" data-bs-target="#adminPreviewModal">
                                                    <i class="las la-play me-1"></i>Watch Preview
                                                </button>
                                            @endif
                                            @if($product->demo_url)
                                                <a href="{{ $product->demo_url }}" target="_blank" class="text-primary text-truncate fs-12" style="max-width: 180px;" title="{{ $product->demo_url }}">
                                                    <i class="las la-external-link-alt me-1"></i> {{ $product->demo_url }}
                                                </a>
                                            @endif
                                        </div>
                                    </li>
                                @endif
                                @foreach ($product->attribute_info ?? [] as $info)
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-2 px-3">
                                        <span class="fw-bold text-dark">{{ __($info->name) }}</span>
                                        @if (is_array($info->value))
                                            <div>
                                                @foreach ($info->value as $val)
                                                    <span>{{ __($val) }}</span>
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <span>{{ __(@$info->value) }}</span>
                                        @endif
                                    </li>
                                @endforeach
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-2 px-3">
                                    <span class="fw-bold text-dark">@lang('Tags')</span>
                                    <div>
                                        @forelse ($product->tags ?? [] as $tag)
                                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-0 fs-11 mb-1">{{ __($tag) }}</span>
                                        @empty
                                            <span class="text-secondary fs-11">@lang('No Tags')</span>
                                        @endforelse
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-2 px-3">
                                    <span class="fw-bold text-dark">@lang('Published Date')</span>
                                    <span class="text-muted">{{ showDateTime($product->published_at ?: $product->created_at, 'd M, Y - h:i A') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />

    {{-- reject modal --}}
    <div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reason" class="form-label">@lang('Reason for rejection')</label>
                            <textarea name="reason" id="reason" class="form--control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($product->video_embed_info)
        <!-- Live Video Preview Modal -->
        <div class="modal fade" id="adminPreviewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content bg-dark border border-secondary text-white">
                    <div class="modal-header border-bottom border-secondary py-2 px-3">
                        <h6 class="modal-title fs-14 fw-bold text-warning mb-0">
                            <i class="las la-play-circle me-1"></i> {{ __($product->title) }} &middot; {{ $product->video_embed_info->provider }}
                        </h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0 bg-black">
                        <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;" id="adminPreviewContainer">
                            @if($product->video_embed_info->is_embed)
                                <iframe src="{{ $product->video_embed_info->embed_url }}" 
                                        style="position: absolute; top:0; left: 0; width: 100%; height: 100%; border: 0;" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen>
                                </iframe>
                            @elseif($product->video_embed_info->type === 'audio')
                                <div class="p-5 text-center" style="position: absolute; top:0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <i class="las la-compact-disc text-warning mb-3" style="font-size: 56px;"></i>
                                    <audio controls autoplay class="w-100" style="max-width: 480px;"><source src="{{ $product->video_embed_info->embed_url }}"></audio>
                                </div>
                            @else
                                <video controls autoplay playsinline style="position: absolute; top:0; left: 0; width: 100%; height: 100%; object-fit: contain;">
                                    <source src="{{ $product->video_embed_info->embed_url }}" type="video/mp4">
                                </video>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.rejectBtn').on('click', function() {
                var modal = $('#rejectModal');
                modal.find('.modal-title').text($(this).data('title'));
                modal.find('form').attr('action', $(this).data('action'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset(activeTemplate(true) . 'css/vendor/magnific-popup.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/wavesurfer.min.js') }}"></script>
    <script src="{{ asset(activeTemplate(true) . 'js/vendor/jquery.magnific-popup.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            $(document).ready(function() {

                if ($('#waveform').length) {
                    var wavesurfer = WaveSurfer.create({
                        container: '#waveform',
                        waveColor: 'rgba(0, 0, 0, 0.7)',
                        progressColor: '#FBAF43',
                        backend: 'MediaElement',
                        audioContext: new(window.AudioContext || window.webkitAudioContext)(),
                        height: 100,
                        barWidth: 2
                    });

                    var audioElement = $('#audio')[0];

                    wavesurfer.load(
                        '{{ asset(getFilePath('previewFile') . productFilePath($product, 'temp_audio_file') . $product->audio_temp_file) }}'
                    );

                    $('#play-button').on('click', function() {
                        if (wavesurfer.isPlaying()) {
                            wavesurfer.pause();
                            $(this).html('<i class="fas fa-play"></i>');
                        } else {
                            wavesurfer.play();
                            $(this).html('<i class="fas fa-pause"></i>');
                        }
                    });

                    $(audioElement).on('play', function() {
                        wavesurfer.play();
                        $('#play-button').html('<i class="fas fa-pause"></i>');
                    });

                    $(audioElement).on('pause', function() {
                        wavesurfer.pause();
                        $('#play-button').html('<i class="fas fa-play"></i>');
                    });

                    $(audioElement).on('seeked', function() {
                        wavesurfer.seekTo(audioElement.currentTime / audioElement.duration);
                    });

                    wavesurfer.on('finish', function() {
                        $('#play-button').html('<i class="fas fa-play"></i>');
                    });

                    wavesurfer.on('ready', function() {
                        var totalTime = wavesurfer.getDuration();
                        $('#total-time').text(formatTime(totalTime));
                    });

                    wavesurfer.on('audioprocess', function() {
                        var currentTime = wavesurfer.getCurrentTime();
                        $('#current-time').text(formatTime(currentTime));
                    });

                    function formatTime(seconds) {
                        var minutes = Math.floor(seconds / 60);
                        var seconds = Math.floor(seconds % 60);
                        return (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
                    }
                }

            });

            $('#adminPreviewModal').on('hidden.bs.modal', function () {
                var iframe = $(this).find('iframe');
                if (iframe.length) {
                    var src = iframe.attr('src');
                    iframe.attr('src', src);
                }
                var video = $(this).find('video');
                if (video.length) video[0].pause();
                var audio = $(this).find('audio');
                if (audio.length) audio[0].pause();
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .audio-player-wrapper {
            min-height: 163px !important;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0px 10px;
        }

        .audio-player-wrapper .play-button {
            width: 30px;
            height: 30px;
            background-color: #FBAF43 !important;
            border-radius: 3px;
            color: #121316 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }

        .audio-player-left {
            width: 80px;
            flex-shrink: 0;
            font-size: 12px;
        }

        .audio-player-time {
            width: 50px;
            font-size: 12px;
            flex-shrink: 0;
        }

        .audio-player-middle {
            width: 100%;
        }
    </style>
@endpush

@push('breadcrumb-plugins')
    <a href="{{ route('product.details', $product->slug) }}" target="_blank" class="btn btn-sm btn-outline--info">
        <i class="las la-external-link-alt"></i> @lang('View Live')
    </a>
    <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-sm btn--primary">
        <i class="las la-pen"></i> @lang('Edit Course')
    </a>
    <a href="{{ route('admin.product.all') }}" class="btn btn-sm btn-outline--primary">
        <i class="las la-arrow-left"></i> @lang('Back to Courses')
    </a>
@endpush
