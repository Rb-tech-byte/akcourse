@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="profile-content py-5">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-8">
                    <div class="card custom--card border-0 shadow-sm overflow-hidden mb-4">
                        <div class="profile-content__thumb position-relative" style="height: 220px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                            @if (@$author->cover_img)
                                <img src="{{ getImage(getFilePath('authorAvatar') . '/' . @$author->cover_img) }}" class="w-100 h-100 object-fit-cover" alt="@lang('Cover Image')">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.25), transparent);">
                                    <span class="text-white-50 fs-18"><i class="las la-graduation-cap la-2x"></i></span>
                                </div>
                            @endif
                            <div class="position-absolute bottom-0 start-0 p-4 d-flex align-items-center gap-3">
                                <div class="avatar-wrapper rounded-circle border border-3 border-dark overflow-hidden bg-white shadow" style="width: 80px; height: 80px;">
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . @$author->image, getFileSize('userProfile'), true) }}" class="w-100 h-100 object-fit-cover" alt="{{ $author->fullname }}">
                                </div>
                                <div>
                                    <h4 class="text-white mb-1">{{ $author->fullname }}</h4>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-primary px-3 py-1 rounded-pill fs-12"><i class="las la-user-graduate me-1"></i> AK23 Student</span>
                                        <span class="text-white-50 fs-13">@lang('Member since') {{ showDateTime($author->created_at, 'M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4 pt-3">
                            <h5 class="card-title fw-bold text-dark mb-3">@lang('About Student')</h5>
                            <div class="text-muted leading-relaxed">
                                @if (@$author->bio)
                                    @php echo @$author->bio @endphp
                                @else
                                    <p class="mb-0 text-secondary">@lang('Passionate music producer and audio mixing student at AK23 Academy.')</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card custom--card border-0 shadow-sm p-4">
                        <h5 class="fw-bold text-dark mb-3"><i class="las la-certificate text-primary me-2"></i>@lang('Academy Status')</h5>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <span class="text-muted fs-13 d-block">@lang('Enrolled Courses')</span>
                                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ @$author->orders ? $author->orders()->count() : 0 }}</h4>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <span class="text-muted fs-13 d-block">@lang('Academy Campus')</span>
                                    <h5 class="fw-bold text-primary mb-0 mt-1">AK23 Vocal Mixing Studio</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 ps-xl-4">
                    <div class="card custom--card border-0 shadow-sm p-4 mb-4">
                        <h6 class="fw-bold text-dark mb-3">@lang('Instructor & Platform Support')</h6>
                        <p class="text-muted fs-13 mb-3">@lang('Need assistance with your lessons or studio kits? Our lead instructors are ready to help.')</p>
                        <a href="{{ route('ticket.open') }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill"><i class="las la-life-ring me-1"></i> @lang('Get Studio Support')</a>
                    </div>

                    @include($activeTemplate . 'partials.social_profile')
                </div>
            </div>
        </div>
    </div>
@endsection
