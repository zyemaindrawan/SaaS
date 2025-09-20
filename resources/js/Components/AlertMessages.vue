<template>
    <div v-if="hasMessages" class="space-y-4">
        <!-- Success Message -->
        <transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 transform scale-95 translate-y-2"
            enter-to-class="opacity-100 transform scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100 transform scale-100 translate-y-0"
            leave-to-class="opacity-0 transform scale-95 translate-y-2"
        >
            <div 
                v-if="flashMessages.success"
                class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg shadow-sm"
            >
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <CheckCircleIcon class="h-5 w-5 text-green-400" />
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-green-700">
                            {{ flashMessages.success }}
                        </p>
                    </div>
                    <div class="ml-auto pl-3" v-if="dismissible">
                        <button 
                            @click="dismissMessage('success')"
                            class="inline-flex bg-green-50 rounded-md p-1.5 text-green-400 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600 transition duration-200"
                        >
                            <span class="sr-only">Dismiss</span>
                            <XMarkIcon class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Error Message -->
        <transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 transform scale-95 translate-y-2"
            enter-to-class="opacity-100 transform scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100 transform scale-100 translate-y-0"
            leave-to-class="opacity-0 transform scale-95 translate-y-2"
        >
            <div 
                v-if="flashMessages.error"
                class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg shadow-sm"
            >
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <XCircleIcon class="h-5 w-5 text-red-400" />
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-red-700">
                            {{ flashMessages.error }}
                        </p>
                    </div>
                    <div class="ml-auto pl-3" v-if="dismissible">
                        <button 
                            @click="dismissMessage('error')"
                            class="inline-flex bg-red-50 rounded-md p-1.5 text-red-400 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600 transition duration-200"
                        >
                            <span class="sr-only">Dismiss</span>
                            <XMarkIcon class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Warning Message -->
        <transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 transform scale-95 translate-y-2"
            enter-to-class="opacity-100 transform scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100 transform scale-100 translate-y-0"
            leave-to-class="opacity-0 transform scale-95 translate-y-2"
        >
            <div 
                v-if="flashMessages.warning"
                class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg shadow-sm"
            >
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <ExclamationTriangleIcon class="h-5 w-5 text-yellow-400" />
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-yellow-700">
                            {{ flashMessages.warning }}
                        </p>
                    </div>
                    <div class="ml-auto pl-3" v-if="dismissible">
                        <button 
                            @click="dismissMessage('warning')"
                            class="inline-flex bg-yellow-50 rounded-md p-1.5 text-yellow-400 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-yellow-50 focus:ring-yellow-600 transition duration-200"
                        >
                            <span class="sr-only">Dismiss</span>
                            <XMarkIcon class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Info Message -->
        <transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 transform scale-95 translate-y-2"
            enter-to-class="opacity-100 transform scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100 transform scale-100 translate-y-0"
            leave-to-class="opacity-0 transform scale-95 translate-y-2"
        >
            <div 
                v-if="flashMessages.info"
                class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg shadow-sm"
            >
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <InformationCircleIcon class="h-5 w-5 text-blue-400" />
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-blue-700">
                            {{ flashMessages.info }}
                        </p>
                    </div>
                    <div class="ml-auto pl-3" v-if="dismissible">
                        <button 
                            @click="dismissMessage('info')"
                            class="inline-flex bg-blue-50 rounded-md p-1.5 text-blue-400 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-50 focus:ring-blue-600 transition duration-200"
                        >
                            <span class="sr-only">Dismiss</span>
                            <XMarkIcon class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Generic Message (fallback) -->
        <transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 transform scale-95 translate-y-2"
            enter-to-class="opacity-100 transform scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100 transform scale-100 translate-y-0"
            leave-to-class="opacity-0 transform scale-95 translate-y-2"
        >
            <div 
                v-if="flashMessages.message && !flashMessages.success && !flashMessages.error && !flashMessages.warning && !flashMessages.info"
                class="bg-gray-50 border-l-4 border-gray-400 p-4 rounded-r-lg shadow-sm"
            >
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <InformationCircleIcon class="h-5 w-5 text-gray-400" />
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-700">
                            {{ flashMessages.message }}
                        </p>
                    </div>
                    <div class="ml-auto pl-3" v-if="dismissible">
                        <button 
                            @click="dismissMessage('message')"
                            class="inline-flex bg-gray-50 rounded-md p-1.5 text-gray-400 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-gray-600 transition duration-200"
                        >
                            <span class="sr-only">Dismiss</span>
                            <XMarkIcon class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Multiple Messages Support -->
        <template v-if="Array.isArray(flashMessages.errors) && flashMessages.errors.length > 0">
            <transition-group
                enter-active-class="transition ease-out duration-300"
                enter-from-class="opacity-0 transform scale-95 translate-y-2"
                enter-to-class="opacity-100 transform scale-100 translate-y-0"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="opacity-100 transform scale-100 translate-y-0"
                leave-to-class="opacity-0 transform scale-95 translate-y-2"
                tag="div"
            >
                <div 
                    v-for="(error, index) in flashMessages.errors" 
                    :key="`error-${index}`"
                    class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg shadow-sm"
                >
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <XCircleIcon class="h-5 w-5 text-red-400 mt-0.5" />
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-red-700">
                                {{ error }}
                            </p>
                        </div>
                        <div class="ml-auto pl-3" v-if="dismissible">
                            <button 
                                @click="dismissArrayMessage('errors', index)"
                                class="inline-flex bg-red-50 rounded-md p-1.5 text-red-400 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600 transition duration-200"
                            >
                                <span class="sr-only">Dismiss</span>
                                <XMarkIcon class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </transition-group>
        </template>

        <!-- Validation Errors -->
        <transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 transform scale-95 translate-y-2"
            enter-to-class="opacity-100 transform scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100 transform scale-100 translate-y-0"
            leave-to-class="opacity-0 transform scale-95 translate-y-2"
        >
            <div 
                v-if="hasValidationErrors"
                class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg shadow-sm"
            >
                <div class="flex">
                    <div class="flex-shrink-0">
                        <XCircleIcon class="h-5 w-5 text-red-400" />
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            There were {{ Object.keys($page.props.errors).length }} error{{ Object.keys($page.props.errors).length > 1 ? 's' : '' }} with your submission
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li v-for="(error, field) in $page.props.errors" :key="field">
                                    <strong>{{ formatFieldName(field) }}:</strong> {{ error }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="ml-auto pl-3" v-if="dismissible">
                        <button 
                            @click="clearValidationErrors"
                            class="inline-flex bg-red-50 rounded-md p-1.5 text-red-400 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600 transition duration-200"
                        >
                            <span class="sr-only">Dismiss</span>
                            <XMarkIcon class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Session Status (for Laravel Breeze compatibility) -->
        <transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 transform scale-95 translate-y-2"
            enter-to-class="opacity-100 transform scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100 transform scale-100 translate-y-0"
            leave-to-class="opacity-0 transform scale-95 translate-y-2"
        >
            <div 
                v-if="$page.props.status"
                class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg shadow-sm"
            >
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <CheckCircleIcon class="h-5 w-5 text-green-400" />
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-green-700">
                            {{ $page.props.status }}
                        </p>
                    </div>
                    <div class="ml-auto pl-3" v-if="dismissible">
                        <button 
                            @click="clearStatus"
                            class="inline-flex bg-green-50 rounded-md p-1.5 text-green-400 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600 transition duration-200"
                        >
                            <span class="sr-only">Dismiss</span>
                            <XMarkIcon class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { computed, ref, watch, onMounted } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import {
    CheckCircleIcon,
    XCircleIcon,
    ExclamationTriangleIcon,
    InformationCircleIcon,
    XMarkIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    dismissible: {
        type: Boolean,
        default: true
    },
    autoHide: {
        type: Boolean,
        default: false
    },
    autoHideDelay: {
        type: Number,
        default: 5000
    },
    position: {
        type: String,
        default: 'top',
        validator: (value) => ['top', 'bottom'].includes(value)
    }
})

