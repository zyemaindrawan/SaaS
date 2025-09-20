<template>
    <transition
        enter-active-class="transition-opacity duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-300"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div 
            v-if="show" 
            class="fixed inset-0 bg-white bg-opacity-90 backdrop-blur-sm z-50 flex items-center justify-center"
        >
            <div class="text-center">
                <!-- Animated Logo/Icon -->
                <div class="relative mb-8">
                    <div class="w-16 h-16 mx-auto">
                        <svg 
                            class="w-full h-full text-blue-600 animate-spin" 
                            fill="none" 
                            viewBox="0 0 24 24"
                        >
                            <circle 
                                class="opacity-25" 
                                cx="12" 
                                cy="12" 
                                r="10" 
                                stroke="currentColor" 
                                stroke-width="4"
                            />
                            <path 
                                class="opacity-75" 
                                fill="currentColor" 
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            />
                        </svg>
                    </div>
                    
                    <!-- Pulse effect -->
                    <div class="absolute inset-0 w-16 h-16 mx-auto rounded-full bg-blue-200 animate-ping opacity-20"></div>
                </div>

                <!-- Loading Text with Typewriter Effect -->
                <div class="space-y-2">
                    <h3 class="text-xl font-semibold text-gray-900">
                        {{ currentMessage }}
                        <span class="animate-pulse">|</span>
                    </h3>
                    <p class="text-gray-600 text-sm">
                        Preparing your template details...
                    </p>
                </div>

                <!-- Progress Bar -->
                <div class="mt-6 w-64 mx-auto">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div 
                            class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-300 ease-out"
                            :style="{ width: progress + '%' }"
                        ></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">{{ Math.round(progress) }}% loaded</p>
                </div>

                <!-- Floating Elements Animation -->
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    <div 
                        v-for="i in 6" 
                        :key="i"
                        class="absolute w-2 h-2 bg-blue-400 rounded-full opacity-30 animate-float"
                        :style="{ 
                            left: Math.random() * 100 + '%', 
                            top: Math.random() * 100 + '%',
                            animationDelay: Math.random() * 2 + 's',
                            animationDuration: (3 + Math.random() * 2) + 's'
                        }"
                    ></div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    }
})

const currentMessage = ref('Loading template')
const progress = ref(0)

const messages = [
    'Loading template',
    'Fetching details',
    'Preparing content',
    'Almost ready'
]

let messageInterval = null
let progressInterval = null

onMounted(() => {
    if (props.show) {
        startAnimations()
    }
})

onUnmounted(() => {
    clearIntervals()
})

const startAnimations = () => {
    let messageIndex = 0
    let currentProgress = 0

    // Message rotation
    messageInterval = setInterval(() => {
        messageIndex = (messageIndex + 1) % messages.length
        currentMessage.value = messages[messageIndex]
    }, 800)

    // Progress animation
    progressInterval = setInterval(() => {
        currentProgress += Math.random() * 15 + 5
        if (currentProgress > 95) {
            currentProgress = 95
        }
        progress.value = currentProgress
    }, 200)
}

const clearIntervals = () => {
    if (messageInterval) {
        clearInterval(messageInterval)
        messageInterval = null
    }
    if (progressInterval) {
        clearInterval(progressInterval)
        progressInterval = null
    }
}
</script>

<style scoped>
@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
        opacity: 0.3;
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
        opacity: 0.8;
    }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}
</style>
