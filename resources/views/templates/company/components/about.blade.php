@php
    $aboutTitle = $content['about_title'] ?? 'About Us';
    $aboutContent = $content['about_content'] ?? '';
    $aboutImage = $content['about_image'] ?? null;
    $companyName = $content['company_name'] ?? 'Company';
@endphp

<section id="about" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Image -->
            <div class="order-2 lg:order-1">
                @if($aboutImage)
                    <img src="{{ resolveAssetPath($aboutImage) }}" alt="{{ $aboutTitle }}" class="w-full h-auto rounded-lg shadow-lg object-cover">
                @else
                    <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                        <span class="text-gray-500 text-lg">About Image</span>
                    </div>
                @endif
            </div>
            
            <!-- Content -->
            <div class="order-1 lg:order-2">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                    {{ $aboutTitle }}
                </h2>
                <div class="text-gray-700 text-lg leading-relaxed space-y-4">
                    {!! nl2br(e($aboutContent)) !!}
                </div>
                <div class="mt-8">
                    <a href="#contact" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition duration-300">
                        Get in Touch
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
