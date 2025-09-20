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
    @if(session('success') || session('error') || session('info') || $errors->any())
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
            
            @if($errors->any())
                <div class="max-w-6xl mx-auto mb-6">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-center mb-2">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 font-medium">Terdapat kesalahan pada form:</p>
                            </div>
                        </div>
                        <ul class="ml-8 text-sm text-red-600 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
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
            <form method="POST" action="{{ route('templates.checkout.process', $template->slug) }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8" id="checkoutForm">
                @csrf
                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf-token">
                <input type="hidden" name="form_timestamp" value="{{ now()->timestamp }}">
                <input type="hidden" name="session_id" value="{{ session()->getId() }}">
                
                <!-- Left Column - Form -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Company Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2h4a1 1 0 110 2h-1v11a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 010-2h4zM9 3v1h2V3H9zM8 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 112 0v6a1 1 0 11-2 0V8z"/>
                                </svg>
                                Informasi Perusahaan & SEO
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
                                Kontak & WhatsApp
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

                                    <!-- WhatsApp Greeting Text -->
                                    <div>
                                        <label for="whatsapp_greeting_text" class="block text-sm font-medium text-gray-700 mb-2">
                                            WhatsApp Button Text
                                        </label>
                                        <input 
                                            type="text" 
                                            id="whatsapp_greeting_text"
                                            name="whatsapp_greeting_text" 
                                            value="{{ old('whatsapp_greeting_text', 'Chat Us') }}"
                                            placeholder="Chat Us"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('whatsapp_greeting_text') border-red-300 @enderror"
                                        >
                                        @error('whatsapp_greeting_text')
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
                                    <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
                                </svg>
                                Website, SEO & Analytics
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

                            <!-- SEO & Analytics -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="google_analytics" class="block text-sm font-medium text-gray-700 mb-2">
                                        Google Analytics ID
                                    </label>
                                    <input 
                                        type="text" 
                                        id="google_analytics"
                                        name="google_analytics" 
                                        value="{{ old('google_analytics') }}"
                                        placeholder="G-XXXXXXXXXX"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>

                                <div>
                                    <label for="google_tag_manager" class="block text-sm font-medium text-gray-700 mb-2">
                                        Google Tag Manager ID
                                    </label>
                                    <input 
                                        type="text" 
                                        id="google_tag_manager"
                                        name="google_tag_manager" 
                                        value="{{ old('google_tag_manager') }}"
                                        placeholder="GTM-XXXXXXX"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="robots_meta" class="block text-sm font-medium text-gray-700 mb-2">
                                    Robots Meta Tag
                                </label>
                                <select 
                                    id="robots_meta"
                                    name="robots_meta"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="index,follow" {{ old('robots_meta', 'index,follow') == 'index,follow' ? 'selected' : '' }}>Index, Follow</option>
                                    <option value="noindex,nofollow" {{ old('robots_meta') == 'noindex,nofollow' ? 'selected' : '' }}>Noindex, Nofollow</option>
                                    <option value="index,nofollow" {{ old('robots_meta') == 'index,nofollow' ? 'selected' : '' }}>Index, Nofollow</option>
                                    <option value="noindex,follow" {{ old('robots_meta') == 'noindex,follow' ? 'selected' : '' }}>Noindex, Follow</option>
                                </select>
                            </div>

                            <!-- Design Colors -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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

                                <!-- Accent Color -->
                                <div>
                                    <label for="accent_color" class="block text-sm font-medium text-gray-700 mb-2">
                                        Accent Color
                                    </label>
                                    <div class="flex items-center space-x-3">
                                        <input 
                                            type="color" 
                                            id="accent_color"
                                            name="accent_color" 
                                            value="{{ old('accent_color', '#f59e0b') }}"
                                            class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer @error('accent_color') border-red-300 @enderror"
                                        >
                                        <div class="flex-1">
                                            <input 
                                                type="text" 
                                                value="{{ old('accent_color', '#f59e0b') }}"
                                                placeholder="#f59e0b"
                                                class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                                                readonly
                                                id="accent_color_display"
                                            >
                                        </div>
                                    </div>
                                    @error('accent_color')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Typography & Style -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                <div>
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
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gallery Photos Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                                Galeri & Portfolio
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div id="gallery-container">
                                @if(old('gallery_photos'))
                                    @foreach(old('gallery_photos') as $index => $photo)
                                        <div class="gallery-item p-4 border border-gray-200 rounded-lg mb-4 relative bg-gray-50">
                                            <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-gallery">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Photo URL</label>
                                                <input type="url" name="gallery_photos[]" value="{{ $photo }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="https://images.unsplash.com/photo-...">
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Default first item -->
                                    <div class="gallery-item p-4 border border-gray-200 rounded-lg mb-4 relative bg-gray-50">
                                        <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-gallery">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Photo URL</label>
                                            <input type="url" name="gallery_photos[]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="https://images.unsplash.com/photo-...">
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="button" id="add-gallery" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                + Add Gallery Photo
                            </button>
                        </div>
                    </div>

                    <!-- Services Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Layanan & Produk Unggulan
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div id="services-container">
                                @if(old('services'))
                                    @foreach(old('services') as $index => $service)
                                        <div class="service-item p-4 border border-gray-200 rounded-lg mb-4 relative">
                                            <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-service">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Font Awesome)</label>
                                                    <input type="text" name="services[{{ $index }}][icon]" value="{{ $service['icon'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="fas fa-chart-line">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Link (Anchor)</label>
                                                    <input type="text" name="services[{{ $index }}][link]" value="{{ $service['link'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="#konsultasi-bisnis">
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Layanan</label>
                                                <input type="text" name="services[{{ $index }}][title]" value="{{ $service['title'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Konsultasi Bisnis">
                                            </div>
                                            <div class="mt-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                                <textarea name="services[{{ $index }}][description]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Strategi dan perencanaan bisnis...">{{ $service['description'] }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Default first item -->
                                    <div class="service-item p-4 border border-gray-200 rounded-lg mb-4 relative">
                                        <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-service">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Font Awesome)</label>
                                                <input type="text" name="services[0][icon]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="fas fa-chart-line">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Link (Anchor)</label>
                                                <input type="text" name="services[0][link]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="#konsultasi-bisnis">
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Layanan</label>
                                            <input type="text" name="services[0][title]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Konsultasi Bisnis">
                                        </div>
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                            <textarea name="services[0][description]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Strategi dan perencanaan bisnis..."></textarea>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="button" id="add-service" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                + Tambah Layanan
                            </button>
                        </div>
                    </div>

                    <!-- Hero & About Content Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                </svg>
                                Hero & About Content
                            </h3>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <!-- Hero Section Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="hero_title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Hero Title
                                    </label>
                                    <input 
                                        type="text" 
                                        id="hero_title"
                                        name="hero_title" 
                                        value="{{ old('hero_title', 'Solusi Bisnis Terbaik') }}"
                                        placeholder="Solusi Bisnis Terbaik"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>

                                <div>
                                    <label for="hero_cta_text" class="block text-sm font-medium text-gray-700 mb-2">
                                        Hero Button Text
                                    </label>
                                    <input 
                                        type="text" 
                                        id="hero_cta_text"
                                        name="hero_cta_text" 
                                        value="{{ old('hero_cta_text', 'Konsultasi Gratis') }}"
                                        placeholder="Konsultasi Gratis"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="hero_subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                                    Hero Subtitle
                                </label>
                                <textarea 
                                    id="hero_subtitle"
                                    name="hero_subtitle" 
                                    rows="2"
                                    placeholder="Kami memberikan layanan terbaik untuk mengembangkan bisnis Anda..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >{{ old('hero_subtitle', 'Kami memberikan layanan terbaik untuk mengembangkan bisnis Anda dengan teknologi modern dan strategi yang tepat sasaran') }}</textarea>
                            </div>

                            <div>
                                <label for="hero_cta_link" class="block text-sm font-medium text-gray-700 mb-2">
                                    Hero Button Link
                                </label>
                                <input 
                                    type="text" 
                                    id="hero_cta_link"
                                    name="hero_cta_link" 
                                    value="{{ old('hero_cta_link', '#contact') }}"
                                    placeholder="#contact"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>

                            <!-- About Section -->
                            <div class="border-t border-gray-200 pt-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label for="about_title" class="block text-sm font-medium text-gray-700 mb-2">
                                            About Section Title
                                        </label>
                                        <input 
                                            type="text" 
                                            id="about_title"
                                            name="about_title" 
                                            value="{{ old('about_title', 'Tentang Kami') }}"
                                            placeholder="Tentang Kami"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        >
                                    </div>

                                    <div>
                                        <label for="contact_title" class="block text-sm font-medium text-gray-700 mb-2">
                                            Contact Section Title
                                        </label>
                                        <input 
                                            type="text" 
                                            id="contact_title"
                                            name="contact_title" 
                                            value="{{ old('contact_title', 'Hubungi Kami') }}"
                                            placeholder="Hubungi Kami"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        >
                                    </div>
                                </div>

                                <div>
                                    <label for="about_content" class="block text-sm font-medium text-gray-700 mb-2">
                                        About Content <span class="text-red-500">*</span>
                                    </label>
                                    <textarea 
                                        id="about_content"
                                        name="about_content" 
                                        rows="6"
                                        placeholder="Describe your company, mission, and values..."
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('about_content') border-red-300 @enderror"
                                        required
                                    >{{ old('about_content', '<p>PT Corporate Indonesia adalah perusahaan konsultan bisnis terdepan yang telah berpengalaman lebih dari 10 tahun dalam memberikan solusi bisnis terpadu. Kami memiliki komitmen untuk membantu perusahaan mencapai kesuksesan melalui strategi yang inovatif dan berkelanjutan.</p><p>Dengan tim ahli yang berpengalaman dan teknologi terdepan, kami siap menjadi partner strategis dalam transformasi digital dan pengembangan bisnis perusahaan Anda.</p>') }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500">You can use HTML tags for formatting</p>
                                    @error('about_content')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Testimonials Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-8">
                    <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M18 3a1 1 0 00-1.447-.894L8.763 6H5a3 3 0 000 6h.28l1.771 5.316A1 1 0 008 18h1a1 1 0 001-1v-4.382l6.553 3.276A1 1 0 0018 15V3z"/>
                            </svg>
                            Testimoni & Ulasan Klien
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div id="testimonials-container">
                            @if(old('testimonials'))
                                @foreach(old('testimonials') as $index => $testimonial)
                                    <div class="testimonial-item p-4 border border-gray-200 rounded-lg mb-4 relative bg-gray-50">
                                        <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-testimonial">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                                <input type="text" name="testimonials[{{ $index }}][name]" value="{{ $testimonial['name'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Rudi Wijaya">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Posisi / Jabatan</label>
                                                <input type="text" name="testimonials[{{ $index }}][position]" value="{{ $testimonial['position'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="CEO, PT Maju Bersama">
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Rating (1-5)</label>
                                            <select name="testimonials[{{ $index }}][rating]" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                                <option value="5" {{ $testimonial['rating'] == '5' ? 'selected' : '' }}>5 - Sangat Puas</option>
                                                <option value="4" {{ $testimonial['rating'] == '4' ? 'selected' : '' }}>4 - Puas</option>
                                                <option value="3" {{ $testimonial['rating'] == '3' ? 'selected' : '' }}>3 - Cukup</option>
                                                <option value="2" {{ $testimonial['rating'] == '2' ? 'selected' : '' }}>2 - Kurang</option>
                                                <option value="1" {{ $testimonial['rating'] == '1' ? 'selected' : '' }}>1 - Tidak Puas</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Testimoni</label>
                                            <textarea name="testimonials[{{ $index }}][content]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Pelayanan PT Corporate Indonesia sangat profesional...">{{ $testimonial['content'] }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <!-- Default first item -->
                                <div class="testimonial-item p-4 border border-gray-200 rounded-lg mb-4 relative bg-gray-50">
                                    <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-testimonial">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                            <input type="text" name="testimonials[0][name]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Rudi Wijaya">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Posisi / Jabatan</label>
                                            <input type="text" name="testimonials[0][position]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="CEO, PT Maju Bersama">
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Rating (1-5)</label>
                                        <select name="testimonials[0][rating]" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                            <option value="5">5 - Sangat Puas</option>
                                            <option value="4">4 - Puas</option>
                                            <option value="3">3 - Cukup</option>
                                            <option value="2">2 - Kurang</option>
                                            <option value="1">1 - Tidak Puas</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Testimoni</label>
                                        <textarea name="testimonials[0][content]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Pelayanan PT Corporate Indonesia sangat profesional..."></textarea>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" id="add-testimonial" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                            + Tambah Testimoni
                        </button>
                    </div>
                </div>
                
                <!-- Social Links Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-pink-600 to-pink-700 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"/>
                            </svg>
                            Social Media Links
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div id="social-links-container">
                            @if(old('social_links'))
                                @foreach(old('social_links') as $index => $social)
                                    <div class="social-item p-4 border border-gray-200 rounded-lg mb-4 relative bg-gray-50">
                                        <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-social">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Platform</label>
                                                <select name="social_links[{{ $index }}][platform]" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                                    <option value="facebook" {{ $social['platform'] == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                                    <option value="instagram" {{ $social['platform'] == 'instagram' ? 'selected' : '' }}>Instagram</option>
                                                    <option value="twitter" {{ $social['platform'] == 'twitter' ? 'selected' : '' }}>Twitter</option>
                                                    <option value="linkedin" {{ $social['platform'] == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                                                    <option value="youtube" {{ $social['platform'] == 'youtube' ? 'selected' : '' }}>YouTube</option>
                                                    <option value="tiktok" {{ $social['platform'] == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                                                <input type="url" name="social_links[{{ $index }}][url]" value="{{ $social['url'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="https://facebook.com/yourpage">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                                                <input type="text" name="social_links[{{ $index }}][label]" value="{{ $social['label'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Your Page Name">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <!-- Default first item -->
                                <div class="social-item p-4 border border-gray-200 rounded-lg mb-4 relative bg-gray-50">
                                    <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-social">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Platform</label>
                                            <select name="social_links[0][platform]" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                                <option value="facebook">Facebook</option>
                                                <option value="instagram">Instagram</option>
                                                <option value="twitter">Twitter</option>
                                                <option value="linkedin">LinkedIn</option>
                                                <option value="youtube">YouTube</option>
                                                <option value="tiktok">TikTok</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                                            <input type="url" name="social_links[0][url]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="https://facebook.com/yourpage">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                                            <input type="text" name="social_links[0][label]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Your Page Name">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" id="add-social" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                            + Add Social Link
                        </button>
                    </div>
                </div>
                
                <!-- Company Stats Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                            Company Statistics
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div id="stats-container">
                            @if(old('company_stats'))
                                @foreach(old('company_stats') as $index => $stat)
                                    <div class="stat-item p-4 border border-gray-200 rounded-lg mb-4 relative bg-gray-50">
                                        <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-stat">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Font Awesome)</label>
                                                <input type="text" name="company_stats[{{ $index }}][icon]" value="{{ $stat['icon'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="fas fa-users">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Number</label>
                                                <input type="text" name="company_stats[{{ $index }}][number]" value="{{ $stat['number'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="500+">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                                                <input type="text" name="company_stats[{{ $index }}][label]" value="{{ $stat['label'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Happy Clients">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <!-- Default first item -->
                                <div class="stat-item p-4 border border-gray-200 rounded-lg mb-4 relative bg-gray-50">
                                    <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-stat">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Font Awesome)</label>
                                            <input type="text" name="company_stats[0][icon]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="fas fa-users">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Number</label>
                                            <input type="text" name="company_stats[0][number]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="500+">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                                            <input type="text" name="company_stats[0][label]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Happy Clients">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" id="add-stat" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                            + Add Statistic
                        </button>
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
                                        type="button"
                                        id="submitBtn"
                                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                                    >
                                        @if($subtotal > 0)
                                            Lanjutkan Pembayaran
                                        @else
                                            Buat Website Gratis
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
    const accentColor = document.getElementById('accent_color');
    const primaryDisplay = document.getElementById('primary_color_display');
    const secondaryDisplay = document.getElementById('secondary_color_display');
    const accentDisplay = document.getElementById('accent_color_display');
    
    primaryColor.addEventListener('input', function() {
        primaryDisplay.value = this.value;
    });
    
    secondaryColor.addEventListener('input', function() {
        secondaryDisplay.value = this.value;
    });
    
    accentColor.addEventListener('input', function() {
        accentDisplay.value = this.value;
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

    // ============ Services Repeater ============
    let serviceIndex = {{ old('services') ? count(old('services')) : 1 }};
    
    document.getElementById('add-service').addEventListener('click', function() {
        const container = document.getElementById('services-container');
        const newItem = document.createElement('div');
        newItem.className = 'service-item p-4 border border-gray-200 rounded-lg mb-4 relative';
        newItem.innerHTML = `
            <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-service">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Font Awesome)</label>
                    <input type="text" name="services[${serviceIndex}][icon]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="fas fa-chart-line">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link (Anchor)</label>
                    <input type="text" name="services[${serviceIndex}][link]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="#konsultasi-bisnis">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Layanan</label>
                <input type="text" name="services[${serviceIndex}][title]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Konsultasi Bisnis">
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="services[${serviceIndex}][description]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Strategi dan perencanaan bisnis..."></textarea>
            </div>
        `;
        container.appendChild(newItem);
        serviceIndex++;
    });

    // ============ Testimonial Repeater ============
    let testimonialIndex = {{ old('testimonials') ? count(old('testimonials')) : 1 }};

    document.getElementById('add-testimonial').addEventListener('click', function() {
        const container = document.getElementById('testimonials-container');
        const newItem = document.createElement('div');
        newItem.className = 'testimonial-item p-4 border border-gray-200 rounded-lg mb-4 relative bg-gray-50';
        newItem.innerHTML = `
            <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-testimonial">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="testimonials[${testimonialIndex}][name]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Rudi Wijaya">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Posisi / Jabatan</label>
                    <input type="text" name="testimonials[${testimonialIndex}][position]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="CEO, PT Maju Bersama">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rating (1-5)</label>
                <select name="testimonials[${testimonialIndex}][rating]" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="5">5 - Sangat Puas</option>
                    <option value="4">4 - Puas</option>
                    <option value="3">3 - Cukup</option>
                    <option value="2">2 - Kurang</option>
                    <option value="1">1 - Tidak Puas</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Testimoni</label>
                <textarea name="testimonials[${testimonialIndex}][content]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Pelayanan PT Corporate Indonesia sangat profesional..."></textarea>
            </div>
        `;
        container.appendChild(newItem);
        testimonialIndex++;
    });

    // ============ Gallery Repeater ============
    let galleryIndex = {{ old('gallery_photos') ? count(old('gallery_photos')) : 1 }};

    document.getElementById('add-gallery').addEventListener('click', function() {
        const container = document.getElementById('gallery-container');
        const newItem = document.createElement('div');
        newItem.className = 'gallery-item p-4 border border-gray-200 rounded-lg mb-4 relative bg-gray-50';
        newItem.innerHTML = `
            <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-gallery">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Photo URL</label>
                <input type="url" name="gallery_photos[]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="https://images.unsplash.com/photo-...">
            </div>
        `;
        container.appendChild(newItem);
        galleryIndex++;
    });

    // ============ Social Links Repeater ============
    let socialIndex = {{ old('social_links') ? count(old('social_links')) : 1 }};

    if (document.getElementById('add-social')) {
        document.getElementById('add-social').addEventListener('click', function() {
            const container = document.getElementById('social-links-container');
            const newItem = document.createElement('div');
            newItem.className = 'social-item p-4 border border-gray-200 rounded-lg mb-4 relative bg-gray-50';
            newItem.innerHTML = `
                <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-social">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Platform</label>
                            <select name="social_links[${socialIndex}][platform]" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="facebook">Facebook</option>
                                <option value="instagram">Instagram</option>
                                <option value="twitter">Twitter</option>
                                <option value="linkedin">LinkedIn</option>
                                <option value="youtube">YouTube</option>
                                <option value="tiktok">TikTok</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                            <input type="url" name="social_links[${socialIndex}][url]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="https://facebook.com/yourpage">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                            <input type="text" name="social_links[${socialIndex}][label]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Your Page Name">
                        </div>
                    </div>
            `;
            container.appendChild(newItem);
            socialIndex++;
        });
    }

    // ============ Company Stats Repeater ============
    let statsIndex = {{ old('company_stats') ? count(old('company_stats')) : 1 }};

    if (document.getElementById('add-stat')) {
        document.getElementById('add-stat').addEventListener('click', function() {
            const container = document.getElementById('stats-container');
            const newItem = document.createElement('div');
            newItem.className = 'stat-item p-4 border border-gray-200 rounded-lg mb-4 relative bg-gray-50';
            newItem.innerHTML = `
                <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-stat">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Font Awesome)</label>
                            <input type="text" name="company_stats[${statsIndex}][icon]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="fas fa-users">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Number</label>
                            <input type="text" name="company_stats[${statsIndex}][number]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="500+">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                            <input type="text" name="company_stats[${statsIndex}][label]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Happy Clients">
                        </div>
                    </div>
            `;
            container.appendChild(newItem);
            statsIndex++;
        });
    }

    // Handle remove for all repeaters
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-service')) {
            e.target.closest('.service-item').remove();
        }
        if (e.target.closest('.remove-testimonial')) {
            e.target.closest('.testimonial-item').remove();
        }
        if (e.target.closest('.remove-gallery')) {
            e.target.closest('.gallery-item').remove();
        }
        if (e.target.closest('.remove-social')) {
            e.target.closest('.social-item').remove();
        }
        if (e.target.closest('.remove-stat')) {
            e.target.closest('.stat-item').remove();
        }
    });

    // ============ Form Submission Handling ============
    const submitBtn = document.getElementById('submitBtn');
    const confirmationModal = document.getElementById('confirmationModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const confirmBtn = document.getElementById('confirmBtn');
    const form = document.getElementById('checkoutForm');
    
    if (!submitBtn || !confirmationModal || !cancelBtn || !confirmBtn || !form) {
        console.error('Required elements not found:', {
            submitBtn: !!submitBtn,
            confirmationModal: !!confirmationModal,
            cancelBtn: !!cancelBtn,
            confirmBtn: !!confirmBtn,
            form: !!form
        });
        return;
    }

    // Store original button text
    const originalSubmitText = submitBtn.innerHTML;
    const originalConfirmText = confirmBtn.innerHTML;
    
    // Track submission state
    let isSubmitting = false;

    // Show modal when submit button is clicked
    submitBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (isSubmitting) {
            console.log('Form is already being submitted');
            return;
        }
        
        // Basic form validation
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-300');
                field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                isValid = false;
            } else {
                field.classList.remove('border-red-300');
            }
        });
        
        if (!isValid) {
            alert('Mohon lengkapi semua field yang wajib diisi.');
            return;
        }
        
        // Log form data for debugging
        console.log('Form validation passed, showing confirmation modal');
        
        // Refresh CSRF token before showing modal
        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                const csrfInput = document.getElementById('csrf-token');
                if (csrfInput) {
                    csrfInput.value = csrfMeta.getAttribute('content');
                }
            }
        } catch (error) {
            console.error('CSRF token refresh error:', error);
        }
        
        // Show confirmation modal
        confirmationModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    // Hide modal when cancel button is clicked
    cancelBtn.addEventListener('click', function() {
        if (isSubmitting) {
            return; // Don't allow cancel during submission
        }
        confirmationModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    });

    // Submit form when confirm button is clicked
    confirmBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Prevent double submission
        if (isSubmitting || confirmBtn.disabled) {
            console.log('Submission already in progress');
            return;
        }
        
        isSubmitting = true;
        
        // Hide modal immediately
        confirmationModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        // Add loading state
        confirmBtn.innerHTML = 'Processing...';
        confirmBtn.disabled = true;
        submitBtn.innerHTML = 'Processing...';
        submitBtn.disabled = true;
        
        console.log('Starting form submission...');
        
        // Add debug logging
        console.log('Form submission attempt:', {
            timestamp: new Date().toISOString(),
            userId: '{{ Auth::id() }}',
            templateSlug: '{{ $template->slug }}',
            formAction: form.action,
            sessionId: '{{ session()->getId() }}',
            authCheck: '{{ Auth::check() ? "authenticated" : "not_authenticated" }}'
        });
        
        // Ensure form data is properly formatted
        const formData = new FormData(form);
        
        // Log CSRF token for debugging
        const csrfToken = formData.get('_token');
        console.log('Form submission debug info:', {
            csrfTokenPresent: !!csrfToken,
            csrfTokenLength: csrfToken ? csrfToken.length : 0,
            formAction: form.action,
            formMethod: form.method,
            sessionId: formData.get('session_id'),
            timestamp: formData.get('form_timestamp'),
            userAgent: navigator.userAgent
        });
        
        // Validate critical form data
        const companyName = formData.get('company_name');
        const subdomain = formData.get('subdomain');
        const contactEmail = formData.get('contact_email');
        
        if (!companyName || !subdomain || !contactEmail) {
            console.error('Critical form data missing:', {
                companyName: !!companyName,
                subdomain: !!subdomain,
                contactEmail: !!contactEmail
            });
            
            isSubmitting = false;
            confirmBtn.innerHTML = originalConfirmText;
            confirmBtn.disabled = false;
            submitBtn.innerHTML = originalSubmitText;
            submitBtn.disabled = false;
            
            alert('Data form tidak lengkap. Silakan periksa kembali.');
            return;
        }
        
        // Submit the form with error handling
        try {
            console.log('Submitting form directly...');
            
            // Simply submit the form - let the browser handle it naturally
            form.submit();
            
        } catch (error) {
            console.error('Form submission error:', error);
            
            // Reset state on error
            isSubmitting = false;
            confirmBtn.innerHTML = originalConfirmText;
            confirmBtn.disabled = false;
            submitBtn.innerHTML = originalSubmitText;
            submitBtn.disabled = false;
            
            alert('Terjadi kesalahan saat mengirim form. Silakan coba lagi.');
        }
    });

    // Hide modal when clicking outside (only if not submitting)
    confirmationModal.addEventListener('click', function(e) {
        if (e.target === confirmationModal && !isSubmitting) {
            confirmationModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    });
    
    // Handle page unload during submission
    window.addEventListener('beforeunload', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            e.returnValue = 'Form sedang diproses. Yakin ingin meninggalkan halaman?';
            return e.returnValue;
        }
    });
});
</script>

@endsection

<!-- Confirmation Modal -->
<div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            
            <!-- Content -->
            <div class="mt-2 px-7 py-3">
                <h3 class="text-lg font-medium text-gray-900 text-center">
                    Konfirmasi Pembuatan Website
                </h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 text-center">
                        Apakah Anda yakin ingin melanjutkan pembuatan website dengan data yang telah diisi? Pastikan semua informasi sudah benar sebelum melanjutkan.
                    </p>
                </div>
            </div>
            
            <!-- Buttons -->
            <div class="items-center px-4 py-3">
                <div class="flex space-x-3">
                    <button id="cancelBtn" type="button" class="flex-1 px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 transition duration-150 ease-in-out">
                        Batal
                    </button>
                    <button id="confirmBtn" type="button" class="flex-1 px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition duration-150 ease-in-out">
                        Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>