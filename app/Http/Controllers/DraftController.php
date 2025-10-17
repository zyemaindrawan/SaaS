<?php

namespace App\Http\Controllers;

use App\Models\WebsiteContent;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DraftController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $drafts = WebsiteContent::where('user_id', $user->id)
            ->whereIn('status', ['draft', 'previewed'])
            ->with('template')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($draft) {
                return [
                    'id' => $draft->id,
                    'website_name' => $draft->website_name,
                    'template_name' => $draft->template->name ?? 'Unknown Template',
                    'template_slug' => $draft->template_slug,
                    'status' => $draft->status,
                    'created_at' => $draft->created_at->format('M j, Y'),
                    'updated_at' => $draft->updated_at->format('M j, Y g:i A'),
                ];
            });

        return Inertia::render('Drafts/Index', [
            'drafts' => $drafts,
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'href' => '/dashboard'],
                ['name' => 'My Drafts', 'href' => null],
            ]
        ]);
    }

    public function show(WebsiteContent $websiteContent)
    {
        $user = Auth::user();

        // Verify ownership
        if ($websiteContent->user_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        // Check if website content is in draft or previewed status
        if (!in_array($websiteContent->status, ['draft', 'previewed'])) {
            return redirect()->route('drafts.index')
                ->with('error', 'Website content is not in draft status');
        }

        // Load template relationship
        $websiteContent->load('template');

        // Get pricing details from session or calculate defaults
        $pricingDetails = session('checkout_pricing');
        
        if (!$pricingDetails) {
            // Calculate default pricing if not in session
            $pricingDetails = [
                'subtotal' => $websiteContent->template->price ?? 0,
                'platform_fee' => 4000,
                'discount' => 0,
                'total' => ($websiteContent->template->price ?? 0) + 4000,
                'voucher_code' => null,
                'voucher_discount' => 0,
            ];
            
            // Store in session for payment processing
            session(['checkout_pricing' => $pricingDetails]);
        }

        // Get associated payment if exists
        $payment = Payment::where('website_content_id', $websiteContent->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return Inertia::render('Drafts/Show', [
            'websiteContent' => [
                'id' => $websiteContent->id,
                'website_name' => $websiteContent->website_name,
                'template_slug' => $websiteContent->template_slug,
                'status' => $websiteContent->status,
                'created_at' => $websiteContent->created_at->format('M j, Y g:i A'),
                'updated_at' => $websiteContent->updated_at->format('M j, Y g:i A'),
                'content_data' => $websiteContent->content_data,
            ],
            'template' => [
                'id' => $websiteContent->template->id,
                'name' => $websiteContent->template->name,
                'slug' => $websiteContent->template->slug,
                'description' => $websiteContent->template->description,
                'category' => $websiteContent->template->category,
                'preview_image' => $websiteContent->template->preview_image,
                'price' => $websiteContent->template->price,
            ],
            'pricing' => $pricingDetails,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
            'payment' => $payment ? [
                'id' => $payment->id,
                'code' => $payment->code,
                'status' => $payment->status,
                'gross_amount' => $payment->gross_amount,
                'created_at' => $payment->created_at,
                'expired_at' => $payment->expired_at,
            ] : null,
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'href' => '/dashboard'],
                ['name' => 'My Drafts', 'href' => '/drafts'],
                ['name' => $websiteContent->website_name, 'href' => null],
            ]
        ]);
    }

    public function updateStatus(Request $request, WebsiteContent $websiteContent)
    {
        $user = Auth::user();

        // Verify ownership
        if ($websiteContent->user_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'status' => 'required|in:draft,previewed'
        ]);

        $websiteContent->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status updated successfully');
    }
}