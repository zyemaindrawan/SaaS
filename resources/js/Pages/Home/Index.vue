<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
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
    const endDate = new Date();
    endDate.setDate(endDate.getDate() + 1);
    
    countdownInterval = setInterval(() => {
        const now = new Date().getTime();
        const distance = endDate.getTime() - now;
        
        if (distance > 0) {
            countdown.value.days = Math.floor(distance / (1000 * 60 * 60 * 24));
            countdown.value.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            countdown.value.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            countdown.value.seconds = Math.floor((distance % (1000 * 60)) / 1000);
        }
    }, 1000);
};

const scrollToSection = (sectionId) => {
    document.getElementById(sectionId)?.scrollIntoView({ behavior: 'smooth' });
};

const openWhatsApp = () => {
    window.open('https://wa.me/6281193506085?text=Halo, saya tertarik dengan jasa pembuatan website profesional Anda!', '_blank');
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
    <Head title="Jasa Pembuatan Website Profesional - Siap dalam 1 Jam" />
    <AppLayout>

        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-blue-600 via-purple-600 to-blue-800 text-white py-12 md:py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 md:mb-6 leading-tight">
                    Jasa Pembuatan Website Profesional<br class="hidden sm:block">
                    <span class="text-yellow-300">Siap dalam 1 Jam!</span>
                </h1>
                <p class="text-lg sm:text-xl md:text-2xl mb-6 md:mb-8 opacity-90 max-w-4xl mx-auto">
                    Solusi jasa landing page dan website tanpa coding tercepat untuk bisnis Anda.<br class="hidden sm:block">
                    Cukup isi data, AI kami yang desain.
                </p>
                <button @click="scrollToSection('order')" class="bg-yellow-400 text-blue-900 font-bold text-lg sm:text-xl px-8 sm:px-12 py-3 sm:py-4 rounded-full hover:bg-yellow-300 transform hover:scale-105 transition-all duration-300 shadow-lg">
                    🚀 Dapatkan Website Saya Sekarang!
                </button>
                <div class="mt-6 md:mt-8 text-sm opacity-80">
                    ⭐ Dipercaya 1000+ bisnis di Indonesia
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
                                <h3 class="text-lg md:text-xl font-bold text-red-600 mb-4">❌ Masalah yang Sering Dialami:</h3>
                                <ul class="space-y-2 md:space-y-3 text-sm md:text-base text-gray-700">
                                    <li>• Butuh jasa website company profile tapi budget terbatas?</li>
                                    <li>• Ingin bikin landing page jualan tapi tidak bisa coding?</li>
                                    <li>• Jasa pembuatan website mahal dan lama prosesnya?</li>
                                    <li>• Website tidak responsif dan tidak profesional?</li>
                                </ul>
                            </div>
                            <div class="bg-green-50 p-6 md:p-8 rounded-xl border-l-4 border-green-500">
                                <h3 class="text-lg md:text-xl font-bold text-green-600 mb-4">✅ Solusi Kami:</h3>
                                <ul class="space-y-2 md:space-y-3 text-sm md:text-base text-gray-700">
                                    <li>• Jasa website murah yang hasilnya tidak murahan</li>
                                    <li>• Website tanpa coding, siap dalam 1 jam</li>
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
                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="bg-blue-100 w-16 h-16 md:w-20 md:h-20 rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                                <span class="text-2xl md:text-3xl font-bold text-blue-600">1</span>
                            </div>
                            <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-3 md:mb-4">Isi Detail Bisnis</h3>
                            <p class="text-sm md:text-base text-gray-600">Masukkan informasi bisnis Anda. Ini adalah inti dari jasa pembuatan website kami yang mudah dan cepat.</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="bg-purple-100 w-16 h-16 md:w-20 md:h-20 rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                                <span class="text-2xl md:text-3xl font-bold text-purple-600">2</span>
                            </div>
                            <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-3 md:mb-4">Upload Materi</h3>
                            <p class="text-sm md:text-base text-gray-600">Unggah foto produk dan logo bisnis Anda. AI kami akan mengatur semuanya dengan profesional.</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="bg-green-100 w-16 h-16 md:w-20 md:h-20 rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                                <span class="text-2xl md:text-3xl font-bold text-green-600">3</span>
                            </div>
                            <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-3 md:mb-4">Bayar & Live!</h3>
                            <p class="text-sm md:text-base text-gray-600">Selesaikan pembayaran dan website Anda langsung online. Tidak ada waktu tunggu yang lama!</p>
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
                    <div class="bg-red-600 text-white p-4 md:p-6 rounded-xl mb-6 md:mb-8 shadow-lg">
                        <h3 class="text-xl md:text-2xl font-bold mb-3 md:mb-4">Penawaran Terbatas!</h3>
                        <div class="flex justify-center space-x-4 md:space-x-8 text-center">
                            <!-- <div>
                                <div class="text-2xl md:text-3xl font-bold">{{ countdown.days }}</div>
                                <div class="text-xs md:text-sm opacity-80">Hari</div>
                            </div> -->
                            <div>
                                <div class="text-2xl md:text-3xl font-bold">{{ countdown.hours }}</div>
                                <div class="text-xs md:text-sm opacity-80">Jam</div>
                            </div>
                            <div>
                                <div class="text-2xl md:text-3xl font-bold">{{ countdown.minutes }}</div>
                                <div class="text-xs md:text-sm opacity-80">Menit</div>
                            </div>
                            <div>
                                <div class="text-2xl md:text-3xl font-bold">{{ countdown.seconds }}</div>
                                <div class="text-xs md:text-sm opacity-80">Detik</div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Card -->
                    <div class="bg-white text-gray-800 rounded-2xl p-6 md:p-8 shadow-2xl">
                        <div class="mb-6 md:mb-8">
                            <div class="text-gray-500 text-lg md:text-xl line-through mb-2">Harga Normal: Rp 1.499.999</div>
                            <div class="text-3xl md:text-5xl font-bold text-green-600 mb-2">Rp 499.000</div>
                            <div class="text-base md:text-lg text-blue-600 font-semibold">Gunakan kode: <span class="bg-yellow-200 px-2 md:px-3 py-1 rounded font-bold text-sm md:text-base">WEBSUPERFLAZZ</span></div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 mb-6 md:mb-8">
                            <div class="text-left">
                                <h4 class="font-bold text-base md:text-lg mb-3 md:mb-4 text-blue-600">✨ Yang Anda Dapatkan:</h4>
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
                                        <span>Siap dalam 1 Jam (solusi jasa bikin website tercepat)</span>
                                    </li>
                                    <li class="flex items-start text-sm md:text-base">
                                        <span class="text-green-500 mr-2 flex-shrink-0">✓</span>
                                        <span>Sangat terjangkau (jasa website murah berkualitas)</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="text-left">
                                <h4 class="font-bold text-base md:text-lg mb-3 md:mb-4 text-blue-600">🚀 Fitur Premium:</h4>
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

                        <Link :href="route('register')" class="bg-gradient-to-r from-green-500 to-green-600 text-white font-bold text-lg md:text-xl px-8 md:px-12 py-3 md:py-4 rounded-full hover:from-green-600 hover:to-green-700 transform hover:scale-105 transition-all duration-300 shadow-lg inline-block">
                            💎 Ya, Saya Mau Website Rp 499rb!
                        </Link>
                        
                        <p class="mt-4 text-sm text-gray-600">
                            💳 Pembayaran Aman & Terpercaya | 🔒 Garansi 100% Puas atau Uang Kembali
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
                            <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-3">❓ Apa bedanya jasa landing page ini dengan website biasa?</h3>
                            <p class="text-sm md:text-base text-gray-600">Jasa landing page kami fokus pada konversi dan penjualan. Didesain khusus untuk mengubah pengunjung menjadi pelanggan dengan teknologi AI yang canggih.</p>
                        </div>
                        
                        <div class="bg-white rounded-xl p-4 md:p-6 shadow-lg">
                            <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-3">❓ Apakah saya benar-benar bisa punya website tanpa coding?</h3>
                            <p class="text-sm md:text-base text-gray-600">Absolutely! Sistem website tanpa coding kami menggunakan AI untuk membuat website profesional. Anda hanya perlu mengisi data bisnis dan upload foto.</p>
                        </div>
                        
                        <div class="bg-white rounded-xl p-4 md:p-6 shadow-lg">
                            <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-3">❓ Apakah jasa pembuatan website ini ada biaya bulanan lainnya?</h3>
                            <p class="text-sm md:text-base text-gray-600">Tidak ada biaya tersembunyi! Harga Rp 499.000 sudah termasuk semuanya. Hosting dan domain gratis selamanya untuk paket ini.</p>
                        </div>
                        
                        <div class="bg-white rounded-xl p-4 md:p-6 shadow-lg">
                            <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-3">❓ Apakah website dapat saya edit sendiri?</h3>
                            <p class="text-sm md:text-base text-gray-600">Ya! Anda mendapat akses penuh untuk mengedit konten website kapan saja melalui dashboard yang mudah digunakan, tanpa perlu keahlian teknis.</p>
                        </div>
                        
                        <div class="bg-white rounded-xl p-4 md:p-6 shadow-lg">
                            <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-3">❓ Benarkah website siap dalam 1 jam?</h3>
                            <p class="text-sm md:text-base text-gray-600">Ya benar! Setelah Anda melengkapi data dan upload materi, AI kami akan generate website profesional dalam hitungan jam, bukan hari atau minggu.</p>
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
                
                <div class="max-w-3xl mx-auto bg-white/10 backdrop-blur-sm rounded-2xl p-4 md:p-8 mb-6 md:mb-8">
                    <div class="grid md:grid-cols-3 gap-4 md:gap-6 text-center mb-6 md:mb-8">
                        <div>
                            <div class="text-3xl md:text-4xl mb-2">⚡</div>
                            <div class="font-bold text-sm md:text-base">Proses Cepat</div>
                            <div class="text-xs md:text-sm opacity-80">Siap dalam 1 jam</div>
                        </div>
                        <div>
                            <div class="text-3xl md:text-4xl mb-2">💰</div>
                            <div class="font-bold text-sm md:text-base">Harga Terjangkau</div>
                            <div class="text-xs md:text-sm opacity-80">Hanya Rp 499.000</div>
                        </div>
                        <div>
                            <div class="text-3xl md:text-4xl mb-2">🏆</div>
                            <div class="font-bold text-sm md:text-base">Kualitas Premium</div>
                            <div class="text-xs md:text-sm opacity-80">Desain profesional</div>
                        </div>
                    </div>
                    
                    <Link :href="route('register')" class="bg-yellow-400 text-blue-900 font-bold text-lg md:text-2xl px-8 md:px-16 py-4 md:py-5 rounded-full hover:bg-yellow-300 transform hover:scale-105 transition-all duration-300 shadow-xl inline-block">
                        🎯 Ya, Saya Mau Website Rp 499rb!
                    </Link>
                </div>
                
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 md:p-6 max-w-2xl mx-auto">
                    <h3 class="text-xl md:text-2xl font-bold mb-3 md:mb-4">Masih Ada Pertanyaan?</h3>
                    <p class="mb-3 md:mb-4 text-sm md:text-base">Masih ada pertanyaan seputar jasa bikin website kami?</p>
                    <button @click="openWhatsApp" class="bg-green-500 text-white font-bold px-4 md:px-8 py-2 md:py-3 rounded-full hover:bg-green-600 transition-all duration-300 shadow-lg text-sm md:text-base">
                        📱 Hubungi Kami di WhatsApp: +62 811-935-0608
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
                        <p class="text-sm md:text-base text-gray-600 mb-3 md:mb-4">"Website saya jadi dalam hitungan jam! Benar-benar mudah dan hasilnya sangat profesional. Recommended banget untuk yang butuh website cepat."</p>
                        <div class="font-bold text-sm md:text-base text-gray-800">- Chandra Sahita, Owner Travel</div>
                    </div>
                    
                    <div class="bg-white p-4 md:p-6 rounded-xl shadow-lg">
                        <div class="flex mb-3 md:mb-4">
                            <span class="text-yellow-400 text-sm md:text-base">★★★★★</span>
                        </div>
                        <p class="text-sm md:text-base text-gray-600 mb-3 md:mb-4">"Awalnya ragu dengan harga yang murah, tapi ternyata kualitasnya luar biasa! Customer service juga sangat responsif. Proses instan dan mudah, thanks ya..."</p>
                        <div class="font-bold text-sm md:text-base text-gray-800">- Sari Dewi, Wedding Organizer</div>
                    </div>
                    
                    <div class="bg-white p-4 md:p-6 rounded-xl shadow-lg">
                        <div class="flex mb-3 md:mb-4">
                            <span class="text-yellow-400 text-sm md:text-base">★★★★★</span>
                        </div>
                        <p class="text-sm md:text-base text-gray-600 mb-3 md:mb-4">"Sebagai pemula yang tidak ngerti coding sama sekali, layanan ini sangat membantu. Website company profile saya langsung jadi dengan waktu instan."</p>
                        <div class="font-bold text-sm md:text-base text-gray-800">- Ahmad Rizky, Konsultan Bisnis</div>
                    </div>
                </div>
            </div>
        </section>

    </AppLayout>
</template>
