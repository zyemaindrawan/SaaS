@php
    $galleryPhotos = $content['gallery_photos'] ?? [];
@endphp

@if(!empty($galleryPhotos))
<section id="gallery" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Gallery
            </h2>
            <p class="text-gray-600 text-lg">
                Take a look at our work and facilities
            </p>
        </div>
        
        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($galleryPhotos as $photo)
                <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition duration-300 group">
                    <img src="{{ resolveAssetPath($photo) }}" alt="Gallery" class="w-full h-64 object-cover group-hover:scale-110 transition duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition duration-300"></div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
