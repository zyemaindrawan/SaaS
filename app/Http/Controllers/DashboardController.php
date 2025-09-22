<?php

namespace App\Http\Controllers;

use App\Services\WebsiteContentService;
use App\Services\TemplateService;
use App\Models\Payment;
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

        return Inertia::render('Dashboard', [
            'websites' => $websites,
            'stats' => $stats,
            'invoices' => $invoices,
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
