@php
    function resolveAssetImage($path) {
        if (empty($path)) return null;
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) return $path;
        if (str_starts_with($path, 'website-assets/')) return url('storage/' . $path);
        if (str_starts_with($path, 'storage/')) return url($path);
        return url('storage/' . $path);
    }

    $aboutTitle = $content['about_title'] ?? 'Tentang Kami';
    $aboutContent = $content['about_content'] ?? '';
    $aboutImage = resolveAssetImage($content['about_image'] ?? null);
    $companyTagline = $content['company_tagline'] ?? '';
@endphp

<section id="about" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Image -->
            <div class="order-2 lg:order-1">
                @if($aboutImage)
                    <img src="{{ $aboutImage }}" alt="{{ $aboutTitle }}" class="w-full h-auto rounded-lg shadow-2xl object-cover">
                @else
                    <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                        <span class="text-gray-500 text-lg">About Image</span>
                    </div>
                @endif
            </div>
 
            <!-- Content -->
            <div class="order-1 lg:order-2">
                @if($companyTagline)
                    <p class="text-honda-red font-bold text-lg mb-2">{{ $companyTagline }}</p>
                @endif
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-6">
                    {{ $aboutTitle }}
                </h2>
                <div class="text-gray-700 text-lg leading-relaxed space-y-4">
                    {!! nl2br(e($aboutContent)) !!}
                </div>
                <div class="mt-8">
                    <a href="#contact" class="inline-block bg-honda-red hover:bg-honda-red-dark text-white font-bold px-8 py-3 rounded-lg transition duration-300 shadow-lg">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
