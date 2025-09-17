@php
    // Check if services are available
    if (empty($content['services']) || !is_array($content['services'])) {
        return;
    }
    
    // Get services configuration
    $servicesTitle = $content['services_title'] ?? 'Layanan Kami';
    $servicesSubtitle = $content['services_subtitle'] ?? 'Kami menyediakan berbagai layanan profesional untuk membantu mengembangkan bisnis Anda';
    $services = $content['services'];
    $companyName = $content['company_name'] ?? 'Kami';
    
    // Theme colors
    $primaryColor = $content['primary_color'] ?? '#2563eb';
    $secondaryColor = $content['secondary_color'] ?? '#64748b';
    $accentColor = $content['accent_color'] ?? '#f59e0b';
    
    // Layout options
    $servicesLayout = $content['services_layout'] ?? 'grid'; // grid, carousel, list
    $servicesColumns = $content['services_columns'] ?? 'auto'; // auto, 2, 3, 4
    $showServicesCta = $content['show_services_cta'] ?? true;
    $serviceCtaText = $content['service_cta_text'] ?? 'Pelajari Lebih Lanjut';
    $serviceCtaLink = $content['service_cta_link'] ?? '#contact';
    
    // Animation settings
    $animationStyle = $content['services_animation'] ?? 'fade-up'; // fade-up, slide-in, scale
    $animationDelay = $content['services_animation_delay'] ?? 100; // milliseconds between items
    
    // Determine grid classes based on number of services
    $serviceCount = count($services);
    $gridClass = match($servicesColumns) {
        '2' => 'lg:grid-cols-2',
        '3' => 'lg:grid-cols-3',
        '4' => 'lg:grid-cols-4',
        default => $serviceCount >= 4 ? 'lg:grid-cols-4' : ($serviceCount == 3 ? 'lg:grid-cols-3' : 'lg:grid-cols-2')
    };
@endphp

