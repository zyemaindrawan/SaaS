@php
    function resolveAssetPath4($path) {
        if (empty($path)) return null;
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) return $path;
        if (str_starts_with($path, 'website-assets/')) return url('storage/' . $path);
        if (str_starts_with($path, 'storage/')) return url($path);
        return url('storage/' . $path);
    }

    $galleryPhotos = $content['gallery_photos'] ?? [];
@endphp

@if(!empty($galleryPhotos))
<section id="gallery" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-clean-blue mb-4">
                Galeri <span class="text-clean-yellow">Before & After</span>
            </h2>
            <p class="text-gray-600 text-lg">
                Lihat hasil kerja profesional kami yang memuaskan
            </p>
        </div>
        
        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($galleryPhotos as $photo)
                @php
                    $photoUrl = resolveAssetPath4($photo);
                @endphp
                @if($photoUrl)
                    <div class="relative overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition duration-300 group">
                        <img src="{{ $photoUrl }}" alt="Gallery Photo" class="w-full h-64 object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-clean-blue/80 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end justify-center pb-4">
                            <span class="text-white font-bold text-lg">Hasil Maksimal</span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif
