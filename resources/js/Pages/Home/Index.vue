<template>
    <AppLayout>
        <Head>
            <title>{{ appName }}</title>
        </Head>

        <!-- Loading Component -->
        <PageLoader :show="loading" />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Hero Section -->
    <section class="text-center py-16 md:py-24">
      <h1 class="text-4xl md:text-6xl font-bold mb-4">{{ appName }}</h1>
      <h2 class="text-3xl md:text-5xl font-bold mb-6">Jasa Pembuatan Website Profesional</h2>
      <p class="max-w-3xl mx-auto text-subtext-light dark:text-subtext-dark mb-8">
        Dapatkan website professional dengan harga terjangkau yang mudah dikelola untuk membantu bisnis Anda berkembang lebih pesat di dunia digital.
      </p>
      <div class="relative inline-block">
        <input
          v-model="domain"
          class="border border-border-light dark:border-border-dark rounded-full py-3 pl-6 pr-40 w-full md:w-auto bg-background-light dark:bg-background-dark"
          placeholder="www.domainanda.com"
          type="text"
        />
        <button
          @click="checkDomain"
          class="absolute right-1 top-1 bottom-1 bg-primary text-white px-6 rounded-full font-semibold"
        >
          Cek Domain
        </button>
      </div>
      <div class="bg-primary text-white p-8 rounded-lg max-w-4xl mx-auto mt-[-4rem] relative z-10 shadow-lg">
        <div class="flex flex-col md:flex-row justify-between items-center">
          <div class="mb-4 md:mb-0">
            <p class="text-lg">Bangun website Anda sekarang!</p>
            <p class="text-sm opacity-80">1.257+ Website telah dibuat</p>
          </div>
          <Link
            :href="route('templates.index')"
            class="bg-white text-primary px-8 py-3 rounded-lg font-semibold"
          >
            Lihat Tema Website
          </Link>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 md:py-24">
      <h2 class="text-3xl md:text-4xl font-bold text-center mb-4">
        Mengapa pilih Jasa Pembuatan Website di {{ appName }}?
      </h2>
      <p class="text-center max-w-3xl mx-auto text-subtext-light dark:text-subtext-dark mb-12">
        Tak hanya melayani jasa pembuatan website, kami juga memberikan layanan modifikasi website agar sesuai dengan kebutuhan bisnis Anda.
      </p>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div v-for="feature in features" :key="feature.title" class="text-center p-6 border border-border-light dark:border-border-dark rounded-lg">
          <component :is="feature.icon" class="h-12 w-12 text-primary mx-auto mb-4" />
          <h3 class="font-semibold mb-2">{{ feature.title }}</h3>
          <p class="text-sm text-subtext-light dark:text-subtext-dark">{{ feature.description }}</p>
        </div>
      </div>
    </section>

    <!-- Templates Section -->
    <section class="py-16 md:py-24 bg-gray-50 dark:bg-gray-800 rounded-lg">
      <h2 class="text-3xl md:text-4xl font-bold text-center mb-4">
        Template Website Terbaru
      </h2>
      <p class="text-center max-w-3xl mx-auto text-subtext-light dark:text-subtext-dark mb-12">
        Pilih dari berbagai template website profesional kami yang siap untuk dikustomisasi sesuai kebutuhan bisnis Anda.
      </p>
      <div class="flex justify-center mb-8">
        <span class="bg-primary text-white px-4 py-2 rounded-full text-sm">Rilis Terbaru</span>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
        <div v-for="plan in plans" :key="plan.name" 
          :class="['p-8 rounded-lg bg-background-light dark:bg-background-dark', 
            plan.popular ? 'border-2 border-primary relative' : 'border border-border-light dark:border-border-dark']">
          <span v-if="plan.popular" class="absolute top-0 right-4 bg-primary text-white text-xs px-3 py-1 rounded-b-md">
            PALING LARIS
          </span>
          <h3 class="font-bold text-lg mb-2">{{ plan.name }}</h3>
          <p class="text-sm text-subtext-light dark:text-subtext-dark mb-4">{{ plan.description }}</p>
          <p :class="['text-3xl font-bold mb-1', plan.popular ? 'text-primary' : '']">
            {{ plan.price }}
          </p>
          <p class="text-subtext-light dark:text-subtext-dark text-sm line-through mb-4">
            {{ plan.originalPrice }}
          </p>
          <ul class="space-y-3 text-sm mb-8">
            <li v-for="feature in plan.features" :key="feature" class="flex items-center">
              <CheckCircleIcon class="h-5 w-5 text-green-500 shrink-0 mr-2" />
              {{ feature }}
            </li>
          </ul>
          <Link 
            :href="route('templates.show', { slug: plan.slug })"
            :class="['w-full py-3 rounded-lg font-semibold text-center block', 
              plan.popular ? 'bg-primary' : 'bg-orange-500',
              'text-white hover:opacity-90 transition-opacity']"
          >
            Lihat Template
          </Link>
        </div>
      </div>
      <div class="mt-12 text-center">
        <Link
          :href="route('templates.index')"
          class="inline-block bg-orange-500 text-white px-8 py-4 rounded-lg font-bold text-lg hover:opacity-90 transition-opacity"
        >
          Lihat Semua Template<br><small class="font-normal">(12+ Template Tersedia)</small>
        </Link>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 md:py-24">
      <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">
        Pertanyaan Seputar {{ appName }}
      </h2>
      <div class="max-w-3xl mx-auto space-y-4">
        <div v-for="(faq, index) in faqs" :key="index"
          class="border border-border-light dark:border-border-dark rounded-lg">
          <button
            @click="toggleFaq(index)"
            class="w-full flex justify-between items-center p-5 text-left font-semibold"
          >
            <span>{{ faq.question }}</span>
            <component 
              :is="activeFaq === index ? ChevronUpIcon : ChevronDownIcon"
              class="h-5 w-5 text-gray-500"
            />
          </button>
          <div v-show="activeFaq === index" class="p-5 pt-0">
            {{ faq.answer }}
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section class="bg-primary text-white text-center p-12 my-16 md:my-24 rounded-lg">
      <h2 class="text-3xl font-bold mb-2">Butuh Bantuan?</h2>
      <p class="mb-6">Jangan ragu! Tim kami siap membantu Anda 24 jam.</p>
      <button @click="contactUs" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold">
        Hubungi Kami
      </button>
    </section>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PageLoader from '@/Components/PageLoader.vue'
