<template>
    <AppLayout>
        <Head :title="`Payment - ${props.template.name}`" />

        <!-- Loading Component -->
        <PageLoader :show="loading" />

        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
            <!-- Hero Section -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                <div class="container mx-auto px-4 py-12">
                    <div class="max-w-4xl mx-auto text-center">
                        <div class="flex items-center justify-center mb-4">
                            <span class="text-sm font-medium bg-white bg-opacity-20 px-3 py-1 rounded-full">Step 3 of 3</span>
                        </div>
                        
                        <h1 class="text-4xl md:text-5xl font-bold mb-4">
                            Selesaikan Pembayaran
                        </h1>
                        <p class="text-xl opacity-90 max-w-2xl mx-auto">
                            Satu langkah lagi untuk membuat website Anda online!
                        </p>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            <AlertMessages />

            <!-- Breadcrumb -->
            <div class="container mx-auto px-4 py-4">
                <nav class="flex max-w-4xl mx-auto" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <Link href="/templates" class="text-gray-400 hover:text-gray-500 transition duration-200">
                                <HomeIcon class="w-5 h-5" />
                                <span class="sr-only">Templates</span>
                            </Link>
                        </li>
                        <li>
                            <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-300" />
                        </li>
                        <li>
                            <Link 
                                :href="`/templates/${props.template.slug}`" 
                                class="text-gray-400 hover:text-gray-500 transition duration-200"
                            >
                                {{ props.template.name }}
                            </Link>
                        </li>
                        <li>
                            <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-300" />
                        </li>
                        <li class="text-gray-500 font-medium">
                            Payment
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="container mx-auto px-4 py-8">
                <div class="max-w-4xl mx-auto space-y-8">
                    
                    <!-- Order Summary Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <CreditCardIcon class="w-5 h-5 mr-2" />
                                Order Summary
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Template Preview -->
                                <div>
                                    <img 
                                        :src="props.template.preview_image || '/images/template-placeholder.jpg'" 
                                        :alt="props.template.name"
                                        class="w-full h-48 object-cover rounded-lg mb-4"
                                    >
                                    
                                    <div>
                                        <h4 class="font-bold text-xl text-gray-900 mb-2">
                                            {{ props.template.name }}
                                        </h4>
                                        
                                        <!-- <span 
                                            v-if="props.template.category"
                                            class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full mb-3"
                                        >
                                            {{ formatCategory(props.template.category) }}
                                        </span> -->
                                        
                                        <p 
                                            v-if="props.template.description"
                                            class="text-gray-600 leading-relaxed"
                                        >
                                            {{ props.template.description }}
                                        </p>
                                    </div>

                                    <!-- Payment Button -->
                                    <button 
                                        id="pay-button" 
                                        :disabled="paymentProcessing || isPaymentExpired"
                                        @click="processPayment"
                                        class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold mt-4 py-4 px-12 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-lg mb-4 w-full disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                        :class="{ 'animate-pulse': paymentProcessing }"
                                    >
                                        <ShieldCheckIcon v-if="!paymentProcessing" class="w-6 h-6 inline mr-3" />
                                        
                                        <svg 
                                            v-if="paymentProcessing"
                                            class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" 
                                            xmlns="http://www.w3.org/2000/svg" 
                                            fill="none" 
                                            viewBox="0 0 24 24"
                                        >
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        
                                        <span v-if="!paymentProcessing && !isPaymentExpired">
                                            Bayar - {{ formatPrice(payment.gross_amount) }}
                                        </span>
                                        <span v-else-if="paymentProcessing">
                                            Processing Payment...
                                        </span>
                                        <span v-else>
                                            Payment Expired
                                        </span>
                                    </button>

                                    <!-- Expiry Warning -->
                                    <div v-if="timeUntilExpiry && !isPaymentExpired" class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-2">
                                        <div class="flex items-center text-sm text-yellow-800">
                                            <ClockIcon class="w-4 h-4 mr-2" />
                                            <span class="font-medium">Payment expires in {{ timeUntilExpiry }}</span>
                                        </div>
                                    </div>

                                    <!-- Expired Notice -->
                                    <div v-if="isPaymentExpired" class="bg-red-50 border border-red-200 rounded-lg p-3 mt-2">
                                        <div class="flex items-center text-sm text-red-800">
                                            <ExclamationTriangleIcon class="w-4 h-4 mr-2" />
                                            <span class="font-medium">This payment has expired. Please contact support.</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Details -->
                                <div class="space-y-6">
                                    <!-- Pricing Breakdown -->
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-4">Price Breakdown</h5>
                                        <div class="space-y-3">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Template Price</span>
                                                <span class="font-medium">
                                                    {{ formatPrice(props.template.price) }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-500">Platform Fee</span>
                                                <span class="text-gray-500">{{ formatPrice(payment.fee) }}</span>
                                            </div>
                                            
                                            <hr class="border-gray-200">
                                            
                                            <div class="flex justify-between text-xl font-bold">
                                                <span>Total Amount</span>
                                                <span class="text-green-600">{{ formatPrice(payment.gross_amount) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment Details -->
                                    <div class="bg-blue-50 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-3">Payment Details</h5>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Payment Code:</span>
                                                <div class="flex items-center">
                                                    <span class="font-mono font-medium mr-2">{{ payment.code }}</span>
                                                    <CopyButton :text="payment.code" size="sm" />
                                                </div>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Website Address:</span>
                                                <span class="font-medium text-blue-600">{{ props.website_content.subdomain }}.oursite.com</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Payment Expires:</span>
                                                <span class="font-medium text-orange-600">{{ formatDateTime(payment.expired_at) }} WIB</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Status:</span>
                                                <StatusBadge :status="payment.status" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment Methods Info -->
                                    <!-- <div class="bg-indigo-50 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-3">Available Payment Methods</h5>
                                        <div class="grid grid-cols-4 gap-2">
                                            <div v-for="method in paymentMethods" :key="method.name" class="text-center">
                                                <div class="w-8 h-8 mx-auto mb-1 rounded" :style="{ backgroundColor: method.color }">
                                                    <span class="text-white text-xs font-bold leading-8">{{ method.short }}</span>
                                                </div>
                                                <span class="text-xs text-gray-600">{{ method.name }}</span>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Support Contact -->
                    <!-- <div class="bg-gray-50 rounded-xl p-6 text-center">
                        <div class="max-w-2xl mx-auto">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Butuh Bantuan?</h3>
                            <p class="text-gray-600 mb-4">
                                Tim support kami tersedia 24/7 untuk membantu Anda dengan pertanyaan apapun.
                            </p>
                            <div class="flex justify-center space-x-4">
                                <a href="mailto:support@saasbuilder.com" class="text-blue-600 hover:text-blue-500 font-medium text-sm transition duration-200">
                                    <EnvelopeIcon class="w-4 h-4 inline mr-1" />
                                    Email Support
                                </a>
                                <a href="tel:+6281234567890" class="text-blue-600 hover:text-blue-500 font-medium text-sm transition duration-200">
                                    <PhoneIcon class="w-4 h-4 inline mr-1" />
                                    Telepon
                                </a>
                                <a href="https://wa.me/6281234567890" target="_blank" class="text-green-600 hover:text-green-500 font-medium text-sm transition duration-200">
                                    <ChatBubbleLeftRightIcon class="w-4 h-4 inline mr-1" />
                                    WhatsApp
                                </a>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PageLoader from '@/Components/PageLoader.vue'
import AlertMessages from '@/Components/AlertMessages.vue'
import StatusBadge from '@/Components/StatusBadge.vue'
import CopyButton from '@/Components/CopyButton.vue'

// Icons
import {
    HomeIcon,
    ChevronRightIcon,
    CreditCardIcon,
    ShieldCheckIcon,
    ClockIcon,
    ExclamationTriangleIcon,
    EnvelopeIcon,
    PhoneIcon,
    ChatBubbleLeftRightIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    payment: Object,
    snapToken: String,
    template: Object,
    website_content: Object
})

// Reactive state
const loading = ref(true)
const paymentProcessing = ref(false)
const timeUntilExpiry = ref('')
const midtransLoaded = ref(false)
let countdownInterval = null

// Payment methods for display
const paymentMethods = ref([
    { name: 'Credit Card', short: 'CC', color: '#1f2937' },
    { name: 'Bank Transfer', short: 'BT', color: '#059669' },
    { name: 'GoPay', short: 'GP', color: '#00AA13' },
    { name: 'OVO', short: 'OVO', color: '#4C3BCF' },
    { name: 'DANA', short: 'DN', color: '#118EEA' },
    { name: 'ShopeePay', short: 'SP', color: '#EE4D2D' },
    { name: 'LinkAja', short: 'LA', color: '#E11E25' },
    { name: 'BCA VA', short: 'BCA', color: '#0066CC' }
])

// Computed properties
const isPaymentExpired = computed(() => {
    return new Date() > new Date(props.payment.expired_at)
})

// Methods
const formatPrice = (price) => {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(price)
}

const formatDateTime = (datetime) => {
    return new Date(datetime).toLocaleString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const formatCategory = (category) => {
    if (!category) return ''
    return category.split('-').map(word => 
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ')
}

const updateCountdown = () => {
    if (isPaymentExpired.value) {
        timeUntilExpiry.value = ''
        return
    }

    const now = new Date()
    const expiry = new Date(props.payment.expired_at)
    const diff = expiry - now

    if (diff <= 0) {
        timeUntilExpiry.value = ''
        return
    }

    const hours = Math.floor(diff / (1000 * 60 * 60))
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))
    const seconds = Math.floor((diff % (1000 * 60)) / 1000)

    if (hours > 0) {
        timeUntilExpiry.value = `${hours}h ${minutes}m ${seconds}s`
    } else if (minutes > 0) {
        timeUntilExpiry.value = `${minutes}m ${seconds}s`
    } else {
        timeUntilExpiry.value = `${seconds}s`
    }
}

const loadMidtransScript = () => {
    return new Promise((resolve, reject) => {
        if (window.snap) {
            midtransLoaded.value = true
            resolve()
            return
        }

        const script = document.createElement('script')
        script.src = `https://app.sandbox.midtrans.com/snap/snap.js`
        script.setAttribute('data-client-key', import.meta.env.VITE_MIDTRANS_CLIENT_KEY)
        
        script.onload = () => {
            midtransLoaded.value = true
            resolve()
        }
        
        script.onerror = () => {
            reject(new Error('Failed to load Midtrans script'))
        }
        
        document.head.appendChild(script)
    })
}

const processPayment = async () => {
    if (paymentProcessing.value || isPaymentExpired.value || !props.snapToken) {
        return
    }

    try {
        paymentProcessing.value = true

        // Ensure Midtrans is loaded
        if (!midtransLoaded.value) {
            await loadMidtransScript()
        }

        // Process payment with Midtrans Snap
        window.snap.pay(props.snapToken, {
            onSuccess: function(result) {
                console.log('Payment success:', result)
                window.location.href = `/payment/${props.payment.code}/finish`
            },
            
            onPending: function(result) {
                console.log('Payment pending:', result)
                window.location.href = `/payment/${props.payment.code}/finish`
            },
            
            onError: function(result) {
                console.log('Payment error:', result)
                window.location.href = `/payment/${props.payment.code}/error`
            },
            
            onClose: function() {
                console.log('Payment popup closed')
                paymentProcessing.value = false
            }
        })

    } catch (error) {
        console.error('Payment processing error:', error)
        paymentProcessing.value = false
        
        // Show error message
        alert('Gagal memproses pembayaran. Silakan coba lagi atau hubungi support.')
    }
}

// Lifecycle hooks
onMounted(async () => {
    // Load Midtrans script
    try {
        await loadMidtransScript()
    } catch (error) {
        console.error('Failed to load Midtrans:', error)
    }

    // Start countdown
    updateCountdown()
    countdownInterval = setInterval(updateCountdown, 1000)

    // Hide loading
    setTimeout(() => {
        loading.value = false
    }, 800)
})

onUnmounted(() => {
    if (countdownInterval) {
        clearInterval(countdownInterval)
    }
})
</script>

<style scoped>
/* Custom animations for payment button */
@keyframes paymentPulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.02);
    }
}

.payment-ready {
    animation: paymentPulse 2s infinite;
}

/* Loading spinner animation */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Hover effects */
.payment-button:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

/* Disabled state */
.payment-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .payment-button {
        width: 100%;
        font-size: 1rem;
        padding: 1rem 2rem;
    }
}
</style>
