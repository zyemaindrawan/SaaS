<template>
    <AppLayout title="Draft Details">
        <ConfirmationDialog
            :show="showConfirmDialog"
            :loading="form.processing"
            :template="template"
            :form-data="formDataWithVoucher"
            :pricing="finalPricing"
            @confirm="confirmPayment"
            @cancel="showConfirmDialog = false"
        />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ websiteContent.website_name }}</h1>
                                <p class="mt-1 text-sm text-gray-600">
                                    Draft created {{ websiteContent.created_at }}
                                </p>
                                <div class="mt-2 flex items-center space-x-2">
                                    <span 
                                        :class="statusClass(websiteContent.status)"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    >
                                        {{ statusText(websiteContent.status) }}
                                    </span>
                                    <span class="text-sm text-gray-500">•</span>
                                    <span class="text-sm text-gray-500">{{ template.name }}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <Link
                                    :href="route('dashboard')"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                >
                                    ← Dashboard
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Template Info -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Template Information</h3>
                                <div class="flex items-start space-x-4">
                                    <img 
                                        :src="resolveAssetPath(template.preview_image)" 
                                        :alt="template.name"
                                        class="w-20 h-20 object-cover rounded-lg flex-shrink-0"
                                    >
                                    <div class="flex-1">
                                        <h4 class="text-base font-medium text-gray-900">{{ template.name }}</h4>
                                        <p class="mt-1 text-sm text-gray-600">{{ template.description }}</p>
                                        <div class="mt-2 flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ template.category }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Website Content Preview -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">Content Summary</h3>
                                    <Link
                                        :href="route('preview.show', websiteContent.id)"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Pratinjau Website
                                    </Link>
                                </div>
                                
                                <div class="space-y-4">
                                    <!-- Company Info -->
                                    <div v-if="websiteContent.content_data.company_name">
                                        <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                                        <dd class="mt-1 flex items-center space-x-3">
                                            <span class="text-sm text-gray-900">{{ websiteContent.content_data.company_name }}</span>
                                            <img 
                                                v-if="websiteContent.content_data.company_logo"
                                                :src="resolveAssetPath(websiteContent.content_data.company_logo)" 
                                                :alt="websiteContent.content_data.company_name + ' logo'"
                                                class="h-8 w-8 object-contain rounded border border-gray-200"
                                            >
                                        </dd>
                                    </div>

                                    <!-- Hero Section -->
                                    <div v-if="websiteContent.content_data.hero_title">
                                        <dt class="text-sm font-medium text-gray-500">Hero Title</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ websiteContent.content_data.hero_title }}</dd>
                                    </div>

                                    <!-- Hero Background -->
                                    <div v-if="websiteContent.content_data.hero_background">
                                        <dt class="text-sm font-medium text-gray-500">Hero Background</dt>
                                        <dd class="mt-1">
                                            <img 
                                                :src="resolveAssetPath(websiteContent.content_data.hero_background)" 
                                                alt="Hero background"
                                                class="w-full h-24 object-cover rounded border border-gray-200"
                                            >
                                        </dd>
                                    </div>

                                    <!-- Products Count with Preview -->
                                    <div v-if="websiteContent.content_data.products">
                                        <dt class="text-sm font-medium text-gray-500">Products</dt>
                                        <dd class="mt-1">
                                            <div class="text-sm text-gray-900 mb-2">{{ websiteContent.content_data.products.length }} products configured</div>
                                            <div v-if="websiteContent.content_data.products.length > 0" class="flex space-x-2">
                                                <div 
                                                    v-for="(product, index) in websiteContent.content_data.products.slice(0, 3)" 
                                                    :key="index"
                                                    class="flex-shrink-0"
                                                >
                                                    <img 
                                                        v-if="product.image"
                                                        :src="resolveAssetPath(product.image)" 
                                                        :alt="product.name"
                                                        class="h-12 w-12 object-cover rounded border border-gray-200"
                                                    >
                                                    <div v-else class="h-12 w-12 bg-gray-100 rounded border border-gray-200 flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div v-if="websiteContent.content_data.products.length > 3" class="flex items-center">
                                                    <span class="text-xs text-gray-500">+{{ websiteContent.content_data.products.length - 3 }} more</span>
                                                </div>
                                            </div>
                                        </dd>
                                    </div>

                                    <!-- Testimonials Count with Preview -->
                                    <div v-if="websiteContent.content_data.testimonials">
                                        <dt class="text-sm font-medium text-gray-500">Testimonials</dt>
                                        <dd class="mt-1">
                                            <div class="text-sm text-gray-900 mb-2">{{ websiteContent.content_data.testimonials.length }} testimonials added</div>
                                            <div v-if="websiteContent.content_data.testimonials.length > 0" class="flex space-x-2">
                                                <div 
                                                    v-for="(testimonial, index) in websiteContent.content_data.testimonials.slice(0, 3)" 
                                                    :key="index"
                                                    class="flex-shrink-0"
                                                >
                                                    <img 
                                                        v-if="testimonial.photo"
                                                        :src="resolveAssetPath(testimonial.photo)" 
                                                        :alt="testimonial.name"
                                                        class="h-10 w-10 object-cover rounded-full border border-gray-200"
                                                    >
                                                    <div v-else class="h-10 w-10 bg-gray-100 rounded-full border border-gray-200 flex items-center justify-center">
                                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div v-if="websiteContent.content_data.testimonials.length > 3" class="flex items-center">
                                                    <span class="text-xs text-gray-500">+{{ websiteContent.content_data.testimonials.length - 3 }} more</span>
                                                </div>
                                            </div>
                                        </dd>
                                    </div>

                                    <!-- About Image -->
                                    <div v-if="websiteContent.content_data.about_image">
                                        <dt class="text-sm font-medium text-gray-500">About Image</dt>
                                        <dd class="mt-1">
                                            <img 
                                                :src="resolveAssetPath(websiteContent.content_data.about_image)" 
                                                alt="About section image"
                                                class="w-full h-24 object-cover rounded border border-gray-200"
                                            >
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Order Summary -->
                        <DraftOrderSummary
                            :template="template"
                            :pricing="pricing"
                            :form-data="{}"
                            :loading="form.processing"
                            :website-status="websiteContent.status"
                            :payment="payment"
                            @voucher-applied="handleVoucherApplied"
                            @confirm-payment="showConfirmation"
                        />

                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import DraftOrderSummary from '@/Components/DraftOrderSummary.vue'
