@php
$aboutTitle = $content['about_title'] ?? 'Tentang Saya';
$aboutContent = $content['about_content'] ?? '<p>Saya Rizky, UI/UX designer dan frontend developer berpengalaman 5+ tahun.</p>';
$aboutImage = $content['about_image'] ?? null;
$companyStats = $content['company_stats'] ?? [];
$primaryColor = $content['primary_color'] ?? '#6366f1';
@endphp

<section id="about" class="py-20">
  <div class="container mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
    <div>
      <h2 class="text-3xl md:text-4xl font-extrabold mb-6">{{ $aboutTitle }}</h2>
      <div class="text-gray-700 mb-6">{!! $aboutContent !!}</div>
      <div class="grid grid-cols-2 gap-6">
        @foreach($companyStats as $stat)
          <div class="text-center">
            <div class="text-2xl font-bold text-primary">{{ $stat['number'] }}</div>
            <div class="text-sm text-gray-600">{{ $stat['label'] }}</div>
          </div>
        @endforeach
      </div>
    </div>
    <div>
      @if($aboutImage)
        <img src="{{ $aboutImage }}" alt="{{ $aboutTitle }}" class="rounded-custom shadow">
      @else
        <div class="bg-gray-200 rounded-custom h-80 flex items-center justify-center text-gray-500">Tidak ada gambar</div>
      @endif
    </div>
  </div>
</section>