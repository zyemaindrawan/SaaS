@extends('layouts.app')

@section('title', 'Website Templates')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="container mx-auto px-4 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Choose Your Perfect Template
                </h1>
                <p class="text-xl opacity-90 max-w-2xl mx-auto">
                    Professional, modern website templates ready to customize for your business
                </p>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Filters & Search -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <form method="GET" action="{{ route('templates.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search templates..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select 
                            name="category" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('-', ' ', $category)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                        <div class="flex gap-2">
                            <input 
                                type="number" 
                                name="price_min" 
                                value="{{ request('price_min') }}"
                                placeholder="Min"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <input 
                                type="number" 
                                name="price_max" 
                                value="{{ request('price_max') }}"
                                placeholder="Max"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select 
                            name="sort" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="sort_order" {{ request('sort') == 'sort_order' ? 'selected' : '' }}>Featured</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button 
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md transition duration-200"
                    >
                        Apply Filters
                    </button>
                    
                    <a 
                        href="{{ route('templates.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition duration-200"
                    >
                        Clear All
                    </a>
                </div>
            </form>
        </div>

        <!-- Results Info -->
        <div class="flex justify-between items-center mb-6">
            <p class="text-gray-600">
                Showing {{ $templates->firstItem() ?? 0 }} - {{ $templates->lastItem() ?? 0 }} of {{ $templates->total() }} templates
            </p>
            
            @if(request()->hasAny(['search', 'category', 'price_min', 'price_max']))
                <div class="flex flex-wrap gap-2">
                    @if(request('search'))
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            Search: "{{ request('search') }}"
                        </span>
                    @endif
                    
                    @if(request('category') && request('category') !== 'all')
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                            Category: {{ ucwords(str_replace('-', ' ', request('category'))) }}
                        </span>
                    @endif
                    
                    @if(request('price_min') || request('price_max'))
                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                            Price: 
                            @if(request('price_min'))
                                Rp {{ number_format(request('price_min'), 0, ',', '.') }}+
                            @endif
                            @if(request('price_min') && request('price_max'))
                                - 
                            @endif
                            @if(request('price_max'))
                                Rp {{ number_format(request('price_max'), 0, ',', '.') }}
                            @endif
                        </span>
                    @endif
                </div>
            @endif
        </div>

        <!-- Templates Grid -->
        @if($templates->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($templates as $template)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden group">
                        <!-- Template Preview -->
                        <div class="relative overflow-hidden">
                            <img 
                                src="{{ $template->preview_image ?? '/images/template-placeholder.jpg' }}" 
                                alt="{{ $template->name }}"
                                class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                loading="lazy"
                            >
                            
                            <!-- Overlay on hover -->
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <div class="space-x-3">
                                    @if($template->demo_url)
                                        <a 
                                            href="{{ route('templates.preview', $template->slug) }}" 
                                            target="_blank"
                                            class="bg-white text-gray-800 px-4 py-2 rounded-md hover:bg-gray-100 transition duration-200 text-sm font-medium"
                                        >
                                            Live Preview
                                        </a>
                                    @endif
                                    
                                    <a 
                                        href="{{ route('templates.show', $template->slug) }}"
                                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200 text-sm font-medium"
                                    >
                                        View Details
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Category Badge -->
                            @if($template->category)
                                <div class="absolute top-3 left-3">
                                    <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full font-medium">
                                        {{ ucwords(str_replace('-', ' ', $template->category)) }}
                                    </span>
                                </div>
                            @endif

                            <!-- Price Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="bg-green-600 text-white text-sm px-3 py-1 rounded-full font-bold">
                                    @if($template->price > 0)
                                        Rp {{ number_format($template->price, 0, ',', '.') }}
                                    @else
                                        FREE
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Template Info -->
                        <div class="p-4">
                            <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-1">
                                {{ $template->name }}
                            </h3>
                            
                            @if($template->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ $template->description }}
                                </p>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <a 
                                    href="{{ route('templates.show', $template->slug) }}"
                                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 text-center py-2 px-4 rounded-md transition duration-200 text-sm font-medium"
                                >
                                    Details
                                </a>
                                
                                @auth
                                    <a 
                                        href="{{ route('templates.checkout', $template->slug) }}"
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md transition duration-200 text-sm font-medium"
                                    >
                                        Order Now
                                    </a>
                                @else
                                    <a 
                                        href="{{ route('login', ['redirect' => route('templates.checkout', $template->slug)]) }}"
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md transition duration-200 text-sm font-medium"
                                    >
                                        Login to Order
                                    </a>
                                @endauth

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $templates->links('pagination::tailwind') }}
            </div>
        @else
            <!-- No Templates Found -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A9.971 9.971 0 0124 24c4.21 0 7.813 2.602 9.288 6.286" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
                    
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No templates found</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Try adjusting your search filters to find what you're looking for.
                    </p>
                    
                    <div class="mt-6">
                        <a 
                            href="{{ route('templates.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                        >
                            View All Templates
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush
