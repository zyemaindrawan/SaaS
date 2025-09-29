@php
$whatsappEnabled = $content['whatsapp_enabled'] ?? false;
$whatsappNumber = $content['whatsapp_number'] ?? '';
$whatsappMessage = $content['whatsapp_message'] ?? 'Halo, saya tertarik dengan layanan Anda.';
$whatsappPosition = $content['whatsapp_position'] ?? 'bottom-right';
$whatsappColor = $content['whatsapp_color'] ?? '#25D366';
$whatsappGreeting = $content['whatsapp_greeting_text'] ?? 'Tanya-tanya dulu yuk!';
@endphp

@if($whatsappEnabled && $whatsappNumber)
  <div class="fixed {{ $whatsappPosition === 'bottom-left' ? 'left-6' : 'right-6' }} bottom-6 z-50">
    <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode($whatsappMessage) }}"
       target="_blank"
       rel="noopener noreferrer"
       class="flex items-center justify-center w-14 h-14 rounded-full shadow-lg text-white text-2xl hover:scale-110 transition"
       style="background-color: {{ $whatsappColor }};">
      <i class="fab fa-whatsapp"></i>
    </a>
    @if($whatsappGreeting)
      <div class="absolute {{ $whatsappPosition === 'bottom-left' ? 'left-16' : 'right-16' }} bottom-2 bg-white text-gray-800 text-sm px-3 py-2 rounded-lg shadow whitespace-nowrap">
        {{ $whatsappGreeting }}
      </div>
    @endif
  </div>
@endif