@php
$servicesTitle = $content['services_title'] ?? 'Layanan yang Saya Tawarkan';
$servicesSubtitle = $content['services_subtitle'] ?? 'Dari konsep hingga deployment, saya bantu wujudkan visi digital Anda.';
$services = $content['services'] ?? [];
$primaryColor = $content['primary_color'] ?? '#6366f1';
@endphp

<section id="services" class="py-20 bg-gray-50">
  <div class="container mx-auto px-6">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">{{ $servicesTitle }}</h2>
      <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ $servicesSubtitle }}</p>
    </div>
    <div class="grid md:grid-cols-3 gap-8">
      @foreach($services as $service)
        <div class="bg-white rounded-custom shadow p-6 hover:shadow-lg transition">
          <div class="text-primary text-3xl mb-4">
            <i class="{{ $service['icon'] ?? 'fas fa-cog' }}"></i>
          </div>
          <h3 class="text-xl font-semibold mb-2">{{ $service['title'] }}</h3>
          <p class="text-gray-600 text-sm">{{ $service['description'] }}</p>
          @if(!empty($service['link']))
            <a href="{{ $service['link'] }}" class="inline-flex items-center gap-2 text-primary mt-4 font-medium hover:underline">
              Lihat Detail <i class="fas fa-arrow-right text-xs"></i>
            </a>
          @endif
        </div>
      @endforeach
    </div>
  </div>
</section>