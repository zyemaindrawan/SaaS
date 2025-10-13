@php
    $websiteName = $content['website_name'] ?? 'Honda Dealer';
    $companyName = $content['company_name'] ?? 'Honda Dealer';
    $title = $websiteName . ' - ' . $companyName;
    $description = $content['hero_subtitle'] ?? 'Dealer resmi Honda dengan promo terbaik';
    $canonicalUrl = request()->url();
    $ogImage = !empty($content['hero_background']) ? asset('storage/' . $content['hero_background']) : asset('images/default-og.jpg');
    $favicon = !empty($content['favicon']) ? asset('storage/' . $content['favicon']) : asset('favicon.ico');
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        /* Honda Red Theme Colors */
        :root {
            --honda-red: #cc0000;
            --honda-red-dark: #a30000;
            --honda-red-light: #ff0000;
        }
        
        .bg-honda-red {
            background-color: var(--honda-red);
        }
        
        .bg-honda-red-dark {
            background-color: var(--honda-red-dark);
        }
        
        .text-honda-red {
            color: var(--honda-red);
        }
        
        .border-honda-red {
            border-color: var(--honda-red);
        }
        
        .hover\:bg-honda-red-dark:hover {
            background-color: var(--honda-red-dark);
        }
        
        .hover\:text-honda-red:hover {
            color: var(--honda-red);
        }
    </style>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'honda-red': '#cc0000',
                        'honda-red-dark': '#a30000',
                        'honda-red-light': '#ff0000',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
