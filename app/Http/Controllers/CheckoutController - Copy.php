<?php
namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\WebsiteContent;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->middleware('auth');
        $this->middleware('verified');
        $this->paymentService = $paymentService;
    }

    public function show(Template $template)
    {
        if (!$template->is_active) {
            abort(404);
        }

        $user = Auth::user();

        // Check if user already has a website content with this template in draft/previewed status
        $existingContent = WebsiteContent::where('user_id', $user->id)
            ->where('template_slug', $template->slug)
            ->whereIn('status', ['draft', 'previewed'])
            ->first();

        // if ($existingContent) {
        //     return redirect()->route('content.edit', $existingContent)
        //         ->with('info', 'You already have a draft with this template. Please complete it first.');
        // }

        // Calculate pricing
        $subtotal = $template->price;
        //$platformFee = ($subtotal * 0.029) + 2000; // 2.9% + Rp 2,000
        $platformFee = 4000; // Rp 4,000
        $total = $subtotal + $platformFee;

        return view('checkout.index', compact('template', 'user', 'subtotal', 'platformFee', 'total'));
    }

    public function process(Request $request, Template $template)
    {
        if (!$template->is_active) {
            abort(404);
        }

        $user = Auth::user();
        
        // Enhanced session and authentication debugging
        \Log::info('Checkout process - Initial state', [
            'user_authenticated' => !!$user,
            'user_id' => $user ? $user->id : null,
            'session_id' => $request->session()->getId(),
            'has_session' => $request->hasSession(),
            'csrf_token_present' => !!$request->session()->token(),
            'template_slug' => $template->slug,
            'request_method' => $request->method(),
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip()
        ]);
        
        // Check if user is still authenticated
        if (!$user) {
            \Log::warning('User not authenticated during checkout', [
                'session_id' => $request->session()->getId(),
                'has_session' => $request->hasSession(),
                'csrf_token' => $request->session()->token(),
                'session_data' => $request->session()->all()
            ]);
            return redirect()->route('login')
                ->with('error', 'Your session has expired. Please log in again.');
        }
        
        // Log session info for debugging
        \Log::info('Checkout process - Session info', [
            'user_id' => $user->id,
            'session_id' => $request->session()->getId(),
            'template_slug' => $template->slug,
            'form_data_keys' => array_keys($request->except(['_token'])),
            'csrf_valid' => $request->session()->token() === $request->input('_token')
        ]);

        try {
            // Validation
            \Log::info('Starting form validation', [
                'user_id' => $user->id,
                'data_keys' => array_keys($request->except(['_token']))
            ]);
            
            $validated = $request->validate([
                // Company Information
                'company_name' => 'required|string|max:255',
                'company_tagline' => 'nullable|string|max:1000',
                
                // SEO Fields
                'seo_title' => 'required|string|max:500',
                'seo_description' => 'required|string|max:1000',
                'seo_keywords' => 'nullable|string|max:500',
                'robots_meta' => 'nullable|string|max:100',
                'google_analytics' => 'nullable|string|max:100',
                'google_tag_manager' => 'nullable|string|max:100',
                
                // Contact Information
                'contact_email' => 'required|email|max:255',
                'contact_phone' => 'required|string|max:20',
                'contact_address' => 'required|string|max:500',
                
                // Hero & Content Sections
                'hero_title' => 'nullable|string|max:255',
                'hero_subtitle' => 'nullable|string|max:1000',
                'hero_cta_text' => 'nullable|string|max:100',
                'hero_cta_link' => 'nullable|string|max:255',
                'about_title' => 'nullable|string|max:255',
                'about_content' => 'required|string',
                'contact_title' => 'nullable|string|max:255',
                'services_title' => 'nullable|string|max:255',
                'services_subtitle' => 'nullable|string|max:1000',
                'testimonials_title' => 'nullable|string|max:255',
                'gallery_title' => 'nullable|string|max:255',
                
                // Design & Styling
                'font_family' => 'nullable|string|max:255',
                'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'accent_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'border_radius' => 'nullable|string|max:50',
                
                // WhatsApp Settings
                'whatsapp_enabled' => 'nullable|boolean',
                'whatsapp_number' => 'nullable|string|max:20',
                'whatsapp_message' => 'nullable|string|max:1000',
                'whatsapp_position' => 'nullable|string|max:50',
                'whatsapp_greeting_text' => 'nullable|string|max:100',
                
                // Website Settings
                'subdomain' => 'required|string|min:3|max:50|regex:/^[a-z0-9-]+$/|unique:website_contents,subdomain',
                
                // Repeater Fields
                'services' => 'nullable|array',
                'services.*.icon' => 'nullable|string|max:100',
                'services.*.link' => 'nullable|string|max:255',
                'services.*.title' => 'required_with:services.*|string|max:255',
                'services.*.description' => 'nullable|string',
                
                'testimonials' => 'nullable|array',
                'testimonials.*.name' => 'required_with:testimonials.*|string|max:255',
                'testimonials.*.position' => 'nullable|string|max:255',
                'testimonials.*.rating' => 'nullable|integer|min:1|max:5',
                'testimonials.*.content' => 'required_with:testimonials.*|string',
                'testimonials.*.photo' => 'nullable|string|max:500',
                
                'social_links' => 'nullable|array',
                'social_links.*.platform' => 'required_with:social_links.*|string|max:50',
                'social_links.*.url' => 'required_with:social_links.*|url|max:500',
                'social_links.*.label' => 'nullable|string|max:255',
                
                'company_stats' => 'nullable|array',
                'company_stats.*.icon' => 'nullable|string|max:100',
                'company_stats.*.number' => 'required_with:company_stats.*|string|max:50',
                'company_stats.*.label' => 'required_with:company_stats.*|string|max:255',
                
                'gallery_photos' => 'nullable|array',
                'gallery_photos.*' => 'nullable|url|max:500',
                
                // Terms
                'agree_terms' => 'required|accepted',
            ], [
                'subdomain.unique' => 'This subdomain is already taken. Please choose another one.',
                'subdomain.regex' => 'Subdomain can only contain lowercase letters, numbers, and hyphens.',
                'primary_color.regex' => 'Please select a valid color.',
                'secondary_color.regex' => 'Please select a valid color.',
                'accent_color.regex' => 'Please select a valid color.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Validation failed', [
                'user_id' => $user->id,
                'template_slug' => $template->slug,
                'validation_errors' => $e->errors(),
                'session_id' => $request->session()->getId()
            ]);
            
            return back()->withErrors($e->errors())->withInput()
                ->with('error', 'Terdapat kesalahan dalam data yang diisi. Silakan periksa kembali.');
        }

        //dd($validated);
        
        \Log::info('Form validation passed', [
            'user_id' => $user->id,
            'validated_data_count' => count($validated),
            'company_name' => $validated['company_name'],
            'subdomain' => $validated['subdomain']
        ]);

        try {
            // Prepare website content data
            $contentData = [
                // Company Information & SEO
                'company_name' => $validated['company_name'],
                'company_tagline' => $validated['company_tagline'] ?? '',
                'seo_title' => $validated['seo_title'],
                'seo_description' => $validated['seo_description'],
                'seo_keywords' => $validated['seo_keywords'] ?? '',
                'robots_meta' => $validated['robots_meta'] ?? 'index,follow',
                'google_analytics' => $validated['google_analytics'] ?? '',
                'google_tag_manager' => $validated['google_tag_manager'] ?? '',

                // Contact & WhatsApp
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'],
                'contact_address' => $validated['contact_address'],
                'whatsapp_enabled' => (bool) ($validated['whatsapp_enabled'] ?? false),
                'whatsapp_number' => $validated['whatsapp_number'] ?? '',
                'whatsapp_message' => $validated['whatsapp_message'] ?? 'Halo, saya ingin konsultasi...',
                'whatsapp_position' => $validated['whatsapp_position'] ?? 'bottom-right',
                'whatsapp_greeting_text' => $validated['whatsapp_greeting_text'] ?? 'Chat Us',
                'whatsapp_color' => '#25D366',

                // Design & Branding
                'primary_color' => $validated['primary_color'],
                'secondary_color' => $validated['secondary_color'] ?? '#64748b',
                'accent_color' => $validated['accent_color'] ?? '#f59e0b',
                'font_family' => $validated['font_family'] ?? 'inter',
                'border_radius' => $validated['border_radius'] ?? 'medium',

                // Hero Section
                'hero_title' => $validated['hero_title'] ?? 'Solusi Bisnis Terbaik',
                'hero_subtitle' => $validated['hero_subtitle'] ?? 'Kami memberikan layanan terbaik untuk mengembangkan bisnis Anda dengan teknologi modern dan strategi yang tepat sasaran',
                'hero_cta_text' => $validated['hero_cta_text'] ?? 'Konsultasi Gratis',
                'hero_cta_link' => $validated['hero_cta_link'] ?? '#contact',
                'hero_background' => 'hero/hero-bg-corporate.jpg',

                // About Section
                'about_title' => $validated['about_title'] ?? 'Tentang Kami',
                'about_content' => $validated['about_content'] ?? '<p>PT Corporate Indonesia adalah perusahaan konsultan bisnis terdepan yang telah berpengalaman lebih dari 10 tahun dalam memberikan solusi bisnis terpadu. Kami memiliki komitmen untuk membantu perusahaan mencapai kesuksesan melalui strategi yang inovatif dan berkelanjutan.</p><p>Dengan tim ahli yang berpengalaman dan teknologi terdepan, kami siap menjadi partner strategis dalam transformasi digital dan pengembangan bisnis perusahaan Anda.</p>',
                'about_image' => 'about/about-corporate-team.jpg',

                // Contact Section
                'contact_title' => $validated['contact_title'] ?? 'Hubungi Kami',

                // Services Section
                'services_title' => $validated['services_title'] ?? 'Layanan Unggulan Kami',
                'services_subtitle' => $validated['services_subtitle'] ?? 'Kami menyediakan berbagai layanan profesional untuk membantu mengembangkan bisnis Anda dengan solusi yang inovatif dan terpercaya',

                // Testimonials Section
                'testimonials_title' => $validated['testimonials_title'] ?? 'Testimoni Klien',

                // Gallery Section
                'gallery_title' => $validated['gallery_title'] ?? 'Portfolio & Galeri',

                // Images
                'favicon' => 'branding/favicon-corporate.ico',
                'og_image' => 'seo/og-corporate-indonesia.jpg',
                'company_logo' => '/logoipsum.png',

                // Initialize empty arrays for repeater fields
                'services' => [],
                'testimonials' => [],
                'social_links' => [],
                'company_stats' => [],
                'gallery_photos' => [],
            ];

            // Handle Repeater Fields
            
            // Services
            if (isset($validated['services']) && is_array($validated['services'])) {
                foreach ($validated['services'] as $service) {
                    if (!empty($service['title'])) {
                        $contentData['services'][] = [
                            'icon' => $service['icon'] ?? 'fas fa-chart-line',
                            'link' => $service['link'] ?? '#',
                            'title' => $service['title'],
                            'description' => $service['description'] ?? '',
                        ];
                    }
                }
            }

            // Testimonials
            if (isset($validated['testimonials']) && is_array($validated['testimonials'])) {
                foreach ($validated['testimonials'] as $testimonial) {
                    if (!empty($testimonial['name']) && !empty($testimonial['content'])) {
                        $contentData['testimonials'][] = [
                            'name' => $testimonial['name'],
                            'position' => $testimonial['position'] ?? '',
                            'rating' => $testimonial['rating'] ?? '5',
                            'content' => $testimonial['content'],
                            'photo' => $testimonial['photo'] ?? '',
                        ];
                    }
                }
            }

            // Social Links
            if (isset($validated['social_links']) && is_array($validated['social_links'])) {
                foreach ($validated['social_links'] as $link) {
                    if (!empty($link['url']) && !empty($link['platform'])) {
                        $contentData['social_links'][] = [
                            'url' => $link['url'],
                            'label' => $link['label'] ?? $link['platform'],
                            'platform' => $link['platform'],
                        ];
                    }
                }
            }

            // Company Stats
            if (isset($validated['company_stats']) && is_array($validated['company_stats'])) {
                foreach ($validated['company_stats'] as $stat) {
                    if (!empty($stat['label']) && !empty($stat['number'])) {
                        $contentData['company_stats'][] = [
                            'icon' => $stat['icon'] ?? 'fas fa-users',
                            'label' => $stat['label'],
                            'number' => $stat['number'],
                        ];
                    }
                }
            }

            // Gallery Photos
            if (isset($validated['gallery_photos']) && is_array($validated['gallery_photos'])) {
                foreach ($validated['gallery_photos'] as $photo) {
                    if (!empty($photo)) {
                        $contentData['gallery_photos'][] = $photo;
                    }
                }
            }
            $websiteContent = WebsiteContent::create([
                'user_id' => $user->id,
                'template_slug' => $template->slug,
                'website_name' => $validated['company_name'],
                'content_data' => $contentData,
                'status' => 'draft',
                'subdomain' => $validated['subdomain'],
                'expires_at' => now()->addYear(),
            ]);
            
            \Log::info('Website content created successfully', [
                'user_id' => $user->id,
                'website_content_id' => $websiteContent->id,
                'template_slug' => $template->slug,
                'subdomain' => $websiteContent->subdomain
            ]);

            // If template is free, skip payment
            if ($template->price == 0) {
                $websiteContent->update(['status' => 'paid']);
                
                \Log::info('Free template activated', [
                    'user_id' => $user->id,
                    'website_content_id' => $websiteContent->id
                ]);
                
                return redirect()->route('dashboard')
                    ->with('success', 'Free template activated successfully! Your website is being prepared.');
            }

            // Create payment and redirect
            $payment = $this->paymentService->createPayment($websiteContent);
            
            \Log::info('Payment created, redirecting to payment page', [
                'user_id' => $user->id,
                'website_content_id' => $websiteContent->id,
                'payment_code' => $payment->code
            ]);
            
            return redirect()->route('payment.show', $payment->code)
                ->with('success', 'Website content saved! Please complete your payment.');

        } catch (\Exception $e) {
            \Log::error('Checkout process failed', [
                'user_id' => $user ? $user->id : 'null',
                'template_slug' => $template->slug,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'session_id' => $request->session()->getId()
            ]);
            
            // Store error in session for user feedback
            $request->session()->flash('checkout_error', [
                'message' => $e->getMessage(),
                'time' => now()->toISOString()
            ]);
            
            return back()->withInput()->with('error', 'Something went wrong during checkout. Please try again. Error: ' . $e->getMessage());
        }
    }
}
