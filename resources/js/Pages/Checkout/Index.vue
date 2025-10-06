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
            :show-retry="showRetryButton"
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
                        Sesuaikan Konten Website
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
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Form Section -->
                <div class="lg:col-span-2">
                    <form @submit.prevent="showConfirmation" class="space-y-8">
                        <!-- Company Information -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Company Information</h3>
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel for="company_name" value="Company Name" />
                                        <TextInput
                                            id="company_name"
                                            v-model="formData.company_name"
                                            type="text"
                                            class="mt-1 block w-full"
                                            placeholder="PT. Your Company Name"
                                            required
                                        />
                                        <InputError :message="errors.company_name" class="mt-2" />
                                    </div>
                                    <div>
                                        <InputLabel for="company_tagline" value="Company Tagline" />
                                        <TextInput
                                            id="company_tagline"
                                            v-model="formData.company_tagline"
                                            type="text"
                                            class="mt-1 block w-full"
                                            placeholder="Your company tagline"
                                            required
                                        />
                                        <InputError :message="errors.company_tagline" class="mt-2" />
                                    </div>
                                </div>
                                
                                <div>
                                    <InputLabel for="company_logo" value="Company Logo" />
                                    <input
                                        type="file"
                                        id="company_logo"
                                        @change="handleCompanyLogoUpload"
                                        accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    />
                                    <InputError :message="errors.company_logo" class="mt-2" />
                                    <div v-if="imagePreviews.company_logo" class="mt-2">
                                        <img :src="imagePreviews.company_logo" alt="Company Logo Preview" class="h-16 w-auto object-contain" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="website_name" value="Website Name" />
                                    <TextInput
                                        id="website_name"
                                        v-model="formData.website_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="Your Website Name"
                                        required
                                    />
                                    <InputError :message="errors.website_name" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- SEO Settings -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>
                            <div class="space-y-6">
                                <div>
                                    <InputLabel for="seo_title" value="SEO Title" />
                                    <TextInput
                                        id="seo_title"
                                        v-model="formData.seo_title"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="Website title for search engines"
                                        required
                                    />
                                    <InputError :message="errors.seo_title" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500">{{ formData.seo_title?.length || 0 }}/60 characters</p>
                                </div>
                                <div>
                                    <InputLabel for="seo_description" value="SEO Description" />
                                    <textarea
                                        id="seo_description"
                                        v-model="formData.seo_description"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        rows="3"
                                        placeholder="Website description for search engines"
                                        required
                                    ></textarea>
                                    <InputError :message="errors.seo_description" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500">{{ formData.seo_description?.length || 0 }}/160 characters</p>
                                </div>
                                <div>
                                    <InputLabel for="seo_keywords" value="SEO Keywords" />
                                    <textarea
                                        id="seo_keywords"
                                        v-model="formData.seo_keywords"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        rows="2"
                                        placeholder="Comma-separated keywords for search engines"
                                        required
                                    ></textarea>
                                    <InputError :message="errors.seo_keywords" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="robots_meta" value="Robots Meta Tag" />
                                    <select
                                        id="robots_meta"
                                        v-model="formData.robots_meta"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        required
                                    >
                                        <option value="index,follow">Index, Follow</option>
                                        <option value="noindex,follow">No Index, Follow</option>
                                        <option value="index,nofollow">Index, No Follow</option>
                                        <option value="noindex,nofollow">No Index, No Follow</option>
                                    </select>
                                </div>
                                <div>
                                    <InputLabel for="favicon" value="Favicon (32x32 .ico)" />
                                    <input
                                        type="file"
                                        id="favicon"
                                        @change="handleFaviconUpload"
                                        accept=".ico"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    />
                                    <InputError :message="errors.favicon" class="mt-2" />
                                    <div v-if="imagePreviews.favicon || resolveMediaPath(formData.favicon)" class="mt-2">
                                        <img :src="imagePreviews.favicon || resolveMediaPath(formData.favicon)" alt="Favicon Preview" class="h-12 w-12 object-contain border border-gray-200 rounded" />
                                    </div>
                                </div>
                                <div>
                                    <InputLabel for="og_image" value="Social Media Share Image" />
                                    <input
                                        type="file"
                                        id="og_image"
                                        @change="handleOgImageUpload"
                                        accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    />
                                    <p class="mt-1 text-sm text-gray-500">Recommended size: 1200x630 pixels</p>
                                    <InputError :message="errors.og_image" class="mt-2" />
                                    <div v-if="imagePreviews.og_image || resolveMediaPath(formData.og_image)" class="mt-2">
                                        <img :src="imagePreviews.og_image || resolveMediaPath(formData.og_image)" alt="Social Share Image Preview" class="w-full max-w-md rounded-md border border-gray-200 object-cover" />
                                    </div>
                                </div>
                                <div>
                                    <InputLabel for="google_analytics" value="Google Analytics ID" />
                                    <TextInput
                                        id="google_analytics"
                                        v-model="formData.google_analytics"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="G-XXXXXXXXXX"
                                    />
                                </div>
                                <div>
                                    <InputLabel for="google_tag_manager" value="Google Tag Manager ID" />
                                    <TextInput
                                        id="google_tag_manager"
                                        v-model="formData.google_tag_manager"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="GTM-XXXXXX"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Hero Section -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Hero Section</h3>
                            <div class="space-y-6">
                                <div>
                                    <InputLabel for="hero_title" value="Hero Title" />
                                    <TextInput
                                        id="hero_title"
                                        v-model="formData.hero_title"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="Main headline for your website"
                                        required
                                    />
                                </div>
                                <div>
                                    <InputLabel for="hero_subtitle" value="Hero Subtitle" />
                                    <textarea
                                        id="hero_subtitle"
                                        v-model="formData.hero_subtitle"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        rows="2"
                                        placeholder="Supporting text for your headline"
                                        required
                                    ></textarea>
                                </div>
                                <div>
                                    <InputLabel for="hero_background" value="Hero Background Image" />
                                    <input
                                        type="file"
                                        id="hero_background"
                                        @change="handleHeroBackgroundUpload"
                                        accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    />
                                    <p class="mt-1 text-sm text-gray-500">Recommended size: 1920x1080 pixels</p>
                                    <div v-if="imagePreviews.hero_background || resolveMediaPath(formData.hero_background)" class="mt-2">
                                        <img :src="imagePreviews.hero_background || resolveMediaPath(formData.hero_background)" alt="Hero Background Preview" class="w-full max-w-3xl rounded-md border border-gray-200 object-cover" />
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel for="hero_cta_text" value="CTA Button Text" />
                                        <TextInput
                                            id="hero_cta_text"
                                            v-model="formData.hero_cta_text"
                                            type="text"
                                            class="mt-1 block w-full"
                                            placeholder="Get Started"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <InputLabel for="hero_cta_link" value="CTA Button Link" />
                                        <TextInput
                                            id="hero_cta_link"
                                            v-model="formData.hero_cta_link"
                                            type="text"
                                            class="mt-1 block w-full"
                                            placeholder="#contact"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="contact_email" value="Contact Email" />
                                    <TextInput
                                        id="contact_email"
                                        v-model="formData.contact_email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        placeholder="contact@company.com"
                                        required
                                    />
                                    <InputError :message="errors.contact_email" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="contact_phone" value="Contact Phone" />
                                    <TextInput
                                        id="contact_phone"
                                        v-model="formData.contact_phone"
                                        type="tel"
                                        class="mt-1 block w-full"
                                        placeholder="+62 812-3456-7890"
                                        required
                                    />
                                    <InputError :message="errors.contact_phone" class="mt-2" />
                                </div>
                                <div class="md:col-span-2">
                                    <InputLabel for="contact_address" value="Complete Address" />
                                    <textarea
                                        id="contact_address"
                                        v-model="formData.contact_address"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        rows="3"
                                        placeholder="Your complete business address"
                                        required
                                    ></textarea>
                                    <InputError :message="errors.contact_address" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Services (Repeater) -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <div class="space-y-6">
                                <div>
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-medium text-gray-900">Layanan</h3>
                                        <TextInput
                                            v-model="formData.services_title"
                                            type="text"
                                            class="w-64"
                                            placeholder="Judul Seksi Layanan"
                                        />
                                    </div>
                                    <textarea
                                        v-model="formData.services_subtitle"
                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        rows="2"
                                        placeholder="Deskripsi singkat tentang layanan yang Anda tawarkan"
                                    ></textarea>
                                </div>

                                <div class="space-y-4">
                                    <div v-for="(service, index) in formData.services" :key="index" 
                                        class="relative border border-gray-200 rounded-lg p-6 bg-white hover:shadow-md transition-shadow duration-200">
                                        <!-- Header dengan nomor dan tombol hapus -->
                                        <div class="absolute right-4 top-4">
                                            <button
                                                type="button"
                                                @click="removeService(index)"
                                                v-if="formData.services.length > 1"
                                                class="inline-flex items-center text-red-600 hover:text-red-800 focus:outline-none"
                                                title="Hapus Layanan"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <InputLabel :for="'service_title_' + index" value="Nama Layanan" />
                                                <TextInput
                                                    :id="'service_title_' + index"
                                                    v-model="service.title"
                                                    type="text"
                                                    class="mt-1 block w-full"
                                                    placeholder="Contoh: Konsultasi Bisnis"
                                                    required
                                                />
                                                <InputError :message="errors['services.' + index + '.title']" class="mt-1" />
                                            </div>

                                            <div>
                                                <InputLabel :for="'service_link_' + index" value="Link Layanan" />
                                                <TextInput
                                                    :id="'service_link_' + index"
                                                    v-model="service.link"
                                                    type="text"
                                                    class="mt-1 block w-full"
                                                    placeholder="Contoh: #konsultasi-bisnis"
                                                    required
                                                />
                                                <InputError :message="errors['services.' + index + '.link']" class="mt-1" />
                                            </div>

                                            <div>
                                                <InputLabel :for="'service_icon_' + index" value="Icon (Font Awesome)" />
                                                <div class="mt-1 relative">
                                                    <TextInput
                                                        :id="'service_icon_' + index"
                                                        v-model="service.icon"
                                                        type="text"
                                                        class="block w-full pr-10"
                                                        placeholder="fas fa-chart-line"
                                                        required
                                                    />
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                        <i :class="service.icon" class="text-gray-400"></i>
                                                    </div>
                                                </div>
                                                <InputError :message="errors['services.' + index + '.icon']" class="mt-1" />
                                            </div>

                                            <div>
                                                <InputLabel :for="'service_description_' + index" value="Deskripsi Layanan" />
                                                <textarea
                                                    :id="'service_description_' + index"
                                                    v-model="service.description"
                                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                    rows="3"
                                                    placeholder="Jelaskan layanan Anda secara detail"
                                                    required
                                                ></textarea>
                                                <InputError :message="errors['services.' + index + '.description']" class="mt-1" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tombol Tambah Layanan -->
                                    <button
                                        type="button"
                                        @click="addService"
                                        v-if="formData.services.length < 10"
                                        class="w-full flex items-center justify-center px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg text-sm font-medium text-gray-600 hover:border-gray-400 hover:bg-gray-50 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
                                    >
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Tambah Layanan Baru
                                    </button>
                        </div>
                    </div>
                </div>

                <!-- Dealer Inventory -->
                <div v-if="isDealer" class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Mobil</h3>

                    <div class="space-y-4">
                        <div
                            v-for="(car, index) in formData.cars"
                            :key="`car-${index}`"
                            class="relative border border-gray-200 rounded-lg p-5"
                        >
                            <div class="absolute right-4 top-4">
                                <button
                                    type="button"
                                    @click="removeCar(index)"
                                    v-if="formData.cars.length > 0"
                                    class="inline-flex items-center text-red-600 hover:text-red-800 focus:outline-none"
                                    title="Hapus mobil"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span class="sr-only">Remove</span>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel :for="`car_name_${index}`" value="Nama Mobil" />
                                    <TextInput
                                        :id="`car_name_${index}`"
                                        v-model="car.name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="Contoh: Honda CR-V 1.5 Prestige"
                                        required
                                    />
                                    <InputError :message="errors[`cars.${index}.name`]" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel :for="`car_type_${index}`" value="Jenis" />
                                    <TextInput
                                        :id="`car_type_${index}`"
                                        v-model="car.type"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="baru / bekas"
                                    />
                                    <InputError :message="errors[`cars.${index}.type`]" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel :for="`car_year_${index}`" value="Tahun" />
                                    <TextInput
                                        :id="`car_year_${index}`"
                                        v-model="car.year"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="2025"
                                    />
                                    <InputError :message="errors[`cars.${index}.year`]" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel :for="`car_price_${index}`" value="Harga" />
                                    <TextInput
                                        :id="`car_price_${index}`"
                                        v-model="car.price"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="Rp 399.000.000"
                                    />
                                    <InputError :message="errors[`cars.${index}.price`]" class="mt-1" />
                                </div>

                                <div class="md:col-span-2">
                                    <InputLabel :for="`car_image_${index}`" value="Foto Mobil" />
                                    <input
                                        :id="`car_image_${index}`"
                                        type="file"
                                        accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        @change="(event) => handleCarImageUpload(event, index)"
                                    />
                                    <InputError :message="errors[`cars.${index}.image`]" class="mt-1" />
                                    <div v-if="getCarImageUrl(car)" class="mt-2">
                                        <img :src="getCarImageUrl(car)" alt="Preview mobil" class="w-full max-w-sm rounded-md border border-gray-200" />
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <InputLabel :for="`car_features_${index}`" value="Fitur Unggulan" />
                                    <textarea
                                        :id="`car_features_${index}`"
                                        v-model="car.features"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        rows="3"
                                        placeholder="Turbo engine, Honda Sensing, Panoramic roof..."
                                    ></textarea>
                                    <InputError :message="errors[`cars.${index}.features`]" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel :for="`car_transmission_${index}`" value="Transmisi" />
                                    <TextInput
                                        :id="`car_transmission_${index}`"
                                        v-model="car.transmission"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="matic / manual"
                                    />
                                    <InputError :message="errors[`cars.${index}.transmission`]" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel :for="`car_whatsapp_${index}`" value="Nomor Sales (WhatsApp)" />
                                    <TextInput
                                        :id="`car_whatsapp_${index}`"
                                        v-model="car.whatsapp_sales"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="628123456789"
                                    />
                                    <InputError :message="errors[`cars.${index}.whatsapp_sales`]" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <div v-if="formData.cars.length === 0" class="text-sm text-gray-500 italic">
                            Belum ada mobil yang ditambahkan. Klik “Tambah Mobil” untuk mulai mengisi data.
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="addCar"
                        v-if="formData.cars.length < 50"
                        class="w-full flex items-center justify-center px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg text-sm font-medium text-gray-600 hover:border-gray-400 hover:bg-gray-50 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Mobil
                    </button>
                </div>

                <!-- Dealer Promo -->
                <div v-if="isDealer" class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Promo Dealer</h3>
                    <div class="space-y-6">
                        <div>
                            <InputLabel for="promo_banner" value="Banner Promo" />
                            <input
                                id="promo_banner"
                                type="file"
                                accept="image/*"
                                @change="handlePromoBannerUpload"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            />
                            <InputError :message="errors.promo_banner" class="mt-1" />
                            <div v-if="imagePreviews.promo_banner || resolveMediaPath(formData.promo_banner)" class="mt-2">
                                <img :src="imagePreviews.promo_banner || resolveMediaPath(formData.promo_banner)" alt="Promo banner" class="w-full max-w-xl rounded-md border border-gray-200" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="promo_title" value="Judul Promo" />
                            <TextInput
                                id="promo_title"
                                v-model="formData.promo_title"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="Promo Oktober 2025 – Diskon Sampai Rp 50 Juta!"
                            />
                            <InputError :message="errors.promo_title" class="mt-1" />
                        </div>

                        <div>
                            <InputLabel for="promo_description" value="Deskripsi Promo (boleh HTML)" />
                            <textarea
                                id="promo_description"
                                v-model="formData.promo_description"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                rows="4"
                                placeholder="<ul><li>Diskon Rp 50 juta...</li></ul>"
                            ></textarea>
                            <p class="mt-1 text-sm text-gray-500">Anda dapat menggunakan HTML sederhana seperti &lt;ul&gt; dan &lt;li&gt;.</p>
                            <InputError :message="errors.promo_description" class="mt-1" />
                        </div>
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Social Media Links</h3>
                            <div class="space-y-4">
                                <div
                                    v-for="(link, index) in formData.social_links"
                                    :key="index"
                                    class="relative border border-gray-200 rounded-lg p-4"
                                >
                                    <div class="absolute right-4 top-4">
                                        <button
                                            type="button"
                                            @click="removeSocialLink(index)"
                                            v-if="formData.social_links.length > 0"
                                            class="inline-flex items-center text-red-600 hover:text-red-800 focus:outline-none"
                                            title="Hapus tautan"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span class="sr-only">Remove</span>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div>
                                            <InputLabel :for="'social_platform_' + index" value="Platform" />
                                            <select
                                                :id="'social_platform_' + index"
                                                v-model="link.platform"
                                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                required
                                            >
                                                <option value="">Select Platform</option>
                                                <option value="facebook">Facebook</option>
                                                <option value="instagram">Instagram</option>
                                                <option value="twitter">Twitter</option>
                                                <option value="linkedin">LinkedIn</option>
                                                <option value="youtube">YouTube</option>
                                                <option value="tiktok">TikTok</option>
                                            </select>
                                        </div>
                                        <div>
                                            <InputLabel :for="'social_url_' + index" value="URL" />
                                            <TextInput
                                                :id="'social_url_' + index"
                                                v-model="link.url"
                                                type="url"
                                                class="mt-1 block w-full"
                                                placeholder="https://facebook.com/yourpage"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <InputLabel :for="'social_label_' + index" value="Display Label" />
                                            <TextInput
                                                :id="'social_label_' + index"
                                                v-model="link.label"
                                                type="text"
                                                class="mt-1 block w-full"
                                                placeholder="Follow us on Facebook"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    @click="addSocialLink"
                                    v-if="formData.social_links.length < 10"
                                    class="w-full flex items-center justify-center px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg text-sm font-medium text-gray-600 hover:border-gray-400 hover:bg-gray-50 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Social Link
                                </button>
                            </div>
                        </div>

                        <!-- Design Settings -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Design Settings</h3>
                            <div class="space-y-6">
                                <!-- Colors -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Colors</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <InputLabel for="primary_color" value="Primary Color" />
                                            <TextInput
                                                id="primary_color"
                                                v-model="formData.primary_color"
                                                type="color"
                                                class="mt-1 block w-full h-10"
                                                required
                                            />
                                            <InputError :message="errors.primary_color" class="mt-2" />
                                        </div>
                                        <div>
                                            <InputLabel for="secondary_color" value="Secondary Color" />
                                            <TextInput
                                                id="secondary_color"
                                                v-model="formData.secondary_color"
                                                type="color"
                                                class="mt-1 block w-full h-10"
                                                required
                                            />
                                            <InputError :message="errors.secondary_color" class="mt-2" />
                                        </div>
                                        <div>
                                            <InputLabel for="accent_color" value="Accent Color" />
                                            <TextInput
                                                id="accent_color"
                                                v-model="formData.accent_color"
                                                type="color"
                                                class="mt-1 block w-full h-10"
                                                required
                                            />
                                            <InputError :message="errors.accent_color" class="mt-2" />
                                        </div>
                                        <div>
                                            <InputLabel for="whatsapp_color" value="WhatsApp Button Color" />
                                            <TextInput
                                                id="whatsapp_color"
                                                v-model="formData.whatsapp_color"
                                                type="color"
                                                class="mt-1 block w-full h-10"
                                            />
                                            <InputError :message="errors.whatsapp_color" class="mt-2" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Typography -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Typography</h4>
                                    <div>
                                        <InputLabel for="font_family" value="Font Family" />
                                        <select
                                            id="font_family"
                                            v-model="formData.font_family"
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            required
                                        >
                                            <option value="inter">Inter</option>
                                            <option value="poppins">Poppins</option>
                                            <option value="nunito">Nunito</option>
                                            <option value="roboto">Roboto</option>
                                        </select>
                                        <InputError :message="errors.font_family" class="mt-2" />
                                    </div>
                                </div>

                                <!-- Styling -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Styling</h4>
                                    <div>
                                        <InputLabel for="border_radius" value="Border Radius Style" />
                                        <select
                                            id="border_radius"
                                            v-model="formData.border_radius"
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            required
                                        >
                                            <option value="none">None</option>
                                            <option value="small">Small</option>
                                            <option value="medium">Medium</option>
                                            <option value="large">Large</option>
                                        </select>
                                        <InputError :message="errors.border_radius" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Website Settings -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Website Settings</h3>
                            <div class="space-y-6">
                                <div>
                                    <InputLabel for="subdomain" value="Website Address" />
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <TextInput
                                            id="subdomain"
                                            v-model="formData.subdomain"
                                            type="text"
                                            class="flex-1 rounded-none rounded-l-md"
                                            placeholder="yourwebsite"
                                            required
                                        />
                                        <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                            .oursite.com
                                        </span>
                                    </div>
                                    <InputError :message="errors.subdomain" class="mt-2" />
                                </div>

                                <!-- WhatsApp Settings -->
                                <div class="border-t pt-6">
                                    <h4 class="text-sm font-medium text-gray-900 mb-4">WhatsApp Integration</h4>
                                    <div class="space-y-4">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input
                                                    id="whatsapp_enabled"
                                                    v-model="formData.whatsapp_enabled"
                                                    type="checkbox"
                                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                                />
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="whatsapp_enabled" class="font-medium text-gray-700">Enable WhatsApp Button</label>
                                                <p class="text-gray-500">Show a floating WhatsApp button on your website</p>
                                            </div>
                                        </div>

                                        <div v-if="formData.whatsapp_enabled">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <InputLabel for="whatsapp_number" value="WhatsApp Number" />
                                                    <TextInput
                                                        id="whatsapp_number"
                                                        v-model="formData.whatsapp_number"
                                                        type="text"
                                                        class="mt-1 block w-full"
                                                        placeholder="628123456789"
                                                        required
                                                    />
                                                    <p class="mt-1 text-sm text-gray-500">Include country code without +</p>
                                                </div>
                                                <div>
                                                    <InputLabel for="whatsapp_position" value="Button Position" />
                                                    <select
                                                        id="whatsapp_position"
                                                        v-model="formData.whatsapp_position"
                                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                        required
                                                    >
                                                        <option value="bottom-right">Bottom Right</option>
                                                        <option value="bottom-left">Bottom Left</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="whatsapp_message" value="Default Message Template" />
                                                <textarea
                                                    id="whatsapp_message"
                                                    v-model="formData.whatsapp_message"
                                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                    rows="3"
                                                    placeholder="Hi {company_name}, I'm interested in your services..."
                                                    required
                                                ></textarea>
                                                <p class="mt-1 text-sm text-gray-500">Use {company_name} to insert the company name</p>
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="whatsapp_greeting_text" value="Greeting Text (Optional)" />
                                                <TextInput
                                                    id="whatsapp_greeting_text"
                                                    v-model="formData.whatsapp_greeting_text"
                                                    type="text"
                                                    class="mt-1 block w-full"
                                                    placeholder="👋 Hi! How can we help you?"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Company Stats Repeater -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Company Statistics</h3>
                            <div class="space-y-4">
                                <div
                                    v-for="(stat, index) in formData.company_stats"
                                    :key="index"
                                    class="relative border border-gray-200 rounded-lg p-4"
                                >
                                    <div class="absolute right-4 top-4">
                                        <button
                                            type="button"
                                            @click="removeCompanyStat(index)"
                                            v-if="formData.company_stats.length > 1"
                                            class="inline-flex items-center text-red-600 hover:text-red-800 focus:outline-none"
                                            title="Hapus statistik"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span class="sr-only">Remove</span>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <InputLabel :for="'stat_icon_' + index" value="Icon Class" />
                                            <TextInput
                                                :id="'stat_icon_' + index"
                                                v-model="stat.icon"
                                                type="text"
                                                class="mt-1 block w-full"
                                                placeholder="fas fa-users"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <InputLabel :for="'stat_number_' + index" value="Number/Value" />
                                            <TextInput
                                                :id="'stat_number_' + index"
                                                v-model="stat.number"
                                                type="text"
                                                class="mt-1 block w-full"
                                                placeholder="500+"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <InputLabel :for="'stat_label_' + index" value="Label" />
                                            <TextInput
                                                :id="'stat_label_' + index"
                                                v-model="stat.label"
                                                type="text"
                                                class="mt-1 block w-full"
                                                placeholder="Happy Clients"
                                                required
                                            />
                                        </div>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    @click="addCompanyStat"
                                    v-if="formData.company_stats.length < 6"
                                    class="w-full flex items-center justify-center px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg text-sm font-medium text-gray-600 hover:border-gray-400 hover:bg-gray-50 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Statistic
                                </button>
                            </div>
                        </div>

                        <!-- Testimonials Repeater -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-medium text-gray-900">Testimonials</h3>
                                    <TextInput
                                        v-model="formData.testimonials_title"
                                        type="text"
                                        class="inline-block w-64"
                                        placeholder="Section Title"
                                    />
                                </div>

                                <div class="space-y-4">
                                    <div v-for="(testimonial, index) in formData.testimonials" :key="index" 
                                class="relative border border-gray-200 rounded-lg p-6 bg-white hover:shadow-md transition-shadow duration-200">
                                <!-- Header dengan nomor dan tombol hapus -->
                                <div class="absolute right-4 top-4">
                                    <button
                                        type="button"
                                        @click="removeTestimonial(index)"
                                        v-if="formData.testimonials.length > 1"
                                        class="inline-flex items-center text-red-600 hover:text-red-800 focus:outline-none"
                                        title="Hapus Testimoni"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Client Photo dan Rating -->
                                <div class="flex items-center space-x-4 mb-6">
                                    <div class="relative">
                                        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center border-2 border-gray-200 overflow-hidden">
                                            <img v-if="testimonialPreviews[index]" :src="testimonialPreviews[index]" alt="Client Photo" class="w-full h-full object-cover" />
                                            <svg v-else class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <input
                                            :id="'testimonial_photo_' + index"
                                            type="file"
                                            @change="(e) => handleTestimonialPhotoUpload(e, index)"
                                            accept="image/*"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                            title="Upload foto klien"
                                        />
                                    </div>

                                    <div class="flex-1">
                                        <InputLabel :for="'testimonial_rating_' + index" value="Rating" class="mb-1" />
                                        <div class="flex items-center space-x-1">
                                            <template v-for="star in 5" :key="star">
                                                <button 
                                                    type="button"
                                                    @click="testimonial.rating = String(star)"
                                                    class="focus:outline-none"
                                                >
                                                    <svg 
                                                        class="w-6 h-6" 
                                                        :class="Number(testimonial.rating) >= star ? 'text-yellow-400' : 'text-gray-300'"
                                                        fill="currentColor" 
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Client Info dan Testimonial -->
                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <InputLabel :for="'testimonial_name_' + index" value="Nama Klien" />
                                            <TextInput
                                                :id="'testimonial_name_' + index"
                                                v-model="testimonial.name"
                                                type="text"
                                                class="mt-1 block w-full"
                                                placeholder="Nama lengkap klien"
                                                required
                                            />
                                            <InputError :message="errors['testimonials.' + index + '.name']" class="mt-1" />
                                        </div>

                                        <div>
                                            <InputLabel :for="'testimonial_position_' + index" value="Jabatan/Perusahaan" />
                                            <TextInput
                                                :id="'testimonial_position_' + index"
                                                v-model="testimonial.position"
                                                type="text"
                                                class="mt-1 block w-full"
                                                placeholder="CEO, Nama Perusahaan"
                                                required
                                            />
                                            <InputError :message="errors['testimonials.' + index + '.position']" class="mt-1" />
                                        </div>
                                    </div>

                                    <div>
                                        <InputLabel :for="'testimonial_content_' + index" value="Isi Testimoni" />
                                        <textarea
                                            :id="'testimonial_content_' + index"
                                            v-model="testimonial.content"
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            rows="3"
                                            placeholder="Testimoni dari klien Anda"
                                            required
                                        ></textarea>
                                        <InputError :message="errors['testimonials.' + index + '.content']" class="mt-1" />
                                    </div>
                                </div>
                            </div>

                            <button
                                type="button"
                                @click="addTestimonial"
                                v-if="formData.testimonials.length < 10"
                                class="w-full flex items-center justify-center px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg text-sm font-medium text-gray-600 hover:border-gray-400 hover:bg-gray-50 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Testimonial
                            </button>
                        </div>
                    </div>
                </div>

                        <!-- Terms Agreement -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input
                                        id="agree_terms"
                                        v-model="formData.agree_terms"
                                        type="checkbox"
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                        required
                                    />
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="agree_terms" class="font-medium text-gray-700">
                                        I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500">Terms of Service</a> and <a href="#" class="text-indigo-600 hover:text-indigo-500">Privacy Policy</a>
                                    </label>
                                    <InputError :message="errors.agree_terms" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <!-- <div class="flex justify-end">
                            <PrimaryButton
                                type="submit"
                                :loading="submitting"
                                class="px-8 py-3"
                            >
                                Continue to Payment
                            </PrimaryButton>
                        </div> -->
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <OrderSummary
                        :template="template"
                        :pricing="pricing"
                        :form-data="formData"
                        :submitting="submitting"
                        :voucher-discount="formData.voucher_discount"
                        @submit="showConfirmation"
                        @voucherApplied="handleVoucherApplied"
                    />
                </div>



            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, toRaw, onBeforeUnmount } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PageLoader from '@/Components/PageLoader.vue'
