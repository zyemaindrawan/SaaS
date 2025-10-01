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
                            <Link href="/dashboard" class="text-gray-400 hover:text-gray-500 transition duration-200">
                                <HomeIcon class="w-5 h-5" />
                                <span class="sr-only">Dashboard</span>
                            </Link>
                        </li>
                        <li>
                            <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-300" />
                        </li>
                        <li>
                            <Link href="/templates" class="text-gray-400 hover:text-gray-500 transition duration-200"
                            >Templates</Link>
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
                                        :src="props.template.preview_image ? `/storage/${props.template.preview_image}` : '/default-avatar.png'"
                                        :alt="props.template.name"
                                        class="w-full h-48 object-cover rounded-lg mb-4"
                                        @error="handleImageError"
                                    >

                                    <div>
                                        <h4 class="font-bold text-xl text-gray-900 mb-2">
                                            {{ props.template.name }}
                                        </h4>

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
                                        :disabled="paymentProcessing || isPaymentExpired || !props.snapToken"
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

                                        <span v-if="!paymentProcessing && !isPaymentExpired && props.snapToken">
                                            Bayar - {{ formatPrice(payment.final_amount) }}
                                        </span>
                                        <span v-else-if="paymentProcessing">
                                            Processing Payment...
                                        </span>
                                        <span v-else-if="!props.snapToken">
                                            Generating Payment Token...
                                        </span>
                                        <span v-else>
                                            Payment Expired
                                        </span>
                                    </button>

                                    <!-- Debug Info (only in development) -->
                                    <div v-if="isDevelopment" class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-2 text-xs">
                                        <div class="text-yellow-800">
                                            <strong>Debug Info:</strong><br>
                                            Snap Token: {{ props.snapToken ? 'Available' : 'Not Available' }}<br>
                                            Payment Status: {{ payment.status }}<br>
                                            Expired: {{ isPaymentExpired ? 'Yes' : 'No' }}<br>
                                            Processing: {{ paymentProcessing ? 'Yes' : 'No' }}
                                        </div>
                                    </div>

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

                                    <!-- No Token Notice -->
                                    <div v-if="!props.snapToken && !isPaymentExpired" class="bg-orange-50 border border-orange-200 rounded-lg p-3 mt-2">
                                        <div class="flex items-center text-sm text-orange-800">
                                            <ExclamationTriangleIcon class="w-4 h-4 mr-2" />
                                            <span class="font-medium">Payment token is being generated. Please wait...</span>
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
                                                    {{ formatPrice(payment.amount) }}
                                                </span>
                                            </div>

                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-500">Platform Fee</span>
                                                <span class="text-gray-500">{{ formatPrice(payment.fee) }}</span>
                                            </div>

                                            <!-- Subtotal -->
                                            <div class="flex justify-between text-sm border-t border-gray-100 pt-2">
                                                <span class="text-gray-600 font-medium">Subtotal</span>
                                                <span class="font-medium">{{ formatPrice(payment.gross_amount) }}</span>
                                            </div>

                                            <!-- Voucher Discount -->
                                            <div v-if="payment.voucher_code && payment.discount > 0" class="flex justify-between text-sm">
                                                <span class="text-green-600">Voucher ({{ payment.voucher_code }})</span>
                                                <span class="text-green-600 font-medium">-{{ formatPrice(payment.discount) }}</span>
                                            </div>

                                            <hr class="border-gray-200">

                                            <!-- Final Amount -->
                                            <div class="flex justify-between items-center">
                                                <span class="text-lg font-bold text-gray-900">Final Amount</span>
                                                <div class="text-right">
                                                    <div v-if="payment.discount > 0" class="text-sm text-gray-500 line-through">
                                                        {{ formatPrice(payment.gross_amount) }}
                                                    </div>
                                                    <div class="text-xl font-bold text-green-600">
                                                        {{ formatPrice(payment.final_amount) }}
                                                    </div>
                                                    <div v-if="payment.discount > 0" class="text-xs text-green-600 font-medium mt-1">
                                                        💰 You save {{ formatPrice(payment.discount) }}
                                                    </div>
                                                </div>
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
                                </div>
                            </div>
                        </div>
                    </div>
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
    website_content: Object,
    midtrans_client_key: String
})

// Reactive state
const loading = ref(true)
const paymentProcessing = ref(false)
const timeUntilExpiry = ref('')
const midtransLoaded = ref(false)
let countdownInterval = null

// Computed properties
const isDevelopment = computed(() => {
    return process.env.NODE_ENV === 'development'
})

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

const handleImageError = (event) => {
    event.target.src = '/default-avatar.png'
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
        script.setAttribute('data-client-key', props.midtrans_client_key || 'SB-Mid-client-...')

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
        console.log('Payment blocked:', {
            processing: paymentProcessing.value,
            expired: isPaymentExpired.value,
            hasToken: !!props.snapToken
        })
        return
    }

    try {
        paymentProcessing.value = true
        console.log('Starting payment process with token:', props.snapToken)

        // Ensure Midtrans is loaded
        if (!midtransLoaded.value) {
            console.log('Loading Midtrans script...')
            await loadMidtransScript()
        }

        console.log('Midtrans loaded, processing payment...')

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
                paymentProcessing.value = false
                // Show error message but don't redirect immediately
                alert('Payment failed: ' + (result.status_message || 'Unknown error'))
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
        alert('Gagal memproses pembayaran: ' + error.message + '. Silakan coba lagi atau hubungi support.')
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
