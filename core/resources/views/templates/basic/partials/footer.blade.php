<footer class="ak23-footer">
    <div class="ak23-container">
        <div class="ak23-footer-top">
            <div>
                <a class="ak23-brand" href="{{ route('home') }}">
                    <img src="{{ asset('assets/images/logo_icon/logo_dark.png') }}" alt="{{ gs('site_name') }}" style="height: 28px; max-width: 140px; object-fit: contain;">
                </a>
                <p class="ak23-footer-tagline">Practical audio & software education. Built for modern creators.</p>
            </div>

            <ul class="ak23-footer-links">
                <li><a href="{{ route('products') }}" class="ak23-footer-link">COURSES</a></li>
                <li><a href="{{ route('faq') }}" class="ak23-footer-link">FAQ</a></li>
                <li><a href="{{ route('contact') }}" class="ak23-footer-link">SUPPORT</a></li>
                <li><a href="{{ route('policy.pages', 'privacy-policy') }}" class="ak23-footer-link">LICENSE TERMS</a></li>
            </ul>
        </div>

        <div class="ak23-footer-bottom">
            <span>&copy; {{ date('Y') }} {{ gs('site_name') }} Academy &middot; All rights reserved.</span>
        </div>
    </div>
</footer>
