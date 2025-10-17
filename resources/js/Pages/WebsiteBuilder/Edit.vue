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

            <!-- Notification Modal -->
            <div v-if="notification.show" class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="closeNotification"></div>
                    
                    <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg v-if="notification.type === 'success'" class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <svg v-else class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium" :class="notification.type === 'success' ? 'text-green-900' : 'text-red-900'">
                                    {{ notification.type === 'success' ? 'Success!' : 'Error!' }}
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm" :class="notification.type === 'success' ? 'text-green-700' : 'text-red-700'">
                                        {{ notification.message }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button
                                type="button"
                                @click="closeNotification"
                                class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md"
                                :class="notification.type === 'success' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

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
                                Website Information
                            </h3>
                        </div>

                        <div class="p-6">
                            <div class="w-full">
                                <div class="bg-white-50 rounded-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4 text-sm">
                                        <div class="flex">
                                            <span class="text-gray-600">Name:</span>&nbsp;
                                            <span class="font-medium">{{ websiteContent.website_name }}</span>
                                        </div>
                                        <div class="flex">
                                            <StatusBadge :status="websiteContent.status" />
                                        </div>
                                        <div class="flex">
                                            <span class="text-gray-600">Created:</span>&nbsp;
                                            <span class="font-medium">{{ formatDate(websiteContent.created_at) }}</span>
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
                                <!-- Website Basics -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label for="website_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Website Name *
                                        </label>
                                        <input
                                            id="website_name"
                                            v-model="form.website_name"
                                            type="text"
                                            required
                                            placeholder="Enter website name"
                                            :class="[
                                                'w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 transition duration-200',
                                                errors.website_name 
                                                    ? 'border-red-300 focus:ring-red-500 focus:border-red-500' 
                                                    : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                                            ]"
                                        >
                                        <p v-if="errors.website_name" class="mt-1 text-sm text-red-600">{{ errors.website_name }}</p>
                                    </div>
                                    <div>
                                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Company Name *
                                        </label>
                                        <input
                                            id="company_name"
                                            v-model="form.content_data.company_name"
                                            type="text"
                                            required
                                            placeholder="Enter company name"
                                            :class="[
                                                'w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 transition duration-200',
                                                errors['content_data.company_name'] 
                                                    ? 'border-red-300 focus:ring-red-500 focus:border-red-500' 
                                                    : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                                            ]"
                                        >
                                        <p v-if="errors['content_data.company_name']" class="mt-1 text-sm text-red-600">{{ errors['content_data.company_name'] }}</p>
                                    </div>
                                </div>

                                <!-- Branding -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="company_logo" class="block text-sm font-medium text-gray-700 mb-2">
                                            Company Logo
                                        </label>
                                        <input
                                            id="company_logo"
                                            type="file"
                                            accept="image/*"
                                            @change="handleCompanyLogoUpload"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                        >
                                        <p class="text-xs text-gray-500 mt-2">Recommended size: 256x256px (PNG with transparent background).</p>
                                        <div v-if="imagePreviews.company_logo" class="mt-3 flex items-center justify-center">
                                            <img
                                                :src="imagePreviews.company_logo"
                                                alt="Company logo preview"
                                                class="h-20 w-auto object-contain border border-dashed border-gray-300 rounded-md bg-white p-3"
                                            />
                                        </div>
                                    </div>
                                    <div>
                                        <label for="favicon" class="block text-sm font-medium text-gray-700 mb-2">
                                            Favicon (32x32)
                                        </label>
                                        <input
                                            id="favicon"
                                            type="file"
                                            accept="image/*"
                                            @change="handleFaviconUpload"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                        >
                                        <p class="text-xs text-gray-500 mt-2">Upload a square image (ICO, PNG, or SVG).</p>
                                        <div v-if="imagePreviews.favicon" class="mt-3 flex items-center justify-center">
                                            <img
                                                :src="imagePreviews.favicon"
                                                alt="Favicon preview"
                                                class="w-12 h-12 object-contain border border-dashed border-gray-300 rounded-md bg-white p-2"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <!-- Company Tagline -->
                                <div class="mt-6">
                                    <label for="company_tagline" class="block text-sm font-medium text-gray-700 mb-2">
                                        Company Tagline
                                    </label>
                                    <input
                                        id="company_tagline"
                                        v-model="form.content_data.company_tagline"
                                        type="text"
                                        placeholder="Solusi Terbaik Untuk Anda"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                    >
                                </div>

                                <!-- About Section -->
                                <div class="mt-8 p-6 bg-yellow-50 rounded-lg border">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Tentang Perusahaan</h4>
                                    <div class="space-y-6">
                                        <div>
                                            <label for="about_title" class="block text-sm font-medium text-gray-700 mb-2">
                                                Judul Section
                                            </label>
                                            <input
                                                id="about_title"
                                                v-model="form.content_data.about_title"
                                                type="text"
                                                placeholder="Tentang Kami"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                            >
                                        </div>
                                        <div>
                                            <label for="about_content" class="block text-sm font-medium text-gray-700 mb-2">
                                                Deskripsi
                                            </label>
                                            <textarea
                                                id="about_content"
                                                v-model="form.content_data.about_content"
                                                rows="5"
                                                placeholder="Ceritakan tentang perusahaan Anda..."
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                            ></textarea>
                                        </div>
                                        <div>
                                            <label for="about_image" class="block text-sm font-medium text-gray-700 mb-2">
                                                About Image
                                            </label>
                                            <input
                                                id="about_image"
                                                type="file"
                                                accept="image/*"
                                                @change="handleAboutImageUpload"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                            >
                                            <p class="text-xs text-gray-500 mt-2">Ukuran file maksimal 1 MB</p>
                                            <div v-if="imagePreviews.about_image" class="mt-3">
                                                <img
                                                    :src="imagePreviews.about_image"
                                                    alt="About image preview"
                                                    class="w-full h-32 object-cover rounded-lg border"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Gallery Photos -->
                                <div class="mt-8 p-6 bg-purple-50 rounded-lg border">
                                    <div class="mb-6">
                                        <h4 class="text-lg font-semibold text-gray-900">Galeri Foto</h4>
                                        <p class="text-sm text-gray-500">Klik area foto untuk upload gambar (maksimal 4 foto).</p>
                                    </div>

                                    <!-- 4 Grid Layout -->
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <!-- Always show 4 slots -->
                                        <div
                                            v-for="n in 4"
                                            :key="n"
                                            class="relative group"
                                        >
                                            <div class="aspect-square bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 overflow-hidden hover:border-purple-400 transition-colors cursor-pointer">
                                                <img
                                                    v-if="form.content_data.gallery_photos[n-1]?.url"
                                                    :src="resolveMediaPath(form.content_data.gallery_photos[n-1].url)"
                                                    alt="Gallery photo"
                                                    class="w-full h-full object-cover"
                                                />
                                                <div v-else class="flex items-center justify-center h-full text-gray-400">
                                                    <div class="text-center">
                                                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        <p class="text-xs">Klik untuk upload</p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Upload Input -->
                                            <input
                                                type="file"
                                                accept="image/*"
                                                @change="handleGalleryImageUpload($event, n-1)"
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                            >
                                            
                                            <!-- Remove Button -->
                                            <button
                                                v-if="form.content_data.gallery_photos[n-1]?.url"
                                                type="button"
                                                @click="removeGalleryPhoto(n-1)"
                                                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                            >
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Products -->
                                <div class="mt-8 p-6 bg-indigo-50 rounded-lg border">
                                    <div class="flex justify-between items-center mb-6">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">Produk/Layanan</h4>
                                            <p class="text-sm text-gray-500">Tampilkan produk atau layanan unggulan.</p>
                                        </div>
                                        <button
                                            v-if="form.content_data.products.length < 4"
                                            type="button"
                                            @click="addProduct"
                                            class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200"
                                        >
                                            Tambah Produk
                                        </button>
                                    </div>
                                    
                                    <div class="space-y-6">
                                        <div
                                            v-for="(product, index) in form.content_data.products"
                                            :key="index"
                                            class="border border-gray-200 rounded-lg p-5 bg-white"
                                        >
                                            <div class="flex justify-between items-start mb-4">
                                                <h5 class="font-medium text-gray-900">Produk {{ index + 1 }}</h5>
                                                <button
                                                    type="button"
                                                    @click="removeProduct(index)"
                                                    class="text-red-500 hover:text-red-700 transition duration-200"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                        Nama Produk
                                                    </label>
                                                    <input
                                                        v-model="product.name"
                                                        type="text"
                                                        placeholder="Masukkan nama produk"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                    >
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                        Harga
                                                    </label>
                                                    <input
                                                        v-model="product.price"
                                                        type="text"
                                                        placeholder="Rp 100.000"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                    >
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                        Link Produk (Opsional)
                                                    </label>
                                                    <input
                                                        v-model="product.link"
                                                        type="text"
                                                        placeholder="https://..."
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                    >
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                        Foto Produk
                                                    </label>
                                                    <input
                                                        type="file"
                                                        accept="image/*"
                                                        @change="handleProductImageUpload($event, index)"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                    >
                                                    <p class="text-xs text-gray-500 mt-2">Ukuran file maksimal 1 MB</p>
                                                    <div v-if="product.image" class="mt-3">
                                                        <img
                                                            :src="resolveMediaPath(product.image)"
                                                            alt="Product image"
                                                            class="w-32 h-32 object-cover rounded-lg border"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p v-if="form.content_data.products.length >= 4" class="text-sm text-amber-600">Maksimal 4 produk telah tercapai.</p>
                                    </div>
                                </div>

                                <!-- Social Media Links -->
                                <div class="mt-8 p-6 bg-pink-50 rounded-lg border">
                                    <div class="flex justify-between items-center mb-6">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">Media Sosial</h4>
                                            <p class="text-sm text-gray-500">Tambahkan link media sosial perusahaan.</p>
                                        </div>
                                        <button
                                            v-if="form.content_data.social_links.length < 4"
                                            type="button"
                                            @click="addSocialLink"
                                            class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200"
                                        >
                                            Tambah Link
                                        </button>
                                    </div>

                                    <div v-if="!form.content_data.social_links.length" class="text-sm text-gray-500 text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                                        Belum ada link sosial. Klik "Tambah Link" untuk menambahkan.
                                    </div>

                                    <div class="space-y-4" v-else>
                                        <div
                                            v-for="(social, index) in form.content_data.social_links"
                                            :key="index"
                                            class="border border-gray-200 rounded-lg p-5 bg-white"
                                        >
                                            <div class="flex justify-between items-start mb-4">
                                                <h5 class="font-medium text-gray-900">Social Media {{ index + 1 }}</h5>
                                                <button
                                                    type="button"
                                                    @click="removeSocialLink(index)"
                                                    class="text-red-500 hover:text-red-700 transition duration-200"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                        Platform
                                                    </label>
                                                    <select
                                                        v-model="social.platform"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                    >
                                                        <option value="facebook">Facebook</option>
                                                        <option value="instagram">Instagram</option>
                                                        <option value="twitter">Twitter</option>
                                                        <option value="youtube">YouTube</option>
                                                        <option value="linkedin">LinkedIn</option>
                                                        <option value="tiktok">TikTok</option>
                                                        <option value="whatsapp">WhatsApp</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                        Label
                                                    </label>
                                                    <input
                                                        v-model="social.label"
                                                        type="text"
                                                        placeholder="Nama akun"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                    >
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                        URL
                                                    </label>
                                                    <input
                                                        v-model="social.url"
                                                        type="text"
                                                        placeholder="https://..."
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <p v-if="form.content_data.social_links.length >= 4" class="text-sm text-amber-600">Maksimal 4 link media sosial telah tercapai.</p>
                                    </div>
                                </div>

                                <!-- Testimonials -->
                                <div class="mt-8 p-6 bg-orange-50 rounded-lg border">
                                    <div class="flex justify-between items-center mb-6">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">Testimoni</h4>
                                            <p class="text-sm text-gray-500">Tambahkan testimoni dari pelanggan.</p>
                                        </div>
                                        <button
                                            v-if="form.content_data.testimonials.length < 3"
                                            type="button"
                                            @click="addTestimonial"
                                            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200"
                                        >
                                            Tambah Testimoni
                                        </button>
                                    </div>

                                    <div class="space-y-6">
                                        <div
                                            v-for="(testimonial, index) in form.content_data.testimonials"
                                            :key="index"
                                            class="border border-gray-200 rounded-lg p-5 space-y-6 bg-white"
                                        >
                                            <div class="flex justify-between items-start mb-4">
                                                <h5 class="font-medium text-gray-900">Testimoni {{ index + 1 }}</h5>
                                                <button
                                                    type="button"
                                                    @click="removeTestimonial(index)"
                                                    class="text-red-500 hover:text-red-700 transition duration-200"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Foto Pelanggan
                                                </label>
                                                <input
                                                    type="file"
                                                    accept="image/*"
                                                    @change="handleTestimonialImageUpload($event, index)"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                >
                                                <p class="text-xs text-gray-500 mt-2">Ukuran file maksimal 1 MB</p>
                                                <div v-if="testimonial.photo" class="mt-3">
                                                    <img
                                                        :src="resolveMediaPath(testimonial.photo)"
                                                        alt="Customer photo"
                                                        class="w-16 h-16 object-cover rounded-full border"
                                                    />
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                        Nama Pelanggan
                                                    </label>
                                                    <input
                                                        v-model="testimonial.name"
                                                        type="text"
                                                        placeholder="Nama pelanggan"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                    >
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                        Posisi/Jabatan
                                                    </label>
                                                    <input
                                                        v-model="testimonial.position"
                                                        type="text"
                                                        placeholder="CEO di PT Example"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                    >
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Rating
                                                </label>
                                                <select
                                                    v-model="testimonial.rating"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                >
                                                    <option value="5">⭐⭐⭐⭐⭐ (5 bintang)</option>
                                                    <option value="4">⭐⭐⭐⭐ (4 bintang)</option>
                                                    <option value="3">⭐⭐⭐ (3 bintang)</option>
                                                    <option value="2">⭐⭐ (2 bintang)</option>
                                                    <option value="1">⭐ (1 bintang)</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Isi Testimoni
                                                </label>
                                                <textarea
                                                    v-model="testimonial.content"
                                                    rows="4"
                                                    placeholder="Tulis testimoni pelanggan..."
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                ></textarea>
                                            </div>
                                        </div>
                                        <p v-if="form.content_data.testimonials.length >= 3" class="text-sm text-amber-600">Maksimal 3 testimoni telah tercapai.</p>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="mt-8 p-6 bg-red-50 rounded-lg border">
                                    <div class="mb-6">
                                        <h4 class="text-lg font-semibold text-gray-900">Informasi Kontak</h4>
                                        <p class="text-sm text-gray-500">Informasi kontak untuk pelanggan menghubungi Anda.</p>
                                    </div>

                                    <div class="space-y-6">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Nomor Telepon
                                                </label>
                                                <input
                                                    v-model="form.content_data.contact_phone"
                                                    type="text"
                                                    placeholder="021-12345678"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                >
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Email
                                                </label>
                                                <input
                                                    v-model="form.content_data.contact_email"
                                                    type="email"
                                                    placeholder="info@bisnis.com"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                >
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Alamat
                                            </label>
                                            <textarea
                                                v-model="form.content_data.contact_address"
                                                rows="3"
                                                placeholder="Alamat lengkap bisnis Anda"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                            ></textarea>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Google Maps Embed URL (Opsional)
                                            </label>
                                            <input
                                                v-model="form.content_data.maps_embed_url"
                                                type="text"
                                                placeholder="https://www.google.com/maps/embed?pb=..."
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                            >
                                            <p class="text-xs text-gray-500 mt-1">
                                                Dapatkan dari Google Maps → Bagikan → Sematkan peta
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- WhatsApp Integration -->
                                <div class="mt-8 p-6 bg-green-50 rounded-lg border">
                                    <div class="mb-6">
                                        <h4 class="text-lg font-semibold text-gray-900">WhatsApp</h4>
                                        <p class="text-sm text-gray-500">Konfigurasi tombol WhatsApp untuk website Anda.</p>
                                    </div>

                                    <div class="space-y-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Nomor WhatsApp
                                                </label>
                                                <input
                                                    v-model="form.content_data.whatsapp_number"
                                                    type="tel"
                                                    placeholder="628123456789"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                >
                                                <p class="text-xs text-gray-500 mt-1">Format: 628xxxxxxxxxx (tanpa tanda +)</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Pesan Default
                                                </label>
                                                <textarea
                                                    v-model="form.content_data.whatsapp_message"
                                                    rows="3"
                                                    placeholder="Halo, saya tertarik dengan produk/layanan Anda..."
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                ></textarea>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Teks Tombol
                                                </label>
                                                <input
                                                    v-model="form.content_data.whatsapp_greeting_text"
                                                    type="text"
                                                    placeholder="Chat dengan kami!"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                >
                                            </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="pt-6 flex flex-col sm:flex-row gap-4">
                                    <Link
                                        href="/dashboard"
                                        class="flex items-center justify-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 px-8 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                                    >
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        Back
                                    </Link>

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
import { ref, reactive, onMounted, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PageLoader from '@/Components/PageLoader.vue'
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
    config: Object,
    errors: {
        type: Object,
        default: () => ({})
    }
})

