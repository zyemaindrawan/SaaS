@extends('layouts.app')

@section('title', 'Checkout - ' . $template->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto text-center">
                <div class="flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732L14.146 12.8l-1.179 4.456a1 1 0 01-1.934 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732L9.854 7.2l1.179-4.456A1 1 0 0112 2z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium bg-white bg-opacity-20 px-3 py-1 rounded-full">Step 2 of 3</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Complete Your Order
                </h1>
                <p class="text-xl opacity-90 max-w-2xl mx-auto">
                    Just a few more details and you'll be ready to launch your amazing website!
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

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <form method="POST" action="{{ route('templates.checkout.process', $template->slug) }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf
                
                <!-- Left Column - Form -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Website Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                                    <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                                </svg>
                                Website Information
                            </h3>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <div>
                                <label for="website_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Website Name <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="website_name"
                                    name="website_name" 
                                    value="{{ old('website_name') }}"
                                    placeholder="e.g., My Awesome Business"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('website_name') border-red-300 @enderror"
                                    required
                                >
                                @error('website_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Site Description -->
                            <div>
                                <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Website Description
                                </label>
                                <textarea 
                                    id="site_description"
                                    name="site_description" 
                                    rows="3"
                                    placeholder="Brief description of your website or business"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('site_description') border-red-300 @enderror"
                                >{{ old('site_description') }}</textarea>
                                @error('site_description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Business Type -->
                            <div>
                                <label for="business_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Business Type
                                </label>
                                <select 
                                    id="business_type"
                                    name="business_type"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('business_type') border-red-300 @enderror"
                                >
                                    <option value="">Select business type</option>
                                    <option value="corporate" {{ old('business_type') == 'corporate' ? 'selected' : '' }}>Corporate/Company</option>
                                    <option value="startup" {{ old('business_type') == 'startup' ? 'selected' : '' }}>Startup</option>
                                    <option value="freelancer" {{ old('business_type') == 'freelancer' ? 'selected' : '' }}>Freelancer</option>
                                    <option value="agency" {{ old('business_type') == 'agency' ? 'selected' : '' }}>Agency</option>
                                    <option value="ecommerce" {{ old('business_type') == 'ecommerce' ? 'selected' : '' }}>E-commerce</option>
                                    <option value="blog" {{ old('business_type') == 'blog' ? 'selected' : '' }}>Blog/Magazine</option>
                                    <option value="portfolio" {{ old('business_type') == 'portfolio' ? 'selected' : '' }}>Portfolio</option>
                                    <option value="nonprofit" {{ old('business_type') == 'nonprofit' ? 'selected' : '' }}>Non-profit</option>
                                    <option value="other" {{ old('business_type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('business_type')
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
                                Contact Information
                            </h3>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Contact Name -->
                                <div>
                                    <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Contact Name <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="contact_name"
                                        name="contact_name" 
                                        value="{{ old('contact_name', $user->name) }}"
                                        placeholder="Full name"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_name') border-red-300 @enderror"
                                        required
                                    >
                                    @error('contact_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

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
                                        placeholder="contact@example.com"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_email') border-red-300 @enderror"
                                        required
                                    >
                                    @error('contact_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Contact Phone -->
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number
                                    </label>
                                    <input 
                                        type="tel" 
                                        id="contact_phone"
                                        name="contact_phone" 
                                        value="{{ old('contact_phone', $user->phone) }}"
                                        placeholder="+62 812 3456 7890"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_phone') border-red-300 @enderror"
                                    >
                                    @error('contact_phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Subdomain -->
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
                                            placeholder="mywebsite"
                                            class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('subdomain') border-red-300 @enderror"
                                            pattern="[a-z0-9-]+"
                                            required
                                        >
                                        <span class="inline-flex items-center px-4 py-3 bg-gray-50 border border-l-0 border-gray-300 rounded-r-lg text-sm text-gray-500">
                                            .oursite.com
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">Only lowercase letters, numbers, and hyphens allowed</p>
                                    @error('subdomain')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Contact Address -->
                            <div>
                                <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Address
                                </label>
                                <textarea 
                                    id="contact_address"
                                    name="contact_address" 
                                    rows="2"
                                    placeholder="Complete address"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_address') border-red-300 @enderror"
                                >{{ old('contact_address') }}</textarea>
                                @error('contact_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Design Preferences -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a3 3 0 106 0V4a2 2 0 00-2-2H4zm1 14a1 1 0 100-2 1 1 0 000 2zm5-1.757l4.9-4.9a2 2 0 000-2.828L13.485 5.1a2 2 0 00-2.828 0L10 5.757v8.486zM16 18H9.071l6-6H16a2 2 0 012 2v2a2 2 0 01-2 2z" clip-rule="evenodd" />
                                </svg>
                                Design Preferences
                            </h3>
                        </div>
                        
                        <div class="p-6 space-y-6">
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
                                            value="{{ old('primary_color', '#2563eb') }}"
                                            class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer @error('primary_color') border-red-300 @enderror"
                                            required
                                        >
                                        <div class="flex-1">
                                            <input 
                                                type="text" 
                                                value="{{ old('primary_color', '#2563eb') }}"
                                                placeholder="#2563eb"
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
                                            value="{{ old('secondary_color', '#1e40af') }}"
                                            class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer @error('secondary_color') border-red-300 @enderror"
                                        >
                                        <div class="flex-1">
                                            <input 
                                                type="text" 
                                                value="{{ old('secondary_color', '#1e40af') }}"
                                                placeholder="#1e40af"
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

                            <!-- Color Presets -->
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-3">Or choose from presets:</p>
                                <div class="grid grid-cols-4 gap-3">
                                    <button type="button" class="color-preset flex flex-col items-center p-3 border-2 border-gray-200 rounded-lg hover:border-blue-500 transition" data-primary="#2563eb" data-secondary="#1e40af">
                                        <div class="flex space-x-1 mb-2">
                                            <div class="w-6 h-6 rounded" style="background-color: #2563eb;"></div>
                                            <div class="w-6 h-6 rounded" style="background-color: #1e40af;"></div>
                                        </div>
                                        <span class="text-xs text-gray-600">Blue</span>
                                    </button>
                                    
                                    <button type="button" class="color-preset flex flex-col items-center p-3 border-2 border-gray-200 rounded-lg hover:border-blue-500 transition" data-primary="#059669" data-secondary="#047857">
                                        <div class="flex space-x-1 mb-2">
                                            <div class="w-6 h-6 rounded" style="background-color: #059669;"></div>
                                            <div class="w-6 h-6 rounded" style="background-color: #047857;"></div>
                                        </div>
                                        <span class="text-xs text-gray-600">Green</span>
                                    </button>
                                    
                                    <button type="button" class="color-preset flex flex-col items-center p-3 border-2 border-gray-200 rounded-lg hover:border-blue-500 transition" data-primary="#dc2626" data-secondary="#b91c1c">
                                        <div class="flex space-x-1 mb-2">
                                            <div class="w-6 h-6 rounded" style="background-color: #dc2626;"></div>
                                            <div class="w-6 h-6 rounded" style="background-color: #b91c1c;"></div>
                                        </div>
                                        <span class="text-xs text-gray-600">Red</span>
                                    </button>
                                    
                                    <button type="button" class="color-preset flex flex-col items-center p-3 border-2 border-gray-200 rounded-lg hover:border-blue-500 transition" data-primary="#7c3aed" data-secondary="#6d28d9">
                                        <div class="flex space-x-1 mb-2">
                                            <div class="w-6 h-6 rounded" style="background-color: #7c3aed;"></div>
                                            <div class="w-6 h-6 rounded" style="background-color: #6d28d9;"></div>
                                        </div>
                                        <span class="text-xs text-gray-600">Purple</span>
                                    </button>
                                </div>
                            </div>
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
                                            <span class="text-gray-500">Platform Fee (2.9% + Rp 2.000)</span>
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
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <h5 class="font-semibold text-gray-900 mb-3">What's included:</h5>
                                    <ul class="space-y-2 text-sm">
                                        <li class="flex items-center">
                                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            Responsive Design
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            SEO Optimized
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            1 Year Hosting
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            24/7 Support
                                        </li>
                                    </ul>
                                </div>

                                <!-- Submit Button -->
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <button 
                                        type="submit"
                                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                                    >
                                        @if($subtotal > 0)
                                            Continue to Payment
                                        @else
                                            Create Free Website
                                        @endif
                                    </button>
                                    
                                    <p class="text-center text-xs text-gray-500 mt-2">
                                        @if($subtotal > 0)
                                            You'll be redirected to secure payment
                                        @else
                                            Your website will be created immediately
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
    
    // Color preset handlers
    const colorPresets = document.querySelectorAll('.color-preset');
    colorPresets.forEach(preset => {
        preset.addEventListener('click', function() {
            const primaryValue = this.dataset.primary;
            const secondaryValue = this.dataset.secondary;
            
            primaryColor.value = primaryValue;
            secondaryColor.value = secondaryValue;
            primaryDisplay.value = primaryValue;
            secondaryDisplay.value = secondaryValue;
            
            // Update preset selection
            colorPresets.forEach(p => p.classList.remove('border-blue-500', 'bg-blue-50'));
            this.classList.add('border-blue-500', 'bg-blue-50');
        });
    });
    
    // Subdomain validation
    const subdomainInput = document.getElementById('subdomain');
    subdomainInput.addEventListener('input', function() {
        this.value = this.value.toLowerCase().replace(/[^a-z0-9-]/g, '');
    });
});
</script>
@endsection
