@php
$testimonialsTitle = $content['testimonials_title'] ?? 'Apa Kata Klien';
$testimonials = $content['testimonials'] ?? [];
@endphp

<section id="testimonials" class="py-20">
  <div class="container mx-auto px-6">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">{{ $testimonialsTitle }}</h2>
    </div>
    <div class="grid md:grid-cols-3 gap-8">
      @foreach($testimonials as $testi)
        <div class="bg-white rounded-custom shadow p-6">
          <div class="flex items-center gap-4 mb-4">
            @if(!empty($testi['photo']))
              <img src="{{ $testi['photo'] }}" alt="{{ $testi['name'] }}" class="w-12 h-12 rounded-full object-cover">
            @else
              <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold">
                {{ substr($testi['name'], 0, 1) }}
              </div>
            @endif
            <div>
              <h4 class="font-semibold">{{ $testi['name'] }}</h4>
              <p class="text-sm text-gray-600">{{ $testi['position'] }}</p>
            </div>
          </div>
          <p class="text-gray-700 text-sm italic">“{{ $testi['content'] }}”</p>
          <div class="mt-4 text-yellow-400">
            @for($i = 0; $i < (int)$testi['rating']; $i++)
              <i class="fas fa-star text-sm"></i>
            @endfor
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>