@php
    $websiteName = $content['website_name'] ?? 'Jasa Cleaning';
    $companyName = $content['company_name'] ?? 'Bersih Maksimal';
    $title = $websiteName . ' - ' . $companyName;
    $description = $content['hero_subtitle'] ?? 'Jasa cuci sofa, springbed, dan karpet profesional';
    $canonicalUrl = request()->url();
    $ogImage = resolveAssetPath($content['hero_background'] ?? null) ?? asset('images/default-og.jpg');
    $favicon = resolveAssetPath($content['favicon'] ?? null) ?? asset('favicon.ico');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $favicon }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:image" content="{{ $ogImage }}">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ $canonicalUrl }}">
    <meta property="twitter:title" content="{{ $title }}">
    <meta property="twitter:description" content="{{ $description }}">
    <meta property="twitter:image" content="{{ $ogImage }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        /* Cleaning Blue & Yellow Theme */
        :root {
            --clean-blue: #1e40af;
            --clean-blue-dark: #1e3a8a;
            --clean-blue-light: #3b82f6;
            --clean-yellow: #fbbf24;
            --clean-yellow-light: #fcd34d;
        }
        
        .bg-clean-blue {
            background-color: var(--clean-blue);
        }
        
        .bg-clean-blue-dark {
            background-color: var(--clean-blue-dark);
        }
        
        .bg-clean-yellow {
            background-color: var(--clean-yellow);
        }
        
        .text-clean-blue {
            color: var(--clean-blue);
        }
        
        .text-clean-yellow {
            color: var(--clean-yellow);
        }
        
        .hover\:bg-clean-blue-dark:hover {
            background-color: var(--clean-blue-dark);
        }
        
        .hover\:text-clean-blue:hover {
            color: var(--clean-blue);
        }
        
        .border-clean-blue {
            border-color: var(--clean-blue);
        }
        
        .border-clean-yellow {
            border-color: var(--clean-yellow);
        }
    </style>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'clean-blue': '#1e40af',
                        'clean-blue-dark': '#1e3a8a',
                        'clean-blue-light': '#3b82f6',
                        'clean-yellow': '#fbbf24',
                        'clean-yellow-light': '#fcd34d',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
