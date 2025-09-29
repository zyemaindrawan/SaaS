@php
$companyName = $content['company_name'] ?? 'Portfolio';
$companyLogo = $content['company_logo'] ?? null;
$primaryColor = $content['primary_color'] ?? '#6366f1';
$navItems = [
    ['label' => 'Beranda', 'href' => '#home'],
    ['label' => 'Layanan', 'href' => '#services'],
    ['label' => 'Tentang', 'href' => '#about'],
    ['label' => 'Galeri', 'href' => '#gallery'],
    ['label' => 'Testimoni', 'href' => '#testimonials'],
    ['label' => 'Kontak', 'href' => '#contact']
];
@endphp

<header class="fixed top-0 left-0 right-0 bg-white shadow z-50">
  <div class="container mx-auto px-6 py-4 flex justify-between items-center">
    <a href="#home" class="flex items-center gap-3">
      @if($companyLogo)
        <img src="{{ $companyLogo }}" alt="{{ $companyName }}" class="h-10">
      @endif
      <span class="font-bold text-lg">{{ $companyName }}</span>
    </a>
    <nav class="hidden md:flex gap-6">
      @foreach($navItems as $item)
        <a href="{{ $item['href'] }}" class="text-gray-700 hover:text-primary transition">{{ $item['label'] }}</a>
      @endforeach
    </nav>
    <button id="mobile-menu-toggle" class="md:hidden text-gray-700">
      <i class="fas fa-bars"></i>
    </button>
  </div>
</header>