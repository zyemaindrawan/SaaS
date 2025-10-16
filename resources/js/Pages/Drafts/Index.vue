<template>
    <AppLayout title="My Drafts">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">My Drafts</h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    Manage your draft websites and complete your orders
                                </p>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ drafts.length }} draft{{ drafts.length !== 1 ? 's' : '' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Drafts List -->
                <div v-if="drafts.length > 0" class="space-y-4">
                    <div 
                        v-for="draft in drafts" 
                        :key="draft.id"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200"
                    >
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <h3 class="text-lg font-medium text-gray-900">
                                            {{ draft.website_name }}
                                        </h3>
                                        <span 
                                            :class="statusClass(draft.status)"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        >
                                            {{ statusText(draft.status) }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600">
                                        Template: {{ draft.template_name }}
                                    </p>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 space-x-4">
                                        <span>Created: {{ draft.created_at }}</span>
                                        <span>•</span>
                                        <span>Updated: {{ draft.updated_at }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <Link
                                        :href="route('preview.show', draft.id)"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Preview
                                    </Link>
                                    <Link
                                        :href="route('drafts.show', draft.id)"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        Continue
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No drafts yet</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Start by choosing a template and creating your first website.
                        </p>
                        <div class="mt-6">
                            <Link
                                :href="route('templates.index')"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Browse Templates
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
    drafts: Array,
    breadcrumbs: Array
})

// Helper function for resolving asset paths
const resolveAssetPath = (path) => {
    if (!path) return null
    
    // If already a full URL, return as is
    if (path.startsWith('http://') || path.startsWith('https://')) {
        return path
    }
    
    // If starts with 'website-assets/', add '/storage/' prefix
    if (path.startsWith('website-assets/')) {
        return `/storage/${path}`
    }
    
    // If starts with 'storage/', return as is
    if (path.startsWith('storage/')) {
        return `/${path}`
    }
    
    // If starts with '/' already, return as is
    if (path.startsWith('/')) {
        return path
    }
    
    // Default: assume relative from storage
    return `/storage/${path}`
}

const statusClass = (status) => {
    const classes = {
        'draft': 'bg-yellow-100 text-yellow-800',
        'previewed': 'bg-blue-100 text-blue-800',
        'paid': 'bg-green-100 text-green-800',
        'active': 'bg-green-100 text-green-800'
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
}

const statusText = (status) => {
    const texts = {
        'draft': 'Draft',
        'previewed': 'Previewed',
        'paid': 'Paid',
        'active': 'Active'
    }
    return texts[status] || status
}
</script>