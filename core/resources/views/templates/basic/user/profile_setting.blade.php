@extends($activeTemplate . 'layouts.portal')

@section('content')

<!-- =========================================================================
     HERO BANNER: PROFILE SETTINGS
     ========================================================================= -->
<div class="ak23-portal-hero">
    <div class="ak23-portal-hero-left">
        <div class="ak23-portal-hero-tag">
            <i class="las la-user-cog me-1"></i> ACCOUNT SETTINGS &middot; {{ strtoupper(auth()->user()->username ?? 'STUDENT') }}
        </div>
        <h1 class="ak23-portal-hero-title">Profile &amp; Preferences</h1>
        <p class="ak23-portal-hero-sub">
            Manage your personal profile, display identity, biographical notes, and social connectivity.
        </p>
    </div>
</div>

<form action="{{ route('user.profile.setting.submit') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row gy-3">
        @include($activeTemplate . 'user.settings.sidebar')
        @include($activeTemplate . 'user.settings.index')
    </div>
</form>

@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/nicEdit.js') }}"></script>
@endpush

@push('script')
    <script>
        bkLib.onDomLoaded(function() {
            $(".nicEdit").each(function(index) {
                $(this).attr("id", "nicEditor" + index);
                new nicEditor({
                    fullPanel: true
                }).panelInstance('nicEditor' + index, {
                    hasPanel: true
                });
            });
        });

        (function($) {
            "use strict";

            function updateActiveLink() {
                var scrollPosition = $(window).scrollTop() + 52;

                $('.setting-sidebar-list__link').each(function() {
                    var target = $($(this).attr('href'));
                    if (target.position().top <= scrollPosition && target.position().top + target.height() > scrollPosition) {
                        $('.setting-sidebar-list__link').removeClass('active');
                        $(this).addClass('active');
                    }
                });
            }

            var currentHash = window.location.hash;
            $('.setting-sidebar-list__link[href="' + currentHash + '"]').addClass('active');

            $(window).on('scroll', function() {
                updateActiveLink();
            });

        })(jQuery);
    </script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}" />
@endpush
