@php
$galleryTitle = $content['gallery_title'] ?? 'Galeri Showroom & Unit Honda';
$galleryPhotos = $content['gallery_photos'] ?? [];
$primaryColor = $content['primary_color'] ?? '#e4002b';
@endphp

<section id="gallery" class="py-20 bg-gray-50">
  <div class="container mx-auto px-6">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">{{ $galleryTitle }}</h2>
    </div>

    @if(!empty($galleryPhotos))
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($galleryPhotos as $index => $photo)
          <div class="overflow-hidden rounded-lg shadow hover:shadow-xl transition cursor-pointer" onclick="openLightbox({{ $index }})">
            <img src="{{ $photo }}" alt="Galeri Honda" class="w-full h-64 object-cover hover:scale-105 transition duration-300">
          </div>
        @endforeach
      </div>
    @else
      <div class="text-center text-gray-500">
        <i class="fas fa-image text-4xl mb-4 opacity-50"></i>
        <p>Belum ada foto galeri yang ditambahkan.</p>
      </div>
    @endif
  </div>
</section>

<div id="lightbox" class="fixed inset-0 bg-black bg-opacity-80 hidden flex items-center justify-center z-50" onclick="closeLightbox()">
  <div class="relative max-w-4xl max-h-full p-4">
    <img id="lightbox-img" src="" alt="Lightbox" class="max-w-full max-h-[90vh] rounded-lg">
    <button class="absolute top-4 right-4 text-white text-2xl" onclick="closeLightbox()">
      <i class="fas fa-times"></i>
    </button>
  </div>
</div>

<script>
  const galleryPhotos = @json($galleryPhotos);

  function openLightbox(index) {
    const img = galleryPhotos[index];
    document.getElementById('lightbox-img').src = img;
    document.getElementById('lightbox').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  }

  function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
  });
</script>