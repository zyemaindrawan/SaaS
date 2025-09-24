<template>
    <AppLayout>
        <Head>
            <title>Page</title>
        </Head>

        <PageLoader :show="loading" />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <section class="text-center py-16 md:py-24">
                <p class="max-w-3xl mx-auto text-subtext-light dark:text-subtext-dark mb-24 mt-36">
                    Halaman masih dalam pengembangan...
                </p>
            </section>
        </div>

    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PageLoader from '@/Components/PageLoader.vue'

const appName = import.meta.env.VITE_APP_NAME
const loading = ref(true)

onMounted(() => {
    setTimeout(() => {
        loading.value = false
    }, 1000)
})
</script>