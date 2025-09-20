<template>
    <AppLayout>
        <Head :title="`Checkout - ${template.name}`" />

        <!-- Loading Component -->
        <PageLoader :show="loading" />

        <!-- Confirmation Dialog -->
        <ConfirmationDialog
            :show="showConfirmDialog"
            :loading="submitting"
            :template="template"
            :form-data="formData"
            :pricing="pricing"
            @confirm="submitForm"
            @cancel="showConfirmDialog = false"
        />

        <!-- Error Dialog -->
        <ErrorDialog
            :show="showErrorDialog"
            :title="errorTitle"
            :message="errorMessage"
            :error="errorDetails"
            :show-retry="true"
            @close="closeErrorDialog"
            @retry="retrySubmission"
        />

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="flex items-center justify-center mb-4">
                        <span class="text-sm font-medium bg-white bg-opacity-20 px-3 py-1 rounded-full">Step 2 of 3</span>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        Kostum Konten Website
                    </h1>
                    <p class="text-xl opacity-90 max-w-2xl mx-auto">
                        Lengkapi informasi berikut dan buatlah template {{ template.name }} Anda
                    </p>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6" v-if="$page.props.flash?.message">
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg shadow-sm" v-if="$page.props.flash?.type === 'success'">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 font-medium">{{ $page.props.flash.message }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg shadow-sm" v-else-if="$page.props.flash?.type === 'error'">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-medium">{{ $page.props.flash.message }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breadcrumb -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <Breadcrumb :breadcrumbs="breadcrumbs" />
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <form @submit.prevent="showConfirmation" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column - Form Sections (2 columns) -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Company Information & SEO Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2h4a1 1 0 110 2h-1v11a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 010-2h4zM9 3v1h2V3H9zM8 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 112 0v6a1 1 0 11-2 0V8z"/>
                                </svg>
                                Informasi Perusahaan & SEO
                            </h3>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Company Name -->
                                <div>
                                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Company Name <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="company_name"
                                        name="company_name"
                                        v-model="formData.company_name"
                                        placeholder="PT. Corporate Indonesia"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-300': errors.company_name }"
                                        required
                                    >
                                    <div v-if="errors.company_name" class="mt-1 text-sm text-red-600">{{ errors.company_name }}</div>
                                </div>

                                <!-- Website Name -->
                                <div>
                                    <label for="website_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Website Name <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="website_name"
                                        name="website_name"
                                        v-model="formData.website_name"
                                        placeholder="Corporate Indonesia Official"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-300': errors.website_name }"
                                        required
                                    >
                                    <div v-if="errors.website_name" class="mt-1 text-sm text-red-600">{{ errors.website_name }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Company Tagline -->
                                <div>
                                    <label for="company_tagline" class="block text-sm font-medium text-gray-700 mb-2">
                                        Company Tagline
                                    </label>
                                    <input 
                                        type="text" 
                                        id="company_tagline"
                                        v-model="formData.company_tagline"
                                        placeholder="Solusi Digital Bisnis Anda"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-300': errors.company_tagline }"
                                    >
                                    <div v-if="errors.company_tagline" class="mt-1 text-sm text-red-600">{{ errors.company_tagline }}</div>
                                </div>

                                <!-- Placeholder for future field -->
                                <div></div>
                            </div>

                            <!-- SEO Title -->
                            <div>
                                <label for="seo_title" class="block text-sm font-medium text-gray-700 mb-2">
                                    SEO Title <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="seo_title"
                                    v-model="formData.seo_title"
                                    placeholder="PT Corporate Indonesia - Solusi Bisnis Terbaik & Terpercaya"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-300': errors.seo_title }"
                                    maxlength="60"
                                    required
                                >
                                <p class="mt-1 text-xs text-gray-500">Recommended: 50-60 characters</p>
                                <div v-if="errors.seo_title" class="mt-1 text-sm text-red-600">{{ errors.seo_title }}</div>
                            </div>

                            <!-- SEO Description -->
                            <div>
                                <label for="seo_description" class="block text-sm font-medium text-gray-700 mb-2">
                                    SEO Description <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    id="seo_description"
                                    v-model="formData.seo_description"
                                    rows="3"
                                    placeholder="Describe your business for search engines..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-300': errors.seo_description }"
                                    maxlength="160"
                                    required
                                ></textarea>
                                <p class="mt-1 text-xs text-gray-500">Recommended: 150-160 characters</p>
                                <div v-if="errors.seo_description" class="mt-1 text-sm text-red-600">{{ errors.seo_description }}</div>
                            </div>

                            <!-- SEO Keywords -->
                            <div>
                                <label for="seo_keywords" class="block text-sm font-medium text-gray-700 mb-2">
                                    SEO Keywords
                                </label>
                                <input 
                                    type="text" 
                                    id="seo_keywords"
                                    v-model="formData.seo_keywords"
                                    placeholder="konsultasi bisnis, digital marketing, solusi teknologi"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-300': errors.seo_keywords }"
                                >
                                <p class="mt-1 text-xs text-gray-500">Separate keywords with commas</p>
                                <div v-if="errors.seo_keywords" class="mt-1 text-sm text-red-600">{{ errors.seo_keywords }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information & WhatsApp Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                                Kontak & WhatsApp
                            </h3>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Contact Email -->
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="email" 
                                        id="contact_email"
                                        v-model="formData.contact_email"
                                        placeholder="info@corporateindonesia.com"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-300': errors.contact_email }"
                                        required
                                    >
                                    <div v-if="errors.contact_email" class="mt-1 text-sm text-red-600">{{ errors.contact_email }}</div>
                                </div>

                                <!-- Contact Phone -->
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="tel" 
                                        id="contact_phone"
                                        v-model="formData.contact_phone"
                                        placeholder="+62 812-3456-7890"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-300': errors.contact_phone }"
                                        required
                                    >
                                    <div v-if="errors.contact_phone" class="mt-1 text-sm text-red-600">{{ errors.contact_phone }}</div>
                                </div>
                            </div>

                            <!-- Contact Address -->
                            <div>
                                <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Complete Address <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    id="contact_address"
                                    v-model="formData.contact_address"
                                    rows="2"
                                    placeholder="Jl. Sudirman No. 123, Jakarta Pusat 10220, DKI Jakarta, Indonesia"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-300': errors.contact_address }"
                                    required
                                ></textarea>
                                <div v-if="errors.contact_address" class="mt-1 text-sm text-red-600">{{ errors.contact_address }}</div>
                            </div>

                            <!-- WhatsApp Settings -->
                            <div class="border-t border-gray-200 pt-6">
                                <div class="flex items-center mb-4">
                                    <input 
                                        id="whatsapp_enabled" 
                                        v-model="formData.whatsapp_enabled"
                                        type="checkbox" 
                                        class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                    >
                                    <div class="ml-3">
                                        <label for="whatsapp_enabled" class="text-sm font-medium text-gray-700">
                                            Enable WhatsApp Chat Widget
                                        </label>
                                        <p class="text-xs text-gray-500">Add floating WhatsApp button to your website</p>
                                    </div>
                                </div>

                                <div v-show="formData.whatsapp_enabled" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- WhatsApp Number -->
                                    <div>
                                        <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">
                                            WhatsApp Number
                                        </label>
                                        <input 
                                            type="text" 
                                            id="whatsapp_number"
                                            v-model="formData.whatsapp_number"
                                            placeholder="6281234567890"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            :class="{ 'border-red-300': errors.whatsapp_number }"
                                        >
                                        <p class="mt-1 text-xs text-gray-500">Format: 6281234567890 (with country code, no +)</p>
                                        <div v-if="errors.whatsapp_number" class="mt-1 text-sm text-red-600">{{ errors.whatsapp_number }}</div>
                                    </div>

                                    <!-- WhatsApp Position -->
                                    <div>
                                        <label for="whatsapp_position" class="block text-sm font-medium text-gray-700 mb-2">
                                            Widget Position
                                        </label>
                                        <select 
                                            id="whatsapp_position"
                                            v-model="formData.whatsapp_position"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            :class="{ 'border-red-300': errors.whatsapp_position }"
                                        >
                                            <option value="bottom-right">Bottom Right</option>
                                            <option value="bottom-left">Bottom Left</option>
                                        </select>
                                        <div v-if="errors.whatsapp_position" class="mt-1 text-sm text-red-600">{{ errors.whatsapp_position }}</div>
                                    </div>

                                    <!-- WhatsApp Message -->
                                    <div class="md:col-span-2">
                                        <label for="whatsapp_message" class="block text-sm font-medium text-gray-700 mb-2">
                                            Default Message
                                        </label>
                                        <textarea 
                                            id="whatsapp_message"
                                            v-model="formData.whatsapp_message"
                                            rows="2"
                                            placeholder="Halo {company_name}, saya tertarik dengan layanan Anda..."
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            :class="{ 'border-red-300': errors.whatsapp_message }"
                                        ></textarea>
                                        <p class="mt-1 text-xs text-gray-500">Use {company_name} to automatically insert company name</p>
                                        <div v-if="errors.whatsapp_message" class="mt-1 text-sm text-red-600">{{ errors.whatsapp_message }}</div>
                                    </div>

                                    <!-- WhatsApp Greeting Text -->
                                    <div>
                                        <label for="whatsapp_greeting_text" class="block text-sm font-medium text-gray-700 mb-2">
                                            WhatsApp Button Text
                                        </label>
                                        <input 
                                            type="text" 
                                            id="whatsapp_greeting_text"
                                            v-model="formData.whatsapp_greeting_text"
                                            placeholder="Chat Us"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            :class="{ 'border-red-300': errors.whatsapp_greeting_text }"
                                        >
                                        <div v-if="errors.whatsapp_greeting_text" class="mt-1 text-sm text-red-600">{{ errors.whatsapp_greeting_text }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Website Settings, SEO & Analytics Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
                                </svg>
                                Website, SEO & Analytics
                            </h3>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <!-- Website Address -->
                            <div>
                                <label for="subdomain" class="block text-sm font-medium text-gray-700 mb-2">
                                    Website Address <span class="text-red-500">*</span>
                                </label>
                                <div class="flex">
                                    <input 
                                        type="text" 
                                        id="subdomain"
                                        v-model="formData.subdomain"
                                        placeholder="corporateindonesia"
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-300': errors.subdomain }"
                                        pattern="[a-z0-9\-]+"
                                        required
                                        @input="sanitizeSubdomain"
                                    >
                                    <span class="inline-flex items-center px-4 py-3 bg-gray-50 border border-l-0 border-gray-300 rounded-r-lg text-sm text-gray-500">
                                        .oursite.com
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Only lowercase letters, numbers, and hyphens allowed</p>
                                <div v-if="errors.subdomain" class="mt-1 text-sm text-red-600">{{ errors.subdomain }}</div>
                            </div>

                            <!-- SEO & Analytics -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="google_analytics" class="block text-sm font-medium text-gray-700 mb-2">
                                        Google Analytics ID
                                    </label>
                                    <input 
                                        type="text" 
                                        id="google_analytics"
                                        v-model="formData.google_analytics"
                                        placeholder="G-XXXXXXXXXX"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>

                                <div>
                                    <label for="google_tag_manager" class="block text-sm font-medium text-gray-700 mb-2">
                                        Google Tag Manager ID
                                    </label>
                                    <input 
                                        type="text" 
                                        id="google_tag_manager"
                                        v-model="formData.google_tag_manager"
                                        placeholder="GTM-XXXXXXX"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="robots_meta" class="block text-sm font-medium text-gray-700 mb-2">
                                    Robots Meta Tag
                                </label>
                                <select 
                                    id="robots_meta"
                                    v-model="formData.robots_meta"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="index,follow">Index, Follow</option>
                                    <option value="noindex,nofollow">Noindex, Nofollow</option>
                                    <option value="index,nofollow">Index, Nofollow</option>
                                    <option value="noindex,follow">Noindex, Follow</option>
                                </select>
                            </div>

                            <!-- Design Colors -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Primary Color -->
                                <div>
                                    <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">
                                        Primary Color <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center space-x-3">
                                        <input 
                                            type="color" 
                                            id="primary_color"
                                            v-model="formData.primary_color"
                                            class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer"
                                            :class="{ 'border-red-300': errors.primary_color }"
                                            required
                                        >
                                        <div class="flex-1">
                                            <input 
                                                type="text" 
                                                :value="formData.primary_color"
                                                placeholder="#2146ff"
                                                class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                                                readonly
                                            >
                                        </div>
                                    </div>
                                    <div v-if="errors.primary_color" class="mt-1 text-sm text-red-600">{{ errors.primary_color }}</div>
                                </div>

                                <!-- Secondary Color -->
                                <div>
                                    <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-2">
                                        Secondary Color
                                    </label>
                                    <div class="flex items-center space-x-3">
                                        <input 
                                            type="color" 
                                            id="secondary_color"
                                            v-model="formData.secondary_color"
                                            class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer"
                                            :class="{ 'border-red-300': errors.secondary_color }"
                                        >
                                        <div class="flex-1">
                                            <input 
                                                type="text" 
                                                :value="formData.secondary_color"
                                                placeholder="#64748b"
                                                class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                                                readonly
                                            >
                                        </div>
                                    </div>
                                    <div v-if="errors.secondary_color" class="mt-1 text-sm text-red-600">{{ errors.secondary_color }}</div>
                                </div>

                                <!-- Accent Color -->
                                <div>
                                    <label for="accent_color" class="block text-sm font-medium text-gray-700 mb-2">
                                        Accent Color
                                    </label>
                                    <div class="flex items-center space-x-3">
                                        <input 
                                            type="color" 
                                            id="accent_color"
                                            v-model="formData.accent_color"
                                            class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer"
                                            :class="{ 'border-red-300': errors.accent_color }"
                                        >
                                        <div class="flex-1">
                                            <input 
                                                type="text" 
                                                :value="formData.accent_color"
                                                placeholder="#f59e0b"
                                                class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                                                readonly
                                            >
                                        </div>
                                    </div>
                                    <div v-if="errors.accent_color" class="mt-1 text-sm text-red-600">{{ errors.accent_color }}</div>
                                </div>
                            </div>

                            <!-- Typography & Style -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Font Family -->
                                <div>
                                    <label for="font_family" class="block text-sm font-medium text-gray-700 mb-2">
                                        Font Family
                                    </label>
                                    <select 
                                        id="font_family"
                                        v-model="formData.font_family"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-300': errors.font_family }"
                                    >
                                        <option value="inter">Inter (Modern Sans-serif)</option>
                                        <option value="roboto">Roboto (Clean Sans-serif)</option>
                                        <option value="open-sans">Open Sans (Friendly Sans-serif)</option>
                                        <option value="lora">Lora (Elegant Serif)</option>
                                        <option value="poppins">Poppins (Geometric Sans-serif)</option>
                                        <option value="playfair">Playfair Display (Stylish Serif)</option>
                                    </select>
                                    <div v-if="errors.font_family" class="mt-1 text-sm text-red-600">{{ errors.font_family }}</div>
                                </div>

                                <!-- Border Radius -->
                                <div>
                                    <label for="border_radius" class="block text-sm font-medium text-gray-700 mb-2">
                                        Design Style
                                    </label>
                                    <select 
                                        id="border_radius"
                                        v-model="formData.border_radius"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-300': errors.border_radius }"
                                    >
                                        <option value="none">Sharp (No Rounded Corners)</option>
                                        <option value="small">Slightly Rounded</option>
                                        <option value="medium">Medium Rounded</option>
                                        <option value="large">Very Rounded</option>
                                        <option value="full">Fully Rounded (Pills)</option>
                                    </select>
                                    <div v-if="errors.border_radius" class="mt-1 text-sm text-red-600">{{ errors.border_radius }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gallery Photos Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                                Galeri & Portfolio
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="space-y-4">
                                <div 
                                    v-for="(photo, index) in formData.gallery_photos" 
                                    :key="`gallery-${index}`"
                                    class="p-4 border border-gray-200 rounded-lg bg-gray-50 relative"
                                >
                                    <button 
                                        type="button" 
                                        @click="removeGalleryPhoto(index)"
                                        class="absolute top-2 right-2 text-red-500 hover:text-red-700"
                                        v-if="formData.gallery_photos.length > 1"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Photo URL</label>
                                        <input 
                                            type="url" 
                                            v-model="formData.gallery_photos[index]"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                                            placeholder="https://images.unsplash.com/photo-..."
                                        >
                                    </div>
                                </div>
                            </div>
                            <button 
                                type="button" 
                                @click="addGalleryPhoto"
                                class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out"
                            >
                                + Add Gallery Photo
                            </button>
                        </div>
                    </div>

                    <!-- Services Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Layanan & Produk Unggulan
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="space-y-4">
                                <div 
                                    v-for="(service, index) in formData.services" 
                                    :key="`service-${index}`"
                                    class="p-4 border border-gray-200 rounded-lg relative"
                                >
                                    <button 
                                        type="button" 
                                        @click="removeService(index)"
                                        class="absolute top-2 right-2 text-red-500 hover:text-red-700"
                                        v-if="formData.services.length > 1"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Font Awesome)</label>
                                            <input 
                                                type="text" 
                                                v-model="service.icon"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                                                placeholder="fas fa-chart-line"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Link (Anchor)</label>
                                            <input 
                                                type="text" 
                                                v-model="service.link"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                                                placeholder="#konsultasi-bisnis"
                                            >
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Layanan</label>
                                        <input 
                                            type="text" 
                                            v-model="service.title"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                                            placeholder="Konsultasi Bisnis"
                                        >
                                    </div>
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                        <textarea 
                                            v-model="service.description"
                                            rows="3" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                                            placeholder="Strategi dan perencanaan bisnis..."
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                            <button 
                                type="button" 
                                @click="addService"
                                class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out"
                            >
                                + Tambah Layanan
                            </button>
                        </div>
                    </div>

                    <!-- Hero & About Content Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                </svg>
                                Hero & About Content
                            </h3>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <!-- Hero Section Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="hero_title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Hero Title
                                    </label>
                                    <input 
                                        type="text" 
                                        id="hero_title"
                                        v-model="formData.hero_title"
                                        placeholder="Solusi Bisnis Terbaik"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>

                                <div>
                                    <label for="hero_cta_text" class="block text-sm font-medium text-gray-700 mb-2">
                                        Hero Button Text
                                    </label>
                                    <input 
                                        type="text" 
                                        id="hero_cta_text"
                                        v-model="formData.hero_cta_text"
                                        placeholder="Konsultasi Gratis"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="hero_subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                                    Hero Subtitle
                                </label>
                                <textarea 
                                    id="hero_subtitle"
                                    v-model="formData.hero_subtitle"
                                    rows="2"
                                    placeholder="Kami memberikan layanan terbaik untuk mengembangkan bisnis Anda..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                ></textarea>
                            </div>

                            <div>
                                <label for="hero_cta_link" class="block text-sm font-medium text-gray-700 mb-2">
                                    Hero Button Link
                                </label>
                                <input 
                                    type="text" 
                                    id="hero_cta_link"
                                    v-model="formData.hero_cta_link"
                                    placeholder="#contact"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>

                            <!-- About Section -->
                            <div class="border-t border-gray-200 pt-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label for="about_title" class="block text-sm font-medium text-gray-700 mb-2">
                                            About Section Title
                                        </label>
                                        <input 
                                            type="text" 
                                            id="about_title"
                                            v-model="formData.about_title"
                                            placeholder="Tentang Kami"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        >
                                    </div>

                                    <div>
                                        <label for="contact_title" class="block text-sm font-medium text-gray-700 mb-2">
                                            Contact Section Title
                                        </label>
                                        <input 
                                            type="text" 
                                            id="contact_title"
                                            v-model="formData.contact_title"
                                            placeholder="Hubungi Kami"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        >
                                    </div>
                                </div>

                                <div>
                                    <label for="about_content" class="block text-sm font-medium text-gray-700 mb-2">
                                        About Content <span class="text-red-500">*</span>
                                    </label>
                                    <textarea 
                                        id="about_content"
                                        v-model="formData.about_content"
                                        rows="6"
                                        placeholder="Describe your company, mission, and values..."
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-300': errors.about_content }"
                                        required
                                    ></textarea>
                                    <p class="mt-1 text-xs text-gray-500">You can use HTML tags for formatting</p>
                                    <div v-if="errors.about_content" class="mt-1 text-sm text-red-600">{{ errors.about_content }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonials Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M18 3a1 1 0 00-1.447-.894L8.763 6H5a3 3 0 000 6h.28l1.771 5.316A1 1 0 008 18h1a1 1 0 001-1v-4.382l6.553 3.276A1 1 0 0018 15V3z"/>
                                </svg>
                                Testimoni & Ulasan Klien
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="space-y-4">
                                <div 
                                    v-for="(testimonial, index) in formData.testimonials" 
                                    :key="`testimonial-${index}`"
                                    class="p-4 border border-gray-200 rounded-lg bg-gray-50 relative"
                                >
                                    <button 
                                        type="button" 
                                        @click="removeTestimonial(index)"
                                        class="absolute top-2 right-2 text-red-500 hover:text-red-700"
                                        v-if="formData.testimonials.length > 1"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                            <input 
                                                type="text" 
                                                v-model="testimonial.name"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                                                placeholder="Rudi Wijaya"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Posisi / Jabatan</label>
                                            <input 
                                                type="text" 
                                                v-model="testimonial.position"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                                                placeholder="CEO, PT Maju Bersama"
                                            >
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Rating (1-5)</label>
                                        <select v-model="testimonial.rating" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                            <option value="5">5 - Sangat Puas</option>
                                            <option value="4">4 - Puas</option>
                                            <option value="3">3 - Cukup</option>
                                            <option value="2">2 - Kurang</option>
                                            <option value="1">1 - Tidak Puas</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Testimoni</label>
                                        <textarea 
                                            v-model="testimonial.content"
                                            rows="3" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                                            placeholder="Pelayanan PT Corporate Indonesia sangat profesional..."
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                            <button 
                                type="button" 
                                @click="addTestimonial"
                                class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out"
                            >
                                + Tambah Testimoni
                            </button>
                        </div>
                    </div>
                    
                    <!-- Social Links Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-pink-600 to-pink-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"/>
                                </svg>
                                Social Media Links
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="space-y-4">
                                <div 
                                    v-for="(social, index) in formData.social_links" 
                                    :key="`social-${index}`"
                                    class="p-4 border border-gray-200 rounded-lg bg-gray-50 relative"
                                >
                                    <button 
                                        type="button" 
                                        @click="removeSocialLink(index)"
                                        class="absolute top-2 right-2 text-red-500 hover:text-red-700"
                                        v-if="formData.social_links.length > 1"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Platform</label>
                                            <select v-model="social.platform" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                                <option value="facebook">Facebook</option>
                                                <option value="instagram">Instagram</option>
                                                <option value="twitter">Twitter</option>
                                                <option value="linkedin">LinkedIn</option>
                                                <option value="youtube">YouTube</option>
                                                <option value="tiktok">TikTok</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                                            <input 
                                                type="url" 
                                                v-model="social.url"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                                                placeholder="https://facebook.com/yourpage"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                                            <input 
                                                type="text" 
                                                v-model="social.label"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                                                placeholder="Your Page Name"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button 
                                type="button" 
                                @click="addSocialLink"
                                class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out"
                            >
                                + Add Social Link
                            </button>
                        </div>
                    </div>

                    <!-- Company Stats Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                                Company Statistics
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="space-y-4">
                                <div 
                                    v-for="(stat, index) in formData.company_stats" 
                                    :key="`stat-${index}`"
                                    class="p-4 border border-gray-200 rounded-lg bg-gray-50 relative"
                                >
                                    <button 
                                        type="button" 
                                        @click="removeCompanyStat(index)"
                                        class="absolute top-2 right-2 text-red-500 hover:text-red-700"
                                        v-if="formData.company_stats.length > 1"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Font Awesome)</label>
                                            <input 
                                                type="text" 
                                                v-model="stat.icon"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                                                placeholder="fas fa-users"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Number</label>
                                            <input 
                                                type="text" 
                                                v-model="stat.number"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                                                placeholder="500+"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                                            <input 
                                                type="text" 
                                                v-model="stat.label"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                                                placeholder="Happy Clients"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button 
                                type="button" 
                                @click="addCompanyStat"
                                class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out"
                            >
                                + Add Statistic
                            </button>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input 
                                    id="agree_terms" 
                                    v-model="formData.agree_terms" 
                                    type="checkbox" 
                                    class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    :class="{ 'border-red-300': errors.agree_terms }"
                                    required
                                >
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="agree_terms" class="font-medium text-gray-700">
                                    I agree to the Terms of Service and Privacy Policy <span class="text-red-500">*</span>
                                </label>
                                <p class="text-gray-500">
                                    By checking this box, you agree to our 
                                    <a href="#" class="text-blue-600 hover:text-blue-500">Terms of Service</a> 
                                    and 
                                    <a href="#" class="text-blue-600 hover:text-blue-500">Privacy Policy</a>.
                                </p>
                                <div v-if="errors.agree_terms" class="mt-1 text-sm text-red-600">
                                    {{ errors.agree_terms }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column - Order Summary (1 column) -->
                <div class="lg:col-span-1">
                    <OrderSummary
                        :template="template"
                        :pricing="pricing"
                        :form-data="formData"
                        :submitting="submitting"
                        :subtotal="subtotal"
                        :platform-fee="platformFee"
                        :total="total"
                        @submit="showConfirmation"
                        @voucherApplied="handleVoucherApplied"
                    />
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PageLoader from '@/Components/PageLoader.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import OrderSummary from '@/Components/OrderSummary.vue'
import ConfirmationDialog from '@/Components/ConfirmationDialog.vue'
import ErrorDialog from '@/Components/ErrorDialog.vue'

const props = defineProps({
    template: Object,
    user: Object,
    pricing: Object,
    form_fields: Array,
    breadcrumbs: Array,
    subtotal: Number,
    platformFee: Number,
    total: Number
})

const loading = ref(true)
const submitting = ref(false)
const showConfirmDialog = ref(false)
const errors = ref({})

// Error dialog state
const showErrorDialog = ref(false)
const errorTitle = ref('')
const errorMessage = ref('')
const errorDetails = ref(null)

// Initialize form data
const formData = reactive({
    // Company Information & SEO
    company_name: '',
    company_tagline: '',
    website_name: '', // Required field for database
    seo_title: '',
    seo_description: '',
    seo_keywords: '',
    
    // Contact Information
    contact_email: props.user?.email || '',
    contact_phone: props.user?.phone || '',
    contact_address: '',
    
    // WhatsApp Settings
    whatsapp_enabled: true,
    whatsapp_number: '',
    whatsapp_position: 'bottom-right',
    whatsapp_message: '',
    whatsapp_greeting_text: 'Chat Us',
    
    // Website Settings
    subdomain: '',
    google_analytics: '',
    google_tag_manager: '',
    robots_meta: 'index,follow',
    
    // Design Settings
    primary_color: '#2146ff',
    secondary_color: '#64748b',
    accent_color: '#f59e0b',
    font_family: 'inter',
    border_radius: 'medium',
    
    // Hero & About Content
    hero_title: 'Solusi Bisnis Terbaik',
    hero_subtitle: 'Kami memberikan layanan terbaik untuk mengembangkan bisnis Anda dengan teknologi modern dan strategi yang tepat sasaran',
    hero_cta_text: 'Konsultasi Gratis',
    hero_cta_link: '#contact',
    about_title: 'Tentang Kami',
    contact_title: 'Hubungi Kami',
    about_content: '<p>PT Corporate Indonesia adalah perusahaan konsultan bisnis terdepan yang telah berpengalaman lebih dari 10 tahun dalam memberikan solusi bisnis terpadu. Kami memiliki komitmen untuk membantu perusahaan mencapai kesuksesan melalui strategi yang inovatif dan berkelanjutan.</p><p>Dengan tim ahli yang berpengalaman dan teknologi terdepan, kami siap menjadi partner strategis dalam transformasi digital dan pengembangan bisnis perusahaan Anda.</p>',
    
    // Repeater Fields
    services: [
        {
            icon: '',
            link: '',
            title: '',
            description: ''
        }
    ],
    testimonials: [
        {
            name: '',
            position: '',
            rating: '5',
            content: ''
        }
    ],
    gallery_photos: [''],
    social_links: [
        {
            platform: 'facebook',
            url: '',
            label: ''
        }
    ],
    company_stats: [
        {
            icon: '',
            number: '',
            label: ''
        }
    ],
    
    // Voucher data
    voucher_code: '',
    voucher_discount: 0,
    final_total: 0,
    
    // Terms
    agree_terms: false
})

// Get fields by section
const getFieldsBySection = (section) => {
    return props.form_fields.filter(field => field.section === section)
}

// Update form data
const updateFormData = ({ key, value }) => {
    formData[key] = value
    // Clear error when field is updated
    if (errors.value[key]) {
        delete errors.value[key]
    }
}

// Show confirmation dialog
const showConfirmation = () => {
    // Validate required fields
    if (!validateForm()) {
        return
    }
    
    showConfirmDialog.value = true
}

// Submit form
const submitForm = async () => {
    submitting.value = true
    errors.value = {}

    // Debug: Log the form data being sent
    // console.log('Form data being submitted:', {
    //     company_name: formData.company_name,
    //     website_name: formData.website_name,
    // })

    try {
        await router.post(`/templates/${props.template.slug}/checkout`, formData, {
            preserveState: true,
            onSuccess: (page) => {
                // Handle success response
                const { success, message, redirect } = page.props.flash || {}
                
                if (success && redirect) {
                    window.location.href = redirect
                }
            },
            onError: (responseErrors) => {
                submitting.value = false
                showConfirmDialog.value = false
                
                // Check if this is an Inertia error with JSON response
                if (typeof responseErrors === 'string' && responseErrors.includes('plain JSON response')) {
                    handleJsonResponseError(responseErrors)
                    return
                }
                
                // Handle regular validation errors
                if (typeof responseErrors === 'object') {
                    errors.value = responseErrors
                    
                    // Scroll to first error
                    const firstErrorField = Object.keys(responseErrors)[0]
                    if (firstErrorField) {
                        const element = document.querySelector(`[name="${firstErrorField}"]`)
                        if (element) {
                            element.scrollIntoView({ behavior: 'smooth', block: 'center' })
                            element.focus()
                        }
                    }
                } else {
                    // Handle other error types
                    showErrorDialog.value = true
                    errorTitle.value = 'Submission Error'
                    errorMessage.value = 'An unexpected error occurred while processing your request.'
                    errorDetails.value = responseErrors
                }
            },
            onFinish: () => {
                submitting.value = false
            }
        })
    } catch (error) {
        console.error('Submission error:', error)
        submitting.value = false
        showConfirmDialog.value = false
        
        // Show error dialog for catch errors
        showErrorDialog.value = true
        errorTitle.value = 'Network Error'
        errorMessage.value = 'Failed to connect to the server. Please check your internet connection and try again.'
        errorDetails.value = error.message || error
    }
}

// Form validation
const validateForm = () => {
    const newErrors = {}
    
    // Required field validation
    const requiredFields = props.form_fields?.filter(field => field.required) || []
    
    // Always validate these critical fields
    const criticalFields = [
        { key: 'company_name', label: 'Company Name' },
        { key: 'website_name', label: 'Website Name' },
        { key: 'seo_title', label: 'SEO Title' },
        { key: 'seo_description', label: 'SEO Description' },
        { key: 'contact_email', label: 'Contact Email' },
        { key: 'contact_phone', label: 'Contact Phone' },
        { key: 'contact_address', label: 'Contact Address' },
        { key: 'subdomain', label: 'Subdomain' }
    ]
    
    criticalFields.forEach(field => {
        const value = formData[field.key]
        if (!value || (typeof value === 'string' && value.trim() === '')) {
            newErrors[field.key] = `${field.label} is required`
        }
    })
    
    // Additional validation from form_fields prop
    requiredFields.forEach(field => {
        if (!formData[field.key] || (Array.isArray(formData[field.key]) && formData[field.key].length === 0)) {
            newErrors[field.key] = `${field.label} is required`
        }
    })
    
    // Terms validation
    if (!formData.agree_terms) {
        newErrors.agree_terms = 'You must agree to the terms and conditions'
    }
    
    errors.value = newErrors
    return Object.keys(newErrors).length === 0
}

// Handle voucher application
const handleVoucherApplied = (voucherData) => {
    formData.voucher_code = voucherData.code
    formData.voucher_discount = voucherData.discount
    formData.final_total = voucherData.finalTotal
}

// Loading simulation
onMounted(() => {
    setTimeout(() => {
        loading.value = false
    }, 1000)
})

const generateSubdomain = (companyName) => {
    return companyName
        .toLowerCase()
        .replace(/[^a-z0-9]/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '')
        .substring(0, 20)
}

// Subdomain sanitization
const sanitizeSubdomain = (event) => {
    event.target.value = event.target.value.toLowerCase().replace(/[^a-z0-9-]/g, '')
    formData.subdomain = event.target.value
}

// Repeater field methods
const addService = () => {
    formData.services.push({
        icon: '',
        link: '',
        title: '',
        description: ''
    })
}

const removeService = (index) => {
    if (formData.services.length > 1) {
        formData.services.splice(index, 1)
    }
}

const addTestimonial = () => {
    formData.testimonials.push({
        name: '',
        position: '',
        rating: '5',
        content: ''
    })
}

const removeTestimonial = (index) => {
    if (formData.testimonials.length > 1) {
        formData.testimonials.splice(index, 1)
    }
}

const addGalleryPhoto = () => {
    formData.gallery_photos.push('')
}

const removeGalleryPhoto = (index) => {
    if (formData.gallery_photos.length > 1) {
        formData.gallery_photos.splice(index, 1)
    }
}

const addSocialLink = () => {
    formData.social_links.push({
        platform: 'facebook',
        url: '',
        label: ''
    })
}

const removeSocialLink = (index) => {
    if (formData.social_links.length > 1) {
        formData.social_links.splice(index, 1)
    }
}

const addCompanyStat = () => {
    formData.company_stats.push({
        icon: '',
        number: '',
        label: ''
    })
}

const removeCompanyStat = (index) => {
    if (formData.company_stats.length > 1) {
        formData.company_stats.splice(index, 1)
    }
}

// Error dialog handlers
const handleJsonResponseError = (errorString) => {
    try {
        // Extract JSON from the error message
        const jsonMatch = errorString.match(/\{.*\}/)
        if (jsonMatch) {
            const errorData = JSON.parse(jsonMatch[0])
            
            showErrorDialog.value = true
            errorTitle.value = 'Database Error'
            errorMessage.value = errorData.message || 'A database error occurred while processing your request.'
            errorDetails.value = errorData.message || errorString
        } else {
            // Fallback for Inertia errors
            showErrorDialog.value = true
            errorTitle.value = 'Server Communication Error'
            errorMessage.value = 'The server returned an unexpected response format. This usually indicates a server-side issue.'
            errorDetails.value = errorString
        }
    } catch (parseError) {
        // If JSON parsing fails, show generic error
        showErrorDialog.value = true
        errorTitle.value = 'Application Error'
        errorMessage.value = 'An unexpected error occurred. Please try again or contact support if the problem persists.'
        errorDetails.value = errorString
    }
}

const closeErrorDialog = () => {
    showErrorDialog.value = false
    errorTitle.value = ''
    errorMessage.value = ''
    errorDetails.value = null
}

const retrySubmission = () => {
    closeErrorDialog()
    showConfirmation()
}
</script>
