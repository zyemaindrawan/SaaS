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
                <div class="max-w-4xl mx-auto">
                    <form @submit.prevent="submitForm">
                        <div class="space-y-6">
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
                    <form @submit.prevent="submitForm" class="space-y-8">
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

                                <!-- Company Information -->
                                <div class="border border-gray-200 rounded-lg p-6 mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Company Information</h3>
                                    <div class="space-y-4">
                                        <!-- Company Name -->
                                        <div>
                                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Company Name *</label>
                                            <input
                                                id="company_name"
                                                v-model="form.content_data.company_name"
                                                type="text"
                                                required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Your company name"
                                            >
                                        </div>

                                        <!-- Company Tagline -->
                                        <div>
                                            <label for="company_tagline" class="block text-sm font-medium text-gray-700 mb-1">Company Tagline</label>
                                            <input
                                                id="company_tagline"
                                                v-model="form.content_data.company_tagline"
                                                type="text"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Your company tagline"
                                            >
                                        </div>

                                        <!-- Company Logo -->
                                        <div>
                                            <label for="company_logo" class="block text-sm font-medium text-gray-700 mb-1">Company Logo</label>
                                            <input
                                                id="company_logo"
                                                type="file"
                                                @change="handleFileUpload('company_logo', $event)"
                                                accept="image/*"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                            >
                                            <img 
                                                v-if="form.content_data.company_logo" 
                                                :src="form.content_data.company_logo" 
                                                class="mt-2 h-20 object-contain" 
                                                alt="Company Logo"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <!-- Website Design -->
                                <div class="border border-gray-200 rounded-lg p-6 mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Website Design</h3>
                                    <div class="space-y-4">
                                        <!-- Font Family -->
                                        <div>
                                            <label for="font_family" class="block text-sm font-medium text-gray-700 mb-1">Font Family *</label>
                                            <select
                                                id="font_family"
                                                v-model="form.content_data.font_family"
                                                required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                            >
                                                <option value="inter">Inter</option>
                                                <option value="poppins">Poppins</option>
                                                <option value="nunito">Nunito</option>
                                                <option value="roboto">Roboto</option>
                                            </select>
                                        </div>

                                        <!-- Colors -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-1">Primary Color *</label>
                                                <input
                                                    id="primary_color"
                                                    v-model="form.content_data.primary_color"
                                                    type="color"
                                                    required
                                                    class="w-full h-10 px-2 border border-gray-300 rounded-lg"
                                                >
                                            </div>
                                            <div>
                                                <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-1">Secondary Color *</label>
                                                <input
                                                    id="secondary_color"
                                                    v-model="form.content_data.secondary_color"
                                                    type="color"
                                                    required
                                                    class="w-full h-10 px-2 border border-gray-300 rounded-lg"
                                                >
                                            </div>
                                            <div>
                                                <label for="accent_color" class="block text-sm font-medium text-gray-700 mb-1">Accent Color *</label>
                                                <input
                                                    id="accent_color"
                                                    v-model="form.content_data.accent_color"
                                                    type="color"
                                                    required
                                                    class="w-full h-10 px-2 border border-gray-300 rounded-lg"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SEO Settings -->
                                <div class="border border-gray-200 rounded-lg p-6 mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>
                                    <div class="space-y-4">
                                        <!-- SEO Title -->
                                        <div>
                                            <label for="seo_title" class="block text-sm font-medium text-gray-700 mb-1">SEO Title *</label>
                                            <input
                                                id="seo_title"
                                                v-model="form.content_data.seo_title"
                                                type="text"
                                                required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Enter SEO title"
                                            >
                                            <p class="text-sm text-gray-500 mt-1">This will appear as the title in search engine results.</p>
                                        </div>

                                        <!-- SEO Description -->
                                        <div>
                                            <label for="seo_description" class="block text-sm font-medium text-gray-700 mb-1">SEO Description *</label>
                                            <textarea
                                                id="seo_description"
                                                v-model="form.content_data.seo_description"
                                                required
                                                rows="3"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Enter SEO description"
                                            ></textarea>
                                            <p class="text-sm text-gray-500 mt-1">Brief description that appears in search results.</p>
                                        </div>

                                        <!-- SEO Keywords -->
                                        <div>
                                            <label for="seo_keywords" class="block text-sm font-medium text-gray-700 mb-1">SEO Keywords</label>
                                            <input
                                                id="seo_keywords"
                                                v-model="form.content_data.seo_keywords"
                                                type="text"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Enter keywords separated by commas"
                                            >
                                        </div>

                                        <!-- Robots Meta -->
                                        <div>
                                            <label for="robots_meta" class="block text-sm font-medium text-gray-700 mb-1">Robots Meta</label>
                                            <select
                                                id="robots_meta"
                                                v-model="form.content_data.robots_meta"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                            >
                                                <option value="index,follow">Index, Follow</option>
                                                <option value="noindex,follow">No Index, Follow</option>
                                                <option value="index,nofollow">Index, No Follow</option>
                                                <option value="noindex,nofollow">No Index, No Follow</option>
                                            </select>
                                            <p class="text-sm text-gray-500 mt-1">Controls how search engines interact with your website.</p>
                                        </div>

                                        <!-- Analytics Section -->
                                        <div class="pt-4">
                                            <h4 class="text-md font-medium text-gray-800 mb-3">Analytics Integration</h4>
                                            
                                            <!-- Google Analytics -->
                                            <div class="mb-4">
                                                <label for="google_analytics" class="block text-sm font-medium text-gray-700 mb-1">Google Analytics ID</label>
                                                <input
                                                    id="google_analytics"
                                                    v-model="form.content_data.google_analytics"
                                                    type="text"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                    placeholder="e.g., UA-XXXXXXXXX-X or G-XXXXXXXXXX"
                                                >
                                            </div>

                                            <!-- Google Tag Manager -->
                                            <div>
                                                <label for="google_tag_manager" class="block text-sm font-medium text-gray-700 mb-1">Google Tag Manager ID</label>
                                                <input
                                                    id="google_tag_manager"
                                                    v-model="form.content_data.google_tag_manager"
                                                    type="text"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                    placeholder="e.g., GTM-XXXXXX"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- About Company Section -->
                                <div class="border border-gray-200 rounded-lg p-6 mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">About Company</h3>
                                    <div class="space-y-4">
                                        <!-- About Title -->
                                        <div>
                                            <label for="about_title" class="block text-sm font-medium text-gray-700 mb-1">About Title</label>
                                            <input
                                                id="about_title"
                                                v-model="form.content_data.about_title"
                                                type="text"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Enter about section title"
                                            >
                                        </div>

                                        <!-- About Content -->
                                        <div>
                                            <label for="about_content" class="block text-sm font-medium text-gray-700 mb-1">About Content</label>
                                            <textarea
                                                id="about_content"
                                                v-model="form.content_data.about_content"
                                                rows="4"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Enter company description"
                                            ></textarea>
                                        </div>

                                        <!-- About Image -->
                                        <div>
                                            <label for="about_image" class="block text-sm font-medium text-gray-700 mb-1">About Image</label>
                                            <input
                                                id="about_image"
                                                type="file"
                                                @change="handleFileUpload('about_image', $event)"
                                                accept="image/*"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                            >
                                            <img 
                                                v-if="form.content_data.about_image" 
                                                :src="form.content_data.about_image" 
                                                class="mt-2 h-32 object-contain" 
                                                alt="About Image"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <!-- Services Section -->
                                <div class="border border-gray-200 rounded-lg p-6 mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Services Section</h3>
                                    <div class="space-y-4">
                                        <!-- Services Title -->
                                        <div>
                                            <label for="services_title" class="block text-sm font-medium text-gray-700 mb-1">Services Title</label>
                                            <input
                                                id="services_title"
                                                v-model="form.content_data.services_title"
                                                type="text"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Enter services section title"
                                            >
                                        </div>

                                        <!-- Services Subtitle -->
                                        <div>
                                            <label for="services_subtitle" class="block text-sm font-medium text-gray-700 mb-1">Services Subtitle</label>
                                            <input
                                                id="services_subtitle"
                                                v-model="form.content_data.services_subtitle"
                                                type="text"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Enter services section subtitle"
                                            >
                                        </div>

                                        <!-- Services List -->
                                        <div class="space-y-4">
                                            <div class="flex justify-between items-center">
                                                <h4 class="text-md font-medium text-gray-800">Services List</h4>
                                                <button
                                                    type="button"
                                                    @click="addService"
                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150"
                                                >
                                                    <PlusIcon class="w-5 h-5 mr-2" />
                                                    Add Service
                                                </button>
                                            </div>

                                            <!-- Services Repeater -->
                                            <div v-if="form.content_data.services && form.content_data.services.length > 0" class="space-y-4">
                                                <div v-for="(service, index) in form.content_data.services" :key="index"
                                                    class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                                                    <div class="flex justify-between items-start mb-4">
                                                        <h5 class="text-sm font-medium text-gray-700">Service #{{ index + 1 }}</h5>
                                                        <button
                                                            type="button"
                                                            @click="removeArrayItem('services', index)"
                                                            class="text-red-500 hover:text-red-700"
                                                        >
                                                            <TrashIcon class="w-5 h-5" />
                                                        </button>
                                                    </div>
                                                    <div class="grid grid-cols-1 gap-4">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                                            <input
                                                                v-model="service.title"
                                                                type="text"
                                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Service title"
                                                            >
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                                            <textarea
                                                                v-model="service.description"
                                                                rows="2"
                                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Service description"
                                                            ></textarea>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Icon</label>
                                                            <input
                                                                v-model="service.icon"
                                                                type="text"
                                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Icon class or name"
                                                            >
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Link</label>
                                                            <input
                                                                v-model="service.link"
                                                                type="text"
                                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Service link"
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-else class="text-center py-4 text-gray-500">
                                                No services added yet. Click the button above to add a service.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                                                </div>
                        </div>
                    </form>

                    <!-- Testimonials Section -->
                                <div class="border border-gray-200 rounded-lg p-6 mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Testimonials Section</h3>
                                    <div class="space-y-4">
                                        <!-- Testimonials Title -->
                                        <div>
                                            <label for="testimonials_title" class="block text-sm font-medium text-gray-700 mb-1">Testimonials Title</label>
                                            <input
                                                id="testimonials_title"
                                                v-model="form.content_data.testimonials_title"
                                                type="text"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Enter testimonials section title"
                                            >
                                        </div>

                                        <!-- Testimonials List -->
                                        <div class="space-y-4">
                                            <div class="flex justify-between items-center">
                                                <h4 class="text-md font-medium text-gray-800">Client Testimonials</h4>
                                                <button
                                                    type="button"
                                                    @click="addTestimonial"
                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150"
                                                >
                                                    <PlusIcon class="w-5 h-5 mr-2" />
                                                    Add Testimonial
                                                </button>
                                            </div>

                                            <!-- Testimonials Repeater -->
                                            <div v-if="form.content_data.testimonials && form.content_data.testimonials.length > 0" class="space-y-4">
                                                <div v-for="(testimonial, index) in form.content_data.testimonials" :key="index"
                                                    class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                                                    <div class="flex justify-between items-start mb-4">
                                                        <h5 class="text-sm font-medium text-gray-700">Testimonial #{{ index + 1 }}</h5>
                                                        <button
                                                            type="button"
                                                            @click="removeArrayItem('testimonials', index)"
                                                            class="text-red-500 hover:text-red-700"
                                                        >
                                                            <TrashIcon class="w-5 h-5" />
                                                        </button>
                                                    </div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                                                            <input
                                                                v-model="testimonial.name"
                                                                type="text"
                                                                required
                                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Client name"
                                                            >
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                                                            <input
                                                                v-model="testimonial.position"
                                                                type="text"
                                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Client position/company"
                                                            >
                                                        </div>
                                                        <div class="md:col-span-2">
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Testimonial Content *</label>
                                                            <textarea
                                                                v-model="testimonial.content"
                                                                required
                                                                rows="3"
                                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Client testimonial"
                                                            ></textarea>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                                                            <select
                                                                v-model="testimonial.rating"
                                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                            >
                                                                <option value="5">5 Stars</option>
                                                                <option value="4">4 Stars</option>
                                                                <option value="3">3 Stars</option>
                                                                <option value="2">2 Stars</option>
                                                                <option value="1">1 Star</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                                                            <input
                                                                type="file"
                                                                @change="handleFileUpload('testimonials.' + index + '.photo', $event)"
                                                                accept="image/*"
                                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                            >
                                                            <img 
                                                                v-if="testimonial.photo" 
                                                                :src="testimonial.photo" 
                                                                class="mt-2 h-16 w-16 object-cover rounded-full" 
                                                                alt="Client Photo"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-else class="text-center py-4 text-gray-500">
                                                No testimonials added yet. Click the button above to add a testimonial.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Info Section -->
                                <div class="border border-gray-200 rounded-lg p-6 mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                                    <div class="space-y-4">
                                        <!-- Contact Title -->
                                        <div>
                                            <label for="contact_title" class="block text-sm font-medium text-gray-700 mb-1">Contact Section Title</label>
                                            <input
                                                id="contact_title"
                                                v-model="form.content_data.contact_title"
                                                type="text"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Enter contact section title"
                                            >
                                        </div>

                                        <!-- Contact Email -->
                                        <div>
                                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                            <input
                                                id="contact_email"
                                                v-model="form.content_data.contact_email"
                                                type="email"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Enter contact email"
                                            >
                                        </div>

                                        <!-- Contact Phone -->
                                        <div>
                                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                            <input
                                                id="contact_phone"
                                                v-model="form.content_data.contact_phone"
                                                type="tel"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Enter contact phone number"
                                            >
                                        </div>

                                        <!-- Contact Address -->
                                        <div>
                                            <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                            <textarea
                                                id="contact_address"
                                                v-model="form.content_data.contact_address"
                                                rows="3"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Enter contact address"
                                            ></textarea>
                                        </div>

                                        <!-- WhatsApp Integration -->
                                        <div class="pt-4 border-t border-gray-200">
                                            <div class="flex items-center justify-between mb-4">
                                                <h4 class="text-md font-medium text-gray-800">WhatsApp Integration</h4>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input 
                                                        type="checkbox" 
                                                        v-model="form.content_data.whatsapp_enabled"
                                                        class="sr-only peer"
                                                    >
                                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                    <span class="ml-3 text-sm font-medium text-gray-700">Enable WhatsApp</span>
                                                </label>
                                            </div>

                                            <div v-if="form.content_data.whatsapp_enabled" class="space-y-4">
                                                <!-- WhatsApp Number -->
                                                <div>
                                                    <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Number *</label>
                                                    <input
                                                        id="whatsapp_number"
                                                        v-model="form.content_data.whatsapp_number"
                                                        type="text"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                        placeholder="Enter WhatsApp number (e.g., 628989899143)"
                                                    >
                                                </div>

                                                <!-- WhatsApp Position -->
                                                <div>
                                                    <label for="whatsapp_position" class="block text-sm font-medium text-gray-700 mb-1">Button Position</label>
                                                    <select
                                                        id="whatsapp_position"
                                                        v-model="form.content_data.whatsapp_position"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                    >
                                                        <option value="bottom-left">Bottom Left</option>
                                                        <option value="bottom-right">Bottom Right</option>
                                                    </select>
                                                </div>

                                                <!-- WhatsApp Color -->
                                                <div>
                                                    <label for="whatsapp_color" class="block text-sm font-medium text-gray-700 mb-1">Button Color</label>
                                                    <input
                                                        id="whatsapp_color"
                                                        v-model="form.content_data.whatsapp_color"
                                                        type="color"
                                                        class="w-full h-10 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                    >
                                                </div>

                                                <!-- WhatsApp Message -->
                                                <div>
                                                    <label for="whatsapp_message" class="block text-sm font-medium text-gray-700 mb-1">Default Message</label>
                                                    <textarea
                                                        id="whatsapp_message"
                                                        v-model="form.content_data.whatsapp_message"
                                                        rows="2"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                        placeholder="Enter default message for WhatsApp"
                                                    ></textarea>
                                                </div>

                                                <!-- WhatsApp Greeting -->
                                                <div>
                                                    <label for="whatsapp_greeting_text" class="block text-sm font-medium text-gray-700 mb-1">Greeting Text</label>
                                                    <textarea
                                                        id="whatsapp_greeting_text"
                                                        v-model="form.content_data.whatsapp_greeting_text"
                                                        rows="2"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                        placeholder="Enter greeting text for WhatsApp widget"
                                                    ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Company Stats Section -->
                                <div class="border border-gray-200 rounded-lg p-6 mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Company Stats</h3>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center">
                                            <h4 class="text-md font-medium text-gray-800">Statistics</h4>
                                            <button
                                                type="button"
                                                @click="addCompanyStat"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150"
                                            >
                                                <PlusIcon class="w-5 h-5 mr-2" />
                                                Add Statistic
                                            </button>
                                        </div>

                                        <!-- Stats Repeater -->
                                        <div v-if="form.content_data.company_stats && form.content_data.company_stats.length > 0" class="space-y-4">
                                            <div v-for="(stat, index) in form.content_data.company_stats" :key="index"
                                                class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                                                <div class="flex justify-between items-start mb-4">
                                                    <h5 class="text-sm font-medium text-gray-700">Statistic #{{ index + 1 }}</h5>
                                                    <button
                                                        type="button"
                                                        @click="removeArrayItem('company_stats', index)"
                                                        class="text-red-500 hover:text-red-700"
                                                    >
                                                        <TrashIcon class="w-5 h-5" />
                                                    </button>
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Icon *</label>
                                                        <select
                                                            v-model="stat.icon"
                                                            required
                                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                        >
                                                            <option value="fas fa-users">Users</option>
                                                            <option value="fas fa-project-diagram">Projects</option>
                                                            <option value="fas fa-calendar-alt">Calendar</option>
                                                            <option value="fas fa-user-tie">Professional</option>
                                                            <option value="fas fa-star">Star</option>
                                                            <option value="fas fa-trophy">Trophy</option>
                                                            <option value="fas fa-certificate">Certificate</option>
                                                            <option value="fas fa-tasks">Tasks</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Number *</label>
                                                        <input
                                                            v-model="stat.number"
                                                            type="text"
                                                            required
                                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                            placeholder="e.g., 500+"
                                                        >
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Label *</label>
                                                        <input
                                                            v-model="stat.label"
                                                            type="text"
                                                            required
                                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                            placeholder="e.g., Happy Clients"
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else class="text-center py-4 text-gray-500">
                                            No statistics added yet. Click the button above to add a statistic.
                                        </div>
                                    </div>
                                </div>

                                <!-- Gallery Section -->
                                <div class="border border-gray-200 rounded-lg p-6 mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Gallery</h3>
                                    <div class="space-y-4">
                                        <!-- Gallery Title -->
                                        <div>
                                            <label for="gallery_title" class="block text-sm font-medium text-gray-700 mb-1">Gallery Title</label>
                                            <input
                                                id="gallery_title"
                                                v-model="form.content_data.gallery_title"
                                                type="text"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Enter gallery title"
                                            >
                                        </div>

                                        <!-- Gallery Photos -->
                                        <div class="space-y-4">
                                            <div class="flex justify-between items-center">
                                                <h4 class="text-md font-medium text-gray-800">Gallery Photos</h4>
                                                <button
                                                    type="button"
                                                    @click="addGalleryPhoto"
                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150"
                                                >
                                                    <PlusIcon class="w-5 h-5 mr-2" />
                                                    Add Photo
                                                </button>
                                            </div>

                                            <!-- Gallery Grid -->
                                            <div v-if="form.content_data.gallery_photos && form.content_data.gallery_photos.length > 0" 
                                                class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                <div v-for="(photo, index) in form.content_data.gallery_photos" :key="index"
                                                    class="relative group">
                                                    <img 
                                                        :src="photo" 
                                                        class="w-full h-48 object-cover rounded-lg" 
                                                        :alt="'Gallery photo ' + (index + 1)"
                                                    >
                                                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <button
                                                            type="button"
                                                            @click="removeArrayItem('gallery_photos', index)"
                                                            class="text-white bg-red-600 hover:bg-red-700 p-2 rounded-full"
                                                        >
                                                            <TrashIcon class="w-5 h-5" />
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Add New Photo</label>
                                                <input
                                                    type="file"
                                                    @change="handleGalleryPhotoUpload($event)"
                                                    accept="image/*"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                >
                                            </div>
                                            <div v-if="!form.content_data.gallery_photos?.length" class="text-center py-4 text-gray-500">
                                                No photos added yet. Click the button above to add photos.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Social Links Section -->
                                <div class="border border-gray-200 rounded-lg p-6 mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Social Media Links</h3>
                                    <div class="space-y-4">
                                        <!-- Social Links List -->
                                        <div class="space-y-4">
                                            <div class="flex justify-between items-center">
                                                <h4 class="text-md font-medium text-gray-800">Social Media Profiles</h4>
                                                <button
                                                    type="button"
                                                    @click="addSocialLink"
                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150"
                                                >
                                                    <PlusIcon class="w-5 h-5 mr-2" />
                                                    Add Social Link
                                                </button>
                                            </div>

                                            <!-- Social Links Repeater -->
                                            <div v-if="form.content_data.social_links && form.content_data.social_links.length > 0" class="space-y-4">
                                                <div v-for="(link, index) in form.content_data.social_links" :key="index"
                                                    class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                                                    <div class="flex justify-between items-start mb-4">
                                                        <h5 class="text-sm font-medium text-gray-700">Social Link #{{ index + 1 }}</h5>
                                                        <button
                                                            type="button"
                                                            @click="removeArrayItem('social_links', index)"
                                                            class="text-red-500 hover:text-red-700"
                                                        >
                                                            <TrashIcon class="w-5 h-5" />
                                                        </button>
                                                    </div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Platform *</label>
                                                            <select
                                                                v-model="link.platform"
                                                                required
                                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                            >
                                                                <option value="facebook">Facebook</option>
                                                                <option value="instagram">Instagram</option>
                                                                <option value="twitter">Twitter</option>
                                                                <option value="linkedin">LinkedIn</option>
                                                                <option value="youtube">YouTube</option>
                                                                <option value="tiktok">TikTok</option>
                                                                <option value="pinterest">Pinterest</option>
                                                                <option value="whatsapp">WhatsApp</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Label *</label>
                                                            <input
                                                                v-model="link.label"
                                                                type="text"
                                                                required
                                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Display label"
                                                            >
                                                        </div>
                                                        <div class="md:col-span-2">
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">URL *</label>
                                                            <input
                                                                v-model="link.url"
                                                                type="url"
                                                                required
                                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="https://..."
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-else class="text-center py-4 text-gray-500">
                                                No social links added yet. Click the button above to add a social media link.
                                            </div>
                                        </div>
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

                                    <Link href="/dashboard"
                                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 px-8 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-center"
                                    >
                                        Back to Dashboard
                                    </Link>
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
    DocumentDuplicateIcon,
    PlusIcon,
    TrashIcon
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

const addGalleryPhoto = () => {
    if (!Array.isArray(form.content_data.gallery_photos)) {
        form.content_data.gallery_photos = []
    }
    // Open file input programmatically
    const fileInput = document.querySelector('input[type="file"]')
    if (fileInput) {
        fileInput.click()
    }
}

const handleGalleryPhotoUpload = async (event) => {
    const file = event.target.files[0]
    if (file) {
        if (file.size > 2 * 1024 * 1024) { // 2MB limit
            alert('File size should not exceed 2MB')
            return
        }

        try {
            const base64 = await fileToBase64(file)
            if (!Array.isArray(form.content_data.gallery_photos)) {
                form.content_data.gallery_photos = []
            }
            form.content_data.gallery_photos.push(base64)
        } catch (error) {
            console.error('Error processing file:', error)
            alert('Error processing file. Please try again.')
        }
    }
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
