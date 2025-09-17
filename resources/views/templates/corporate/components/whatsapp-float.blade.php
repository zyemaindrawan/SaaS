@php
    // Check if WhatsApp feature is enabled
    if (empty($content['whatsapp_enabled']) || empty($content['whatsapp_number'])) {
        return;
    }
    
    // Get WhatsApp configuration
    $whatsappNumber = $content['whatsapp_number'];
    $companyName = $content['company_name'] ?? 'Kami';
    $defaultMessage = $content['whatsapp_message'] ?? "Halo {company_name}, saya tertarik dengan layanan Anda";
    $whatsappMessage = str_replace('{company_name}', $companyName, $defaultMessage);
    $encodedMessage = urlencode($whatsappMessage);
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$encodedMessage}";
    
    // Styling and positioning
    $position = $content['whatsapp_position'] ?? 'bottom-right';
    $whatsappColor = $content['whatsapp_color'] ?? '#25D366';
    $greetingText = $content['whatsapp_greeting_text'] ?? 'Butuh bantuan? Chat dengan kami!';
    $showGreeting = $content['whatsapp_show_greeting'] ?? true;
    $autoShow = $content['whatsapp_auto_show'] ?? false;
    $autoShowDelay = $content['whatsapp_auto_show_delay'] ?? 30; // seconds
    
    // Widget style options
    $widgetStyle = $content['whatsapp_widget_style'] ?? 'simple'; // simple, advanced, bubble
    $showAvatar = $content['whatsapp_show_avatar'] ?? true;
    $avatarImage = $content['whatsapp_avatar'] ?? null;
    $agentName = $content['whatsapp_agent_name'] ?? 'Customer Service';
    $onlineStatus = $content['whatsapp_online_status'] ?? 'online';
    
    // Animation and behavior
    $animation = $content['whatsapp_animation'] ?? 'pulse'; // pulse, shake, bounce, glow
    $hideOnDesktop = $content['whatsapp_hide_desktop'] ?? false;
    $hideOnMobile = $content['whatsapp_hide_mobile'] ?? false;
    
    // Working hours (optional)
    $workingHours = $content['whatsapp_working_hours'] ?? [];
    $timezone = $content['whatsapp_timezone'] ?? 'Asia/Jakarta';
    
    // Multiple agents support
    $agents = $content['whatsapp_agents'] ?? [];
    $multiAgent = !empty($agents) && count($agents) > 1;
@endphp

