@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen">
    <!-- Hero Section -->
    <div class="bg-primary text-white">
        <div class="py-12">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Dashboard
                </h1>
                <p class="text-xl opacity-90 max-w-2xl mx-auto">
                    Welcome to your dashboard
                </p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success') || session('error') || session('info'))
        <div class="pt-6">
            @if(session('success'))
                <div class="max-w-6xl mx-auto mb-6">
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="material-icons text-green-400">check_circle</span>
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
                                <span class="material-icons text-red-400">error</span>
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
                                <span class="material-icons text-blue-400">info</span>
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
    <div class="py-8">
        <div class="max-w-6xl mx-auto">
            <div class="bg-background-light dark:bg-background-dark rounded-xl shadow-sm border border-border-light dark:border-border-dark p-8">
                <h2 class="text-2xl font-bold text-text-light dark:text-text-dark mb-4">
                    Your Websites
                </h2>
                
                @auth
                    @php
                        $websiteContents = \App\Models\WebsiteContent::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
                    @endphp
                    
                    @if($websiteContents->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($websiteContents as $content)
                                <div class="border border-border-light dark:border-border-dark rounded-lg p-6">
                                    <h3 class="font-bold text-lg text-text-light dark:text-text-dark mb-2">
                                        {{ $content->website_name }}
                                    </h3>
                                    <p class="text-subtext-light dark:text-subtext-dark text-sm mb-4">
                                        Status: <span class="capitalize font-medium">{{ $content->status }}</span>
                                    </p>
                                    <p class="text-subtext-light dark:text-subtext-dark text-sm mb-4">
                                        Subdomain: {{ $content->subdomain }}
                                    </p>
                                    <div class="flex gap-2">
                                        @if($content->status === 'paid')
                                            <a href="#" class="flex-1 bg-primary text-white text-center py-2 px-4 rounded-md text-sm">
                                                View Website
                                            </a>
                                        @else
                                            <span class="flex-1 bg-gray-300 text-gray-600 text-center py-2 px-4 rounded-md text-sm">
                                                Pending Payment
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-subtext-light dark:text-subtext-dark mb-4">
                                You don't have any websites yet.
                            </p>
                            <a href="{{ route('templates.index') }}" class="bg-primary text-white px-6 py-3 rounded-lg font-semibold">
                                Create Your First Website
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection