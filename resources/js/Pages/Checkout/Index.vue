<template>
    <AppLayout>
        <Head :title="`Checkout - ${template.name}`" />

        <!-- Loading Component -->
        <PageLoader :show="loading" />

        <!-- Confirmation Dialog -->
        <ConfirmationDialog
            :show="showConfirmDialog"
            :loading="submitting"
            :template="template"
            :form-data="formData"
            :pricing="pricing"
            @confirm="submitForm"
            @cancel="showConfirmDialog = false"
        />

        <!-- Error Dialog -->
        <ErrorDialog
            :show="showErrorDialog"
            :title="errorTitle"
            :message="errorMessage"
            :error="errorDetails"
            :show-retry="true"
            @close="closeErrorDialog"
            @retry="retrySubmission"
        />

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="flex items-center justify-center mb-4">
                        <span class="text-sm font-medium bg-white bg-opacity-20 px-3 py-1 rounded-full">Step 2 of 3</span>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        Kostum Konten Website
                    </h1>
                    <p class="text-xl opacity-90 max-w-2xl mx-auto">
                        Lengkapi informasi berikut dan buatlah template {{ template.name }} Anda
                    </p>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6" v-if="$page.props.flash?.message">
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg shadow-sm" v-if="$page.props.flash?.type === 'success'">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 font-medium">{{ $page.props.flash.message }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Form Section -->
                <div class="lg:col-span-2">
                    <form @submit.prevent="showConfirmation" class="space-y-8">
                        <!-- Company Information -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Company Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="company_name" value="Company Name" />
                                    <TextInput
                                        id="company_name"
                                        v-model="formData.company_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="PT. Your Company Name"
                                        required
                                    />
                                    <InputError :message="errors.company_name" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="website_name" value="Website Name" />
                                    <TextInput
                                        id="website_name"
                                        v-model="formData.website_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="Your Website Name"
                                        required
                                    />
                                    <InputError :message="errors.website_name" class="mt-2" />
                                </div>
                                <div class="md:col-span-2">
                                    <InputLabel for="company_tagline" value="Company Tagline" />
                                    <TextInput
                                        id="company_tagline"
                                        v-model="formData.company_tagline"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="Your company tagline"
                                    />
                                    <InputError :message="errors.company_tagline" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- SEO Settings -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>
                            <div class="space-y-6">
                                <div>
                                    <InputLabel for="seo_title" value="SEO Title" />
                                    <TextInput
                                        id="seo_title"
                                        v-model="formData.seo_title"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="Website title for search engines"
                                        required
                                    />
                                    <InputError :message="errors.seo_title" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500">{{ formData.seo_title?.length || 0 }}/60 characters</p>
                                </div>
                                <div>
                                    <InputLabel for="seo_description" value="SEO Description" />
                                    <textarea
                                        id="seo_description"
                                        v-model="formData.seo_description"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        rows="3"
                                        placeholder="Website description for search engines"
                                        required
                                    ></textarea>
                                    <InputError :message="errors.seo_description" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500">{{ formData.seo_description?.length || 0 }}/160 characters</p>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="contact_email" value="Contact Email" />
                                    <TextInput
                                        id="contact_email"
                                        v-model="formData.contact_email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        placeholder="contact@company.com"
                                        required
                                    />
                                    <InputError :message="errors.contact_email" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="contact_phone" value="Contact Phone" />
                                    <TextInput
                                        id="contact_phone"
                                        v-model="formData.contact_phone"
                                        type="tel"
                                        class="mt-1 block w-full"
                                        placeholder="+62 812-3456-7890"
                                        required
                                    />
                                    <InputError :message="errors.contact_phone" class="mt-2" />
                                </div>
                                <div class="md:col-span-2">
                                    <InputLabel for="contact_address" value="Complete Address" />
                                    <textarea
                                        id="contact_address"
                                        v-model="formData.contact_address"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        rows="3"
                                        placeholder="Your complete business address"
                                        required
                                    ></textarea>
                                    <InputError :message="errors.contact_address" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Services (Repeater) -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Services</h3>
                            <div class="space-y-4">
                                <div v-for="(service, index) in formData.services" :key="index" class="border border-gray-200 rounded-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <InputLabel :for="'service_title_' + index" value="Service Title" />
                                            <TextInput
                                                :id="'service_title_' + index"
                                                v-model="service.title"
                                                type="text"
                                                class="mt-1 block w-full"
                                                placeholder="Service name"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <InputLabel :for="'service_icon_' + index" value="Icon Class" />
                                            <TextInput
                                                :id="'service_icon_' + index"
                                                v-model="service.icon"
                                                type="text"
                                                class="mt-1 block w-full"
                                                placeholder="fas fa-chart-line"
                                            />
                                        </div>
                                        <div class="flex items-end">
                                            <SecondaryButton
                                                type="button"
                                                @click="removeService(index)"
                                                v-if="formData.services.length > 1"
                                                class="w-full"
                                            >
                                                Remove
                                            </SecondaryButton>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <InputLabel :for="'service_description_' + index" value="Service Description" />
                                        <textarea
                                            :id="'service_description_' + index"
                                            v-model="service.description"
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            rows="2"
                                            placeholder="Describe your service"
                                            required
                                        ></textarea>
                                    </div>
                                </div>
                                <PrimaryButton
                                    type="button"
                                    @click="addService"
                                    class="w-full"
                                    v-if="formData.services.length < 10"
                                >
                                    Add Service
                                </PrimaryButton>
                            </div>
                        </div>

                        <!-- Social Media Links -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Social Media Links</h3>
                            <div class="space-y-4">
                                <div v-for="(link, index) in formData.social_links" :key="index" class="border border-gray-200 rounded-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div>
                                            <InputLabel :for="'social_platform_' + index" value="Platform" />
                                            <select
                                                :id="'social_platform_' + index"
                                                v-model="link.platform"
                                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                required
                                            >
                                                <option value="">Select Platform</option>
                                                <option value="facebook">Facebook</option>
                                                <option value="instagram">Instagram</option>
                                                <option value="twitter">Twitter</option>
                                                <option value="linkedin">LinkedIn</option>
                                                <option value="youtube">YouTube</option>
                                                <option value="tiktok">TikTok</option>
                                            </select>
                                        </div>
                                        <div>
                                            <InputLabel :for="'social_url_' + index" value="URL" />
                                            <TextInput
                                                :id="'social_url_' + index"
                                                v-model="link.url"
                                                type="url"
                                                class="mt-1 block w-full"
                                                placeholder="https://facebook.com/yourpage"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <InputLabel :for="'social_label_' + index" value="Display Label" />
                                            <TextInput
                                                :id="'social_label_' + index"
                                                v-model="link.label"
                                                type="text"
                                                class="mt-1 block w-full"
                                                placeholder="Follow us on Facebook"
                                            />
                                        </div>
                                        <div class="flex items-end">
                                            <SecondaryButton
                                                type="button"
                                                @click="removeSocialLink(index)"
                                                v-if="formData.social_links.length > 0"
                                                class="w-full"
                                            >
                                                Remove
                                            </SecondaryButton>
                                        </div>
                                    </div>
                                </div>
                                <PrimaryButton
                                    type="button"
                                    @click="addSocialLink"
                                    class="w-full"
                                    v-if="formData.social_links.length < 10"
                                >
                                    Add Social Link
                                </PrimaryButton>
                            </div>
                        </div>

                        <!-- Design Settings -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Design Settings</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="primary_color" value="Primary Color" />
                                    <TextInput
                                        id="primary_color"
                                        v-model="formData.primary_color"
                                        type="color"
                                        class="mt-1 block w-full h-10"
                                        required
                                    />
                                    <InputError :message="errors.primary_color" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="secondary_color" value="Secondary Color" />
                                    <TextInput
                                        id="secondary_color"
                                        v-model="formData.secondary_color"
                                        type="color"
                                        class="mt-1 block w-full h-10"
                                    />
                                    <InputError :message="errors.secondary_color" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Website Settings -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Website Settings</h3>
                            <div>
                                <InputLabel for="subdomain" value="Website Address" />
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <TextInput
                                        id="subdomain"
                                        v-model="formData.subdomain"
                                        type="text"
                                        class="flex-1 rounded-none rounded-l-md"
                                        placeholder="yourwebsite"
                                        required
                                    />
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        .oursite.com
                                    </span>
                                </div>
                                <InputError :message="errors.subdomain" class="mt-2" />
                            </div>
                        </div>

                        <!-- Terms Agreement -->
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input
                                        id="agree_terms"
                                        v-model="formData.agree_terms"
                                        type="checkbox"
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                        required
                                    />
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="agree_terms" class="font-medium text-gray-700">
                                        I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500">Terms of Service</a> and <a href="#" class="text-indigo-600 hover:text-indigo-500">Privacy Policy</a>
                                    </label>
                                    <InputError :message="errors.agree_terms" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <PrimaryButton
                                type="submit"
                                :loading="submitting"
                                class="px-8 py-3"
                            >
                                Continue to Payment
                            </PrimaryButton>
                        </div>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <OrderSummary
                        :template="template"
                        :pricing="pricing"
                        :form-data="formData"
                        :submitting="submitting"
                        :voucher-discount="formData.voucher_discount"
                        @submit="showConfirmation"
                        @voucherApplied="handleVoucherApplied"
                    />
                </div>



            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import PageLoader from '@/Components/PageLoader.vue'
import ConfirmationDialog from '@/Components/ConfirmationDialog.vue'
import ErrorDialog from '@/Components/ErrorDialog.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import InputError from '@/Components/InputError.vue'
import OrderSummary from '@/Components/OrderSummary.vue'

// Props
const props = defineProps({
    template: Object,
    user: Object,
    pricing: Object,
    form_fields: Array,
})

// Reactive data
const loading = ref(false)
const submitting = ref(false)
const showConfirmDialog = ref(false)
const showErrorDialog = ref(false)
const errorTitle = ref('')
const errorMessage = ref('')
const errorDetails = ref(null)

// Form data


const formData = reactive({

    company_name: '',
    website_name: '',
    company_tagline: '',
    seo_title: '',
    seo_description: '',
    contact_email: '',
    contact_phone: '',
    contact_address: '',
    services: [
        { title: '', description: '', icon: '' }
    ],
    social_links: [],
    primary_color: '#2146ff',
    secondary_color: '#64748b',
    subdomain: '',
    agree_terms: false,
    voucher_code: '',
    voucher_discount: 0,
})

// Computed
const errors = computed(() => usePage().props.errors || {})

// Methods
const showConfirmation = () => {
    showConfirmDialog.value = true
}

const submitForm = () => {
    submitting.value = true
    showConfirmDialog.value = false

    const form = useForm(formData)

    form.post(route('templates.checkout.process', props.template.slug), {
        onSuccess: () => {
            submitting.value = false
        },
        onError: (errors) => {
            submitting.value = false
        }
    })
}

const addService = () => {
    if (formData.services.length < 10) {
        formData.services.push({
            title: '',
            description: '',
            icon: ''
        })
    }
}

const removeService = (index) => {
    if (formData.services.length > 1) {
        formData.services.splice(index, 1)
    }
}

const addSocialLink = () => {
    if (formData.social_links.length < 10) {
        formData.social_links.push({
            platform: 'facebook',
            url: '',
            label: ''
        })
    }
}

const removeSocialLink = (index) => {
    formData.social_links.splice(index, 1)
}

const handleVoucherApplied = (voucherData) => {
    formData.voucher_code = voucherData.code
    formData.voucher_discount = voucherData.discount
}

const closeErrorDialog = () => {
    showErrorDialog.value = false
    errorTitle.value = ''
    errorMessage.value = ''
    errorDetails.value = null
}

const retrySubmission = () => {
    closeErrorDialog()
    showConfirmation()
}
</script>
