<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    redirect: {
        type: String,
        default: null,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
    redirect: props.redirect,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <!-- <GuestLayout> -->
        <Head title="Log in" />

        <!-- Main Content -->
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">

                <!-- Header -->
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white text-center">Sign In</h2>
                </div>

                <!-- Status Message -->
                <div v-if="status" class="m-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-800">{{ status }}</p>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <div>
                        <InputLabel for="email" value="Email Address" class="text-gray-700 font-medium" />
                        <TextInput
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Enter your email address"
                            class="mt-2 w-full"
                        />
                        <InputError :message="form.errors.email" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="password" value="Password" class="text-gray-700 font-medium" />
                        <TextInput
                            id="password"
                            type="password"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password"
                            class="mt-2 w-full"
                        />
                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-blue-600 hover:text-blue-500 font-medium"
                        >
                            Forgot password?
                        </Link>
                    </div>

                    <PrimaryButton
                        :disabled="form.processing"
                        class="w-full justify-center py-3"
                    >
                        <span v-if="form.processing">
                            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Signing in...
                        </span>
                        <span v-else>Sign In</span>
                    </PrimaryButton>
                </form>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-center">
                    <p class="text-gray-600">
                        Don't have an account?
                        <Link
                            :href="props.redirect ? route('register', { redirect: props.redirect }) : route('register')"
                            class="text-blue-600 hover:text-blue-500 font-medium ml-1"
                        >
                            Sign up
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    <!-- </GuestLayout> -->
</template>

<style scoped>
/* Custom animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.bg-white {
    animation: fadeIn 0.6s ease-out;
}

/* Custom scrollbar for better UX */
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
