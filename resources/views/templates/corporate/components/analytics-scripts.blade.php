@php
    // Get analytics configuration from content
    $googleAnalytics = $content['google_analytics'] ?? '';
    $googleTagManager = $content['google_tag_manager'] ?? '';
    $facebookPixel = $content['facebook_pixel'] ?? '';
    $hotjarId = $content['hotjar_id'] ?? '';
    $microsoftClarity = $content['microsoft_clarity'] ?? '';
    $linkedInInsight = $content['linkedin_insight'] ?? '';
    $tiktokPixel = $content['tiktok_pixel'] ?? '';
    $snapchatPixel = $content['snapchat_pixel'] ?? '';
    $pinterestPixel = $content['pinterest_pixel'] ?? '';
    
    // Website and company information
    $companyName = $content['company_name'] ?? 'Your Company';
    $websiteId = $websiteContent->id ?? null;
    $userId = $websiteContent->user_id ?? null;
    $templateSlug = $websiteContent->template_slug ?? 'corporate';
    $currentUrl = url()->current();
    $pageTitle = $content['seo_title'] ?? ($content['hero_title'] ?? $companyName);
    $pageCategory = 'SaaS Website';
    $contentGroup = $templateSlug;
    
    // Enhanced tracking settings
    $enableEcommerce = $content['enable_ecommerce_tracking'] ?? false;
    $enableScrollTracking = $content['enable_scroll_tracking'] ?? true;
    $enableClickTracking = $content['enable_click_tracking'] ?? true;
    $enableFormTracking = $content['enable_form_tracking'] ?? true;
    $enableFileDownloadTracking = $content['enable_download_tracking'] ?? true;
    $enableVideoTracking = $content['enable_video_tracking'] ?? true;
    $enableSearchTracking = $content['enable_search_tracking'] ?? true;
    $enableCustomDimensions = $content['enable_custom_dimensions'] ?? true;
    
    // Performance and debugging
    $debugMode = app()->environment(['local', 'development']) && ($preview_mode ?? false);
    $analyticsConsent = 'granted'; // This should come from cookie consent
    
    // Custom events configuration
    $customEvents = $content['custom_tracking_events'] ?? [];
    
    // A/B testing
    $enableAbTesting = $content['enable_ab_testing'] ?? false;
    $abTestingVariant = $content['ab_testing_variant'] ?? 'A';
@endphp

{{-- Google Analytics 4 (if enabled and no GTM) --}}
@if(!empty($googleAnalytics) && empty($googleTagManager))
    <!-- Google Analytics 4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAnalytics }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        
        // Set default consent state
        gtag('consent', 'default', {
            'analytics_storage': '{{ $analyticsConsent }}',
            'ad_storage': '{{ $analyticsConsent }}',
            'ad_user_data': '{{ $analyticsConsent }}',
            'ad_personalization': '{{ $analyticsConsent }}',
            'personalization_storage': '{{ $analyticsConsent }}',
            'functionality_storage': 'granted',
            'security_storage': 'granted'
        });
        
        gtag('js', new Date());
        gtag('config', '{{ $googleAnalytics }}', {
            page_title: '{{ $pageTitle }}',
            page_location: '{{ $currentUrl }}',
            send_page_view: true,
            
            // Enhanced measurement settings
            enhanced_measurements: {
                scrolls: {{ $enableScrollTracking ? 'true' : 'false' }},
                outbound_clicks: {{ $enableClickTracking ? 'true' : 'false' }},
                site_search: {{ $enableSearchTracking ? 'true' : 'false' }},
                video_engagement: {{ $enableVideoTracking ? 'true' : 'false' }},
                file_downloads: {{ $enableFileDownloadTracking ? 'true' : 'false' }}
            },
            
            @if($enableCustomDimensions)
            // Custom dimensions
            custom_map: {
                'custom_parameter_1': 'company_name',
                'custom_parameter_2': 'template_name',
                'custom_parameter_3': 'website_id',
                'custom_parameter_4': 'user_id',
                'custom_parameter_5': 'page_category',
                'custom_parameter_6': 'ab_variant'
            },
            @endif
            
            // Advanced settings
            cookie_domain: 'auto',
            cookie_expires: 63072000, // 2 years
            cookie_update: true,
            anonymize_ip: true,
            allow_google_signals: true,
            allow_ad_personalization_signals: true,
            
            // User properties
            user_properties: {
                company_name: '{{ $companyName }}',
                template_type: '{{ $templateSlug }}',
                website_tier: 'saas_generated',
                @if($enableAbTesting)
                ab_variant: '{{ $abTestingVariant }}',
                @endif
                signup_date: '{{ $websiteContent->created_at ?? now()->format('Y-m-d') }}',
                user_segment: 'small_business'
            },
            
            @if($debugMode)
            debug_mode: true,
            @endif
        });
        
        // Send enhanced page view with custom data
        gtag('event', 'page_view', {
            page_title: '{{ $pageTitle }}',
            page_location: '{{ $currentUrl }}',
            page_referrer: document.referrer,
            company_name: '{{ $companyName }}',
            template_name: '{{ $templateSlug }}',
            website_id: '{{ $websiteId }}',
            user_id: '{{ $userId }}',
            content_group1: '{{ $pageCategory }}',
            content_group2: '{{ $contentGroup }}',
            content_group3: '{{ $companyName }}',
            @if($enableAbTesting)
            ab_variant: '{{ $abTestingVariant }}',
            @endif
            page_load_time: 0, // Will be updated by performance script
            session_start: true,
            engagement_time_msec: 0,
            send_to: '{{ $googleAnalytics }}'
        });
        
        @if($enableEcommerce)
        // Enhanced ecommerce setup
        gtag('config', '{{ $googleAnalytics }}', {
            enhanced_conversions: true,
            conversion_linker: true
        });
        @endif
        
        @if($debugMode)
        console.log('Google Analytics 4 initialized:', '{{ $googleAnalytics }}');
        @endif
    </script>
    <!-- End Google Analytics 4 -->
