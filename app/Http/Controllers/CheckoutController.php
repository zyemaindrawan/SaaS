<?php
namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\WebsiteContent;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Voucher;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function show(Template $template)
    {
        if (!$template->is_active) {
            abort(404);
        }

        $user = Auth::user();

        // Check existing draft
        $existingContent = WebsiteContent::where('user_id', $user->id)
            ->where('template_slug', $template->slug)
            ->whereIn('status', ['draft', 'previewed'])
            ->first();

        if ($existingContent) {
            return redirect()->route('content.edit', $existingContent)
                ->with('info', 'You already have a draft with this template. Please complete it first.');
        }

        // Calculate pricing
        $subtotal = $template->price;
        $platformFee = 4000;
        $total = $subtotal + $platformFee;

        // Get dynamic form fields based on template
        $formFields = $this->getTemplateFormFields($template);
        //dd($formFields);

        return Inertia::render('Checkout/Index', [
            'template' => [
                'id' => $template->id,
                'name' => $template->name,
                'slug' => $template->slug,
                'description' => $template->description,
                'category' => $template->category,
                'preview_image' => $template->preview_image,
                'price' => $template->price,
            ],
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
            'pricing' => [
                'subtotal' => $subtotal,
                'platform_fee' => $platformFee,
                'total' => $total,
            ],
            'form_fields' => $formFields,
            'breadcrumbs' => [
                ['name' => 'Templates', 'href' => '/templates'],
                ['name' => $template->name, 'href' => "/templates/{$template->slug}"],
                ['name' => 'Checkout', 'href' => null],
            ]
        ]);
    }

    public function process(Request $request, Template $template)
    {
        if (!$template->is_active) {
            abort(404);
        }

        $user = Auth::user();
        $validationRules = $this->getValidationRules($template);
        $validationRules['voucher_code'] = 'nullable|string|max:255';
        
        try {
            $validated = $request->validate($validationRules);
        } catch (ValidationException $e) {
            Log::warning('Form validation failed', [
                'user_id' => $user->id,
                'template' => $template->slug,
                'errors' => $e->errors()
            ]);
            
            return back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Mohon periksa kembali form anda. Beberapa field wajib belum diisi dengan benar.');
        }

        // Calculate pricing
        $subtotal = $template->price;
        $platformFee = 4000;
        $discount = 0;
        $voucherId = null;

        // Handle voucher logic
        if (!empty($validated['voucher_code'])) {
            $voucher = Voucher::where('code', strtoupper(trim($validated['voucher_code'])))
                ->active()
                ->first();

            if (!$voucher) {
                throw ValidationException::withMessages([
                    'voucher_code' => 'The provided voucher is invalid or has expired.',
                ]);
            }

            // Check minimum purchase requirement
            if ($voucher->min_purchase && $subtotal < $voucher->min_purchase) {
                throw ValidationException::withMessages([
                    'voucher_code' => 'Minimum purchase amount of Rp ' . number_format($voucher->min_purchase, 0, ',', '.') . ' is required for this voucher.',
                ]);
            }

            // Calculate discount using the model method
            $discount = $voucher->calculateDiscount($subtotal);
            $voucherId = $voucher->id;
        }

        // Calculate total after discount
        $total = $subtotal + $platformFee - $discount;
        $total = max(0, $total); // Ensure total is not negative

        DB::beginTransaction();

        try {
            try {
                // Log::info('Starting website content creation', [
                //     'user_id' => $user->id,
                //     'template_slug' => $template->slug,
                //     'validated_data' => array_keys($validated)
                // ]);

                // Process content data first
                $contentData = $this->processContentData($validated, $template);
                
                // Validate required fields in content data
                if (empty($validated['company_name'])) {
                    throw ValidationException::withMessages(['company_name' => 'Nama perusahaan wajib diisi']);
                }
                
                if (empty($validated['website_name'])) {
                    throw ValidationException::withMessages(['website_name' => 'Nama website wajib diisi']);
                }
                
                if (empty($validated['subdomain'])) {
                    throw ValidationException::withMessages(['subdomain' => 'Subdomain wajib diisi']);
                }

                // Create website content
                $websiteContent = WebsiteContent::create([
                    'user_id' => $user->id,
                    'template_slug' => $template->slug,
                    'website_name' => $validated['website_name'],
                    'content_data' => $contentData,
                    'status' => 'draft',
                    'subdomain' => $validated['subdomain'],
                    'expires_at' => now()->addYear(),
                ]);

                // Log::info('Website content created successfully', [
                //     'website_content_id' => $websiteContent->id
                // ]);
            } catch (\Exception $e) {
                Log::error('Failed to create website content', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'validated_data' => $validated
                ]);
                throw $e;
            }

            try {
                // Load the template relationship to ensure it's available
                $websiteContent->load('template');

                // Verify the website content was created properly
                if (!$websiteContent->template) {
                    Log::error('Template relationship not loaded', [
                        'website_content_id' => $websiteContent->id,
                        'template_slug' => $template->slug
                    ]);
                    throw new \Exception('Website content template relationship not loaded properly');
                }

                // Log::info('Template relationship loaded successfully', [
                //     'website_content_id' => $websiteContent->id,
                //     'template_id' => $websiteContent->template->id
                // ]);
            } catch (\Exception $e) {
                Log::error('Failed to load template relationship', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

            // Handle free templates or if total is 0 after discount
            if ($total <= 0) {
                $websiteContent->update(['status' => 'paid']);

                DB::commit();

                // For free templates, redirect to content edit page
                return redirect()->route('content.edit', $websiteContent->id)
                    ->with('success', 'Template activated successfully!');
            }

            try {
                // Create payment with Midtrans
                $pricingDetails = [
                    'subtotal' => $subtotal,
                    'platform_fee' => $platformFee,
                    'discount' => $discount,
                    'total' => $total,
                    'voucher_code' => !empty($validated['voucher_code']) ? $validated['voucher_code'] : null,
                ];
                
                // Log::info('Creating payment', [
                //     'website_content_id' => $websiteContent->id,
                //     'pricing_details' => $pricingDetails
                // ]);

                $payment = $this->paymentService->createPayment($websiteContent, $pricingDetails);

                // Log::info('Payment created successfully', [
                //     'payment_id' => $payment->id,
                //     'payment_code' => $payment->code
                // ]);
            } catch (\Exception $e) {
                Log::error('Failed to create payment', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'pricing_details' => $pricingDetails
                ]);
                throw $e;
            }

            DB::commit();

            // Redirect to invoice page for paid templates
            return redirect()->route('invoice.show', $payment->code)
                ->with('success', 'Order created successfully! Please complete your payment.');

        } catch (ValidationException $e) {
            DB::rollback();
            
            Log::warning('Validation failed during checkout', [
                'user_id' => $user->id,
                'template_id' => $template->id,
                'validation_errors' => $e->errors()
            ]);

            return back()->withInput()->withErrors($e->errors());

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Checkout process error', [
                'user_id' => $user->id,
                'template_id' => $template->id,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['company_logo', 'favicon', 'og_image', 'hero_background', 'about_image']) // Exclude large image data from logs
            ]);

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses checkout. Silakan coba lagi atau hubungi support.');
        }
    }

    private function getTemplateFormFields($template)
    {
        // Base fields untuk semua template
        $baseFields = [
            [
                'key' => 'company_name',
                'label' => 'Company Name',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'PT. Your Company Name',
                'section' => 'company'
            ],
            [
                'key' => 'website_name',
                'label' => 'Website Name',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Your Website Name',
                'section' => 'company'
            ],
            [
                'key' => 'company_tagline',
                'label' => 'Company Tagline',
                'type' => 'text',
                'required' => false,
                'placeholder' => 'Your company tagline',
                'section' => 'company'
            ],
            [
                'key' => 'seo_title',
                'label' => 'SEO Title',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Website title for search engines',
                'max_length' => 60,
                'section' => 'seo'
            ],
            [
                'key' => 'seo_description',
                'label' => 'SEO Description',
                'type' => 'textarea',
                'required' => true,
                'placeholder' => 'Website description for search engines',
                'max_length' => 160,
                'section' => 'seo'
            ],
            [
                'key' => 'contact_email',
                'label' => 'Contact Email',
                'type' => 'email',
                'required' => true,
                'placeholder' => 'contact@company.com',
                'section' => 'contact'
            ],
            [
                'key' => 'contact_phone',
                'label' => 'Contact Phone',
                'type' => 'tel',
                'required' => true,
                'placeholder' => '+62 812-3456-7890',
                'section' => 'contact'
            ],
            [
                'key' => 'contact_address',
                'label' => 'Complete Address',
                'type' => 'textarea',
                'required' => true,
                'placeholder' => 'Your complete business address',
                'section' => 'contact'
            ],
            [
                'key' => 'services',
                'label' => 'Services',
                'type' => 'repeater',
                'required' => false,
                'min_items' => 1,
                'max_items' => 10,
                'section' => 'content',
                'fields' => [
                    [
                        'key' => 'title',
                        'label' => 'Service Title',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Service name'
                    ],
                    [
                        'key' => 'description',
                        'label' => 'Service Description',
                        'type' => 'textarea',
                        'required' => true,
                        'placeholder' => 'Describe your service'
                    ],
                    [
                        'key' => 'icon',
                        'label' => 'Icon Class',
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => 'fas fa-chart-line'
                    ]
                ]
            ],
            [
                'key' => 'social_links',
                'label' => 'Social Media Links',
                'type' => 'repeater',
                'required' => false,
                'min_items' => 0,
                'max_items' => 10,
                'section' => 'social',
                'fields' => [
                    [
                        'key' => 'platform',
                        'label' => 'Platform',
                        'type' => 'select',
                        'required' => true,
                        'options' => [
                            'facebook' => 'Facebook',
                            'instagram' => 'Instagram',
                            'twitter' => 'Twitter',
                            'linkedin' => 'LinkedIn',
                            'youtube' => 'YouTube',
                            'tiktok' => 'TikTok'
                        ]
                    ],
                    [
                        'key' => 'url',
                        'label' => 'URL',
                        'type' => 'url',
                        'required' => true,
                        'placeholder' => 'https://facebook.com/yourpage'
                    ],
                    [
                        'key' => 'label',
                        'label' => 'Display Label',
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => 'Follow us on Facebook'
                    ]
                ]
            ],
            [
                'key' => 'primary_color',
                'label' => 'Primary Color',
                'type' => 'color',
                'required' => true,
                'default' => '#2146ff',
                'section' => 'design'
            ],
            [
                'key' => 'secondary_color',
                'label' => 'Secondary Color',
                'type' => 'color',
                'required' => false,
                'default' => '#64748b',
                'section' => 'design'
            ],
            [
                'key' => 'subdomain',
                'label' => 'Website Address',
                'type' => 'subdomain',
                'required' => true,
                'placeholder' => 'yourwebsite',
                'suffix' => '.oursite.com',
                'section' => 'settings'
            ]
        ];

        // Template-specific fields berdasarkan kategori
        $specificFields = $this->getTemplateSpecificFields($template);

        return array_merge($baseFields, $specificFields);
    }

    private function getTemplateSpecificFields($template)
    {
        $fields = [];

        switch ($template->category) {
            case 'corporate':
                $fields[] = [
                    'key' => 'company_stats',
                    'label' => 'Company Statistics',
                    'type' => 'repeater',
                    'required' => false,
                    'max_items' => 4,
                    'section' => 'content',
                    'fields' => [
                        [
                            'key' => 'number',
                            'label' => 'Number',
                            'type' => 'text',
                            'required' => true,
                            'placeholder' => '500+'
                        ],
                        [
                            'key' => 'label',
                            'label' => 'Label',
                            'type' => 'text',
                            'required' => true,
                            'placeholder' => 'Happy Clients'
                        ],
                        [
                            'key' => 'icon',
                            'label' => 'Icon',
                            'type' => 'text',
                            'required' => false,
                            'placeholder' => 'fas fa-users'
                        ]
                    ]
                ];
                break;

            case 'portfolio':
                $fields[] = [
                    'key' => 'portfolio_items',
                    'label' => 'Portfolio Items',
                    'type' => 'repeater',
                    'required' => false,
                    'max_items' => 12,
                    'section' => 'content',
                    'fields' => [
                        [
                            'key' => 'title',
                            'label' => 'Project Title',
                            'type' => 'text',
                            'required' => true,
                            'placeholder' => 'Project name'
                        ],
                        [
                            'key' => 'category',
                            'label' => 'Category',
                            'type' => 'text',
                            'required' => true,
                            'placeholder' => 'Web Design'
                        ],
                        [
                            'key' => 'image_url',
                            'label' => 'Image URL',
                            'type' => 'url',
                            'required' => false,
                            'placeholder' => 'https://example.com/image.jpg'
                        ],
                        [
                            'key' => 'description',
                            'label' => 'Description',
                            'type' => 'textarea',
                            'required' => false,
                            'placeholder' => 'Project description'
                        ]
                    ]
                ];
                break;
        }

        return $fields;
    }

    private function getValidationRules($template)
    {
        $rules = [
            // Company Info
            'company_name' => 'required|string|max:255',
            'company_tagline' => 'nullable|string|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            
            // Website Basics
            'website_name' => 'required|string|max:255',
            'subdomain' => 'required|string|min:3|max:50|regex:/^[a-z0-9-]+$/|unique:website_contents,subdomain',
            'font_family' => 'required|string|in:inter,poppins,nunito,roboto',
            'border_radius' => 'required|string|in:none,small,medium,large',
            
            // Colors
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'accent_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'whatsapp_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            
            // SEO
            'seo_title' => 'required|string|max:60',
            'seo_description' => 'required|string|max:160',
            'seo_keywords' => 'required|string|max:500',
            'robots_meta' => 'required|string',
            'favicon' => 'nullable|file|mimes:ico,png|max:1024',
            'og_image' => 'nullable|image|max:4096',
            
            // Contact
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_address' => 'required|string|max:500',
            'contact_title' => 'nullable|string|max:255',
            
            // Hero Section
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string|max:500',
            'hero_background' => 'nullable|image|max:6144',
            'hero_cta_text' => 'required|string|max:50',
            'hero_cta_link' => 'required|string|max:255',
            
            // About Section
            'about_title' => 'nullable|string|max:255',
            'about_content' => 'nullable|string|max:2000',
            'about_image' => 'nullable|image|max:4096',
            
            // Services Section
            'services_title' => 'required|string|max:255',
            'services_subtitle' => 'nullable|string|max:500',
            'services' => 'required|array|min:1|max:10',
            'services.*.title' => 'required|string|max:255',
            'services.*.description' => 'required|string|max:1000',
            'services.*.icon' => 'required|string|max:100',
            'services.*.link' => 'required|string|max:255',
            
            // Company Stats
            'company_stats' => 'required|array|min:1|max:6',
            'company_stats.*.icon' => 'required|string|max:100',
            'company_stats.*.label' => 'required|string|max:100',
            'company_stats.*.number' => 'required|string|max:20',
            
            // Social Media
            'social_links' => 'nullable|array|max:10',
            'social_links.*.platform' => 'required|string|in:facebook,instagram,twitter,linkedin,youtube,tiktok',
            'social_links.*.url' => 'required|url|max:500',
            'social_links.*.label' => 'required|string|max:255',
            
            // Testimonials
            'testimonials_title' => 'required|string|max:255',
            'testimonials' => 'required|array|min:1|max:10',
            'testimonials.*.name' => 'required|string|max:255',
            'testimonials.*.photo' => 'nullable|image|max:2048',
            'testimonials.*.rating' => 'required|string|in:1,2,3,4,5',
            'testimonials.*.content' => 'required|string|max:1000',
            'testimonials.*.position' => 'required|string|max:255',
            
            // Gallery
            'gallery_title' => 'nullable|string|max:255',
            'gallery_photos' => 'nullable|array|max:12',
            'gallery_photos.*' => 'nullable|image|max:4096',
            
            // WhatsApp Integration
            'whatsapp_enabled' => 'required|boolean',
            'whatsapp_number' => 'nullable|required_if:whatsapp_enabled,true|string|max:20',
            'whatsapp_message' => 'nullable|required_if:whatsapp_enabled,true|string|max:500',
            'whatsapp_position' => 'nullable|required_if:whatsapp_enabled,true|string|in:bottom-left,bottom-right',
            'whatsapp_greeting_text' => 'nullable|string|max:255',
            
            // Analytics
            'google_analytics' => 'nullable|string|max:50',
            'google_tag_manager' => 'nullable|string|max:50',
            
            // Form Control
            'agree_terms' => 'required|accepted',
            'voucher_code' => 'nullable|string|max:255',
        ];

        // Template-specific validation rules
        if ($template->category === 'corporate') {
            // Company Stats
            $rules['company_stats'] = 'required|array|min:1|max:4';
            $rules['company_stats.*.number'] = 'required|string|max:20';
            $rules['company_stats.*.label'] = 'required|string|max:100';
            $rules['company_stats.*.icon'] = 'required|string|max:100';
            
            // Hero section is required for corporate
            $rules['hero_title'] = 'required|string|max:255';
            $rules['hero_subtitle'] = 'required|string|max:500';
            $rules['hero_background'] = 'required|image|max:6144';
            
            // About section is required for corporate
            $rules['about_title'] = 'nullable|string|max:255';
            $rules['about_content'] = 'nullable|string|max:2000';
            $rules['about_image'] = 'nullable|image|max:4096';
            
            // Services section is required
            $rules['services'] = 'required|array|min:3|max:10';
            $rules['services.*.title'] = 'required|string|max:255';
            $rules['services.*.description'] = 'required|string|min:50|max:1000';
            $rules['services.*.icon'] = 'required|string|max:100';
            $rules['services.*.link'] = 'required|string|max:255';
        }

        if ($template->category === 'dealer') {
            $rules['cars'] = 'nullable|array|max:50';
            $rules['cars.*.name'] = 'required_with:cars|string|max:255';
            $rules['cars.*.type'] = 'nullable|string|max:50';
            $rules['cars.*.year'] = 'nullable|string|max:10';
            $rules['cars.*.image'] = 'nullable';
            $rules['cars.*.price'] = 'nullable|string|max:255';
            $rules['cars.*.features'] = 'nullable|string|max:2000';
            $rules['cars.*.transmission'] = 'nullable|string|max:50';
            $rules['cars.*.whatsapp_sales'] = 'nullable|string|max:30';

            $rules['promo_banner'] = 'nullable';
            $rules['promo_title'] = 'nullable|string|max:255';
            $rules['promo_description'] = 'nullable|string|max:5000';
        }

        // if ($template->category === 'portfolio') {
        //     $rules['portfolio_items'] = 'nullable|array|max:12';
        //     $rules['portfolio_items.*.title'] = 'required|string|max:255';
        //     $rules['portfolio_items.*.category'] = 'required|string|max:100';
        //     'portfolio_items.*.image_url' => 'nullable|url|max:500';
        //     'portfolio_items.*.description' => 'nullable|string|max:1000';
        // }

        return $rules;
    }

    private function processContentData($validated, $template)
    {
        // Process dan clean up data sebelum disimpan
        $contentData = $validated;

        // Remove non-content fields
        unset($contentData['agree_terms']);
        unset($contentData['voucher_code']);
        // Don't unset subdomain here since we need it for validation
        
        // Process repeater fields
        if (isset($contentData['services'])) {
            $contentData['services'] = array_values(array_filter($contentData['services'], function ($service) {
                return !empty($service['title']) && !empty($service['description']);
            }));
        }

        if (isset($contentData['social_links'])) {
            $contentData['social_links'] = array_values(array_filter($contentData['social_links'], function ($link) {
                return !empty($link['platform']) && !empty($link['url']);
            }));
        }

        if (isset($contentData['company_stats'])) {
            $contentData['company_stats'] = array_values(array_filter($contentData['company_stats'], function ($stat) {
                return !empty($stat['label']) && !empty($stat['number']);
            }));
        }

        if (isset($contentData['testimonials'])) {
            $processedTestimonials = [];

            foreach ($contentData['testimonials'] as $testimonial) {
                if (empty($testimonial['name']) || empty($testimonial['content'])) {
                    continue;
                }

                if (array_key_exists('photo', $testimonial)) {
                    $testimonial['photo'] = $this->storeUploadedFile($testimonial['photo'], 'website-assets/testimonials');
                }

                $processedTestimonials[] = $testimonial;
            }

            $contentData['testimonials'] = array_values($processedTestimonials);
        }

        if (isset($contentData['cars'])) {
            $processedCars = [];

            foreach ($contentData['cars'] as $car) {
                if (empty($car['name'])) {
                    continue;
                }

                if (array_key_exists('image_preview', $car)) {
                    unset($car['image_preview']);
                }

                if (array_key_exists('image', $car)) {
                    $car['image'] = $this->storeUploadedFile($car['image'], 'website-assets/cars');
                }

                $processedCars[] = $car;
            }

            $contentData['cars'] = array_values($processedCars);
        }

        // Store single image/file fields
        $fileFields = [
            'company_logo' => 'website-assets/company-logos',
            'favicon' => 'website-assets/favicons',
            'og_image' => 'website-assets/og-images',
            'hero_background' => 'website-assets/hero-backgrounds',
            'about_image' => 'website-assets/about-images',
        ];

        foreach ($fileFields as $field => $directory) {
            if (array_key_exists($field, $contentData)) {
                $contentData[$field] = $this->storeUploadedFile($contentData[$field], $directory);
            }
        }

        if (array_key_exists('promo_banner', $contentData)) {
            $contentData['promo_banner'] = $this->storeUploadedFile($contentData['promo_banner'], 'website-assets/promo-banners');
        }

        // Process gallery photos
        if (isset($contentData['gallery_photos']) && is_array($contentData['gallery_photos'])) {
            $galleryPaths = [];

            foreach ($contentData['gallery_photos'] as $photo) {
                $stored = $this->storeUploadedFile($photo, 'website-assets/gallery');

                if ($stored) {
                    $galleryPaths[] = $stored;
                }
            }

            $contentData['gallery_photos'] = $galleryPaths;
        }

        // Set default values jika tidak disediakan
        $defaults = [
            'font_family' => 'inter',
            'border_radius' => 'medium',
            'whatsapp_enabled' => false,
            'whatsapp_position' => 'bottom-right',
            'robots_meta' => 'index,follow',
        ];

        foreach ($defaults as $key => $value) {
            if (!isset($contentData[$key])) {
                $contentData[$key] = $value;
            }
        }

        return $contentData;
    }

    private function storeUploadedFile($value, string $directory): ?string
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof UploadedFile) {
            return $value->store($directory, 'public');
        }

        if (is_string($value)) {
            if (Str::startsWith($value, ['http://', 'https://'])) {
                return $value;
            }

            $path = ltrim($value, '/');

            if (Str::startsWith($path, 'storage/')) {
                $path = substr($path, strlen('storage/'));
            }

            return $path;
        }

        return null;
    }
}
