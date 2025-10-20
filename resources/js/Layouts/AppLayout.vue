<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Progress Bar -->
        <div
            v-if="$page.props.inertia?.loading"
            class="fixed top-0 left-0 right-0 z-50 h-1 bg-gradient-to-r from-blue-500 to-purple-600 transform origin-left transition-transform duration-200"
            :class="{ 'scale-x-0': !$page.props.inertia?.loading }"
        ></div>

        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200 relative z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <Link href="/" class="flex-shrink-0 flex items-center group">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ ($page.props.app?.name || 'SaaS').charAt(0) }}</span>
                            </div>
                            <span class="ml-2 text-xl font-bold text-gray-900 group-hover:text-blue-600 transition duration-200">
                                {{ $page.props.app?.name || 'SaaS' }}
                            </span>
                        </Link>

                        <!-- Navigation Links -->
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <NavLink href="/templates" :active="$page.url.startsWith('/templates')">
                                Templates
                            </NavLink>
                            <!-- <NavLink href="/pricing" :active="$page.url.startsWith('/pricing')">
                                Pricing
                            </NavLink>
                            <NavLink href="/about" :active="$page.url.startsWith('/about')">
                                About
                            </NavLink> -->
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center space-x-4">
                        <template v-if="$page.props.auth.user">
                            <!-- User Dropdown -->
                            <UserDropdown :user="$page.props.auth.user" />
                        </template>
                        <template v-else>
                            <!-- Guest Links -->
                            <Link
                                href="/login"
                                class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200"
                            >
                                Login
                            </Link>
                            <Link
                                href="/register"
                                class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200 shadow-sm hover:shadow-md"
                            >
                                Get Started
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="relative">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white mt-20">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ ($page.props.app?.name || 'SaaS').charAt(0) }}</span>
                            </div>
                            <h3 class="ml-2 text-lg font-semibold">{{ $page.props.app?.name || 'SaaS' }}</h3>
                        </div>
                        <p class="text-gray-300 text-sm">
                            Create professional websites in minutes with our template-based platform.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold mb-4">Product</h4>
                        <ul class="space-y-2 text-sm text-gray-300">
                            <li><Link href="/templates" class="hover:text-white transition duration-200">Templates</Link></li>
                            <li><Link href="/pricing" class="hover:text-white transition duration-200">Pricing</Link></li>
                            <li><Link href="/features" class="hover:text-white transition duration-200">Features</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold mb-4">Support</h4>
                        <ul class="space-y-2 text-sm text-gray-300">
                            <li><Link href="/help" class="hover:text-white transition duration-200">Help Center</Link></li>
                            <li><Link href="/contact" class="hover:text-white transition duration-200">Contact</Link></li>
                            <li><Link href="/docs" class="hover:text-white transition duration-200">Documentation</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold mb-4">Legal</h4>
                        <ul class="space-y-2 text-sm text-gray-300">
                            <li><Link href="/privacy" class="hover:text-white transition duration-200">Privacy Policy</Link></li>
                            <li><Link href="/terms" class="hover:text-white transition duration-200">Terms of Service</Link></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300 text-sm">
                    <p>&copy; 2025 {{ $page.props.app?.name || 'SaaS' }}. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import NavLink from '@/Components/NavLink.vue'
import UserDropdown from '@/Components/UserDropdown.vue'
</script>