<!-- Services Section Styles -->
<style>
    .services-section {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        position: relative;
        overflow: hidden;
    }

    .services-section::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -10%;
        width: 120%;
        height: 200%;
        background: radial-gradient(ellipse at center, rgba(37, 99, 235, 0.05) 0%, transparent 70%);
        pointer-events: none;
    }

    .service-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .service-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, {{ $primaryColor }}, {{ $accentColor }});
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: {{ $primaryColor }}33;
    }

    .service-card:hover::before {
        transform: scaleX(1);
    }

    .service-icon {
        width: 4rem;
        height: 4rem;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        position: relative;
        background: {{ $primaryColor }}15;
        color: {{ $primaryColor }};
        transition: all 0.4s ease;
    }

    .service-card:hover .service-icon {
        background: {{ $primaryColor }};
        color: white;
        transform: scale(1.1) rotate(5deg);
    }

    .service-icon::after {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(45deg, {{ $primaryColor }}, {{ $accentColor }});
        border-radius: 1rem;
        z-index: -1;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .service-card:hover .service-icon::after {
        opacity: 1;
    }

    .service-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.75rem;
        transition: color 0.3s ease;
        line-height: 1.4;
    }

    .service-card:hover .service-title {
        color: {{ $primaryColor }};
    }

    .service-description {
        color: {{ $secondaryColor }};
        line-height: 1.6;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
        flex-grow: 1;
    }

    .service-link {
        display: inline-flex;
        align-items: center;
        color: {{ $primaryColor }};
        font-weight: 600;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        margin-top: auto;
    }

    .service-link:hover {
        color: {{ $accentColor }};
        text-decoration: none;
        transform: translateX(4px);
    }

    .service-link i {
        margin-left: 0.5rem;
        transition: transform 0.3s ease;
    }

    .service-link:hover i {
        transform: translateX(4px);
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

    .animate-slide-in {
        opacity: 0;
        transform: translateX(-30px);
    }

    .animate-slide-in.animate-in {
        opacity: 1;
        transform: translateX(0);
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .animate-scale {
        opacity: 0;
        transform: scale(0.9);
    }

    .animate-scale.animate-in {
        opacity: 1;
        transform: scale(1);
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Section Header Animations */
    .section-header {
        text-align: center;
        margin-bottom: 4rem;
        max-width: 3xl;
        margin-left: auto;
        margin-right: auto;
    }

    .section-title {
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 1rem;
        position: relative;
    }

    .section-title::after {
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

    .section-subtitle {
        font-size: 1.125rem;
        color: {{ $secondaryColor }};
        line-height: 1.7;
        margin-top: 1.5rem;
    }

    /* CTA Section */
    .services-cta {
        text-align: center;
        margin-top: 4rem;
        padding: 3rem;
        background: linear-gradient(135deg, {{ $primaryColor }}, {{ $accentColor }});
        border-radius: 1rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .services-cta::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .services-cta-content {
        position: relative;
        z-index: 2;
    }

    .services-cta h3 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .services-cta p {
        font-size: 1.125rem;
        opacity: 0.9;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .services-cta-button {
        display: inline-flex;
        align-items: center;
        background: white;
        color: {{ $primaryColor }};
        padding: 1rem 2rem;
        border-radius: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .services-cta-button:hover {
        color: {{ $primaryColor }};
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .service-card {
            padding: 1.5rem;
        }

        .service-icon {
            width: 3rem;
            height: 3rem;
            font-size: 1.25rem;
        }

        .section-header {
            margin-bottom: 3rem;
        }

        .services-cta {
            padding: 2rem;
            margin-top: 3rem;
        }

        .services-cta h3 {
            font-size: 1.5rem;
        }

        .services-cta p {
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .service-card {
            padding: 1.25rem;
        }

        .section-title {
            font-size: 1.75rem;
        }

        .section-subtitle {
            font-size: 1rem;
        }
    }

    /* Print Styles */
    @media print {
        .service-card {
            break-inside: avoid;
            box-shadow: none;
            border: 1px solid #ddd;
        }

        .services-cta {
            background: #f8f9fa !important;
            color: #333 !important;
        }
    }

    /* High Contrast Mode */
    @media (prefers-contrast: high) {
        .service-card {
            border: 2px solid #333;
        }

        .service-link:focus,
        .services-cta-button:focus {
            outline: 3px solid #333;
            outline-offset: 2px;
        }
    }

    /* Reduced Motion */
    @media (prefers-reduced-motion: reduce) {
        .service-card,
        .service-icon,
        .service-link,
        .animate-fade-up,
        .animate-slide-in,
        .animate-scale {
            animation: none !important;
            transition: none !important;
        }

        .services-cta::before {
            animation: none !important;
        }
    }
</style>

<!-- Services Section -->
<section 
    id="services" 
    class="services-section py-20 relative"
    aria-labelledby="services-title"
>
    <div class="container mx-auto px-6 lg:px-8 relative">
        <!-- Section Header -->
        <div class="section-header animate-fade-up">
            <h2 id="services-title" class="section-title">
                {{ $servicesTitle }}
            </h2>
            @if($servicesSubtitle)
                <p class="section-subtitle">
                    {{ $servicesSubtitle }}
                </p>
            @endif
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 {{ $gridClass }} gap-8 mb-8">
            @foreach($services as $index => $service)
                <div 
                    class="service-card animate-{{ $animationStyle }}"
                    style="animation-delay: {{ $index * $animationDelay }}ms"
                    data-service-index="{{ $index }}"
                >
                    <!-- Service Icon -->
                    <div class="service-icon">
                        @if(!empty($service['icon']))
                            <i class="{{ $service['icon'] }}" aria-hidden="true"></i>
                        @else
                            <i class="fas fa-cog" aria-hidden="true"></i>
                        @endif
                    </div>

                    <!-- Service Content -->
                    <div class="service-content">
                        <h3 class="service-title">
                            {{ $service['title'] ?? 'Layanan ' . ($index + 1) }}
                        </h3>

                        @if(!empty($service['description']))
                            <p class="service-description">
                                {{ $service['description'] }}
                            </p>
                        @endif

                        <!-- Service Link -->
                        @if(!empty($service['link']))
                            <a 
                                href="{{ $service['link'] }}" 
                                class="service-link"
                                onclick="trackServiceClick('{{ $service['title'] ?? 'Service ' . ($index + 1) }}', '{{ $service['link'] }}')"
                                @if(str_starts_with($service['link'], 'http'))
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    aria-label="Pelajari lebih lanjut tentang {{ $service['title'] ?? 'layanan ini' }} (opens in new window)"
                                @else
                                    aria-label="Pelajari lebih lanjut tentang {{ $service['title'] ?? 'layanan ini' }}"
                                @endif
                            >
                                Pelajari Lebih Lanjut
                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Services CTA -->
        @if($showServicesCta)
            <div class="services-cta animate-fade-up" style="animation-delay: {{ count($services) * $animationDelay + 200 }}ms">
                <div class="services-cta-content">
                    <h3>Tertarik dengan Layanan Kami?</h3>
                    <p>
                        Konsultasikan kebutuhan bisnis Anda dengan tim ahli {{ $companyName }}. 
                        Kami siap memberikan solusi terbaik untuk perusahaan Anda.
                    </p>
                    <a 
                        href="{{ $serviceCtaLink }}" 
                        class="services-cta-button"
                        onclick="trackServicesCTA('{{ $serviceCtaText }}', '{{ $serviceCtaLink }}')"
                    >
                        <i class="fas fa-phone mr-2" aria-hidden="true"></i>
                        <span>{{ $serviceCtaText }}</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Services JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize services animations
        initializeServicesAnimations();
        
        // Initialize services interactions
        initializeServicesInteractions();
    });

    function initializeServicesAnimations() {
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

        // Observe all animated elements
        document.querySelectorAll('.animate-fade-up, .animate-slide-in, .animate-scale').forEach(element => {
            observer.observe(element);
        });
    }

    function initializeServicesInteractions() {
        // Add hover analytics tracking
        document.querySelectorAll('.service-card').forEach((card, index) => {
            let hoverStartTime;
            
            card.addEventListener('mouseenter', function() {
                hoverStartTime = Date.now();
                
                // Track hover start
                if (typeof trackEvent !== 'undefined') {
                    trackEvent('service_card_hover', {
                        category: 'Services',
                        service_index: index,
                        service_title: this.querySelector('.service-title')?.textContent || `Service ${index + 1}`
                    });
                }
            });
            
            card.addEventListener('mouseleave', function() {
                if (hoverStartTime) {
                    const hoverDuration = Date.now() - hoverStartTime;
                    
                    // Track hover duration if significant
                    if (hoverDuration > 1000) { // More than 1 second
                        if (typeof trackEvent !== 'undefined') {
                            trackEvent('service_card_engagement', {
                                category: 'Services',
                                service_index: index,
                                hover_duration: hoverDuration,
                                engagement_level: hoverDuration > 3000 ? 'high' : 'medium'
                            });
                        }
                    }
                }
            });
        });

        // Track services section visibility
        const servicesSection = document.getElementById('services');
        if (servicesSection) {
            const sectionObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        if (typeof trackEvent !== 'undefined') {
                            trackEvent('services_section_viewed', {
                                category: 'Section Views',
                                services_count: {{ count($services) }},
                                company_name: '{{ $companyName }}'
                            });
                        }
                        
                        // Unobserve after first view
                        sectionObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            
            sectionObserver.observe(servicesSection);
        }
    }

    // Analytics tracking functions
    function trackServiceClick(serviceTitle, serviceLink) {
        if (typeof trackEvent !== 'undefined') {
            trackEvent('service_link_click', {
                category: 'Services',
                service_title: serviceTitle,
                service_link: serviceLink,
                click_source: 'service_card'
            });
        }

        // Google Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'click', {
                event_category: 'Services',
                event_label: serviceTitle,
                custom_parameter_1: '{{ $companyName }}',
                custom_parameter_2: 'service_interaction'
            });
        }
    }

    function trackServicesCTA(ctaText, ctaLink) {
        if (typeof trackEvent !== 'undefined') {
            trackEvent('services_cta_click', {
                category: 'CTA',
                cta_text: ctaText,
                cta_link: ctaLink,
                cta_location: 'services_section'
            });
        }

        // Google Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'click', {
                event_category: 'CTA',
                event_label: ctaText,
                custom_parameter_1: '{{ $companyName }}',
                custom_parameter_2: 'services_cta'
            });
        }
    }

    // Performance optimization: Lazy load service icons
    function lazyLoadServiceIcons() {
        const serviceIcons = document.querySelectorAll('.service-icon i[data-icon]');
        
        serviceIcons.forEach(icon => {
            const iconClass = icon.dataset.icon;
            if (iconClass) {
                icon.className = iconClass;
                icon.removeAttribute('data-icon');
            }
        });
    }

    // Initialize lazy loading if needed
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', lazyLoadServiceIcons);
    } else {
        lazyLoadServiceIcons();
    }

    // Accessibility: Keyboard navigation for service cards
    document.querySelectorAll('.service-card').forEach(card => {
        card.setAttribute('tabindex', '0');
        
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                const link = this.querySelector('.service-link');
                if (link) {
                    e.preventDefault();
                    link.click();
                }
            }
        });
    });
</script>