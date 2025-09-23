<template>
    <AppLayout>
        <Head :title="template.name" />

        <!-- Loading Component -->
        <PageLoader :show="loading" />

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="flex items-center justify-center mb-4">
                        <span class="text-sm font-medium bg-white bg-opacity-20 px-3 py-1 rounded-full">Step 1 of 3</span>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        {{ template.name }}
                    </h1>
                    <p class="text-xl opacity-90 max-w-2xl mx-auto">
                        {{ template.description || 'Professional website template ready to customize' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6" v-if="$page.props.flash.message">
            <Alert
                :type="$page.props.flash.type || 'success'"
                :message="$page.props.flash.message"
                @dismiss="$page.props.flash.message = null"
            />
        </div>

        <!-- Breadcrumb -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <Breadcrumb :breadcrumbs="breadcrumbs" />
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left Column - Template Details (2 columns) -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Template Preview Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden slide-in-left">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <EyeIcon class="w-5 h-5 mr-2" />
                                Template Preview
                            </h3>
                        </div>

                        <div class="p-6">
                            <div class="mb-6">
                                <div class="relative group">
                                    <img
                                        :src="template.preview_image || '/images/template-placeholder.jpg'"
                                        :alt="template.name"
                                        class="w-full h-80 object-cover rounded-lg shadow-md transition-transform duration-300 group-hover:scale-[1.02]"
                                        loading="lazy"
                                        style="opacity: 1;"
                                    >

                                    <!-- Overlay with preview button -->
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                                        <Link
                                            v-if="template.demo_url"
                                            :href="`/preview/${template.slug}`"
                                            target="_blank"
                                            class="opacity-0 group-hover:opacity-100 transform scale-95 group-hover:scale-100 transition-all duration-300 bg-white text-gray-800 px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl"
                                        >
                                            <ArrowTopRightOnSquareIcon class="w-5 h-5 inline mr-2" />
                                            View Live Preview
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <div v-if="template.demo_url" class="text-center">
                                <Link
                                    :href="`/templates/${template.slug}/preview`"
                                    target="_blank"
                                    class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white py-3 px-6 rounded-lg transition duration-200 font-medium"
                                >
                                    <ArrowTopRightOnSquareIcon class="w-5 h-5 mr-2" />
                                    Open Live Preview
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Template Information Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden slide-in-left">
                        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <InformationCircleIcon class="w-5 h-5 mr-2" />
                                About This Template
                            </h3>
                        </div>

                        <div class="p-6 space-y-6">
                            <div v-if="template.description">
                                <h4 class="font-semibold text-gray-900 mb-3">Description</h4>
                                <p class="text-gray-600 leading-relaxed">
                                    {{ template.description }}
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h5 class="font-semibold text-gray-900 mb-2">Category</h5>
                                    <span v-if="template.category" class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                                        {{ formatCategory(template.category) }}
                                    </span>
                                    <span v-else class="text-gray-500">Uncategorized</span>
                                </div>

                                <div>
                                    <h5 class="font-semibold text-gray-900 mb-2">Price</h5>
                                    <div class="text-2xl font-bold text-green-600">
                                        {{ template.price > 0 ? formatPrice(template.price) : 'FREE' }}
                                    </div>
                                </div>

                                <div>
                                    <h5 class="font-semibold text-gray-900 mb-2">Last Updated</h5>
                                    <p class="text-gray-600">{{ formatDate(template.created_at) }}</p>
                                </div>

                                <div>
                                    <h5 class="font-semibold text-gray-900 mb-2">Version</h5>
                                    <p class="text-gray-600">v2.1.0</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden slide-in-left">
                        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <SparklesIcon class="w-5 h-5 mr-2" />
                                Features & Specifications
                            </h3>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Features -->
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-4">What's Included</h4>
                                    <div class="space-y-3">
                                        <div
                                            v-for="(description, feature) in template.features"
                                            :key="feature"
                                            class="flex items-start"
                                        >
                                            <CheckCircleIcon class="h-5 w-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" />
                                            <div>
                                                <span class="font-medium text-gray-900">{{ feature }}</span>
                                                <p class="text-sm text-gray-600">{{ description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tech Specs -->
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-4">Technical Specifications</h4>
                                    <div class="space-y-3">
                                        <div
                                            v-for="(value, spec) in template.tech_specs"
                                            :key="spec"
                                            class="flex justify-between py-2 border-b border-gray-100 last:border-b-0"
                                        >
                                            <span class="text-gray-600">{{ spec }}:</span>
                                            <span class="font-medium text-gray-900">{{ value }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column - Order CTA (1 column) -->
                <div class="lg:col-span-1">
                    <div class="sticky top-8 slide-in-right">
                        <!-- Order Summary Card -->
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                                <h3 class="text-lg font-semibold text-white flex items-center">
                                    <ShoppingCartIcon class="w-5 h-5 mr-2" />
                                    Template Details
                                </h3>
                            </div>

                            <div class="p-6">
                                <!-- Template Summary -->
                                <div class="mb-6">
                                    <img
                                        :src="template.preview_image || '/images/template-placeholder.jpg'"
                                        :alt="template.name"
                                        class="w-full h-32 object-cover rounded-lg mb-4"
                                        style="opacity: 1;"
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

                                <!-- Pricing -->
                                <div class="mb-6 text-center">
                                    <div class="text-3xl font-bold text-green-600 mb-2">
                                        {{ template.price > 0 ? formatPrice(template.price) : 'FREE' }}
                                    </div>
                                    <!-- <p class="text-gray-500 text-sm">One-time payment</p> -->
                                </div>

                                <!-- Order CTA -->
                                <div class="space-y-4">
                                    <Link
                                        v-if="$page.props.auth.user"
                                        :href="`/templates/${template.slug}/checkout`"
                                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-center block"
                                        :class="{ 'animate-pulse': orderLoading }"
                                        @click="orderLoading = true"
                                    >
                                        <ShoppingCartIcon class="w-5 h-5 inline mr-2" />
                                        {{ template.price > 0 ? 'Order Sekarang' : 'Dapatkan Gratis' }}
                                    </Link>

                                    <div v-else class="text-center space-y-4">
                                        <p class="text-gray-600 text-sm">Please login to order this template</p>

                                        <div class="space-y-2">
                                            <Link
                                                :href="`/login?redirect=${$page.url}`"
                                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg transition duration-200 font-medium block text-center"
                                            >
                                                Login to Continue
                                            </Link>

                                            <p class="text-xs text-gray-500">
                                                Don't have an account?
                                                <Link :href="`/register?redirect=${$page.url}`" class="text-blue-600 hover:text-blue-500">
                                                    Sign up here
                                                </Link>
                                            </p>
                                        </div>
                                    </div>

                                    <p class="text-center text-xs text-gray-500">
                                        {{ template.price > 0 ? '' : 'Create your website immediately' }}
                                    </p>
                                </div>

                                <!-- Quick Stats -->
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <div class="grid grid-cols-2 gap-4 text-center">
                                        <div>
                                            <div class="text-lg font-bold text-gray-900">6 Hours</div>
                                            <div class="text-xs text-gray-500">Activation Time</div>
                                        </div>
                                        <div>
                                            <div class="text-lg font-bold text-gray-900">24/7</div>
                                            <div class="text-xs text-gray-500">Support</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Trust Indicators -->
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <div class="space-y-3">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <ShieldCheckIcon class="w-4 h-4 text-green-500 mr-2" />
                                            SSL Certificate Included
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <ClockIcon class="w-4 h-4 text-blue-500 mr-2" />
                                            1 Year Hosting Included
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <ChatBubbleLeftRightIcon class="w-4 h-4 text-purple-500 mr-2" />
                                            Priority Support Access
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Badge -->
                        <!-- <div class="mt-6 text-center">
                            <div class="inline-flex items-center px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
                                <ShieldCheckIcon class="w-5 h-5 text-green-500 mr-2" />
                                <span class="text-sm text-green-700 font-medium">Secure & Trusted</span>
                            </div>
                        </div> -->

                        <!-- Money Back Guarantee -->
                        <div v-if="template.price > 0" class="mt-4 text-center">
                            <!-- <div class="inline-flex items-center px-3 py-2 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <BoltIcon class="w-4 h-4 text-yellow-500 mr-2" />
                                <span class="text-xs text-yellow-700 font-medium">30-Day Money Back Guarantee</span>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Templates Section -->
            <div v-if="relatedTemplates.length > 0" class="mt-16 slide-in-bottom">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <RectangleGroupIcon class="w-6 h-6 mr-2" />
                            Related Templates
                        </h2>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div
                                v-for="relatedTemplate in relatedTemplates"
                                :key="relatedTemplate.id"
                                class="bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100 group"
                            >
                                <div class="relative">
                                    <img
                                        :src="relatedTemplate.preview_image || '/images/template-placeholder.jpg'"
                                        :alt="relatedTemplate.name"
                                        class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy"
                                        style="opacity: 1;"
                                    >

                                    <div class="absolute top-3 right-3">
                                        <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full font-bold">
                                            {{ relatedTemplate.price > 0 ? formatPrice(relatedTemplate.price) : 'FREE' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <h3 class="font-bold text-sm text-gray-900 mb-2 line-clamp-1">
                                        {{ relatedTemplate.name }}
                                    </h3>

                                    <span v-if="relatedTemplate.category" class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mb-3">
                                        {{ formatCategory(relatedTemplate.category) }}
                                    </span>

                                    <div class="flex gap-2">
                                        <Link
                                            :href="`/templates/${relatedTemplate.slug}`"
                                            class="flex-1 bg-white hover:bg-gray-100 text-gray-700 text-center py-2 px-3 rounded-md transition duration-200 text-xs font-medium border"
                                        >
                                            View Details
                                        </Link>
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
import { ref, onMounted, onUnmounted } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Alert from '@/Components/Alert.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import PageLoader from '@/Components/PageLoader.vue'

// Icons
import {
    EyeIcon,
    InformationCircleIcon,
    SparklesIcon,
    CheckCircleIcon,
    ShoppingCartIcon,
    ArrowTopRightOnSquareIcon,
    ShieldCheckIcon,
    ClockIcon,
    ChatBubbleLeftRightIcon,
    BoltIcon,
    RectangleGroupIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    template: Object,
    relatedTemplates: Array,
    breadcrumbs: Array
})

const loading = ref(true)
const orderLoading = ref(false)

// Loading simulation
onMounted(() => {
    // Simulate API loading time
    setTimeout(() => {
        loading.value = false
    }, 1500)

    // Preload images
    preloadImages()
})

const preloadImages = () => {
    const images = [
        props.template.preview_image,
        ...props.relatedTemplates.map(t => t.preview_image)
    ].filter(Boolean)

    images.forEach(src => {
        const img = new Image()
        img.onload = () => {
            // Image loaded successfully
        }
        img.onerror = () => {
            // Image failed to load, use fallback
        }
        img.src = src
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

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const truncateText = (text, length) => {
    if (!text) return ''
    return text.length > length ? text.substring(0, length) + '...' : text
}

// Smooth scroll to top when component mounts
onMounted(() => {
    window.scrollTo({ top: 0, behavior: 'smooth' })
})

// Handle page leave loading
onUnmounted(() => {
    // Clean up any ongoing requests
})
</script>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom animations */
@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInBottom {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.v-motion-slide-visible-once-left,
.slide-in-left {
    animation: slideInLeft 0.6s ease-out;
}

.v-motion-slide-visible-once-right,
.slide-in-right {
    animation: slideInRight 0.6s ease-out;
}

.v-motion-slide-visible-once-bottom,
.slide-in-bottom {
    animation: slideInBottom 0.6s ease-out;
}
</style>
