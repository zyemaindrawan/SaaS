@php
$contactTitle = $content['contact_title'] ?? 'Hubungi Sales Kami – Siap Bantu 24 Jam';
$contactAddress = $content['contact_address'] ?? '';
$contactPhone = $content['contact_phone'] ?? '';
$contactEmail = $content['contact_email'] ?? '';
$primaryColor = $content['primary_color'] ?? '#c30010';
@endphp

<section id="contact" class="py-20 bg-white">
  <div class="container mx-auto px-6">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">{{ $contactTitle }}</h2>
    </div>
    <div class="grid md:grid-cols-2 gap-12">
      <div>
        <h3 class="text-xl font-semibold mb-4">Informasi Kontak</h3>
        <ul class="space-y-3 text-gray-700">
          @if($contactAddress)
            <li class="flex items-start gap-3"><i class="fas fa-map-marker-alt mt-1 text-primary"></i>{{ $contactAddress }}</li>
          @endif
          @if($contactPhone)
            <li class="flex items-center gap-3"><i class="fas fa-phone text-primary"></i><a href="tel:{{ $contactPhone }}" class="hover:underline">{{ $contactPhone }}</a></li>
          @endif
          @if($contactEmail)
            <li class="flex items-center gap-3"><i class="fas fa-envelope text-primary"></i><a href="mailto:{{ $contactEmail }}" class="hover:underline">{{ $contactEmail }}</a></li>
          @endif
        </ul>
      </div>
      <div>
        <h3 class="text-xl font-semibold mb-4">Jam Operasional</h3>
        <ul class="space-y-2 text-gray-700 text-sm">
          <li>Senin – Jumat: 08:00 – 17:00</li>
          <li>Sabtu: 08:00 – 15:00</li>
          <li>Minggu: 10:00 – 15:00</li>
        </ul>
        <a href="https://wa.me/{{ $content['whatsapp_number'] ?? '' }}?text=Halo%20{{ $content['company_name'] ?? '' }}%2C%20saya%20ingin%20info%20promo%20dan%20ketersediaan%20unit" 
           target="_blank" 
           class="inline-flex items-center gap-2 bg-green-500 text-white px-6 py-3 rounded-custom font-semibold mt-6 hover:bg-green-600 transition">
          <i class="fab fa-whatsapp"></i> Chat Sekarang
        </a>
      </div>
    </div>
  </div>
</section>