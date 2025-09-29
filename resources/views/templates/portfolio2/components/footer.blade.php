@php
$companyName = $content['company_name'] ?? 'Portfolio';
$contactEmail = $content['contact_email'] ?? '';
$contactPhone = $content['contact_phone'] ?? '';
$contactAddress = $content['contact_address'] ?? '';
$socialLinks = $content['social_links'] ?? [];
$primaryColor = $content['primary_color'] ?? '#6366f1';
@endphp

<footer class="bg-gray-900 text-white py-12">
  <div class="container mx-auto px-6 grid md:grid-cols-3 gap-8">
    <div>
      <h3 class="font-bold text-lg mb-4">{{ $companyName }}</h3>
      <p class="text-sm opacity-80">Portofolio profesional modern untuk kreator digital.</p>
    </div>
    <div>
      <h4 class="font-semibold mb-4">Kontak</h4>
      <ul class="space-y-2 text-sm opacity-80">
        @if($contactEmail)
          <li><a href="mailto:{{ $contactEmail }}" class="hover:underline">{{ $contactEmail }}</a></li>
        @endif
        @if($contactPhone)
          <li><a href="tel:{{ $contactPhone }}" class="hover:underline">{{ $contactPhone }}</a></li>
        @endif
        @if($contactAddress)
          <li>{{ $contactAddress }}</li>
        @endif
      </ul>
    </div>
    <div>
      <h4 class="font-semibold mb-4">Sosial Media</h4>
      <div class="flex gap-4">
        @foreach($socialLinks as $link)
          <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" class="text-white hover:text-primary transition">
            <i class="fab fa-{{ $link['platform'] }} text-xl"></i>
          </a>
        @endforeach
      </div>
    </div>
  </div>
  <div class="border-t border-gray-800 mt-8 pt-6 text-center text-xs opacity-60">
    &copy; {{ date('Y') }} {{ $companyName }}. Semua hak dilindungi.
  </div>
</footer>