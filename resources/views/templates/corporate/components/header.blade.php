@php
    // Get header configuration from content
    $companyName = $content['company_name'] ?? 'Your Company';
    $companyLogo = $content['company_logo'] ?? null;
    $primaryColor = $content['primary_color'] ?? '#2563eb';
    $secondaryColor = $content['secondary_color'] ?? '#64748b';
    $headerStyle = $content['header_style'] ?? 'default'; // default, transparent, colored
    $stickyHeader = $content['sticky_header'] ?? true;
    
    // Navigation items based on available content sections
    $navItems = [];
    
    // Always include Home
    $navItems[] = ['label' => 'Beranda', 'href' => '#home', 'active' => true];
    
    // Add sections based on content availability
    if (!empty($content['about_content']) || !empty($content['about_title'])) {
        $navItems[] = ['label' => 'Tentang', 'href' => '#about', 'active' => false];
    }
    
    if (!empty($content['services']) && is_array($content['services'])) {
        $navItems[] = ['label' => 'Layanan', 'href' => '#services', 'active' => false];
    }
    
    if (!empty($content['gallery_photos']) && is_array($content['gallery_photos'])) {
        $navItems[] = ['label' => 'Gallery', 'href' => '#gallery', 'active' => false];
    }
    
    if (!empty($content['testimonials']) && is_array($content['testimonials'])) {
        $navItems[] = ['label' => 'Testimoni', 'href' => '#testimonials', 'active' => false];
    }
    
    // Always include Contact
    $navItems[] = ['label' => 'Kontak', 'href' => '#contact', 'active' => false];
    
    // Custom navigation items if provided
    if (!empty($content['custom_nav_items']) && is_array($content['custom_nav_items'])) {
        foreach ($content['custom_nav_items'] as $customNav) {
            if (!empty($customNav['label']) && !empty($customNav['href'])) {
                $navItems[] = [
                    'label' => $customNav['label'],
                    'href' => $customNav['href'],
                    'active' => false,
                    'external' => $customNav['external'] ?? false
                ];
            }
        }
    }
@endphp

