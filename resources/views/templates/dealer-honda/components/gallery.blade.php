@php
    $galleryPhotos = $content['gallery_photos'] ?? [];
@endphp

@if(!empty($galleryPhotos))
<section id="gallery" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">
                <span class="text-honda-red">Galeri</span> Showroom
            </h2>
            <p class="text-gray-600 text-lg">
                Lihat suasana showroom dan unit-unit Honda kami
            </p>
        </div>
        
        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($galleryPhotos as $photo)
                <div class="relative overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition duration-300 group">
                    <img src="{{ asset('storage/' . $photo) }}" alt="Gallery Photo" class="w-full h-64 object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-honda-red/70 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
