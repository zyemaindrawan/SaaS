<?php
namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\WebsiteContent;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

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
        $validated = $request->validate($validationRules);

        //dd(request()->all());

        DB::beginTransaction();

        try {
            // Create website content
            $websiteContent = WebsiteContent::create([
                'user_id' => $user->id,
                'template_slug' => $template->slug,
                'website_name' => $validated['website_name'], // Use the validated input from form
                'content_data' => $this->processContentData($validated, $template),
                'status' => 'draft',
                'subdomain' => $validated['subdomain'],
                'expires_at' => now()->addYear(),
            ]);
            
            // Load the template relationship to ensure it's available
            $websiteContent->load('template');
            
            // Verify the website content was created properly
            if (!$websiteContent->template) {
                throw new \Exception('Website content template relationship not loaded properly');
            }

            // Handle free templates
            if ($template->price == 0) {
                $websiteContent->update(['status' => 'paid']);
                
                DB::commit();
                
                // For free templates, redirect to success page or dashboard
                return response()->json([
                    'success' => true,
                    'message' => 'Free template activated successfully!',
                    'redirect' => route('dashboard')
                ]);
            }

            // Create payment with Midtrans
            $payment = $this->paymentService->createPayment($websiteContent);
            
            DB::commit();
            
            // Redirect to invoice page instead of payment directly
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully! Redirecting to invoice...',
                'redirect' => route('invoice.show', $payment->code)
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            \Log::error('Checkout process error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'template_id' => $template->id,
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 422);
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
            'company_name' => 'required|string|max:255',
            'website_name' => 'required|string|max:255',
            'company_tagline' => 'nullable|string|max:255',
            'seo_title' => 'required|string|max:60',
            'seo_description' => 'required|string|max:160',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_address' => 'required|string|max:500',
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'subdomain' => 'required|string|min:3|max:50|regex:/^[a-z0-9-]+$/|unique:website_contents,subdomain',
            'services' => 'nullable|array|max:10',
            'services.*.title' => 'required|string|max:255',
            'services.*.description' => 'required|string|max:1000',
            'services.*.icon' => 'nullable|string|max:100',
            'social_links' => 'nullable|array|max:10',
            'social_links.*.platform' => 'required|string|in:facebook,instagram,twitter,linkedin,youtube,tiktok',
            'social_links.*.url' => 'required|url|max:500',
            'social_links.*.label' => 'nullable|string|max:255',
            'agree_terms' => 'required|accepted',
        ];

        // Template-specific validation rules
        if ($template->category === 'corporate') {
            $rules['company_stats'] = 'nullable|array|max:4';
            $rules['company_stats.*.number'] = 'required|string|max:20';
            $rules['company_stats.*.label'] = 'required|string|max:100';
            $rules['company_stats.*.icon'] = 'nullable|string|max:100';
        }

        if ($template->category === 'portfolio') {
            $rules['portfolio_items'] = 'nullable|array|max:12';
            $rules['portfolio_items.*.title'] = 'required|string|max:255';
            $rules['portfolio_items.*.category'] = 'required|string|max:100';
            $rules['portfolio_items.*.image_url'] = 'nullable|url|max:500';
            $rules['portfolio_items.*.description'] = 'nullable|string|max:1000';
        }

        return $rules;
    }

    private function processContentData($validated, $template)
    {
        // Process dan clean up data sebelum disimpan
        $contentData = $validated;

        // Remove non-content fields
        unset($contentData['agree_terms']);

        //dd($contentData);

        // Process repeater fields
        if (isset($contentData['services'])) {
            $contentData['services'] = array_values(array_filter($contentData['services'], function($service) {
                return !empty($service['title']) && !empty($service['description']);
            }));
        }

        if (isset($contentData['social_links'])) {
            $contentData['social_links'] = array_values(array_filter($contentData['social_links'], function($link) {
                return !empty($link['platform']) && !empty($link['url']);
            }));
        }

        // Add default values
        $contentData['font_family'] = 'inter';
        $contentData['border_radius'] = 'medium';
        $contentData['whatsapp_enabled'] = false;
        $contentData['created_at'] = now()->toISOString();

        return $contentData;
    }
}
