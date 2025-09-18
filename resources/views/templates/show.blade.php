@extends('layouts.app')

@section('title', $template->name)

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('templates.index') }}" class="text-gray-400 hover:text-gray-500">
                        Templates
                    </a>
                </li>
                <li>
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
                <li class="text-gray-500">
                    {{ $template->name }}
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Template Preview -->
            <div>
                <div class="sticky top-8">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <img 
                            src="{{ $template->preview_image ?? '/images/template-placeholder.jpg' }}" 
                            alt="{{ $template->name }}"
                            class="w-full h-96 object-cover"
                        >
                        
                        <div class="p-6">
                            @if($template->demo_url)
                                <a 
                                    href="{{ route('templates.preview', $template->slug) }}" 
                                    target="_blank"
                                    class="w-full bg-gray-600 hover:bg-gray-700 text-white text-center py-3 px-6 rounded-md transition duration-200 font-medium block"
                                >
                                    View Live Preview
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Template Details -->
            <div class="space-y-6">
                <!-- Header -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                                {{ $template->name }}
                            </h1>
                            
                            @if($template->category)
                                <span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                                    {{ ucwords(str_replace('-', ' ', $template->category)) }}
                                </span>
                            @endif
                        </div>
                        
                        <div class="text-right">
                            <div class="text-3xl font-bold text-green-600">
                                @if($template->price > 0)
                                    Rp {{ number_format($template->price, 0, ',', '.') }}
                                @else
                                    FREE
                                @endif
                            </div>
                            <p class="text-gray-500 text-sm">One-time payment</p>
                        </div>
                    </div>

                    @if($template->description)
                        <p class="text-gray-600 leading-relaxed">
                            {{ $template->description }}
                        </p>
                    @endif
                </div>

                <!-- Order Section -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">Ready to get started?</h3>
                    
                    @auth
                        <a 
                            href="{{ route('templates.checkout', $template->slug) }}"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 px-6 rounded-md transition duration-200 font-semibold text-lg block text-center"
                        >
                            Order This Template - 
                            @if($template->price > 0)
                                Rp {{ number_format($template->price, 0, ',', '.') }}
                            @else
                                FREE
                            @endif
                        </a>
                        <p class="text-center text-gray-500 text-sm mt-3">
                            You'll be redirected to checkout page
                        </p>
                    @else
                        <div class="text-center space-y-4">
                            <p class="text-gray-600">Please login to order this template</p>
                            
                            <div class="space-x-4">
                                <a 
                                    href="{{ route('login', ['redirect' => request()->url()]) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-md transition duration-200 font-medium inline-block"
                                >
                                    Login
                                </a>
                                
                                <a 
                                    href="{{ route('register', ['redirect' => request()->url()]) }}"
                                    class="bg-gray-600 hover:bg-gray-700 text-white py-3 px-6 rounded-md transition duration-200 font-medium inline-block"
                                >
                                    Register
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>

                <!-- Features Section -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">What's included:</h3>
                    
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Responsive design for all devices
                        </li>
                        
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Easy content customization
                        </li>
                        
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            SEO optimized structure
                        </li>
                        
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            24/7 customer support
                        </li>
                        
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Fast loading & performance optimized
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Related Templates -->
        @if($relatedTemplates->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Related Templates</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedTemplates as $relatedTemplate)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                            <div class="relative">
                                <img 
                                    src="{{ $relatedTemplate->preview_image ?? '/images/template-placeholder.jpg' }}" 
                                    alt="{{ $relatedTemplate->name }}"
                                    class="w-full h-48 object-cover"
                                    loading="lazy"
                                >
                                
                                <div class="absolute top-3 right-3">
                                    <span class="bg-green-600 text-white text-sm px-3 py-1 rounded-full font-bold">
                                        @if($relatedTemplate->price > 0)
                                            Rp {{ number_format($relatedTemplate->price, 0, ',', '.') }}
                                        @else
                                            FREE
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="p-4">
                                <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-1">
                                    {{ $relatedTemplate->name }}
                                </h3>
                                
                                <div class="flex gap-2">
                                    <a 
                                        href="{{ route('templates.show', $relatedTemplate->slug) }}"
                                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 text-center py-2 px-4 rounded-md transition duration-200 text-sm font-medium"
                                    >
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
