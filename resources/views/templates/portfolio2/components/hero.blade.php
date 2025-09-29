@php
$heroTitle = $content['hero_title'] ?? 'Halo, Saya Rizky – Desainer Antarmuka Digital';
$heroSubtitle = $content['hero_subtitle'] ?? 'Saya membantu brand dan startup menciptakan produk digital yang indah, intuitif, dan berdampak.';
$heroCtaText = $content['hero_cta_text'] ?? 'Lihat Portofolio';
$heroCtaLink = $content['hero_cta_link'] ?? '#gallery';
$primaryColor = $content['primary_color'] ?? '#6366f1';
@endphp

<section id="home" class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
  <div class="container mx-auto px-6 text-center">
    <h1 class="text-4xl md:text-6xl font-extrabold mb-6 animate-fade-up">{{ $heroTitle }}</h1>
    <p class="text-lg md:text-xl max-w-2xl mx-auto mb-8 opacity-90">{{ $heroSubtitle }}</p>
    <a href="{{ $heroCtaLink }}" class="inline-flex items-center gap-2 bg-white text-indigo-600 px-6 py-3 rounded-custom font-semibold shadow hover:scale-105 transition">
      {{ $heroCtaText }} <i class="fas fa-arrow-right"></i>
    </a>
  </div>
</section>