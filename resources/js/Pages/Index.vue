<template>
    <AppLayout>
        <Head title="Website Templates" />

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        Choose Your Perfect Template
                    </h1>
                    <p class="text-xl opacity-90 max-w-2xl mx-auto">
                        Professional, modern website templates ready to customize for your business
                    </p>
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
            <!-- Filters & Search -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
                <form @submit.prevent="applyFilters" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input 
                                v-model="filterForm.search"
                                type="text" 
                                placeholder="Search templates..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select 
                                v-model="filterForm.category"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
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

                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                            <div class="flex gap-2">
                                <input 
                                    v-model="filterForm.price_min"
                                    type="number" 
                                    placeholder="Min"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                <input 
                                    v-model="filterForm.price_max"
                                    type="number" 
                                    placeholder="Max"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                            <select 
                                v-model="filterForm.sort"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="sort_order">Featured</option>
                                <option value="price_low">Price: Low to High</option>
                                <option value="price_high">Price: High to Low</option>
                                <option value="name">Name A-Z</option>
                                <option value="newest">Newest First</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button 
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md transition duration-200"
                            :disabled="loading"
                        >
                            <span v-if="loading">Applying...</span>
                            <span v-else>Apply Filters</span>
                        </button>
                        
                        <button 
                            @click="clearFilters"
                            type="button"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition duration-200"
                        >
                            Clear All
                        </button>
                    </div>
                </form>
            </div>

            <!-- Results Info -->
            <div class="flex justify-between items-center mb-6">
                <p class="text-gray-600">
                    Showing {{ templates.from || 0 }} - {{ templates.to || 0 }} of {{ templates.total }} templates
                </p>
                
                <div class="flex flex-wrap gap-2" v-if="hasActiveFilters">
                    <span 
                        v-if="filters.search"
                        class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm"
                    >
                        Search: "{{ filters.search }}"
                    </span>
                    
                    <span 
                        v-if="filters.category && filters.category !== 'all'"
                        class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm"
                    >
                        Category: {{ formatCategory(filters.category) }}
                    </span>
                    
                    <span 
                        v-if="filters.price_min || filters.price_max"
                        class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm"
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
                    </span>
                </div>
            </div>

            <!-- Templates Grid -->
            <div v-if="templates.data.length > 0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    <TemplateCard 
                        v-for="template in templates.data" 
                        :key="template.id" 
                        :template="template"
                    />
                </div>

                <!-- Pagination -->
                <Pagination :links="templates.links" />
            </div>

            <!-- No Templates Found -->
            <div v-else class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A9.971 9.971 0 0124 24c4.21 0 7.813 2.602 9.288 6.286" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
                    
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No templates found</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Try adjusting your search filters to find what you're looking for.
                    </p>
                    
                    <div class="mt-6">
                        <button 
                            @click="clearFilters"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                        >
                            View All Templates
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Alert from '@/Components/Alert.vue'
import TemplateCard from '@/Components/TemplateCard.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
    templates: Object,
    categories: Array,
    priceRange: Object,
    filters: Object
})

const loading = ref(false)

const filterForm = ref({
    search: props.filters.search || '',
    category: props.filters.category || 'all',
    price_min: props.filters.price_min || '',
    price_max: props.filters.price_max || '',
    sort: props.filters.sort || 'sort_order'
})

const hasActiveFilters = computed(() => {
    return props.filters.search || 
           (props.filters.category && props.filters.category !== 'all') || 
           props.filters.price_min || 
           props.filters.price_max
})

const formatCategory = (category) => {
    return category.split('-').map(word => 
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ')
}

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID').format(price)
}

const applyFilters = () => {
    loading.value = true
    
    const params = {}
    
    if (filterForm.value.search) params.search = filterForm.value.search
    if (filterForm.value.category !== 'all') params.category = filterForm.value.category
    if (filterForm.value.price_min) params.price_min = filterForm.value.price_min
    if (filterForm.value.price_max) params.price_max = filterForm.value.price_max
    if (filterForm.value.sort !== 'sort_order') params.sort = filterForm.value.sort

    router.get('/templates', params, {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => loading.value = false
    })
}

const clearFilters = () => {
    filterForm.value = {
        search: '',
        category: 'all',
        price_min: '',
        price_max: '',
        sort: 'sort_order'
    }
    
    router.get('/templates', {}, {
        preserveState: true,
        preserveScroll: true
    })
}

// Auto-apply filters when sort changes
watch(() => filterForm.value.sort, () => {
    applyFilters()
})
</script>