const page = usePage()
const localMessages = ref({})

// Computed properties
const flashMessages = computed(() => {
    return {
        success: page.props.flash?.success || localMessages.value.success,
        error: page.props.flash?.error || localMessages.value.error,
        warning: page.props.flash?.warning || localMessages.value.warning,
        info: page.props.flash?.info || localMessages.value.info,
        message: page.props.flash?.message || localMessages.value.message,
        errors: page.props.flash?.errors || localMessages.value.errors
    }
})

const hasMessages = computed(() => {
    return flashMessages.value.success || 
           flashMessages.value.error || 
           flashMessages.value.warning || 
           flashMessages.value.info || 
           flashMessages.value.message ||
           (flashMessages.value.errors && flashMessages.value.errors.length > 0) ||
           hasValidationErrors.value ||
           page.props.status
})

const hasValidationErrors = computed(() => {
    return page.props.errors && Object.keys(page.props.errors).length > 0
})

// Methods
const dismissMessage = (type) => {
    if (page.props.flash?.[type]) {
        // Clear flash message by making a request to clear it
        router.reload({ only: ['flash'] })
    } else {
        // Clear local message
        localMessages.value[type] = null
    }
}

const dismissArrayMessage = (type, index) => {
    if (page.props.flash?.[type]) {
        // For server flash messages, we need to reload
        router.reload({ only: ['flash'] })
    } else if (localMessages.value[type]) {
        // For local messages, remove specific item
        localMessages.value[type].splice(index, 1)
        if (localMessages.value[type].length === 0) {
            localMessages.value[type] = null
        }
    }
}

