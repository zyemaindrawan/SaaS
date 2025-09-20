@extends('layouts.app')

@section('title', env('APP_NAME'))

@section('content')
<section class="text-center py-16 md:py-24">
<h1 class="text-4xl md:text-6xl font-bold mb-4">{{env('APP_NAME')}}</h1>
<h2 class="text-3xl md:text-5xl font-bold mb-6">Jasa Pembuatan Website Profesional</h2>
<p class="max-w-3xl mx-auto text-subtext-light dark:text-subtext-dark mb-8">Dapatkan website professional dengan harga terjangkau yang mudah dikelola untuk membantu bisnis Anda berkembang lebih pesat di dunia digital.</p>
<div class="relative inline-block">
<input class="border border-border-light dark:border-border-dark rounded-full py-3 pl-6 pr-40 w-full md:w-auto bg-background-light dark:bg-background-dark" placeholder="www.domainanda.com" type="text"/>
<button class="absolute right-1 top-1 bottom-1 bg-primary text-white px-6 rounded-full font-semibold">Cek Domain</button>
</div>
<div class="bg-primary text-white p-8 rounded-lg max-w-4xl mx-auto mt-[-4rem] relative z-10 shadow-lg">
<div class="flex flex-col md:flex-row justify-between items-center">
<div class="mb-4 md:mb-0">
<p class="text-lg">Lihat demo website yang akan anda dapatkan</p>
<p class="text-sm opacity-80">1.257+ Website telah dibuat</p>
</div>
<button class="bg-white text-primary px-8 py-3 rounded-lg font-semibold">Lihat Demo Website</button>
</div>
</div>
</section>
<section class="py-16 md:py-24">
<h2 class="text-3xl md:text-4xl font-bold text-center mb-4">Mengapa pilih Jasa Pembuatan Website di Bikin.Website?</h2>
<p class="text-center max-w-3xl mx-auto text-subtext-light dark:text-subtext-dark mb-12">Tak hanya melayani jasa pembuatan website, kami juga memberikan layanan modifikasi website agar sesuai dengan kebutuhan bisnis Anda.</p>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
<div class="text-center p-6 border border-border-light dark:border-border-dark rounded-lg">
<span class="material-icons text-primary text-4xl mb-4">speed</span>
<h3 class="font-semibold mb-2">PROSES CEPAT &amp; MUDAH</h3>
<p class="text-sm text-subtext-light dark:text-subtext-dark">Website Anda akan online dalam hitungan jam.</p>
</div>
<div class="text-center p-6 border border-border-light dark:border-border-dark rounded-lg">
<span class="material-icons text-primary text-4xl mb-4">dns</span>
<h3 class="font-semibold mb-2">DOMAIN &amp; HOSTING GRATIS</h3>
<p class="text-sm text-subtext-light dark:text-subtext-dark">Gratis domain .com dan .id, serta gratis hosting selamanya.</p>
</div>
<div class="text-center p-6 border border-border-light dark:border-border-dark rounded-lg">
<span class="material-icons text-primary text-4xl mb-4">security</span>
<h3 class="font-semibold mb-2">FITUR LENGKAP &amp; KEAMANAN TERJAMIN</h3>
<p class="text-sm text-subtext-light dark:text-subtext-dark">Dilengkapi dengan fitur-fitur canggih dan keamanan SSL.</p>
</div>
<div class="text-center p-6 border border-border-light dark:border-border-dark rounded-lg">
<span class="material-icons text-primary text-4xl mb-4">support_agent</span>
<h3 class="font-semibold mb-2">BANYAK PILIHAN DESAIN TERBARU</h3>
<p class="text-sm text-subtext-light dark:text-subtext-dark">Tersedia banyak pilihan desain website yang modern.</p>
</div>
</div>
</section>
<section class="py-16 md:py-24 bg-gray-50 dark:bg-gray-800 rounded-lg">
<h2 class="text-3xl md:text-4xl font-bold text-center mb-4">Paket Website Apa yang Cocok Untuk Saya?</h2>
<p class="text-center max-w-3xl mx-auto text-subtext-light dark:text-subtext-dark mb-12">Kami menyediakan beberapa paket pembuatan website yang dapat membantu Anda memilih jenis website dengan target pasar yang tepat.</p>
<div class="flex justify-center mb-8">
<span class="bg-primary text-white px-4 py-2 rounded-full text-sm">Rekomendasi Kami</span>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
<div class="border border-border-light dark:border-border-dark rounded-lg p-8 bg-background-light dark:bg-background-dark">
<h3 class="font-bold text-lg mb-2">Paket Personal</h3>
<p class="text-sm text-subtext-light dark:text-subtext-dark mb-4">Untuk Website Personal</p>
<p class="text-3xl font-bold mb-1">Rp 1.999.000</p>
<p class="text-subtext-light dark:text-subtext-dark text-sm line-through mb-4">Rp 2.999.000</p>
<ul class="space-y-3 text-sm mb-8">
<li class="flex items-center"><span class="material-icons text-green-500 mr-2">check_circle</span>Buat 1 website</li>
<li class="flex items-center"><span class="material-icons text-green-500 mr-2">check_circle</span>Gratis Domain .com ke-1</li>
<li class="flex items-center"><span class="material-icons text-green-500 mr-2">check_circle</span>SSL Gratis Selamanya</li>
</ul>
<button class="w-full bg-orange-500 text-white py-3 rounded-lg font-semibold">Pilih Paket</button>
</div>
<div class="border-2 border-primary rounded-lg p-8 bg-background-light dark:bg-gray-900 relative">
<span class="absolute top-0 right-4 bg-primary text-white text-xs px-3 py-1 rounded-b-md">PALING LARIS</span>
<h3 class="font-bold text-lg mb-2">Paket UKM</h3>
<p class="text-sm text-subtext-light dark:text-subtext-dark mb-4">Untuk Website UKM</p>
<p class="text-3xl font-bold text-primary mb-1">Rp 2.999.000</p>
<p class="text-subtext-light dark:text-subtext-dark text-sm line-through mb-4">Rp 3.999.000</p>
<ul class="space-y-3 text-sm mb-8">
<li class="flex items-center"><span class="material-icons text-green-500 mr-2">check_circle</span>Buat 1 website</li>
<li class="flex items-center"><span class="material-icons text-green-500 mr-2">check_circle</span>Gratis Domain .com ke-1</li>
<li class="flex items-center"><span class="material-icons text-green-500 mr-2">check_circle</span>SSL Gratis Selamanya</li>
<li class="flex items-center"><span class="material-icons text-green-500 mr-2">check_circle</span>Akses Cpanel</li>
</ul>
<button class="w-full bg-primary text-white py-3 rounded-lg font-semibold">Pilih Paket</button>
</div>
<div class="border border-border-light dark:border-border-dark rounded-lg p-8 bg-background-light dark:bg-background-dark">
<h3 class="font-bold text-lg mb-2">Paket Bisnis</h3>
<p class="text-sm text-subtext-light dark:text-subtext-dark mb-4">Untuk Website Bisnis</p>
<p class="text-3xl font-bold mb-1">Rp 4.999.000</p>
<p class="text-subtext-light dark:text-subtext-dark text-sm line-through mb-4">Rp 5.999.000</p>
<ul class="space-y-3 text-sm mb-8">
<li class="flex items-center"><span class="material-icons text-green-500 mr-2">check_circle</span>Buat 1 website</li>
<li class="flex items-center"><span class="material-icons text-green-500 mr-2">check_circle</span>Gratis Domain .com ke-1</li>
<li class="flex items-center"><span class="material-icons text-green-500 mr-2">check_circle</span>SSL Gratis Selamanya</li>
</ul>
<button class="w-full bg-orange-500 text-white py-3 rounded-lg font-semibold">Pilih Paket</button>
</div>
</div>
<div class="mt-12 text-center">
<button class="bg-orange-500 text-white px-8 py-4 rounded-lg font-bold text-lg">
                        Butuh Website dengan Fitur Custom? <span class="font-normal">Konsultasikan, Gratis!</span>
