@extends('layouts.app')

@section('title', 'Checkout - ' . $template->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto text-center">
                <div class="flex items-center justify-center mb-4">
                    <span class="text-sm font-medium bg-white bg-opacity-20 px-3 py-1 rounded-full">Step 2 of 3</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Kostum Konten Website
                </h1>
                <p class="text-xl opacity-90 max-w-2xl mx-auto">
                    Lengkapi informasi berikut dan buatlah template {{ $template->name }} Anda
                </p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success') || session('error') || session('info'))
        <div class="container mx-auto px-4 pt-6">
            @if(session('success'))
                <div class="max-w-6xl mx-auto mb-6">
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-6xl mx-auto mb-6">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="max-w-6xl mx-auto mb-6">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700 font-medium">{{ session('info') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Breadcrumb -->
    <div class="container mx-auto px-4 py-4">
        <nav class="flex max-w-6xl mx-auto" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('templates.index') }}" class="text-gray-400 hover:text-gray-500 transition duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        <span class="sr-only">Templates</span>
                    </a>
                </li>
                <li>
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
                <li>
                    <a href="{{ route('templates.show', $template->slug) }}" class="text-gray-400 hover:text-gray-500 transition duration-200">
                        {{ $template->name }}
                    </a>
                </li>
                <li>
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
                <li class="text-gray-500 font-medium">
                    Checkout
                </li>
            </ol>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <form method="POST" action="{{ route('templates.checkout.process', $template->slug) }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf
                
                <!-- Left Column - Form -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Company Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                                    <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                                </svg>
                                Informasi Perusahaan
                            </h3>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Company Name -->
                                <div>
                                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Company Name <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="company_name"
                                        name="company_name" 
                                        value="{{ old('company_name') }}"
                                        placeholder="PT. Corporate Indonesia"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('company_name') border-red-300 @enderror"
                                        required
                                    >
                                    @error('company_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Company Tagline -->
                                <div>
                                    <label for="company_tagline" class="block text-sm font-medium text-gray-700 mb-2">
                                        Company Tagline
                                    </label>
                                    <input 
                                        type="text" 
                                        id="company_tagline"
                                        name="company_tagline" 
                                        value="{{ old('company_tagline') }}"
                                        placeholder="Solusi Digital Bisnis Anda"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('company_tagline') border-red-300 @enderror"
                                    >
                                    @error('company_tagline')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- SEO Title -->
                            <div>
                                <label for="seo_title" class="block text-sm font-medium text-gray-700 mb-2">
                                    SEO Title <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="seo_title"
                                    name="seo_title" 
                                    value="{{ old('seo_title') }}"
                                    placeholder="PT Corporate Indonesia - Solusi Bisnis Terbaik & Terpercaya"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('seo_title') border-red-300 @enderror"
                                    maxlength="60"
                                    required
                                >
                                <p class="mt-1 text-xs text-gray-500">Recommended: 50-60 characters</p>
                                @error('seo_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- SEO Description -->
                            <div>
                                <label for="seo_description" class="block text-sm font-medium text-gray-700 mb-2">
                                    SEO Description <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    id="seo_description"
                                    name="seo_description" 
                                    rows="3"
                                    placeholder="Describe your business for search engines..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('seo_description') border-red-300 @enderror"
                                    maxlength="160"
                                    required
                                >{{ old('seo_description') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Recommended: 150-160 characters</p>
                                @error('seo_description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- SEO Keywords -->
                            <div>
                                <label for="seo_keywords" class="block text-sm font-medium text-gray-700 mb-2">
                                    SEO Keywords
                                </label>
                                <input 
                                    type="text" 
                                    id="seo_keywords"
                                    name="seo_keywords" 
                                    value="{{ old('seo_keywords') }}"
                                    placeholder="konsultasi bisnis, digital marketing, solusi teknologi"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('seo_keywords') border-red-300 @enderror"
                                >
                                <p class="mt-1 text-xs text-gray-500">Separate keywords with commas</p>
                                @error('seo_keywords')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                                Informasi Kontak
                            </h3>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Contact Email -->
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="email" 
                                        id="contact_email"
                                        name="contact_email" 
                                        value="{{ old('contact_email', $user->email) }}"
                                        placeholder="info@corporateindonesia.com"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_email') border-red-300 @enderror"
                                        required
                                    >
                                    @error('contact_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Contact Phone -->
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="tel" 
                                        id="contact_phone"
                                        name="contact_phone" 
                                        value="{{ old('contact_phone', $user->phone) }}"
                                        placeholder="+62 812-3456-7890"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_phone') border-red-300 @enderror"
                                        required
                                    >
                                    @error('contact_phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Contact Address -->
                            <div>
                                <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Complete Address <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    id="contact_address"
                                    name="contact_address" 
                                    rows="2"
                                    placeholder="Jl. Sudirman No. 123, Jakarta Pusat 10220, DKI Jakarta, Indonesia"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_address') border-red-300 @enderror"
                                    required
                                >{{ old('contact_address') }}</textarea>
                                @error('contact_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- WhatsApp Settings -->
                            <div class="border-t border-gray-200 pt-6">
                                <div class="flex items-center mb-4">
                                    <input 
                                        id="whatsapp_enabled" 
                                        name="whatsapp_enabled" 
                                        type="checkbox" 
                                        value="1"
                                        class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                        {{ old('whatsapp_enabled', true) ? 'checked' : '' }}
                                    >
                                    <div class="ml-3">
                                        <label for="whatsapp_enabled" class="text-sm font-medium text-gray-700">
                                            Enable WhatsApp Chat Widget
                                        </label>
                                        <p class="text-xs text-gray-500">Add floating WhatsApp button to your website</p>
                                    </div>
                                </div>

                                <div id="whatsapp_settings" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- WhatsApp Number -->
                                    <div>
                                        <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">
                                            WhatsApp Number
                                        </label>
                                        <input 
                                            type="text" 
                                            id="whatsapp_number"
                                            name="whatsapp_number" 
                                            value="{{ old('whatsapp_number') }}"
                                            placeholder="6281234567890"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('whatsapp_number') border-red-300 @enderror"
                                        >
                                        <p class="mt-1 text-xs text-gray-500">Format: 6281234567890 (with country code, no +)</p>
                                        @error('whatsapp_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- WhatsApp Position -->
                                    <div>
                                        <label for="whatsapp_position" class="block text-sm font-medium text-gray-700 mb-2">
                                            Widget Position
                                        </label>
                                        <select 
                                            id="whatsapp_position"
                                            name="whatsapp_position"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('whatsapp_position') border-red-300 @enderror"
                                        >
                                            <option value="bottom-right" {{ old('whatsapp_position', 'bottom-right') == 'bottom-right' ? 'selected' : '' }}>Bottom Right</option>
                                            <option value="bottom-left" {{ old('whatsapp_position') == 'bottom-left' ? 'selected' : '' }}>Bottom Left</option>
                                        </select>
                                        @error('whatsapp_position')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- WhatsApp Message -->
                                    <div class="md:col-span-2">
                                        <label for="whatsapp_message" class="block text-sm font-medium text-gray-700 mb-2">
                                            Default Message
                                        </label>
                                        <textarea 
                                            id="whatsapp_message"
                                            name="whatsapp_message" 
                                            rows="2"
                                            placeholder="Halo {company_name}, saya tertarik dengan layanan Anda..."
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('whatsapp_message') border-red-300 @enderror"
                                        >{{ old('whatsapp_message') }}</textarea>
                                        <p class="mt-1 text-xs text-gray-500">Use {company_name} to automatically insert company name</p>
                                        @error('whatsapp_message')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Website Settings -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                </svg>
                                Setting Website
                            </h3>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <!-- Website Address -->
                            <div>
                                <label for="subdomain" class="block text-sm font-medium text-gray-700 mb-2">
                                    Website Address <span class="text-red-500">*</span>
                                </label>
                                <div class="flex">
                                    <input 
                                        type="text" 
                                        id="subdomain"
                                        name="subdomain" 
                                        value="{{ old('subdomain') }}"
                                        placeholder="corporateindonesia"
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('subdomain') border-red-300 @enderror"
                                        pattern="[a-z0-9-]+"
                                        required
                                    >
                                    <span class="inline-flex items-center px-4 py-3 bg-gray-50 border border-l-0 border-gray-300 rounded-r-lg text-sm text-gray-500">
                                        .oursite.com
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Only lowercase letters, numbers, and hyphens allowed</p>
                                @error('subdomain')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Design Colors -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Primary Color -->
                                <div>
                                    <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">
                                        Primary Color <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center space-x-3">
                                        <input 
                                            type="color" 
                                            id="primary_color"
                                            name="primary_color" 
                                            value="{{ old('primary_color', '#2146ff') }}"
                                            class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer @error('primary_color') border-red-300 @enderror"
                                            required
                                        >
                                        <div class="flex-1">
                                            <input 
                                                type="text" 
                                                value="{{ old('primary_color', '#2146ff') }}"
                                                placeholder="#2146ff"
                                                class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                                                readonly
                                                id="primary_color_display"
                                            >
                                        </div>
                                    </div>
                                    @error('primary_color')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Secondary Color -->
                                <div>
                                    <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-2">
                                        Secondary Color
                                    </label>
                                    <div class="flex items-center space-x-3">
                                        <input 
                                            type="color" 
                                            id="secondary_color"
                                            name="secondary_color" 
                                            value="{{ old('secondary_color', '#64748b') }}"
                                            class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer @error('secondary_color') border-red-300 @enderror"
                                        >
                                        <div class="flex-1">
                                            <input 
                                                type="text" 
                                                value="{{ old('secondary_color', '#64748b') }}"
                                                placeholder="#64748b"
                                                class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                                                readonly
                                                id="secondary_color_display"
                                            >
                                        </div>
                                    </div>
                                    @error('secondary_color')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Font Family -->
                            <div>
                                <label for="font_family" class="block text-sm font-medium text-gray-700 mb-2">
                                    Font Family
                                </label>
                                <select 
                                    id="font_family"
                                    name="font_family"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('font_family') border-red-300 @enderror"
                                >
                                    <option value="inter" {{ old('font_family', 'inter') == 'inter' ? 'selected' : '' }}>Inter (Modern Sans-serif)</option>
                                    <option value="roboto" {{ old('font_family') == 'roboto' ? 'selected' : '' }}>Roboto (Clean Sans-serif)</option>
                                    <option value="open-sans" {{ old('font_family') == 'open-sans' ? 'selected' : '' }}>Open Sans (Friendly Sans-serif)</option>
                                    <option value="lora" {{ old('font_family') == 'lora' ? 'selected' : '' }}>Lora (Elegant Serif)</option>
                                    <option value="poppins" {{ old('font_family') == 'poppins' ? 'selected' : '' }}>Poppins (Geometric Sans-serif)</option>
                                    <option value="playfair" {{ old('font_family') == 'playfair' ? 'selected' : '' }}>Playfair Display (Stylish Serif)</option>
                                </select>
                                @error('font_family')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Border Radius -->
                            <!-- <div>
                                <label for="border_radius" class="block text-sm font-medium text-gray-700 mb-2">
                                    Design Style
                                </label>
                                <select 
                                    id="border_radius"
                                    name="border_radius"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('border_radius') border-red-300 @enderror"
                                >
                                    <option value="none" {{ old('border_radius') == 'none' ? 'selected' : '' }}>Sharp (No Rounded Corners)</option>
                                    <option value="small" {{ old('border_radius') == 'small' ? 'selected' : '' }}>Slightly Rounded</option>
                                    <option value="medium" {{ old('border_radius', 'medium') == 'medium' ? 'selected' : '' }}>Medium Rounded</option>
                                    <option value="large" {{ old('border_radius') == 'large' ? 'selected' : '' }}>Very Rounded</option>
                                    <option value="full" {{ old('border_radius') == 'full' ? 'selected' : '' }}>Fully Rounded (Pills)</option>
                                </select>
                                @error('border_radius')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div> -->
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input 
                                    id="agree_terms" 
                                    name="agree_terms" 
                                    type="checkbox" 
                                    value="1"
                                    class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error('agree_terms') border-red-300 @enderror"
                                    {{ old('agree_terms') ? 'checked' : '' }}
                                    required
                                >
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="agree_terms" class="font-medium text-gray-700">
                                    I agree to the Terms of Service and Privacy Policy <span class="text-red-500">*</span>
                                </label>
                                <p class="text-gray-500">
                                    By checking this box, you agree to our 
                                    <a href="#" class="text-blue-600 hover:text-blue-500">Terms of Service</a> 
                                    and 
                                    <a href="#" class="text-blue-600 hover:text-blue-500">Privacy Policy</a>.
                                </p>
                                @error('agree_terms')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column - Order Summary -->
                <div class="lg:col-span-1">
                    <div class="sticky top-8">
                        <!-- Order Summary -->
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                                <h3 class="text-lg font-semibold text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                                        <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Order Summary
                                </h3>
                            </div>
                            
                            <div class="p-6">
                                <!-- Template Preview -->
                                <div class="mb-6">
                                    <img 
                                        src="{{ $template->preview_image ?? '/images/template-placeholder.jpg' }}" 
                                        alt="{{ $template->name }}"
                                        class="w-full h-32 object-cover rounded-lg mb-4"
                                    >
                                    
                                    <div>
                                        <h4 class="font-bold text-lg text-gray-900 mb-1">
                                            {{ $template->name }}
                                        </h4>
                                        
                                        @if($template->category)
                                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mb-2">
                                                {{ ucwords(str_replace('-', ' ', $template->category)) }}
                                            </span>
                                        @endif
                                        
                                        @if($template->description)
                                            <p class="text-sm text-gray-600">
                                                {{ Str::limit($template->description, 100) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Pricing Breakdown -->
                                <div class="space-y-4 border-t border-gray-200 pt-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Template Price</span>
                                        <span class="font-medium">
                                            @if($subtotal > 0)
                                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                                            @else
                                                FREE
                                            @endif
                                        </span>
                                    </div>
                                    
                                    @if($subtotal > 0)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Platform Fee</span>
                                            <span class="text-gray-500">Rp {{ number_format($platformFee, 0, ',', '.') }}</span>
                                        </div>
                                        
                                        <hr class="border-gray-200">
                                        
                                        <div class="flex justify-between text-lg font-bold">
                                            <span>Total</span>
                                            <span class="text-green-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                        </div>
                                    @else
                                        <hr class="border-gray-200">
                                        
                                        <div class="flex justify-between text-lg font-bold">
                                            <span>Total</span>
                                            <span class="text-green-600">FREE</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- What's Included -->
                                <!-- <div class="mt-6 pt-4 border-t border-gray-200">
                                    <h5 class="font-semibold text-gray-900 mb-3">What's included:</h5>
                                    <ul class="space-y-2 text-sm">
                                        <li class="flex items-center">
                                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            <span>Responsive Design</span>
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            <span>SEO Optimized</span>
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            <span>1 Year Hosting</span>
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            <span>24/7 Support</span>
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            <span>WhatsApp Integration</span>
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            <span>SSL Certificate</span>
                                        </li>
                                    </ul>
                                </div> -->

                                <!-- Submit Button -->
                                <div class="mt-6 pt-4">
                                    <button 
                                        type="submit"
                                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                                    >
                                        @if($subtotal > 0)
                                            Lanjutkan
                                        @else
                                            Buat Gratis
                                        @endif
                                    </button>
                                    
                                    <p class="text-center text-xs text-gray-500 mt-2">
                                        @if($subtotal > 0)
                                            Anda akan diarahkan ke halaman pembayaran
                                        @else
                                            Website Anda akan dibuat secara gratis
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Security Badge -->
                        <div class="mt-6 text-center">
                            <div class="inline-flex items-center px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-green-700 font-medium">SSL Secured</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Color picker handlers
    const primaryColor = document.getElementById('primary_color');
    const secondaryColor = document.getElementById('secondary_color');
    const primaryDisplay = document.getElementById('primary_color_display');
    const secondaryDisplay = document.getElementById('secondary_color_display');
    
    primaryColor.addEventListener('input', function() {
        primaryDisplay.value = this.value;
    });
    
    secondaryColor.addEventListener('input', function() {
        secondaryDisplay.value = this.value;
    });
    
    // Subdomain validation
    const subdomainInput = document.getElementById('subdomain');
    subdomainInput.addEventListener('input', function() {
        this.value = this.value.toLowerCase().replace(/[^a-z0-9-]/g, '');
    });

    // WhatsApp settings toggle
    const whatsappEnabled = document.getElementById('whatsapp_enabled');
    const whatsappSettings = document.getElementById('whatsapp_settings');
    
    function toggleWhatsappSettings() {
        if (whatsappEnabled.checked) {
            whatsappSettings.style.display = 'grid';
        } else {
            whatsappSettings.style.display = 'none';
        }
    }
    
    whatsappEnabled.addEventListener('change', toggleWhatsappSettings);
    toggleWhatsappSettings(); // Initial state

    // Character counters
    const seoTitle = document.getElementById('seo_title');
    const seoDescription = document.getElementById('seo_description');
    
    function addCharacterCounter(element, maxLength) {
        const counter = document.createElement('div');
        counter.className = 'text-xs text-gray-500 mt-1';
        element.parentNode.insertBefore(counter, element.nextSibling);
        
        function updateCounter() {
            const remaining = maxLength - element.value.length;
            counter.textContent = `${element.value.length}/${maxLength} characters`;
            counter.classList.toggle('text-red-500', remaining < 10);
            counter.classList.toggle('text-yellow-500', remaining < 20 && remaining >= 10);
        }
        
        element.addEventListener('input', updateCounter);
        updateCounter();
    }
    
    addCharacterCounter(seoTitle, 60);
    addCharacterCounter(seoDescription, 160);
});
</script>
@endsection