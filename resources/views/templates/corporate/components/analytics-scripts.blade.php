@php
    // Get hero configuration from content
    $heroTitle = $content['hero_title'] ?? 'Solusi Bisnis Terbaik';
    $heroSubtitle = $content['hero_subtitle'] ?? 'Kami memberikan layanan terbaik untuk mengembangkan bisnis Anda';
    $heroBackground = $content['hero_background'] ?? null;
    $heroCtaText = $content['hero_cta_text'] ?? 'Pelajari Lebih Lanjut';
    $heroCtaLink = $content['hero_cta_link'] ?? '#services';
    $heroSecondaryCtaText = $content['hero_secondary_cta_text'] ?? '';
    $heroSecondaryCtaLink = $content['hero_secondary_cta_link'] ?? '';
    
    // Colors and styling
    $primaryColor = $content['primary_color'] ?? '#2563eb';
    $accentColor = $content['accent_color'] ?? '#f59e0b';
    $secondaryColor = $content['secondary_color'] ?? '#64748b';
    
    // Hero style options
    $heroStyle = $content['hero_style'] ?? 'gradient'; // gradient, image, video, particles
    $heroHeight = $content['hero_height'] ?? 'fullscreen'; // fullscreen, large, medium, small
    $heroAlignment = $content['hero_alignment'] ?? 'center'; // left, center, right
    $heroOverlay = $content['hero_overlay'] ?? true;
    $heroParallax = $content['hero_parallax'] ?? false;
    
    // Animation settings
    $heroAnimation = $content['hero_animation'] ?? 'fade-up'; // fade-up, slide-left, slide-right, none
    
    // Company info for dynamic content
    $companyName = $content['company_name'] ?? 'Your Company';
    $contactPhone = $content['contact_phone'] ?? '';
    
    // Statistics/numbers to display (optional)
    $heroStats = $content['hero_stats'] ?? [];
    
    // Determine height class
    $heightClass = match($heroHeight) {
        'fullscreen' => 'min-h-screen',
        'large' => 'min-h-[80vh]',
        'medium' => 'min-h-[60vh]',
        'small' => 'min-h-[40vh]',
        default => 'min-h-screen'
    };
    
    // Determine text alignment class
    $alignmentClass = match($heroAlignment) {
        'left' => 'text-left items-start',
        'right' => 'text-right items-end',
        default => 'text-center items-center'
    };
@endphp

