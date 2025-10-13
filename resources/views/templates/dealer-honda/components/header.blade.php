@php
    $companyName = $content['company_name'] ?? 'Honda Dealer';
    $companyLogo = resolveAssetPath($content['company_logo'] ?? null);
@endphp

<header class="bg-white shadow-md sticky top-0 z-50">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo & Company Name -->
            <div class="flex items-center space-x-3">
                @if($companyLogo)
                    <img src="{{ $companyLogo }}" alt="{{ $companyName }}" class="h-12 w-auto">
                @else
                    <span class="text-2xl font-bold text-honda-red">{{ $companyName }}</span>
                @endif
            </div>

            <!-- Navigation Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#home" class="text-gray-700 hover:text-honda-red font-semibold transition">Home</a>
                <a href="#about" class="text-gray-700 hover:text-honda-red font-semibold transition">About</a>
                <a href="#products" class="text-gray-700 hover:text-honda-red font-semibold transition">Units</a>
                <a href="#gallery" class="text-gray-700 hover:text-honda-red font-semibold transition">Gallery</a>
                <a href="#testimonials" class="text-gray-700 hover:text-honda-red font-semibold transition">Testimonials</a>
                <a href="#contact" class="bg-honda-red hover:bg-honda-red-dark text-white font-semibold px-6 py-2 rounded-lg transition">Contact</a>
            </div>
            
            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-btn" class="text-gray-700 hover:text-honda-red focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4">
            <a href="#home" class="block py-2 text-gray-700 hover:text-honda-red font-medium">Home</a>
            <a href="#about" class="block py-2 text-gray-700 hover:text-honda-red font-medium">About</a>
            <a href="#products" class="block py-2 text-gray-700 hover:text-honda-red font-medium">Units</a>
            <a href="#gallery" class="block py-2 text-gray-700 hover:text-honda-red font-medium">Gallery</a>
            <a href="#testimonials" class="block py-2 text-gray-700 hover:text-honda-red font-medium">Testimonials</a>
            <a href="#contact" class="block py-2 text-honda-red font-semibold">Contact</a>
        </div>
    </nav>
</header>

<script>
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>
