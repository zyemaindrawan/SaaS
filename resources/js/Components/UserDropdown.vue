<template>
    <Menu as="div" class="relative inline-block text-left">
        <div>
            <MenuButton class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <img 
                    class="h-8 w-8 rounded-full" 
                    :src="user.avatar || '/default-avatar.png'" 
                    :alt="user.name" 
                />
                <span class="ml-2 text-gray-700">{{ user.name }}</span>
                <ChevronDownIcon class="ml-1 h-4 w-4 text-gray-400" />
            </MenuButton>
        </div>

        <transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <MenuItems class="absolute right-0 z-10 mt-2 w-56 origin-top-right bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none rounded-md">
                <div class="py-1">
                    <MenuItem v-slot="{ active }">
                        <Link 
                            href="/dashboard" 
                            :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']"
                        >
                            Dashboard
                        </Link>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                        <Link 
                            href="/profile" 
                            :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']"
                        >
                            Profile
                        </Link>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                        <Link 
                            href="/settings" 
                            :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']"
                        >
                            Settings
                        </Link>
                    </MenuItem>
                    <div class="border-t border-gray-100"></div>
                    <MenuItem v-slot="{ active }">
                        <Link 
                            href="/logout" 
                            method="post" 
                            as="button"
                            :class="[active ? 'bg-gray-100' : '', 'block w-full text-left px-4 py-2 text-sm text-gray-700']"
                        >
                            Sign out
                        </Link>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>

<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'
import { Link } from '@inertiajs/vue3'

defineProps({
    user: Object
})
</script>
