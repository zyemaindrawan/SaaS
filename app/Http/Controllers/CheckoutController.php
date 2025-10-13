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

        // Calculate total for checkout
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
                
                // if (empty($validated['subdomain'])) {
                //     throw ValidationException::withMessages(['subdomain' => 'Subdomain wajib diisi']);
                // }

                // Create website content
                $websiteContent = WebsiteContent::create([
                    'user_id' => $user->id,
                    'template_slug' => $template->slug,
                    'website_name' => $validated['website_name'],
                    'content_data' => $contentData,
                    'status' => 'draft',
                    //'subdomain' => $validated['subdomain'],
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
        return [
            [
                'key' => 'website_name',
                'label' => 'Website Name',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Nama Web Saya',
                'section' => 'general',
            ],
            // [
            //     'key' => 'subdomain',
            //     'label' => 'Subdomain',
            //     'type' => 'subdomain',
            //     'required' => true,
            //     'placeholder' => 'demo123',
            //     'section' => 'general',
            // ],
            [
                'key' => 'company_name',
                'label' => 'Company Name',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'PT Maju Jaya',
                'section' => 'branding',
            ],
            [
                'key' => 'company_tagline',
                'label' => 'Company Tagline',
                'type' => 'text',
                'required' => false,
                'placeholder' => 'Mays and Wagner',
                'section' => 'branding',
            ],
            [
                'key' => 'company_logo',
                'label' => 'Company Logo',
                'type' => 'file',
                'required' => false,
                'section' => 'branding',
            ],
            [
                'key' => 'favicon',
                'label' => 'Favicon',
                'type' => 'file',
                'required' => false,
                'section' => 'branding',
            ],
            [
                'key' => 'hero_title',
                'label' => 'Hero Title',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Selamat Datang',
                'section' => 'hero',
            ],
            [
                'key' => 'hero_subtitle',
                'label' => 'Hero Subtitle',
                'type' => 'textarea',
                'required' => true,
                'placeholder' => 'Assumenda aperiam re',
                'section' => 'hero',
            ],
            [
                'key' => 'hero_background',
                'label' => 'Hero Background',
                'type' => 'file',
                'required' => false,
                'section' => 'hero',
            ],
            [
                'key' => 'about_title',
                'label' => 'About Title',
                'type' => 'text',
                'required' => false,
                'placeholder' => 'Welcome To The World',
                'section' => 'about',
            ],
            [
                'key' => 'about_content',
                'label' => 'About Content',
                'type' => 'textarea',
                'required' => false,
                'placeholder' => 'Ceritakan tentang perusahaan Anda...',
                'section' => 'about',
            ],
            [
                'key' => 'about_image',
                'label' => 'About Image',
                'type' => 'file',
                'required' => false,
                'section' => 'about',
            ],
            [
                'key' => 'products',
                'label' => 'Products',
                'type' => 'repeater',
                'required' => true,
                'min_items' => 1,
                'max_items' => 12,
                'section' => 'content',
                'fields' => [
                    [
                        'key' => 'name',
                        'label' => 'Product Name',
                        'type' => 'text',
                        'required' => true,
                    ],
                    [
                        'key' => 'price',
                        'label' => 'Product Price',
                        'type' => 'text',
                        'required' => true,
                    ],
                    [
                        'key' => 'link',
                        'label' => 'Product Link',
                        'type' => 'url',
                        'required' => true,
                    ],
                    [
                        'key' => 'image',
                        'label' => 'Product Image',
                        'type' => 'file',
                        'required' => false,
                    ],
                ],
            ],
            [
                'key' => 'social_links',
                'label' => 'Social Links',
                'type' => 'repeater',
                'required' => false,
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
                            'tiktok' => 'TikTok',
                        ],
                    ],
                    [
                        'key' => 'label',
                        'label' => 'Label',
                        'type' => 'text',
                        'required' => true,
                    ],
                    [
                        'key' => 'url',
                        'label' => 'URL',
                        'type' => 'url',
                        'required' => true,
                    ],
                ],
            ],
            [
                'key' => 'testimonials',
                'label' => 'Testimonials',
                'type' => 'repeater',
                'required' => true,
                'min_items' => 1,
                'max_items' => 10,
                'section' => 'social-proof',
                'fields' => [
                    [
                        'key' => 'name',
                        'label' => 'Name',
                        'type' => 'text',
                        'required' => true,
                    ],
                    [
                        'key' => 'position',
                        'label' => 'Position',
                        'type' => 'text',
                        'required' => false,
                    ],
                    [
                        'key' => 'rating',
                        'label' => 'Rating',
                        'type' => 'select',
                        'required' => true,
                        'options' => [
                            '5' => '5',
                            '4' => '4',
                            '3' => '3',
                            '2' => '2',
                            '1' => '1',
                        ],
                    ],
                    [
                        'key' => 'content',
                        'label' => 'Content',
                        'type' => 'textarea',
                        'required' => true,
                    ],
                    [
                        'key' => 'photo',
                        'label' => 'Photo',
                        'type' => 'file',
                        'required' => false,
                    ],
                ],
            ],
            [
                'key' => 'contact_address',
                'label' => 'Contact Address',
                'type' => 'textarea',
                'required' => false,
                'section' => 'contact',
            ],
            [
                'key' => 'contact_email',
                'label' => 'Contact Email',
                'type' => 'email',
                'required' => false,
                'section' => 'contact',
            ],
            [
                'key' => 'contact_phone',
                'label' => 'Contact Phone',
                'type' => 'text',
                'required' => false,
                'section' => 'contact',
            ],
            [
                'key' => 'whatsapp_number',
                'label' => 'WhatsApp Number',
                'type' => 'text',
                'required' => false,
                'section' => 'whatsapp',
            ],
            [
                'key' => 'whatsapp_greeting_text',
                'label' => 'WhatsApp Button Text',
                'type' => 'text',
                'required' => false,
                'section' => 'whatsapp',
            ],
            [
                'key' => 'whatsapp_message',
                'label' => 'WhatsApp Message',
                'type' => 'textarea',
                'required' => false,
                'section' => 'whatsapp',
            ],
        ];
    }

    private function getValidationRules($template)
    {
        return [
            'website_name' => 'required|string|max:255',
            // subdomain dihapus dari validasi
            'company_name' => 'required|string|max:255',
            'company_tagline' => 'nullable|string|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'favicon' => 'nullable|file|mimes:ico,png|max:1024',
            // 'hero_title' => 'required|string|max:255',
            // 'hero_subtitle' => 'required|string|max:500',
            // 'hero_background' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'about_title' => 'nullable|string|max:255',
            'about_content' => 'nullable|string|max:2000',
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            
            // Gallery photos - maksimal 4 items
            'gallery_photos' => 'nullable|array|max:4',
            'gallery_photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            
            // Products - maksimal 4 items
            'products' => 'required|array|min:1|max:4',
            'products.*.name' => 'required|string|max:255',
            'products.*.price' => 'required|string|max:100',
            'products.*.link' => 'required|string|max:500',
            'products.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            
            'social_links' => 'nullable|array|max:10',
            'social_links.*.platform' => 'required|string|in:facebook,instagram,twitter,linkedin,youtube,tiktok',
            'social_links.*.label' => 'required|string|max:255',
            'social_links.*.url' => 'required|url|max:500',
            
            // Testimonials - maksimal 3 items
            'testimonials' => 'required|array|min:1|max:3',
            'testimonials.*.name' => 'required|string|max:255',
            'testimonials.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'testimonials.*.rating' => 'required|string|in:1,2,3,4,5',
            'testimonials.*.content' => 'required|string|max:1000',
            'testimonials.*.position' => 'nullable|string|max:255',
            
            'contact_address' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'whatsapp_greeting_text' => 'nullable|string|max:255',
            'whatsapp_message' => 'nullable|string|max:500',
        ];
    }

    private function processContentData($validated, $template)
    {
        $contentData = $validated;

        if (isset($contentData['products'])) {
            $processedProducts = [];

            foreach ($contentData['products'] as $product) {
                if (empty($product['name'])) {
                    continue;
                }

                if (array_key_exists('image', $product)) {
                    $product['image'] = $this->storeUploadedFile($product['image'], 'website-assets/products');
                }

                $processedProducts[] = $product;
            }

            $contentData['products'] = array_values($processedProducts);
        }

        if (isset($contentData['social_links'])) {
            $contentData['social_links'] = array_values(array_filter($contentData['social_links'], function ($link) {
                return !empty($link['platform']) && !empty($link['url']);
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

        $fileFields = [
            'company_logo' => 'website-assets/company-logos',
            'favicon' => 'website-assets/favicons',
            'hero_background' => 'website-assets/hero-backgrounds',
            'about_image' => 'website-assets/about-images',
        ];

        // Process gallery photos
        if (isset($contentData['gallery_photos'])) {
            $processedGallery = [];
            foreach ($contentData['gallery_photos'] as $photo) {
                if ($photo) {
                    $processedGallery[] = $this->storeUploadedFile($photo, 'website-assets/gallery_photos');
                }
            }
            $contentData['gallery_photos'] = array_values(array_filter($processedGallery));
        }

        foreach ($fileFields as $field => $directory) {
            if (array_key_exists($field, $contentData)) {
                $contentData[$field] = $this->storeUploadedFile($contentData[$field], $directory);
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
