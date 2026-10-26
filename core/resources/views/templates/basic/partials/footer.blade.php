<footer class="ak23-footer">
    <div class="ak23-container">
        <div class="ak23-footer-top">
            <div>
                <a class="ak23-brand" href="{{ route('home') }}">
                    <img src="{{ siteLogo('dark') }}" alt="{{ gs('site_name') }}" style="height: 28px; max-width: 175px; width: auto; object-fit: contain;">
                </a>
                <p class="ak23-footer-tagline">@lang('Practical audio & software education. Built for modern creators.')</p>
            </div>

            <ul class="ak23-footer-links">
                <li><a href="{{ route('products') }}" class="ak23-footer-link">@lang('COURSES')</a></li>
                <li><a href="{{ route('faq') }}" class="ak23-footer-link">@lang('FAQ')</a></li>
                <li><a href="{{ route('contact') }}" class="ak23-footer-link">@lang('SUPPORT')</a></li>
                <li><a href="{{ route('policy.pages', 'privacy-policy') }}" class="ak23-footer-link">@lang('LICENSE TERMS')</a></li>
            </ul>
        </div>

        <div class="ak23-footer-bottom">
            <span>&copy; {{ date('Y') }} {{ gs('site_name') }} Academy &middot; @lang('All rights reserved.')</span>
        </div>
    </div>
</footer>
