@php
    $heroTitle = $content['about_title'] ?? 'Jasa Cuci Sofa Profesional';
    $WebsiteName = $website->website_name ?? 'Nama Website';
    $heroBackground = $content['about_image'] ?? null;
@endphp

<section id="home" class="relative bg-gradient-to-br from-clean-blue to-clean-blue-dark text-white min-h-[700px] flex items-center">
    <!-- Background Image with Overlay -->
    @if($heroBackground)
        <div class="absolute inset-0">
            <img src="{{ $heroBackground }}" alt="Hero Background" class="w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-gradient-to-br from-clean-blue/90 to-clean-blue-dark/90"></div>
        </div>
    @endif
    
    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="max-w-3xl">
            <div class="inline-block bg-clean-yellow text-gray-900 font-bold px-4 py-2 rounded-full mb-6">
                ⭐ Garansi 100% Puas!
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                Selamat Datang Di {{ $WebsiteName }}
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-gray-100">
                {{ $heroTitle }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="#contact" class="inline-flex items-center justify-center bg-clean-yellow hover:bg-clean-yellow-light text-gray-900 font-bold px-8 py-4 rounded-lg transition duration-300 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    Booking Sekarang
                </a>
                <a href="#services" class="inline-flex items-center justify-center bg-transparent border-2 border-white hover:bg-white hover:text-clean-blue text-white font-bold px-8 py-4 rounded-lg transition duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Lihat Layanan
                </a>
            </div>
        </div>
    </div>
    
    <!-- Floating Icons Decoration -->
    <div class="absolute bottom-10 right-10 opacity-10 hidden lg:block">
        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 20 20">
            <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
            <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
        </svg>
    </div>
</section>
