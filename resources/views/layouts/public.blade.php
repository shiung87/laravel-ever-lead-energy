@php
    $siteName = \App\Models\WebsiteSetting::getValue('site_name', 'Everlead Energy');
    $defaultTitle = \App\Models\WebsiteSetting::getValue('meta_title_default', 'Everlead Energy | Car Battery Solutions');
    $defaultDesc = \App\Models\WebsiteSetting::getValue('meta_description_default', 'Reliable Car Battery Solutions for Every Drive.');
    $ga4Id = \App\Models\WebsiteSetting::getValue('ga4_measurement_id');
    $gtmId = \App\Models\WebsiteSetting::getValue('gtm_container_id');
    $metaPixelId = \App\Models\WebsiteSetting::getValue('meta_pixel_id');
    $waEnabled = \App\Models\WebsiteSetting::getValue('whatsapp_enabled', '1') === '1';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('meta_title', $defaultTitle)</title>
    <meta name="description" content="@yield('meta_description', $defaultDesc)">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <!-- Open Graph -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="@yield('meta_title', $defaultTitle)">
    <meta property="og:description" content="@yield('meta_description', $defaultDesc)">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/hero-battery.svg'))">

    @stack('schema')

    @if($gtmId)
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{ $gtmId }}');</script>
    @endif

    @if($ga4Id)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $ga4Id }}');
        </script>
    @endif

    @if($metaPixelId)
        <script>
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $metaPixelId }}');
            fbq('track', 'PageView');
        </script>
    @endif

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    @if($gtmId)
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    <x-public.header />

    <main>
        @yield('content')
    </main>

    <x-public.footer />

    @if($waEnabled)
        <a href="{{ \App\Services\WhatsappService::buildUrl() }}"
           target="_blank"
           rel="noopener"
           class="whatsapp-float"
           data-wa-track
           data-wa-type="floating"
           data-wa-label="Floating WhatsApp Button"
           aria-label="Chat with us on WhatsApp"
           title="Chat on WhatsApp">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
    @endif

    <x-public.promo-modal />
    @stack('scripts')
</body>
</html>
