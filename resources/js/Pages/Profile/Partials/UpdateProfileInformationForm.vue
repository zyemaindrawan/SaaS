<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    phone: user.phone || '',
});

const phoneError = ref('');

// Watch phone field for real-time validation
watch(() => form.phone, (newPhone) => {
    if (newPhone && newPhone.length > 0) {
        // Remove any non-digit characters for validation
        const cleanPhone = newPhone.replace(/\D/g, '');
        
        // Check format
        if (!/^(628|08)\d{8,11}$/.test(cleanPhone)) {
            phoneError.value = 'Nomor telepon tidak valid. Format 08xxxxxx atau 628xxxxxx';
        } else {
            phoneError.value = '';
        }
        
        // Update form with clean phone number
        if (cleanPhone !== newPhone) {
            form.phone = cleanPhone;
        }
    } else {
        phoneError.value = '';
    }
});

const submitForm = () => {
    // Check if there are any phone validation errors
    if (phoneError.value) {
        return;
    }
    
    form.patch(route('profile.update'));
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Information
            </h2>
        </header>

        <form
            @submit.prevent="submitForm"
            class="mt-6 space-y-6"
        >
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="phone" value="Phone Number" />

                <TextInput
                    id="phone"
                    type="tel"
                    class="mt-1 block w-full"
                    v-model="form.phone"
                    required
                    autocomplete="tel"
                    placeholder="081234567890"
                    :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': phoneError || form.errors.phone }"
                />

                <InputError class="mt-2" :message="phoneError || form.errors.phone" />
                <p class="mt-1 text-xs text-gray-500">
                    <span v-if="form.phone && !phoneError && form.phone.length >= 10" class="text-green-600 ml-2">✓ Format valid</span>
                </p>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
