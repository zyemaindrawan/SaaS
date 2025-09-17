@php
    // Check if gallery photos are available
    if (empty($content['gallery_photos']) || !is_array($content['gallery_photos'])) {
        return;
    }
    
    // Get gallery configuration
    $galleryTitle = $content['gallery_title'] ?? 'Gallery';
    $gallerySubtitle = $content['gallery_subtitle'] ?? 'Lihat hasil kerja dan proyek-proyek yang telah kami selesaikan';
    $galleryPhotos = array_filter($content['gallery_photos']); // Remove empty values
    $companyName = $content['company_name'] ?? 'Kami';
    
    // Theme colors
    $primaryColor = $content['primary_color'] ?? '#2563eb';
    $secondaryColor = $content['secondary_color'] ?? '#64748b';
    $accentColor = $content['accent_color'] ?? '#f59e0b';
    
    // Gallery layout options
    $galleryLayout = $content['gallery_layout'] ?? 'masonry'; // masonry, grid, carousel
    $galleryColumns = $content['gallery_columns'] ?? 'auto'; // auto, 2, 3, 4
    $showGalleryFilter = $content['show_gallery_filter'] ?? false;
    $galleryCategories = $content['gallery_categories'] ?? [];
    $enableLightbox = $content['enable_lightbox'] ?? true;
    $showImageCaptions = $content['show_image_captions'] ?? true;
    
    // Animation settings
    $animationStyle = $content['gallery_animation'] ?? 'fade-up';
    $animationDelay = $content['gallery_animation_delay'] ?? 100;
    
    // Image optimization settings
    $lazyLoading = $content['gallery_lazy_loading'] ?? true;
    $imageQuality = $content['gallery_image_quality'] ?? 'medium'; // low, medium, high
    
    // Determine grid classes
    $photoCount = count($galleryPhotos);
    $gridClass = match($galleryColumns) {
        '2' => 'lg:columns-2',
        '3' => 'lg:columns-3',
        '4' => 'lg:columns-4',
        default => $photoCount >= 8 ? 'lg:columns-4' : ($photoCount >= 6 ? 'lg:columns-3' : 'lg:columns-2')
    };
@endphp