@endif

{{-- Google Tag Manager Scripts (if GTM is used instead of direct GA4) --}}
@if(!empty($googleTagManager))
    <script>
        // Enhanced dataLayer with comprehensive data
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            'event': 'gtm.dom',
            'company_name': '{{ $companyName }}',
            'template_name': '{{ $templateSlug }}',
            'website_id': '{{ $websiteId }}',
            'user_id': '{{ $userId }}',
            'page_type': 'saas_website',
            'page_category': '{{ $pageCategory }}',
            'page_template': '{{ $templateSlug }}',
            'content_group1': '{{ $pageCategory }}',
            'content_group2': '{{ $contentGroup }}',
            'content_group3': '{{ $companyName }}',
            'business_type': 'small_business',
            'website_tier': 'saas_generated',
            'creation_date': '{{ $websiteContent->created_at ?? now()->format('Y-m-d') }}',
            @if($enableAbTesting)
            'ab_variant': '{{ $abTestingVariant }}',
            'ab_test_name': 'template_variant_test',
            @endif
            'user_properties': {
                'company_name': '{{ $companyName }}',
                'template_type': '{{ $templateSlug }}',
                'signup_method': 'website_builder'
            }
        });
        
        @if($debugMode)
        console.log('Google Tag Manager dataLayer initialized with enhanced data');
        @endif
    </script>
@endif

{{-- Facebook Pixel --}}
@if(!empty($facebookPixel))
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        
        fbq('consent', '{{ $analyticsConsent }}');
        fbq('init', '{{ $facebookPixel }}');
        fbq('track', 'PageView', {
            content_name: '{{ $pageTitle }}',
            content_category: '{{ $templateSlug }}',
            content_type: 'website',
            company_name: '{{ $companyName }}',
            template_type: '{{ $templateSlug }}',
            website_id: '{{ $websiteId }}',
            @if($enableAbTesting)
            ab_variant: '{{ $abTestingVariant }}',
            @endif
            value: 0,
            currency: 'IDR'
        });
        
        // Enhanced tracking events
        fbq('trackCustom', 'WebsiteGenerated', {
            company_name: '{{ $companyName }}',
            template_used: '{{ $templateSlug }}',
            website_tier: 'saas_generated'
        });
        
        @if($debugMode)
        console.log('Facebook Pixel initialized:', '{{ $facebookPixel }}');
        @endif
    </script>
    <!-- End Facebook Pixel Code -->
@endif

{{-- Microsoft Clarity --}}
@if(!empty($microsoftClarity))
    <!-- Microsoft Clarity -->
    <script type="text/javascript">
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", "{{ $microsoftClarity }}");
        
        // Enhanced identify with custom data
        clarity('identify', '{{ $websiteId }}', '{{ $companyName }}', '{{ $templateSlug }}', {
            customUserId: '{{ $userId }}',
            websiteType: 'saas_generated',
            templateName: '{{ $templateSlug }}',
            companyName: '{{ $companyName }}',
            creationDate: '{{ $websiteContent->created_at ?? now()->format('Y-m-d') }}',
            @if($enableAbTesting)
            abVariant: '{{ $abTestingVariant }}',
            @endif
            businessCategory: 'small_business'
        });
        
        // Set custom tags
        clarity('set', 'template_type', '{{ $templateSlug }}');
        clarity('set', 'company_name', '{{ $companyName }}');
        clarity('set', 'website_tier', 'saas');
        @if($enableAbTesting)
        clarity('set', 'ab_variant', '{{ $abTestingVariant }}');
        @endif
        
        @if($debugMode)
        console.log('Microsoft Clarity initialized:', '{{ $microsoftClarity }}');
        @endif
    </script>
    <!-- End Microsoft Clarity -->
@endif

{{-- LinkedIn Insight Tag --}}
@if(!empty($linkedInInsight))
    <!-- LinkedIn Insight Tag -->
    <script type="text/javascript">
        _linkedin_partner_id = "{{ $linkedInInsight }}";
        window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
        window._linkedin_data_partner_ids.push(_linkedin_partner_id);
    </script>
    
    <script type="text/javascript">
        (function(l) {
            if (!l){window.lintrk = function(a,b){window.lintrk.q.push([a,b])};
            window.lintrk.q=[]}
            var s = document.getElementsByTagName("script")[0];
            var b = document.createElement("script");
            b.type = "text/javascript";b.async = true;
            b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
            s.parentNode.insertBefore(b, s);
        })(window.lintrk);
        
        // Enhanced tracking
        lintrk('track', { conversion_id: {{ $linkedInInsight }} });
        
        @if($debugMode)
        console.log('LinkedIn Insight Tag initialized:', '{{ $linkedInInsight }}');
        @endif
    </script>
    <!-- End LinkedIn Insight Tag -->
@endif

{{-- TikTok Pixel --}}
@if(!empty($tiktokPixel))
    <!-- TikTok Pixel Code -->
    <script>
        !function (w, d, t) {
            w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};
            ttq.load('{{ $tiktokPixel }}');
            ttq.page();
            
            // Enhanced tracking
            ttq.track('ViewContent', {
                content_type: 'website',
                content_name: '{{ $pageTitle }}',
                content_category: '{{ $templateSlug }}',
                company_name: '{{ $companyName }}',
                template_type: '{{ $templateSlug }}'
            });
        }(window, document, 'ttq');
        
        @if($debugMode)
        console.log('TikTok Pixel initialized:', '{{ $tiktokPixel }}');
        @endif
    </script>
    <!-- End TikTok Pixel Code -->
@endif

{{-- Snapchat Pixel --}}
@if(!empty($snapchatPixel))
    <!-- Snapchat Pixel -->
    <script type='text/javascript'>
        (function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function()
        {a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};
        a.queue=[];var s='script';r=t.createElement(s);r.async=!0;
        r.src=n;var u=t.getElementsByTagName(s)[0];
        u.parentNode.insertBefore(r,u);})(window,document,
        'https://sc-static.net/scevent.min.js');

        snaptr('init', '{{ $snapchatPixel }}', {
            'user_email': '__INSERT_USER_EMAIL__'
        });

        snaptr('track', 'PAGE_VIEW');
        
        @if($debugMode)
        console.log('Snapchat Pixel initialized:', '{{ $snapchatPixel }}');
        @endif
    </script>
    <!-- End Snapchat Pixel -->
@endif

{{-- Pinterest Pixel --}}
@if(!empty($pinterestPixel))
    <!-- Pinterest Pixel -->
    <script type="text/javascript">
        !function(e){if(!window.pintrk){window.pintrk = function () {
        window.pintrk.queue.push(Array.prototype.slice.call(arguments))};var
        n=window.pintrk;n.queue=[],n.version="3.0";var
        t=document.createElement("script");t.async=!0,t.src=e;var
        r=document.getElementsByTagName("script")[0];
        r.parentNode.insertBefore(t,r)}}("https://s.pinimg.com/ct/core.js");
        
        pintrk('load', '{{ $pinterestPixel }}', {
            em: '__INSERT_USER_EMAIL__'
        });
        pintrk('page');
        
        @if($debugMode)
        console.log('Pinterest Pixel initialized:', '{{ $pinterestPixel }}');
        @endif
    </script>
    <!-- End Pinterest Pixel -->
@endif

