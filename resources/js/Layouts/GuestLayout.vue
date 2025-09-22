<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <Link href="/" class="inline-flex items-center group">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-lg">{{ ($page.props.app?.name || 'SaaS').charAt(0) }}</span>
                    </div>
                    <span class="ml-3 text-2xl font-bold text-gray-900 group-hover:text-blue-600 transition duration-200">
                        {{ $page.props.app?.name || 'SaaS' }}
                    </span>
                </Link>
            </div>

            <!-- Main Content -->
            <div class="bg-white py-8 px-6 shadow-xl rounded-2xl border border-gray-200">
                <slot />
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    By continuing, you agree to our
                    <Link href="/terms" class="text-blue-600 hover:text-blue-500 font-medium">
                        Terms of Service
                    </Link>
                    and
                    <Link href="/privacy" class="text-blue-600 hover:text-blue-500 font-medium">
                        Privacy Policy
                    </Link>
                </p>
            </div>
        </div>
    </div>
</template>