// Reactive state
const loading = ref(true)
const submitting = ref(false)

// Notification state
const notification = reactive({
    show: false,
    type: 'success', // 'success' or 'error'
    message: ''
})

const form = reactive({
    website_name: props.websiteContent?.website_name || '',
    content_data: {
        // Company Info
        company_name: '',
        company_tagline: '',
        company_logo: null,
        
        // Website Basics
        website_name: '',
        font_family: 'Poppins',
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

const imagePreviews = reactive({
    company_logo: null,
    favicon: null,
    hero_background: null,
    about_image: null,
    og_image: null,
})

const resolveMediaPath = (value) => {
    if (!value) {
        return null
    }

    if (typeof value === 'string') {
        if (
            value.startsWith('data:') ||
            value.startsWith('http://') ||
            value.startsWith('https://') ||
            value.startsWith('blob:')
        ) {
            return value
        }

        const normalized = value.replace(/^storage\//, '')
        return `/storage/${normalized}`
    }

    return null
}

watch(
    () => form.content_data.company_logo,
    (value) => {
        imagePreviews.company_logo = resolveMediaPath(value)
    },
    { immediate: true }
)

watch(
    () => form.content_data.favicon,
    (value) => {
        imagePreviews.favicon = resolveMediaPath(value)
    },
    { immediate: true }
)

watch(
    () => form.content_data.hero_background,
    (value) => {
        imagePreviews.hero_background = resolveMediaPath(value)
    },
    { immediate: true }
)

watch(
    () => form.content_data.about_image,
    (value) => {
        imagePreviews.about_image = resolveMediaPath(value)
    },
    { immediate: true }
)

watch(
    () => form.content_data.og_image,
    (value) => {
        imagePreviews.og_image = resolveMediaPath(value)
    },
    { immediate: true }
)

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

const handleImageUpload = async (fieldName, event, sizeLimitMb = 2) => {
    const file = event.target.files?.[0]
    event.target.value = ''

    if (!file) {
        return
    }

    if (file.size > sizeLimitMb * 1024 * 1024) {
        alert(`File size should not exceed ${sizeLimitMb}MB`)
        return
    }

    try {
        const oldFile = form.content_data[fieldName]
        const uploadedPath = await uploadFileToServer(file, fieldName, oldFile)
        form.content_data[fieldName] = uploadedPath
    } catch (error) {
        console.error('Error processing file:', error)
        alert('Error processing file. Please try again.')
    }
}

const uploadFileToServer = async (file, fieldName, oldFile = null) => {
    const formData = new FormData()
    formData.append('file', file)
    formData.append('field', fieldName)
    formData.append('website_id', props.websiteContent.id)
    
    // Include old file path for deletion if it exists
    if (oldFile && !oldFile.startsWith('data:')) {
        formData.append('old_file', oldFile)
    }

    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

    console.log('Uploading file:', {
        url: '/api/website-builder/upload-file',
        field: fieldName,
        websiteId: props.websiteContent.id,
        hasToken: !!csrfToken
    })

    const response = await fetch('/api/website-builder/upload-file', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })

    console.log('Response:', {
        status: response.status,
        ok: response.ok,
        contentType: response.headers.get('content-type')
    })

    const responseText = await response.text()
    console.log('Response text preview:', responseText.substring(0, 500))

    if (!response.ok) {
        console.error('Error response:', responseText)
        
        try {
            const errorData = JSON.parse(responseText)
            throw new Error(errorData.message || 'File upload failed')
        } catch (parseError) {
            throw new Error('File upload failed: ' + response.status + ' ' + response.statusText)
        }
    }

    try {
        const result = JSON.parse(responseText)
        return result.path
    } catch (parseError) {
        console.error('Failed to parse JSON response:', responseText)
        throw new Error('Invalid response format from server')
    }
}

const handleCompanyLogoUpload = (event) => handleImageUpload('company_logo', event, 2)
const handleFaviconUpload = (event) => handleImageUpload('favicon', event, 1)
const handleAboutImageUpload = (event) => handleImageUpload('about_image', event, 5)

// Gallery Photos methods
const removeGalleryPhoto = (index) => {
    // Ensure gallery_photos is an array and the index exists
    if (!Array.isArray(form.content_data.gallery_photos)) {
        form.content_data.gallery_photos = []
    }
    
    // Remove the photo at the specific index
    if (form.content_data.gallery_photos[index]) {
        form.content_data.gallery_photos.splice(index, 1)
    }
}

const handleGalleryImageUpload = async (event, index) => {
    const file = event.target.files?.[0]
    event.target.value = ''

    if (!file) return

    if (file.size > 5 * 1024 * 1024) {
        alert('File size should not exceed 5MB')
        return
    }

    try {
        // Ensure gallery_photos is an array
        if (!Array.isArray(form.content_data.gallery_photos)) {
            form.content_data.gallery_photos = []
        }

        const oldFile = form.content_data.gallery_photos[index]?.url
        const uploadedPath = await uploadFileToServer(file, `gallery_photo_${index}`, oldFile)
        
        // Ensure the photo object exists at the index
        if (!form.content_data.gallery_photos[index]) {
            form.content_data.gallery_photos[index] = { url: null, caption: '' }
        }
        
        // Set the URL - now we're sure it's an object
        form.content_data.gallery_photos[index].url = uploadedPath
    } catch (error) {
        console.error('Error processing file:', error)
        alert('Error processing file. Please try again.')
    }
}

// Products methods
const addProduct = () => {
    if (form.content_data.products.length < 4) {
        form.content_data.products.push({
            name: '',
            price: '',
            link: '',
            image: null
        })
    }
}

const removeProduct = (index) => {
    form.content_data.products.splice(index, 1)
}

const handleProductImageUpload = async (event, index) => {
    const file = event.target.files?.[0]
    event.target.value = ''

    if (!file) return

    if (file.size > 2 * 1024 * 1024) {
        alert('File size should not exceed 2MB')
        return
    }

    try {
        const oldFile = form.content_data.products[index]?.image
        const uploadedPath = await uploadFileToServer(file, `product_image_${index}`, oldFile)
        form.content_data.products[index].image = uploadedPath
    } catch (error) {
        console.error('Error processing file:', error)
        alert('Error processing file. Please try again.')
    }
}

// Social Links methods
const addSocialLink = () => {
    if (form.content_data.social_links.length < 4) {
        form.content_data.social_links.push({
            platform: '',
            label: '',
            url: ''
        })
    }
}

const removeSocialLink = (index) => {
    form.content_data.social_links.splice(index, 1)
}

// Testimonials methods
const addTestimonial = () => {
    if (form.content_data.testimonials.length < 3) {
        form.content_data.testimonials.push({
            name: '',
            position: '',
            content: '',
            rating: '5',
            photo: null
        })
    }
}

const removeTestimonial = (index) => {
    form.content_data.testimonials.splice(index, 1)
}

const handleTestimonialImageUpload = async (event, index) => {
    const file = event.target.files?.[0]
    event.target.value = ''

    if (!file) return

    if (file.size > 1 * 1024 * 1024) {
        alert('File size should not exceed 1MB')
        return
    }

    try {
        const oldFile = form.content_data.testimonials[index]?.photo
        const uploadedPath = await uploadFileToServer(file, `testimonial_photo_${index}`, oldFile)
        form.content_data.testimonials[index].photo = uploadedPath
    } catch (error) {
        console.error('Error processing file:', error)
        alert('Error processing file. Please try again.')
    }
}

// File upload utility removed - now using server upload

const submitForm = () => {
    if (submitting.value) return

    submitting.value = true

    router.put(`/website-builder/${props.websiteContent.id}`, {
        website_name: form.website_name,
        content_data: form.content_data
    }, {
        onSuccess: (page) => {
            submitting.value = false
            showNotification('success', page.props.flash?.success || 'Website updated successfully!')
        },
        onError: (errors) => {
            submitting.value = false
            const errorMessage = errors.message || Object.values(errors).flat().join(', ') || 'An error occurred while updating the website.'
            showNotification('error', errorMessage)
        },
        onFinish: () => {
            submitting.value = false
        }
    })
}

// Notification methods
const showNotification = (type, message) => {
    notification.type = type
    notification.message = message
    notification.show = true
}

const closeNotification = () => {
    notification.show = false
}

// Initialize arrays if they don't exist
const initializeArrays = () => {
    if (!Array.isArray(form.content_data.gallery_photos)) {
        form.content_data.gallery_photos = []
    }
    if (!Array.isArray(form.content_data.products)) {
        form.content_data.products = []
    }
    if (!Array.isArray(form.content_data.social_links)) {
        form.content_data.social_links = []
    }
    if (!Array.isArray(form.content_data.testimonials)) {
        form.content_data.testimonials = []
    }
    
    // Ensure gallery_photos array has the right structure for existing data
    form.content_data.gallery_photos = form.content_data.gallery_photos.map(photo => {
        if (typeof photo === 'string') {
            return { url: photo, caption: '' }
        }
        return photo || { url: null, caption: '' }
    })
}

// Lifecycle hooks
onMounted(() => {
    initializeArrays()
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
