<!-- Favicon & Touch Icons -->
<link rel="shortcut icon" href="{{ siteFavicon() }}" type="image/x-icon">
<link rel="icon" type="image/png" sizes="32x32" href="{{ siteFavicon() }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ siteFavicon() }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/logo_icon/apple-touch-icon.png') }}?v={{ file_exists(base_path('../assets/images/logo_icon/apple-touch-icon.png')) ? filemtime(base_path('../assets/images/logo_icon/apple-touch-icon.png')) : time() }}">

<!-- Theme & App Branding -->
<meta name="theme-color" content="#0C0E17">
<meta name="msapplication-TileColor" content="#0C0E17">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ gs('site_name') }}">

@if ($seo)
    <meta name="title" content="{{ gs()->siteName(__($pageTitle)) }}">
    <meta name="description" content="{{ @$seoContents->description ?? $seo->description }}">
    <meta name="keywords" content="{{ implode(',', (array)(@$seoContents->keywords ?? $seo->keywords)) }}">

    <!-- Google / Search Engine Tags -->
    <meta itemprop="name" content="{{ gs()->siteName($pageTitle) }}">
    <meta itemprop="description" content="{{ @$seoContents->description ?? $seo->description }}">
    <meta itemprop="image" content="{{ $seoImage ?? getImage(getFilePath('seo') . '/' . $seo->image) }}">

    <!-- Facebook & Open Graph Meta Tags -->
    <meta property="og:site_name" content="{{ gs('site_name') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ @$seoContents->social_title ?? $seo->social_title }}">
    <meta property="og:description" content="{{ @$seoContents->social_description ?? $seo->social_description }}">
    <meta property="og:image" content="{{ $seoImage ?? getImage(getFilePath('seo') . '/' . $seo->image) }}">
    <meta property="og:image:type" content="image/{{ pathinfo($seoImage ?? getImage(getFilePath('seo')) . '/' . $seo->image)['extension'] ?? 'png' }}">
    @php $socialImageSize = explode('x', getFileSize('seo')) @endphp
    <meta property="og:image:width" content="{{ $socialImageSize[0] ?? 1180 }}">
    <meta property="og:image:height" content="{{ $socialImageSize[1] ?? 600 }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ @$seoContents->social_title ?? $seo->social_title }}">
    <meta name="twitter:description" content="{{ @$seoContents->social_description ?? $seo->social_description }}">
    <meta name="twitter:image" content="{{ $seoImage ?? getImage(getFilePath('seo') . '/' . $seo->image) }}">
@endif
