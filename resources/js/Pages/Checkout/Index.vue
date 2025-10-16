<template>
    <AppLayout>
        <Head :title="`Checkout - ${template.name}`" />

        <PageLoader :show="loading" />

        <SimpleConfirmationDialog
            :show="showConfirmDialog"
            :loading="submitting"
            @confirm="submitForm"
            @cancel="showConfirmDialog = false"
        />

        <ErrorDialog
            :show="showErrorDialog"
            :title="errorTitle"
            :message="errorMessage"
            :error="errorDetails"
            :show-retry="showRetryButton"
            @close="closeErrorDialog"
            @retry="retrySubmission"
        />

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
                        Lengkapi informasi berikut untuk menyiapkan template {{ template.name }} sesuai kebutuhan bisnis Anda.
                    </p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-4xl mx-auto">
                <div>
                    <form @submit.prevent="showConfirmation" class="space-y-8">
                        <!-- Website Details -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Detail Website</h3>
                                    <p class="text-sm text-gray-500">Informasi dasar untuk nama website Anda.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <InputLabel for="website_name" value="Website Name" />
                                    <TextInput
                                        id="website_name"
                                        v-model="formData.website_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="Nama Web Saya"
                                        required
                                    />
                                    <InputError :message="errors.website_name" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Company Branding -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Informasi Perusahaan</h3>
                                    <p class="text-sm text-gray-500">Tampilkan identitas perusahaan pada website.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="company_name" value="Company Name" />
                                    <TextInput
                                        id="company_name"
                                        v-model="formData.company_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="PT Maju Jaya"
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
                                        placeholder="Solusi Terbaik Untuk Anda"
                                    />
                                    <InputError :message="errors.company_tagline" class="mt-2" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <div>
                                    <InputLabel for="company_logo" value="Company Logo" />
                                    <input
                                        type="file"
                                        id="company_logo"
                                        @change="handleCompanyLogoUpload"
                                        accept="image/jpeg,image/png,image/jpg,image/webp"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    />
                                    <p class="mt-1 text-xs text-gray-500">Ukuran file maksimal 1 Mb</p>
                                    <InputError :message="errors.company_logo" class="mt-2" />
                                    <div v-if="imagePreviews.company_logo || resolveMediaPath(formData.company_logo)" class="mt-3">
                                        <img :src="imagePreviews.company_logo || resolveMediaPath(formData.company_logo)" alt="Company Logo Preview" class="h-16 object-contain border border-gray-200 rounded" />
                                    </div>
                                </div>
                                <div>
                                    <InputLabel for="favicon" value="Logo Web" />
                                    <input
                                        type="file"
                                        id="favicon"
                                        @change="handleFaviconUpload"
                                        accept=".ico,image/png"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    />
                                    <p class="mt-1 text-xs text-gray-500">Ukuran file maksimal 1 Mb</p>
                                    <InputError :message="errors.favicon" class="mt-2" />
                                    <div v-if="imagePreviews.favicon || resolveMediaPath(formData.favicon)" class="mt-3">
                                        <img :src="imagePreviews.favicon || resolveMediaPath(formData.favicon)" alt="Favicon Preview" class="h-12 w-12 object-contain border border-gray-200 rounded" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hero Section -->
                        <!-- <div class="bg-white shadow-sm rounded-lg p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Hero Section</h3>
                                    <p class="text-sm text-gray-500">Konten utama yang pertama kali dilihat pengunjung.</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <InputLabel for="hero_title" value="Judul Hero" />
                                    <TextInput
                                        id="hero_title"
                                        v-model="formData.hero_title"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="Selamat Datang"
                                        required
                                    />
                                    <InputError :message="errors.hero_title" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="hero_subtitle" value="Subjudul Hero" />
                                    <textarea
                                        id="hero_subtitle"
                                        v-model="formData.hero_subtitle"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        rows="3"
                                        placeholder="Kami adalah perusahanan yang bergerak di bidang..."
                                        required
                                    ></textarea>
                                    <InputError :message="errors.hero_subtitle" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="hero_background" value="Hero Background" />
                                    <input
                                        type="file"
                                        id="hero_background"
                                        @change="handleHeroBackgroundUpload"
                                        accept="image/jpeg,image/png,image/jpg,image/webp"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    />
                                    <p class="mt-1 text-xs text-gray-500">Ukuran file maksimal 1 Mb</p>
                                    <InputError :message="errors.hero_background" class="mt-2" />
                                    <div v-if="imagePreviews.hero_background || resolveMediaPath(formData.hero_background)" class="mt-3">
                                        <img :src="imagePreviews.hero_background || resolveMediaPath(formData.hero_background)" alt="Hero Background Preview" class="w-full h-48 object-cover border border-gray-200 rounded" />
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <!-- About Section -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Tentang Perusahaan</h3>
                                    <p class="text-sm text-gray-500">Bagikan cerita singkat tentang perusahaan Anda.</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <InputLabel for="about_title" value="Judul Section" />
                                    <TextInput
                                        id="about_title"
                                        v-model="formData.about_title"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="Tentang Kami"
                                    />
                                    <InputError :message="errors.about_title" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="about_content" value="Deskripsi" />
                                    <textarea
                                        id="about_content"
                                        v-model="formData.about_content"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        rows="4"
                                        placeholder="Ceritakan tentang perusahaan Anda..."
                                    ></textarea>
                                    <InputError :message="errors.about_content" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="about_image" value="Gambar" />
                                    <input
                                        type="file"
                                        id="about_image"
                                        @change="handleAboutImageUpload"
                                        accept="image/jpeg,image/png,image/jpg,image/webp"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    />
                                    <p class="mt-1 text-xs text-gray-500">Ukuran file maksimal 1 Mb</p>
                                    <InputError :message="errors.about_image" class="mt-2" />
                                    <div v-if="imagePreviews.about_image || resolveMediaPath(formData.about_image)" class="mt-3">
                                        <img :src="imagePreviews.about_image || resolveMediaPath(formData.about_image)" alt="About Image Preview" class="w-full h-48 object-cover border border-gray-200 rounded" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gallery Photos -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Galeri Foto</h3>
                                    <p class="text-sm text-gray-500">Upload maksimal 4 foto untuk galeri website (setiap foto Ukuran file maksimal 1 Mb).</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div
                                    v-for="(photo, index) in formData.gallery_photos"
                                    :key="index"
                                    class="border border-gray-200 rounded-lg p-4"
                                >
                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="text-sm font-semibold text-gray-900">Foto {{ index + 1 }}</h4>
                                        <button
                                            type="button"
                                            class="text-sm text-red-600 hover:text-red-700"
                                            @click="removeGalleryPhoto(index)"
                                        >
                                            Hapus
                                        </button>
                                    </div>

                                    <div>
                                        <input
                                            :id="'gallery_photo_' + index"
                                            type="file"
                                            accept="image/jpeg,image/png,image/jpg,image/webp"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                            @change="(event) => handleGalleryPhotoUpload(event, index)"
                                        />
                                        <InputError :message="errors[`gallery_photos.${index}`]" class="mt-2" />
                                        <div v-if="galleryPreviews[index] || resolveMediaPath(photo)" class="mt-3">
                                            <img :src="galleryPreviews[index] || resolveMediaPath(photo)" alt="Gallery Photo Preview" class="w-full h-40 object-cover border border-gray-200 rounded" />
                                        </div>
                                    </div>
                                </div>

                                <button
                                    v-if="formData.gallery_photos.length < 4"
                                    type="button"
                                    @click="addGalleryPhoto"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    + Tambah Foto Galeri
                                </button>
                                <p v-else class="text-sm text-amber-600">Maksimal 4 foto galeri telah tercapai.</p>
                            </div>
                        </div>

                        <!-- Products -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Produk Unggulan</h3>
                                    <p class="text-sm text-gray-500">Daftar produk yang ingin ditampilkan (maksimal 4 produk).</p>
                                </div>
                                <button
                                    v-if="formData.products.length < 4"
                                    type="button"
                                    @click="addProduct"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    Tambah Produk
                                </button>
                            </div>

                            <div class="space-y-6">
                                <div
                                    v-for="(product, index) in formData.products"
                                    :key="index"
                                    class="border border-gray-200 rounded-lg p-5"
                                >
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-base font-semibold text-gray-900">Produk {{ index + 1 }}</h4>
                                        <button
                                            type="button"
                                            class="text-sm text-red-600 hover:text-red-700"
                                            @click="removeProduct(index)"
                                            v-if="formData.products.length > 1"
                                        >
                                            Hapus
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <InputLabel :for="'product_name_' + index" value="Nama Produk" />
                                            <TextInput
                                                :id="'product_name_' + index"
                                                v-model="product.name"
                                                type="text"
                                                class="mt-1 block w-full"
                                                placeholder="Produk 1"
                                                required
                                            />
                                            <InputError :message="errors[`products.${index}.name`]" class="mt-2" />
                                        </div>
                                        <div>
                                            <InputLabel :for="'product_price_' + index" value="Harga Produk" />
                                            <TextInput
                                                :id="'product_price_' + index"
                                                v-model="product.price"
                                                type="text"
                                                class="mt-1 block w-full"
                                                placeholder="50000"
                                                required
                                            />
                                            <InputError :message="errors[`products.${index}.price`]" class="mt-2" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <InputLabel :for="'product_link_' + index" value="Link Pembelian" />
                                            <TextInput
                                                :id="'product_link_' + index"
                                                v-model="product.link"
                                                type="text"
                                                class="mt-1 block w-full"
                                                placeholder="https://wa.me/6281234567890?text=..."
                                                required
                                            />
                                            <InputError :message="errors[`products.${index}.link`]" class="mt-2" />
                                        </div>
                                        <div>
                                            <InputLabel :for="'product_image_' + index" value="Gambar Produk" />
                                            <input
                                                :id="'product_image_' + index"
                                                type="file"
                                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                                @change="(event) => handleProductImageUpload(event, index)"
                                            />
                                            <p class="mt-1 text-xs text-gray-500">Ukuran file maksimal 1 Mb</p>
                                            <InputError :message="errors[`products.${index}.image`]" class="mt-2" />
                                            <div v-if="productPreviews[index] || resolveMediaPath(product.image)" class="mt-3">
                                                <img :src="productPreviews[index] || resolveMediaPath(product.image)" alt="Product Preview" class="w-full h-40 object-cover border border-gray-200 rounded" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p v-if="formData.products.length >= 4" class="text-sm text-amber-600">Maksimal 4 produk telah tercapai.</p>
                            </div>
                        </div>

                        <!-- Social Links -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Media Sosial</h3>
                                    <p class="text-sm text-gray-500">Hubungkan pengunjung dengan akun resmi Anda.</p>
                                </div>
                                <button
                                    type="button"
                                    @click="addSocialLink"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    Tambah Link
                                </button>
                            </div>

                            <div v-if="!formData.social_links.length" class="text-sm text-gray-500">
                                Belum ada link sosial. Klik "Tambah Link" untuk menambahkan.
                            </div>

                            <div class="space-y-4" v-else>
                                <div
                                    v-for="(link, index) in formData.social_links"
                                    :key="index"
                                    class="border border-gray-200 rounded-lg p-5"
                                >
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-base font-semibold text-gray-900">Link {{ index + 1 }}</h4>
                                        <button
                                            type="button"
                                            class="text-sm text-red-600 hover:text-red-700"
                                            @click="removeSocialLink(index)"
                                        >
                                            Hapus
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <InputLabel :for="'social_platform_' + index" value="Platform" />
                                            <select
                                                :id="'social_platform_' + index"
                                                v-model="link.platform"
                                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                required
                                            >
                                                <option value="facebook">Facebook</option>
                                                <option value="instagram">Instagram</option>
                                                <option value="twitter">Twitter</option>
                                                <option value="linkedin">LinkedIn</option>
                                                <option value="youtube">YouTube</option>
                                                <option value="tiktok">TikTok</option>
                                            </select>
                                            <InputError :message="errors[`social_links.${index}.platform`]" class="mt-2" />
                                        </div>
                                        <div>
                                            <InputLabel :for="'social_label_' + index" value="Label" />
                                            <TextInput
                                                :id="'social_label_' + index"
                                                v-model="link.label"
                                                type="text"
                                                class="mt-1 block w-full"
                                                placeholder="facebook"
                                                required
                                            />
                                            <InputError :message="errors[`social_links.${index}.label`]" class="mt-2" />
                                        </div>
                                        <div>
                                            <InputLabel :for="'social_url_' + index" value="URL" />
                                            <TextInput
                                                :id="'social_url_' + index"
                                                v-model="link.url"
                                                type="text"
                                                class="mt-1 block w-full"
                                                placeholder="https://fb.lyky.in"
                                                required
                                            />
                                            <InputError :message="errors[`social_links.${index}.url`]" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Testimonials -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Testimoni Pelanggan</h3>
                                    <p class="text-sm text-gray-500">Tunjukkan pengalaman pelanggan terbaik Anda (maksimal 3 testimoni).</p>
                                </div>
                                <button
                                    v-if="formData.testimonials.length < 3"
                                    type="button"
                                    @click="addTestimonial"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    Tambah Testimoni
                                </button>
                            </div>

                            <div class="space-y-6">
                                <div
                                    v-for="(testimonial, index) in formData.testimonials"
                                    :key="index"
                                    class="border border-gray-200 rounded-lg p-5 space-y-6"
                                >
                                    <div class="flex justify-between items-center">
                                        <h4 class="text-base font-semibold text-gray-900">Testimoni {{ index + 1 }}</h4>
                                        <button
                                            type="button"
                                            class="text-sm text-red-600 hover:text-red-700"
                                            @click="removeTestimonial(index)"
                                            v-if="formData.testimonials.length > 1"
                                        >
                                            Hapus
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <InputLabel :for="'testimonial_name_' + index" value="Nama" />
                                            <TextInput
                                                :id="'testimonial_name_' + index"
                                                v-model="testimonial.name"
                                                type="text"
                                                class="mt-1 block w-full"
                                                placeholder="Intan"
                                                required
                                            />
                                            <InputError :message="errors[`testimonials.${index}.name`]" class="mt-2" />
                                        </div>
                                        <div>
                                            <InputLabel :for="'testimonial_position_' + index" value="Jabatan" />
                                            <TextInput
                                                :id="'testimonial_position_' + index"
                                                v-model="testimonial.position"
                                                type="text"
                                                class="mt-1 block w-full"
                                                placeholder="CEO"
                                            />
                                            <InputError :message="errors[`testimonials.${index}.position`]" class="mt-2" />
                                        </div>
                                    </div>

                                    <div>
                                        <InputLabel :for="'testimonial_content_' + index" value="Isi Testimoni" />
                                        <textarea
                                            :id="'testimonial_content_' + index"
                                            v-model="testimonial.content"
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            rows="3"
                                            placeholder="Sangat puas dengan layanan..."
                                            required
                                        ></textarea>
                                        <InputError :message="errors[`testimonials.${index}.content`]" class="mt-2" />
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                                        <div>
                                            <InputLabel :for="'testimonial_photo_' + index" value="Foto" />
                                            <input
                                                :id="'testimonial_photo_' + index"
                                                type="file"
                                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                                @change="(event) => handleTestimonialPhotoUpload(event, index)"
                                            />
                                            <p class="mt-1 text-xs text-gray-500">Ukuran file maksimal 1 Mb</p>
                                            <InputError :message="errors[`testimonials.${index}.photo`]" class="mt-2" />
                                            <div v-if="testimonialPreviews[index] || resolveMediaPath(testimonial.photo)" class="mt-3">
                                                <img :src="testimonialPreviews[index] || resolveMediaPath(testimonial.photo)" alt="Testimonial Photo" class="h-20 w-20 rounded-full object-cover border border-gray-200" />
                                            </div>
                                        </div>
                                        <div>
                                            <InputLabel :for="'testimonial_rating_' + index" value="Rating" />
                                            <div class="flex items-center space-x-2 mt-2">
                                                <template v-for="star in 5" :key="star">
                                                    <button
                                                        type="button"
                                                        class="focus:outline-none"
                                                        @click="testimonial.rating = String(star)"
                                                        :aria-label="`Set rating ${star}`"
                                                    >
                                                        <svg
                                                            class="w-6 h-6"
                                                            :class="Number(testimonial.rating) >= star ? 'text-yellow-400' : 'text-gray-300'"
                                                            fill="currentColor"
                                                            viewBox="0 0 20 20"
                                                        >
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.756 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L4.44 8.72c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z" />
                                                        </svg>
                                                    </button>
                                                </template>
                                            </div>
                                            <InputError :message="errors[`testimonials.${index}.rating`]" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                                <p v-if="formData.testimonials.length >= 3" class="text-sm text-amber-600">Maksimal 3 testimoni telah tercapai.</p>
                            </div>
                        </div>

                        <!-- Contact -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Kontak</h3>
                                    <p class="text-sm text-gray-500">Pastikan pelanggan mudah menghubungi Anda.</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <InputLabel for="contact_address" value="Alamat" />
                                    <textarea
                                        id="contact_address"
                                        v-model="formData.contact_address"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        rows="3"
                                        placeholder="Jl. Mawar No. 123, Jakarta Barat 12000"
                                    ></textarea>
                                    <InputError :message="errors.contact_address" class="mt-2" />
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel for="contact_email" value="Email" />
                                        <TextInput
                                            id="contact_email"
                                            v-model="formData.contact_email"
                                            type="email"
                                            class="mt-1 block w-full"
                                            placeholder="info@domain.com"
                                        />
                                        <InputError :message="errors.contact_email" class="mt-2" />
                                    </div>
                                    <div>
                                        <InputLabel for="contact_phone" value="Nomor Telepon" />
                                        <TextInput
                                            id="contact_phone"
                                            v-model="formData.contact_phone"
                                            type="text"
                                            class="mt-1 block w-full"
                                            placeholder="081234567890"
                                        />
                                        <InputError :message="errors.contact_phone" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- WhatsApp -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">WhatsApp</h3>
                                    <p class="text-sm text-gray-500">Siapkan pesan dan nomor untuk tombol WhatsApp di website.</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel for="whatsapp_number" value="Nomor WhatsApp" />
                                        <TextInput
                                            id="whatsapp_number"
                                            v-model="formData.whatsapp_number"
                                            type="text"
                                            class="mt-1 block w-full"
                                            placeholder="6281234567890"
                                        />
                                        <InputError :message="errors.whatsapp_number" class="mt-2" />
                                    </div>
                                    <div>
                                        <InputLabel for="whatsapp_greeting_text" value="Teks Tombol WhatsApp" />
                                        <TextInput
                                            id="whatsapp_greeting_text"
                                            v-model="formData.whatsapp_greeting_text"
                                            type="text"
                                            class="mt-1 block w-full"
                                            placeholder="Chat Kami"
                                        />
                                        <InputError :message="errors.whatsapp_greeting_text" class="mt-2" />
                                    </div>
                                </div>
                                <div>
                                    <InputLabel for="whatsapp_message" value="Pesan Awal WhatsApp" />
                                    <textarea
                                        id="whatsapp_message"
                                        v-model="formData.whatsapp_message"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        rows="3"
                                        placeholder="Halo, saya tertarik dengan layanan Anda"
                                    ></textarea>
                                    <InputError :message="errors.whatsapp_message" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                :disabled="submitting"
                            >
                                <svg v-if="submitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ submitting ? 'Menyimpan...' : 'Simpan & Pratinjau' }}
                            </button>
                        </div>
                    </form>
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
import SimpleConfirmationDialog from '@/Components/SimpleConfirmationDialog.vue'
import ErrorDialog from '@/Components/ErrorDialog.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
const props = defineProps({
    template: Object,
    user: Object,
    form_fields: Array,
    pricing: Object,
})

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
    hero_background: null,
    about_image: null,
})

