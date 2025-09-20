<template>
    <span 
        :class="[
            'inline-flex items-center font-medium rounded-full',
            sizeClasses[size],
            statusClasses[status] || statusClasses.default
        ]"
    >
        <component 
            :is="statusIcons[status] || statusIcons.default" 
            :class="iconClasses[size]" 
        />
        {{ statusLabels[status] || status.toUpperCase() }}
    </span>
</template>

<script setup>
import {
    ClockIcon,
    ExclamationTriangleIcon,
    CheckCircleIcon,
    XCircleIcon,
    CurrencyDollarIcon,
    ArrowPathIcon
} from '@heroicons/vue/20/solid'

const props = defineProps({
    status: {
        type: String,
        required: true
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    }
})

const sizeClasses = {
    sm: 'px-2.5 py-0.5 text-xs',
    md: 'px-3 py-1 text-sm',
    lg: 'px-4 py-2 text-base'
}

const iconClasses = {
    sm: 'w-3 h-3 mr-1',
    md: 'w-4 h-4 mr-1.5',
    lg: 'w-5 h-5 mr-2'
}

const statusClasses = {
    pending: 'bg-yellow-100 text-yellow-800',
    initiated: 'bg-blue-100 text-blue-800',
    processing: 'bg-indigo-100 text-indigo-800',
    paid: 'bg-green-100 text-green-800',
    success: 'bg-green-100 text-green-800',
    failed: 'bg-red-100 text-red-800',
    cancelled: 'bg-gray-100 text-gray-800',
    expired: 'bg-orange-100 text-orange-800',
    challenge: 'bg-purple-100 text-purple-800',
    default: 'bg-gray-100 text-gray-800'
}

const statusLabels = {
    pending: 'Pending',
    initiated: 'Initiated',
    processing: 'Processing',
    paid: 'Paid',
    success: 'Success',
    failed: 'Failed',
    cancelled: 'Cancelled',
    expired: 'Expired',
    challenge: 'Challenge'
}

const statusIcons = {
    pending: ClockIcon,
    initiated: ArrowPathIcon,
    processing: ArrowPathIcon,
    paid: CheckCircleIcon,
    success: CheckCircleIcon,
    failed: XCircleIcon,
    cancelled: XCircleIcon,
    expired: ExclamationTriangleIcon,
    challenge: ExclamationTriangleIcon,
    default: ClockIcon
}
</script>
