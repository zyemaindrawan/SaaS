<template>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                Voucher Discount
            </h3>
            <button
                @click="toggleVoucherInput"
                type="button"
                class="text-sm text-blue-600 hover:text-blue-500 font-medium"
            >
                {{ showVoucherInput ? 'Cancel' : 'Apply Voucher' }}
            </button>
        </div>

        <!-- Voucher Input Form -->
        <div v-if="showVoucherInput" class="space-y-4">
            <div class="flex gap-3">
                <div class="flex-1">
                    <input
                        v-model="voucherCode"
                        type="text"
                        placeholder="Enter voucher code"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        :disabled="loading"
                        @keyup.enter="applyVoucher"
                    >
                </div>
                <button
                    @click="applyVoucher"
                    :disabled="loading || !voucherCode.trim()"
                    class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-6 py-3 rounded-lg transition duration-200 font-medium shadow-sm hover:shadow-md disabled:cursor-not-allowed"
                >
                    <span v-if="loading">
                        <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Applying...
                    </span>
                    <span v-else>Apply</span>
                </button>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="text-red-600 text-sm bg-red-50 p-3 rounded-lg border border-red-200">
                {{ error }}
            </div>

            <!-- Success Message -->
            <div v-if="appliedVoucher" class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-semibold text-green-800">
                            {{ appliedVoucher.name }}
                        </h4>
                        <p class="text-sm text-green-700 mt-1">
                            {{ appliedVoucher.description }}
                        </p>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Discount: Rp {{ formatPrice(appliedVoucher.discount) }}
                            </span>
                        </div>
                    </div>
                    <button
                        @click="removeVoucher"
                        class="text-green-600 hover:text-green-800 p-1"
                        title="Remove voucher"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Voucher Applied Summary (when not showing input) -->
        <div v-if="appliedVoucher && !showVoucherInput" class="bg-green-50 border border-green-200 rounded-lg p-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-green-800">
                        {{ appliedVoucher.name }} - Rp {{ formatPrice(appliedVoucher.discount) }} off
                    </span>
                </div>
                <button
                    @click="toggleVoucherInput"
                    class="text-green-600 hover:text-green-800 text-sm font-medium"
                >
                    Change
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineEmits, defineProps } from 'vue'
import axios from 'axios'

const props = defineProps({
    subtotal: {
        type: Number,
        required: true
    }
})

const emit = defineEmits(['voucher-applied', 'voucher-removed'])

const showVoucherInput = ref(false)
const voucherCode = ref('')
const loading = ref(false)
const error = ref('')
const appliedVoucher = ref(null)

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID').format(price)
}

const toggleVoucherInput = () => {
    showVoucherInput.value = !showVoucherInput.value
    if (!showVoucherInput.value) {
        voucherCode.value = ''
        error.value = ''
    }
}

const applyVoucher = async () => {
    if (!voucherCode.value.trim()) {
        error.value = 'Please enter a voucher code'
        return
    }

    loading.value = true
    error.value = ''

    try {
        const response = await axios.post('/vouchers/validate', {
            code: voucherCode.value.trim(),
            subtotal: props.subtotal
        })

        if (response.data.success) {
            appliedVoucher.value = response.data.voucher
            showVoucherInput.value = false
            voucherCode.value = ''
            emit('voucher-applied', response.data.voucher)
        } else {
            error.value = response.data.message
        }
    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to validate voucher'
    } finally {
        loading.value = false
    }
}

const removeVoucher = () => {
    appliedVoucher.value = null
    emit('voucher-removed')
}
</script>