const productPreviews = ref([])
const testimonialPreviews = ref([])
const galleryPreviews = ref([])
const previewRegistry = new Set()

const formData = reactive({
    website_name: '',
    company_name: '',
    company_tagline: '',
    company_logo: null,
    favicon: null,
    //hero_title: '',
    //hero_subtitle: '',
    //hero_background: null,
    about_title: '',
    about_content: '',
    about_image: null,
    gallery_photos: [],
    products: [
        {
            name: '',
            price: '',
            link: '',
            image: null,
        },
    ],
    social_links: [],
    testimonials: [
        {
            name: '',
            photo: null,
            rating: '5',
            content: '',
            position: '',
        },
    ],
    contact_address: '',
    contact_email: '',
    contact_phone: '',
    whatsapp_number: '',
    whatsapp_greeting_text: '',
    whatsapp_message: '',

})

productPreviews.value = formData.products.map(() => null)
testimonialPreviews.value = formData.testimonials.map(() => null)

const errors = computed(() => usePage().props.errors || {})

const FILE_SIZE_LIMIT = 1 // 1MB untuk semua file

const createPreviewUrl = (file) => {
    const url = URL.createObjectURL(file)
    previewRegistry.add(url)
    return url
}

const releasePreviewUrl = (url) => {
    if (!url || typeof url !== 'string' || !url.startsWith('blob:')) {
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

const validateFileSize = (file, limitMB = FILE_SIZE_LIMIT) => {
    if (file.size > limitMB * 1024 * 1024) {
        alert(`File terlalu besar. Maksimal ${limitMB}MB.`)
        return false
    }
    return true
}

const updateImageField = (field, file) => {
    if (!file) {
        formData[field] = null
        if (imagePreviews[field]) {
            releasePreviewUrl(imagePreviews[field])
            imagePreviews[field] = null
        }
        return
    }

    if (!validateFileSize(file)) {
        return
    }

    formData[field] = file

    if (imagePreviews[field]) {
        releasePreviewUrl(imagePreviews[field])
    }

    imagePreviews[field] = createPreviewUrl(file)
}

const handleFileUpload = (event, field) => {
    const file = event.target.files?.[0]
    event.target.value = ''

    if (!file) {
        return
    }

    updateImageField(field, file)
}

const handleCompanyLogoUpload = (event) => handleFileUpload(event, 'company_logo')
const handleFaviconUpload = (event) => handleFileUpload(event, 'favicon')
const handleHeroBackgroundUpload = (event) => handleFileUpload(event, 'hero_background')
const handleAboutImageUpload = (event) => handleFileUpload(event, 'about_image')

// Gallery Photo Handlers
const addGalleryPhoto = () => {
    if (formData.gallery_photos.length >= 4) {
        return
    }

    formData.gallery_photos.push(null)
    galleryPreviews.value.push(null)
}

const removeGalleryPhoto = (index) => {
    if (galleryPreviews.value[index]) {
        releasePreviewUrl(galleryPreviews.value[index])
    }

    formData.gallery_photos.splice(index, 1)
    galleryPreviews.value.splice(index, 1)
}

const handleGalleryPhotoUpload = (event, index) => {
    const file = event.target.files?.[0]
    event.target.value = ''

    if (!file || !formData.gallery_photos.hasOwnProperty(index)) {
        return
    }

    if (!validateFileSize(file)) {
        return
    }

    if (galleryPreviews.value[index]) {
        releasePreviewUrl(galleryPreviews.value[index])
    }

    formData.gallery_photos[index] = file
    galleryPreviews.value[index] = createPreviewUrl(file)
}

// Product Handlers
const addProduct = () => {
    if (formData.products.length >= 4) {
        return
    }

    formData.products.push({
        name: '',
        price: '',
        link: '',
        image: null,
    })
    productPreviews.value.push(null)
}

const removeProduct = (index) => {
    const product = formData.products[index]
    if (!product) {
        return
    }

    if (productPreviews.value[index]) {
        releasePreviewUrl(productPreviews.value[index])
    }

    formData.products.splice(index, 1)
    productPreviews.value.splice(index, 1)

    if (!formData.products.length) {
        addProduct()
    }
}

const handleProductImageUpload = (event, index) => {
    const file = event.target.files?.[0]
    event.target.value = ''

    if (!file || !formData.products[index]) {
        return
    }

    if (!validateFileSize(file)) {
        return
    }

    if (productPreviews.value[index]) {
        releasePreviewUrl(productPreviews.value[index])
    }

    formData.products[index].image = file
    productPreviews.value[index] = createPreviewUrl(file)
}

// Social Link Handlers
const addSocialLink = () => {
    if (formData.social_links.length >= 10) {
        return
    }

    formData.social_links.push({
        platform: 'facebook',
        label: '',
        url: '',
    })
}

const removeSocialLink = (index) => {
    formData.social_links.splice(index, 1)
}

// Testimonial Handlers
const addTestimonial = () => {
    if (formData.testimonials.length >= 3) {
        return
    }

    formData.testimonials.push({
        name: '',
        photo: null,
        rating: '5',
        content: '',
        position: '',
    })
    testimonialPreviews.value.push(null)
}

const removeTestimonial = (index) => {
    if (!formData.testimonials[index]) {
        return
    }

    if (testimonialPreviews.value[index]) {
        releasePreviewUrl(testimonialPreviews.value[index])
    }

    testimonialPreviews.value.splice(index, 1)
    formData.testimonials.splice(index, 1)

    if (!formData.testimonials.length) {
        addTestimonial()
    }
}

const handleTestimonialPhotoUpload = (event, index) => {
    const file = event.target.files?.[0]
    event.target.value = ''

    if (!file || !formData.testimonials[index]) {
        return
    }

    if (!validateFileSize(file)) {
        return
    }

    if (testimonialPreviews.value[index]) {
        releasePreviewUrl(testimonialPreviews.value[index])
    }

    formData.testimonials[index].photo = file
    testimonialPreviews.value[index] = createPreviewUrl(file)
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
        },
    })
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

onBeforeUnmount(() => {
    Object.values(imagePreviews).forEach((preview) => releasePreviewUrl(preview))
    productPreviews.value.forEach((preview) => releasePreviewUrl(preview))
    testimonialPreviews.value.forEach((preview) => releasePreviewUrl(preview))
    galleryPreviews.value.forEach((preview) => releasePreviewUrl(preview))
    previewRegistry.clear()
})
</script>
