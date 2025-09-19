@extends('layouts.app')

@section('title', $template->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto text-center">
                <div class="flex items-center justify-center mb-4">
                    <span class="text-sm font-medium bg-white bg-opacity-20 px-3 py-1 rounded-full">Step 1 of 3</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    {{ $template->name }}
                </h1>
                <p class="text-xl opacity-90 max-w-2xl mx-auto">
                    {{ $template->description ?? 'Professional website template ready to customize for your business' }}
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
                    <a href="{{ route('templates.index') }}" class="text-gray-400 hover:text-gray-500 transition duration-200">
                        Templates
                    </a>
                </li>
                <li>
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
                <li class="text-gray-500 font-medium">
                    {{ $template->name }}
                </li>
            </ol>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column - Template Details -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Template Preview -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                                    <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                                </svg>
                                Template Preview
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="mb-6">
                                <img 
                                    src="{{ $template->preview_image ?? '/images/template-placeholder.jpg' }}" 
                                    alt="{{ $template->name }}"
                                    class="w-full h-80 object-cover rounded-lg shadow-md"
                                >
                            </div>
                            
                            @if($template->demo_url)
                                <div class="text-center">
                                    <a 
                                        href="{{ route('templates.preview', $template->slug) }}" 
                                        target="_blank"
                                        class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white py-3 px-6 rounded-lg transition duration-200 font-medium"
                                    >
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        View Live Preview
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Template Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                About This Template
                            </h3>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            @if($template->description)
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-3">Description</h4>
                                    <p class="text-gray-600 leading-relaxed">
                                        {{ $template->description }}
                                    </p>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h5 class="font-semibold text-gray-900 mb-2">Category</h5>
                                    @if($template->category)
                                        <span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                                            {{ ucwords(str_replace('-', ' ', $template->category)) }}
                                        </span>
                                    @else
                                        <span class="text-gray-500">Uncategorized</span>
                                    @endif
                                </div>

                                <div>
                                    <h5 class="font-semibold text-gray-900 mb-2">Price</h5>
                                    <div class="text-2xl font-bold text-green-600">
                                        @if($template->price > 0)
                                            Rp {{ number_format($template->price, 0, ',', '.') }}
                                        @else
                                            <span class="text-green-600">FREE</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                What's Included
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Responsive design for all devices</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Easy content customization</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span>SEO optimized structure</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span>1 Year hosting included</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span>24/7 customer support</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Fast loading & performance optimized</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span>SSL Certificate included</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Regular updates & maintenance</span>
                                </div>
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
                                    Template Details
                                </h3>
                            </div>
                            
                            <div class="p-6">
                                <!-- Template Summary -->
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
                                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mb-3">
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

                                <!-- Pricing -->
                                <div class="mb-6 text-center">
                                    <div class="text-3xl font-bold text-green-600 mb-2">
                                        @if($template->price > 0)
                                            Rp {{ number_format($template->price, 0, ',', '.') }}
                                        @else
                                            FREE
                                        @endif
                                    </div>
                                    <p class="text-gray-500 text-sm">One-time payment</p>
                                </div>

                                <!-- Order Section -->
                                <div class="space-y-4">
                                    @auth
                                        <a 
                                            href="{{ route('templates.checkout', $template->slug) }}"
                                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-center block"
                                        >
                                            @if($template->price > 0)
                                                Continue to Checkout
                                            @else
                                                Get This Template FREE
                                            @endif
                                        </a>
                                    @else
                                        <div class="text-center space-y-4">
                                            <p class="text-gray-600 text-sm">Please login to order this template</p>
                                            
                                            <div class="space-y-2">
                                                <a 
                                                    href="{{ route('login', ['redirect' => request()->url()]) }}"
                                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg transition duration-200 font-medium block text-center"
                                                >
                                                    Login to Continue
                                                </a>
                                                
                                                <p class="text-xs text-gray-500">
                                                    Don't have an account? 
                                                    <a href="{{ route('register', ['redirect' => request()->url()]) }}" class="text-blue-600 hover:text-blue-500">
                                                        Sign up here
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    @endauth
                                </div>

                                <!-- Quick Stats -->
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <div class="grid grid-cols-2 gap-4 text-center">
                                        <div>
                                            <div class="text-lg font-bold text-gray-900">6 Hours</div>
                                            <div class="text-xs text-gray-500">Activation Time</div>
                                        </div>
                                        <div>
                                            <div class="text-lg font-bold text-gray-900">24/7</div>
                                            <div class="text-xs text-gray-500">Support</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Badge -->
                        <div class="mt-6 text-center">
                            <div class="inline-flex items-center px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-green-700 font-medium">Secure & Trusted</span>
                            </div>
                        </div>

                        <!-- Money Back Guarantee -->
                        @if($template->price > 0)
                            <div class="mt-4 text-center">
                                <div class="inline-flex items-center px-3 py-2 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <svg class="w-4 h-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-xs text-yellow-700 font-medium">30-Day Money Back Guarantee</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Related Templates -->
            @if($relatedTemplates->count() > 0)
                <div class="mt-16">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                </svg>
                                Related Templates
                            </h2>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                @foreach($relatedTemplates as $relatedTemplate)
                                    <div class="bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100">
                                        <div class="relative">
                                            <img 
                                                src="{{ $relatedTemplate->preview_image ?? '/images/template-placeholder.jpg' }}" 
                                                alt="{{ $relatedTemplate->name }}"
                                                class="w-full h-40 object-cover"
                                                loading="lazy"
                                            >
                                            
                                            <div class="absolute top-3 right-3">
                                                <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full font-bold">
                                                    @if($relatedTemplate->price > 0)
                                                        Rp {{ number_format($relatedTemplate->price, 0, ',', '.') }}
                                                    @else
                                                        FREE
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <div class="p-4">
                                            <h3 class="font-bold text-sm text-gray-900 mb-2 line-clamp-1">
                                                {{ $relatedTemplate->name }}
                                            </h3>
                                            
                                            @if($relatedTemplate->category)
                                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mb-3">
                                                    {{ ucwords(str_replace('-', ' ', $relatedTemplate->category)) }}
                                                </span>
                                            @endif
                                            
                                            <div class="flex gap-2">
                                                <a 
                                                    href="{{ route('templates.show', $relatedTemplate->slug) }}"
                                                    class="flex-1 bg-white hover:bg-gray-100 text-gray-700 text-center py-2 px-3 rounded-md transition duration-200 text-xs font-medium border"
                                                >
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection