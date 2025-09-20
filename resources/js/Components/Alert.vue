<template>
    <transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 transform scale-95"
        enter-to-class="opacity-100 transform scale-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 transform scale-100"
        leave-to-class="opacity-0 transform scale-95"
    >
        <div 
            v-if="show" 
            :class="[
                'p-4 rounded-lg shadow-sm border-l-4',
                alertClasses[type]
            ]"
        >
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <component :is="alertIcons[type]" class="h-5 w-5" :class="iconClasses[type]" />
                </div>
                <div class="ml-3">
                    <p :class="['text-sm font-medium', textClasses[type]]">
                        {{ message }}
                    </p>
                </div>
                <div class="ml-auto pl-3" v-if="dismissible">
                    <button 
                        @click="dismiss"
                        :class="['inline-flex rounded-md p-1.5 hover:bg-opacity-20', hoverClasses[type]]"
                    >
                        <XMarkIcon class="h-4 w-4" :class="iconClasses[type]" />
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref, computed } from 'vue'
import { 
    CheckCircleIcon, 
    ExclamationTriangleIcon, 
    InformationCircleIcon, 
    XCircleIcon,
    XMarkIcon 
} from '@heroicons/vue/20/solid'

const props = defineProps({
    type: {
        type: String,
        default: 'info',
        validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    },
    message: {
        type: String,
        required: true
    },
    dismissible: {
        type: Boolean,
        default: true
    },
    autoHide: {
        type: Boolean,
        default: false
    },
    timeout: {
        type: Number,
        default: 5000
    }
})

const emit = defineEmits(['dismiss'])

const show = ref(true)

const alertClasses = {
    success: 'bg-green-50 border-green-400',
    error: 'bg-red-50 border-red-400',
    warning: 'bg-yellow-50 border-yellow-400',
    info: 'bg-blue-50 border-blue-400'
}

const iconClasses = {
    success: 'text-green-400',
    error: 'text-red-400',
    warning: 'text-yellow-400',
    info: 'text-blue-400'
}

const textClasses = {
    success: 'text-green-700',
    error: 'text-red-700',
    warning: 'text-yellow-700',
    info: 'text-blue-700'
}

const hoverClasses = {
    success: 'text-green-500 hover:bg-green-200',
    error: 'text-red-500 hover:bg-red-200',
    warning: 'text-yellow-500 hover:bg-yellow-200',
    info: 'text-blue-500 hover:bg-blue-200'
}

const alertIcons = {
    success: CheckCircleIcon,
    error: XCircleIcon,
    warning: ExclamationTriangleIcon,
    info: InformationCircleIcon
}

const dismiss = () => {
    show.value = false
    emit('dismiss')
}

// Auto hide functionality
if (props.autoHide) {
    setTimeout(() => {
        dismiss()
    }, props.timeout)
}
</script>