{{-- Enhanced Custom Event Tracking System --}}
<script>
    // Enhanced Custom Event Tracking System
    (function() {
        'use strict';
        
        // Configuration
        const analyticsConfig = {
            companyName: '{{ $companyName }}',
            websiteId: '{{ $websiteId }}',
            userId: '{{ $userId }}',
            templateSlug: '{{ $templateSlug }}',
            pageCategory: '{{ $pageCategory }}',
            contentGroup: '{{ $contentGroup }}',
            @if($enableAbTesting)
            abVariant: '{{ $abTestingVariant }}',
            @endif
            enableScrollTracking: {{ $enableScrollTracking ? 'true' : 'false' }},
            enableClickTracking: {{ $enableClickTracking ? 'true' : 'false' }},
            enableFormTracking: {{ $enableFormTracking ? 'true' : 'false' }},
            enableDownloadTracking: {{ $enableFileDownloadTracking ? 'true' : 'false' }},
            enableVideoTracking: {{ $enableVideoTracking ? 'true' : 'false' }},
            enableSearchTracking: {{ $enableSearchTracking ? 'true' : 'false' }},
            debugMode: {{ $debugMode ? 'true' : 'false' }}
        };
        
        // Enhanced tracking functions
        window.trackEvent = function(eventName, eventData = {}) {
            const timestamp = new Date().toISOString();
            const baseData = {
                company_name: analyticsConfig.companyName,
                website_id: analyticsConfig.websiteId,
                user_id: analyticsConfig.userId,
                template_slug: analyticsConfig.templateSlug,
                page_category: analyticsConfig.pageCategory,
                content_group: analyticsConfig.contentGroup,
                @if($enableAbTesting)
                ab_variant: analyticsConfig.abVariant,
                @endif
                timestamp: timestamp,
                page_url: window.location.href,
                page_title: document.title,
                user_agent: navigator.userAgent,
                viewport_width: window.innerWidth,
                viewport_height: window.innerHeight,
                screen_resolution: `${screen.width}x${screen.height}`,
                color_depth: screen.colorDepth,
                language: navigator.language,
                timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                connection_type: navigator.connection ? navigator.connection.effectiveType : 'unknown',
                device_memory: navigator.deviceMemory || 'unknown',
                hardware_concurrency: navigator.hardwareConcurrency || 'unknown'
            };
            
            const fullEventData = { ...baseData, ...eventData };
            
            // Google Analytics 4
            if (typeof gtag !== 'undefined') {
                gtag('event', eventName, {
                    event_category: eventData.category || 'Custom',
                    event_label: eventData.label || '',
                    value: eventData.value || 0,
                    currency: eventData.currency || 'IDR',
                    ...fullEventData
                });
            }
            
            // Facebook Pixel
            @if(!empty($facebookPixel))
            if (typeof fbq !== 'undefined') {
                fbq('track', 'CustomEvent', {
                    event_name: eventName,
                    ...fullEventData
                });
            }
            @endif
            
            // LinkedIn
            @if(!empty($linkedInInsight))
            if (typeof lintrk !== 'undefined') {
                lintrk('track', { 
                    conversion_id: '{{ $linkedInInsight }}',
                    event_name: eventName,
                    ...fullEventData
                });
            }
            @endif
            
            // TikTok
            @if(!empty($tiktokPixel))
            if (typeof ttq !== 'undefined') {
                ttq.track('CustomEvent', {
                    content_type: eventName,
                    ...fullEventData
                });
            }
            @endif
            
            // Custom analytics endpoint
            if (window.customAnalyticsEndpoint) {
                sendToCustomEndpoint(eventName, fullEventData);
            }

        };
        
        // Enhanced page performance tracking
        function trackPagePerformance() {
            if ('performance' in window && 'timing' in window.performance) {
                window.addEventListener('load', function() {
                    setTimeout(function() {
                        const timing = window.performance.timing;
                        const navigation = window.performance.navigation;
                        const loadStart = timing.navigationStart;
                        const loadEnd = timing.loadEventEnd;
                        const loadTime = loadEnd - loadStart;
                        
                        const performanceData = {
                            category: 'Performance',
                            // Core metrics
                            page_load_time: loadTime,
                            dom_ready_time: timing.domContentLoadedEventEnd - loadStart,
                            dom_interactive_time: timing.domInteractive - loadStart,
                            
                            // Network metrics
                            dns_lookup_time: timing.domainLookupEnd - timing.domainLookupStart,
                            tcp_connection_time: timing.connectEnd - timing.connectStart,
                            server_response_time: timing.responseEnd - timing.requestStart,
                            dom_loading_time: timing.responseEnd - timing.navigationStart,
                            
                            // Navigation type
                            navigation_type: navigation.type,
                            redirect_count: navigation.redirectCount,
                            
                            // Connection info
                            connection_type: navigator.connection ? navigator.connection.effectiveType : 'unknown',
                            connection_downlink: navigator.connection ? navigator.connection.downlink : 'unknown',
                            connection_rtt: navigator.connection ? navigator.connection.rtt : 'unknown'
                        };
                        
                        // Get paint metrics
                        if (window.performance.getEntriesByType) {
                            const paintMetrics = window.performance.getEntriesByType('paint');
                            paintMetrics.forEach(metric => {
                                performanceData[metric.name.replace('-', '_')] = Math.round(metric.startTime);
                            });
                        }
                        
                        trackEvent('page_performance', performanceData);
                        
                        // Update GA4 page_view with load time
                        if (typeof gtag !== 'undefined') {
                            gtag('event', 'page_view', {
                                page_load_time: loadTime,
                                send_to: '{{ $googleAnalytics }}'
                            });
                        }
                    }, 2000);
                });
            }
        }
        
        // Enhanced click tracking with heatmap data
        function trackClicks() {
            if (!analyticsConfig.enableClickTracking) return;
            
            document.addEventListener('click', function(event) {
                const element = event.target.closest('a, button, [data-track], [onclick]');
                if (!element) return;
                
                const elementType = element.tagName.toLowerCase();
                const elementText = element.textContent?.trim().substring(0, 100) || '';
                const elementHref = element.href || '';
                const elementClasses = element.className || '';
                const elementId = element.id || '';
                const trackingData = element.dataset.track || '';
                
                // Get click position relative to element
                const rect = element.getBoundingClientRect();
                const clickX = event.clientX - rect.left;
                const clickY = event.clientY - rect.top;
                const relativeX = (clickX / rect.width) * 100;
                const relativeY = (clickY / rect.height) * 100;
                
                let eventName = 'element_click';
                let eventData = {
                    category: 'Interaction',
                    element_type: elementType,
                    element_text: elementText,
                    element_href: elementHref,
                    element_classes: elementClasses,
                    element_id: elementId,
                    click_x: Math.round(event.clientX),
                    click_y: Math.round(event.clientY),
                    element_x: Math.round(rect.left),
                    element_y: Math.round(rect.top),
                    element_width: Math.round(rect.width),
                    element_height: Math.round(rect.height),
                    relative_click_x: Math.round(relativeX),
                    relative_click_y: Math.round(relativeY)
                };
                
                // Special tracking for different element types
                if (elementType === 'a') {
                    if (elementHref.startsWith('tel:')) {
                        eventName = 'phone_click';
                        eventData.phone_number = elementHref.replace('tel:', '');
                    } else if (elementHref.startsWith('mailto:')) {
                        eventName = 'email_click';
                        eventData.email_address = elementHref.replace('mailto:', '');
                    } else if (elementHref.includes('wa.me') || elementHref.includes('whatsapp')) {
                        eventName = 'whatsapp_click';
                    } else if (element.hostname !== window.location.hostname) {
                        eventName = 'external_link_click';
                        eventData.external_domain = element.hostname;
                    } else if (elementHref.startsWith('#')) {
                        eventName = 'anchor_link_click';
                        eventData.anchor_target = elementHref;
                    }
                } else if (elementType === 'button') {
                    eventName = 'button_click';
                    eventData.button_type = element.type || 'button';
                }
                
                // Custom data attributes
                if (trackingData) {
                    try {
                        const customData = JSON.parse(trackingData);
                        eventData = { ...eventData, ...customData };
                    } catch (e) {
                        eventData.custom_data = trackingData;
                    }
                }
                
                trackEvent(eventName, eventData);
            });
        }
        
        // Enhanced form tracking
        function trackForms() {
            if (!analyticsConfig.enableFormTracking) return;
            
            // Track form submissions
            document.addEventListener('submit', function(event) {
                const form = event.target;
                if (form.tagName.toLowerCase() !== 'form') return;
                
                const formData = new FormData(form);
                const formFields = {};
                let fieldCount = 0;
                let hasEmailField = false;
                let hasPhoneField = false;
                
                for (let [key, value] of formData.entries()) {
                    if (key && !key.toLowerCase().includes('password') && !key.toLowerCase().includes('secret')) {
                        if (key.toLowerCase().includes('email')) hasEmailField = true;
                        if (key.toLowerCase().includes('phone') || key.toLowerCase().includes('tel')) hasPhoneField = true;
                        
                        formFields[key] = typeof value === 'string' ? 
                            value.substring(0, 50) + (value.length > 50 ? '...' : '') : 'file';
                        fieldCount++;
                    }
                }
                
                trackEvent('form_submit', {
                    category: 'Form',
                    form_id: form.id || 'unnamed',
                    form_action: form.action || window.location.href,
                    form_method: form.method || 'get',
                    field_count: fieldCount,
                    has_email_field: hasEmailField,
                    has_phone_field: hasPhoneField,
                    form_completion_time: Date.now() - (form._startTime || Date.now())
                });
            });
            
            // Track form field interactions
            document.addEventListener('focus', function(event) {
                const element = event.target;
                if (['input', 'textarea', 'select'].includes(element.tagName.toLowerCase())) {
                    const form = element.closest('form');
                    if (form && !form._startTime) {
                        form._startTime = Date.now();
                    }
                    
                    trackEvent('form_field_focus', {
                        category: 'Form',
                        field_type: element.type || element.tagName.toLowerCase(),
                        field_name: element.name || element.id || 'unnamed',
                        form_id: form?.id || 'unnamed',
                        field_required: element.required
                    });
                }
            }, true);
            
            // Track form field completion
            document.addEventListener('blur', function(event) {
                const element = event.target;
                if (['input', 'textarea', 'select'].includes(element.tagName.toLowerCase())) {
                    if (element.value && element.value.length > 0) {
                        trackEvent('form_field_completed', {
                            category: 'Form',
                            field_type: element.type || element.tagName.toLowerCase(),
                            field_name: element.name || element.id || 'unnamed',
                            field_length: element.value.length,
                            form_id: element.closest('form')?.id || 'unnamed'
                        });
                    }
                }
            }, true);
        }
        
        // Enhanced file download tracking
        function trackDownloads() {
            if (!analyticsConfig.enableDownloadTracking) return;
            
            document.addEventListener('click', function(event) {
                const link = event.target.closest('a');
                if (!link || !link.href) return;
                
                const href = link.href.toLowerCase();
                const downloadExtensions = [
                    'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 
                    'zip', 'rar', '7z', 'tar', 'gz',
                    'mp3', 'mp4', 'avi', 'mov', 'wmv', 'flv',
                    'jpg', 'jpeg', 'png', 'gif', 'svg', 'bmp',
                    'txt', 'csv', 'json', 'xml'
                ];
                
                const urlParts = href.split('?')[0].split('.');
                const extension = urlParts[urlParts.length - 1];
                
                if (downloadExtensions.includes(extension)) {
                    trackEvent('file_download', {
                        category: 'Download',
                        file_extension: extension,
                        file_url: link.href,
                        file_name: link.href.split('/').pop(),
                        link_text: link.textContent?.trim() || '',
                        file_size_estimated: getEstimatedFileSize(extension)
                    });
                }
            });
        }
        
        function getEstimatedFileSize(extension) {
            const sizeMappings = {
                'pdf': 'medium', 'doc': 'small', 'docx': 'small',
                'xls': 'small', 'xlsx': 'small', 'ppt': 'medium', 'pptx': 'medium',
                'zip': 'large', 'rar': 'large', '7z': 'large',
                'mp3': 'medium', 'mp4': 'large', 'avi': 'large',
                'jpg': 'small', 'png': 'small', 'gif': 'small'
            };
            return sizeMappings[extension] || 'unknown';
        }
        
        // Enhanced video tracking
        function trackVideoEngagement() {
            if (!analyticsConfig.enableVideoTracking) return;
            
            const videoEvents = ['play', 'pause', 'ended', 'seeking', 'seeked'];
            
            videoEvents.forEach(eventType => {
                document.addEventListener(eventType, function(event) {
                    if (event.target.tagName.toLowerCase() === 'video') {
                        const video = event.target;
                        const videoSrc = video.src || video.currentSrc || 'unknown';
                        const videoDuration = video.duration || 0;
                        const currentTime = video.currentTime || 0;
                        const watchedPercentage = videoDuration ? (currentTime / videoDuration) * 100 : 0;
                        
                        trackEvent(`video_${eventType}`, {
                            category: 'Media',
                            video_src: videoSrc,
                            video_title: video.title || videoSrc.split('/').pop(),
                            video_duration: Math.round(videoDuration),
                            current_time: Math.round(currentTime),
                            watched_percentage: Math.round(watchedPercentage),
                            video_quality: video.videoWidth ? `${video.videoWidth}x${video.videoHeight}` : 'unknown',
                            is_fullscreen: document.fullscreenElement === video
                        });
                    }
                }, true);
            });
        }
        
        // Enhanced error tracking
        function trackErrors() {
            // JavaScript errors
            window.addEventListener('error', function(event) {
                trackEvent('javascript_error', {
                    category: 'Error',
                    error_message: event.message || 'Unknown error',
                    error_filename: event.filename || 'Unknown file',
                    error_line: event.lineno || 0,
                    error_column: event.colno || 0,
                    error_stack: event.error?.stack?.substring(0, 500) || 'No stack trace'
                });
            });
            
            // Promise rejection errors
            window.addEventListener('unhandledrejection', function(event) {
                trackEvent('promise_rejection', {
                    category: 'Error',
                    error_message: event.reason?.message || 'Promise rejected',
                    error_stack: event.reason?.stack?.substring(0, 500) || 'No stack trace',
                    error_type: typeof event.reason
                });
            });
            
            // Resource loading errors
            document.addEventListener('error', function(event) {
                if (event.target !== window) {
                    const element = event.target;
                    trackEvent('resource_error', {
                        category: 'Error',
                        resource_type: element.tagName.toLowerCase(),
                        resource_src: element.src || element.href || 'unknown',
                        error_type: 'load_failed'
                    });
                }
            }, true);
        }
        
        // Enhanced page visibility tracking
        function trackVisibility() {
            let startTime = Date.now();
            let isVisible = !document.hidden;
            let totalVisibleTime = 0;
            let focusCount = 0;
            
            document.addEventListener('visibilitychange', function() {
                const now = Date.now();
                const timeSpent = now - startTime;
                
                if (document.hidden && isVisible) {
                    // Page became hidden
                    totalVisibleTime += timeSpent;
                    trackEvent('page_hidden', {
                        category: 'Engagement',
                        time_visible: Math.round(timeSpent / 1000),
                        total_visible_time: Math.round(totalVisibleTime / 1000),
                        focus_count: focusCount
                    });
                    isVisible = false;
                } else if (!document.hidden && !isVisible) {
                    // Page became visible
                    focusCount++;
                    trackEvent('page_visible', {
                        category: 'Engagement',
                        focus_count: focusCount,
                        total_hidden_time: Math.round((now - startTime) / 1000)
                    });
                    isVisible = true;
                    startTime = now;
                }
            });
            
            // Track time on page before unload
            window.addEventListener('beforeunload', function() {
                if (isVisible) {
                    const timeSpent = Date.now() - startTime;
                    totalVisibleTime += timeSpent;
                }
                
                trackEvent('page_unload', {
                    category: 'Engagement',
                    total_time_on_page: Math.round((Date.now() - performance.timing.navigationStart) / 1000),
                    total_visible_time: Math.round(totalVisibleTime / 1000),
                    focus_count: focusCount,
                    engagement_score: calculateEngagementScore(totalVisibleTime, focusCount)
                });
            });
        }
        
        function calculateEngagementScore(visibleTime, focusCount) {
            // Simple engagement score calculation
            const timeScore = Math.min(visibleTime / 60000, 1); // Max 1 minute = score 1
            const focusScore = Math.min(focusCount / 3, 1); // Max 3 focuses = score 1
            return Math.round((timeScore + focusScore) * 50); // Scale to 0-100
        }
        
        // Custom analytics endpoint
        function sendToCustomEndpoint(eventName, eventData) {
            if (!window.customAnalyticsEndpoint) return;
            
            fetch(window.customAnalyticsEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    event: eventName,
                    data: eventData,
                    timestamp: new Date().toISOString(),
                    url: window.location.href,
                    referrer: document.referrer,
                    user_agent: navigator.userAgent
                })
            }).catch(error => {
                if (analyticsConfig.debugMode) {
                    console.error('Custom analytics error:', error);
                }
            });
        }
        
        // Initialize all tracking
        document.addEventListener('DOMContentLoaded', function() {
            trackPagePerformance();
            trackClicks();
            trackForms();
            trackDownloads();
            trackVideoEngagement();
            trackErrors();
            trackVisibility();
            
            // Initial enhanced page view
            trackEvent('enhanced_page_view', {
                category: 'Page',
                referrer: document.referrer || 'direct',
                referrer_domain: document.referrer ? new URL(document.referrer).hostname : 'direct',
                screen_resolution: `${screen.width}x${screen.height}`,
                available_screen: `${screen.availWidth}x${screen.availHeight}`,
                color_depth: screen.colorDepth,
                pixel_ratio: window.devicePixelRatio || 1,
                language: navigator.language,
                languages: navigator.languages?.join(',') || navigator.language,
                platform: navigator.platform,
                user_agent: navigator.userAgent,
                cookie_enabled: navigator.cookieEnabled,
                java_enabled: navigator.javaEnabled ? navigator.javaEnabled() : false,
                timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                timezone_offset: new Date().getTimezoneOffset(),
                connection_type: navigator.connection ? navigator.connection.effectiveType : 'unknown',
                connection_downlink: navigator.connection ? navigator.connection.downlink : 'unknown',
                device_memory: navigator.deviceMemory || 'unknown',
                hardware_concurrency: navigator.hardwareConcurrency || 'unknown',
                @if($enableAbTesting)
                ab_test_variant: analyticsConfig.abVariant,
                @endif
                page_load_source: performance.navigation ? 
                    (performance.navigation.type === 1 ? 'reload' : 'navigation') : 'unknown'
            });
            
            if (analyticsConfig.debugMode) {
                console.log('🚀 Enhanced Analytics initialized with config:', analyticsConfig);
            }
        });
        
    })();
