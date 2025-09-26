<template>
    <AppLayout>
        <Head title="Templates" />

        <!-- Loading Component -->
        <PageLoader :show="loading" />

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center">

                    <h1 class="text-4xl md:text-6xl font-bold mb-6">
                        Choose Your Perfect Template
                    </h1>
                    <p class="text-xl md:text-2xl opacity-90 max-w-3xl mx-auto mb-8">
                        Professional, modern website templates ready to customize
                    </p>

                    <!-- Quick Search -->
                    <div class="max-w-md mx-auto">
                        <div class="relative">
                            <MagnifyingGlassIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
                            <input
                                v-model="quickSearch"
                                @keyup.enter="performQuickSearch"
                                type="text"
                                placeholder="Search templates..."
                                class="w-full pl-10 pr-4 py-3 text-gray-900 rounded-lg border-0 shadow-lg focus:ring-4 focus:ring-white focus:ring-opacity-30 focus:outline-none"
                            >
                            <button
                                @click="performQuickSearch"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200"
                            >
                                Search
                            </button>
                        </div>
                    </div>
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

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Filters & Search Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <FunnelIcon class="w-5 h-5 mr-2" />
                        Filter Templates
                    </h3>
                </div>

                <form @submit.prevent="applyFilters" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

                        <!-- Search Input -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                Search Templates
                            </label>
                            <div class="relative">
                                <MagnifyingGlassIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                                <input
                                    id="search"
                                    v-model="filterForm.search"
                                    type="text"
                                    placeholder="Template name or description..."
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                >
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Category
                            </label>
                            <select
                                id="category"
                                v-model="filterForm.category"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                            >
                                <option value="all">All Categories</option>
                                <option
                                    v-for="category in categories"
                                    :key="category"
                                    :value="category"
                                >
                                    {{ formatCategory(category) }}
                                </option>
                            </select>
                        </div>

                        <!-- Price Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Price Range
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <input
                                    v-model.number="filterForm.price_min"
                                    type="number"
                                    placeholder="Min"
                                    :min="0"
                                    :max="priceRange.max_price"
                                    class="px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 text-sm"
                                >
                                <input
                                    v-model.number="filterForm.price_max"
                                    type="number"
                                    placeholder="Max"
                                    :min="filterForm.price_min || 0"
                                    :max="priceRange.max_price"
                                    class="px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 text-sm"
                                >
                            </div>
                            <div class="mt-1 text-xs text-gray-500">
                                Range: Rp {{ formatPrice(priceRange.min_price) }} - Rp {{ formatPrice(priceRange.max_price) }}
                            </div>
                        </div>

                        <!-- Sort Options -->
                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">
                                Sort By
                            </label>
                            <select
                                id="sort"
                                v-model="filterForm.sort"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                            >
                                <option value="sort_order">Featured</option>
                                <option value="price_low">Price: Low to High</option>
                                <option value="price_high">Price: High to Low</option>
                                <option value="name">Name A-Z</option>
                                <option value="newest">Newest First</option>
                                <option value="popular">Most Popular</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                        <div class="flex gap-4">
                            <button
                                type="submit"
                                :disabled="loading"
                                class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-8 py-3 rounded-lg transition duration-200 font-medium shadow-sm hover:shadow-md disabled:cursor-not-allowed"
                            >
                                <span v-if="loading">
                                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Applying Filters...
                                </span>
                                <span v-else>Apply Filters</span>
                            </button>

                            <button
                                @click="clearFilters"
                                type="button"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition duration-200 font-medium"
                            >
                                Clear All
                            </button>
                        </div>

                        <!-- View Toggle -->
                        <div class="flex items-center bg-gray-100 rounded-lg p-1">
                            <button
                                @click="viewMode = 'grid'"
                                :class="[
                                    'px-3 py-2 rounded-md text-sm font-medium transition duration-200',
                                    viewMode === 'grid'
                                        ? 'bg-white text-gray-900 shadow-sm'
                                        : 'text-gray-600 hover:text-gray-900'
                                ]"
                            >
                                <Squares2X2Icon class="w-4 h-4 mr-1 inline" />
                                Grid
                            </button>
                            <button
                                @click="viewMode = 'list'"
                                :class="[
                                    'px-3 py-2 rounded-md text-sm font-medium transition duration-200',
                                    viewMode === 'list'
                                        ? 'bg-white text-gray-900 shadow-sm'
                                        : 'text-gray-600 hover:text-gray-900'
                                ]"
                            >
                                <ListBulletIcon class="w-4 h-4 mr-1 inline" />
                                List
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Active Filters Display -->
            <div v-if="hasActiveFilters" class="mb-6">
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="text-sm font-medium text-gray-700 mr-2">Active Filters:</span>

                    <span
                        v-if="filters.search"
                        class="inline-flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm"
                    >
                        Search: "{{ filters.search }}"
                        <button @click="clearFilter('search')" class="ml-2 hover:text-blue-600">
                            <XMarkIcon class="w-3 h-3" />
                        </button>
                    </span>

                    <span
                        v-if="filters.category && filters.category !== 'all'"
                        class="inline-flex items-center bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm"
                    >
                        Category: {{ formatCategory(filters.category) }}
                        <button @click="clearFilter('category')" class="ml-2 hover:text-green-600">
                            <XMarkIcon class="w-3 h-3" />
                        </button>
                    </span>

                    <span
                        v-if="filters.price_min || filters.price_max"
                        class="inline-flex items-center bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm"
                    >
                        Price:
                        <template v-if="filters.price_min">
                            Rp {{ formatPrice(filters.price_min) }}+
                        </template>
                        <template v-if="filters.price_min && filters.price_max">
                            -
                        </template>
                        <template v-if="filters.price_max">
                            Rp {{ formatPrice(filters.price_max) }}
                        </template>
                        <button @click="clearPriceFilter" class="ml-2 hover:text-purple-600">
                            <XMarkIcon class="w-3 h-3" />
                        </button>
                    </span>
                </div>
            </div>

            <!-- Results Info -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <p class="text-gray-600">
                        Showing {{ templates.from || 0 }} - {{ templates.to || 0 }} of {{ templates.total }} templates
                    </p>
                    <p v-if="hasActiveFilters" class="text-sm text-gray-500">
                        Filtered results
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Quick Sort -->
                    <div class="flex items-center">
                        <label for="quick-sort" class="text-sm text-gray-600 mr-2">Quick Sort:</label>
                        <select
                            id="quick-sort"
                            v-model="filterForm.sort"
                            @change="applyFilters"
                            class="text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="sort_order">Featured</option>
                            <option value="price_low">Price ↑</option>
                            <option value="price_high">Price ↓</option>
                            <option value="newest">Newest</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Templates Display -->
            <div v-if="templates.data.length > 0">

                <!-- Grid View -->
                <div
                    v-if="viewMode === 'grid'"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12"
                >
                    <TemplateCard
                        v-for="template in templates.data"
                        :key="template.id"
                        :template="template"
                        :view-mode="viewMode"
                        @template-selected="handleTemplateSelected"
                    />
                </div>

                <!-- List View -->
                <div
                    v-else
                    class="space-y-6 mb-12"
                >
                    <TemplateCard
                        v-for="template in templates.data"
                        :key="template.id"
                        :template="template"
                        :view-mode="viewMode"
                        @template-selected="handleTemplateSelected"
                    />
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    <Pagination :links="templates.links" />
                </div>
            </div>

            <!-- No Templates Found -->
            <div v-else class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5l7-7 7 7M9 20h6"/>
                    </svg>

                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No templates found</h3>
                    <p class="text-gray-500 mb-6">
                        We couldn't find any templates matching your criteria. Try adjusting your search filters.
                    </p>

                    <div class="space-y-4">
                        <button
                            @click="clearFilters"
                            class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition duration-200"
                        >
                            View All Templates
                        </button>

                        <div class="text-sm text-gray-500">
                            <p>Or try searching for:</p>
                            <div class="flex flex-wrap justify-center gap-2 mt-2">
                                <button
                                    v-for="suggestion in searchSuggestions"
                                    :key="suggestion"
                                    @click="applySuggestion(suggestion)"
                                    class="text-blue-600 hover:text-blue-500 hover:underline"
                                >
                                    {{ suggestion }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popular Categories Section -->
            <div v-if="categories.length > 0" class="mt-16">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Browse by Category</h2>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            <button
                                v-for="category in categories"
                                :key="category"
                                @click="filterByCategory(category)"
                                class="flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition duration-200 group"
                            >
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mb-2 group-hover:scale-105 transition duration-200">
                                    <component :is="getCategoryIcon(category)" class="w-6 h-6 text-white" />
                                </div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-blue-600 text-center">
                                    {{ formatCategory(category) }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Alert from '@/Components/Alert.vue'
import TemplateCard from '@/Components/TemplateCard.vue'
import Pagination from '@/Components/Pagination.vue'
import PageLoader from '@/Components/PageLoader.vue'

// Icons
import {
    MagnifyingGlassIcon,
    FunnelIcon,
    XMarkIcon,
    Squares2X2Icon,
    ListBulletIcon,
    BuildingOfficeIcon,
    BriefcaseIcon,
    ShoppingBagIcon,
    AcademicCapIcon,
    HeartIcon,
    CameraIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    templates: Object,
    categories: Array,
    priceRange: Object,
    filters: Object
})

// Reactive state
const loading = ref(true)
const viewMode = ref('grid')
const quickSearch = ref('')

const filterForm = reactive({
    search: props.filters.search || '',
    category: props.filters.category || 'all',
    price_min: props.filters.price_min || '',
    price_max: props.filters.price_max || '',
    sort: props.filters.sort || 'sort_order'
})

// Computed properties
const hasActiveFilters = computed(() => {
    return props.filters.search ||
           (props.filters.category && props.filters.category !== 'all') ||
           props.filters.price_min ||
           props.filters.price_max
})

const searchSuggestions = computed(() => {
    return ['corporate', 'portfolio', 'ecommerce', 'landing page', 'blog']
})

// Methods
const formatCategory = (category) => {
    if (!category) return ''
    return category.split('-').map(word =>
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ')
}

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID').format(price)
}

const getCategoryIcon = (category) => {
    const icons = {
        'corporate': BuildingOfficeIcon,
        'business': BriefcaseIcon,
        'ecommerce': ShoppingBagIcon,
        'education': AcademicCapIcon,
        'portfolio': CameraIcon,
        'personal': HeartIcon
    }
    return icons[category] || BuildingOfficeIcon
}

const applyFilters = () => {
    loading.value = true

    const params = {}

    if (filterForm.search) params.search = filterForm.search
    if (filterForm.category !== 'all') params.category = filterForm.category
    if (filterForm.price_min) params.price_min = filterForm.price_min
    if (filterForm.price_max) params.price_max = filterForm.price_max
    if (filterForm.sort !== 'sort_order') params.sort = filterForm.sort

    router.get('/templates', params, {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => loading.value = false
    })
}

const clearFilters = () => {
    filterForm.search = ''
    filterForm.category = 'all'
    filterForm.price_min = ''
    filterForm.price_max = ''
    filterForm.sort = 'sort_order'

    router.get('/templates', {}, {
        preserveState: true,
        preserveScroll: true
    })
}

const clearFilter = (filterKey) => {
    if (filterKey === 'search') {
        filterForm.search = ''
    } else if (filterKey === 'category') {
        filterForm.category = 'all'
    }
    applyFilters()
}

const clearPriceFilter = () => {
    filterForm.price_min = ''
    filterForm.price_max = ''
    applyFilters()
}

const performQuickSearch = () => {
    if (quickSearch.value.trim()) {
        filterForm.search = quickSearch.value.trim()
        applyFilters()
    }
}

const filterByCategory = (category) => {
    filterForm.category = category
    filterForm.search = ''
    applyFilters()
}

const applySuggestion = (suggestion) => {
    filterForm.search = suggestion
    filterForm.category = 'all'
    applyFilters()
}

const handleTemplateSelected = (template) => {
    router.visit(`/templates/${template.slug}`)
}

// Lifecycle
onMounted(() => {
    setTimeout(() => {
        loading.value = false
    }, 1000)

    // Preload template images with error handling
    if (props.templates.data) {
        props.templates.data.forEach((template, index) => {
            if (template.preview_image) {
                const img = new Image()
                img.onload = () => {
                    // Image loaded successfully
                    //console.log('Image loaded:', template.preview_image)
                }
                img.onerror = () => {
                    // Image failed to load, let getPreviewUrl handle the fallback
                    //console.error('Image failed to load:', template.preview_image)
                }
                img.src = template.preview_image
            }
        })
    }
})
</script>

<style scoped>
/* Custom animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.template-grid {
    animation: fadeIn 0.6s ease-out;
}

/* Grid transition animations */
.grid > * {
    animation: fadeIn 0.6s ease-out;
    animation-delay: calc(var(--index) * 0.1s);
}

/* Custom scrollbar for better UX */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Responsive utilities */
@media (max-width: 640px) {
    .hero-title {
        font-size: 2.5rem;
        line-height: 1.1;
    }
}
</style>
