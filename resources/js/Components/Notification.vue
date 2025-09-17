<script setup>
import { ref, onMounted, watch } from 'vue';

const props = defineProps({
    type: {
        type: String,
        default: 'info',
        validator: (value) => ['info', 'success', 'warning', 'error'].includes(value)
    },
    message: {
        type: String,
        required: true
    },
    title: {
        type: String,
        default: ''
    },
    dismissible: {
        type: Boolean,
        default: true
    },
    autoHide: {
        type: Boolean,
        default: true
    },
    duration: {
        type: Number,
        default: 5000
    }
});

const emit = defineEmits(['close']);

const isVisible = ref(true);
const isAnimating = ref(false);

const getIcon = () => {
    const icons = {
        info: 'info',
        success: 'check_circle',
        warning: 'warning',
        error: 'error'
    };
    return icons[props.type];
};

const getClasses = () => {
    const classes = {
        info: 'bg-blue-50 border-blue-200 text-blue-800',
        success: 'bg-green-50 border-green-200 text-green-800',
        warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
        error: 'bg-red-50 border-red-200 text-red-800'
    };
    return classes[props.type];
};

const getIconClasses = () => {
    const classes = {
        info: 'text-blue-500',
        success: 'text-green-500',
        warning: 'text-yellow-500',
        error: 'text-red-500'
    };
    return classes[props.type];
};

const close = () => {
    isAnimating.value = true;
    setTimeout(() => {
        isVisible.value = false;
        emit('close');
    }, 150);
};

let autoHideTimeout;

const setupAutoHide = () => {
    if (props.autoHide && props.duration > 0) {
        clearTimeout(autoHideTimeout);
        autoHideTimeout = setTimeout(() => {
            close();
        }, props.duration);
    }
};

onMounted(() => {
    setupAutoHide();
});

watch(() => props.message, () => {
    isVisible.value = true;
    isAnimating.value = false;
    setupAutoHide();
});
</script>

<template>
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 transform translate-x-2"
        enter-to-class="opacity-100 transform translate-x-0"
        leave-active-class="transition-all duration-150 ease-in"
        leave-from-class="opacity-100 transform translate-x-0"
        leave-to-class="opacity-0 transform translate-x-2"
    >
        <div
            v-if="isVisible"
            :class="[
                'flex items-start p-4 border rounded-lg shadow-sm max-w-md w-full',
                getClasses(),
                { 'transform transition-transform duration-150': isAnimating }
            ]"
            role="alert"
        >
            <div class="flex-shrink-0 mr-3">
                <span :class="['material-icons text-xl', getIconClasses()]">
                    {{ getIcon() }}
                </span>
            </div>

            <div class="flex-1 min-w-0">
                <h4 v-if="title" class="font-semibold text-sm mb-1">
                    {{ title }}
                </h4>
                <p class="text-sm">
                    {{ message }}
                </p>
            </div>

            <button
                v-if="dismissible"
                @click="close"
                class="flex-shrink-0 ml-3 p-1 rounded-md hover:bg-black hover:bg-opacity-10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                aria-label="Close notification"
            >
                <span class="material-icons text-lg opacity-60 hover:opacity-80">
                    close
                </span>
            </button>
        </div>
    </Transition>
</template>