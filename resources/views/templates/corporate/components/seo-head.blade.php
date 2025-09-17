@php
    use App\Services\SEOService;
    
    // Initialize SEO service if websiteContent is available
    $seoService = app(SEOService::class);
    $seo = $websiteContent ? $seoService->generateSEOData($websiteContent) : [];
    
    // Fallback values from content data
    $companyName = $content['company_name'] ?? 'Your Company';
    $heroSubtitle = $content['hero_subtitle'] ?? 'Professional business solutions';
    $currentUrl = url()->current();
    
    // SEO Data with fallbacks
    $title = $seo['title'] ?? ($content['seo_title'] ?? $companyName);
    $description = $seo['description'] ?? ($content['seo_description'] ?? $heroSubtitle);
    $keywords = $seo['keywords'] ?? ($content['seo_keywords'] ?? '');
    $canonicalUrl = $seo['canonical_url'] ?? ($content['canonical_url'] ?? $currentUrl);
    $ogImage = $seo['og_image'] ?? ($content['og_image'] ? asset('storage/' . $content['og_image']) : asset('images/default-og-image.jpg'));
    $favicon = $seo['favicon'] ?? ($content['favicon'] ? asset('storage/' . $content['favicon']) : asset('favicon.ico'));
    $robots = $seo['robots'] ?? ($content['robots_meta'] ?? 'index,follow');
    $schemaData = $seo['schema_data'] ?? [];
    $analytics = $seo['analytics'] ?? [];
    
    // Color theme
    $primaryColor = $content['primary_color'] ?? '#2563eb';
    $secondaryColor = $content['secondary_color'] ?? '#64748b';
    $accentColor = $content['accent_color'] ?? '#f59e0b';
    
    // Font settings
    $fontFamily = $content['font_family'] ?? 'inter';
    $borderRadius = $content['border_radius'] ?? 'medium';
    
    // Language and locale
    $locale = app()->getLocale();
    $language = $locale === 'id' ? 'id_ID' : 'en_US';
@endphp

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="format-detection" content="telephone=no">
<meta name="mobile-web-app-capable" content="yes">

<title>{{ $title }}</title>
<meta name="title" content="{{ $title }}">
<meta name="description" content="{{ $description }}">
@if($keywords)
    <meta name="keywords" content="{{ $keywords }}">
@endif
<meta name="robots" content="{{ $robots }}">
<meta name="author" content="{{ $companyName }}">
<meta name="publisher" content="{{ $companyName }}">
<meta name="language" content="{{ $locale }}">
<meta name="revisit-after" content="7 days">
<meta name="distribution" content="web">
<meta name="rating" content="general">

<link rel="canonical" href="{{ $canonicalUrl }}">
@if($locale === 'id')
    <link rel="alternate" hreflang="en" href="{{ str_replace('/id/', '/en/', $canonicalUrl) }}">
@endif
<link rel="alternate" hreflang="{{ $locale }}" href="{{ $canonicalUrl }}">

<link rel="icon" type="image/x-icon" href="{{ $favicon }}">
<link rel="shortcut icon" type="image/x-icon" href="{{ $favicon }}">
<link rel="apple-touch-icon" sizes="57x57" href="{{ $favicon }}">
<link rel="apple-touch-icon" sizes="60x60" href="{{ $favicon }}">
<link rel="apple-touch-icon" sizes="72x72" href="{{ $favicon }}">
<link rel="apple-touch-icon" sizes="76x76" href="{{ $favicon }}">
<link rel="apple-touch-icon" sizes="114x114" href="{{ $favicon }}">
<link rel="apple-touch-icon" sizes="120x120" href="{{ $favicon }}">
<link rel="apple-touch-icon" sizes="144x144" href="{{ $favicon }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ $favicon }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ $favicon }}">
<link rel="icon" type="image/png" sizes="192x192" href="{{ $favicon }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ $favicon }}">
<link rel="icon" type="image/png" sizes="96x96" href="{{ $favicon }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ $favicon }}">
<link rel="manifest" href="{{ asset('manifest.json') }}">

<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $companyName }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:secure_url" content="{{ $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ $title }}">
<meta property="og:locale" content="{{ $language }}">
<meta property="og:updated_time" content="{{ $websiteContent->updated_at ?? now() }}">

@if($websiteContent ?? false)
    <meta property="article:published_time" content="{{ $websiteContent->created_at->toW3cString() }}">
    <meta property="article:modified_time" content="{{ $websiteContent->updated_at->toW3cString() }}">
    <meta property="article:author" content="{{ $websiteContent->user->name ?? $companyName }}">
    <meta property="article:section" content="{{ $websiteContent->template_slug }}">
    @if(!empty($content['seo_keywords']))
        @foreach(explode(',', $content['seo_keywords']) as $tag)
            <meta property="article:tag" content="{{ trim($tag) }}">
        @endforeach
    @endif