<!-- Hero Section Styles -->
<style>
    .hero-section {
        position: relative;
        overflow: hidden;
        background-attachment: {{ $heroParallax ? 'fixed' : 'scroll' }};
    }

    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 0;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    /* Background Styles */
    .hero-gradient {
        background: linear-gradient(135deg, {{ $primaryColor }}, {{ $accentColor }});
    }

    .hero-image {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        @if($heroBackground)
            background-image: url('{{ asset('storage/' . $heroBackground) }}');
        @endif
    }

    /* Overlay Styles */
    .hero-overlay-dark {
        background: rgba(0, 0, 0, 0.5);
    }

    .hero-overlay-gradient {
        background: linear-gradient(
            135deg,
            rgba(37, 99, 235, 0.8) 0%,
            rgba(249, 158, 11, 0.6) 100%
        );
    }

    /* Animation Classes */
    .animate-fade-up {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeUp 0.8s ease-out 0.2s forwards;
    }

    .animate-fade-up-delay {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeUp 0.8s ease-out 0.6s forwards;
    }

    .animate-slide-left {
        opacity: 0;
        transform: translateX(-50px);
        animation: slideLeft 0.8s ease-out 0.4s forwards;
    }

    .animate-slide-right {
        opacity: 0;
        transform: translateX(50px);
        animation: slideRight 0.8s ease-out 0.4s forwards;
    }

    @keyframes fadeUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideLeft {
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideRight {
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Hero Title Styling */
    .hero-title {
        font-size: clamp(2.5rem, 6vw, 4.5rem);
        line-height: 1.1;
        font-weight: 800;
        color: white;
        margin-bottom: 1.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .hero-subtitle {
        font-size: clamp(1.125rem, 3vw, 1.5rem);
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 2.5rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    /* CTA Button Styles */
    .hero-cta-primary {
        display: inline-flex;
        align-items: center;
        padding: 1rem 2rem;
        background: white;
        color: {{ $primaryColor }};
        font-weight: 600;
        font-size: 1.1rem;
        border-radius: 0.75rem;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transform: translateY(0);
    }

    .hero-cta-primary:hover {
        color: {{ $primaryColor }};
        text-decoration: none;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }

    .hero-cta-secondary {
        display: inline-flex;
        align-items: center;
        padding: 1rem 2rem;
        background: transparent;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        border: 2px solid white;
        border-radius: 0.75rem;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-left: 1rem;
    }

    .hero-cta-secondary:hover {
        background: white;
        color: {{ $primaryColor }};
        text-decoration: none;
    }

    /* Stats Styling */
    .hero-stats {
        display: flex;
        justify-content: center;
        gap: 3rem;
        margin-top: 3rem;
        flex-wrap: wrap;
    }

    .hero-stat-item {
        text-align: center;
        color: white;
    }

    .hero-stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0.5rem;
        color: {{ $accentColor }};
    }

    .hero-stat-label {
        font-size: 0.9rem;
        font-weight: 500;
        opacity: 0.9;
    }

    /* Scroll Indicator */
    .scroll-indicator {
        position: absolute;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        color: white;
        opacity: 0.8;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateX(-50%) translateY(0);
        }
        40% {
            transform: translateX(-50%) translateY(-10px);
        }
        60% {
            transform: translateX(-50%) translateY(-5px);
        }
    }

    /* Floating Elements */
    .hero-floating-element {
        position: absolute;
        opacity: 0.1;
        pointer-events: none;
    }

    .hero-floating-1 {
        top: 20%;
        left: 10%;
        width: 100px;
        height: 100px;
        background: white;
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    .hero-floating-2 {
        top: 60%;
        right: 15%;
        width: 150px;
        height: 150px;
        background: {{ $accentColor }};
        border-radius: 50%;
        animation: float 8s ease-in-out infinite reverse;
    }

    .hero-floating-3 {
        bottom: 30%;
        left: 20%;
        width: 80px;
        height: 80px;
        background: white;
        border-radius: 30%;
        animation: float 7s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        50% {
            transform: translateY(-20px) rotate(180deg);
        }
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .hero-subtitle {
            margin-bottom: 2rem;
        }

        .hero-cta-secondary {
            margin-left: 0;
            margin-top: 1rem;
        }

        .hero-stats {
            gap: 2rem;
            margin-top: 2rem;
        }

        .hero-stat-number {
            font-size: 2rem;
        }

        .hero-section {
            background-attachment: scroll;
        }
    }

    @media (max-width: 480px) {
        .hero-cta-primary,
        .hero-cta-secondary {
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
            display: block;
            text-align: center;
            width: 100%;
            max-width: 280px;
        }

        .hero-stats {
            gap: 1.5rem;
            flex-direction: column;
            align-items: center;
        }

        .hero-stat-item {
            min-width: 120px;
        }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .hero-title {
            color: #f9fafb;
        }
        
        .hero-subtitle {
            color: rgba(249, 250, 251, 0.8);
        }
    }

    /* High contrast mode */
    @media (prefers-contrast: high) {
        .hero-title,
        .hero-subtitle {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }

        .hero-cta-primary,
        .hero-cta-secondary {
            border: 3px solid;
        }
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
        .animate-fade-up,
        .animate-fade-up-delay,
        .animate-slide-left,
        .animate-slide-right,
        .scroll-indicator,
        .hero-floating-element {
            animation: none !important;
            opacity: 1 !important;
            transform: none !important;
        }
    }
</style>

<!-- Hero Section -->
<section 
    id="home" 
    class="hero-section {{ $heightClass }} relative flex {{ $alignmentClass }} justify-center"
    role="banner"
    aria-labelledby="hero-title"
>
    <!-- Background -->
    <div class="hero-background">
        @if($heroStyle === 'image' && $heroBackground)
            <div class="hero-image absolute inset-0"></div>
        @elseif($heroStyle === 'gradient' || !$heroBackground)
            <div class="hero-gradient absolute inset-0"></div>
        @endif
        
        <!-- Floating Elements -->
        <div class="hero-floating-element hero-floating-1"></div>
        <div class="hero-floating-element hero-floating-2"></div>
        <div class="hero-floating-element hero-floating-3"></div>
    </div>

    <!-- Overlay -->
    @if($heroOverlay)
        <div class="hero-overlay {{ $heroBackground ? 'hero-overlay-dark' : 'hero-overlay-gradient' }}"></div>
    @endif

    <!-- Content -->
    <div class="hero-content container mx-auto px-6 lg:px-8 flex flex-col {{ $alignmentClass }} justify-center {{ $heightClass }}">
        <div class="max-w-4xl {{ $heroAlignment === 'center' ? 'mx-auto' : '' }}">
            <!-- Title -->
            <h1 
                id="hero-title"
                class="hero-title {{ $heroAnimation === 'fade-up' ? 'animate-fade-up' : ($heroAnimation === 'slide-left' ? 'animate-slide-left' : ($heroAnimation === 'slide-right' ? 'animate-slide-right' : '')) }}"
            >
                {{ $heroTitle }}
            </h1>

            <!-- Subtitle -->
            @if($heroSubtitle)
                <p class="hero-subtitle {{ $heroAnimation === 'fade-up' ? 'animate-fade-up-delay' : ($heroAnimation === 'slide-left' ? 'animate-slide-left' : ($heroAnimation === 'slide-right' ? 'animate-slide-right' : '')) }}">
                    {{ $heroSubtitle }}
                </p>
            @endif

            <!-- Call to Action Buttons -->
            <div class="flex flex-col sm:flex-row {{ $heroAlignment === 'center' ? 'justify-center' : ($heroAlignment === 'right' ? 'justify-end' : 'justify-start') }} gap-4 mb-8 {{ $heroAnimation === 'fade-up' ? 'animate-fade-up-delay' : '' }}">
                <!-- Primary CTA -->
                <a 
                    href="{{ $heroCtaLink }}"
                    class="hero-cta-primary group"
                    onclick="trackHeroCTA('primary', '{{ $heroCtaText }}', '{{ $heroCtaLink }}')"
                    @if(str_starts_with($heroCtaLink, 'http'))
                        target="_blank" rel="noopener noreferrer"
                    @endif
                >
                    <span>{{ $heroCtaText }}</span>
                    <svg class="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>

                <!-- Secondary CTA -->
                @if($heroSecondaryCtaText && $heroSecondaryCtaLink)
                    <a 
                        href="{{ $heroSecondaryCtaLink }}"
                        class="hero-cta-secondary group"
                        onclick="trackHeroCTA('secondary', '{{ $heroSecondaryCtaText }}', '{{ $heroSecondaryCtaLink }}')"
                        @if(str_starts_with($heroSecondaryCtaLink, 'http'))
                            target="_blank" rel="noopener noreferrer"
                        @endif
                    >
                        <span>{{ $heroSecondaryCtaText }}</span>
                        <svg class="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h6m-3-3v6"></path>
                        </svg>
                    </a>
                @elseif($contactPhone)
                    <a 
                        href="tel:{{ $contactPhone }}"
                        class="hero-cta-secondary group"
                        onclick="trackHeroCTA('phone', 'Hubungi Kami', 'tel:{{ $contactPhone }}')"
                    >
                        <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>Hubungi Kami</span>
                    </a>
                @endif
            </div>

            <!-- Hero Statistics -->
            @if(!empty($heroStats) && is_array($heroStats))
                <div class="hero-stats {{ $heroAnimation === 'fade-up' ? 'animate-fade-up-delay' : '' }}">
                    @foreach($heroStats as $stat)
                        @if(!empty($stat['number']) && !empty($stat['label']))
                            <div class="hero-stat-item">
                                <div class="hero-stat-number">{{ $stat['number'] }}</div>
                                <div class="hero-stat-label">{{ $stat['label'] }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            <!-- Trust Indicators -->
            @if(!empty($content['hero_trust_badges']) && is_array($content['hero_trust_badges']))
                <div class="flex justify-center items-center space-x-8 mt-12 opacity-70">
                    @foreach($content['hero_trust_badges'] as $badge)
                        @if(!empty($badge['image']))
                            <img 
                                src="{{ asset('storage/' . $badge['image']) }}" 
                                alt="{{ $badge['alt'] ?? 'Trust badge' }}"
                                class="h-8 md:h-12 grayscale hover:grayscale-0 transition-all duration-300"
                                loading="lazy"
                            >
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Scroll Indicator -->
    @if($heroHeight === 'fullscreen')
        <div class="scroll-indicator">
            <a 
                href="#{{ !empty($content['services']) ? 'services' : (!empty($content['about_content']) ? 'about' : 'contact') }}"
                class="text-white hover:text-{{ $accentColor }} transition-colors"
                aria-label="Scroll to next section"
                onclick="trackScrollIndicator()"
            >
                <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
                <div class="text-sm font-medium">Scroll</div>
            </a>
        </div>
    @endif
</section>

<!-- Hero JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // Observe animated elements
        document.querySelectorAll('[class*="animate-"]').forEach(el => {
            observer.observe(el);
        });

        // Parallax effect for hero background (if enabled)
        @if($heroParallax && !empty($heroBackground))
        let ticking = false;
        
        function updateParallax() {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.hero-background');
            
            parallaxElements.forEach(element => {
                const speed = 0.5;
                element.style.transform = `translateY(${scrolled * speed}px)`;
            });
            
            ticking = false;
        }

        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(updateParallax);
                ticking = true;
            }
        });
        @endif

        // Dynamic text animation (typewriter effect)
        const heroTitle = document.getElementById('hero-title');
        if (heroTitle && {{ !empty($content['hero_typewriter']) ? 'true' : 'false' }}) {
            const originalText = heroTitle.textContent;
            heroTitle.textContent = '';
            
            let i = 0;
            function typeWriter() {
                if (i < originalText.length) {
                    heroTitle.textContent += originalText.charAt(i);
                    i++;
                    setTimeout(typeWriter, 100);
                }
            }
            
            setTimeout(typeWriter, 500);
        }

        // Hero video handling (if video background is used)
        const heroVideo = document.querySelector('.hero-video');
        if (heroVideo) {
            heroVideo.addEventListener('loadedmetadata', function() {
                this.play();
            });
        }
    });

    // Analytics tracking functions
    function trackHeroCTA(type, text, link) {
        if (typeof trackCustomEvent !== 'undefined') {
            trackCustomEvent('hero_cta_click', {
                cta_type: type,
                cta_text: text,
                cta_link: link,
                company_name: '{{ $companyName }}',
                hero_style: '{{ $heroStyle }}'
            });
        }
    }

    function trackScrollIndicator() {
        if (typeof trackCustomEvent !== 'undefined') {
            trackCustomEvent('scroll_indicator_click', {
                company_name: '{{ $companyName }}',
                hero_height: '{{ $heroHeight }}'
            });
        }
    }

    // Enhanced UX: Auto-focus management
    window.addEventListener('load', function() {
        // Focus management for accessibility
        const firstCTA = document.querySelector('.hero-cta-primary');
        if (firstCTA && window.location.hash === '#home') {
            setTimeout(() => {
                firstCTA.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 1000);
        }
    });

    // Performance: Image lazy loading with intersection observer
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    observer.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPageElement",
    "name": "Hero Section",
    "description": "{{ $heroSubtitle }}",
    "url": "{{ url()->current() }}#home",
    "mainEntity": {
        "@type": "Organization",
        "name": "{{ $companyName }}",
        "description": "{{ $heroSubtitle }}"
        @if(!empty($contactPhone))
        ,"telephone": "{{ $contactPhone }}"
        @endif
    }
}
</script>
