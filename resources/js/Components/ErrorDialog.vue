<template>
    <TransitionRoot as="template" :show="show">
        <Dialog as="div" class="relative z-50" @close="$emit('close')">
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            <!-- Header -->
                            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <ExclamationTriangleIcon class="h-6 w-6 text-yellow-300" />
                                    </div>
                                    <div class="ml-3">
                                        <DialogTitle as="h3" class="text-lg font-semibold text-white">
                                            {{ title }}
                                        </DialogTitle>
                                    </div>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="px-6 py-4">
                                <div class="mb-6">
                                    <!-- Error Message -->
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-700 mb-3">{{ message }}</p>
                                    </div>

                                    <!-- Error Details (if available) -->
                                    <div v-if="details" class="bg-red-50 border border-red-200 rounded-lg p-4">
                                        <h5 class="font-semibold text-red-900 mb-2">Technical Details:</h5>
                                        <div class="text-sm text-red-800 space-y-1">
                                            <!-- Database/SQL Error Details -->
                                            <div v-if="details.sql" class="bg-red-100 rounded p-2 font-mono text-xs">
                                                <strong>SQL Error:</strong><br>
                                                {{ details.sql }}
                                            </div>
                                            
                                            <!-- Connection Details -->
                                            <div v-if="details.connection">
                                                <strong>Connection:</strong> {{ details.connection }}
                                            </div>
                                            
                                            <!-- Error Code -->
                                            <div v-if="details.code">
                                                <strong>Error Code:</strong> {{ details.code }}
                                            </div>
                                            
                                            <!-- Missing Field Information -->
                                            <div v-if="details.field" class="bg-yellow-100 border border-yellow-300 rounded p-2">
                                                <strong>Missing Required Field:</strong> {{ details.field }}
                                                <br>
                                                <span class="text-sm">This field is required but was not provided in the request.</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Suggested Actions -->
                                    <div v-if="suggestions.length > 0" class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <h5 class="font-semibold text-blue-900 mb-2">Suggested Actions:</h5>
                                        <ul class="text-sm text-blue-800 space-y-1">
                                            <li v-for="suggestion in suggestions" :key="suggestion" class="flex items-start">
                                                <span class="text-blue-600 mr-2">•</span>
                                                {{ suggestion }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse">
                                <button
                                    type="button"
                                    class="inline-flex w-full justify-center rounded-md bg-red-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-red-700 sm:ml-3 sm:w-auto"
                                    @click="$emit('close')"
                                >
                                    Got it
                                </button>
                                <button
                                    v-if="showRetry"
                                    type="button"
                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-3 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                                    @click="$emit('retry')"
                                >
                                    Try Again
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline'
import { computed } from 'vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: 'An Error Occurred'
    },
    message: {
        type: String,
        required: true
    },
    error: {
        type: [Object, String],
        default: null
    },
    showRetry: {
        type: Boolean,
        default: false
    }
})

defineEmits(['close', 'retry'])

// Parse error details from the error object
const details = computed(() => {
    if (!props.error) return null
    
    let errorData = props.error
    
    // If error is a string, try to parse it as JSON
    if (typeof errorData === 'string') {
        try {
            errorData = JSON.parse(errorData)
        } catch (e) {
            // If parsing fails, look for specific error patterns
            if (errorData.includes('SQLSTATE')) {
                return parseSQLError(errorData)
            }
            return null
        }
    }
    
    // If error is already an object, extract relevant details
    if (typeof errorData === 'object') {
        const details = {}
        
        if (errorData.message && errorData.message.includes('SQLSTATE')) {
            return parseSQLError(errorData.message)
        }
        
        if (errorData.sql) details.sql = errorData.sql
        if (errorData.connection) details.connection = errorData.connection
        if (errorData.code) details.code = errorData.code
        
        return Object.keys(details).length > 0 ? details : null
    }
    
    return null
})

// Parse SQL error messages to extract useful information
const parseSQLError = (errorMessage) => {
    const details = {}
    
    // Extract SQLSTATE code
    const sqlStateMatch = errorMessage.match(/SQLSTATE\[([^\]]+)\]/)
    if (sqlStateMatch) {
        details.code = sqlStateMatch[1]
    }
    
    // Extract connection info
    const connectionMatch = errorMessage.match(/Connection: ([^,)]+)/)
    if (connectionMatch) {
        details.connection = connectionMatch[1]
    }
    
    // Extract field name for missing default value errors
    const fieldMatch = errorMessage.match(/Field '([^']+)' doesn't have a default value/)
    if (fieldMatch) {
        details.field = fieldMatch[1]
    }
    
    // Extract SQL statement
    const sqlMatch = errorMessage.match(/SQL: ([^)]+)\)/)
    if (sqlMatch) {
        details.sql = sqlMatch[1].trim()
    }
    
    return details
}

// Generate suggestions based on error type
const suggestions = computed(() => {
    const suggestions = []
    
    if (details.value?.field) {
        suggestions.push(`Make sure the "${details.value.field}" field is properly filled in the form`)
        suggestions.push('Check if all required fields are completed before submitting')
    }
    
    if (details.value?.code === 'HY000') {
        suggestions.push('Verify that all required information is provided')
        suggestions.push('Try refreshing the page and submitting again')
    }
    
    if (props.message.includes('Inertia')) {
        suggestions.push('This appears to be a server communication issue')
        suggestions.push('Please try again in a few moments')
        suggestions.push('If the problem persists, contact support')
    }
    
    if (suggestions.length === 0) {
        suggestions.push('Please review your input and try again')
        suggestions.push('If the problem continues, contact our support team')
    }
    
    return suggestions
})
</script>