@php
    $contactAddress = $content['contact_address'] ?? '';
    $contactPhone = $content['contact_phone'] ?? '';
    $contactEmail = $content['contact_email'] ?? '';
    $whatsappNumber = $content['whatsapp_number'] ?? '';
    $companyName = $content['company_name'] ?? 'Bersih Maksimal';
@endphp

<section id="contact" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-clean-blue mb-4">
                Hubungi <span class="text-clean-yellow">Kami</span>
            </h2>
            <p class="text-gray-600 text-lg">
                Siap melayani kebutuhan cleaning Anda kapan saja
            </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Information -->
            <div class="bg-gradient-to-br from-clean-blue to-clean-blue-dark text-white rounded-lg shadow-2xl p-8">
                <h3 class="text-2xl font-bold mb-6">Informasi Kontak</h3>
                
                <div class="space-y-6">
                    @if($contactAddress)
                        <div class="flex items-start">
                            <div class="bg-white/20 p-3 rounded-lg mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold mb-1">Area Layanan</h4>
                                <p class="text-white/90">{{ $contactAddress }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($contactPhone)
                        <div class="flex items-start">
                            <div class="bg-white/20 p-3 rounded-lg mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold mb-1">Telepon</h4>
                                <p class="text-white/90">{{ $contactPhone }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($contactEmail)
                        <div class="flex items-start">
                            <div class="bg-white/20 p-3 rounded-lg mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold mb-1">Email</h4>
                                <p class="text-white/90">{{ $contactEmail }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="mt-8 pt-8 border-t border-white/20">
                    <h4 class="font-bold mb-4">Jam Operasional</h4>
                    <div class="space-y-2 text-white/90">
                        <p>Senin - Jumat: 08:00 - 18:00</p>
                        <p>Sabtu - Minggu: 08:00 - 16:00</p>
                        <p class="text-clean-yellow font-semibold mt-4">Panggilan 24/7 untuk keadaan darurat!</p>
                    </div>
                </div>
                
                @if($whatsappNumber)
                    <div class="mt-8">
                        <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" class="flex items-center justify-center bg-clean-yellow hover:bg-clean-yellow-light text-gray-900 font-bold px-6 py-3 rounded-lg transition duration-300 shadow-lg">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            Chat WhatsApp Sekarang
                        </a>
                    </div>
                @endif
            </div>
            
            <!-- Promo Banner -->
            <div class="bg-gradient-to-br from-clean-yellow to-clean-yellow-light rounded-lg shadow-2xl p-8 flex flex-col justify-center items-center text-center">
                <svg class="w-32 h-32 text-clean-blue mb-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                </svg>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-4">Promo Spesial!</h3>
                <p class="text-xl text-gray-800 mb-6">Diskon 20% untuk pelanggan baru</p>
                <div class="bg-white text-clean-blue px-6 py-3 rounded-full font-bold text-lg shadow-md">
                    Kode: BERSIH20
                </div>
                <p class="text-gray-700 mt-4 text-sm">*Berlaku untuk minimal pemesanan 2 sofa</p>
            </div>
        </div>
    </div>
</section>
