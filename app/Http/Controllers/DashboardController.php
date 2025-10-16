<?php

namespace App\Http\Controllers;

use App\Services\WebsiteContentService;
use App\Services\TemplateService;
use App\Models\Payment;
use App\Models\WebsiteContent;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(
        private WebsiteContentService $websiteContentService,
        private TemplateService $templateService
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();

        // Get user's websites with pagination and stats
        $data = $this->websiteContentService->getUserWebsitesWithStats($user);
        $websites = $data['websites'];
        $stats = $data['stats'];

        // Get user's recent payments/invoices (limit to 10 most recent)
        $invoices = Payment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'code' => $payment->code,
                    'gross_amount' => $payment->gross_amount,
                    'status' => $payment->status,
                    'created_at' => $payment->created_at,
                    'expired_at' => $payment->expired_at,
                    'website_content_id' => $payment->website_content_id,
                ];
            });

        // Get user's drafts (limit to 5 most recent)
        $drafts = WebsiteContent::where('user_id', $user->id)
            ->whereIn('status', ['draft', 'previewed'])
            ->with('template')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
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

        return Inertia::render('Dashboard', [
            'websites' => $websites,
            'stats' => $stats,
            'invoices' => $invoices,
            'drafts' => $drafts,
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
                'info' => $request->session()->get('info'),
            ],
            'app' => [
                'name' => config('app.name'),
            ],
        ]);
    }
}