@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@{{ str_replace(' ', '', strtolower($companyName)) }}">
<meta name="twitter:creator" content="@{{ str_replace(' ', '', strtolower($companyName)) }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">
<meta name="twitter:image:alt" content="{{ $title }}">
<meta name="twitter:domain" content="{{ request()->getHost() }}">
<meta name="twitter:url" content="{{ $canonicalUrl }}">

@if(!empty($content['social_links']))
    @foreach($content['social_links'] as $social)
        @if(($social['platform'] ?? '') === 'twitter' && !empty($social['url']))
            <meta name="twitter:site" content="{{ '@' . basename(parse_url($social['url'], PHP_URL_PATH)) }}">
            @break
        @endif
    @endforeach
@endif

<meta property="og:see_also" content="{{ $canonicalUrl }}">

<meta name="p:domain_verify" content="{{ config('seo.pinterest_verify', '') }}">

<meta name="theme-color" content="{{ $primaryColor }}">
<meta name="msapplication-TileColor" content="{{ $primaryColor }}">
<meta name="msapplication-TileImage" content="{{ $favicon }}">
<meta name="msapplication-config" content="{{ asset('browserconfig.xml') }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ $companyName }}">

<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<meta name="referrer" content="origin-when-cross-origin">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://cdn.tailwindcss.com">
<link rel="preconnect" href="https://cdnjs.cloudflare.com">

<link rel="dns-prefetch" href="//www.google-analytics.com">
<link rel="dns-prefetch" href="//www.googletagmanager.com">
<link rel="dns-prefetch" href="//connect.facebook.net">
<link rel="dns-prefetch" href="//platform.twitter.com">
<link rel="dns-prefetch" href="//www.linkedin.com">

<link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">

@switch($fontFamily)
    @case('roboto')
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
        @break
    @case('open-sans')
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
        @break
    @case('montserrat')
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
        @break
    @case('poppins')
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        @break
    @case('playfair')
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
        @break
    @default
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
@endswitch

@if(!empty($analytics['google_tag_manager']))
    <!-- Google Tag Manager -->
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{ $analytics['google_tag_manager'] }}');
    </script>
    <!-- End Google Tag Manager -->
@endif

@if(!empty($analytics['google_analytics']) && empty($analytics['google_tag_manager']))
    <!-- Google Analytics 4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $analytics['google_analytics'] }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $analytics['google_analytics'] }}', {
            page_title: '{{ $title }}',
            page_location: '{{ $canonicalUrl }}',
            custom_map: {
                'custom_parameter_1': 'company_name',
                'custom_parameter_2': 'template_name'
            }
        });
        
        // Enhanced tracking
        gtag('event', 'page_view', {
            company_name: '{{ $companyName }}',
            template_name: '{{ $websiteContent->template_slug ?? "unknown" }}',
            page_title: '{{ $title }}'
        });
    </script>
    <!-- End Google Analytics 4 -->
@endif

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '{{ $primaryColor }}',
                    secondary: '{{ $secondaryColor }}',
                    accent: '{{ $accentColor }}'
                },
                fontFamily: {
                    body: ['{{ ucfirst($fontFamily) }}', 'system-ui', 'sans-serif']
                }
            }
        }
    }
</script>

