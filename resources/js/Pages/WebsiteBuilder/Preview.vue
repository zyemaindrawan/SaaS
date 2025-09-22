<template>
    <AppLayout>
        <Head :title="`Preview - ${websiteContent.website_name}`" />

        <!-- Loading Component -->
        <PageLoader :show="loading" />

        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
            <!-- Hero Section -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                <div class="container mx-auto px-4 py-12">
                    <div class="max-w-4xl mx-auto text-center">
                        <div class="flex items-center justify-center mb-4">
                            <span class="text-sm font-medium bg-white bg-opacity-20 px-3 py-1 rounded-full">Website Preview</span>
                        </div>

                        <h1 class="text-4xl md:text-5xl font-bold mb-4">
                            Website Preview
                        </h1>
                        <p class="text-xl opacity-90 max-w-2xl mx-auto">
                            Preview your website before publishing
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
                        <li class="text-gray-500 font-medium">
                            Preview
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="container mx-auto px-4 py-8">
                <div class="max-w-6xl mx-auto space-y-8">

                    <!-- Preview Controls -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <EyeIcon class="w-5 h-5 mr-2" />
                                Preview Controls
                            </h3>
                        </div>

                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center">
                                        <label for="device-select" class="text-sm font-medium text-gray-700 mr-2">
                                            Device:
                                        </label>
                                        <select
                                            id="device-select"
                                            v-model="selectedDevice"
                                            class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                        >
                                            <option value="desktop">Desktop</option>
                                            <option value="tablet">Tablet</option>
                                            <option value="mobile">Mobile</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center">
                                        <label for="zoom-select" class="text-sm font-medium text-gray-700 mr-2">
                                            Zoom:
                                        </label>
                                        <select
                                            id="zoom-select"
                                            v-model="zoomLevel"
                                            class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                        >
                                            <option value="0.5">50%</option>
                                            <option value="0.75">75%</option>
                                            <option value="1">100%</option>
                                            <option value="1.25">125%</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <button
                                        @click="refreshPreview"
                                        :disabled="refreshing"
                                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-4 py-2 rounded-lg transition duration-200 font-medium flex items-center"
                                    >
                                        <ArrowPathIcon v-if="refreshing" class="w-4 h-4 mr-2 animate-spin" />
                                        <ArrowPathIcon v-else class="w-4 h-4 mr-2" />
                                        {{ refreshing ? 'Refreshing...' : 'Refresh' }}
                                    </button>

                                    <Link
                                        :href="`/website-builder/${websiteContent.id}/edit`"
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200 font-medium"
                                    >
                                        Edit Content
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Frame -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <GlobeAltIcon class="w-5 h-5 mr-2" />
                                Website Preview
                            </h3>
                        </div>

                        <div class="p-6">
                            <!-- Device Frame -->
                            <div class="flex justify-center mb-6">
                                <div
                                    :class="getDeviceClasses()"
                                    class="bg-gray-100 rounded-lg border-4 border-gray-300 overflow-hidden shadow-lg"
                                >
                                    <!-- Browser Header -->
                                    <div class="bg-gray-200 px-4 py-2 flex items-center space-x-2 border-b">
                                        <div class="flex space-x-1">
                                            <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                                            <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                                            <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                                        </div>
                                        <div class="flex-1 text-center">
                                            <div class="bg-white px-3 py-1 rounded text-xs text-gray-600 font-mono">
                                                {{ getPreviewUrl() }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Preview Content -->
                                    <div
                                        :style="{ transform: `scale(${zoomLevel})`, transformOrigin: 'top center' }"
                                        class="overflow-auto"
                                        :class="getPreviewHeight()"
                                    >
                                        <div v-if="previewLoading" class="flex items-center justify-center h-64">
                                            <div class="text-center">
                                                <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <p class="text-gray-600">Loading preview...</p>
                                            </div>
                                        </div>

                                        <iframe
                                            v-if="!previewLoading"
                                            :src="getPreviewUrl()"
                                            class="w-full border-0"
                                            :class="getPreviewHeight()"
                                            @load="onPreviewLoad"
                                            frameborder="0"
                                        ></iframe>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Info -->
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-start space-x-3">
                                    <InformationCircleIcon class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" />
                                    <div class="text-sm text-blue-800">
                                        <p class="font-medium mb-1">Preview Information</p>
                                        <p>This is a preview of your website. Some features may not work exactly as they will in the live version. The preview will update automatically when you make changes to your content.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Website Details -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <DocumentTextIcon class="w-5 h-5 mr-2" />
                                Website Details
                            </h3>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-2">Basic Information</h5>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Website Name:</span>
                                                <span class="font-medium">{{ websiteContent.website_name }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Template:</span>
                                                <span class="font-medium">{{ template.name }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Status:</span>
                                                <StatusBadge :status="websiteContent.status" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-2">URLs</h5>
                                        <div class="space-y-2 text-sm">
                                            <div v-if="websiteContent.subdomain" class="flex justify-between">
                                                <span class="text-gray-600">Subdomain:</span>
                                                <div class="flex items-center">
                                                    <span class="font-medium text-blue-600">{{ websiteContent.subdomain }}.oursite.com</span>
                                                    <button
                                                        @click="copyToClipboard(`https://${websiteContent.subdomain}.oursite.com`)"
                                                        class="ml-2 text-blue-500 hover:text-blue-700"
                                                    >
                                                        <DocumentDuplicateIcon class="w-4 h-4" />
                                                    </button>
                                                </div>
                                            </div>
                                            <div v-if="websiteContent.custom_domain" class="flex justify-between">
                                                <span class="text-gray-600">Custom Domain:</span>
                                                <div class="flex items-center">
                                                    <span class="font-medium text-blue-600">{{ websiteContent.custom_domain }}</span>
                                                    <button
                                                        @click="copyToClipboard(`https://${websiteContent.custom_domain}`)"
                                                        class="ml-2 text-blue-500 hover:text-blue-700"
                                                    >
                                                        <DocumentDuplicateIcon class="w-4 h-4" />
                                                    </button>
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
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PageLoader from '@/Components/PageLoader.vue'
import AlertMessages from '@/Components/AlertMessages.vue'
import StatusBadge from '@/Components/StatusBadge.vue'

// Icons
import {
    HomeIcon,
    ChevronRightIcon,
    EyeIcon,
    GlobeAltIcon,
    DocumentTextIcon,
    ArrowPathIcon,
    InformationCircleIcon,
    DocumentDuplicateIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    websiteContent: Object,
    template: Object,
    previewData: Object
})

// Reactive state
const loading = ref(true)
const refreshing = ref(false)
const previewLoading = ref(true)
const selectedDevice = ref('desktop')
const zoomLevel = ref(1)

// Computed properties
const getDeviceClasses = () => {
    const baseClasses = 'transition-all duration-300'

    switch (selectedDevice.value) {
        case 'mobile':
            return `${baseClasses} w-80`
        case 'tablet':
            return `${baseClasses} w-96`
        default:
            return `${baseClasses} w-full max-w-4xl`
    }
}

const getPreviewHeight = () => {
    switch (selectedDevice.value) {
        case 'mobile':
            return 'h-96'
        case 'tablet':
            return 'h-80'
        default:
            return 'h-96'
    }
}

// Methods
const getPreviewUrl = () => {
    if (websiteContent.value?.subdomain) {
        return `https://${websiteContent.value.subdomain}.oursite.com`
    }
    return `/preview/${websiteContent.value?.id}`
}

const copyToClipboard = async (text) => {
    try {
        await navigator.clipboard.writeText(text)
        // You could add a toast notification here
    } catch (err) {
        console.error('Failed to copy: ', err)
    }
}

const refreshPreview = () => {
    refreshing.value = true
    previewLoading.value = true

    // Simulate refresh delay
    setTimeout(() => {
        refreshing.value = false
        previewLoading.value = false
    }, 1000)
}

const onPreviewLoad = () => {
    previewLoading.value = false
}

// Lifecycle hooks
onMounted(() => {
    setTimeout(() => {
        loading.value = false
    }, 800)
})
</script>

<style scoped>
/* Custom animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.fade-in {
    animation: fadeIn 0.6s ease-out;
}

/* Preview frame styling */
.preview-frame {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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

/* Device frame animations */
.device-frame {
    transition: all 0.3s ease-in-out;
}

.device-frame:hover {
    transform: translateY(-2px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
</style>
