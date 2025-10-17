@php
    $testimonials = $content['testimonials'] ?? [];
@endphp

@if(!empty($testimonials))
<section id="testimonials" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                What Our Clients Say
            </h2>
            <p class="text-gray-600 text-lg">
                Real testimonials from our satisfied customers
            </p>
        </div>
        
        <!-- Testimonials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($testimonials as $testi)
                <div class="bg-white rounded-lg shadow-md p-8 hover:shadow-xl transition duration-300">
                    <!-- Customer Photo -->
                    <div class="flex items-center mb-6">
                        @if(!empty($testi['photo']))
                            <img src="{{ resolveAssetPath($testi['photo']) }}" alt="{{ $testi['name'] }}" class="w-16 h-16 rounded-full object-cover mr-4">
                        @else
                            <div class="w-16 h-16 rounded-full bg-blue-600 flex items-center justify-center text-white text-2xl font-bold mr-4">
                                {{ substr($testi['name'], 0, 1) }}
                            </div>
                        @endif
                        
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">{{ $testi['name'] }}</h4>
                            @if(!empty($testi['position']))
                                <p class="text-gray-600 text-sm">{{ $testi['position'] }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Rating -->
                    <div class="flex mb-4">
                        @for($i = 0; $i < (int)$testi['rating']; $i++)
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        @endfor
                    </div>
                    
                    <!-- Testimonial Content -->
                    <p class="text-gray-700 italic leading-relaxed">
                        "{{ $testi['content'] }}"
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