</script>

{{-- Core Web Vitals and Advanced Performance Monitoring --}}
<script>
    // Core Web Vitals monitoring with enhanced data
    function sendMetricToAnalytics({name, value, id, navigationType, rating}) {
        const metricData = {
            category: 'Web Vitals',
            metric_name: name,
            metric_value: Math.round(name === 'CLS' ? value * 1000 : value),
            metric_id: id,
            metric_rating: rating || 'unknown',
            navigation_type: navigationType || 'navigate',
            connection_type: navigator.connection ? navigator.connection.effectiveType : 'unknown',
            device_memory: navigator.deviceMemory || 'unknown',
            hardware_concurrency: navigator.hardwareConcurrency || 'unknown'
        };
        
        // Determine performance rating
        let performanceRating = 'good';
        switch(name) {
            case 'LCP':
                performanceRating = value <= 2500 ? 'good' : value <= 4000 ? 'needs-improvement' : 'poor';
                break;
            case 'FID':
                performanceRating = value <= 100 ? 'good' : value <= 300 ? 'needs-improvement' : 'poor';
                break;
            case 'CLS':
                performanceRating = value <= 0.1 ? 'good' : value <= 0.25 ? 'needs-improvement' : 'poor';
                break;
            case 'FCP':
                performanceRating = value <= 1800 ? 'good' : value <= 3000 ? 'needs-improvement' : 'poor';
                break;
            case 'TTFB':
                performanceRating = value <= 800 ? 'good' : value <= 1800 ? 'needs-improvement' : 'poor';
                break;
        }
        metricData.performance_rating = performanceRating;
        
        // Send to Google Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', name, {
                event_category: 'Web Vitals',
                event_label: id,
                value: Math.round(name === 'CLS' ? value * 1000 : value),
                custom_parameter_1: '{{ $companyName }}',
                custom_parameter_2: '{{ $templateSlug }}',
                custom_parameter_3: performanceRating,
                non_interaction: true
            });
        }
        
        // Send to custom tracking
        if (typeof trackEvent !== 'undefined') {
            trackEvent('web_vital_' + name.toLowerCase(), metricData);
        }
        
        @if($debugMode)
        console.log('Web Vital tracked:', name, value, 'Rating:', performanceRating);
        @endif
    }
    
    // Load web-vitals library dynamically
    const script = document.createElement('script');
    script.src = 'https://unpkg.com/web-vitals@3/dist/web-vitals.iife.js';
    script.onload = function() {
        if (window.webVitals) {
            webVitals.getCLS(sendMetricToAnalytics);
            webVitals.getFID(sendMetricToAnalytics);
            webVitals.getFCP(sendMetricToAnalytics);
            webVitals.getLCP(sendMetricToAnalytics);
            webVitals.getTTFB(sendMetricToAnalytics);
        }
    };
    document.head.appendChild(script);
