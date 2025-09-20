<template>
    <button
        @click="copyToClipboard"
        :class="buttonClass"
        class="relative inline-flex items-center transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
    >
        <slot>
            <span v-if="!copied">{{ label }}</span>
            <span v-else class="text-green-600">{{ copiedLabel }}</span>
        </slot>
        
        <component 
            :is="copied ? CheckIcon : ($slots.default ? null : ClipboardIcon)" 
            :class="iconClass"
        />

        <!-- Copy success animation -->
        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div 
                v-if="copied && showTooltip"
                class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap z-10"
            >
                Copied to clipboard!
                <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-2 border-r-2 border-t-2 border-l-transparent border-r-transparent border-t-gray-800"></div>
            </div>
        </transition>
    </button>
</template>

<script setup>
import { ref } from 'vue'
import { ClipboardIcon, CheckIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    text: {
        type: String,
        required: true
    },
    label: {
        type: String,
        default: 'Copy'
    },
    copiedLabel: {
        type: String,
        default: 'Copied!'
    },
    buttonClass: {
        type: String,
        default: 'bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-md'
    },
    iconClass: {
        type: String,
        default: 'w-4 h-4 ml-1'
    },
    showTooltip: {
        type: Boolean,
        default: true
    },
    resetDelay: {
        type: Number,
        default: 2000
    }
})

const copied = ref(false)

const copyToClipboard = async () => {
    try {
        await navigator.clipboard.writeText(props.text)
        copied.value = true
        
        setTimeout(() => {
            copied.value = false
        }, props.resetDelay)
    } catch (err) {
        console.error('Failed to copy text: ', err)
        
        // Fallback for older browsers
        const textArea = document.createElement('textarea')
        textArea.value = props.text
        document.body.appendChild(textArea)
        textArea.select()
        
        try {
            document.execCommand('copy')
            copied.value = true
            
            setTimeout(() => {
                copied.value = false
            }, props.resetDelay)
        } catch (fallbackErr) {
            console.error('Fallback copy failed: ', fallbackErr)
        }
        
        document.body.removeChild(textArea)
    }
}
</script>