const clearValidationErrors = () => {
    router.reload({ only: ['errors'] })
}

const clearStatus = () => {
    router.reload({ only: ['status'] })
}

const formatFieldName = (fieldName) => {
    // Convert snake_case or dot notation to readable format
    return fieldName
        .replace(/_/g, ' ')
        .replace(/\./g, ' ')
        .replace(/^\w/, c => c.toUpperCase())
        .replace(/\s\w/g, c => c.toUpperCase())
}

const showMessage = (type, message, duration = null) => {
    localMessages.value[type] = message
    
    if (props.autoHide || duration) {
        setTimeout(() => {
            localMessages.value[type] = null
        }, duration || props.autoHideDelay)
    }
}

const showSuccess = (message, duration = null) => {
    showMessage('success', message, duration)
}

const showError = (message, duration = null) => {
    showMessage('error', message, duration)
}

const showWarning = (message, duration = null) => {
    showMessage('warning', message, duration)
}

const showInfo = (message, duration = null) => {
    showMessage('info', message, duration)
}

const clearAll = () => {
    localMessages.value = {}
    router.reload({ only: ['flash', 'errors', 'status'] })
}

// Auto hide functionality
watch(flashMessages, (newMessages) => {
    if (props.autoHide) {
        Object.keys(newMessages).forEach(type => {
            if (newMessages[type] && !localMessages.value[type]) {
                setTimeout(() => {
                    dismissMessage(type)
                }, props.autoHideDelay)
            }
        })
    }
}, { deep: true, immediate: true })

// Expose methods for parent components
defineExpose({
    showSuccess,
    showError,
    showWarning,
    showInfo,
    clearAll,
    dismissMessage
})

// Auto scroll to alerts on mount if they exist
onMounted(() => {
    if (hasMessages.value && props.position === 'top') {
        // Small delay to ensure DOM is ready
        setTimeout(() => {
            const alertElement = document.querySelector('[data-alert-container]')
            if (alertElement) {
                alertElement.scrollIntoView({ behavior: 'smooth', block: 'start' })
            }
        }, 100)
    }
})
</script>

<style scoped>
/* Custom animations for better UX */
.alert-enter-active {
    transition: all 0.3s ease-out;
}

.alert-enter-from {
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
}

.alert-enter-to {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.alert-leave-active {
    transition: all 0.2s ease-in;
}

.alert-leave-from {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.alert-leave-to {
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
}

/* Pulse animation for important messages */
.alert-pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

/* Smooth hover transitions */
button {
    transition: all 0.2s ease-in-out;
}

/* Focus styles for accessibility */
button:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
}

/* Custom scrollbar for long error lists */
.error-list {
    max-height: 200px;
    overflow-y: auto;
}

.error-list::-webkit-scrollbar {
    width: 4px;
}

.error-list::-webkit-scrollbar-track {
    background: transparent;
}

.error-list::-webkit-scrollbar-thumb {
    background: rgba(239, 68, 68, 0.3);
    border-radius: 2px;
}

.error-list::-webkit-scrollbar-thumb:hover {
    background: rgba(239, 68, 68, 0.5);
}
</style>