import ConfirmationDialog from '@/Components/ConfirmationDialog.vue'
import ErrorDialog from '@/Components/ErrorDialog.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import OrderSummary from '@/Components/OrderSummary.vue'

// Props
const props = defineProps({
    template: Object,
    user: Object,
    pricing: Object,
    form_fields: Array,
})

// Reactive data
const loading = ref(false)
const submitting = ref(false)
const showConfirmDialog = ref(false)
const showErrorDialog = ref(false)
const showRetryButton = ref(false)
const errorTitle = ref('')
const errorMessage = ref('')
const errorDetails = ref(null)

const imagePreviews = reactive({
    company_logo: null,
    favicon: null,
    og_image: null,
    hero_background: null,
    about_image: null,
    promo_banner: null,
})

const testimonialPreviews = ref([])
const previewRegistry = new Set()
const isDealer = computed(() => props.template?.category === 'dealer')

// Form data


const formData = reactive({
    // Company Info
    company_name: '',
    company_tagline: '',
    company_logo: null,
    
    // Website Basics
    website_name: '',
    subdomain: '',
    font_family: 'inter',
    border_radius: 'medium',
    
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
    about_title: '',
    about_content: '',
    about_image: null,
    
    // Services Section
    services_title: 'Layanan Unggulan Kami',
    services_subtitle: '',
    services: [
        { 
            title: '',
            description: '',
            icon: 'fas fa-chart-line',
            link: ''
        }
    ],
    
    // Company Stats
    company_stats: [
        {
            icon: 'fas fa-users',
            label: '',
            number: ''
        }
    ],
    
    // Social Media
    social_links: [],
    
    // Testimonials
    testimonials_title: 'Testimoni Klien',
    testimonials: [
        {
            name: '',
            photo: null,
            rating: '5',
            content: '',
            position: ''
        }
    ],
    
    // Gallery
    gallery_title: 'Portfolio & Galeri',
    gallery_photos: [],

    // Dealer Specials
    cars: [],
    promo_banner: null,
    promo_title: '',
    promo_description: '',
    
    // WhatsApp Integration
    whatsapp_enabled: true,
    whatsapp_number: '',
    whatsapp_message: '',
    whatsapp_position: 'bottom-left',
    whatsapp_greeting_text: '',
    
    // Analytics
    google_analytics: '',
    google_tag_manager: '',
    
    // Form Control
    agree_terms: false,
    voucher_code: '',
    voucher_discount: 0,
})

