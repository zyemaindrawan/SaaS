@php
    function resolveAssetPath3($path) {
        if (empty($path)) return null;
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) return $path;
        if (str_starts_with($path, 'website-assets/')) return url('storage/' . $path);
        if (str_starts_with($path, 'storage/')) return url($path);
        return url('storage/' . $path);
    }

    $products = $content['products'] ?? [];
@endphp

<section id="services" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-clean-blue mb-4">
                Layanan <span class="text-clean-yellow">Cleaning</span> Kami
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Berbagai pilihan layanan cuci profesional untuk kebutuhan rumah dan kantor Anda
            </p>
        </div>
        
        <!-- Services Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($products as $index => $product)
                @php
                    $productImage = resolveAssetPath($product['image'] ?? null);
                @endphp
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-2 group">
                    <!-- Service Image -->
                    @if($productImage)
                        <div class="relative overflow-hidden h-56">
                            <img src="{{ $productImage }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute top-3 right-3 bg-clean-yellow text-gray-900 text-xs font-bold px-3 py-1 rounded-full">
                                POPULER
                            </div>
                        </div>
                    @else
                        <div class="w-full h-56 bg-gradient-to-br from-clean-blue-light to-clean-blue flex items-center justify-center">
                            <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Service Info -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 min-h-[56px]">
                            {{ $product['name'] }}
                        </h3>
                        <p class="text-2xl font-extrabold text-clean-blue mb-4">
                            {{ $product['price'] }}
                        </p>
                        <a href="{{ $product['link'] }}" class="block w-full text-center bg-clean-blue hover:bg-clean-blue-dark text-white font-bold py-3 px-4 rounded-lg transition duration-300 shadow-md">
                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
