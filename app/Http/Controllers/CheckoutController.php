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

        // Validation
        $validated = $request->validate([
            'website_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:1000',
            'business_type' => 'nullable|string|max:100',
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string|max:500',
            'subdomain' => 'required|string|min:3|max:50|regex:/^[a-z0-9-]+$/|unique:website_contents,subdomain',
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'agree_terms' => 'required|accepted',
        ], [
            'subdomain.unique' => 'This subdomain is already taken. Please choose another one.',
            'subdomain.regex' => 'Subdomain can only contain lowercase letters, numbers, and hyphens.',
            'primary_color.regex' => 'Please select a valid color.',
            'secondary_color.regex' => 'Please select a valid color.',
        ]);

        try {
            // Create website content
            $contentData = [
                'website_name' => $validated['website_name'],
                'site_description' => $validated['site_description'],
                'business_type' => $validated['business_type'],
                'contact_name' => $validated['contact_name'],
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'],
                'contact_address' => $validated['contact_address'],
                'primary_color' => $validated['primary_color'],
                'secondary_color' => $validated['secondary_color'] ?? '#1e40af',
                'logo_url' => null,
                'gallery_images' => [],
                'social_media' => [
                    'facebook' => '',
                    'instagram' => '',
                    'twitter' => '',
                    'linkedin' => '',
                ],
            ];

            $websiteContent = WebsiteContent::create([
                'user_id' => $user->id,
                'template_slug' => $template->slug,
                'website_name' => $validated['website_name'],
                'content_data' => $contentData,
                'status' => 'draft',
                'subdomain' => $validated['subdomain'],
                'expires_at' => now()->addYear(),
            ]);

            // If template is free, skip payment
            if ($template->price == 0) {
                $websiteContent->update(['status' => 'paid']);
                
                return redirect()->route('dashboard')
                    ->with('success', 'Free template activated successfully! Your website is being prepared.');
            }

            // Create payment and redirect
            $payment = $this->paymentService->createPayment($websiteContent);
            
            return redirect()->route('payment.show', $payment->code)
                ->with('success', 'Website content saved! Please complete your payment.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong. Please try again. ' . $e->getMessage());
        }
    }
}
