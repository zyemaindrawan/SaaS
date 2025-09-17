@php
    // Check if testimonials are available
    if (empty($content['testimonials']) || !is_array($content['testimonials'])) {
        return;
    }
    
    // Get testimonials configuration
    $testimonialsTitle = $content['testimonials_title'] ?? 'Testimoni Klien';
    $testimonialsSubtitle = $content['testimonials_subtitle'] ?? 'Lihat apa yang dikatakan klien-klien kami tentang layanan yang kami berikan';
    $testimonials = array_filter($content['testimonials'], fn($t) => !empty($t['content']) && !empty($t['name']));
    $companyName = $content['company_name'] ?? 'Kami';
    
    // Theme colors
    $primaryColor = $content['primary_color'] ?? '#2563eb';
    $secondaryColor = $content['secondary_color'] ?? '#64748b';
    $accentColor = $content['accent_color'] ?? '#f59e0b';
    
    // Testimonials layout options
    $testimonialsLayout = $content['testimonials_layout'] ?? 'slider'; // slider, grid, carousel
    $testimonialsPerSlide = $content['testimonials_per_slide'] ?? 'auto'; // auto, 1, 2, 3
    $showTestimonialRating = $content['show_testimonial_rating'] ?? true;
    $showTestimonialPhoto = $content['show_testimonial_photo'] ?? true;
    $autoplaySlider = $content['autoplay_testimonials'] ?? true;
    $autoplayDelay = $content['testimonials_autoplay_delay'] ?? 5000;
    
    // Animation settings
    $animationStyle = $content['testimonials_animation'] ?? 'fade';
    $showDots = $content['show_testimonials_dots'] ?? true;
    $showArrows = $content['show_testimonials_arrows'] ?? true;
    
    // Determine slides per view based on testimonials count
    $testimonialsCount = count($testimonials);
    $slidesPerView = match($testimonialsPerSlide) {
        '1' => 1,
        '2' => 2,
        '3' => 3,
        default => min($testimonialsCount, 3)
    };
@endphp

