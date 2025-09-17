@php
    // Get analytics configuration from content
    $googleTagManager = $content['google_tag_manager'] ?? '';
    $facebookPixel = $content['facebook_pixel'] ?? '';
    $hotjarId = $content['hotjar_id'] ?? '';
    $companyName = $content['company_name'] ?? 'Your Company';
    $websiteId = $websiteContent->id ?? null;
    $userId = $websiteContent->user_id ?? null;
    $templateSlug = $websiteContent->template_slug ?? 'unknown';
@endphp

@if(!empty($googleTagManager))
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe 
            src="https://www.googletagmanager.com/ns.html?id={{ $googleTagManager }}"
            height="0" 
            width="0" 
            style="display:none;visibility:hidden"
            title="Google Tag Manager"
            loading="lazy">
        </iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
@endif

@if(!empty($facebookPixel))
    <!-- Facebook Pixel (noscript) -->
    <noscript>
        <img 
            height="1" 
            width="1" 
            style="display:none"
            src="https://www.facebook.com/tr?id={{ $facebookPixel }}&ev=PageView&noscript=1"
            alt="Facebook Pixel"
            loading="lazy"
        />
    </noscript>
    <!-- End Facebook Pixel (noscript) -->
@endif

@if(!empty($content['custom_analytics_body']))
    <!-- Custom Analytics Body Code -->
    {!! $content['custom_analytics_body'] !!}
    <!-- End Custom Analytics Body Code -->
@endif

<div class="sr-only">
    <a href="#main-content" class="skip-link">Skip to main content</a>
    @if(!empty($content['services']))
        <a href="#services" class="skip-link">Skip to services</a>
    @endif
    @if(!empty($content['contact_email']) || !empty($content['contact_phone']))
        <a href="#contact" class="skip-link">Skip to contact</a>
    @endif
</div>

<div id="sr-announcements" class="sr-only" aria-live="polite" aria-atomic="true"></div>

@if(!empty($googleTagManager) || !empty($facebookPixel))
    <div id="analytics-loading" style="display: none;" aria-hidden="true">
        <span class="sr-only">Loading analytics...</span>
    </div>
@endif

@if(app()->environment(['local', 'development']) && ($preview_mode ?? false))
    <!-- Analytics Debug Panel -->
    <div id="analytics-debug" style="position: fixed; bottom: 10px; left: 10px; background: #1f2937; color: #f9fafb; padding: 12px; border-radius: 8px; font-family: monospace; font-size: 12px; z-index: 9998; max-width: 300px; opacity: 0.9; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
        <div style="font-weight: bold; color: #60a5fa; margin-bottom: 8px;">🔍 Analytics Debug</div>
        <div><strong>GTM:</strong> {{ !empty($googleTagManager) ? $googleTagManager : 'Not configured' }}</div>
        <div><strong>FB Pixel:</strong> {{ !empty($facebookPixel) ? $facebookPixel : 'Not configured' }}</div>
        <div><strong>Website ID:</strong> {{ $websiteId ?? 'N/A' }}</div>
        <div><strong>Template:</strong> {{ $templateSlug }}</div>
        <div><strong>Company:</strong> {{ $companyName }}</div>
        <button onclick="this.parentElement.style.display='none'" style="position: absolute; top: 4px; right: 4px; background: #ef4444; color: white; border: none; border-radius: 4px; width: 20px; height: 20px; font-size: 12px; cursor: pointer;">×</button>
    </div>
@endif

<div id="performance-markers" style="display: none;" aria-hidden="true">
    <!-- Performance timing markers for analytics -->
    <span data-performance="body-start">{{ microtime(true) }}</span>
</div>

