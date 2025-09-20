<template>
    <TransitionRoot as="template" :show="show">
        <Dialog as="div" class="relative z-50" @close="$emit('cancel')">
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            <!-- Header -->
                            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <DialogTitle as="h3" class="text-lg font-semibold text-white">
                                            Konfirmasi Order
                                        </DialogTitle>
                                    </div>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="px-6 py-4">
                                <div class="mb-6">
                                    <p class="text-sm text-gray-600 mb-4">
                                        Please review your order details:
                                    </p>

                                    <!-- Order Summary -->
                                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                        <div class="flex items-center mb-3">
                                            <img 
                                                :src="template.preview_image || '/images/template-placeholder.jpg'" 
                                                :alt="template.name"
                                                class="w-12 h-12 object-cover rounded-md mr-3"
                                            >
                                            <div>
                                                <h4 class="font-semibold text-gray-900">{{ template.name }}</h4>
                                                <p class="text-sm text-gray-600">{{ formatCategory(template.category) }}</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Configuration Details -->
                                        <div class="space-y-2 text-sm">
                                            <div v-if="formData.company_name" class="flex justify-between">
                                                <span class="text-gray-500">Company Name:</span>
                                                <span class="font-medium">{{ formData.company_name }}</span>
                                            </div>
                                            <div v-if="formData.subdomain" class="flex justify-between">
                                                <span class="text-gray-500">Website Address:</span>
                                                <span class="font-medium text-blue-600">{{ formData.subdomain }}.oursite.com</span>
                                            </div>
                                            <div v-if="formData.contact_email" class="flex justify-between">
                                                <span class="text-gray-500">Contact Email:</span>
                                                <span class="font-medium">{{ formData.contact_email }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pricing -->
                                    <div class="border-t border-gray-200 pt-4">
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Price:</span>
                                                <span class="font-medium">
                                                    {{ pricing.subtotal > 0 ? formatPrice(pricing.subtotal) : 'FREE' }}
                                                </span>
                                            </div>
                                            <div v-if="pricing.subtotal > 0" class="flex justify-between text-sm">
                                                <span class="text-gray-500">Platform Fee:</span>
                                                <span class="text-gray-500">{{ formatPrice(pricing.platform_fee) }}</span>
                                            </div>
                                            
                                            <!-- Voucher Discount -->
                                            <div v-if="formData.voucher_code && formData.voucher_discount > 0" class="flex justify-between text-sm">
                                                <span class="text-green-600">Discount ({{ formData.voucher_code }}):</span>
                                                <span class="text-green-600">-{{ formatPrice(formData.voucher_discount) }}</span>
                                            </div>
                                            
                                            <hr>
                                            <div class="flex justify-between text-lg font-bold">
                                                <span>Total Amount:</span>
                                                <span class="text-green-600">
                                                    {{ finalTotal > 0 ? formatPrice(finalTotal) : 'FREE' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Next Steps -->
                                    <!-- <div class="mt-6 bg-blue-50 rounded-lg p-4">
                                        <h5 class="font-semibold text-blue-900 mb-2">What happens next?</h5>
                                        <ul class="text-sm text-blue-800 space-y-1">
                                            <li v-if="pricing.total > 0">• You'll be redirected to secure payment</li>
                                            <li v-else>• Your free website will be created immediately</li>
                                            <li>• Website setup will begin (up to 6 hours)</li>
                                            <li>• You'll receive email confirmation when ready</li>
                                        </ul>
                                    </div> -->
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse">
                                <button
                                    type="button"
                                    :disabled="loading"
                                    class="inline-flex w-full justify-center rounded-md bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 sm:ml-3 sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed"
                                    :class="{ 'animate-pulse': loading }"
                                    @click="$emit('confirm')"
                                >
                                    <span v-if="loading">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Processing...
                                    </span>
                                    <span v-else>
                                        {{ finalTotal > 0 ? 'Pembayaran' : 'Create Website' }}
                                    </span>
                                </button>
                                <button
                                    type="button"
                                    :disabled="loading"
                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-3 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto disabled:opacity-50"
                                    @click="$emit('cancel')"
                                >
                                    Batalkan
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { computed } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    show: Boolean,
    loading: Boolean,
    template: Object,
    formData: Object,
    pricing: Object
})

defineEmits(['confirm', 'cancel'])

// Calculate final total after voucher discount
const finalTotal = computed(() => {
    const baseTotal = props.pricing.total || 0
    const discount = props.formData.voucher_discount || 0
    return Math.max(0, baseTotal - discount)
})

const formatCategory = (category) => {
    if (!category) return ''
    return category.split('-').map(word => 
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ')
}

const formatPrice = (price) => {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(price)
}
</script>
