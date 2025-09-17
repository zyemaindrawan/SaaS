<?php

namespace App\Http\Controllers;

use App\Services\WebsiteContentService;
use App\Services\TemplateService;
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
        
        // Get user's websites with pagination
        $websites = $this->websiteContentService->getUserWebsites($user);
        
        // Get statistics
        $stats = [
            'total_websites' => $user->websiteContents()->count(),
            'active_websites' => $user->websiteContents()->active()->count(),
            'draft_websites' => $user->websiteContents()->byStatus('draft')->count(),
            'processing_websites' => $user->websiteContents()->byStatus('processing')->count(),
        ];
        
        return Inertia::render('Dashboard', [
            'websites' => $websites,
            'stats' => $stats
        ]);
    }
}
