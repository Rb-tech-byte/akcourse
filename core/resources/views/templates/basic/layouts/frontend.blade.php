<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>
    @include('partials.seo')
    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/icom-moon.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/template.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/ak23.css') }}?v={{ file_exists(public_path($activeTemplateTrue . 'css/ak23.css')) ? filemtime(public_path($activeTemplateTrue . 'css/ak23.css')) : time() }}">

    @stack('style-lib')

    @stack('style')

    <link rel="stylesheet"
        href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ gs('base_color') }}&secondColor={{ gs('secondary_color') }}" />
</head>

@php echo loadExtension('google-analytics') @endphp

<body>

    @stack('fbComment')

    <div class="body-overlay"></div>
    <div class="sidebar-overlay"></div>
    <a class="scroll-top"><i class="fas fa-angle-double-up"></i></a>

    @php
        $isHeaderFooterHide =
            request()->routeIs('maintenance') || (request()->routeIs('user.register') && !gs('registration')) || request()->routeIs('order.*');
    @endphp

    @if (!$isHeaderFooterHide)
        @include($activeTemplate . 'partials.header')
    @endif

    @yield('content')

    @if (!$isHeaderFooterHide)
        @include($activeTemplate . 'partials.footer')

        @php
            $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
        @endphp
        @if ($cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie'))
            <div class="cookies-card text-center hide">
                <div class="cookies-card__icon bg--base">
                    <i class="las la-cookie-bite"></i>
                </div>
                <p class="mt-4 cookies-card__content">{{ __($cookie->data_values->short_desc) }} <a
                        href="{{ route('cookie.policy') }}" target="_blank">@lang('learn more')</a></p>
                <div class="cookies-card__btn mt-4">
                    <a href="javascript:void(0)" class="btn btn--base w-100 policy">@lang('Allow')</a>
                </div>
            </div>
        @endif
    @endif

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>

    @stack('script-lib')

    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>

    @include('partials.notify')

    @if (gs('pn'))
        @include('partials.push_script')
    @endif

    @include('Template::partials.waveserfer')

    <script>
        (function($) {
            "use strict";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            $('.select2').select2();

            $('.language_switcher > .language_switcher__caption').on('click', function() {
                $(this).parent().toggleClass('open');
            });
            $(document).on('keyup', function(evt) {
                if ((evt.keyCode || evt.which) === 27) {
                    $('.language_switcher').removeClass('open');
                }
            });
            $(document).on('click', function(evt) {
                if ($(evt.target).closest(".language_switcher > .language_switcher__caption").length === 0) {
                    $('.language_switcher').removeClass('open');
                }
            });

            $('.policy').on('click', function() {
                $.get('{{ route('cookie.accept') }}', function(response) {
                    $('.cookies-card').addClass('d-none');
                });
            });

            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);

            var inputElements = $('[type=text],[type=password],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {
                var elementType = $(element);
                if (elementType.attr('type') != 'checkbox') {
                    if (element.hasAttribute('required')) {
                        $(element).closest('.form-group').find('label').addClass('required');
                    }
                }
            });

            Array.from(document.querySelectorAll('table')).forEach(table => {
                let heading = table.querySelectorAll('thead tr th');
                Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                    Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
                        colum.setAttribute('data-label', heading[i].innerText)
                    });
                });
            });

            $(".toggle-fav-button").on("click", function(e) {
                e.preventDefault();
                const productId = $(this).data("product-id");
                const url = $(this).data("route");
                $(this).toggleClass("wishlisted");

                $.ajax({
                    url,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    method: "POST",
                    data: {
                        product_id: productId
                    },
                });
            });

            let disableSubmission = false;
            $('.disableSubmission').on('submit', function(e) {
                if (disableSubmission) {
                    e.preventDefault()
                } else {
                    disableSubmission = true;
                }
            });

            $('.select2').each(function(index, element) {
                $(element).select2();
            });

            $(document).on('click', '.advertisement-click', function(e) {
                let advertisementId = $(this).data('id');

                $.ajax({
                    url: "{{ url('click') }}/" + advertisementId,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        console.log(response.success);
                    },
                    error: function(error) {
                        console.error('Error:', error.responseJSON.error);
                    }
                });
            });


        })(jQuery);
    </script>

    @stack('script')

    @php echo loadExtension('whatsapp-chat') @endphp

</body>

</html>