import {
  RocketLaunchIcon,
  GlobeAltIcon,
  ShieldCheckIcon,
  PaintBrushIcon,
  CheckCircleIcon,
  ChevronUpIcon,
  ChevronDownIcon
} from '@heroicons/vue/24/outline'

const appName = import.meta.env.VITE_APP_NAME
const loading = ref(true)

const domain = ref('')
const activeFaq = ref(null)

const features = [
  {
    icon: RocketLaunchIcon,
    title: 'PROSES CEPAT & MUDAH',
    description: 'Website Anda akan online dalam hitungan jam.'
  },
  {
    icon: GlobeAltIcon,
    title: 'DOMAIN & HOSTING GRATIS',
    description: 'Gratis domain .com dan .web.id, serta gratis hosting selamanya.'
  },
  {
    icon: ShieldCheckIcon,
    title: 'FITUR LENGKAP & KEAMANAN TERJAMIN',
    description: 'Dilengkapi dengan fitur-fitur canggih dan keamanan SSL.'
  },
  {
    icon: PaintBrushIcon,
    title: 'BANYAK PILIHAN DESAIN TERBARU',
    description: 'Tersedia banyak pilihan desain website yang modern.'
  }
]

const plans = [
  {
    name: 'Restaurant',
    slug: 'restaurant',
    description: 'Template Website Restoran Modern',
    price: 'Rp 2.000.000',
    originalPrice: 'Rp 2.999.000',
    features: [
      'Sistem Pemesanan Online',
      'Menu Digital Interaktif',
      'Integrasi dengan Gofood/Grabfood',
      'Gallery Foto Menu'
    ],
    popular: false
  },
  {
    name: 'Company Profile',
    slug: 'corporate',
    description: 'Template Website Company Profile',
    price: 'Rp 1.950.000',
    originalPrice: 'Rp 3.999.000',
    features: [
      'Desain Premium & Profesional',
      'Halaman Blog Terintegrasi',
      'Form Kontak & Career',
      'Portfolio & Testimonial'
    ],
    popular: true
  },
  {
    name: 'E-Commerce',
    slug: 'e-commerce',
    description: 'Template Toko Online Lengkap',
    price: 'Rp 5.250.000',
    originalPrice: 'Rp 7.999.000',
    features: [
      'Sistem Cart & Checkout',
      'Payment Gateway',
      'Manajemen Produk',
      'Tracking Order'
    ],
    popular: false
  }
]

const faqs = [
  {
    question: 'Mengapa saya harus memiliki website?',
    answer: 'Website merupakan investasi penting untuk meningkatkan visibilitas dan kredibilitas bisnis Anda di era digital.'
  },
  {
    question: 'Berapa lama proses pembuatan website?',
    answer: 'Proses pembuatan website bisa selesai dalam hitungan jam, tergantung kompleksitas dan kustomisasi yang diinginkan.'
  },
  {
    question: 'Apakah saya bisa menambahkan desain saya sendiri?',
    answer: 'Ya, Anda dapat menyesuaikan desain sesuai kebutuhan dengan bantuan tim kami.'
  },
  {
    question: 'Apakah saya dapat mengelola website?',
    answer: 'Ya, Anda akan mendapatkan akses penuh untuk mengelola konten website Anda dengan mudah.'
  },
  {
    question: 'Apa syarat membuat website?',
    answer: 'Anda hanya perlu menyiapkan konten dan logo perusahaan, sisanya kami yang urus.'
  },
  {
    question: 'Apakah saya bisa mengontrol konten?',
    answer: 'Ya, Anda memiliki kendali penuh atas konten website melalui panel admin yang mudah digunakan.'
  }
]

const checkDomain = () => {
  window.location = route('templates.index')
}

const toggleFaq = (index) => {
  activeFaq.value = activeFaq.value === index ? null : index
}

const contactUs = () => {
  window.location = route('templates.index')
}

// Lifecycle
onMounted(() => {
    setTimeout(() => {
        loading.value = false
    }, 1000)
})
</script>