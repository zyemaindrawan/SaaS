@php
    $heroTitle = $content['about_title'] ?? 'Company Name';
    $WebsiteName = $website->website_name ?? 'Nama Website';
    $heroBackground = resolveAssetPath($content['about_image']) ?? null;
@endphp

<section id="home" class="relative bg-gray-900 text-white min-h-[600px] flex items-center">
    <!-- Background Image -->
    @if($heroBackground)
        <div class="absolute inset-0">
            <img src="{{ $heroBackground }}" class="w-full h-full object-cover opacity-40">
        </div>
    @endif
    
    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
            Welcome to {{ $WebsiteName }}
        </h1>
        <p class="text-xl md:text-2xl mb-8 text-gray-200 max-w-3xl mx-auto">
            Leading Global Energy Innovation. Reliable Exploration, Production, and Distribution for the World.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#contact" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition duration-300">
                Contact Us
            </a>
            <a href="#about" class="inline-block bg-transparent border-2 border-white hover:bg-white hover:text-gray-900 text-white font-semibold px-8 py-3 rounded-lg transition duration-300">
                Learn More
            </a>
        </div>
    </div>
</section>
