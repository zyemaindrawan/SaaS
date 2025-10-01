@php
$carsTitle = $content['cars_title'] ?? 'Unit Siap Kirim / Stok Terbatas';
$carsSubtitle = $content['cars_subtitle'] ?? 'Pilih unit favorit Anda. Semua mobil sudah melalui inspeksi ketat dan garansi resmi Toyota.';
$cars = $content['cars'] ?? [];
$primaryColor = $content['primary_color'] ?? '#c30010';
@endphp

<section id="cars" class="py-20 bg-white">
  <div class="container mx-auto px-6">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">{{ $carsTitle }}</h2>
      <p class="text-gray-600 max-w-2xl mx-auto">{{ $carsSubtitle }}</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach($cars as $car)
        <div class="bg-white rounded-custom shadow hover:shadow-xl transition overflow-hidden">
          <img src="{{ $car['image'] ?? 'https://via.placeholder.com/400x250' }}" alt="{{ $car['name'] }}" class="w-full h-48 object-cover">
          <div class="p-6">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $car['type'] === 'baru' ? 'Baru' : 'Bekas' }}</span>
              <span class="text-xs text-gray-500">{{ $car['year'] }}</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $car['name'] }}</h3>
            <p class="text-2xl font-extrabold text-primary mb-4">{{ $car['price'] }}</p>
            <ul class="text-sm text-gray-600 space-y-1 mb-4">
              @foreach(explode(',', $car['features']) as $feature)
                <li><i class="fas fa-check text-green-500 mr-2"></i>{{ trim($feature) }}</li>
              @endforeach
            </ul>
            <div class="flex gap-3">
              <a href="https://wa.me/{{ $car['whatsapp_sales'] }}?text=Halo,%20saya%20tertarik%20dengan%20{{ urlencode($car['name']) }}" 
                 target="_blank" 
                 class="flex-1 bg-green-500 text-white text-center py-2 rounded-custom font-semibold hover:bg-green-600 transition">
                <i class="fab fa-whatsapp mr-2"></i>Chat Sales
              </a>
              <a href="#contact" class="bg-primary flex-1 border border-primary text-white text-center py-2 rounded-custom font-semibold hover:bg-primary-600 hover:text-white transition">
                Test Drive
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>