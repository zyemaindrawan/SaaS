<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

// Icons
import {
    PencilIcon,
    EyeIcon,
    DocumentTextIcon,
    ClockIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon,
    CurrencyDollarIcon,
    ReceiptRefundIcon,
    HomeIcon,
    ChevronRightIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    websites: {
        type: Object,
        default: () => ({})
    },
    stats: {
        type: Object,
        default: () => ({})
    },
    invoices: {
        type: Array,
        default: () => []
    },
    drafts: {
        type: Array,
        default: () => []
    }
});

// Filtered website collections
const userWebsites = computed(() => {
    const websites = props.websites?.data || [];
    return websites.filter(website => 
        ['active', 'processing', 'expired', 'suspended'].includes(website.status)
    );
});

const draftWebsites = computed(() => {
    const websites = props.websites?.data || [];
    return websites.filter(website => 
        ['draft', 'previewed'].includes(website.status)
    );
});

const allWebsites = computed(() => props.websites?.data || []);

const getStatusIcon = (status) => {
    switch (status) {
        case 'active':
            return CheckCircleIcon;
        case 'processing':
        case 'paid':
            return ClockIcon;
        case 'draft':
        case 'previewed':
            return PencilIcon;
        case 'expired':
        case 'suspended':
        case 'pending':
            return ExclamationTriangleIcon;
        default:
            return ExclamationTriangleIcon;
    }
};

