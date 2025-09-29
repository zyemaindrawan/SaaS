@php
$contactTitle = $content['contact_title'] ?? 'Mari Berkolaborasi';
$contactEmail = $content['contact_email'] ?? '';
$contactPhone = $content['contact_phone'] ?? '';
$contactAddress = $content['contact_address'] ?? '';
$primaryColor = $content['primary_color'] ?? '#6366f1';
@endphp

<section id="contact" class="py-20 bg-gray-50">
  <div class="container mx-auto px-6 text-center">
    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">{{ $contactTitle }}</h2>
    <p class="text-gray-600 mb-8 max-w-2xl mx-auto">Saya siap membantu wujudkan ide Anda. Mari diskusi lebih lanjut.</p>
    <div class="flex flex-col md:flex-row justify-center items-center gap-6">
      @if($contactEmail)
        <a href="mailto:{{ $contactEmail }}" class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-800 px-6 py-3 rounded-custom shadow hover:shadow-md transition">
          <i class="fas fa-envelope"></i> {{ $contactEmail }}
        </a>
      @endif
      @if($contactPhone)
        <a href="tel:{{ $contactPhone }}" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-custom shadow hover:opacity-90 transition">
          <i class="fas fa-phone"></i> {{ $contactPhone }}
        </a>
      @endif
    </div>
    @if($contactAddress)
      <p class="text-sm text-gray-600 mt-6">{{ $contactAddress }}</p>
    @endif
  </div>
</section>