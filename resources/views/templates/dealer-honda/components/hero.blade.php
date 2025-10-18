@php
    $heroTitle = $content['about_title'] ?? 'Dealer Resmi Honda Terpercaya di Indonesia';
    $WebsiteName = $website->website_name ?? 'Nama Website';
    $heroBackground = $content['about_image'] ?? null;
    $companyTagline = $content['company_tagline'] ?? 'Melayani dengan sepenuh hati untuk kepuasan Anda';
@endphp

<section id="home" class="relative bg-gray-900 text-white min-h-[700px] flex items-center">
    <!-- Background Image with Overlay -->
    @if($heroBackground)
        <div class="absolute inset-0">
            <img src="{{ $heroBackground }}" alt="Hero Background" class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-r from-honda-red-dark/80 to-black/70"></div>
        </div>
    @else
        <div class="absolute inset-0 bg-gradient-to-r from-honda-red to-honda-red-dark"></div>
    @endif

    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="max-w-3xl">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
               Selamat Datang Di<br>{{ $WebsiteName }}
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-gray-100">
                {{ $companyTagline}}
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="#contact" class="inline-flex items-center justify-center bg-white hover:bg-gray-100 text-honda-red font-bold px-8 py-4 rounded-lg transition duration-300 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    Hubungi Sales
                </a>
                <a href="#products" class="inline-flex items-center justify-center bg-transparent border-2 border-white hover:bg-white hover:text-honda-red text-white font-bold px-8 py-4 rounded-lg transition duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Lihat Unit
                </a>
            </div>
        </div>
    </div>
</section>