</script>

{{-- Custom Analytics Endpoint (Optional) --}}
@if(!empty($content['custom_analytics_endpoint']))
<script>
    // Set custom analytics endpoint for server-side tracking
    window.customAnalyticsEndpoint = '{{ $content['custom_analytics_endpoint'] }}';
    
    // Enhanced server-side tracking
    window.sendToCustomAnalytics = function(eventName, eventData) {
        if (!window.customAnalyticsEndpoint) return;
        
        const enhancedData = {
            ...eventData,
            server_timestamp: new Date().toISOString(),
            client_ip: 'auto_detect', // Server will detect
            session_id: getOrCreateSessionId(),
            user_fingerprint: generateUserFingerprint()
        };
        
        fetch(window.customAnalyticsEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Analytics-Version': '2.0',
                'X-Client-Timestamp': new Date().toISOString()
            },
            body: JSON.stringify({
                event: eventName,
                data: enhancedData,
                metadata: {
                    url: window.location.href,
                    referrer: document.referrer,
                    user_agent: navigator.userAgent,
                    viewport: `${window.innerWidth}x${window.innerHeight}`,
                    screen: `${screen.width}x${screen.height}`,
                    language: navigator.language,
                    timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
                }
            })
        }).catch(error => {
            @if($debugMode)
            console.error('Custom analytics error:', error);
            @endif
        });
    };
    
    function getOrCreateSessionId() {
        let sessionId = sessionStorage.getItem('analytics_session_id');
        if (!sessionId) {
            sessionId = 'sess_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            sessionStorage.setItem('analytics_session_id', sessionId);
        }
        return sessionId;
    }
    
    function generateUserFingerprint() {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        ctx.textBaseline = 'top';
        ctx.font = '14px Arial';
        ctx.fillText('Analytics fingerprint', 2, 2);
        
        return btoa(JSON.stringify({
            screen: `${screen.width}x${screen.height}`,
            timezone: new Date().getTimezoneOffset(),
            language: navigator.language,
            platform: navigator.platform,
            plugins: Array.from(navigator.plugins).map(p => p.name),
            canvas: canvas.toDataURL(),
            webgl: getWebGLInfo()
        })).substring(0, 32);
    }
    
    function getWebGLInfo() {
        try {
            const canvas = document.createElement('canvas');
            const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
            if (gl) {
                const debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
                return {
                    vendor: gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL),
                    renderer: gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL)
                };
            }
        } catch (e) {
            return null;
        }
        return null;
    }
