<template>
    <div class="sticky top-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <ShoppingCartIcon class="w-5 h-5 mr-2" />
                    Order Summary
                </h3>
            </div>

            <div class="p-6">
                <!-- Status Indicator for Previewed -->
                <div v-if="shouldShowInvoiceButton" class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-sm">
                            <p class="font-medium text-blue-800">Payment Pending</p>
                            <p class="text-blue-600">Invoice #{{ payment?.code }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pricing Breakdown -->
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Template Price</span>
                        <span class="font-medium">
                            {{ pricing.subtotal > 0 ? formatPrice(pricing.subtotal) : 'FREE' }}
                        </span>
                    </div>

                    <div v-if="pricing.subtotal > 0" class="flex justify-between text-sm">
                        <span class="text-gray-500">Platform Fee</span>
                        <span class="text-gray-500">{{ formatPrice(pricing.platform_fee) }}</span>
                    </div>

                    <!-- Voucher Discount -->
                    <div v-if="localVoucherDiscount > 0 && voucherApplied" class="flex justify-between text-sm">
                        <span class="text-green-600">Voucher ({{ appliedVoucher }})</span>
                        <span class="text-green-600 font-medium">-{{ formatPrice(localVoucherDiscount) }}</span>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Final Amount -->
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">Final Amount</span>
                        <div class="text-right">
                            <div v-if="localVoucherDiscount > 0" class="text-sm text-gray-500 line-through">
                                {{ formatPrice(pricing.total) }}
                            </div>
                            <div class="text-xl font-bold" :class="finalTotal > 0 ? 'text-green-600' : 'text-blue-600'">
                                {{ finalTotal > 0 ? formatPrice(finalTotal) : 'FREE' }}
                            </div>
                            <div v-if="localVoucherDiscount > 0" class="text-xs text-green-600 font-medium mt-1">
                                💰 You save {{ formatPrice(localVoucherDiscount) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Voucher Section -->
                <div v-if="pricing.total > 0 && !shouldShowInvoiceButton" class="mt-6 pt-4 border-t border-gray-200">
                    <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 5a3 3 0 015-2.236A3 3 0 0114.83 6H16a2 2 0 110 4h-5V9a1 1 0 10-2 0v1H4a2 2 0 110-4h1.17C5.06 5.687 5 5.35 5 5zm4 1V5a1 1 0 10-1 1h1zm3 0a1 1 0 10-1-1v1h1z" clip-rule="evenodd" />
                            <path d="M9 11H3v5a2 2 0 002 2h4v-7zM11 18h4a2 2 0 002-2v-5h-6v7z" />
                        </svg>
                        Voucher Code
                    </h5>
                    
                    <div class="space-y-3">
                        <!-- Popular Voucher Codes -->
                        <div v-if="!voucherApplied" class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <div class="text-xs font-semibold text-blue-800 mb-2">💡 Pilihan Kode Voucher:</div>
                            <div class="flex flex-wrap gap-2">
                                <button
                                    v-for="popularCode in popularVoucherCodes"
                                    :key="popularCode.code"
                                    @click="applyPopularVoucher(popularCode.code)"
                                    class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded-full transition duration-200 font-medium"
                                    :disabled="applyingVoucher"
                                >
                                    {{ popularCode.code }}
                                </button>
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <input
                                type="text"
                                v-model="voucherCode"
                                placeholder="Enter voucher code"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                :disabled="voucherApplied || applyingVoucher"
                                @keyup.enter="applyVoucher"
                            >
                            <button
                                type="button"
                                @click="voucherApplied ? removeVoucher() : applyVoucher()"
                                :disabled="(!voucherCode.trim() && !voucherApplied) || applyingVoucher"
                                class="px-4 py-2 text-sm font-medium rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                :class="voucherApplied ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-blue-600 hover:bg-blue-700 text-white'"
                            >
                                <span v-if="applyingVoucher">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Applying...
                                </span>
                                <span v-else>
                                    {{ voucherApplied ? 'Remove' : 'Apply' }}
                                </span>
                            </button>
                        </div>

                        <!-- Voucher Success Message -->
                        <div v-if="voucherApplied" class="flex items-center text-sm text-green-600 bg-green-50 border border-green-200 rounded-lg p-2">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium">Voucher applied successfully!</span>
                        </div>

                        <!-- Voucher Error Message -->
                        <div v-if="voucherError" class="flex items-center text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg p-2">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium">{{ voucherError }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Button -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <button
                        type="button"
                        @click="handleButtonClick"
                        :disabled="loading"
                        :class="shouldShowInvoiceButton ? 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500' : 'bg-purple-600 hover:bg-purple-700 focus:ring-purple-500'"
                        class="w-full inline-flex justify-center items-center px-4 py-4 border border-transparent text-sm font-medium rounded-md shadow-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        
                        <svg v-if="shouldShowInvoiceButton && !loading" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        
                        {{ buttonText }}
                    </button>
                    <p class="text-xs text-gray-500 text-center mt-2">
                        {{ buttonSubtext }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { ShoppingCartIcon } from '@heroicons/vue/24/outline'
import axios from 'axios'

const props = defineProps({
    template: Object,
    pricing: Object,
    formData: Object,
    voucherDiscount: {
        type: Number,
        default: 0
    },
    loading: {
        type: Boolean,
        default: false
    },
    websiteStatus: {
        type: String,
        default: 'draft'
    },
    payment: {
        type: Object,
        default: null
    }
})

const emit = defineEmits(['voucherApplied', 'confirm-payment'])

// Voucher state
const voucherCode = ref('')
const voucherApplied = ref(false)
const voucherError = ref('')
const appliedVoucher = ref('')
const applyingVoucher = ref(false)
const localVoucherDiscount = ref(props.voucherDiscount || 0)

// Popular voucher codes for quick selection
const popularVoucherCodes = ref([
    { code: 'WELCOME10', discount: 10 },
    { code: 'SAVE20', discount: 20 },
    { code: 'PROMO15%', discount: 15 },
    { code: 'NEW15', discount: 10 }
])

const finalTotal = computed(() => {
    return Math.max(0, props.pricing.total - localVoucherDiscount.value)
})

const hasFormData = computed(() => {
    return props.formData && Object.keys(props.formData).length > 0
})

const shouldShowInvoiceButton = computed(() => {
    return props.websiteStatus === 'previewed' && props.payment && props.payment.code
})

const buttonText = computed(() => {
    if (shouldShowInvoiceButton.value) {
        return 'Lihat Invoice & Lanjutkan Pembayaran'
    }
    if (props.loading) {
        return 'Memproses...'
    }
    return finalTotal.value > 0 ? 'Buat Website & Bayar Sekarang' : 'Aktivasi Website Gratis'
})

const buttonSubtext = computed(() => {
    if (shouldShowInvoiceButton.value) {
        return 'Klik untuk melihat detail pembayaran'
    }
    return finalTotal.value > 0 ? 'Anda akan diarahkan ke halaman pembayaran' : 'Website akan langsung diaktifkan'
})

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(price)
}

const handleImageError = (event) => {
    event.target.src = '/default-avatar.png'
}

const applyVoucher = async () => {
    if (!voucherCode.value.trim() || applyingVoucher.value) return

    applyingVoucher.value = true
    voucherError.value = ''

    try {
        const response = await axios.post('/api/vouchers/validate', {
            code: voucherCode.value.trim(),
            subtotal: props.pricing.total
        })

        if (response.data.valid) {
            localVoucherDiscount.value = response.data.discount || 0
            appliedVoucher.value = voucherCode.value.trim()
            voucherApplied.value = true
            
            // Emit the voucher data to parent
            emit('voucherApplied', {
                code: appliedVoucher.value,
                discount: localVoucherDiscount.value
            })
        } else {
            voucherError.value = response.data.message || 'Invalid voucher code'
        }
    } catch (error) {
        voucherError.value = error.response?.data?.message || 'Failed to validate voucher'
    } finally {
        applyingVoucher.value = false
    }
}

const applyPopularVoucher = async (code) => {
    voucherCode.value = code
    await applyVoucher()
}

const removeVoucher = () => {
    voucherCode.value = ''
    voucherApplied.value = false
    voucherError.value = ''
    appliedVoucher.value = ''
    localVoucherDiscount.value = 0
    
    // Emit empty voucher data to parent
    emit('voucherApplied', {
        code: '',
        discount: 0
    })
}

const handleButtonClick = () => {
    if (shouldShowInvoiceButton.value) {
        // Redirect to invoice page
        window.location.href = `/invoice/${props.payment.code}`
    } else {
        // Emit confirm payment event
        emit('confirm-payment')
    }
}

// Watch for external voucher discount changes
watch(() => props.voucherDiscount, (newValue) => {
    localVoucherDiscount.value = newValue || 0
})
</script>