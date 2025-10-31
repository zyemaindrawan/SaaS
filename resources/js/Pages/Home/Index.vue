<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    canLogin: {
        type: Boolean,
        default: true,
    },
    canRegister: {
        type: Boolean,
        default: true,
    },
    laravelVersion: {
        type: String,
        default: '11.x',
    },
    phpVersion: {
        type: String,
        default: '8.2',
    },
    heroBackground: {
        type: String,
        default: null,
    },
    testimonialImage: {
        type: String,
        default: null,
    },
});

// Countdown timer
const countdown = ref({
    days: 0,
    hours: 0,
    minutes: 5,
    seconds: 10
});

let countdownInterval = null;

const startCountdown = () => {
    const getNextEndTime = () => {
        const now = new Date();
        const startHour = 7; // 07:00 WIB
        const intervalHours = 8; // 8 hours interval
        
        // Set timezone to WIB (GMT+7)
        const nowWIB = new Date(now.getTime() + (7 * 60 * 60 * 1000));
        
        // Get today's start time (07:00 WIB)
        const todayStart = new Date(nowWIB.getFullYear(), nowWIB.getMonth(), nowWIB.getDate(), startHour, 0, 0);
        
        // Calculate how many intervals have passed since start time
        let endTime = new Date(todayStart);
        
        while (endTime <= nowWIB) {
            endTime = new Date(endTime.getTime() + (intervalHours * 60 * 60 * 1000));
        }
        
        // Convert back to local time
        return new Date(endTime.getTime() - (7 * 60 * 60 * 1000));
    };
    
    countdownInterval = setInterval(() => {
        const now = new Date().getTime();
        const endTime = getNextEndTime().getTime();
        const distance = endTime - now;
        
        if (distance > 0) {
            countdown.value.days = Math.floor(distance / (1000 * 60 * 60 * 24));
            countdown.value.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            countdown.value.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            countdown.value.seconds = Math.floor((distance % (1000 * 60)) / 1000);
        } else {
            // Reset if countdown reaches zero
            countdown.value.days = 0;
            countdown.value.hours = 8;
            countdown.value.minutes = 0;
            countdown.value.seconds = 0;
        }
    }, 1000);
};

const scrollToSection = (sectionId) => {
    document.getElementById(sectionId)?.scrollIntoView({ behavior: 'smooth' });
};

const openWhatsApp = () => {
    window.open('https://api.whatsapp.com/send/?phone=628118185852&text=Halo, saya tertarik dengan jasa pembuatan website instan!', '_blank');
};

const copyPromoCode = async () => {
    try {
        await navigator.clipboard.writeText('WEBSUPERFLAZZ');
        // You could add a toast notification here
        alert('Kode promo berhasil disalin!');
    } catch (err) {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = 'WEBSUPERFLAZZ';
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Kode promo berhasil disalin!');
    }
};

onMounted(() => {
    startCountdown();
});

onUnmounted(() => {
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }
});
</script>