<style>
    :root {
        --primary-color: {{ $primaryColor }};
        --secondary-color: {{ $secondaryColor }};
        --accent-color: {{ $accentColor }};
        
        @switch($borderRadius)
            @case('none')
                --border-radius: 0px;
                @break
            @case('small')
                --border-radius: 4px;
                @break
            @case('large')
                --border-radius: 12px;
                @break
            @case('rounded')
                --border-radius: 20px;
                @break
            @default
                --border-radius: 8px;
        @endswitch
    }
    
    * {
        font-family: '{{ ucfirst($fontFamily) }}', system-ui, -apple-system, sans-serif;
    }
    
    .bg-primary { background-color: var(--primary-color) !important; }
    .text-primary { color: var(--primary-color) !important; }
    .border-primary { border-color: var(--primary-color) !important; }
    .bg-secondary { background-color: var(--secondary-color) !important; }
    .text-secondary { color: var(--secondary-color) !important; }
    .bg-accent { background-color: var(--accent-color) !important; }
    .text-accent { color: var(--accent-color) !important; }
    
    /* Custom border radius */
    .rounded-custom { border-radius: var(--border-radius) !important; }
    
    /* Performance optimizations */
    img {
        loading: lazy;
        decoding: async;
    }
    
    /* Animations */
    .fade-in { 
        opacity: 0; 
        transform: translateY(20px); 
        animation: fadeInUp 0.8s ease forwards; 
    }
    
    @keyframes fadeInUp {
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }
    
    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }
    
    /* Focus styles for accessibility */
    *:focus {
        outline: 2px solid var(--primary-color);
        outline-offset: 2px;
    }
    
    /* Print styles */
    @media print {
        .no-print { display: none !important; }
        a { text-decoration: underline; }
        .bg-primary, .bg-secondary, .bg-accent { 
            background: transparent !important; 
            color: #000 !important; 
        }
    }
    
    /* High contrast mode support */
    @media (prefers-contrast: high) {
        .bg-primary { background-color: #000 !important; }
        .text-primary { color: #000 !important; }
        .border-primary { border-color: #000 !important; }
    }
    
    /* Reduced motion support */
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
        html {
            scroll-behavior: auto;
        }
    }
</style>

<noscript>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</noscript>

@if(!empty($schemaData))
    <script type="application/ld+json">
        {!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endif

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "{{ $companyName }}",
    "url": "{{ $canonicalUrl }}",
    "description": "{{ $description }}",
    @if(!empty($content['contact_email']))
    "email": "{{ $content['contact_email'] }}",
    @endif
    @if(!empty($content['contact_phone']))
    "telephone": "{{ $content['contact_phone'] }}",
    @endif
    "inLanguage": "{{ $locale }}",
    "copyrightYear": "{{ date('Y') }}",
    "dateModified": "{{ $websiteContent->updated_at ?? now() }}",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ $canonicalUrl }}?search={search_term_string}",
        "query-input": "required name=search_term_string"
    }
    @if(!empty($content['social_links']))
    ,"sameAs": [
        @foreach($content['social_links'] as $index => $social)
            @if(!empty($social['url']))
                "{{ $social['url'] }}"{{ $index < count($content['social_links']) - 1 ? ',' : '' }}
            @endif
        @endforeach
    ]
    @endif
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": "{{ url('/') }}"
        }
        @if($websiteContent ?? false)
        ,{
            "@type": "ListItem", 
            "position": 2,
            "name": "{{ $title }}",
            "item": "{{ $canonicalUrl }}"
        }
        @endif
    ]
}
</script>

<style>
    /* Critical styles for immediate render */
    body {
        margin: 0;
        font-family: '{{ ucfirst($fontFamily) }}', system-ui, sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #fff;
    }
    
    header {
        background: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .hero-section {
        min-height: 60vh;
        background: linear-gradient(135deg, {{ $primaryColor }}, {{ $accentColor }});
    }
</style>

@if($preview_mode ?? false)
    <style>
        body::before {
            content: "🔍 PREVIEW MODE - This website is not yet published";
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(90deg, #ef4444, #dc2626);
            color: white;
            padding: 8px 16px;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            z-index: 9999;
            animation: preview-pulse 2s infinite;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }
        
        @keyframes preview-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        
        body {
            padding-top: 40px;
        }
        
        @media (max-width: 768px) {
            body::before {
                font-size: 10px;
                padding: 6px 12px;
            }
            body {
                padding-top: 32px;
            }
        }
    </style>
@endif

<script>
    // Core Web Vitals and performance monitoring
    if ('PerformanceObserver' in window) {
        try {
            // Largest Contentful Paint (LCP)
            new PerformanceObserver((entryList) => {
                for (const entry of entryList.getEntries()) {
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'LCP', {
                            event_category: 'Web Vitals',
                            value: Math.round(entry.startTime),
                            non_interaction: true,
                        });
                    }
                }
            }).observe({entryTypes: ['largest-contentful-paint']});
            
            // First Input Delay (FID)
            new PerformanceObserver((entryList) => {
                for (const entry of entryList.getEntries()) {
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'FID', {
                            event_category: 'Web Vitals',
                            value: Math.round(entry.processingStart - entry.startTime),
                            non_interaction: true,
                        });
                    }
                }
            }).observe({entryTypes: ['first-input']});
            
        } catch (e) {
            console.log('Performance monitoring not supported');
        }
    }
    
    // Page load time tracking
    window.addEventListener('load', function() {
        if (typeof gtag !== 'undefined' && performance && performance.timing) {
            const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
            gtag('event', 'page_load_time', {
                event_category: 'Performance',
                value: loadTime,
                non_interaction: true
            });
        }
    });
</script>
