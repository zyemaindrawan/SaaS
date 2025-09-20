<template>
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <Link href="/" class="text-gray-400 hover:text-gray-500 transition duration-200">
                    <HomeIcon class="w-5 h-5" />
                    <span class="sr-only">Home</span>
                </Link>
            </li>
            
            <li v-for="(breadcrumb, index) in breadcrumbs" :key="index">
                <div class="flex items-center">
                    <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-300 mr-4" />
                    
                    <Link 
                        v-if="breadcrumb.href"
                        :href="breadcrumb.href" 
                        class="text-gray-400 hover:text-gray-500 transition duration-200 text-sm font-medium"
                    >
                        {{ breadcrumb.name }}
                    </Link>
                    
                    <span 
                        v-else
                        class="text-gray-500 font-medium text-sm"
                    >
                        {{ breadcrumb.name }}
                    </span>
                </div>
            </li>
        </ol>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { HomeIcon, ChevronRightIcon } from '@heroicons/vue/20/solid'

defineProps({
    breadcrumbs: {
        type: Array,
        required: true
    }
})
</script>
