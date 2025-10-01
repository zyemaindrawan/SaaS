@php
$promoTitle = $content['promo_title'] ?? 'Promo Oktober 2025 – Diskon Sampai Rp 50 Juta!';
$promoBanner = $content['promo_banner'] ?? null;
$promoDescription = $content['promo_description'] ?? '';
$primaryColor = $content['primary_color'] ?? '#c30010';
@endphp

<section id="promo" class="py-16 bg-gray-50">
  <div class="container mx-auto px-6">
    <div class="text-center mb-8">
      <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">{{ $promoTitle }}</h2>
    </div>
    <div class="grid md:grid-cols-2 gap-8 items-center">
      <div>
        @if($promoBanner)
          <img src="{{ $promoBanner }}" alt="Promo" class="rounded-custom shadow">
        @else
          <div class="bg-gray-200 rounded-custom h-64 flex items-center justify-center text-gray-500">Banner Promo</div>
        @endif
      </div>
      <div class="text-gray-700">
        {!! $promoDescription !!}
        <a href="#contact" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-custom font-semibold shadow hover:opacity-90 transition mt-4">
          Klaim Promo Sekarang
        </a>
      </div>
    </div>
  </div>
</section>