<template>
    <Head title="Jasa Pembuatan Website Profesional - Siap dalam 15 Menit" />
    <AppLayout>

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-blue-800 text-white py-12 md:py-20 overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <img :src="props.heroBackground" alt="Hero" class="w-full h-full object-cover opacity-20">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 via-purple-900/80 to-blue-900/80"></div>
            </div>
            
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 md:mb-6 leading-tight">
                    Jasa Pembuatan Website Profesional <br class="hidden sm:block">
                    <span class="text-yellow-300">Siap dalam 15 Menit!</span>
                </h1>
                <p class="text-lg sm:text-xl md:text-2xl mb-6 md:mb-8 opacity-90 max-w-4xl mx-auto">
                    Solusi jasa landing page dan website tanpa coding tercepat untuk bisnis Anda.<br class="hidden sm:block">
                    Cukup isi data, AI kami yang desain.
                </p>
                <div class="flex justify-center">
                    <button @click="scrollToSection('order')" class="bg-yellow-400 text-blue-900 font-bold text-lg sm:text-xl px-8 sm:px-12 py-3 sm:py-4 rounded-full hover:bg-yellow-300 transform hover:scale-105 transition-all duration-300 shadow-lg inline-flex items-center justify-center space-x-2">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                        </svg>
                        <span>Dapatkan Website Saya Sekarang!</span>
                    </button>
                </div>
                <div class="mt-6 md:mt-8 text-sm opacity-80 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <span>Dipercaya 1000+ bisnis di Indonesia</span>
                </div>
            </div>
        </section>

        <!-- Problem & Solution Section -->
        <section class="py-12 md:py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12 md:mb-16">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-4 md:mb-6">Stop Pusing Cari Jasa Bikin Website!</h2>
                    <div class="max-w-4xl mx-auto">
                        <div class="grid md:grid-cols-2 gap-6 md:gap-8">
                            <div class="bg-red-50 p-6 md:p-8 rounded-xl border-l-4 border-red-500">
                                <h3 class="text-lg md:text-xl font-bold text-red-600 mb-4 flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                    </svg>
                                    Masalah yang Sering Dialami:
                                </h3>
                                <ul class="space-y-2 md:space-y-3 text-sm md:text-base text-gray-700">
                                    <li>• Butuh jasa website company profile tapi budget terbatas?</li>
                                    <li>• Ingin bikin landing page jualan tapi tidak bisa coding?</li>
                                    <li>• Jasa pembuatan website mahal dan lama prosesnya?</li>
                                    <li>• Website tidak responsif dan tidak profesional?</li>
                                </ul>
                            </div>
                            <div class="bg-green-50 p-6 md:p-8 rounded-xl border-l-4 border-green-500">
                                <h3 class="text-lg md:text-xl font-bold text-green-600 mb-4 flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                    </svg>
                                    Solusi Kami:
                                </h3>
                                <ul class="space-y-2 md:space-y-3 text-sm md:text-base text-gray-700">
                                    <li>• Jasa website murah yang hasilnya tidak murahan</li>
                                    <li>• Website tanpa coding, siap dalam 15 menit</li>
                                    <li>• Desain profesional dengan teknologi AI</li>
                                    <li>• Harga terjangkau untuk semua kalangan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="py-12 md:py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12 md:mb-16">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-4 md:mb-6">Proses Website Tanpa Coding Kami</h2>
                    <p class="text-lg md:text-xl text-gray-600">Semudah 1-2-3! Tidak perlu keahlian teknis</p>
                </div>
                
                <div class="max-w-4xl mx-auto">
                    <div class="relative">
                        <!-- Connection Lines -->
                        <div class="hidden md:block absolute top-10 left-1/2 transform -translate-x-1/2 w-full">
                            <div class="flex justify-between items-center px-20">
                                <div class="flex-1 h-px border-t border-dashed border-gray-300"></div>
                                <div class="w-20"></div>
                                <div class="flex-1 h-px border-t border-dashed border-gray-300"></div>
                            </div>
                        </div>
                        
                        <div class="grid md:grid-cols-3 gap-8 relative z-10">
                            <div class="text-center">
                                <div class="bg-blue-100 w-16 h-16 md:w-20 md:h-20 rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6 border-4 border-white shadow-lg relative z-20">
                                    <span class="text-2xl md:text-3xl font-bold text-blue-600">1</span>
                                </div>
                                <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-3 md:mb-4">Isi Detail Bisnis</h3>
                                <p class="text-sm md:text-base text-gray-600">Masukkan informasi bisnis Anda. Ini adalah inti dari jasa pembuatan website kami yang mudah dan cepat.</p>
                            </div>
                            
                            <div class="text-center">
                                <div class="bg-purple-100 w-16 h-16 md:w-20 md:h-20 rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6 border-4 border-white shadow-lg relative z-20">
                                    <span class="text-2xl md:text-3xl font-bold text-purple-600">2</span>
                                </div>
                                <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-3 md:mb-4">Upload Materi</h3>
                                <p class="text-sm md:text-base text-gray-600">Unggah foto produk dan logo bisnis Anda. AI kami akan mengatur semuanya dengan profesional.</p>
                            </div>
                            
                            <div class="text-center">
                                <div class="bg-green-100 w-16 h-16 md:w-20 md:h-20 rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6 border-4 border-white shadow-lg relative z-20">
                                    <span class="text-2xl md:text-3xl font-bold text-green-600">3</span>
                                </div>
                                <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-3 md:mb-4">Bayar & Live!</h3>
                                <p class="text-sm md:text-base text-gray-600">Selesaikan pembayaran dan website Anda langsung online. Tidak ada waktu tunggu yang lama!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="order" class="py-12 md:py-20 bg-gradient-to-br from-blue-600 to-purple-700 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 md:mb-6">Detail Penawaran Jasa Pembuatan Website Profesional Kami</h2>
                <p class="text-lg md:text-xl mb-8 md:mb-12 opacity-90">Penawaran terbaik yang sulit ditolak!</p>
                
                <div class="max-w-4xl mx-auto">
                    <!-- Countdown Timer -->
                    <div class="bg-gradient-to-br from-orange-500 to-pink-600 text-white p-6 md:p-8 rounded-2xl mb-6 md:mb-8 shadow-2xl border-2 border-orange-300">
                        <div class="flex items-center justify-center mb-4 md:mb-6">
                            <svg class="w-7 h-7 mr-3 text-yellow-300 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                            <h3 class="text-2xl md:text-3xl font-bold tracking-wide">PENAWARAN TERBATAS!</h3>
                        </div>
                        <div class="flex justify-center items-center space-x-3 md:space-x-6">
                            <div class="flex flex-col items-center">
                                <div class="bg-black/70 backdrop-blur-sm rounded-2xl px-4 py-3 md:px-6 md:py-4 min-w-[70px] md:min-w-[90px] border-2 border-gray-600 shadow-inner">
                                    <div class="text-3xl md:text-5xl font-mono font-bold text-green-400 tracking-wider">{{ String(countdown.hours).padStart(2, '0') }}</div>
                                </div>
                                <div class="text-sm md:text-base font-semibold mt-2 tracking-wide">JAM</div>
                            </div>
                            
                            <div class="text-3xl md:text-4xl font-mono font-bold text-yellow-300 animate-pulse">:</div>
                            
                            <div class="flex flex-col items-center">
                                <div class="bg-black/70 backdrop-blur-sm rounded-2xl px-4 py-3 md:px-6 md:py-4 min-w-[70px] md:min-w-[90px] border-2 border-gray-600 shadow-inner">
                                    <div class="text-3xl md:text-5xl font-mono font-bold text-green-400 tracking-wider">{{ String(countdown.minutes).padStart(2, '0') }}</div>
                                </div>
                                <div class="text-sm md:text-base font-semibold mt-2 tracking-wide">MENIT</div>
                            </div>
                            
                            <div class="text-3xl md:text-4xl font-mono font-bold text-yellow-300 animate-pulse">:</div>
                            
                            <div class="flex flex-col items-center">
                                <div class="bg-black/70 backdrop-blur-sm rounded-2xl px-4 py-3 md:px-6 md:py-4 min-w-[70px] md:min-w-[90px] border-2 border-gray-600 shadow-inner">
                                    <div class="text-3xl md:text-5xl font-mono font-bold text-green-400 tracking-wider">{{ String(countdown.seconds).padStart(2, '0') }}</div>
                                </div>
                                <div class="text-sm md:text-base font-semibold mt-2 tracking-wide">DETIK</div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Card -->
                    <div class="bg-white text-gray-800 rounded-2xl p-6 md:p-8 shadow-2xl">
                        <div class="mb-6 md:mb-8">
                            <div class="text-gray-500 text-lg md:text-xl line-through mb-2">Harga Normal: Rp 1.499.999</div>
                            <div class="text-3xl md:text-5xl font-bold text-green-600 mb-2">Rp 499.000</div>
                            <div class="text-base md:text-lg text-blue-600 font-semibold flex items-center justify-center flex-wrap gap-2">
                                <span>Gunakan kode:</span>
                                <div class="relative inline-flex items-center">
                                    <span 
                                        class="bg-gradient-to-r from-yellow-300 to-orange-300 text-orange-800 px-3 md:px-4 py-2 rounded-lg font-bold text-sm md:text-base border-2 border-dashed border-orange-400 cursor-pointer hover:from-yellow-400 hover:to-orange-400 transition-all duration-300 select-all shadow-lg"
                                        @click="copyPromoCode"
                                        title="Klik untuk copy kode promo"
                                    >
                                        WEBSUPERFLAZZ
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 mb-6 md:mb-8">
                            <div class="text-left">
                                <h4 class="font-bold text-base md:text-lg mb-3 md:mb-4 text-blue-600 flex items-center">
                                    Yang Anda Dapatkan:
                                </h4>
                                <ul class="space-y-2 md:space-y-3">
                                    <li class="flex items-start text-sm md:text-base">
                                        <span class="text-green-500 mr-2 flex-shrink-0">✓</span>
                                        <span>Desain website Profesional 1 halaman (Cocok untuk jasa website company profile)</span>
                                    </li>
                                    <li class="flex items-start text-sm md:text-base">
                                        <span class="text-green-500 mr-2 flex-shrink-0">✓</span>
                                        <span>Domain .com GRATIS</span>
                                    </li>
                                    <li class="flex items-start text-sm md:text-base">
                                        <span class="text-green-500 mr-2 flex-shrink-0">✓</span>
                                        <span>Siap dalam 15 Menit (solusi jasa bikin website tercepat)</span>
                                    </li>
                                    <li class="flex items-start text-sm md:text-base">
                                        <span class="text-green-500 mr-2 flex-shrink-0">✓</span>
                                        <span>Sangat terjangkau (jasa website murah berkualitas)</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="text-left">
                                <h4 class="font-bold text-base md:text-lg mb-3 md:mb-4 text-blue-600 flex items-center">
                                    Fitur Premium:
                                </h4>
                                <ul class="space-y-2 md:space-y-3">
                                    <li class="flex items-start text-sm md:text-base">
                                        <span class="text-green-500 mr-2 flex-shrink-0">✓</span>
                                        <span>Sempurna untuk Promosi (Efektif untuk bikin landing page jualan)</span>
                                    </li>
                                    <li class="flex items-start text-sm md:text-base">
                                        <span class="text-green-500 mr-2 flex-shrink-0">✓</span>
                                        <span>Responsif di semua device</span>
                                    </li>
                                    <li class="flex items-start text-sm md:text-base">
                                        <span class="text-green-500 mr-2 flex-shrink-0">✓</span>
                                        <span>SEO Friendly</span>
                                    </li>
                                    <li class="flex items-start text-sm md:text-base">
                                        <span class="text-green-500 mr-2 flex-shrink-0">✓</span>
                                        <span>Support 24/7</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <Link :href="route('templates.index')" class="bg-gradient-to-r from-green-500 to-green-600 text-white font-bold text-lg md:text-xl px-8 md:px-12 py-3 md:py-4 rounded-full hover:from-green-600 hover:to-green-700 transform hover:scale-105 transition-all duration-300 shadow-lg inline-flex items-center justify-center space-x-2">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                            </svg>
                            <span>Ya, Saya Mau Website Rp 499rb!</span>
                        </Link>
                        
                        <p class="mt-4 text-sm text-gray-600 flex items-center justify-center space-x-4">
                            <span class="flex items-center space-x-1">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                                </svg>
                                <span>Pembayaran Aman & Terpercaya</span>
                            </span>
                            <span class="text-gray-400">|</span>
                            <span class="flex items-center space-x-1">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                                </svg>
                                <span>Garansi 100% Puas atau Uang Kembali</span>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="py-12 md:py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12 md:mb-16">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-4 md:mb-6">FAQ</h2>
                    <p class="text-lg md:text-xl text-gray-600">Pertanyaan yang sering ditanyakan tentang layanan kami</p>
                </div>
                
                <div class="max-w-4xl mx-auto">
                    <div class="space-y-6">
                        <div class="bg-white rounded-xl p-4 md:p-6 shadow-lg">
                            <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-3 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/>
                                </svg>
                                Apa bedanya jasa landing page ini dengan website biasa?
                            </h3>
                            <p class="text-sm md:text-base text-gray-600">Jasa landing page kami fokus pada konversi dan penjualan. Didesain khusus untuk mengubah pengunjung menjadi pelanggan dengan teknologi AI yang canggih.</p>
                        </div>
                        
                        <div class="bg-white rounded-xl p-4 md:p-6 shadow-lg">
                            <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-3 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/>
                                </svg>
                                Apakah saya benar-benar bisa punya website tanpa coding?
                            </h3>
                            <p class="text-sm md:text-base text-gray-600">Absolutely! Sistem website tanpa coding kami menggunakan AI untuk membuat website profesional. Anda hanya perlu mengisi data bisnis dan upload foto.</p>
                        </div>
                        
                        <div class="bg-white rounded-xl p-4 md:p-6 shadow-lg">
                            <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-3 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/>
                                </svg>
                                Apakah jasa pembuatan website ini ada biaya bulanan lainnya?
                            </h3>
                            <p class="text-sm md:text-base text-gray-600">Tidak ada biaya tersembunyi! Harga Rp 499.000 sudah termasuk semuanya. Hosting dan domain gratis selamanya untuk paket ini.</p>
                        </div>
                        
                        <div class="bg-white rounded-xl p-4 md:p-6 shadow-lg">
                            <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-3 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/>
                                </svg>
                                Apakah website dapat saya edit sendiri?
                            </h3>
                            <p class="text-sm md:text-base text-gray-600">Ya! Anda mendapat akses penuh untuk mengedit konten website kapan saja melalui dashboard yang mudah digunakan, tanpa perlu keahlian teknis.</p>
                        </div>
                        
                        <div class="bg-white rounded-xl p-4 md:p-6 shadow-lg">
                            <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-3 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/>
                                </svg>
                                Benarkah website siap dalam 15 menit?
                            </h3>
                            <p class="text-sm md:text-base text-gray-600">Ya benar! Setelah Anda melengkapi data dan upload materi, AI kami akan generate website profesional dalam hitungan menit, bukan hari atau minggu.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA Section -->
        <section class="py-12 md:py-20 bg-gradient-to-r from-green-600 to-blue-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 md:mb-6">Ambil Kesempatan Ini Sekarang!</h2>
                <p class="text-lg md:text-xl mb-6 md:mb-8 opacity-90">
                    Jangan tunda lagi! Dapatkan jasa pembuatan website profesional dengan harga paling terjangkau dalam waktu terbatas
                </p>
                
                <div class="max-w-5xl mx-auto mb-6 md:mb-8">
                    <div class="grid md:grid-cols-3 gap-6 md:gap-8 text-center mb-6 md:mb-8">
                        <div class="bg-white/15 backdrop-blur-sm rounded-2xl p-6 md:p-8 border border-white/20 hover:bg-white/20 transition-all duration-300">
                            <div class="mb-4">
                                <svg class="w-16 h-16 md:w-20 md:h-20 mx-auto text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M7 2v11h3v9l7-12h-4l3-8z"/>
                                </svg>
                            </div>
                            <div class="font-bold text-lg md:text-xl mb-2">Proses Cepat</div>
                            <div class="text-sm md:text-base opacity-80">Siap dalam 15 menit</div>
                        </div>
                        <div class="bg-white/15 backdrop-blur-sm rounded-2xl p-6 md:p-8 border border-white/20 hover:bg-white/20 transition-all duration-300">
                            <div class="mb-4">
                                <svg class="w-16 h-16 md:w-20 md:h-20 mx-auto text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                                </svg>
                            </div>
                            <div class="font-bold text-lg md:text-xl mb-2">Harga Terjangkau</div>
                            <div class="text-sm md:text-base opacity-80">Hanya Rp 499.000</div>
                        </div>
                        <div class="bg-white/15 backdrop-blur-sm rounded-2xl p-6 md:p-8 border border-white/20 hover:bg-white/20 transition-all duration-300">
                            <div class="mb-4">
                                <svg class="w-16 h-16 md:w-20 md:h-20 mx-auto text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm2.7-2h8.6l.9-5.4-2.1 1.4L12 8l-3.1 2L6.8 8.6L7.7 14z"/>
                                </svg>
                            </div>
                            <div class="font-bold text-lg md:text-xl mb-2">Kualitas Premium</div>
                            <div class="text-sm md:text-base opacity-80">Desain profesional</div>
                        </div>
                    </div>
                    
                    <Link :href="route('templates.index')" class="bg-yellow-400 text-blue-900 font-bold text-lg md:text-2xl px-8 md:px-16 py-4 md:py-5 rounded-full hover:bg-yellow-300 transform hover:scale-105 transition-all duration-300 shadow-xl inline-flex items-center justify-center space-x-2">
                        <svg class="w-6 h-6 md:w-8 md:h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        <span>Ya, Saya Mau Website Rp 499rb!</span>
                    </Link>
                </div>
                
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 md:p-6 max-w-2xl mx-auto">
                    <h3 class="text-xl md:text-2xl font-bold mb-3 md:mb-4">Ada Pertanyaan?</h3>
                    <p class="mb-3 md:mb-4 text-sm md:text-base">Masih ada pertanyaan seputar jasa bikin website kami?</p>
                    <button @click="openWhatsApp" class="bg-green-500 text-white font-bold px-4 md:px-8 py-2 md:py-3 rounded-full hover:bg-green-600 transition-all duration-300 shadow-lg text-sm md:text-base">
                        Hubungi Kami di WhatsApp: 0811-818-5852
                    </button>
                </div>
            </div>
        </section>

        <!-- Testimonial Section -->
        <section class="py-12 md:py-20 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12 md:mb-16">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-4 md:mb-6">Apa Kata Mereka Tentang Layanan Kami?</h2>
                    <p class="text-lg md:text-xl text-gray-600">1000+ bisnis telah mempercayai kami</p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    <div class="bg-white p-4 md:p-6 rounded-xl shadow-lg">
                        <div class="flex mb-3 md:mb-4">
                            <span class="text-yellow-400 text-sm md:text-base">★★★★★</span>
                        </div>
                        <p class="text-sm md:text-base text-gray-600 mb-3 md:mb-4">"Website saya jadi dalam hitungan menit! Benar-benar mudah dan hasilnya sangat profesional. Recommended banget untuk yang butuh website cepat."</p>
                        <div class="flex items-center">
                            <img :src="props.testimonialImage" alt="Chandra Sahita" class="w-10 h-10 rounded-full mr-3">
                            <div class="font-bold text-sm md:text-base text-gray-800">- Chandra Sahita, Owner Travel</div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-4 md:p-6 rounded-xl shadow-lg">
                        <div class="flex mb-3 md:mb-4">
                            <span class="text-yellow-400 text-sm md:text-base">★★★★★</span>
                        </div>
                        <p class="text-sm md:text-base text-gray-600 mb-3 md:mb-4">"Awalnya ragu dengan harga yang murah, tapi ternyata kualitasnya luar biasa! Customer service juga sangat responsif. Proses instan dan mudah, thanks ya..."</p>
                        <div class="flex items-center">
                            <img :src="props.testimonialImage" alt="Sari Dewi" class="w-10 h-10 rounded-full mr-3">
                            <div class="font-bold text-sm md:text-base text-gray-800">- Sari Dewi, Wedding Organizer</div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-4 md:p-6 rounded-xl shadow-lg">
                        <div class="flex mb-3 md:mb-4">
                            <span class="text-yellow-400 text-sm md:text-base">★★★★★</span>
                        </div>
                        <p class="text-sm md:text-base text-gray-600 mb-3 md:mb-4">"Sebagai pemula yang tidak ngerti coding sama sekali, layanan ini sangat membantu. Website company profile saya langsung jadi dengan waktu instan."</p>
                        <div class="flex items-center">
                            <img :src="props.testimonialImage" alt="Ahmad Rizky" class="w-10 h-10 rounded-full mr-3">
                            <div class="font-bold text-sm md:text-base text-gray-800">- Ahmad Rizky, Konsultan Bisnis</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Location Map Section -->
        <section class="py-12 md:py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8 md:mb-12">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-4">Lokasi Kantor Kami</h3>
                    <p class="text-lg md:text-xl text-gray-600"><strong>PT. Media Periklanan Indonesia</strong><br>
                    <span class="text-sm md:text-base">Alamanda Tower Lantai 30<br>
                    Jl. Tahi Bonar Simatupang No.22-26, RT.1/RW.1, Cilandak, Jakarta Selatan 12430</span></p>
                </div>
                <div class="max-w-4xl mx-auto">
                    <div class="bg-gray-100 rounded-xl overflow-hidden shadow-lg">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.2648397398937!2d106.80464530000001!3d-6.291153000000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f1800fa270a7%3A0x9d6aa0b988f53bed!2sMEDIA%20PERIKLANAN%20INDONESIA!5e0!3m2!1sen!2sid!4v1698731760000"
                            width="100%" 
                            height="400" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="w-full"
                        ></iframe>
                    </div>
                </div>
            </div>
        </section>

        <!-- Floating Contact Buttons -->
        <div class="fixed bottom-4 left-1/2 transform -translate-x-1/2 z-50 w-11/12 max-w-2xl">
            <div class="grid grid-cols-2 gap-4">
                <!-- WhatsApp Button -->
                <div class="flex flex-col items-center">
                    <button 
                        @click="openWhatsApp"
                        class="bg-green-500 hover:bg-green-600 text-white py-4 px-6 rounded-2xl shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-3 w-full"
                        title="Chat WhatsApp"
                    >
                        <!-- WhatsApp SVG Icon -->
                        <svg 
                            class="w-7 h-7" 
                            viewBox="0 0 24 24" 
                            fill="currentColor"
                        >
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.865 3.488"/>
                        </svg>
                        <span class="font-bold text-lg leading-tight">WhatsApp<br>
                        <span class="text-xs font-light">0811-818-5852</span>
                        </span>
                    </button>
                </div>

                <!-- Phone Button -->
                <div class="flex flex-col items-center">
                    <button 
                        @click="() => window.open('tel:+628118185852', '_self')"
                        class="bg-blue-500 hover:bg-blue-600 text-white py-4 px-6 rounded-2xl shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-3 w-full"
                        title="Call Phone"
                    >
                        <!-- Phone SVG Icon -->
                        <svg 
                            class="w-7 h-7" 
                            viewBox="0 0 24 24" 
                            fill="currentColor"
                        >
                            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                        <span class="font-bold text-lg leading-tight">Call Sekarang<br>
                        <span class="text-xs font-light">0811-818-5852</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>

    </AppLayout>
</template>
