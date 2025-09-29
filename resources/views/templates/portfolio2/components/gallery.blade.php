@php
$galleryTitle = $content['gallery_title'] ?? 'Galeri Karya';
$galleryPhotos = $content['gallery_photos'] ?? [];
@endphp

<section id="gallery" class="py-20 bg-gray-50">
  <div class="container mx-auto px-6">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">{{ $galleryTitle }}</h2>
    </div>
    <div class="grid md:grid-cols-3 gap-6">
      @foreach($galleryPhotos as $photo)
        <div class="overflow-hidden rounded-custom shadow hover:shadow-xl transition">
          <img src="{{ $photo }}" alt="Karya" class="w-full h-64 object-cover">
        </div>
      @endforeach
    </div>
  </div>
</section>