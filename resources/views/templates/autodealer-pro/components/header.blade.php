@php
$companyName = $content['company_name'] ?? 'Dealer Mobil';
$companyLogo = $content['company_logo'] ?? null;
$primaryColor = $content['primary_color'] ?? '#c30010';
@endphp

<header class="fixed top-0 left-0 right-0 bg-white shadow z-50">
  <div class="container mx-auto px-6 py-4 flex justify-between items-center">
    {{-- Logo / Brand --}}
    <a href="#home" class="flex items-center gap-3">
      @if($companyLogo)
        <img src="{{ $companyLogo }}" alt="{{ $companyName }}" class="h-10">
      @endif
      <span class="font-bold text-lg text-gray-900">{{ $companyName }}</span>
    </a>

    {{-- Desktop Menu --}}
    <nav id="desktop-nav" class="hidden md:flex gap-6">
      <a href="#home" class="nav-link">Home</a>
      <a href="#promo" class="nav-link">Promo</a>
      <a href="#cars" class="nav-link">Unit</a>
      <a href="#testimonials" class="nav-link">Testimoni</a>
      <a href="#gallery" class="nav-link">Galeri</a>
      <a href="#contact" class="nav-link">Kontak</a>
    </nav>

    {{-- Mobile Menu Button --}}
    <button id="mobile-menu-toggle" class="md:hidden text-gray-700 focus:outline-none">
      <i class="fas fa-bars text-xl"></i>
    </button>
  </div>

  {{-- Mobile Menu --}}
  <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
    <div class="px-6 py-4 space-y-3">
      <a href="#home" class="nav-link block">Home</a>
      <a href="#promo" class="nav-link block">Promo</a>
      <a href="#cars" class="nav-link block">Unit</a>
      <a href="#testimonials" class="nav-link block">Testimoni</a>
      <a href="#gallery" class="nav-link block">Galeri</a>
      <a href="#contact" class="nav-link block">Kontak</a>
    </div>
  </div>
</header>

<style>
  .nav-link {
    @apply text-gray-700 hover:text-primary transition;
  }
</style>

<script>
  const toggle = document.getElementById('mobile-menu-toggle');
  const menu = document.getElementById('mobile-menu');
  toggle.addEventListener('click', () => {
    menu.classList.toggle('hidden');
  });

  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        menu.classList.add('hidden');
      }
    });
  });

  document.addEventListener('click', function (e) {
    if (!e.target.closest('header')) {
      menu.classList.add('hidden');
    }
  });
</script>