</script>
@endif

{{-- A/B Testing Integration --}}
@if($enableAbTesting)
<script>
    // A/B Testing configuration
    window.abTestConfig = {
        variant: '{{ $abTestingVariant }}',
        testName: 'template_variant_test',
        websiteId: '{{ $websiteId }}',
        startDate: '{{ $websiteContent->created_at ?? now()->format('Y-m-d') }}'
    };
    
    // Track A/B test exposure
    if (typeof trackEvent !== 'undefined') {
        trackEvent('ab_test_exposure', {
            category: 'A/B Testing',
            test_name: window.abTestConfig.testName,
            variant: window.abTestConfig.variant,
            website_id: window.abTestConfig.websiteId
        });
    }
    
    // Set A/B test data in all analytics platforms
    if (typeof gtag !== 'undefined') {
        gtag('set', {
            'custom_parameter_6': window.abTestConfig.variant
        });
    }
    
    @if(!empty($facebookPixel))
    if (typeof fbq !== 'undefined') {
        fbq('set', 'ab_variant', window.abTestConfig.variant);
    }
    @endif
    
    @if($debugMode)
    console.log('A/B Testing initialized:', window.abTestConfig);
    @endif
</script>
@endif

{{-- Debug Console (Development Only) --}}
@if($debugMode)
<script>
    console.group('🔍 Enhanced Analytics Debug Information');
    console.log('Company:', '{{ $companyName }}');
    console.log('Template:', '{{ $templateSlug }}');
    console.log('Website ID:', '{{ $websiteId }}');
    console.log('User ID:', '{{ $userId }}');
    console.log('Page Category:', '{{ $pageCategory }}');
    console.log('Content Group:', '{{ $contentGroup }}');
    
    console.group('📊 Analytics Platforms');
    console.log('Google Analytics:', '{{ $googleAnalytics ?: "Not configured" }}');
    console.log('Google Tag Manager:', '{{ $googleTagManager ?: "Not configured" }}');
    console.log('Facebook Pixel:', '{{ $facebookPixel ?: "Not configured" }}');
    console.log('Microsoft Clarity:', '{{ $microsoftClarity ?: "Not configured" }}');
    console.log('LinkedIn Insight:', '{{ $linkedInInsight ?: "Not configured" }}');
    console.log('TikTok Pixel:', '{{ $tiktokPixel ?: "Not configured" }}');
    console.groupEnd();
    
    console.group('⚙️ Tracking Features');
    console.log('Scroll Tracking:', {{ $enableScrollTracking ? 'true' : 'false' }});
    console.log('Click Tracking:', {{ $enableClickTracking ? 'true' : 'false' }});
    console.log('Form Tracking:', {{ $enableFormTracking ? 'true' : 'false' }});
    console.log('Download Tracking:', {{ $enableFileDownloadTracking ? 'true' : 'false' }});
    console.log('Video Tracking:', {{ $enableVideoTracking ? 'true' : 'false' }});
    console.log('Search Tracking:', {{ $enableSearchTracking ? 'true' : 'false' }});
    console.log('Custom Dimensions:', {{ $enableCustomDimensions ? 'true' : 'false' }});
    console.log('Ecommerce Tracking:', {{ $enableEcommerce ? 'true' : 'false' }});
    @if($enableAbTesting)
    console.log('A/B Testing:', 'Enabled - Variant {{ $abTestingVariant }}');
    @endif
    console.groupEnd();
    
    console.group('🌐 Page Information');
    console.log('Page URL:', '{{ $currentUrl }}');
    console.log('Page Title:', '{{ $pageTitle }}');
    console.log('Referrer:', document.referrer || 'Direct');
    console.log('User Agent:', navigator.userAgent);
    console.log('Language:', navigator.language);
    console.log('Timezone:', Intl.DateTimeFormat().resolvedOptions().timeZone);
    console.log('Screen Resolution:', `${screen.width}x${screen.height}`);
    console.log('Viewport:', `${window.innerWidth}x${window.innerHeight}`);
    console.log('Connection:', navigator.connection ? navigator.connection.effectiveType : 'Unknown');
    console.groupEnd();
    
    console.groupEnd();
    
    // Add debug panel toggle
    window.toggleAnalyticsDebug = function() {
        const debugPanel = document.getElementById('analytics-debug');
        if (debugPanel) {
            debugPanel.style.display = debugPanel.style.display === 'none' ? 'block' : 'none';
        }
    };
    
    // Keyboard shortcut for debug panel (Ctrl+Shift+A)
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'A') {
            e.preventDefault();
            toggleAnalyticsDebug();
        }
    });
    
    // Debug event logger
    const originalTrackEvent = window.trackEvent;
    if (originalTrackEvent) {
        window.trackEvent = function(eventName, eventData) {
            console.group('📈 Event: ' + eventName);
            console.log('Data:', eventData);
            console.log('Timestamp:', new Date().toISOString());
            console.groupEnd();
            return originalTrackEvent.call(this, eventName, eventData);
        };
    }
