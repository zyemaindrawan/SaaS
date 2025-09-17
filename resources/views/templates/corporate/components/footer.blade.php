@php
    // Get footer configuration from content
    $companyName = $content['company_name'] ?? 'Your Company';
    $companyTagline = $content['company_tagline'] ?? '';
    $companyLogo = $content['company_logo'] ?? null;
    $heroSubtitle = $content['hero_subtitle'] ?? '';
    
    // Contact information
    $contactTitle = $content['contact_title'] ?? 'Hubungi Kami';
    $contactEmail = $content['contact_email'] ?? '';
    $contactPhone = $content['contact_phone'] ?? '';
    $contactAddress = $content['contact_address'] ?? '';
    $contactWhatsApp = $content['whatsapp_number'] ?? '';
    
    // Social media links
    $socialLinks = $content['social_links'] ?? [];
    
    // Colors and styling
    $primaryColor = $content['primary_color'] ?? '#2563eb';
    $secondaryColor = $content['secondary_color'] ?? '#64748b';
    $accentColor = $content['accent_color'] ?? '#f59e0b';
    
    // Footer style and layout
    $footerStyle = $content['footer_style'] ?? 'dark'; // dark, light, colored
    $footerLayout = $content['footer_layout'] ?? '4-column'; // 2-column, 3-column, 4-column
    $showNewsletter = $content['show_newsletter_signup'] ?? true;
    $newsletterTitle = $content['newsletter_title'] ?? 'Berlangganan Newsletter';
    $newsletterSubtitle = $content['newsletter_subtitle'] ?? 'Dapatkan update terbaru dan penawaran khusus';
    
    // Footer sections configuration
    $footerSections = [
        'company' => [
            'title' => 'Perusahaan',
            'show' => true,
            'links' => []
        ],
        'services' => [
            'title' => 'Layanan',
            'show' => !empty($content['services']),
            'links' => []
        ],
        'resources' => [
            'title' => 'Sumber Daya',
            'show' => true,
            'links' => []
        ],
        'legal' => [
            'title' => 'Legal',
            'show' => true,
            'links' => []
        ]
    ];
    
    // Build dynamic footer links based on available content
    if (!empty($content['about_content'])) {
        $footerSections['company']['links'][] = ['label' => 'Tentang Kami', 'href' => '#about'];
    }
    
    if (!empty($content['services']) && is_array($content['services'])) {
        foreach (array_slice($content['services'], 0, 5) as $service) {
            if (!empty($service['title'])) {
                $footerSections['services']['links'][] = [
                    'label' => $service['title'],
                    'href' => $service['link'] ?? '#services'
                ];
            }
        }
    }
    
    if (!empty($content['gallery_photos'])) {
        $footerSections['resources']['links'][] = ['label' => 'Gallery', 'href' => '#gallery'];
    }
    
    if (!empty($content['testimonials'])) {
        $footerSections['resources']['links'][] = ['label' => 'Testimoni', 'href' => '#testimonials'];
    }
    
    // Custom footer links if provided
    if (!empty($content['custom_footer_links']) && is_array($content['custom_footer_links'])) {
        foreach ($content['custom_footer_links'] as $customLink) {
            if (!empty($customLink['title']) && !empty($customLink['links']) && is_array($customLink['links'])) {
                $footerSections[strtolower(str_replace(' ', '_', $customLink['title']))] = [
                    'title' => $customLink['title'],
                    'show' => true,
                    'links' => $customLink['links']
                ];
            }
        }
    }
    
    // Default legal links
    $footerSections['legal']['links'] = [
        ['label' => 'Kebijakan Privasi', 'href' => '#privacy-policy'],
        ['label' => 'Syarat & Ketentuan', 'href' => '#terms-of-service'],
        ['label' => 'Sitemap', 'href' => '/sitemap.xml']
    ];
    
    // Determine footer classes based on style
    $footerClasses = match($footerStyle) {
        'light' => 'bg-gray-50 text-gray-800',
        'colored' => 'text-white',
        default => 'bg-gray-900 text-white'
    };
    
    $footerBgStyle = $footerStyle === 'colored' ? "background: linear-gradient(135deg, {$primaryColor}, {$accentColor});" : '';