testimonialPreviews.value = formData.testimonials.map(() => null)

const createEmptyCar = () => ({
    name: '',
    type: '',
    year: '',
    image: null,
    image_preview: null,
    price: '',
    features: '',
    transmission: '',
    whatsapp_sales: '',
})

if (isDealer.value) {
    formData.cars = formData.cars.map((car) => ({
        ...car,
        image_preview: car.image_preview ?? null,
    }))
}

const FILE_SIZE_LIMITS = {
    company_logo: 2,
    favicon: 1,
    og_image: 4,
    hero_background: 6,
    about_image: 4,
    testimonial_photo: 2,
    gallery_photo: 4,
    car_image: 4,
    promo_banner: 4,
}

const createPreviewUrl = (file) => {
    const url = URL.createObjectURL(file)
    previewRegistry.add(url)
    return url
}

const releasePreviewUrl = (url) => {
    if (!url) {
        return
    }

    URL.revokeObjectURL(url)
    previewRegistry.delete(url)
}

const resolveMediaPath = (path) => {
    if (!path || typeof path !== 'string') {
        return null
    }

    if (path.startsWith('blob:') || path.startsWith('http://') || path.startsWith('https://')) {
        return path
    }

    if (path.startsWith('/')) {
        return path
    }

    const normalized = path.replace(/^storage\//, '')
    return `/storage/${normalized}`
}

const getCarImageUrl = (car) => {
    if (!car) {
        return null
    }

    if (car.image_preview) {
        return car.image_preview
    }

    return resolveMediaPath(car.image)
}

const updateImageField = (field, file, sizeLimitMb = 2) => {
    if (!file) {
        formData[field] = null
        if (imagePreviews[field]) {
            releasePreviewUrl(imagePreviews[field])
            imagePreviews[field] = null
        }
        return
    }

    if (sizeLimitMb && file.size > sizeLimitMb * 1024 * 1024) {
        alert(`File terlalu besar. Maksimal ${sizeLimitMb}MB.`)
        return
    }

    formData[field] = file

    if (imagePreviews[field]) {
        releasePreviewUrl(imagePreviews[field])
    }

    imagePreviews[field] = createPreviewUrl(file)
}

const serializeValue = (value) => {
    if (value === null || value === undefined) {
        return value
    }

    if (value instanceof File || value instanceof Blob) {
        return value
    }

    if (Array.isArray(value)) {
        return value.map((item) => serializeValue(item))
    }

    if (typeof value === 'object') {
        const rawObject = toRaw(value)
        return Object.keys(rawObject).reduce((acc, key) => {
            if (key === 'image_preview') {
                return acc
            }

            acc[key] = serializeValue(rawObject[key])
            return acc
        }, {})
    }

    return value
}

const buildPayload = () => {
    const rawFormData = toRaw(formData)

    return Object.keys(rawFormData).reduce((acc, key) => {
        acc[key] = serializeValue(rawFormData[key])
        return acc
    }, {})
}

// Computed
const errors = computed(() => usePage().props.errors || {})

// Methods
const showConfirmation = () => {
    showConfirmDialog.value = true
}

const submitForm = () => {
    submitting.value = true
    showConfirmDialog.value = false

    const payload = buildPayload()

    router.post(route('templates.checkout.process', props.template.slug), payload, {
        forceFormData: true,
        onSuccess: () => {
            showRetryButton.value = false
        },
        onError: (formErrors) => {
            const errorKeys = Object.keys(formErrors || {})
            const hasValidationErrors = errorKeys.length > 0

            if (hasValidationErrors) {
                const messageSet = new Set()

                Object.values(formErrors).forEach((value) => {
                    if (Array.isArray(value)) {
                        value.filter(Boolean).forEach((msg) => messageSet.add(msg))
                    } else if (value) {
                        messageSet.add(value)
                    }
                })

                const messages = Array.from(messageSet)

                errorTitle.value = 'Validasi Data Gagal'
                errorMessage.value = 'Beberapa data belum valid. Silakan periksa kembali formulir Anda.'
                errorDetails.value = { field: errorKeys[0] }
                showRetryButton.value = false
            } else {
                errorTitle.value = 'Terjadi Kesalahan'
                errorMessage.value = 'Terjadi kesalahan saat memproses permintaan. Silakan coba lagi.'
                errorDetails.value = null
                showRetryButton.value = true
            }

            showErrorDialog.value = true
        },
        onFinish: () => {
            submitting.value = false
        }
    })
}

const addService = () => {
    if (formData.services.length < 10) {
        formData.services.push({
            title: '',
            description: '',
            icon: 'fas fa-star',
            link: '#layanan-' + (formData.services.length + 1)
        })
    }
}

const removeService = (index) => {
    if (formData.services.length > 1) {
        formData.services.splice(index, 1)
    }
}

const addSocialLink = () => {
    if (formData.social_links.length < 10) {
        formData.social_links.push({
            platform: 'facebook',
            url: '',
            label: ''
        })
    }
}

const removeSocialLink = (index) => {
    formData.social_links.splice(index, 1)
}

const handleVoucherApplied = (voucherData) => {
    formData.voucher_code = voucherData.code
    formData.voucher_discount = voucherData.discount
}

const closeErrorDialog = () => {
    showErrorDialog.value = false
    errorTitle.value = ''
    errorMessage.value = ''
    errorDetails.value = null
    showRetryButton.value = false
}

const retrySubmission = () => {
    closeErrorDialog()
    showConfirmation()
}

const addCompanyStat = () => {
    if (formData.company_stats.length < 6) {
        formData.company_stats.push({
            icon: 'fas fa-users',
            label: '',
            number: ''
        })
    }
}

const removeCompanyStat = (index) => {
    if (formData.company_stats.length > 1) {
        formData.company_stats.splice(index, 1)
    }
}
const addTestimonial = () => {
    if (formData.testimonials.length < 10) {
        formData.testimonials.push({
            name: '',
            photo: null,
            rating: '5',
            content: '',
            position: ''
        })
        testimonialPreviews.value.push(null)
    }
}

const addCar = () => {
    if (formData.cars.length < 50) {
        formData.cars.push(createEmptyCar())
    }
}

const removeCar = (index) => {
    if (!formData.cars[index]) {
        return
    }

    if (formData.cars[index].image_preview) {
        releasePreviewUrl(formData.cars[index].image_preview)
    }

    formData.cars.splice(index, 1)
}

const handleTestimonialPhotoUpload = (event, index) => {
    const file = event.target.files?.[0]
    event.target.value = ''

    if (!file) {
        return
    }

    const limit = FILE_SIZE_LIMITS.testimonial_photo || 2
    if (file.size > limit * 1024 * 1024) {
        alert(`Ukuran foto terlalu besar. Maksimal ${limit}MB.`)
        return
    }

    formData.testimonials[index].photo = file

    const currentPreview = testimonialPreviews.value[index]
    if (currentPreview) {
        releasePreviewUrl(currentPreview)
    }

    testimonialPreviews.value[index] = createPreviewUrl(file)
}

const removeTestimonial = (index) => {
    const preview = testimonialPreviews.value[index]
    if (preview) {
        releasePreviewUrl(preview)
    }

    testimonialPreviews.value.splice(index, 1)
    formData.testimonials.splice(index, 1)
}

const handleFileUpload = (event, field) => {
    const file = event.target.files?.[0]
    event.target.value = ''

    if (!file) {
        return
    }

    const limit = FILE_SIZE_LIMITS[field] ?? 2
    updateImageField(field, file, limit)
}

const handleFaviconUpload = (event) => handleFileUpload(event, 'favicon')
const handleOgImageUpload = (event) => handleFileUpload(event, 'og_image')
const handleHeroBackgroundUpload = (event) => handleFileUpload(event, 'hero_background')
const handleCompanyLogoUpload = (event) => handleFileUpload(event, 'company_logo')
const handleAboutImageUpload = (event) => handleFileUpload(event, 'about_image')
const handlePromoBannerUpload = (event) => handleFileUpload(event, 'promo_banner')

const handleGalleryUpload = (event) => {
    const files = Array.from(event.target.files || [])
    event.target.value = ''

    if (!files.length) {
        return
    }

    const remainingSlots = 12 - formData.gallery_photos.length
    if (remainingSlots <= 0) {
        alert('Maksimal 12 foto galeri yang dapat diunggah.')
        return
    }

    files.slice(0, remainingSlots).forEach((file) => {
        const limit = FILE_SIZE_LIMITS.gallery_photo || 4
        if (file.size > limit * 1024 * 1024) {
            alert(`Ukuran file ${file.name} terlalu besar. Maksimal ${limit}MB.`)
            return
        }

        formData.gallery_photos.push(file)
    })
}

const handleCarImageUpload = (event, index) => {
    const file = event.target.files?.[0]
    event.target.value = ''

    if (!formData.cars[index]) {
        return
    }

    if (!file) {
        return
    }

    const limit = FILE_SIZE_LIMITS.car_image || 4
    if (file.size > limit * 1024 * 1024) {
        alert(`Ukuran file terlalu besar. Maksimal ${limit}MB.`)
        return
    }

    formData.cars[index].image = file

    if (formData.cars[index].image_preview) {
        releasePreviewUrl(formData.cars[index].image_preview)
    }

    formData.cars[index].image_preview = createPreviewUrl(file)
}

onBeforeUnmount(() => {
    previewRegistry.forEach((url) => URL.revokeObjectURL(url))
    previewRegistry.clear()
})
</script>