<!-- WhatsApp Float Styles -->
<style>
    :root {
        --whatsapp-color: {{ $whatsappColor }};
        --whatsapp-color-dark: #128C7E;
        --whatsapp-position-{{ $position === 'bottom-left' ? 'left' : 'right' }}: 20px;
        --whatsapp-position-bottom: 20px;
    }

    .whatsapp-container {
        position: fixed;
        {{ $position === 'bottom-left' ? 'left' : 'right' }}: var(--whatsapp-position-{{ $position === 'bottom-left' ? 'left' : 'right' }});
        bottom: var(--whatsapp-position-bottom);
        z-index: 9999;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
        
        @if($hideOnDesktop)
        @media (min-width: 768px) {
            display: none !important;
        }
        @endif
        
        @if($hideOnMobile)
        @media (max-width: 767px) {
            display: none !important;
        }
        @endif
    }

    /* Main WhatsApp Button */
    .whatsapp-button {
        position: relative;
        width: 60px;
        height: 60px;
        background: var(--whatsapp-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        font-size: 28px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
        border: none;
        outline: none;
    }

    .whatsapp-button:hover {
        background: var(--whatsapp-color-dark);
        color: white;
        text-decoration: none;
        transform: scale(1.1);
        box-shadow: 0 6px 25px rgba(37, 211, 102, 0.6);
    }

    .whatsapp-button:focus {
        outline: 2px solid #fff;
        outline-offset: 3px;
    }

    /* Animation Classes */
    .whatsapp-pulse {
        animation: whatsapp-pulse-animation 2s infinite;
    }

    .whatsapp-shake {
        animation: whatsapp-shake-animation 1s infinite;
    }

    .whatsapp-bounce {
        animation: whatsapp-bounce-animation 2s infinite;
    }

    .whatsapp-glow {
        animation: whatsapp-glow-animation 2s infinite alternate;
    }

    @keyframes whatsapp-pulse-animation {
        0% {
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
        }
        50% {
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4), 0 0 0 10px rgba(37, 211, 102, 0.1);
        }
        100% {
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
        }
    }

    @keyframes whatsapp-shake-animation {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    @keyframes whatsapp-bounce-animation {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }

    @keyframes whatsapp-glow-animation {
        from {
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
        }
        to {
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.8), 0 0 20px rgba(37, 211, 102, 0.3);
        }
    }

    /* Notification Badge */
    .whatsapp-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #ff4444;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        animation: whatsapp-badge-pulse 1.5s infinite;
    }

    @keyframes whatsapp-badge-pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    /* Greeting Tooltip */
    .whatsapp-greeting {
        position: absolute;
        {{ $position === 'bottom-left' ? 'left: 70px;' : 'right: 70px;' }}
        bottom: 10px;
        background: white;
        color: #333;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 14px;
        white-space: nowrap;
        max-width: 250px;
        white-space: normal;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.3s ease;
        z-index: 1;
        line-height: 1.4;
    }

    .whatsapp-greeting::before {
        content: '';
        position: absolute;
        top: 50%;
        {{ $position === 'bottom-left' ? 'left: -8px;' : 'right: -8px;' }}
        transform: translateY(-50%);
        border: 8px solid transparent;
        {{ $position === 'bottom-left' ? 'border-right-color: white;' : 'border-left-color: white;' }}
    }

    .whatsapp-greeting.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    /* Advanced Widget */
    .whatsapp-widget {
        position: absolute;
        {{ $position === 'bottom-left' ? 'left: 0;' : 'right: 0;' }}
        bottom: 80px;
        width: 320px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        overflow: hidden;
        transform: scale(0) translateY(50px);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 9998;
    }

    .whatsapp-widget.show {
        transform: scale(1) translateY(0);
        opacity: 1;
    }

    .whatsapp-widget-header {
        background: var(--whatsapp-color);
        color: white;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .whatsapp-agent-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .whatsapp-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .whatsapp-agent-details h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .whatsapp-status {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        opacity: 0.9;
        margin-top: 2px;
    }

    .whatsapp-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #4ade80;
    }

    .whatsapp-status-dot.offline {
        background: #94a3b8;
    }

    .whatsapp-close {
        background: none;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: background 0.2s;
    }

    .whatsapp-close:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .whatsapp-widget-body {
        padding: 20px;
    }

    .whatsapp-message {
        background: #f0f0f0;
        padding: 12px 16px;
        border-radius: 18px 18px 18px 4px;
        margin-bottom: 16px;
        font-size: 14px;
        line-height: 1.4;
        position: relative;
    }

    .whatsapp-message::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: -8px;
        border: 8px solid transparent;
        border-bottom-color: #f0f0f0;
        border-right-color: #f0f0f0;
    }

    .whatsapp-cta {
        background: var(--whatsapp-color);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .whatsapp-cta:hover {
        background: var(--whatsapp-color-dark);
    }

    /* Multi-agent Selection */
    .whatsapp-agents {
        max-height: 200px;
        overflow-y: auto;
    }

    .whatsapp-agent-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: background 0.2s;
    }

    .whatsapp-agent-item:hover {
        background: #f8f9fa;
    }

    .whatsapp-agent-item:last-child {
        border-bottom: none;
    }

    /* Working Hours Notice */
    .whatsapp-hours-notice {
        background: #fff3cd;
        color: #856404;
        padding: 12px;
        font-size: 12px;
        text-align: center;
        border-bottom: 1px solid #ffeaa7;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .whatsapp-container {
            {{ $position === 'bottom-left' ? 'left' : 'right' }}: 15px;
            bottom: 15px;
        }

        .whatsapp-button {
            width: 55px;
            height: 55px;
            font-size: 24px;
        }

        .whatsapp-widget {
            width: calc(100vw - 30px);
            max-width: 320px;
        }

        .whatsapp-greeting {
            max-width: 200px;
            {{ $position === 'bottom-left' ? 'left: 65px;' : 'right: 65px;' }}
        }
    }

    @media (max-width: 480px) {
        .whatsapp-widget {
            {{ $position === 'bottom-left' ? 'left: -15px;' : 'right: -15px;' }}
            width: calc(100vw - 30px);
        }
    }

    /* Print Hide */
    @media print {
        .whatsapp-container {
            display: none !important;
        }
    }

    /* Accessibility */
    @media (prefers-reduced-motion: reduce) {
        .whatsapp-button,
        .whatsapp-greeting,
        .whatsapp-widget,
        .whatsapp-badge {
            animation: none !important;
            transition: none !important;
        }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .whatsapp-greeting,
        .whatsapp-widget {
            background: #1f2937;
            color: #f9fafb;
        }
        
        .whatsapp-message {
            background: #374151;
        }
        
        .whatsapp-agent-item:hover {
            background: #374151;
        }
    }