</button>
</div>
</section>

<section class="py-16 md:py-24">
<h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Pertanyaan Seputar Bikin Website</h2>
<div class="max-w-3xl mx-auto space-y-4">
<div class="border border-border-light dark:border-border-dark rounded-lg">
<button class="w-full flex justify-between items-center p-5 text-left font-semibold">
<span>Mengapa saya harus memiliki website?</span>
<span class="material-icons">expand_more</span>
</button>
</div>
<div class="border border-border-light dark:border-border-dark rounded-lg">
<button class="w-full flex justify-between items-center p-5 text-left font-semibold">
<span>Berapa lama proses pembuatan website?</span>
<span class="material-icons">expand_more</span>
</button>
</div>
<div class="border border-border-light dark:border-border-dark rounded-lg">
<button class="w-full flex justify-between items-center p-5 text-left font-semibold">
<span>Apakah saya bisa menambahkan desain saya sendiri?</span>
<span class="material-icons">expand_more</span>
</button>
</div>
<div class="border border-border-light dark:border-border-dark rounded-lg">
<button class="w-full flex justify-between items-center p-5 text-left font-semibold">
<span>Apakah saya dapat mengelola website?</span>
<span class="material-icons">expand_more</span>
</button>
</div>
<div class="border border-border-light dark:border-border-dark rounded-lg">
<button class="w-full flex justify-between items-center p-5 text-left font-semibold">
<span>Apa syarat membuat website?</span>
<span class="material-icons">expand_more</span>
</button>
</div>
<div class="border border-border-light dark:border-border-dark rounded-lg">
<button class="w-full flex justify-between items-center p-5 text-left font-semibold">
<span>Apakah saya bisa mengontrol konten?</span>
<span class="material-icons">expand_more</span>
</button>
</div>
</div>
</section>
<section class="bg-primary text-white text-center p-12 my-16 md:my-24 rounded-lg">
<h2 class="text-3xl font-bold mb-2">Butuh Bantuan?</h2>
<p class="mb-6">Jangan ragu! Tim kami siap membantu Anda 24 jam.</p>
<button class="bg-white text-primary px-8 py-3 rounded-lg font-semibold">Hubungi Kami</button>
</section>
@endsection