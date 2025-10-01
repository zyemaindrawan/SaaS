@php
$heroTitle = $content['hero_title'] ?? 'Promo Toyota Terbaru di Bandung – Oktober 2025';
$heroSubtitle = $content['hero_subtitle'] ?? 'Dapatkan diskon s/d Rp 50 juta, bonus aksesoris, dan cicilan 0% untuk semua tipe Toyota.';
$heroCtaText = $content['hero_cta_text'] ?? 'Ajukan Test Drive';
$heroCtaLink = $content['hero_cta_link'] ?? '#contact';
$heroSecondaryCtaText = $content['hero_secondary_cta_text'] ?? 'Lihat Daftar Mobil';
$heroSecondaryCtaLink = $content['hero_secondary_cta_link'] ?? '#cars';
$primaryColor = $content['primary_color'] ?? '#c30010';
@endphp

<section id="home" class="min-h-screen flex items-center justify-center bg-gradient-to-br from-red-600 to-red-800 text-white">
  <div class="container mx-auto px-6 text-center">
    <h1 class="text-4xl md:text-6xl font-extrabold mb-6">{{ $heroTitle }}</h1>
    <p class="text-lg md:text-xl max-w-3xl mx-auto mb-8 opacity-90">{{ $heroSubtitle }}</p>
    <div class="flex flex-col md:flex-row justify-center gap-4">
      <a href="{{ $heroCtaLink }}" class="inline-flex items-center gap-2 bg-white text-red-600 px-6 py-3 rounded-custom font-semibold shadow hover:scale-105 transition">
        <i class="fas fa-car"></i> {{ $heroCtaText }}
      </a>
    </div>
  </div>
</section>