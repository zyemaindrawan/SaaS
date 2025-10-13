@php
    $products = $content['products'] ?? [];
@endphp

<section id="products" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">
                Unit <span class="text-honda-red">Honda</span> Tersedia
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Pilihan unit Honda terbaik dengan promo menarik
            </p>
        </div>
        
        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-2">
                    <!-- Product Image -->
                    @if(!empty($product['image']))
                        <div class="relative overflow-hidden">
                            <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}" class="w-full h-56 object-cover">
                            <div class="absolute top-3 right-3 bg-honda-red text-white text-xs font-bold px-3 py-1 rounded-full">
                                PROMO
                            </div>
                        </div>
                    @else
                        <div class="w-full h-56 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400 text-sm">No Image</span>
                        </div>
                    @endif
                    
                    <!-- Product Info -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">
                            {{ $product['name'] }}
                        </h3>
                        <p class="text-2xl font-extrabold text-honda-red mb-4">
                            @if(is_numeric(str_replace(['.', ','], '', $product['price'])))
                                Rp {{ number_format($product['price'], 0, ',', '.') }}
                            @else
                                {{ $product['price'] }}
                            @endif
                        </p>
                        <a href="{{ $product['link'] }}" target="_blank" class="block w-full text-center bg-honda-red hover:bg-honda-red-dark text-white font-bold py-3 px-4 rounded-lg transition duration-300 shadow-md">
                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            Hubungi Sales
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