</script>
@endif

{{-- Performance mark for scripts end --}}
<script>
    // Mark analytics scripts loaded
    if ('performance' in window && 'mark' in window.performance) {
        window.performance.mark('analytics-scripts-loaded');
        window.performance.measure('analytics-load-time', 'navigationStart', 'analytics-scripts-loaded');
    }
    
    // Final initialization
    document.addEventListener('DOMContentLoaded', function() {
        // Track successful analytics initialization
        if (typeof trackEvent !== 'undefined') {
            trackEvent('analytics_initialized', {
                category: 'System',
                platforms_loaded: [
                    @if(!empty($googleAnalytics) || !empty($googleTagManager))
                    'google',
                    @endif
                    @if(!empty($facebookPixel))
                    'facebook',
                    @endif
                    @if(!empty($microsoftClarity))
                    'clarity',
                    @endif
                    @if(!empty($linkedInInsight))
                    'linkedin',
                    @endif
                    @if(!empty($tiktokPixel))
                    'tiktok',
                    @endif
                ].join(','),
                initialization_time: performance.now()
            });
        }
    });
    
    @if($debugMode)
    console.log('🚀 All enhanced analytics scripts loaded successfully');
    console.log('📊 Performance timing:', {
        'DOM Ready': performance.timing.domContentLoadedEventEnd - performance.timing.navigationStart + 'ms',
        'Analytics Load': performance.getEntriesByName('analytics-load-time')[0]?.duration + 'ms'
    });
    @endif
</script>