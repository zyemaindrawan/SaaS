<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#5145CD",
                        "background-light": "#FFFFFF",
                        "background-dark": "#111827",
                        "text-light": "#1F2937",
                        "text-dark": "#F9FAFB",
                        "subtext-light": "#6B7280",
                        "subtext-dark": "#9CA3AF",
                        "border-light": "#E5E7EB",
                        "border-dark": "#374151"
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                    },
                },
            },
        };
    </script>
    @stack('styles')
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
    <div class="container mx-auto px-4">
        @include('components.header')
        
        <main>
            @yield('content')
        </main>
    </div>
    
    @include('components.footer')
    
    @stack('scripts')
</body>
</html>