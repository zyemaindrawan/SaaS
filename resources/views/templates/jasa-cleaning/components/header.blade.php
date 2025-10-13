@php
    function resolveAssetPath0($path) {
        if (empty($path)) return null;
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) return $path;
        if (str_starts_with($path, 'website-assets/') || str_starts_with($path, 'branding/')) {
            return url('storage/' . $path);
        }
        if (str_starts_with($path, 'storage/')) return url($path);
        return url('storage/' . $path);
    }

    $companyName = $content['company_name'] ?? 'Bersih Maksimal';
    $companyLogo = resolveAssetPath0($content['company_logo'] ?? null);
@endphp

<header class="bg-white shadow-md sticky top-0 z-50">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo & Company Name -->
            <div class="flex items-center space-x-3">
                @if($companyLogo)
                    <img src="{{ $companyLogo }}" alt="{{ $companyName }}" class="h-12 w-auto">
                @else
                    <div class="flex items-center">
                        <svg class="w-10 h-10 text-clean-blue mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-2xl font-bold text-clean-blue">{{ $companyName }}</span>
                    </div>
                @endif
            </div>
            
            <!-- Navigation Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#home" class="text-gray-700 hover:text-clean-blue font-semibold transition">Home</a>
                <a href="#about" class="text-gray-700 hover:text-clean-blue font-semibold transition">Tentang</a>
                <a href="#services" class="text-gray-700 hover:text-clean-blue font-semibold transition">Layanan</a>
                <a href="#gallery" class="text-gray-700 hover:text-clean-blue font-semibold transition">Galeri</a>
                <a href="#testimonials" class="text-gray-700 hover:text-clean-blue font-semibold transition">Testimoni</a>
                <a href="#contact" class="bg-clean-yellow hover:bg-clean-yellow-light text-gray-900 font-bold px-6 py-2 rounded-lg transition shadow-md">Hubungi Kami</a>
            </div>
            
            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-btn" class="text-gray-700 hover:text-clean-blue focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4">
            <a href="#home" class="block py-2 text-gray-700 hover:text-clean-blue font-medium">Home</a>
            <a href="#about" class="block py-2 text-gray-700 hover:text-clean-blue font-medium">Tentang</a>
            <a href="#services" class="block py-2 text-gray-700 hover:text-clean-blue font-medium">Layanan</a>
            <a href="#gallery" class="block py-2 text-gray-700 hover:text-clean-blue font-medium">Galeri</a>
            <a href="#testimonials" class="block py-2 text-gray-700 hover:text-clean-blue font-medium">Testimoni</a>
            <a href="#contact" class="block py-2 text-clean-blue font-bold">Hubungi Kami</a>
        </div>
    </nav>
</header>

<script>
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>