const getStatusColor = (status) => {
    switch (status) {
        case 'active':
            return 'text-green-600 bg-green-100';
        case 'processing':
        case 'paid':
            return 'text-blue-600 bg-blue-100';
        case 'draft':
            return 'text-yellow-600 bg-yellow-100';
        case 'previewed':
            return 'text-purple-600 bg-purple-100';
        case 'expired':
        case 'suspended':
        case 'pending':
            return 'text-red-600 bg-red-100';
        default:
            return 'text-gray-600 bg-gray-100';
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const formatPrice = (price) => {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
};

const getDraftStatusClass = (status) => {
    const classes = {
        'draft': 'bg-yellow-100 text-yellow-800',
        'previewed': 'bg-blue-100 text-blue-800',
        'paid': 'bg-green-100 text-green-800',
        'active': 'bg-green-100 text-green-800'
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
}

const getDraftStatusText = (status) => {
    const texts = {
        'draft': 'Draft',
        'previewed': 'Previewed',
        'paid': 'Paid',
        'active': 'Active'
    }
    return texts[status] || status
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">
                        Your Dashboard
                    </h1>
                    <p class="text-xl md:text-2xl opacity-90 max-w-3xl mx-auto">
                        Manage your websites and track your progress
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <!-- Alert Messages -->
            <div v-if="$page.props.flash?.success || $page.props.flash?.error || $page.props.flash?.info" class="mb-8">
                <div v-if="$page.props.flash?.success" class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <CheckCircleIcon class="h-5 w-5 text-green-400" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-800">{{ $page.props.flash.success }}</p>
                        </div>
                    </div>
                </div>

                <div v-if="$page.props.flash?.error" class="bg-red-50 border border-red-200 rounded-lg p-4 mt-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <ExclamationTriangleIcon class="h-5 w-5 text-red-400" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-800">{{ $page.props.flash.error }}</p>
                        </div>
                    </div>
                </div>

                <div v-if="$page.props.flash?.info" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-800">{{ $page.props.flash.info }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Drafts Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <PencilIcon class="w-6 h-6 mr-3" />
                        Recent Drafts
                    </h2>
                </div>

                <div class="p-6">
                    <div v-if="draftWebsites.length > 0" class="space-y-4">
                        <div
                            v-for="draft in draftWebsites.slice(0, 5)"
                            :key="draft.id"
                            class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-200"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="font-semibold text-gray-900">
                                            {{ draft.website_name }}
                                        </h3>
                                        <span 
                                            :class="getStatusColor(draft.status)"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                        >
                                            {{ draft.status }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-1">
                                        {{ draft.subdomain }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Updated: {{ formatDate(draft.updated_at) }}
                                    </p>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <Link
                                        :href="`/preview/${draft.id}`"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                                    >
                                        <EyeIcon class="w-4 h-4 mr-1" />
                                        Preview
                                    </Link>
                                    <Link
                                        :href="`/website-builder/${draft.id}/edit`"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                                    >
                                        Continue
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="text-center py-8">
                        <PencilIcon class="mx-auto h-12 w-12 text-gray-400 mb-4" />
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No drafts yet</h3>
                        <p class="text-gray-600">
                            Your website drafts will appear here when you start creating.
                        </p>
                    </div>

                    <div v-if="draftWebsites.length > 5" class="text-center mt-4">
                        <Link
                            href="/drafts"
                            class="text-purple-600 hover:text-purple-800 text-sm font-medium"
                        >
                            View all drafts →
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Your Websites Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <DocumentTextIcon class="w-6 h-6 mr-3" />
                        Your Websites
                    </h2>
                </div>

                <div class="p-8">
                    <div v-if="userWebsites.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="website in userWebsites"
                            :key="website.id"
                            class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition duration-200"
                        >
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="font-bold text-lg text-gray-900 mb-2">
                                        {{ website.website_name }}
                                    </h3>
                                    <div class="flex items-center mb-2">
                                        <component
                                            :is="getStatusIcon(website.status)"
                                            class="w-4 h-4 mr-2"
                                            :class="getStatusColor(website.status)"
                                        />
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                            :class="getStatusColor(website.status)"
                                        >
                                            {{ website.status }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        {{ website.custom_domain }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Expired: {{ formatDate(website.expires_at) }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2">
                                <div class="flex gap-2">
                                    <Link
                                        v-if="website.status === 'active'"
                                        :href="`https://${website.custom_domain}`"
                                        target="_blank"
                                        class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium transition duration-200 flex items-center justify-center"
                                    >
                                        <EyeIcon class="w-4 h-4 mr-1" />
                                        View Website
                                    </Link>

                                    <!-- <Link
                                        v-if="website.status === 'processing'"
                                        :href="`/preview/${website.id}`"
                                        class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium transition duration-200 flex items-center justify-center"
                                    >
                                        <EyeIcon class="w-4 h-4 mr-1" />
                                        Preview
                                    </Link> -->

                                    <!-- <Link
                                        :href="`/website-builder/${website.id}/edit`"
                                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium transition duration-200 flex items-center justify-center"
                                    >
                                        <PencilIcon class="w-4 h-4 mr-1" />
                                        Edit Content
                                    </Link> -->
                                </div>

                                <span
                                    v-if="['expired', 'suspended'].includes(website.status)"
                                    class="bg-red-300 text-red-800 text-center py-2 px-4 rounded-lg text-sm font-medium"
                                >
                                    {{ website.status === 'expired' ? 'Expired' : 'Suspended' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div v-else class="text-center py-12">
                        <DocumentTextIcon class="mx-auto h-16 w-16 text-gray-400 mb-4" />

                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No active websites yet</h3>
                        <p class="text-gray-600 mb-6">
                            Your active websites will appear here once they are published.
                        </p>

                        <Link
                            href="/templates"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium rounded-lg transition duration-200 shadow-sm hover:shadow-md"
                        >
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Create Your First Website
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Latest Invoices Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <ReceiptRefundIcon class="w-6 h-6 mr-3" />
                        Latest Invoices
                    </h2>
                </div>

                <div class="p-6">
                    <div v-if="invoices.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div
                            v-for="invoice in invoices.slice(0, 5)"
                            :key="invoice.code"
                            class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-200"
                        >
                            <Link :href="`/invoice/${invoice.code}`" class="block">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                            <ReceiptRefundIcon class="w-5 h-5 text-white" />
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">
                                                #{{ invoice.code }}
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                {{ formatDate(invoice.created_at) }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="font-bold text-sm text-gray-900">
                                            {{ formatPrice(invoice.gross_amount) }}
                                        </p>
                                        <div class="flex items-center justify-center mt-2">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold capitalize"
                                                :class="getStatusColor(invoice.status)"
                                            >
                                                <component
                                                    :is="getStatusIcon(invoice.status)"
                                                    class="w-3 h-3 mr-1"
                                                />
                                                {{ invoice.status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>

                    <div v-else class="text-center py-8">
                        <ReceiptRefundIcon class="mx-auto h-12 w-12 text-gray-400 mb-4" />
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No invoices yet</h3>
                        <p class="text-gray-600">
                            Your invoice history will appear here once you make purchases.
                        </p>
                    </div>

                    <div v-if="invoices.length > 5" class="text-center mt-4">
                        <Link
                            href="/invoices"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                        >
                            View all invoices →
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <DocumentTextIcon class="w-6 h-6 text-white" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Websites</p>
                            <p class="text-2xl font-bold text-gray-900">{{ allWebsites.length }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                            <CheckCircleIcon class="w-6 h-6 text-white" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Active/Processing</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ allWebsites.filter(w => ['active', 'processing'].includes(w.status)).length }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <PencilIcon class="w-6 h-6 text-white" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Draft/Previewed</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ allWebsites.filter(w => ['draft', 'previewed'].includes(w.status)).length }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                            <ExclamationTriangleIcon class="w-6 h-6 text-white" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Expired/Suspended</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ allWebsites.filter(w => ['expired', 'suspended'].includes(w.status)).length }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Custom animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.grid > * {
    animation: fadeIn 0.6s ease-out;
    animation-delay: calc(var(--index) * 0.1s);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