@if(($content['show_cookie_banner'] ?? true) && !($preview_mode ?? false))
    <div id="cookie-consent-banner" class="cookie-banner" style="display: none;" role="dialog" aria-labelledby="cookie-banner-title" aria-describedby="cookie-banner-description">
        <div class="cookie-banner-content">
            <div class="cookie-banner-text">
                <h3 id="cookie-banner-title" class="cookie-banner-title">🍪 Cookie Notice</h3>
                <p id="cookie-banner-description" class="cookie-banner-description">
                    Kami menggunakan cookie untuk meningkatkan pengalaman Anda di website {{ $companyName }}. 
                    Dengan melanjutkan browsing, Anda menyetujui penggunaan cookie kami.
                </p>
            </div>
            <div class="cookie-banner-actions">
                <button id="cookie-accept" class="cookie-btn cookie-accept" onclick="acceptCookies()">
                    Terima Semua
                </button>
                <button id="cookie-decline" class="cookie-btn cookie-decline" onclick="declineCookies()">
                    Tolak
                </button>
                <button id="cookie-customize" class="cookie-btn cookie-customize" onclick="customizeCookies()">
                    Pengaturan
                </button>
            </div>
        </div>
    </div>

    <!-- Cookie Banner Styles -->
    <style>
        .cookie-banner {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-top: 1px solid #e5e7eb;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
            z-index: 10000;
            animation: slideUp 0.3s ease-out;
        }

        .cookie-banner-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .cookie-banner-title {
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 8px 0;
            color: #1f2937;
        }

        .cookie-banner-description {
            font-size: 14px;
            margin: 0;
            color: #6b7280;
            line-height: 1.5;
        }

        .cookie-banner-actions {
            display: flex;
            gap: 12px;
            flex-shrink: 0;
        }

        .cookie-btn {
            padding: 8px 16px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .cookie-accept {
            background: var(--primary-color, #2563eb);
            color: white;
            border-color: var(--primary-color, #2563eb);
        }

        .cookie-accept:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }

        .cookie-decline {
            background: #f9fafb;
            color: #6b7280;
        }

        .cookie-decline:hover {
            background: #f3f4f6;
            color: #4b5563;
        }

        .cookie-customize {
            background: white;
            color: #374151;
        }

        .cookie-customize:hover {
            background: #f9fafb;
            color: #1f2937;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .cookie-banner-content {
                flex-direction: column;
                text-align: center;
                gap: 16px;
            }

            .cookie-banner-actions {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }

            .cookie-btn {
                flex: 1;
                min-width: 80px;
            }
        }

        @media (max-width: 480px) {
            .cookie-banner-actions {
                flex-direction: column;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .cookie-banner {
                background: rgba(31, 41, 55, 0.98);
                border-top-color: #374151;
            }

            .cookie-banner-title {
                color: #f9fafb;
            }

            .cookie-banner-description {
                color: #9ca3af;
            }

            .cookie-decline {
                background: #374151;
                color: #d1d5db;
            }

            .cookie-decline:hover {
                background: #4b5563;
                color: #f3f4f6;
            }

            .cookie-customize {
                background: #1f2937;
                color: #e5e7eb;
                border-color: #4b5563;
            }

            .cookie-customize:hover {
                background: #374151;
                color: #f9fafb;
            }
        }

        /* Accessibility improvements */
        .cookie-btn:focus {
            outline: 2px solid var(--primary-color, #2563eb);
            outline-offset: 2px;
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            .cookie-banner {
                animation: none;
            }
        }
    </style>

    <!-- Cookie Management Script -->
    <script>
        // Cookie consent management
        function checkCookieConsent() {
            const consent = localStorage.getItem('cookie-consent');
            if (!consent) {
                setTimeout(() => {
                    document.getElementById('cookie-consent-banner').style.display = 'block';
                }, 2000); // Show after 2 seconds
            }
        }

        function acceptCookies() {
            localStorage.setItem('cookie-consent', 'accepted');
            localStorage.setItem('cookie-consent-date', new Date().toISOString());
            document.getElementById('cookie-consent-banner').style.display = 'none';
            
            // Enable all tracking
            enableAnalytics();
            
            // Track consent acceptance
            if (typeof gtag !== 'undefined') {
                gtag('event', 'cookie_consent', {
                    event_category: 'Privacy',
                    event_label: 'accepted',
                    company_name: '{{ $companyName }}',
                    website_id: '{{ $websiteId }}'
                });
            }
        }

        function declineCookies() {
            localStorage.setItem('cookie-consent', 'declined');
            localStorage.setItem('cookie-consent-date', new Date().toISOString());
            document.getElementById('cookie-consent-banner').style.display = 'none';
            
            // Disable non-essential tracking
            disableNonEssentialAnalytics();
            
            // Track consent decline
            console.log('Cookie consent declined for {{ $companyName }}');
        }

        function customizeCookies() {
            // Show cookie customization modal (implement as needed)
            alert('Pengaturan cookie akan segera tersedia. Untuk saat ini, silakan pilih Terima atau Tolak.');
        }

        function enableAnalytics() {
            // Enable Google Analytics
            if (typeof gtag !== 'undefined') {
                gtag('consent', 'update', {
                    'analytics_storage': 'granted',
                    'ad_storage': 'granted'
                });
            }

            // Enable Facebook Pixel
            @if(!empty($facebookPixel))
            if (typeof fbq !== 'undefined') {
                fbq('consent', 'grant');
            }
            @endif
        }

        function disableNonEssentialAnalytics() {
            // Disable Google Analytics
            if (typeof gtag !== 'undefined') {
                gtag('consent', 'update', {
                    'analytics_storage': 'denied',
                    'ad_storage': 'denied'
                });
            }

            // Disable Facebook Pixel
            @if(!empty($facebookPixel))
            if (typeof fbq !== 'undefined') {
                fbq('consent', 'revoke');
            }
            @endif
        }

        // Initialize cookie consent check
        document.addEventListener('DOMContentLoaded', function() {
            checkCookieConsent();
        });
    </script>
@endif

<style>
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .skip-link {
        position: absolute;
        top: -40px;
        left: 6px;
        background: var(--primary-color, #2563eb);
        color: white;
        padding: 8px;
        text-decoration: none;
        border-radius: 4px;
        z-index: 10001;
        font-weight: 600;
        transition: top 0.2s;
    }

    .skip-link:focus {
        top: 6px;
        outline: 2px solid #fff;
        outline-offset: 2px;
    }
</style>

<script>
    if ('performance' in window && 'mark' in window.performance) {
        window.performance.mark('body-content-loaded');
    }
</script>
