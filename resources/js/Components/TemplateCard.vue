<template>
    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden group">
        <!-- Template Preview -->
        <div class="relative overflow-hidden">
            <img
                :src="template.preview_image || '/default-avatar.png'"
                :alt="template.name"
                class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                loading="lazy"
                @error="handleImageError"
                @load="handleImageLoad"
                style="opacity: 1;"
            >

            <!-- Loading placeholder -->
            <div v-if="!imageLoaded && !imageError" class="absolute inset-0 bg-gray-200 animate-pulse flex items-center justify-center">
                <div class="text-gray-400 text-sm">Loading...</div>
            </div>

            <!-- Overlay on hover -->
            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                <div class="space-x-3">
                    <Link
                        v-if="template.demo_url"
                        :href="`/preview/${template.slug}`"
                        class="bg-white text-gray-800 px-4 py-2 rounded-md hover:bg-gray-100 transition duration-200 text-sm font-medium"
                    >
                        Live Preview
                    </Link>

                    <Link
                        :href="`/templates/${template.slug}`"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200 text-sm font-medium"
                    >
                        View Details
                    </Link>
                </div>
            </div>

            <!-- Category Badge -->
            <div v-if="template.category" class="absolute top-3 left-3">
                <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full font-medium">
                    {{ formatCategory(template.category) }}
                </span>
            </div>

            <!-- Price Badge -->
            <div class="absolute top-3 right-3">
                <span class="bg-green-600 text-white text-sm px-3 py-1 rounded-full font-bold">
                    {{ template.price > 0 ? formatPrice(template.price) : 'FREE' }}
                </span>
            </div>
        </div>

        <!-- Template Info -->
        <div class="p-4">
            <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-1">
                {{ template.name }}
            </h3>

            <p v-if="template.description" class="text-gray-600 text-sm mb-4 line-clamp-2">
                {{ template.description }}
            </p>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <Link
                    :href="`/templates/${template.slug}`"
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 text-center py-2 px-4 rounded-md transition duration-200 text-sm font-medium"
                >
                    Details
                </Link>

                <Link
                    v-if="$page.props.auth.user"
                    :href="`/templates/${template.slug}/checkout`"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md transition duration-200 text-sm font-medium"
                >
                    Order Now
                </Link>
                <Link
                    v-else
                    :href="`/login?redirect=/templates/${template.slug}`"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md transition duration-200 text-sm font-medium"
                >
                    Login to Order
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    template: Object
})

const imageLoaded = ref(false)
const imageError = ref(false)

const formatCategory = (category) => {
    return category.split('-').map(word =>
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ')
}

const formatPrice = (price) => {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(price)
}

const handleImageError = (event) => {
    imageError.value = true
    imageLoaded.value = false
    event.target.src = '/default-avatar.png'
}

const handleImageLoad = () => {
    imageLoaded.value = true
    imageError.value = false
}
</script>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
