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
                <!-- Template Preview -->
                <div class="mb-6">
                    <img
                        :src="template.preview_image ? `/storage/${template.preview_image}` : '/default-avatar.png'"
                        :alt="template.name"
                        @error="handleImageError"
                        class="w-full h-32 object-cover rounded-lg mb-4"
                    >

                    <div>
                        <h4 class="font-bold text-lg text-gray-900 mb-1">
                            {{ template.name }}
                        </h4>

                        <p v-if="template.description" class="text-sm text-gray-600">
                            {{ truncateText(template.description, 100) }}
                        </p>
                    </div>
                </div>

                <!-- Pricing Breakdown -->
                <div class="space-y-4 border-t border-gray-200 pt-4">
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

                    <!-- Subtotal -->
                    <div v-if="pricing.subtotal > 0" class="flex justify-between text-sm border-t border-gray-100 pt-2">
                        <span class="text-gray-600 font-medium">Subtotal</span>
                        <span class="font-medium">{{ formatPrice(pricing.total) }}</span>
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

                <!-- Form Preview -->
                <div v-if="hasFormData" class="mt-6 pt-4 border-t border-gray-200">
                    <div class="space-y-2 text-sm">
                        <div v-if="formData.company_name" class="flex justify-between">
                            <span class="text-gray-500">Company:</span>
                            <span class="font-medium">{{ formData.company_name }}</span>
                        </div>
                        <div v-if="formData.subdomain" class="flex justify-between">
                            <span class="text-gray-500">Website:</span>
                            <span class="font-medium text-blue-600">{{ formData.subdomain }}.oursite.com</span>
                        </div>
                    </div>
                </div>

                <!-- Voucher Section -->
                <div v-if="pricing.total > 0" class="mt-6 pt-4 border-t border-gray-200">
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

                <!-- Submit Button -->
                <div class="mt-6 pt-4">
                    <button
                        type="submit"
                        :disabled="submitting"
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed"
                        :class="{ 'animate-pulse': submitting }"
                        @click="$emit('submit')"
                    >
                        <span v-if="submitting">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                        <span v-else>
                            {{ finalTotal > 0 ? 'Lanjutkan Pembayaran' : 'Buat Website Gratis' }}
                        </span>
                    </button>

                    <p class="text-center text-xs text-gray-500 mt-2">
                        {{ finalTotal > 0 ? 'Anda akan diarahkan ke halaman pembayaran' : 'Your website will be created immediately' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Security Badge -->
        <!-- <div class="mt-6 text-center">
            <div class="inline-flex items-center px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
                <ShieldCheckIcon class="w-5 h-5 text-green-500 mr-2" />
                <span class="text-sm text-green-700 font-medium">SSL Secured</span>
            </div>
        </div> -->
    </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { ShoppingCartIcon, ShieldCheckIcon } from '@heroicons/vue/24/outline'
import axios from 'axios'

const props = defineProps({
    template: Object,
    pricing: Object,
    formData: Object,
    submitting: Boolean,
    voucherDiscount: {
        type: Number,
        default: 0
    }
})

const emit = defineEmits(['submit', 'voucherApplied'])

// Voucher state
const voucherCode = ref('')
const voucherApplied = ref(false)
const voucherError = ref('')
const appliedVoucher = ref('')
const applyingVoucher = ref(false)
const localVoucherDiscount = ref(props.voucherDiscount || 0)

// Popular voucher codes
const popularVoucherCodes = ref([
    { code: 'WELCOME10', description: '10% off for new users' },
    { code: 'SAVE20', description: '20% discount' },
    { code: 'PROMO15', description: '15% off' },
    { code: 'NEWBIE', description: 'Special discount for beginners' }
])

const hasFormData = computed(() => {
    return props.formData.company_name || props.formData.subdomain
})

// Calculate final total after voucher discount
const finalTotal = computed(() => {
    const baseTotal = props.pricing.total || 0
    return Math.max(0, baseTotal - localVoucherDiscount.value)
})

// Apply popular voucher function
const applyPopularVoucher = (code) => {
    voucherCode.value = code
    applyVoucher()
}

// Apply voucher function using database API
const applyVoucher = async () => {
    const code = voucherCode.value.trim().toUpperCase()

    if (!code) {
        voucherError.value = 'Please enter a voucher code'
        return
    }

    applyingVoucher.value = true
    voucherError.value = ''

    try {
        const response = await axios.post('/api/vouchers/check', {
            code: code,
            subtotal: props.pricing.subtotal || 0
        })

        if (response.data.valid) {
            voucherApplied.value = true
            appliedVoucher.value = code
            localVoucherDiscount.value = response.data.discount

            // Emit voucher applied event to parent
            emit('voucherApplied', {
                code: code,
                discount: response.data.discount,
                finalTotal: Math.max(0, props.pricing.total - response.data.discount)
            })
        } else {
            voucherError.value = response.data.message || 'Invalid voucher code'
            voucherApplied.value = false
            localVoucherDiscount.value = 0
            appliedVoucher.value = ''
        }
    } catch (error) {
        //console.error('Voucher validation error:', error)

        // Fallback to static vouchers if API fails
        const validVouchers = {
            // 'PROMO15': {
            //     discount: 15,
            //     type: 'percentage'
            // },
            // 'WELCOME10': {
            //     discount: 10,
            //     type: 'percentage'
            // },
            // 'SAVE20': {
            //     discount: 20,
            //     type: 'percentage'
            // },
            // 'NEWBIE': {
            //     discount: 5,
            //     type: 'percentage'
            // }
        };
        
        if (validVouchers[code]) {
            const voucher = validVouchers[code]
            const subtotal = props.pricing.subtotal || 0

            if (voucher.type === 'percentage') {
                localVoucherDiscount.value = Math.round(subtotal * (voucher.discount / 100))
            } else if (voucher.type === 'fixed') {
                localVoucherDiscount.value = voucher.discount
            }

            voucherApplied.value = true
            appliedVoucher.value = code
            voucherError.value = ''

            // Emit voucher applied event to parent
            emit('voucherApplied', {
                code: code,
                discount: localVoucherDiscount.value,
                finalTotal: Math.max(0, props.pricing.total - localVoucherDiscount.value)
            })
        } else {
            voucherError.value = error.response?.data?.message || 'Failed to validate voucher. Please try again.'
            voucherApplied.value = false
            localVoucherDiscount.value = 0
            appliedVoucher.value = ''
        }
    } finally {
        applyingVoucher.value = false
    }
}

// Remove voucher function
const removeVoucher = () => {
    voucherCode.value = ''
    voucherApplied.value = false
    voucherError.value = ''
    appliedVoucher.value = ''
    localVoucherDiscount.value = 0

    // Emit voucher removed event to parent
    emit('voucherApplied', {
        code: '',
        discount: 0,
        finalTotal: props.pricing.total
    })
}

const formatCategory = (category) => {
    if (!category) return ''
    return category.split('-').map(word =>
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ')
}

const formatPrice = (price) => {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(price)
}

const truncateText = (text, length) => {
    if (!text) return ''
    return text.length > length ? text.substring(0, length) + '...' : text
}

const handleImageError = (event) => {
    event.target.src = '/default-avatar.png'
}
</script>