<!-- Header Styles -->
<style>
    :root {
        --header-bg: {{ $headerStyle === 'transparent' ? 'rgba(255, 255, 255, 0.95)' : ($headerStyle === 'colored' ? $primaryColor : '#ffffff') }};
        --header-text: {{ $headerStyle === 'colored' ? '#ffffff' : '#1f2937' }};
        --header-shadow: {{ $headerStyle === 'transparent' ? '0 4px 6px -1px rgba(0, 0, 0, 0.1)' : '0 1px 3px 0 rgba(0, 0, 0, 0.1)' }};
        --nav-hover-bg: {{ $headerStyle === 'colored' ? 'rgba(255, 255, 255, 0.1)' : 'rgba(59, 130, 246, 0.1)' }};
        --nav-hover-text: {{ $headerStyle === 'colored' ? '#ffffff' : $primaryColor }};
        --hamburger-color: var(--header-text);
    }

    .header {
        background: var(--header-bg);
        color: var(--header-text);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .header.scrolled {
        box-shadow: var(--header-shadow);
        {{ $headerStyle === 'transparent' ? 'background: rgba(255, 255, 255, 0.98);' : '' }}
    }

    .header.sticky {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
    }

    .header-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 70px;
        position: relative;
    }

    .header.scrolled .header-content {
        height: 60px;
    }

    /* Logo and Brand */
    .brand {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: inherit;
        font-size: 1.5rem;
        font-weight: 700;
        transition: all 0.3s ease;
        z-index: 1001;
    }

    .brand:hover {
        color: inherit;
        text-decoration: none;
        transform: scale(1.02);
    }

    .brand-logo {
        height: 40px;
        width: auto;
        margin-right: 0.75rem;
        object-fit: contain;
        transition: all 0.3s ease;
    }

    .header.scrolled .brand-logo {
        height: 35px;
    }

    .brand-text {
        font-family: inherit;
        letter-spacing: -0.02em;
    }

    /* Desktop Navigation */
    .nav-desktop {
        display: none;
        align-items: center;
        gap: 2rem;
    }

    .nav-item {
        position: relative;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
        color: var(--header-text);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.95rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        position: relative;
    }

    .nav-link:hover,
    .nav-link.active {
        color: var(--nav-hover-text);
        background: var(--nav-hover-bg);
        text-decoration: none;
    }

    .nav-link::before {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%) scaleX(0);
        width: 20px;
        height: 2px;
        background: var(--nav-hover-text);
        border-radius: 1px;
        transition: transform 0.3s ease;
    }

    .nav-link:hover::before,
    .nav-link.active::before {
        transform: translateX(-50%) scaleX(1);
    }

    /* Mobile Menu Button */
    .mobile-menu-button {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border: none;
        background: transparent;
        color: var(--hamburger-color);
        cursor: pointer;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        z-index: 1001;
    }

    .mobile-menu-button:hover {
        background: var(--nav-hover-bg);
        color: var(--nav-hover-text);
    }

    .mobile-menu-button:focus {
        outline: 2px solid var(--nav-hover-text);
        outline-offset: 2px;
    }

    /* Hamburger Animation */
    .hamburger-icon {
        position: relative;
        width: 24px;
        height: 18px;
        transform: rotate(0deg);
        transition: 0.3s ease-in-out;
    }

    .hamburger-icon span {
        display: block;
        position: absolute;
        height: 2px;
        width: 100%;
        background: currentColor;
        border-radius: 1px;
        opacity: 1;
        left: 0;
        transform: rotate(0deg);
        transition: 0.25s ease-in-out;
    }

    .hamburger-icon span:nth-child(1) {
        top: 0;
    }

    .hamburger-icon span:nth-child(2),
    .hamburger-icon span:nth-child(3) {
        top: 8px;
    }

    .hamburger-icon span:nth-child(4) {
        top: 16px;
    }

    /* Hamburger to X animation */
    .mobile-menu-open .hamburger-icon span:nth-child(1) {
        top: 8px;
        width: 0%;
        left: 50%;
    }

    .mobile-menu-open .hamburger-icon span:nth-child(2) {
        transform: rotate(45deg);
    }

    .mobile-menu-open .hamburger-icon span:nth-child(3) {
        transform: rotate(-45deg);
    }

    .mobile-menu-open .hamburger-icon span:nth-child(4) {
        top: 8px;
        width: 0%;
        left: 50%;
    }

    /* Mobile Navigation */
    .nav-mobile {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nav-mobile.open {
        opacity: 1;
        visibility: visible;
    }

    .nav-mobile-content {
        position: absolute;
        top: 0;
        right: -100%;
        width: min(320px, 80vw);
        height: 100vh;
        background: var(--header-bg);
        padding: 6rem 0 2rem;
        overflow-y: auto;
        transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: -4px 0 20px rgba(0, 0, 0, 0.1);
    }

    .nav-mobile.open .nav-mobile-content {
        right: 0;
    }

    .nav-mobile-items {
        padding: 0 1.5rem;
    }

    .nav-mobile-item {
        margin-bottom: 0.5rem;
    }

    .nav-mobile-link {
        display: flex;
        align-items: center;
        padding: 1rem;
        color: var(--header-text);
        text-decoration: none;
        font-weight: 500;
        font-size: 1.1rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        position: relative;
    }

    .nav-mobile-link:hover,
    .nav-mobile-link.active {
        color: var(--nav-hover-text);
        background: var(--nav-hover-bg);
        text-decoration: none;
        transform: translateX(8px);
    }

    .nav-mobile-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 0;
        background: var(--nav-hover-text);
        border-radius: 2px;
        transition: height 0.3s ease;
    }

    .nav-mobile-link:hover::before,
    .nav-mobile-link.active::before {
        height: 24px;
    }

    /* CTA Button */
    .header-cta {
        display: none;
        align-items: center;
        margin-left: 1rem;
    }

    .cta-button {
        padding: 0.75rem 1.5rem;
        background: var(--nav-hover-text, {{ $primaryColor }});
        color: white;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .cta-button:hover {
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* Responsive Design */
    @media (min-width: 768px) {
        .nav-desktop {
            display: flex;
        }

        .mobile-menu-button {
            display: none;
        }

        .header-cta {
            display: flex;
        }

        .header-container {
            padding: 0 2rem;
        }
    }

    @media (min-width: 1024px) {
        .header-content {
            height: 80px;
        }

        .header.scrolled .header-content {
            height: 70px;
        }

        .brand {
            font-size: 1.75rem;
        }

        .brand-logo {
            height: 45px;
            margin-right: 1rem;
        }

        .header.scrolled .brand-logo {
            height: 40px;
        }

        .nav-desktop {
            gap: 2.5rem;
        }
    }

    /* Accessibility */
    @media (prefers-reduced-motion: reduce) {
        * {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }

    /* High contrast mode */
    @media (prefers-contrast: high) {
        .header {
            border-bottom: 2px solid;
        }
        
        .nav-link:focus,
        .mobile-menu-button:focus {
            outline: 3px solid;
            outline-offset: 2px;
        }
    }

    /* Print styles */
    @media print {
        .header {
            position: static !important;
            box-shadow: none !important;
            background: white !important;
        }
        
        .mobile-menu-button,
        .nav-mobile {
            display: none !important;
        }
    }
</style>

<!-- Header Component -->
<header 
    class="header {{ $stickyHeader ? 'sticky' : '' }}"
    id="site-header"
    role="banner"
    aria-label="Site header"
>
    <div class="header-container">
        <div class="header-content">
            <!-- Brand/Logo -->
            <a href="#" class="brand" aria-label="Go to homepage">
                @if($companyLogo)
                    <img 
                        src="{{ $companyLogo }}" 
                        alt="{{ $companyName }}"
                        class="brand-logo"
                        loading="eager"
                        decoding="async"
                        style="max-width:80%"
                    >
                @endif
                <!-- <span class="brand-text">{{ $companyName }}</span> -->
            </a>

            <!-- Desktop Navigation -->
            <nav class="nav-desktop" role="navigation" aria-label="Main navigation">
                @foreach($navItems as $index => $item)
                    <div class="nav-item">
                        <a 
                            href="{{ $item['href'] }}"
                            class="nav-link {{ $item['active'] ? 'active' : '' }}"
                            {{ ($item['external'] ?? false) ? 'target="_blank" rel="noopener noreferrer"' : '' }}
                            data-nav-item="{{ $index }}"
                        >
                            {{ $item['label'] }}
                            @if($item['external'] ?? false)
                                <i class="fas fa-external-link-alt ml-1" style="font-size: 0.75em;"></i>
                            @endif
                        </a>
                    </div>
                @endforeach
            </nav>

            <!-- CTA Button (Desktop) -->
            @if(!empty($content['header_cta_text']) && !empty($content['header_cta_link']))
                <div class="header-cta">
                    <a 
                        href="{{ $content['header_cta_link'] }}"
                        class="cta-button"
                        onclick="trackCTAClick('header', '{{ $content['header_cta_text'] }}')"
                    >
                        {{ $content['header_cta_text'] }}
                    </a>
                </div>
            @endif

            <!-- Mobile Menu Button -->
            <button 
                class="mobile-menu-button"
                id="mobile-menu-toggle"
                aria-label="Toggle mobile menu"
                aria-expanded="false"
                aria-controls="mobile-navigation"
                type="button"
            >
                <div class="hamburger-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <nav 
        class="nav-mobile" 
        id="mobile-navigation"
        role="navigation" 
        aria-label="Mobile navigation"
        aria-hidden="true"
    >
        <div class="nav-mobile-content">
            <div class="nav-mobile-items">
                @foreach($navItems as $index => $item)
                    <div class="nav-mobile-item">
                        <a 
                            href="{{ $item['href'] }}"
                            class="nav-mobile-link {{ $item['active'] ? 'active' : '' }}"
                            {{ ($item['external'] ?? false) ? 'target="_blank" rel="noopener noreferrer"' : '' }}
                            data-nav-item="mobile-{{ $index }}"
                            onclick="closeMobileMenu()"
                        >
                            <span>{{ $item['label'] }}</span>
                            @if($item['external'] ?? false)
                                <i class="fas fa-external-link-alt ml-2" style="font-size: 0.8em;"></i>
                            @endif
                        </a>
                    </div>
                @endforeach

                <!-- Mobile CTA Button -->
                @if(!empty($content['header_cta_text']) && !empty($content['header_cta_link']))
                    <div class="nav-mobile-item" style="margin-top: 2rem; padding: 0 1rem;">
                        <a 
                            href="{{ $content['header_cta_link'] }}"
                            class="cta-button"
                            style="display: block; text-align: center;"
                            onclick="closeMobileMenu(); trackCTAClick('header-mobile', '{{ $content['header_cta_text'] }}')"
                        >
                            {{ $content['header_cta_text'] }}
                        </a>
                    </div>
                @endif

                <!-- Mobile Contact Info -->
                @if(!empty($content['contact_phone']) || !empty($content['contact_email']))
                    <div style="margin-top: 3rem; padding: 1.5rem; border-top: 1px solid rgba(0,0,0,0.1);">
                        @if(!empty($content['contact_phone']))
                            <div style="margin-bottom: 1rem;">
                                <a 
                                    href="tel:{{ $content['contact_phone'] }}"
                                    style="display: flex; align-items: center; color: var(--header-text); text-decoration: none; font-size: 0.9rem;"
                                    onclick="trackContactClick('phone', '{{ $content['contact_phone'] }}')"
                                >
                                    <i class="fas fa-phone" style="width: 20px; margin-right: 0.75rem; color: {{ $primaryColor }};"></i>
                                    {{ $content['contact_phone'] }}
                                </a>
                            </div>
                        @endif

                        @if(!empty($content['contact_email']))
                            <div>
                                <a 
                                    href="mailto:{{ $content['contact_email'] }}"
                                    style="display: flex; align-items: center; color: var(--header-text); text-decoration: none; font-size: 0.9rem;"
                                    onclick="trackContactClick('email', '{{ $content['contact_email'] }}')"
                                >
                                    <i class="fas fa-envelope" style="width: 20px; margin-right: 0.75rem; color: {{ $primaryColor }};"></i>
                                    {{ $content['contact_email'] }}
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </nav>
</header>

<!-- Header JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const header = document.getElementById('site-header');
        const mobileToggle = document.getElementById('mobile-menu-toggle');
        const mobileNav = document.getElementById('mobile-navigation');
        let isMenuOpen = false;

        // Sticky header scroll behavior
        @if($stickyHeader)
        let lastScrollTop = 0;
        let scrollTimeout;

        function handleScroll() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Add scrolled class for styling
            if (scrollTop > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }

            // Auto-hide on scroll down (optional behavior)
            if (scrollTop > lastScrollTop && scrollTop > 200) {
                // Scrolling down - could hide header here if desired
                // header.style.transform = 'translateY(-100%)';
            } else {
                // Scrolling up - show header
                header.style.transform = 'translateY(0)';
            }

            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;

            // Clear timeout and reset header visibility after scroll ends
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                header.style.transform = 'translateY(0)';
            }, 150);
        }

        // Throttle scroll events for performance
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(function() {
                    handleScroll();
                    ticking = false;
                });
                ticking = true;
            }
        });
        @endif

        // Mobile menu functionality
        function toggleMobileMenu() {
            isMenuOpen = !isMenuOpen;
            
            // Update button state
            mobileToggle.setAttribute('aria-expanded', isMenuOpen.toString());
            document.body.classList.toggle('mobile-menu-open', isMenuOpen);
            mobileNav.classList.toggle('open', isMenuOpen);
            mobileNav.setAttribute('aria-hidden', (!isMenuOpen).toString());
            
            // Prevent body scroll when menu is open
            if (isMenuOpen) {
                document.body.style.overflow = 'hidden';
                // Focus management
                mobileNav.querySelector('.nav-mobile-link').focus();
            } else {
                document.body.style.overflow = '';
                mobileToggle.focus();
            }

        }

        // Close mobile menu
        window.closeMobileMenu = function() {
            if (isMenuOpen) {
                toggleMobileMenu();
            }
        };

        // Mobile menu button click
        mobileToggle.addEventListener('click', toggleMobileMenu);

        // Close menu when clicking overlay
        mobileNav.addEventListener('click', function(e) {
            if (e.target === mobileNav) {
                closeMobileMenu();
            }
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isMenuOpen) {
                closeMobileMenu();
            }
        });

        // Handle smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                if (href === '#') return;
                
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    closeMobileMenu();
                    
                    // Calculate offset for sticky header
                    const headerHeight = header.offsetHeight + 20;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });

                    // Update active navigation
                    updateActiveNavigation(href);
                }
            });
        });

        // Update active navigation based on scroll position
        function updateActiveNavigation(activeHref = null) {
            const sections = document.querySelectorAll('section[id], div[id]');
            const navLinks = document.querySelectorAll('.nav-link, .nav-mobile-link');
            
            if (activeHref) {
                // Manual update
                navLinks.forEach(link => {
                    link.classList.toggle('active', link.getAttribute('href') === activeHref);
                });
            } else {
                // Auto update based on scroll
                let current = '';
                const headerHeight = header.offsetHeight;
                
                sections.forEach(section => {
                    const sectionTop = section.getBoundingClientRect().top - headerHeight - 50;
                    if (sectionTop <= 0) {
                        current = '#' + section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.toggle('active', link.getAttribute('href') === current);
                });
            }
        }

        // Update navigation on scroll
        let navigationTicking = false;
        window.addEventListener('scroll', function() {
            if (!navigationTicking) {
                requestAnimationFrame(function() {
                    updateActiveNavigation();
                    navigationTicking = false;
                });
                navigationTicking = true;
            }
        });

        // Initialize active navigation
        updateActiveNavigation();
    });

    // Resize handler for responsive behavior
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768 && isMenuOpen) {
            closeMobileMenu();
        }
    });
</script>

<div class="sr-only">
    <h1>{{ $companyName }} - {{ $content['hero_title'] ?? 'Website' }}</h1>
</div>