<!-- Testimonials Section Styles -->
<style>
    .testimonials-section {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 50%, #f1f5f9 100%);
        position: relative;
        overflow: hidden;
    }

    .testimonials-section::before {
        content: '';
        position: absolute;
        top: -10%;
        left: -10%;
        width: 120%;
        height: 120%;
        background: radial-gradient(ellipse at center, rgba(37, 99, 235, 0.04) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Section Header */
    .testimonials-header {
        text-align: center;
        margin-bottom: 4rem;
        position: relative;
        z-index: 2;
    }

    .testimonials-title {
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 1rem;
        position: relative;
    }

    .testimonials-title::after {
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

    .testimonials-subtitle {
        font-size: 1.125rem;
        color: {{ $secondaryColor }};
        line-height: 1.7;
        margin-top: 1.5rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Slider Container */
    .testimonials-slider {
        position: relative;
        margin: 0 auto;
        padding: 2rem 0;
        overflow: hidden;
    }

    .testimonials-track {
        display: flex;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
    }

    /* Testimonial Card */
    .testimonial-card {
        flex: 0 0 100%;
        padding: 0 1rem;
        box-sizing: border-box;
    }

    @media (min-width: 768px) {
        .testimonial-card {
            flex: 0 0 50%;
        }
    }

    @media (min-width: 1024px) {
        .testimonial-card.slides-1 {
            flex: 0 0 100%;
        }
        .testimonial-card.slides-2 {
            flex: 0 0 50%;
        }
        .testimonial-card.slides-3 {
            flex: 0 0 33.333%;
        }
    }

    .testimonial-content {
        background: white;
        border-radius: 1.5rem;
        padding: 2.5rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        position: relative;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .testimonial-content:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        border-color: {{ $primaryColor }}33;
    }

    .testimonial-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, {{ $primaryColor }}, {{ $accentColor }});
        border-radius: 1.5rem 1.5rem 0 0;
    }

    /* Quote Icon */
    .quote-icon {
        position: absolute;
        top: -15px;
        right: 20px;
        background: {{ $primaryColor }};
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    /* Rating Stars */
    .testimonial-rating {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 1.5rem;
        justify-content: center;
    }

    .star {
        color: #fbbf24;
        font-size: 1.25rem;
    }

    .star.empty {
        color: #e5e7eb;
    }

    /* Testimonial Text */
    .testimonial-text {
        font-size: 1.125rem;
        line-height: 1.7;
        color: #374151;
        margin-bottom: 2rem;
        flex-grow: 1;
        font-style: italic;
        position: relative;
    }

    .testimonial-text::before {
        content: '"';
        font-size: 4rem;
        color: {{ $primaryColor }}33;
        position: absolute;
        top: -1rem;
        left: -0.5rem;
        font-family: Georgia, serif;
        line-height: 1;
    }

    /* Author Info */
    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: auto;
    }

    .author-photo {
        width: 4rem;
        height: 4rem;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid {{ $primaryColor }}33;
        transition: border-color 0.3s ease;
    }

    .testimonial-content:hover .author-photo {
        border-color: {{ $primaryColor }};
    }

    .author-photo-placeholder {
        width: 4rem;
        height: 4rem;
        border-radius: 50%;
        background: linear-gradient(135deg, {{ $primaryColor }}, {{ $accentColor }});
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .author-info h4 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 0.25rem 0;
    }

    .author-info p {
        font-size: 0.875rem;
        color: {{ $secondaryColor }};
        margin: 0;
        opacity: 0.8;
    }

    /* Navigation Arrows */
    .testimonials-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: white;
        border: 2px solid {{ $primaryColor }}33;
        color: {{ $primaryColor }};
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .testimonials-nav:hover {
        background: {{ $primaryColor }};
        color: white;
        transform: translateY(-50%) scale(1.1);
        border-color: {{ $primaryColor }};
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
    }

    .testimonials-nav:active {
        transform: translateY(-50%) scale(0.95);
    }

    .testimonials-nav.disabled {
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
    }

    .testimonials-prev {
        left: -1.75rem;
    }

    .testimonials-next {
        right: -1.75rem;
    }

    /* Dots Pagination */
    .testimonials-dots {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-top: 3rem;
    }

    .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: {{ $primaryColor }}33;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .dot:hover,
    .dot.active {
        background: {{ $primaryColor }};
        transform: scale(1.2);
        border-color: {{ $primaryColor }}66;
    }

    /* Autoplay Pause Button */
    .autoplay-control {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(0, 0, 0, 0.1);
        color: {{ $primaryColor }};
        border: none;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
        z-index: 10;
    }

    .autoplay-control:hover {
        background: rgba(0, 0, 0, 0.2);
        transform: scale(1.1);
    }

    /* Progress Bar */
    .autoplay-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: {{ $primaryColor }};
        transition: width 0.1s linear;
        border-radius: 0 0 1.5rem 1.5rem;
    }

    /* Animation Classes */
    .testimonial-card.animate-in {
        animation: slideInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Company Logo Integration */
    .testimonials-companies {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 2rem;
        margin-top: 4rem;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 1rem;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .company-logo {
        height: 2.5rem;
        opacity: 0.6;
        filter: grayscale(100%);
        transition: all 0.3s ease;
    }

    .company-logo:hover {
        opacity: 1;
        filter: grayscale(0%);
        transform: scale(1.1);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .testimonials-nav {
            display: none;
        }

        .testimonials-slider {
            padding: 1rem 0;
        }
    }

    @media (max-width: 768px) {
        .testimonial-content {
            padding: 2rem;
        }

        .testimonials-header {
            margin-bottom: 2rem;
        }

        .testimonials-title {
            font-size: 1.75rem;
        }

        .testimonials-subtitle {
            font-size: 1rem;
        }

        .testimonial-text {
            font-size: 1rem;
        }

        .testimonials-dots {
            margin-top: 2rem;
        }

        .testimonials-companies {
            gap: 1rem;
        }

        .company-logo {
            height: 2rem;
        }
    }

    @media (max-width: 480px) {
        .testimonial-content {
            padding: 1.5rem;
        }

        .testimonial-card {
            padding: 0 0.5rem;
        }

        .author-photo,
        .author-photo-placeholder {
            width: 3rem;
            height: 3rem;
        }

        .quote-icon {
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }
    }

    /* Print Styles */
    @media print {
        .testimonials-nav,
        .testimonials-dots,
        .autoplay-control {
            display: none !important;
        }

        .testimonial-content {
            break-inside: avoid;
            box-shadow: none;
            border: 1px solid #ddd;
        }

        .testimonials-track {
            display: block;
        }

        .testimonial-card {
            display: block;
            margin-bottom: 2rem;
        }
    }

    /* Accessibility */
    @media (prefers-reduced-motion: reduce) {
        .testimonials-track,
        .testimonial-content,
        .testimonials-nav,
        .dot,
        .autoplay-progress {
            animation: none !important;
            transition: none !important;
        }
    }

    @media (prefers-contrast: high) {
        .testimonial-content {
            border: 2px solid #000;
        }

        .testimonials-nav:focus,
        .dot:focus,
        .autoplay-control:focus {
            outline: 3px solid #000;
            outline-offset: 2px;
        }
    }

    /* Touch indicators for mobile */
    @media (hover: none) and (pointer: coarse) {
        .testimonials-slider::after {
            content: '← Geser untuk melihat testimoni lainnya →';
            position: absolute;
            bottom: -1rem;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.75rem;
            color: {{ $secondaryColor }};
            opacity: 0.7;
        }
    }
</style>

<!-- Testimonials Section -->
<section 
    id="testimonials" 
    class="testimonials-section py-20 relative"
    aria-labelledby="testimonials-title"
>
    <div class="container mx-auto px-6 lg:px-8 relative">
        <!-- Section Header -->
        <div class="testimonials-header">
            <h2 id="testimonials-title" class="testimonials-title">
                {{ $testimonialsTitle }}
            </h2>
            @if($testimonialsSubtitle)
                <p class="testimonials-subtitle">
                    {{ $testimonialsSubtitle }}
                </p>
            @endif
        </div>

        <!-- Testimonials Slider -->
        <div 
            class="testimonials-slider"
            id="testimonials-slider"
            role="region"
            aria-label="Customer testimonials carousel"
        >
            <!-- Autoplay Control -->
            @if($autoplaySlider)
                <button 
                    class="autoplay-control" 
                    id="autoplay-toggle"
                    aria-label="Pause/Play testimonials slideshow"
                    title="Pause slideshow"
                >
                    <i class="fas fa-pause" id="autoplay-icon"></i>
                </button>
            @endif

            <!-- Navigation Arrows -->
            @if($showArrows)
                <button 
                    class="testimonials-nav testimonials-prev" 
                    id="prev-btn"
                    aria-label="Previous testimonial"
                    onclick="previousSlide()"
                >
                    <i class="fas fa-chevron-left" aria-hidden="true"></i>
                </button>
                
                <button 
                    class="testimonials-nav testimonials-next" 
                    id="next-btn"
                    aria-label="Next testimonial"
                    onclick="nextSlide()"
                >
                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                </button>
            @endif

            <!-- Testimonials Track -->
            <div class="testimonials-track" id="testimonials-track">
                @foreach($testimonials as $index => $testimonial)
                    <div class="testimonial-card slides-{{ $slidesPerView }}" data-index="{{ $index }}">
                        <div class="testimonial-content">
                            <!-- Quote Icon -->
                            <div class="quote-icon">
                                <i class="fas fa-quote-right" aria-hidden="true"></i>
                            </div>

                            <!-- Autoplay Progress -->
                            @if($autoplaySlider && $index === 0)
                                <div class="autoplay-progress" id="autoplay-progress"></div>
                            @endif

                            <!-- Rating -->
                            @if($showTestimonialRating && !empty($testimonial['rating']))
                                <div class="testimonial-rating" role="img" aria-label="{{ $testimonial['rating'] }} out of 5 stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star star {{ $i <= ($testimonial['rating'] ?? 5) ? '' : 'empty' }}" aria-hidden="true"></i>
                                    @endfor
                                </div>
                            @endif

                            <!-- Testimonial Text -->
                            <blockquote class="testimonial-text">
                                {{ $testimonial['content'] }}
                            </blockquote>

                            <!-- Author Information -->
                            <div class="testimonial-author">
                                <!-- Author Photo -->
                                @if($showTestimonialPhoto)
                                    @if(!empty($testimonial['photo']))
                                        <img 
                                            src="{{ asset('storage/' . $testimonial['photo']) }}" 
                                            alt="Photo of {{ $testimonial['name'] }}"
                                            class="author-photo"
                                            loading="lazy"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                        >
                                        <div class="author-photo-placeholder" style="display: none;">
                                            {{ strtoupper(substr($testimonial['name'], 0, 1)) }}
                                        </div>
                                    @else
                                        <div class="author-photo-placeholder">
                                            {{ strtoupper(substr($testimonial['name'], 0, 1)) }}
                                        </div>
                                    @endif
                                @endif

                                <!-- Author Info -->
                                <div class="author-info">
                                    <h4>{{ $testimonial['name'] }}</h4>
                                    @if(!empty($testimonial['position']))
                                        <p>{{ $testimonial['position'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Dots Pagination -->
        @if($showDots)
            <div class="testimonials-dots" id="testimonials-dots" role="tablist" aria-label="Testimonial pagination">
                @php
                    $totalSlides = ceil(count($testimonials) / $slidesPerView);
                @endphp
                @for($i = 0; $i < $totalSlides; $i++)
                    <button 
                        class="dot {{ $i === 0 ? 'active' : '' }}" 
                        data-slide="{{ $i }}"
                        role="tab"
                        aria-label="Go to testimonial slide {{ $i + 1 }}"
                        aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                        onclick="goToSlide({{ $i }})"
                    ></button>
                @endfor
            </div>
        @endif

        <!-- Company Logos -->
        @if(!empty($content['testimonial_companies']) && is_array($content['testimonial_companies']))
            <div class="testimonials-companies">
                <h3 style="width: 100%; text-align: center; font-size: 1.25rem; font-weight: 600; color: {{ $secondaryColor }}; margin-bottom: 1rem;">
                    Dipercaya oleh perusahaan terkemuka
                </h3>
                @foreach($content['testimonial_companies'] as $company)
                    @if(!empty($company['logo']))
                        <img 
                            src="{{ asset('storage/' . $company['logo']) }}" 
                            alt="{{ $company['name'] ?? 'Company logo' }}"
                            class="company-logo"
                            loading="lazy"
                        >
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- Testimonials JavaScript -->
<script>
    // Testimonials slider configuration
    const testimonialsConfig = {
        totalTestimonials: {{ count($testimonials) }},
        slidesPerView: {{ $slidesPerView }},
        autoplay: {{ $autoplaySlider ? 'true' : 'false' }},
        autoplayDelay: {{ $autoplayDelay }},
        showDots: {{ $showDots ? 'true' : 'false' }},
        showArrows: {{ $showArrows ? 'true' : 'false' }},
        companyName: '{{ $companyName }}'
    };

    let currentSlide = 0;
    let totalSlides = Math.ceil(testimonialsConfig.totalTestimonials / testimonialsConfig.slidesPerView);
    let autoplayInterval = null;
    let autoplayProgress = 0;
    let isAutoplayPaused = false;
    let touchStartX = 0;
    let touchEndX = 0;

    // Initialize testimonials slider
    document.addEventListener('DOMContentLoaded', function() {
        initializeTestimonialsSlider();
        initializeTouchNavigation();
        initializeKeyboardNavigation();
        trackTestimonialsView();
    });

    function initializeTestimonialsSlider() {
        updateSliderPosition();
        updateNavigationState();
        
        if (testimonialsConfig.autoplay) {
            startAutoplay();
            updateAutoplayProgress();
        }

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.testimonial-card').forEach(card => {
            observer.observe(card);
        });
    }

    function updateSliderPosition() {
        const track = document.getElementById('testimonials-track');
        const translateX = -(currentSlide * (100 / testimonialsConfig.slidesPerView));
        
        track.style.transform = `translateX(${translateX}%)`;
        
        // Update dots
        if (testimonialsConfig.showDots) {
            document.querySelectorAll('.dot').forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
                dot.setAttribute('aria-selected', index === currentSlide ? 'true' : 'false');
            });
        }
    }

    function updateNavigationState() {
        if (testimonialsConfig.showArrows) {
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            
            if (prevBtn) prevBtn.classList.toggle('disabled', currentSlide === 0);
            if (nextBtn) nextBtn.classList.toggle('disabled', currentSlide >= totalSlides - 1);
        }
    }

    function nextSlide() {
        if (currentSlide < totalSlides - 1) {
            currentSlide++;
            updateSliderPosition();
            updateNavigationState();
            resetAutoplay();
            trackSlideNavigation('next', currentSlide);
        }
    }

    function previousSlide() {
        if (currentSlide > 0) {
            currentSlide--;
            updateSliderPosition();
            updateNavigationState();
            resetAutoplay();
            trackSlideNavigation('previous', currentSlide);
        }
    }

    function goToSlide(slideIndex) {
        if (slideIndex >= 0 && slideIndex < totalSlides && slideIndex !== currentSlide) {
            currentSlide = slideIndex;
            updateSliderPosition();
            updateNavigationState();
            resetAutoplay();
            trackSlideNavigation('dot', currentSlide);
        }
    }

    // Autoplay functionality
    function startAutoplay() {
        if (!testimonialsConfig.autoplay || isAutoplayPaused) return;
        
        autoplayInterval = setInterval(() => {
            if (currentSlide >= totalSlides - 1) {
                currentSlide = 0;
            } else {
                currentSlide++;
            }
            updateSliderPosition();
            updateNavigationState();
            trackSlideNavigation('autoplay', currentSlide);
        }, testimonialsConfig.autoplayDelay);
    }

    function stopAutoplay() {
        if (autoplayInterval) {
            clearInterval(autoplayInterval);
            autoplayInterval = null;
        }
    }

    function resetAutoplay() {
        stopAutoplay();
        autoplayProgress = 0;
        updateAutoplayProgress();
        if (!isAutoplayPaused) {
            setTimeout(startAutoplay, 1000); // Resume after 1 second
        }
    }

    function toggleAutoplay() {
        const autoplayIcon = document.getElementById('autoplay-icon');
        const autoplayToggle = document.getElementById('autoplay-toggle');
        
        isAutoplayPaused = !isAutoplayPaused;
        
        if (isAutoplayPaused) {
            stopAutoplay();
            if (autoplayIcon) autoplayIcon.className = 'fas fa-play';
            if (autoplayToggle) autoplayToggle.title = 'Play slideshow';
        } else {
            startAutoplay();
            if (autoplayIcon) autoplayIcon.className = 'fas fa-pause';
            if (autoplayToggle) autoplayToggle.title = 'Pause slideshow';
        }
        
        trackAutoplayToggle(isAutoplayPaused ? 'pause' : 'play');
    }

    function updateAutoplayProgress() {
        if (!testimonialsConfig.autoplay || isAutoplayPaused) return;
        
        const progressBar = document.getElementById('autoplay-progress');
        if (!progressBar) return;
        
        const progressInterval = setInterval(() => {
            if (isAutoplayPaused || !autoplayInterval) {
                clearInterval(progressInterval);
                return;
            }
            
            autoplayProgress += (100 / (testimonialsConfig.autoplayDelay / 100));
            progressBar.style.width = Math.min(autoplayProgress, 100) + '%';
            
            if (autoplayProgress >= 100) {
                autoplayProgress = 0;
                progressBar.style.width = '0%';
                clearInterval(progressInterval);
                setTimeout(updateAutoplayProgress, 100);
            }
        }, 100);
    }

    // Touch navigation
    function initializeTouchNavigation() {
        const slider = document.getElementById('testimonials-slider');
        
        slider.addEventListener('touchstart', handleTouchStart, { passive: true });
        slider.addEventListener('touchend', handleTouchEnd, { passive: true });
    }

    function handleTouchStart(event) {
        touchStartX = event.changedTouches[0].screenX;
    }

    function handleTouchEnd(event) {
        touchEndX = event.changedTouches[0].screenX;
        handleSwipe();
    }

    function handleSwipe() {
        const swipeThreshold = 50;
        const swipeDistance = touchStartX - touchEndX;
        
        if (Math.abs(swipeDistance) > swipeThreshold) {
            if (swipeDistance > 0) {
                // Swiped left - next slide
                nextSlide();
            } else {
                // Swiped right - previous slide
                previousSlide();
            }
        }
    }

    // Keyboard navigation
    function initializeKeyboardNavigation() {
        document.addEventListener('keydown', function(event) {
            const slider = document.getElementById('testimonials-slider');
            if (!slider || !isElementInViewport(slider)) return;
            
            switch (event.key) {
                case 'ArrowLeft':
                    event.preventDefault();
                    previousSlide();
                    break;
                case 'ArrowRight':
                    event.preventDefault();
                    nextSlide();
                    break;
                case ' ':
                case 'Spacebar':
                    event.preventDefault();
                    if (testimonialsConfig.autoplay) {
                        toggleAutoplay();
                    }
                    break;
            }
        });
    }

    function isElementInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    // Event listeners
    document.getElementById('autoplay-toggle')?.addEventListener('click', toggleAutoplay);

    // Pause autoplay on hover (desktop only)
    if (window.innerWidth >= 1024) {
        const slider = document.getElementById('testimonials-slider');
        
        slider.addEventListener('mouseenter', function() {
            if (testimonialsConfig.autoplay && !isAutoplayPaused) {
                stopAutoplay();
            }
        });
        
        slider.addEventListener('mouseleave', function() {
            if (testimonialsConfig.autoplay && !isAutoplayPaused) {
                startAutoplay();
            }
        });
    }

    // Handle window resize
    window.addEventListener('resize', function() {
        // Recalculate slides per view on resize
        const newSlidesPerView = window.innerWidth >= 1024 ? 
            testimonialsConfig.slidesPerView : 
            (window.innerWidth >= 768 ? Math.min(testimonialsConfig.slidesPerView, 2) : 1);
        
        if (newSlidesPerView !== testimonialsConfig.slidesPerView) {
            testimonialsConfig.slidesPerView = newSlidesPerView;
            totalSlides = Math.ceil(testimonialsConfig.totalTestimonials / testimonialsConfig.slidesPerView);
            currentSlide = Math.min(currentSlide, totalSlides - 1);
            updateSliderPosition();
            updateNavigationState();
        }
    });

    // Analytics tracking functions
    function trackTestimonialsView() {
        if (typeof trackEvent !== 'undefined') {
            trackEvent('testimonials_section_viewed', {
                category: 'Testimonials',
                testimonials_count: testimonialsConfig.totalTestimonials,
                slides_per_view: testimonialsConfig.slidesPerView,
                autoplay_enabled: testimonialsConfig.autoplay,
                company_name: testimonialsConfig.companyName
            });
        }
    }

    function trackSlideNavigation(method, slideIndex) {
        if (typeof trackEvent !== 'undefined') {
            trackEvent('testimonial_slide_navigation', {
                category: 'Testimonials',
                navigation_method: method,
                slide_index: slideIndex,
                total_slides: totalSlides,
                company_name: testimonialsConfig.companyName
            });
        }
    }

    function trackAutoplayToggle(action) {
        if (typeof trackEvent !== 'undefined') {
            trackEvent('testimonials_autoplay_toggle', {
                category: 'Testimonials',
                action: action,
                current_slide: currentSlide,
                company_name: testimonialsConfig.companyName
            });
        }
    }

    // Accessibility: Announce slide changes to screen readers
    function announceSlideChange() {
        const announcement = document.createElement('div');
        announcement.setAttribute('aria-live', 'polite');
        announcement.setAttribute('aria-atomic', 'true');
        announcement.className = 'sr-only';
        announcement.textContent = `Showing testimonial ${currentSlide + 1} of ${totalSlides}`;
        
        document.body.appendChild(announcement);
        setTimeout(() => {
            document.body.removeChild(announcement);
        }, 1000);
    }

    // Performance: Pause animations when tab is not visible
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoplay();
        } else if (testimonialsConfig.autoplay && !isAutoplayPaused) {
            startAutoplay();
        }
    });

    // Global functions for manual control
    window.testimonialsSlider = {
        next: nextSlide,
        previous: previousSlide,
        goTo: goToSlide,
        play: () => {
            if (isAutoplayPaused) toggleAutoplay();
        },
        pause: () => {
            if (!isAutoplayPaused) toggleAutoplay();
        }
    };
</script>