import ConfirmationDialog from '@/Components/ConfirmationDialog.vue'

// Helper function for resolving asset paths
const resolveAssetPath = (path) => {
    if (!path) return null
    
    // If already a full URL, return as is
    if (path.startsWith('http://') || path.startsWith('https://')) {
        return path
    }
    
    // If starts with 'website-assets/', add '/storage/' prefix
    if (path.startsWith('website-assets/')) {
        return `/storage/${path}`
    }
    
    // If starts with 'storage/', return as is
    if (path.startsWith('storage/')) {
        return `/${path}`
    }
    
    // If starts with '/' already, return as is
    if (path.startsWith('/')) {
        return path
    }
    
    // Default: assume relative from storage
    return `/storage/${path}`
}

const props = defineProps({
    websiteContent: Object,
    template: Object,
    pricing: Object,
    user: Object,
    payment: Object,
    breadcrumbs: Array
})

const processing = ref(false)
const showConfirmDialog = ref(false)
const voucherDiscount = ref(0)
const appliedVoucherCode = ref('')

const form = useForm({})

// Computed untuk final pricing dengan voucher
const finalPricing = computed(() => {
    const total = props.pricing.total - voucherDiscount.value
    return {
        ...props.pricing,
        voucher_discount: voucherDiscount.value,
        voucher_code: appliedVoucherCode.value,
        total: Math.max(0, total)
    }
})

// Computed untuk form data dengan voucher info
const formDataWithVoucher = computed(() => {
    return {
        ...props.websiteContent.content_data,
        voucher_code: appliedVoucherCode.value,
        voucher_discount: voucherDiscount.value
    }
})

const showConfirmation = () => {
    showConfirmDialog.value = true
}

const confirmPayment = () => {
    showConfirmDialog.value = false
    form.post(`/drafts/${props.websiteContent.id}/confirm-payment`)
}

const handleVoucherApplied = (data) => {
    voucherDiscount.value = data.discount || 0
    appliedVoucherCode.value = data.code || ''
}

const statusClass = (status) => {
    const classes = {
        'draft': 'bg-yellow-100 text-yellow-800',
        'previewed': 'bg-blue-100 text-blue-800',
        'paid': 'bg-green-100 text-green-800',
        'active': 'bg-green-100 text-green-800'
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
}

const statusText = (status) => {
    const texts = {
        'draft': 'Draft',
        'previewed': 'Previewed',
        'paid': 'Paid',
        'active': 'Active'
    }
    return texts[status] || status
}
</script>