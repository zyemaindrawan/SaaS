<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const sidebarOpen = ref(false);
const darkMode = ref(false);

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

const toggleDarkMode = () => {
    darkMode.value = !darkMode.value;
};

const menuItems = [
    { icon: 'home', label: 'Beranda', href: 'dashboard', active: true },
    { 
        icon: 'layers', 
        label: 'Layanan', 
        href: null, 
        submenu: [
            { label: 'Web Hosting', href: null },
            { label: 'VPS', href: null },
            { label: 'Dedicated Server', href: null }
        ]
    },
    { 
        icon: 'dns', 
        label: 'Domain', 
        href: null,
        submenu: [
            { label: 'Register Domain', href: null },
            { label: 'Transfer Domain', href: null },
            { label: 'Domain Management', href: null }
        ]
    },
    { 
        icon: 'receipt', 
        label: 'Finance/Billing', 
        href: null,
        submenu: [
            { label: 'Tagihan Saya', href: null },
            { label: 'Permintaan Tagihan Manual', href: null },
            { label: 'Permintaan Faktur Pajak', href: null },
            { label: 'Tambah Deposit', href: null },
            { label: 'Pengembalian Dana', href: null }
        ]
    },
    { 
        icon: 'support_agent', 
        label: 'Support', 
        href: null,
        submenu: [
            { label: 'Open Ticket', href: null },
            { label: 'My Tickets', href: null },
            { label: 'Knowledge Base', href: null }
        ]
    },
    { icon: 'group', label: 'Affiliate', href: null }
];
</script>

<template>
    <div class="flex h-screen" :class="{ 'dark': darkMode }">
        <!-- Sidebar -->
        <aside class="w-64 sidebar flex flex-col p-4 transition-all duration-300" 
               :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
            <!-- Brand Logo -->
            <div class="text-xl font-bold mb-8">
                <span class="text-blue-400">Your</span><span class="text-white">Brand</span>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-grow">
                <h2 class="text-xs uppercase text-gray-500 mb-2">Menu</h2>
                <ul>
                    <li v-for="(item, index) in menuItems" :key="index" class="mb-2">
                        <template v-if="item.submenu">
                            <details class="group">
                                <summary class="flex items-center justify-between p-2 rounded-lg cursor-pointer hover:bg-gray-700">
                                    <div class="flex items-center">
                                        <span class="material-icons text-gray-400">{{ item.icon }}</span>
                                        <span class="ml-3 text-gray-300">{{ item.label }}</span>
                                    </div>
                                    <span class="material-icons text-gray-400 group-open:rotate-180 transition-transform">expand_more</span>
                                </summary>
                                <ul class="ml-6 mt-1 space-y-1">
                                    <li v-for="(subitem, subindex) in item.submenu" :key="subindex">
                                        <span v-if="!subitem.href" 
                                              class="block p-2 rounded-lg text-gray-400 cursor-not-allowed opacity-50">
                                            {{ subitem.label }}
                                        </span>
                                        <Link v-else :href="subitem.href" 
                                              class="block p-2 rounded-lg text-gray-300 hover:text-white hover:bg-gray-700">
                                            {{ subitem.label }}
                                        </Link>
                                    </li>
                                </ul>
                            </details>
                        </template>
                        <template v-else>
                            <Link v-if="item.href" :href="route(item.href)" 
                                  class="flex items-center p-2 rounded-lg text-gray-300 hover:text-white hover:bg-gray-700"
                                  :class="{ 'bg-gray-700 text-white': item.active }">
                                <span class="material-icons">{{ item.icon }}</span>
                                <span class="ml-3">{{ item.label }}</span>
                            </Link>
                            <span v-else 
                                  class="flex items-center p-2 rounded-lg text-gray-400 cursor-not-allowed opacity-50">
                                <span class="material-icons">{{ item.icon }}</span>
                                <span class="ml-3">{{ item.label }}</span>
                            </span>
                        </template>
                    </li>
                </ul>
            </nav>

            <!-- Dark Mode Toggle -->
            <div class="mt-auto">
                <div class="flex items-center justify-between p-2 bg-gray-700 rounded-lg">
                    <span class="material-icons text-gray-300">dark_mode</span>
                    <span class="ml-2 text-gray-300">Dark Mode</span>
                    <label class="relative inline-flex items-center cursor-pointer ml-auto">
                        <input type="checkbox" v-model="darkMode" @change="toggleDarkMode" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm p-4 flex justify-between items-center">
                <!-- Mobile Menu Button -->
                <div class="flex items-center">
                    <button @click="toggleSidebar" class="lg:hidden mr-4 p-2 rounded-md text-gray-600 hover:bg-gray-100">
                        <span class="material-icons">menu</span>
                    </button>
                    <slot name="header">
                        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                    </slot>
                </div>

                <!-- Header Right Section -->
                <div class="flex items-center space-x-4">
                    <!-- Cart Button -->
                    <button class="text-gray-500 hover:text-gray-700 relative">
                        <span class="material-icons">shopping_cart</span>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                    </button>

                    <!-- Notifications Button -->
                    <button class="text-gray-500 hover:text-gray-700 relative">
                        <span class="material-icons">notifications</span>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                            <img :src="`https://ui-avatars.com/api/?name=${$page.props.auth.user.name}&background=6366f1&color=fff`" 
                                 alt="User avatar" 
                                 class="w-8 h-8 rounded-full">
                            <span>{{ $page.props.auth.user.name }}</span>
                            <span class="material-icons text-gray-500">expand_more</span>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <Link :href="route('profile.edit')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profile Settings
                            </Link>
                            <Link :href="route('logout')" method="post" as="button" 
                                  class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Logout
                            </Link>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Breadcrumb -->
            <div class="px-6 py-3 text-sm text-gray-500 bg-gray-50 border-b">
                <slot name="breadcrumb">
                    DASHBOARD
                </slot>
            </div>

            <!-- Page Content -->
            <main class="flex-1 p-6 bg-gray-50 overflow-auto">
                <slot />
            </main>

            <!-- Footer -->
            <footer class="text-center p-4 text-sm text-gray-500 bg-white border-t">
                Copyright 2025 © All Rights Reserved.
            </footer>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div v-if="sidebarOpen" @click="toggleSidebar" 
             class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"></div>
    </div>
</template>

<style scoped>
.sidebar {
    background-color: #212529;
    color: #E9ECEF;
}

.sidebar a {
    color: #ADB5BD;
}

.sidebar a:hover, 
.sidebar a.active {
    color: #FFFFFF;
    background-color: #343A40;
}

@media (min-width: 1024px) {
    .sidebar {
        transform: translateX(0) !important;
    }
}

details summary::-webkit-details-marker {
    display: none;
}
</style>