@endphp

<!-- Footer Styles -->
<style>
    .footer {
        {{ $footerBgStyle }}
    }
    
    .footer-link {
        transition: all 0.3s ease;
        position: relative;
    }
    
    .footer-link:hover {
        color: {{ $footerStyle === 'dark' || $footerStyle === 'colored' ? '#ffffff' : $primaryColor }};
        text-decoration: none;
        transform: translateX(4px);
    }
    
    .footer-link::before {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: {{ $primaryColor }};
        transition: width 0.3s ease;
    }
    
    .footer-link:hover::before {
        width: 100%;
    }
    
    .footer-social-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: {{ $footerStyle === 'light' ? 'rgba(37, 99, 235, 0.1)' : 'rgba(255, 255, 255, 0.1)' }};
        color: {{ $footerStyle === 'light' ? $primaryColor : 'white' }};
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
    }
    
    .footer-social-link:hover {
        background: {{ $primaryColor }};
        color: white;
        text-decoration: none;
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    
    .newsletter-form {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .newsletter-input {
        flex: 1;
        padding: 0.75rem 1rem;
        border: 2px solid {{ $footerStyle === 'light' ? '#e5e7eb' : 'rgba(255, 255, 255, 0.2)' }};
        border-radius: 0.5rem;
        background: {{ $footerStyle === 'light' ? 'white' : 'rgba(255, 255, 255, 0.1)' }};
        color: {{ $footerStyle === 'light' ? '#1f2937' : 'white' }};
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .newsletter-input:focus {
        outline: none;
        border-color: {{ $primaryColor }};
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    
    .newsletter-input::placeholder {
        color: {{ $footerStyle === 'light' ? '#9ca3af' : 'rgba(255, 255, 255, 0.6)' }};
    }
    
    .newsletter-button {
        padding: 0.75rem 1.5rem;
        background: {{ $primaryColor }};
        color: white;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }
    
    .newsletter-button:hover {
        background: {{ $accentColor }};
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    
    .footer-divider {
        border-color: {{ $footerStyle === 'light' ? '#e5e7eb' : 'rgba(255, 255, 255, 0.1)' }};
    }
    
    /* Responsive Grid System */
    .footer-grid {
        display: grid;
        gap: 2rem;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
    
    @media (min-width: 768px) {
        .footer-grid.grid-4-column {
            grid-template-columns: 2fr 1fr 1fr 1fr;
        }
        
        .footer-grid.grid-3-column {
            grid-template-columns: 2fr 1fr 1fr;
        }
        
        .footer-grid.grid-2-column {
            grid-template-columns: 1fr 1fr;
        }
    }
    
    @media (max-width: 767px) {
        .newsletter-form {
            flex-direction: column;
        }
        
        .footer-social-link {
            width: 40px;
            height: 40px;
        }
    }
    
    /* Back to top button */
    .back-to-top {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 50px;
        height: 50px;
        background: {{ $primaryColor }};
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .back-to-top.visible {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    .back-to-top:hover {
        background: {{ $accentColor }};
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }
    
    /* Trust badges */
    .trust-badge {
        opacity: 0.7;
        transition: opacity 0.3s ease;
        filter: grayscale(100%);
    }
    
    .trust-badge:hover {
        opacity: 1;
        filter: grayscale(0%);
    }
    
    /* Print styles */
    @media print {
        .footer {
            background: white !important;
            color: black !important;
            box-shadow: none !important;
        }
        
        .footer-social-link,
        .newsletter-form,
        .back-to-top {
            display: none !important;
        }
    }
</style>

<!-- Footer Component -->
<footer 
    id="contact" 
    class="footer {{ $footerClasses }} py-16"
    role="contentinfo"
    aria-label="Site footer"
>
    <div class="container mx-auto px-6 lg:px-8">
        <!-- Main Footer Content -->
        <div class="footer-grid grid-{{ str_replace('-column', '', $footerLayout) }}">
            <!-- Company Information -->
            <div class="space-y-6">
                <!-- Logo and Company Name -->
                <div class="flex items-center space-x-3">
                    @if($companyLogo)
                        <img 
                            src="{{ asset('storage/' . $companyLogo) }}" 
                            alt="{{ $companyName }} Logo"
                            class="h-10 w-auto"
                            loading="lazy"
                        >
                    @endif
                    <div>
                        <h3 class="text-2xl font-bold">{{ $companyName }}</h3>
                        @if($companyTagline)
                            <p class="text-sm opacity-80 mt-1">{{ $companyTagline }}</p>
                        @endif
                    </div>
                </div>

                <!-- Company Description -->
                @if($heroSubtitle)
                    <p class="text-sm opacity-90 leading-relaxed max-w-md">
                        {{ $heroSubtitle }}
                    </p>
                @endif

                <!-- Contact Information -->
                <div class="space-y-3">
                    @if($contactAddress)
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt text-{{ $footerStyle === 'light' ? 'blue-600' : 'white' }} mt-1"></i>
                            <address class="text-sm opacity-90 not-italic leading-relaxed">
                                {{ $contactAddress }}
                            </address>
                        </div>
                    @endif

                    @if($contactPhone)
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone text-{{ $footerStyle === 'light' ? 'blue-600' : 'white' }}"></i>
                            <a 
                                href="tel:{{ $contactPhone }}" 
                                class="text-sm opacity-90 hover:opacity-100 transition-opacity"
                                onclick="trackFooterContact('phone', '{{ $contactPhone }}')"
                            >
                                {{ $contactPhone }}
                            </a>
                        </div>
                    @endif

                    @if($contactEmail)
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-{{ $footerStyle === 'light' ? 'blue-600' : 'white' }}"></i>
                            <a 
                                href="mailto:{{ $contactEmail }}" 
                                class="text-sm opacity-90 hover:opacity-100 transition-opacity"
                                onclick="trackFooterContact('email', '{{ $contactEmail }}')"
                            >
                                {{ $contactEmail }}
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Social Media Links -->
                @if(!empty($socialLinks))
                    <div class="space-y-3">
                        <h4 class="font-semibold text-sm uppercase tracking-wide opacity-80">
                            Ikuti Kami
                        </h4>
                        <div class="flex space-x-3">
                            @foreach($socialLinks as $social)
                                @if(!empty($social['url']) && !empty($social['platform']))
                                    <a 
                                        href="{{ $social['url'] }}" 
                                        target="_blank" 
                                        rel="noopener noreferrer"
                                        class="footer-social-link"
                                        aria-label="{{ ucfirst($social['platform']) }}"
                                        onclick="trackFooterSocial('{{ $social['platform'] }}', '{{ $social['url'] }}')"
                                    >
                                        @switch($social['platform'])
                                            @case('facebook')
                                                <i class="fab fa-facebook-f"></i>
                                                @break
                                            @case('instagram')
                                                <i class="fab fa-instagram"></i>
                                                @break
                                            @case('twitter')
                                                <i class="fab fa-twitter"></i>
                                                @break
                                            @case('linkedin')
                                                <i class="fab fa-linkedin-in"></i>
                                                @break
                                            @case('youtube')
                                                <i class="fab fa-youtube"></i>
                                                @break
                                            @case('tiktok')
                                                <i class="fab fa-tiktok"></i>
                                                @break
                                            @case('whatsapp')
                                                <i class="fab fa-whatsapp"></i>
                                                @break
                                            @case('telegram')
                                                <i class="fab fa-telegram"></i>
                                                @break
                                            @default
                                                <i class="fas fa-globe"></i>
                                        @endswitch
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Dynamic Footer Sections -->
            @foreach($footerSections as $sectionKey => $section)
                @if($section['show'] && !empty($section['links']))
                    <div class="space-y-4">
                        <h4 class="font-semibold text-lg mb-4">{{ $section['title'] }}</h4>
                        <ul class="space-y-2">
                            @foreach($section['links'] as $link)
                                <li>
                                    <a 
                                        href="{{ $link['href'] }}" 
                                        class="footer-link text-sm opacity-80 hover:opacity-100 block py-1"
                                        onclick="trackFooterLink('{{ $sectionKey }}', '{{ $link['label'] }}', '{{ $link['href'] }}')"
                                        @if(str_starts_with($link['href'], 'http'))
                                            target="_blank" rel="noopener noreferrer"
                                        @endif
                                    >
                                        {{ $link['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endforeach

            <!-- Newsletter Signup -->
            @if($showNewsletter)
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold text-lg mb-2">{{ $newsletterTitle }}</h4>
                        <p class="text-sm opacity-80 mb-4">{{ $newsletterSubtitle }}</p>
                    </div>
                    
                    <form 
                        class="newsletter-form" 
                        onsubmit="handleNewsletterSubmit(event)"
                        aria-label="Newsletter subscription form"
                    >
                        <input 
                            type="email" 
                            class="newsletter-input"
                            placeholder="Masukkan email Anda"
                            required
                            aria-label="Email address for newsletter"
                        >
                        <button 
                            type="submit" 
                            class="newsletter-button"
                            aria-label="Subscribe to newsletter"
                        >
                            <i class="fas fa-paper-plane mr-2"></i>
                            <span class="hidden sm:inline">Berlangganan</span>
                        </button>
                    </form>
                    
                    <p class="text-xs opacity-60">
                        Dengan berlangganan, Anda menyetujui 
                        <a href="#privacy-policy" class="underline hover:no-underline">
                            kebijakan privasi
                        </a> kami.
                    </p>
                </div>
            @endif
        </div>

        <!-- Trust Badges / Certifications (Optional) -->
        @if(!empty($content['trust_badges']) && is_array($content['trust_badges']))
            <div class="mt-12 pt-8 border-t footer-divider">
                <div class="flex flex-wrap justify-center items-center space-x-8 space-y-4">
                    @foreach($content['trust_badges'] as $badge)
                        @if(!empty($badge['image']))
                            <img 
                                src="{{ asset('storage/' . $badge['image']) }}" 
                                alt="{{ $badge['alt'] ?? 'Trust badge' }}"
                                class="trust-badge h-12 md:h-16"
                                loading="lazy"
                            >
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Footer Bottom -->
        <div class="mt-12 pt-8 border-t footer-divider">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <!-- Copyright -->
                <div class="text-center md:text-left">
                    <p class="text-sm opacity-80">
                        &copy; {{ date('Y') }} {{ $companyName }}. 
                        <span class="hidden sm:inline">Semua hak dilindungi.</span>
                    </p>
                    @if($preview_mode ?? false)
                        <p class="text-xs opacity-60 mt-1">
                            <i class="fas fa-eye mr-1"></i>
                            Mode Preview - Website belum dipublikasikan
                        </p>
                    @endif
                </div>

                <!-- Additional Footer Links -->
                <div class="flex flex-wrap justify-center space-x-6 text-sm opacity-80">
                    <a href="#privacy-policy" class="footer-link hover:opacity-100">
                        Privasi
                    </a>
                    <a href="#terms-of-service" class="footer-link hover:opacity-100">
                        Syarat
                    </a>
                    <a href="/sitemap.xml" class="footer-link hover:opacity-100">
                        Sitemap
                    </a>
                </div>

                <!-- Powered by (Optional - for branding) -->
                @if($preview_mode ?? false)
                    <div class="text-xs opacity-60">
                        <span>Powered by </span>
                        <a href="#" class="font-semibold hover:opacity-80">
                            SaaS Builder
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button 
    id="backToTop"
    class="back-to-top"
    aria-label="Back to top"
    onclick="scrollToTop()"
>
    <i class="fas fa-chevron-up"></i>
</button>

<!-- Footer JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Back to top functionality
        const backToTopButton = document.getElementById('backToTop');
        
        function toggleBackToTop() {
            if (window.scrollY > 300) {
                backToTopButton.classList.add('visible');
            } else {
                backToTopButton.classList.remove('visible');
            }
        }
        
        // Throttle scroll events
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(function() {
                    toggleBackToTop();
                    ticking = false;
                });
                ticking = true;
            }
        });
        
        // Smooth scroll to top
        window.scrollToTop = function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
            
            // Track back to top usage
            if (typeof trackEvent !== 'undefined') {
                trackEvent('back_to_top_click', {
                    category: 'Navigation',
                    scroll_position: window.scrollY
                });
            }
        };
        
        // Newsletter form handling
        window.handleNewsletterSubmit = function(event) {
            event.preventDefault();
            
            const form = event.target;
            const emailInput = form.querySelector('input[type="email"]');
            const submitButton = form.querySelector('button[type="submit"]');
            const email = emailInput.value.trim();
            
            if (!email) return;
            
            // Show loading state
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            submitButton.disabled = true;
            
            // Track newsletter signup attempt
            if (typeof trackEvent !== 'undefined') {
                trackEvent('newsletter_signup', {
                    category: 'Lead Generation',
                    email_domain: email.split('@')[1] || 'unknown'
                });
            }
            
            // Simulate API call (replace with actual endpoint)
            setTimeout(function() {
                // Show success message
                form.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                        <p class="text-sm">Terima kasih! Kami akan mengirim update terbaru ke email Anda.</p>
                    </div>
                `;
                
                // Track successful signup
                if (typeof trackEvent !== 'undefined') {
                    trackEvent('newsletter_signup_success', {
                        category: 'Lead Generation'
                    });
                }
            }, 2000);
            
            // In production, replace with actual API call:
            /*
            fetch('/newsletter-signup', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                // Handle success/error
            })
            .catch(error => {
                // Handle error
            });
            */
        };
        
        // Footer link tracking
        window.trackFooterLink = function(section, label, href) {
            if (typeof trackEvent !== 'undefined') {
                trackEvent('footer_link_click', {
                    category: 'Footer Navigation',
                    section: section,
                    link_label: label,
                    link_href: href
                });
            }
        };
        
        // Social media tracking
        window.trackFooterSocial = function(platform, url) {
            if (typeof trackEvent !== 'undefined') {
                trackEvent('footer_social_click', {
                    category: 'Social Media',
                    platform: platform,
                    url: url
                });
            }
        };
        
        // Contact tracking
        window.trackFooterContact = function(type, value) {
            if (typeof trackEvent !== 'undefined') {
                trackEvent('footer_contact_click', {
                    category: 'Contact',
                    contact_type: type,
                    contact_value: value
                });
            }
        };
        
        // Intersection Observer for footer visibility
        const footerObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (typeof trackEvent !== 'undefined') {
                        trackEvent('footer_viewed', {
                            category: 'Engagement',
                            company_name: '{{ $companyName }}'
                        });
                    }
                }
            });
        }, { threshold: 0.5 });
        
        const footer = document.querySelector('footer');
        if (footer) {
            footerObserver.observe(footer);
        }
    });
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "{{ $companyName }}",
    @if($companyLogo)
    "logo": "{{ asset('storage/' . $companyLogo) }}",
    @endif
    @if($heroSubtitle)
    "description": "{{ $heroSubtitle }}",
    @endif
    @if($contactEmail || $contactPhone || $contactAddress)
    "contactPoint": {
        "@type": "ContactPoint",
        @if($contactEmail)
        "email": "{{ $contactEmail }}",
        @endif
        @if($contactPhone)
        "telephone": "{{ $contactPhone }}",
        @endif
        @if($contactAddress)
        "address": "{{ $contactAddress }}",
        @endif
        "contactType": "customer support"
    },
    @endif
    @if(!empty($socialLinks))
    "sameAs": [
        @foreach($socialLinks as $index => $social)
            @if(!empty($social['url']))
                "{{ $social['url'] }}"{{ $index < count($socialLinks) - 1 ? ',' : '' }}
            @endif
        @endforeach
    ],
    @endif
    "url": "{{ url('/') }}"
}
</script>
