<template>
    <AppLayout>
        <Head :title="`Edit Website - ${websiteContent.website_name}`" />

        <!-- Loading Component -->
        <PageLoader :show="loading" />

        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
            <!-- Hero Section -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                <div class="container mx-auto px-4 py-12">
                    <div class="max-w-4xl mx-auto text-center">
                        <div class="flex items-center justify-center mb-4">
                            <span class="text-sm font-medium bg-white bg-opacity-20 px-3 py-1 rounded-full">Edit Website</span>
                        </div>

                        <h1 class="text-4xl md:text-5xl font-bold mb-4">
                            Edit Website Content
                        </h1>
                        <p class="text-xl opacity-90 max-w-2xl mx-auto">
                            Update your website content for {{ websiteContent.website_name }}
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
                            Edit Website
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="container mx-auto px-4 py-8">
                <div class="max-w-4xl mx-auto space-y-8">

                    <!-- Website Status Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <GlobeAltIcon class="w-5 h-5 mr-2" />
                                Website Status
                            </h3>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="bg-blue-50 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-2">Website Information</h5>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Name:</span>
                                                <span class="font-medium">{{ websiteContent.website_name }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Status:</span>
                                                <StatusBadge :status="websiteContent.status" />
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Created:</span>
                                                <span class="font-medium">{{ formatDate(websiteContent.created_at) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="bg-green-50 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-2">Website URLs</h5>
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

                    <!-- Content Form -->
                    <form @submit.prevent="submitForm" class="space-y-6">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                                <h3 class="text-lg font-semibold text-white flex items-center">
                                    <DocumentTextIcon class="w-5 h-5 mr-2" />
                                    Update Website Content
                                </h3>
                            </div>

                            <div class="p-6">
                                <!-- Website Name -->
                                <div class="mb-6">
                                    <label for="website_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Website Name *
                                    </label>
                                    <input
                                        id="website_name"
                                        v-model="form.website_name"
                                        type="text"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                        :placeholder="websiteContent.website_name"
                                    >
                                </div>

                                <!-- Subdomain -->
                                <div class="mb-6">
                                    <label for="subdomain" class="block text-sm font-medium text-gray-700 mb-2">
                                        Subdomain
                                    </label>
                                    <div class="flex">
                                        <input
                                            id="subdomain"
                                            v-model="form.subdomain"
                                            type="text"
                                            class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                            :placeholder="websiteContent.subdomain || 'mywebsite'"
                                        >
                                        <span class="inline-flex items-center px-3 py-3 text-sm text-gray-500 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg">
                                            .oursite.com
                                        </span>
                                    </div>
                                </div>

                                <!-- Dynamic Content Fields -->
                                <div v-if="config && config.fields" class="space-y-6">
                                    <div
                                        v-for="field in config.fields"
                                        :key="field.name"
                                        class="border border-gray-200 rounded-lg p-4"
                                    >
                                        <label :for="field.name" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ field.label }} <span v-if="field.required" class="text-red-500">*</span>
                                        </label>

                                        <!-- Text Input -->
                                        <input
                                            v-if="field.type === 'text'"
                                            :id="field.name"
                                            v-model="form.content_data[field.name]"
                                            :type="field.type"
                                            :required="field.required"
                                            :placeholder="field.placeholder || `Enter ${field.label.toLowerCase()}`"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                        >

                                        <!-- Textarea -->
                                        <textarea
                                            v-else-if="field.type === 'textarea'"
                                            :id="field.name"
                                            v-model="form.content_data[field.name]"
                                            :required="field.required"
                                            :placeholder="field.placeholder || `Enter ${field.label.toLowerCase()}`"
                                            rows="4"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                        ></textarea>

                                        <!-- Select -->
                                        <select
                                            v-else-if="field.type === 'select'"
                                            :id="field.name"
                                            v-model="form.content_data[field.name]"
                                            :required="field.required"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                        >
                                            <option value="">Select {{ field.label.toLowerCase() }}</option>
                                            <option
                                                v-for="option in field.options"
                                                :key="option.value"
                                                :value="option.value"
                                            >
                                                {{ option.label }}
                                            </option>
                                        </select>

                                        <!-- File Upload -->
                                        <input
                                            v-else-if="field.type === 'file'"
                                            :id="field.name"
                                            :required="field.required"
                                            type="file"
                                            @change="handleFileUpload(field.name, $event)"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                            accept="image/*"
                                        >

                                        <!-- Default Text Input -->
                                        <input
                                            v-else
                                            :id="field.name"
                                            v-model="form.content_data[field.name]"
                                            type="text"
                                            :required="field.required"
                                            :placeholder="field.placeholder || `Enter ${field.label.toLowerCase()}`"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                        >

                                        <p v-if="field.description" class="text-xs text-gray-500 mt-1">
                                            {{ field.description }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="pt-6 flex flex-col sm:flex-row gap-4">
                                    <button
                                        type="submit"
                                        :disabled="submitting"
                                        class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-8 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                    >
                                        <span v-if="submitting">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Updating Website...
                                        </span>
                                        <span v-else>
                                            Update Website
                                        </span>
                                    </button>

                                    <Link
                                        :href="`/preview/${websiteContent.id}`"
                                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 px-8 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-center"
                                    >
                                        Preview Website
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PageLoader from '@/Components/PageLoader.vue'
import AlertMessages from '@/Components/AlertMessages.vue'
import StatusBadge from '@/Components/StatusBadge.vue'

// Icons
import {
    HomeIcon,
    ChevronRightIcon,
    GlobeAltIcon,
    DocumentTextIcon,
    DocumentDuplicateIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    websiteContent: Object,
    template: Object,
    config: Object
})

// Reactive state
const loading = ref(true)
const submitting = ref(false)

const form = reactive({
    website_name: props.websiteContent?.website_name || '',
    subdomain: props.websiteContent?.subdomain || '',
    content_data: {
        // Company Info
        company_name: '',
        company_tagline: '',
        company_logo: null,
        
        // Website Basics
        website_name: '',
        font_family: 'inter',
        border_radius: 'small',
        
        // Colors
        primary_color: '#2146ff',
        secondary_color: '#64748b',
        accent_color: '#f59e0b',
        whatsapp_color: '#25D366',
        
        // SEO
        seo_title: '',
        seo_description: '',
        seo_keywords: '',
        robots_meta: 'index,follow',
        favicon: null,
        og_image: null,
        
        // Contact
        contact_email: '',
        contact_phone: '',
        contact_address: '',
        contact_title: 'Hubungi Kami',
        
        // Hero Section
        hero_title: '',
        hero_subtitle: '',
        hero_background: null,
        hero_cta_text: '',
        hero_cta_link: '',
        
        // About Section
        about_title: null,
        about_content: null,
        about_image: null,
        
        // Services Section
        services_title: '',
        services_subtitle: '',
        services: [],
        
        // Company Stats
        company_stats: [],
        
        // Social Media
        social_links: [],
        
        // Testimonials
        testimonials_title: 'Testimoni Klien',
        testimonials: [],
        
        // Gallery
        gallery_title: 'Portfolio & Galeri',
        gallery_photos: [],
        
        // WhatsApp Integration
        whatsapp_enabled: true,
        whatsapp_number: '',
        whatsapp_message: '',
        whatsapp_position: 'bottom-right',
        whatsapp_greeting_text: '',
        
        // Analytics
        google_analytics: '',
        google_tag_manager: '',
        ...props.websiteContent?.content_data || {}
    }
})

// Methods
const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const copyToClipboard = async (text) => {
    try {
        await navigator.clipboard.writeText(text)
        // You could add a toast notification here
    } catch (err) {
        console.error('Failed to copy: ', err)
    }
}

const handleFileUpload = async (fieldName, event) => {
    const file = event.target.files[0]
    if (file) {
        if (file.size > 2 * 1024 * 1024) { // 2MB limit
            alert('File size should not exceed 2MB')
            return
        }

        try {
            // Convert to base64
            const base64 = await fileToBase64(file)
            form.content_data[fieldName] = base64
        } catch (error) {
            console.error('Error processing file:', error)
            alert('Error processing file. Please try again.')
        }
    }
}

const fileToBase64 = (file) => {
    return new Promise((resolve, reject) => {
        const reader = new FileReader()
        reader.readAsDataURL(file)
        reader.onload = () => resolve(reader.result)
        reader.onerror = (error) => reject(error)
    })
}

// Array field handlers
const addArrayItem = (fieldName, template) => {
    if (!Array.isArray(form.content_data[fieldName])) {
        form.content_data[fieldName] = []
    }
    form.content_data[fieldName].push({...template})
}

const removeArrayItem = (fieldName, index) => {
    if (Array.isArray(form.content_data[fieldName])) {
        form.content_data[fieldName].splice(index, 1)
    }
}

const addService = () => {
    addArrayItem('services', {
        title: '',
        description: '',
        icon: 'fas fa-star',
        link: ''
    })
}

const addCompanyStat = () => {
    addArrayItem('company_stats', {
        number: '',
        label: '',
        icon: 'fas fa-chart-line'
    })
}

const addTestimonial = () => {
    addArrayItem('testimonials', {
        name: '',
        position: '',
        content: '',
        rating: '5',
        photo: null
    })
}

const addSocialLink = () => {
    addArrayItem('social_links', {
        platform: '',
        url: '',
        label: ''
    })
}

const submitForm = () => {
    if (submitting.value) return

    submitting.value = true

    // Clean up empty arrays
    const cleanedContentData = { ...form.content_data }
    
    // Clean up arrays
    if (Array.isArray(cleanedContentData.services)) {
        cleanedContentData.services = cleanedContentData.services.filter(service => 
            service.title && service.description
        )
    }
    
    if (Array.isArray(cleanedContentData.company_stats)) {
        cleanedContentData.company_stats = cleanedContentData.company_stats.filter(stat => 
            stat.number && stat.label
        )
    }
    
    if (Array.isArray(cleanedContentData.testimonials)) {
        cleanedContentData.testimonials = cleanedContentData.testimonials.filter(testimonial => 
            testimonial.name && testimonial.content
        )
    }
    
    if (Array.isArray(cleanedContentData.social_links)) {
        cleanedContentData.social_links = cleanedContentData.social_links.filter(link => 
            link.platform && link.url
        )
    }

    router.put(`/website-builder/${props.websiteContent.id}`, {
        website_name: form.website_name,
        subdomain: form.subdomain,
        content_data: cleanedContentData
    }, {
        onSuccess: () => {
            submitting.value = false
        },
        onError: () => {
            submitting.value = false
        },
        onFinish: () => {
            submitting.value = false
        }
    })
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

/* Form styling */
.form-input:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Button hover effects */
.submit-button:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.submit-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}
</style>
