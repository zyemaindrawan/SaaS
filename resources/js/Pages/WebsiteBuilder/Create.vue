<template>
    <AppLayout>
        <Head :title="`Create Website - ${template.name}`" />

        <!-- Loading Component -->
        <PageLoader :show="loading" />

        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
            <!-- Hero Section -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                <div class="container mx-auto px-4 py-12">
                    <div class="max-w-4xl mx-auto text-center">
                        <div class="flex items-center justify-center mb-4">
                            <span class="text-sm font-medium bg-white bg-opacity-20 px-3 py-1 rounded-full">Step 2 of 3</span>
                        </div>

                        <h1 class="text-4xl md:text-5xl font-bold mb-4">
                            Buat Website Anda
                        </h1>
                        <p class="text-xl opacity-90 max-w-2xl mx-auto">
                            Isi konten website Anda dengan template {{ template.name }}
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
                                :href="`/templates/${template.slug}`"
                                class="text-gray-400 hover:text-gray-500 transition duration-200"
                            >
                                {{ template.name }}
                            </Link>
                        </li>
                        <li>
                            <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-300" />
                        </li>
                        <li class="text-gray-500 font-medium">
                            Create Website
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="container mx-auto px-4 py-8">
                <div class="max-w-4xl mx-auto space-y-8">

                    <!-- Template Preview Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <TemplateIcon class="w-5 h-5 mr-2" />
                                Template Preview
                            </h3>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <img
                                        :src="template.preview_image || '/default-avatar.png'"
                                        :alt="template.name"
                                        class="w-full h-48 object-cover rounded-lg mb-4"
                                        @error="handleImageError"
                                    >
                                    <h4 class="font-bold text-xl text-gray-900 mb-2">
                                        {{ template.name }}
                                    </h4>
                                    <p class="text-gray-600 leading-relaxed">
                                        {{ template.description }}
                                    </p>
                                </div>

                                <div class="space-y-4">
                                    <div class="bg-blue-50 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-2">Template Details</h5>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Price:</span>
                                                <span class="font-medium text-green-600">{{ formatPrice(template.price) }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Category:</span>
                                                <span class="font-medium">{{ formatCategory(template.category) }}</span>
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
                                    Website Content
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
                                        placeholder="Enter your website name"
                                    >
                                    <p class="text-xs text-gray-500 mt-1">This will be used as your website title</p>
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
                                            placeholder="mywebsite"
                                        >
                                        <span class="inline-flex items-center px-3 py-3 text-sm text-gray-500 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg">
                                            .oursite.com
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Leave empty for auto-generated subdomain</p>
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

                                <!-- Submit Button -->
                                <div class="pt-6">
                                    <button
                                        type="submit"
                                        :disabled="submitting"
                                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-8 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                    >
                                        <span v-if="submitting">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Creating Website...
                                        </span>
                                        <span v-else>
                                            Continue to Payment
                                        </span>
                                    </button>
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

// Icons
import {
    HomeIcon,
    ChevronRightIcon,
    TemplateIcon,
    DocumentTextIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    template: Object,
    config: Object
})

// Reactive state
const loading = ref(true)
const submitting = ref(false)

const form = reactive({
    website_name: '',
    subdomain: '',
    content_data: {}
})

// Methods
const formatPrice = (price) => {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(price)
}

const formatCategory = (category) => {
    if (!category) return ''
    return category.split('-').map(word =>
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ')
}

const handleImageError = (event) => {
    event.target.src = '/default-avatar.png'
}

const handleFileUpload = (fieldName, event) => {
    const file = event.target.files[0]
    if (file) {
        // In a real application, you would upload the file to a server
        // For now, we'll just store the file object
        form.content_data[fieldName] = file
    }
}

const submitForm = () => {
    if (submitting.value) return

    submitting.value = true

    router.post('/website-builder', {
        template_slug: props.template.slug,
        website_name: form.website_name,
        subdomain: form.subdomain,
        content_data: form.content_data
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