</style>

<!-- WhatsApp Float Container -->
<div class="whatsapp-container" id="whatsapp-container">
    <!-- Main WhatsApp Button -->
    @if($widgetStyle === 'simple')
        <a 
            href="{{ $whatsappUrl }}" 
            target="_blank" 
            rel="noopener noreferrer"
            class="whatsapp-button whatsapp-{{ $animation }}"
            id="whatsapp-button"
            aria-label="Chat dengan kami di WhatsApp"
            onclick="trackWhatsAppClick('direct')"
        >
            <i class="fab fa-whatsapp"></i>
            
            @if($content['whatsapp_show_badge'] ?? false)
                <span class="whatsapp-badge">1</span>
            @endif
        </a>
    @else
        <button 
            class="whatsapp-button whatsapp-{{ $animation }}"
            id="whatsapp-toggle"
            aria-label="Buka chat WhatsApp"
            onclick="toggleWhatsAppWidget()"
        >
            <i class="fab fa-whatsapp"></i>
            
            @if($content['whatsapp_show_badge'] ?? false)
                <span class="whatsapp-badge">1</span>
            @endif
        </button>
    @endif

    <!-- Greeting Tooltip -->
    @if($showGreeting && $greetingText)
        <div class="whatsapp-greeting" id="whatsapp-greeting">
            {{ $greetingText }}
        </div>
    @endif

    <!-- Advanced Widget -->
    @if($widgetStyle !== 'simple')
        <div class="whatsapp-widget" id="whatsapp-widget">
            <!-- Working Hours Notice -->
            @if(!empty($workingHours))
                <div class="whatsapp-hours-notice" id="hours-notice" style="display: none;">
                    <i class="fas fa-clock mr-2"></i>
                    <span id="hours-text"></span>
                </div>
            @endif

            <!-- Widget Header -->
            <div class="whatsapp-widget-header">
                <div class="whatsapp-agent-info">
                    @if($showAvatar)
                        <img 
                            src="{{ $avatarImage ? asset('storage/' . $avatarImage) : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiNmM2Y0ZjYiLz4KPHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDEyYzIuMjEgMCA0LTEuNzkgNC00cy0xLjc5LTQtNC00LTQgMS43OS00IDQgMS43OSA0IDQgNHptMCAyYy0yLjY3IDAtOCAxLjM0LTggNHYyaDE2di0yYzAtMi42Ni01LjMzLTQtOC00eiIgZmlsbD0iIzlDQTNBRiIvPgo8L3N2Zz4KPC9zdmc+' }}" 
                            alt="{{ $agentName }}"
                            class="whatsapp-avatar"
                        >
                    @endif
                    
                    <div class="whatsapp-agent-details">
                        <h4>{{ $agentName }}</h4>
                        <div class="whatsapp-status">
                            <span class="whatsapp-status-dot {{ $onlineStatus === 'online' ? '' : 'offline' }}"></span>
                            <span>{{ $onlineStatus === 'online' ? 'Online' : 'Offline' }}</span>
                            <span id="response-time">• Biasanya membalas dalam beberapa menit</span>
                        </div>
                    </div>
                </div>
                
                <button class="whatsapp-close" onclick="closeWhatsAppWidget()" aria-label="Tutup chat">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Widget Body -->
            <div class="whatsapp-widget-body">
                @if($multiAgent)
                    <!-- Multi-agent Selection -->
                    <div class="whatsapp-agents">
                        @foreach($agents as $agent)
                            <div class="whatsapp-agent-item" onclick="selectAgent('{{ $agent['number'] }}', '{{ $agent['name'] }}', '{{ $agent['message'] ?? $whatsappMessage }}')">
                                @if(!empty($agent['avatar']))
                                    <img src="{{ asset('storage/' . $agent['avatar']) }}" alt="{{ $agent['name'] }}" class="whatsapp-avatar" style="width: 32px; height: 32px;">
                                @endif
                                <div>
                                    <div style="font-weight: 600; font-size: 14px;">{{ $agent['name'] }}</div>
                                    <div style="font-size: 12px; opacity: 0.7;">{{ $agent['department'] ?? 'Customer Service' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Single Agent Message -->
                    <div class="whatsapp-message">
                        <strong>{{ $companyName }}</strong><br>
                        {{ $greetingText }}
                    </div>
                    
                    <div class="whatsapp-message" style="background: #dcf8c6; margin-left: 20px; border-radius: 18px 18px 4px 18px;">
                        {{ $whatsappMessage }}
                    </div>
                    
                    <button class="whatsapp-cta" onclick="startWhatsAppChat('{{ $whatsappUrl }}')">
                        <i class="fab fa-whatsapp"></i>
                        <span>Mulai Chat</span>
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- WhatsApp JavaScript -->
<script>
    // WhatsApp Widget Configuration
    const whatsappConfig = {
        number: '{{ $whatsappNumber }}',
        message: '{{ $whatsappMessage }}',
        companyName: '{{ $companyName }}',
        autoShow: {{ $autoShow ? 'true' : 'false' }},
        autoShowDelay: {{ $autoShowDelay }} * 1000,
        greetingDelay: 3000,
        workingHours: @json($workingHours),
        timezone: '{{ $timezone }}',
        widgetStyle: '{{ $widgetStyle }}'
    };

    let whatsappWidgetOpen = false;
    let greetingShown = false;
    let autoShowTriggered = false;

    // Initialize WhatsApp Widget
    document.addEventListener('DOMContentLoaded', function() {
        initializeWhatsApp();
    });

    function initializeWhatsApp() {
        // Check working hours
        checkWorkingHours();
        
        // Auto-show greeting
        if (whatsappConfig.widgetStyle === 'simple') {
            setTimeout(showGreeting, whatsappConfig.greetingDelay);
        }
        
        // Auto-show widget
        if (whatsappConfig.autoShow && !localStorage.getItem('whatsapp_widget_dismissed')) {
            setTimeout(function() {
                if (!autoShowTriggered && !whatsappWidgetOpen) {
                    if (whatsappConfig.widgetStyle === 'simple') {
                        showGreeting();
                    } else {
                        toggleWhatsAppWidget();
                    }
                    autoShowTriggered = true;
                }
            }, whatsappConfig.autoShowDelay);
        }

        // Hide greeting on scroll (mobile UX)
        let scrollTimeout;
        window.addEventListener('scroll', function() {
            const greeting = document.getElementById('whatsapp-greeting');
            if (greeting && greeting.classList.contains('show')) {
                greeting.classList.remove('show');
                
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(function() {
                    if (!whatsappWidgetOpen) {
                        greeting.classList.add('show');
                    }
                }, 2000);
            }
        });

        // Click outside to close widget
        document.addEventListener('click', function(event) {
            const container = document.getElementById('whatsapp-container');
            if (whatsappWidgetOpen && !container.contains(event.target)) {
                closeWhatsAppWidget();
            }
        });

        // Escape key to close widget
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && whatsappWidgetOpen) {
                closeWhatsAppWidget();
            }
        });
    }

    function showGreeting() {
        const greeting = document.getElementById('whatsapp-greeting');
        if (greeting && !greetingShown && !whatsappWidgetOpen) {
            greeting.classList.add('show');
            greetingShown = true;
            
            // Hide greeting after 5 seconds
            setTimeout(function() {
                if (!whatsappWidgetOpen) {
                    greeting.classList.remove('show');
                }
            }, 5000);
        }
    }

    function toggleWhatsAppWidget() {
        const widget = document.getElementById('whatsapp-widget');
        const greeting = document.getElementById('whatsapp-greeting');
        
        if (!widget) return;
        
        whatsappWidgetOpen = !whatsappWidgetOpen;
        
        if (whatsappWidgetOpen) {
            widget.classList.add('show');
            if (greeting) greeting.classList.remove('show');
            
            // Track widget open
            trackWhatsAppClick('widget_open');
            
            // Focus management for accessibility
            const closeButton = widget.querySelector('.whatsapp-close');
            if (closeButton) closeButton.focus();
            
        } else {
            widget.classList.remove('show');
        }
    }

    function closeWhatsAppWidget() {
        const widget = document.getElementById('whatsapp-widget');
        const button = document.getElementById('whatsapp-toggle');
        
        if (widget && whatsappWidgetOpen) {
            widget.classList.remove('show');
            whatsappWidgetOpen = false;
            
            // Return focus to trigger button
            if (button) button.focus();
            
            // Mark as dismissed to prevent auto-show
            localStorage.setItem('whatsapp_widget_dismissed', 'true');
            
            trackWhatsAppClick('widget_close');
        }
    }

    function startWhatsAppChat(customUrl = null) {
        const url = customUrl || `https://wa.me/${whatsappConfig.number}?text=${encodeURIComponent(whatsappConfig.message)}`;
        
        // Track chat start
        trackWhatsAppClick('chat_start');
        
        // Open WhatsApp
        window.open(url, '_blank', 'noopener,noreferrer');
        
        // Close widget after starting chat
        if (whatsappWidgetOpen) {
            closeWhatsAppWidget();
        }
    }

    function selectAgent(number, name, message) {
        const url = `https://wa.me/${number}?text=${encodeURIComponent(message)}`;
        
        trackWhatsAppClick('agent_select', {
            agent_name: name,
            agent_number: number
        });
        
        startWhatsAppChat(url);
    }

    function checkWorkingHours() {
        if (!whatsappConfig.workingHours || Object.keys(whatsappConfig.workingHours).length === 0) {
            return;
        }

        const now = new Date();
        const options = { timeZone: whatsappConfig.timezone };
        const currentDay = now.toLocaleDateString('en-US', { ...options, weekday: 'long' }).toLowerCase();
        const currentTime = now.toLocaleTimeString('en-US', { 
            ...options, 
            hour12: false, 
            hour: '2-digit', 
            minute: '2-digit' 
        });

        const todayHours = whatsappConfig.workingHours[currentDay];
        const hoursNotice = document.getElementById('hours-notice');
        const hoursText = document.getElementById('hours-text');
        const statusDot = document.querySelector('.whatsapp-status-dot');
        const responseTime = document.getElementById('response-time');

        if (!todayHours || !hoursNotice || !hoursText) return;

        const [openTime, closeTime] = todayHours.split('-');
        const isOpen = currentTime >= openTime && currentTime <= closeTime;

        if (!isOpen) {
            hoursNotice.style.display = 'block';
            if (statusDot) statusDot.classList.add('offline');
            
            if (currentTime < openTime) {
                hoursText.textContent = `Kami akan online pada ${openTime}`;
            } else {
                hoursText.textContent = `Kami sudah offline hari ini. Buka kembali besok ${openTime}`;
            }
            
            if (responseTime) {
                responseTime.textContent = '• Akan membalas saat jam kerja';
            }
        } else {
            hoursNotice.style.display = 'none';
            if (statusDot) statusDot.classList.remove('offline');
        }
    }

    // Analytics tracking
    function trackWhatsAppClick(action, additionalData = {}) {
        const eventData = {
            category: 'WhatsApp',
            action: action,
            company_name: whatsappConfig.companyName,
            whatsapp_number: whatsappConfig.number,
            widget_style: whatsappConfig.widgetStyle,
            timestamp: new Date().toISOString(),
            page_url: window.location.href,
            ...additionalData
        };

        // Google Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'whatsapp_interaction', {
                event_category: 'WhatsApp',
                event_label: action,
                custom_parameter_1: whatsappConfig.companyName,
                custom_parameter_2: action
            });
        }

        // Custom event tracking
        if (typeof trackEvent !== 'undefined') {
            trackEvent('whatsapp_' + action, eventData);
        }

        // Facebook Pixel
        if (typeof fbq !== 'undefined') {
            fbq('track', 'Contact', {
                content_name: 'WhatsApp Chat',
                content_category: 'Customer Support'
            });
        }

        console.log('WhatsApp interaction tracked:', action, eventData);
    }

    // Visibility API - Track when user returns to tab
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && !whatsappWidgetOpen && !autoShowTriggered) {
            // User returned to tab, show greeting again
            setTimeout(showGreeting, 2000);
        }
    });

    // Mobile-specific behavior
    if (window.innerWidth <= 768) {
        // Reduce auto-show delay on mobile
        whatsappConfig.autoShowDelay = Math.min(whatsappConfig.autoShowDelay, 20000);
        
        // Show greeting on first scroll
        let firstScroll = false;
        window.addEventListener('scroll', function() {
            if (!firstScroll && window.scrollY > 100) {
                firstScroll = true;
                setTimeout(showGreeting, 1000);
            }
        }, { once: true });
    }

    // Performance: Only load heavy features if widget is visible
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Widget is visible, can load additional features
                loadAdditionalFeatures();
            }
        });
    }, { threshold: 0.1 });

    const container = document.getElementById('whatsapp-container');
    if (container) {
        observer.observe(container);
    }

    function loadAdditionalFeatures() {
        // Load additional features like typing indicators, message templates, etc.
        console.log('WhatsApp widget is visible, loading additional features...');
    }

    // Global functions for direct usage
    window.whatsappWidget = {
        show: toggleWhatsAppWidget,
        hide: closeWhatsAppWidget,
        startChat: startWhatsAppChat,
        showGreeting: showGreeting
    };
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CustomerService",
    "serviceType": "WhatsApp Customer Support",
    "provider": {
        "@type": "Organization",
        "name": "{{ $companyName }}"
    },
    "availableChannel": {
        "@type": "ServiceChannel",
        "serviceUrl": "{{ $whatsappUrl }}",
        "serviceName": "WhatsApp Chat",
        "servicePhone": "{{ $whatsappNumber }}"
    }
    @if(!empty($workingHours))
    ,"hoursAvailable": [
        @foreach($workingHours as $day => $hours)
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": "{{ ucfirst($day) }}",
                "opens": "{{ explode('-', $hours)[0] ?? '09:00' }}",
                "closes": "{{ explode('-', $hours)[1] ?? '17:00' }}"
            }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ]
    @endif
}
</script>
