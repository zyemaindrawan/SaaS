@php
    $testimonials = $content['testimonials'] ?? [];
@endphp

@if(!empty($testimonials))
<section id="testimonials" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">
                Testimoni <span class="text-honda-red">Pembeli</span>
            </h2>
            <p class="text-gray-600 text-lg">
                Kepercayaan pelanggan adalah prioritas kami
            </p>
        </div>
        
        <!-- Testimonials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($testimonials as $testi)
                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-2xl transition duration-300 relative">
                    <!-- Quote Icon -->
                    <div class="absolute top-4 right-4">
                        <svg class="w-10 h-10 text-honda-red opacity-20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                        </svg>
                    </div>
                    
                    <!-- Customer Photo -->
                    <div class="flex items-center mb-6">
                        @if(!empty($testi['photo']))
                            <img src="{{ asset('storage/' . $testi['photo']) }}" alt="{{ $testi['name'] }}" class="w-16 h-16 rounded-full object-cover mr-4 border-4 border-honda-red">
                        @else
                            <div class="w-16 h-16 rounded-full bg-honda-red flex items-center justify-center text-white text-2xl font-bold mr-4">
                                {{ substr($testi['name'], 0, 1) }}
                            </div>
                        @endif
                        
                        <div>
                            <h4 class="text-lg font-bold text-gray-900">{{ $testi['name'] }}</h4>
                            @if(!empty($testi['position']))
                                <p class="text-gray-600 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $testi['position'] }}
                                </p>
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
