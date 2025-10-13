@php
    $products = $content['products'] ?? [];
@endphp

<section id="products" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Our Products
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Discover our premium selection of products designed to meet your needs
            </p>
        </div>
        
        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                    <!-- Product Image -->
                    @if(!empty($product['image']))
                        <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}" class="w-full h-56 object-cover">
                    @else
                        <div class="w-full h-56 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400 text-sm">No Image</span>
                        </div>
                    @endif
                    
                    <!-- Product Info -->
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            {{ $product['name'] }}
                        </h3>
                        <p class="text-2xl font-bold text-blue-600 mb-4">
                            Rp {{ number_format($product['price'], 0, ',', '.') }}
                        </p>
                        <a href="{{ $product['link'] }}" target="_blank" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
                            Order Now
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