<!-- Gallery Section Styles -->
<style>
    .gallery-section {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        position: relative;
        overflow: hidden;
    }

    .gallery-section::before {
        content: '';
        position: absolute;
        top: -20%;
        right: -20%;
        width: 40%;
        height: 140%;
        background: radial-gradient(ellipse at center, rgba(37, 99, 235, 0.03) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Section Header */
    .gallery-header {
        text-align: center;
        margin-bottom: 4rem;
        position: relative;
        z-index: 2;
    }

    .gallery-title {
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 1rem;
        position: relative;
    }

    .gallery-title::after {
        content: '';
        position: absolute;
        bottom: -0.5rem;
        left: 50%;
        transform: translateX(-50%);
        width: 4rem;
        height: 4px;
        background: linear-gradient(90deg, {{ $primaryColor }}, {{ $accentColor }});
        border-radius: 2px;
    }

    .gallery-subtitle {
        font-size: 1.125rem;
        color: {{ $secondaryColor }};
        line-height: 1.7;
        margin-top: 1.5rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Gallery Filter */
    .gallery-filter {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 3rem;
    }

    .filter-button {
        padding: 0.75rem 1.5rem;
        border: 2px solid {{ $primaryColor }}33;
        background: transparent;
        color: {{ $primaryColor }};
        border-radius: 2rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .filter-button:hover,
    .filter-button.active {
        background: {{ $primaryColor }};
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    /* Gallery Grid Layouts */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .gallery-masonry {
        columns: 2 280px;
        column-gap: 1.5rem;
        column-fill: balance;
    }

    @media (min-width: 768px) {
        .gallery-masonry.lg\\:columns-3 {
            columns: 3 280px;
        }
        
        .gallery-masonry.lg\\:columns-4 {
            columns: 4 280px;
        }
    }

    /* Gallery Items */
    .gallery-item {
        position: relative;
        margin-bottom: 1.5rem;
        break-inside: avoid;
        border-radius: 1rem;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .gallery-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .gallery-image {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.5s ease;
        object-fit: cover;
    }

    .gallery-item:hover .gallery-image {
        transform: scale(1.05);
    }

    /* Gallery Overlay */
    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, {{ $primaryColor }}ee, {{ $accentColor }}ee);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.4s ease;
        backdrop-filter: blur(2px);
    }

    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }

    .gallery-zoom-icon {
        color: white;
        font-size: 2rem;
        transform: scale(0.8);
        transition: transform 0.3s ease;
    }

    .gallery-item:hover .gallery-zoom-icon {
        transform: scale(1);
    }

    /* Gallery Caption */
    .gallery-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
        color: white;
        padding: 2rem 1.5rem 1.5rem;
        transform: translateY(100%);
        transition: transform 0.4s ease;
    }

    .gallery-item:hover .gallery-caption {
        transform: translateY(0);
    }

    .gallery-caption h4 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .gallery-caption p {
        font-size: 0.875rem;
        opacity: 0.9;
        margin: 0;
    }

    /* Lightbox Modal */
    .lightbox {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        padding: 2rem;
    }

    .lightbox.active {
        opacity: 1;
        visibility: visible;
    }

    .lightbox-content {
        position: relative;
        max-width: 90vw;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .lightbox-image {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
        border-radius: 0.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .lightbox-caption {
        margin-top: 1.5rem;
        text-align: center;
        color: white;
        max-width: 600px;
    }

    .lightbox-caption h4 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .lightbox-caption p {
        font-size: 1rem;
        opacity: 0.9;
        line-height: 1.6;
    }

    .lightbox-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .lightbox-close:hover {
        background: rgba(0, 0, 0, 0.8);
        transform: scale(1.1);
    }

    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .lightbox-nav:hover {
        background: rgba(0, 0, 0, 0.8);
        transform: translateY(-50%) scale(1.1);
    }

    .lightbox-prev {
        left: 2rem;
    }

    .lightbox-next {
        right: 2rem;
    }

    /* Loading Placeholder */
    .gallery-loading {
        width: 100%;
        height: 200px;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 2s infinite;
        border-radius: 1rem;
    }

    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Animation Classes */
    .animate-fade-up {
        opacity: 0;
        transform: translateY(30px);
    }

    .animate-fade-up.animate-in {
        opacity: 1;
        transform: translateY(0);
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Statistics */
    .gallery-stats {
        text-align: center;
        margin-top: 3rem;
        padding: 2rem;
        background: {{ $primaryColor }}11;
        border-radius: 1rem;
        border: 1px solid {{ $primaryColor }}22;
    }

    .gallery-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 2rem;
        margin-top: 1.5rem;
    }

    .gallery-stat-item {
        text-align: center;
    }

    .gallery-stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: {{ $primaryColor }};
        margin-bottom: 0.5rem;
    }

    .gallery-stat-label {
        font-size: 0.875rem;
        color: {{ $secondaryColor }};
        font-weight: 500;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .gallery-masonry {
            columns: 1;
        }

        .gallery-item {
            margin-bottom: 1rem;
        }

        .lightbox {
            padding: 1rem;
        }

        .lightbox-nav {
            width: 2.5rem;
            height: 2.5rem;
            font-size: 1rem;
        }

        .lightbox-prev {
            left: 1rem;
        }

        .lightbox-next {
            right: 1rem;
        }

        .gallery-filter {
            gap: 0.5rem;
        }

        .filter-button {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 480px) {
        .gallery-header {
            margin-bottom: 2rem;
        }

        .gallery-title {
            font-size: 1.75rem;
        }

        .gallery-subtitle {
            font-size: 1rem;
        }

        .lightbox-close {
            top: 0.5rem;
            right: 0.5rem;
            width: 2.5rem;
            height: 2.5rem;
        }
    }

    /* Print Styles */
    @media print {
        .gallery-overlay,
        .lightbox,
        .gallery-filter {
            display: none !important;
        }

        .gallery-item {
            break-inside: avoid;
            box-shadow: none;
            border: 1px solid #ddd;
        }
    }

    /* Accessibility */
    @media (prefers-reduced-motion: reduce) {
        .gallery-item,
        .gallery-image,
        .gallery-overlay,
        .gallery-caption,
        .lightbox,
        .animate-fade-up {
            animation: none !important;
            transition: none !important;
        }
    }

    @media (prefers-contrast: high) {
        .gallery-item {
            border: 2px solid #000;
        }

        .filter-button:focus,
        .lightbox-close:focus,
        .lightbox-nav:focus {
            outline: 3px solid #000;
            outline-offset: 2px;
        }
    }
</style>

<!-- Gallery Section -->
<section 
    id="gallery" 
    class="gallery-section py-20 relative"
    aria-labelledby="gallery-title"
>
    <div class="container mx-auto px-6 lg:px-8 relative">
        <!-- Section Header -->
        <div class="gallery-header animate-fade-up">
            <h2 id="gallery-title" class="gallery-title">
                {{ $galleryTitle }}
            </h2>
            @if($gallerySubtitle)
                <p class="gallery-subtitle">
                    {{ $gallerySubtitle }}
                </p>
            @endif
        </div>

        <!-- Gallery Filter -->
        @if($showGalleryFilter && !empty($galleryCategories))
            <div class="gallery-filter animate-fade-up">
                <button class="filter-button active" data-filter="all">
                    Semua
                </button>
                @foreach($galleryCategories as $category)
                    <button class="filter-button" data-filter="{{ strtolower($category) }}">
                        {{ $category }}
                    </button>
                @endforeach
            </div>
        @endif

        <!-- Gallery Grid -->
        <div 
            class="gallery-container {{ $galleryLayout === 'masonry' ? 'gallery-masonry ' . $gridClass : 'gallery-grid' }}"
            id="gallery-container"
        >
            @foreach($galleryPhotos as $index => $photo)
                <div 
                    class="gallery-item animate-fade-up"
                    style="animation-delay: {{ $index * $animationDelay }}ms"
                    data-category="all {{ $galleryCategories[$index] ?? 'uncategorized' }}"
                    data-index="{{ $index }}"
                    onclick="openLightbox({{ $index }})"
                    role="button"
                    tabindex="0"
                    aria-label="View image {{ $index + 1 }} in lightbox"
                    onkeydown="handleGalleryKeydown(event, {{ $index }})"
                >
                    <!-- Image -->
                    <img 
                        src="{{ $photo }}"
                        alt="Gallery image {{ $index + 1 }}"
                        class="gallery-image"
                        {{ $lazyLoading ? 'loading="lazy"' : '' }}
                        decoding="async"
                        onerror="handleImageError(this, {{ $index }})"
                    >

                    <!-- Overlay -->
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus gallery-zoom-icon" aria-hidden="true"></i>
                    </div>

                    <!-- Caption -->
                    @if($showImageCaptions && !empty($content['gallery_captions'][$index]))
                        <div class="gallery-caption">
                            <h4>{{ $content['gallery_captions'][$index]['title'] ?? 'Image ' . ($index + 1) }}</h4>
                            @if(!empty($content['gallery_captions'][$index]['description']))
                                <p>{{ $content['gallery_captions'][$index]['description'] }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Gallery Statistics -->
        <div class="gallery-stats animate-fade-up" style="animation-delay: {{ count($galleryPhotos) * $animationDelay + 200 }}ms">
            <h3 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;">
                Pencapaian {{ $companyName }}
            </h3>
            <p style="color: {{ $secondaryColor }}; margin-bottom: 1rem;">
                Beberapa angka yang menunjukkan dedikasi dan kualitas kerja kami
            </p>
            
            <div class="gallery-stats-grid">
                <div class="gallery-stat-item">
                    <div class="gallery-stat-number" data-count="{{ count($galleryPhotos) }}">0</div>
                    <div class="gallery-stat-label">Foto Portfolio</div>
                </div>
                <div class="gallery-stat-item">
                    <div class="gallery-stat-number" data-count="{{ $content['completed_projects'] ?? 100 }}">0</div>
                    <div class="gallery-stat-label">Proyek Selesai</div>
                </div>
                <div class="gallery-stat-item">
                    <div class="gallery-stat-number" data-count="{{ $content['satisfied_clients'] ?? 250 }}">0</div>
                    <div class="gallery-stat-label">Klien Puas</div>
                </div>
                <div class="gallery-stat-item">
                    <div class="gallery-stat-number" data-count="{{ $content['years_experience'] ?? 5 }}">0</div>
                    <div class="gallery-stat-label">Tahun Pengalaman</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
@if($enableLightbox)
    <div id="lightbox" class="lightbox" role="dialog" aria-labelledby="lightbox-title" aria-modal="true">
        <div class="lightbox-content">
            <button class="lightbox-close" onclick="closeLightbox()" aria-label="Close lightbox">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
            
            <button class="lightbox-nav lightbox-prev" onclick="previousImage()" aria-label="Previous image">
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
            </button>
            
            <img id="lightbox-image" src="" alt="" class="lightbox-image">
            
            <button class="lightbox-nav lightbox-next" onclick="nextImage()" aria-label="Next image">
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
            </button>
            
            <div class="lightbox-caption" id="lightbox-caption" style="display: none;">
                <h4 id="lightbox-title"></h4>
                <p id="lightbox-description"></p>
            </div>
        </div>
    </div>
@endif

<!-- Gallery JavaScript -->
<script>
    // Gallery configuration
    const galleryConfig = {
        photos: @json($galleryPhotos),
        captions: @json($content['gallery_captions'] ?? []),
        enableLightbox: {{ $enableLightbox ? 'true' : 'false' }},
        lazyLoading: {{ $lazyLoading ? 'true' : 'false' }},
        companyName: '{{ $companyName }}',
        totalPhotos: {{ count($galleryPhotos) }}
    };

    let currentImageIndex = 0;
    let galleryImages = [];
    let animationObserver;

    // Initialize gallery
    document.addEventListener('DOMContentLoaded', function() {
        initializeGallery();
        initializeAnimations();
        initializeFilters();
        animateCounters();
    });

    function initializeGallery() {
        galleryImages = Array.from(document.querySelectorAll('.gallery-item'));
        
        // Track gallery section view
        trackGalleryView();
        
        // Initialize lazy loading if enabled
        if (galleryConfig.lazyLoading && 'IntersectionObserver' in window) {
            initializeLazyLoading();
        }
        
        // Add keyboard navigation
        document.addEventListener('keydown', handleGlobalKeydown);
    }

    function initializeAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        animationObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-fade-up').forEach(element => {
            animationObserver.observe(element);
        });
    }

    function initializeFilters() {
        const filterButtons = document.querySelectorAll('.filter-button');
        const galleryItems = document.querySelectorAll('.gallery-item');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.dataset.filter;
                
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Filter items
                galleryItems.forEach(item => {
                    const categories = item.dataset.category.split(' ');
                    
                    if (filter === 'all' || categories.includes(filter)) {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'translateY(0)';
                        }, 100);
                    } else {
                        item.style.opacity = '0';
                        item.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 300);
                    }
                });
                
                // Track filter usage
                trackGalleryFilter(filter);
            });
        });
    }

    function initializeLazyLoading() {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    
                    // Add loading placeholder
                    const placeholder = document.createElement('div');
                    placeholder.className = 'gallery-loading';
                    img.parentNode.insertBefore(placeholder, img);
                    
                    img.onload = function() {
                        placeholder.remove();
                        img.style.opacity = '1';
                    };
                    
                    observer.unobserve(img);
                }
            });
        });

        document.querySelectorAll('.gallery-image[loading="lazy"]').forEach(img => {
            img.style.opacity = '0';
            imageObserver.observe(img);
        });
    }

    // Lightbox functions
    function openLightbox(index) {
        if (!galleryConfig.enableLightbox) return;
        
        currentImageIndex = index;
        const lightbox = document.getElementById('lightbox');
        const lightboxImage = document.getElementById('lightbox-image');
        const lightboxTitle = document.getElementById('lightbox-title');
        const lightboxDescription = document.getElementById('lightbox-description');
        const lightboxCaption = document.getElementById('lightbox-caption');
        
        // Set image
        lightboxImage.src = galleryConfig.photos[index];
        lightboxImage.alt = 'Gallery image ' + (index + 1);
        
        // Set caption if available
        const caption = galleryConfig.captions[index];
        if (caption && (caption.title || caption.description)) {
            lightboxTitle.textContent = caption.title || 'Image ' + (index + 1);
            lightboxDescription.textContent = caption.description || '';
            lightboxCaption.style.display = 'block';
        } else {
            lightboxCaption.style.display = 'none';
        }
        
        // Show lightbox
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Focus management for accessibility
        lightboxImage.focus();
        
        // Track lightbox open
        trackLightboxAction('open', index);
    }

    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
        
        // Return focus to gallery item
        const galleryItem = document.querySelector(`[data-index="${currentImageIndex}"]`);
        if (galleryItem) galleryItem.focus();
        
        trackLightboxAction('close', currentImageIndex);
    }

    function nextImage() {
        currentImageIndex = (currentImageIndex + 1) % galleryConfig.photos.length;
        openLightbox(currentImageIndex);
        trackLightboxAction('next', currentImageIndex);
    }

    function previousImage() {
        currentImageIndex = (currentImageIndex - 1 + galleryConfig.photos.length) % galleryConfig.photos.length;
        openLightbox(currentImageIndex);
        trackLightboxAction('previous', currentImageIndex);
    }

    // Keyboard navigation
    function handleGlobalKeydown(event) {
        const lightbox = document.getElementById('lightbox');
        if (lightbox && lightbox.classList.contains('active')) {
            switch (event.key) {
                case 'Escape':
                    closeLightbox();
                    break;
                case 'ArrowLeft':
                    previousImage();
                    break;
                case 'ArrowRight':
                    nextImage();
                    break;
            }
        }
    }

    function handleGalleryKeydown(event, index) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            openLightbox(index);
        }
    }

    // Counter animation
    function animateCounters() {
        const counters = document.querySelectorAll('.gallery-stat-number');
        
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = parseInt(counter.dataset.count);
                    const increment = Math.ceil(target / 50);
                    let current = 0;
                    
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            current = target;
                            clearInterval(timer);
                        }
                        counter.textContent = current.toLocaleString();
                    }, 40);
                    
                    counterObserver.unobserve(counter);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => {
            counterObserver.observe(counter);
        });
    }

    // Error handling
    function handleImageError(img, index) {
        img.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xMDAgNzBMMTMwIDEzMEg3MFoiIGZpbGw9IiM5Q0EzQUYiLz4KPGNpcmNsZSBjeD0iMTAwIiBjeT0iMTAwIiByPSI1MCIgc3Ryb2tlPSIjOUNBM0FGIiBzdHJva2Utd2lkdGg9IjIiIGZpbGw9Im5vbmUiLz4KPHN2Zz4=';
        img.alt = 'Image could not be loaded';
        
        trackImageError(index);
    }

    // Analytics functions
    function trackGalleryView() {
        if (typeof trackEvent !== 'undefined') {
            trackEvent('gallery_section_viewed', {
                category: 'Gallery',
                gallery_photos_count: galleryConfig.totalPhotos,
                company_name: galleryConfig.companyName
            });
        }
    }

    function trackGalleryFilter(filter) {
        if (typeof trackEvent !== 'undefined') {
            trackEvent('gallery_filter_used', {
                category: 'Gallery',
                filter_type: filter,
                company_name: galleryConfig.companyName
            });
        }
    }

    function trackLightboxAction(action, index) {
        if (typeof trackEvent !== 'undefined') {
            trackEvent('gallery_lightbox_' + action, {
                category: 'Gallery',
                image_index: index,
                total_images: galleryConfig.totalPhotos,
                company_name: galleryConfig.companyName
            });
        }
    }

    function trackImageError(index) {
        if (typeof trackEvent !== 'undefined') {
            trackEvent('gallery_image_error', {
                category: 'Gallery',
                image_index: index,
                company_name: galleryConfig.companyName
            });
        }
    }

    // Click outside lightbox to close
    document.getElementById('lightbox')?.addEventListener('click', function(event) {
        if (event.target === this) {
            closeLightbox();
        }
    });

    // Performance optimization: Pause/resume animations based on visibility
    document.addEventListener('visibilitychange', function() {
        const gallerySection = document.getElementById('gallery');
        if (document.hidden) {
            // Pause heavy operations
            if (animationObserver) {
                animationObserver.disconnect();
            }
        } else {
            // Resume operations
            if (animationObserver && gallerySection) {
                initializeAnimations();
            }
        }
    });
</script>