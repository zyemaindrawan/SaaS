<?php

namespace App\Services;

use App\Models\WebsiteContent;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class WebsiteContentService
{
    public function __construct(
        private TemplateService $templateService
    ) {}

    public function createWebsiteContent(User $user, array $data): WebsiteContent
    {
        // Validate content data
        $errors = $this->templateService->validateContentData(
            $data['template_slug'],
            $data['content_data']
        );

        if (!empty($errors)) {
            throw new \InvalidArgumentException('Validation failed: ' . json_encode($errors));
        }

        // Generate subdomain if not provided
        if (empty($data['subdomain'])) {
            $data['subdomain'] = $this->generateUniqueSubdomain($data['website_name']);
        }

        return WebsiteContent::create([
            'user_id' => $user->id,
            'template_slug' => $data['template_slug'],
            'website_name' => $data['website_name'],
            'content_data' => $data['content_data'],
            'subdomain' => $data['subdomain'],
            'custom_domain' => $data['custom_domain'] ?? null,
            'status' => 'draft'
        ]);
    }

    public function updateWebsiteContent(WebsiteContent $websiteContent, array $data): WebsiteContent
    {
        // Only allow updates for certain statuses
        if (!in_array($websiteContent->status, ['draft', 'previewed'])) {
            throw new \Exception('Cannot update website content in current status');
        }

        if (isset($data['content_data'])) {
            $errors = $this->templateService->validateContentData(
                $websiteContent->template_slug,
                $data['content_data']
            );

            if (!empty($errors)) {
                throw new \InvalidArgumentException('Validation failed: ' . json_encode($errors));
            }
        }

        $websiteContent->update($data);

        return $websiteContent->fresh();
    }

    public function generatePreview(WebsiteContent $websiteContent): array
    {
        $template = $this->templateService->getTemplateBySlug($websiteContent->template_slug);

        if (!$template) {
            throw new \Exception('Template not found');
        }

        // Update status to previewed
        $websiteContent->update(['status' => 'previewed']);

        return [
            'preview_url' => route('preview.show', $websiteContent->id),
            'website_content' => $websiteContent,
            'template' => $template
        ];
    }

    private function generateUniqueSubdomain(string $websiteName): string
    {
        $baseSlug = Str::slug($websiteName);
        $subdomain = $baseSlug;
        $counter = 1;

        while (WebsiteContent::where('subdomain', $subdomain)->exists()) {
            $subdomain = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $subdomain;
    }

    public function getUserWebsites(User $user, string $status = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = WebsiteContent::forUser($user->id)
            ->with(['template', 'payments' => fn($q) => $q->latest()])
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->byStatus($status);
        }

        return $query->paginate(10);
    }

    public function getUserWebsitesWithStats(User $user): array
    {
        $websites = $this->getUserWebsites($user);

        $stats = [
            'total_websites' => WebsiteContent::forUser($user->id)->count(),
            'active_websites' => WebsiteContent::forUser($user->id)->where('status', 'active')->count(),
            'draft_websites' => WebsiteContent::forUser($user->id)->where('status', 'draft')->count(),
            'processing_websites' => WebsiteContent::forUser($user->id)->where('status', 'processing')->count(),
        ];

        return [
            'websites' => $websites,
            'stats' => $stats
        ];
    }
}
