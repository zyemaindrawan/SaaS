@php
$title = $title ?? ($content['seo_title'] ?? 'Dealer Resmi Honda – Promo Terbaru');
$description = $description ?? ($content['seo_description'] ?? 'Penawaran terbaik mobil Honda baru dan bekas dengan promo Oktober 2025.');
$companyName = $companyName ?? ($content['company_name'] ?? 'Dealer Mobil');
$canonicalUrl = $canonicalUrl ?? request()->url();
$ogImage = $ogImage ?? ($content['promo_banner'] ?? asset('images/default-og.jpg'));
$favicon = $favicon ?? ($content['favicon'] ?? asset('favicon.ico'));
$primaryColor = $primaryColor ?? ($content['primary_color'] ?? '#c30010');
$secondaryColor = $secondaryColor ?? ($content['secondary_color'] ?? '#1f2937');
$accentColor = $accentColor ?? ($content['accent_color'] ?? '#f59e0b');
$fontFamily = $fontFamily ?? ($content['font_family'] ?? 'inter');
$borderRadius = $borderRadius ?? ($content['border_radius'] ?? 'medium');
@endphp

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="robots" content="index,follow">
<link rel="canonical" href="{{ $canonicalUrl }}">
<link rel="icon" href="{{ $favicon }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.tailwindcss.com"></script>

<style>
:root {
  --primary-color: {{ $primaryColor }};
  --secondary-color: {{ $secondaryColor }};
  --accent-color: {{ $accentColor }};
  --border-radius: {{ $borderRadius === 'none' ? '0px' : ($borderRadius === 'small' ? '4px' : ($borderRadius === 'large' ? '12px' : ($borderRadius === 'rounded' ? '20px' : '8px'))) }};
}
* { font-family: 'Inter', sans-serif; }
.bg-primary { background-color: var(--primary-color) !important; }
.text-primary { color: var(--primary-color) !important; }
.rounded-custom { border-radius: var(--border-radius) !important; }
</style>