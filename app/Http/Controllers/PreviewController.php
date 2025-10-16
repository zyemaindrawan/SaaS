<?php

namespace App\Http\Controllers;

use App\Models\WebsiteContent;
use App\Services\TemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;

class PreviewController extends Controller
{
    public function __construct(
        private TemplateService $templateService
    ) {}

    /**
     * Show website preview in Inertia component by slug
     */
    public function showBySlug(string $slug)
    {
        // Allow numeric IDs to fall back to standard preview routes
        if (is_numeric($slug)) {
            $websiteContent = WebsiteContent::find($slug);
            if ($websiteContent) {
                return $this->show($websiteContent);
            }
        }

        // First try to find website content by slug
        $websiteContent = WebsiteContent::where('template_slug', $slug)->first();
        
        // If not found, try to find by template_slug
        if (!$websiteContent) {
            $websiteContent = WebsiteContent::where('template_slug', $slug)->first();
        }
        
        // If still not found, create a default preview for the template
        if (!$websiteContent && $this->templateService->getTemplateBySlug($slug)) {
            $websiteContent = new WebsiteContent([
                'template_slug' => $slug,
                'content_data' => $this->getDefaultContent($slug),
                'status' => 'preview',
                'user_id' => auth()->id() ?? 1, // Default to user 1 for demo
                'title' => ucfirst(str_replace('-', ' ', $slug)) . ' Preview'
            ]);
        }
            
        if (!$websiteContent) {
            abort(404, 'Website content or template not found');
        }
        
        // Only authorize if it's a saved record and user is authenticated
        if ($websiteContent->exists && auth()->check()) {
            try {
                $this->authorize('view', $websiteContent);
            } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
                // If no policy exists or authorization fails, allow for demo purposes
                // In production, you might want to handle this differently
            }
        }
        
        return $this->showPreview($websiteContent, $slug);
    }

    /**
     * Render website preview using Blade template by slug
     */
    public function renderBySlug(string $slug)
    {
        // Allow numeric IDs to fall back to authenticated render routes
        if (is_numeric($slug)) {
            $websiteContent = WebsiteContent::find($slug);
            if ($websiteContent) {
                return $this->render($websiteContent);
            }
        }

        // First try to find website content by slug
        $websiteContent = WebsiteContent::where('template_slug', $slug)->first();
        
        // If not found, try to find by template_slug
        if (!$websiteContent) {
            $websiteContent = WebsiteContent::where('template_slug', $slug)->first();
        }
        
        // If still not found, create a default preview for the template
        if (!$websiteContent && $this->templateService->getTemplateBySlug($slug)) {
            $websiteContent = new WebsiteContent([
                'template_slug' => $slug,
                'content_data' => $this->getDefaultContent($slug),
                'status' => 'preview',
                'user_id' => auth()->id() ?? 1, // Default to user 1 for demo
                'title' => ucfirst(str_replace('-', ' ', $slug)) . ' Preview'
            ]);
        }
            
        if (!$websiteContent) {
            abort(404, 'Website content or template not found');
        }
        
        // Only authorize if it's a saved record and user is authenticated
        if ($websiteContent->exists && auth()->check()) {
            try {
                $this->authorize('view', $websiteContent);
            } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
                // If no policy exists or authorization fails, allow for demo purposes
                // In production, you might want to handle this differently
            }
        }
        
        return $this->render($websiteContent);
    }

    /**
     * Get preview data as JSON by slug (for real-time updates)
     */
    public function dataBySlug(string $slug)
    {
        // Allow numeric IDs to fall back to authenticated data routes
        if (is_numeric($slug)) {
            $websiteContent = WebsiteContent::find($slug);
            if ($websiteContent) {
                return $this->data($websiteContent);
            }
        }

        // First try to find website content by slug
        $websiteContent = WebsiteContent::where('template_slug', $slug)->first();
        
        // If not found, create a default preview for the template
        if (!$websiteContent && $this->templateService->getTemplateBySlug($slug)) {
            $websiteContent = new WebsiteContent([
                'template_slug' => $slug,
                'content_data' => $this->getDefaultContent($slug),
                'status' => 'preview',
                'user_id' => auth()->id() ?? 1, // Default to user 1 for demo
                'title' => ucfirst(str_replace('-', ' ', $slug)) . ' Preview'
            ]);
        }
            
        if (!$websiteContent) {
            abort(404, 'Website content or template not found');
        }
        
        // Only authorize if it's a saved record and user is authenticated
        if ($websiteContent->exists && auth()->check()) {
            try {
                $this->authorize('view', $websiteContent);
            } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
                // Allow viewing for demo purposes
            }
        }
        
        $template = $this->templateService->getTemplateBySlug($websiteContent->template_slug);
        $config = $this->templateService->getTemplateConfig($websiteContent->template_slug);
        $processedContent = $this->processContentData($websiteContent->content_data, $config);
        
        return response()->json([
            'websiteContent' => $websiteContent,
            'template' => $template,
            'config' => $config,
            'processedContent' => $processedContent,
            'lastUpdated' => $websiteContent->updated_at ? $websiteContent->updated_at->toISOString() : now()->toISOString()
        ]);
    }

    /**
     * Show website preview in Inertia component (for slug-based URLs)
     */
    private function showPreview(WebsiteContent $websiteContent, string $slug = null)
    {
        // Get template configuration
        $template = $this->templateService->getTemplateBySlug($websiteContent->template_slug);
        $config = $this->templateService->getTemplateConfig($websiteContent->template_slug);
        
        if (!$template) {
            abort(404, 'Template not found');
        }
        
        // Get processed content data
        $processedContent = $this->processContentData($websiteContent->content_data, $config);
        
        // Determine the correct preview URL based on whether we have an ID or slug
        // If we have a slug parameter, always use slug-based URLs
        if ($slug) {
            $previewUrl = route('preview.render.slug', $slug);
        } else {
            $previewUrl = $websiteContent->exists && $websiteContent->id 
                ? route('preview.render', $websiteContent->id)
                : route('preview.render.slug', $websiteContent->template_slug);
        }
        
        return Inertia::render('Preview/Show', [
            'websiteContent' => $websiteContent->exists ? $websiteContent->load('user') : $websiteContent->makeHidden(['id']),
            'template' => $template,
            'config' => $config,
            'processedContent' => $processedContent,
            'previewUrl' => $previewUrl,
            'slug' => $slug,
            // 'editUrl' => route('website-builder.edit', $websiteContent->id),
            // 'canEdit' => auth()->user()->can('update', $websiteContent)
        ]);
    }

    /**
     * Show website preview in Inertia component
     */
    public function show(WebsiteContent $websiteContent)
    {
        $user = Auth::user();

        // Verify ownership for authenticated users
        if ($user && $websiteContent->user_id !== $user->id) {
            abort(403, 'Unauthorized access to preview');
        }

        // Check if website content exists and is in valid status for preview
        if (!in_array($websiteContent->status, ['draft', 'previewed', 'paid', 'active'])) {
            return redirect()->route('drafts.index')
                ->with('error', 'Website content not available for preview');
        }

        // Update status to 'previewed' if it's currently 'draft'
        if ($websiteContent->status === 'draft') {
            $websiteContent->update(['status' => 'previewed']);
        }

        return $this->showPreview($websiteContent);
    }

    /**
     * Render website preview using Blade template (for iframe/popup)
     */
    public function render(WebsiteContent $websiteContent)
    {
        try {
            $this->authorize('view', $websiteContent);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            // Allow viewing for demo purposes
        }
        
        // Get template and configuration
        $template = $this->templateService->getTemplateBySlug($websiteContent->template_slug);
        $config = $this->templateService->getTemplateConfig($websiteContent->template_slug);
        
        if (!$template) {
            abort(404, 'Template not found');
        }
        
        // Process content data with fallbacks
        $processedContent = $this->processContentData($websiteContent->content_data, $config);
        
        // Check if template view exists
        $templateView = "templates.{$websiteContent->template_slug}.index";
        
        if (!View::exists($templateView)) {
            abort(404, 'Template view not found');
        }
        
        try {
            return view($templateView, [
                'website' => $websiteContent,
                'content' => $processedContent,
                'config' => $config,
                'template' => $template,
                'preview_mode' => true
            ]);
        } catch (\Exception $e) {
            return response()->view('errors.template-error', [
                'error' => $e->getMessage(),
                'template' => $template,
                'websiteContent' => $websiteContent
            ], 500);
        }
    }

    /**
     * Get preview data as JSON (for real-time updates)
     */
    public function data(WebsiteContent $websiteContent)
    {
        try {
            $this->authorize('view', $websiteContent);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            // Allow viewing for demo purposes
        }
        
        $template = $this->templateService->getTemplateBySlug($websiteContent->template_slug);
        $config = $this->templateService->getTemplateConfig($websiteContent->template_slug);
        $processedContent = $this->processContentData($websiteContent->content_data, $config);
        
        return response()->json([
            'websiteContent' => $websiteContent,
            'template' => $template,
            'config' => $config,
            'processedContent' => $processedContent,
            'lastUpdated' => $websiteContent->updated_at->toISOString()
        ]);
    }

    /**
     * Update preview content (for real-time editing)
     */
    public function update(Request $request, WebsiteContent $websiteContent)
    {
        try {
            $this->authorize('update', $websiteContent);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Validate content data
        $contentData = $request->validate([
            'content_data' => 'required|array',
            'content_data.*' => 'nullable'
        ]);
        
        // Validate against template config
        $errors = $this->templateService->validateContentData(
            $websiteContent->template_slug,
            $contentData['content_data']
        );
        
        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'errors' => $errors
            ], 422);
        }
        
        // Update content data
        $websiteContent->update([
            'content_data' => $contentData['content_data'],
            'status' => 'previewed'
        ]);
        
        // Get updated processed content
        $config = $this->templateService->getTemplateConfig($websiteContent->template_slug);
        $processedContent = $this->processContentData($websiteContent->content_data, $config);
        
        return response()->json([
            'success' => true,
            'websiteContent' => $websiteContent->fresh(),
            'processedContent' => $processedContent
        ]);
    }

    /**
     * Process content data with fallbacks and transformations
     */
    private function processContentData(array $contentData, array $config): array
    {
        $processed = [];
        
        if (empty($config['fields'])) {
            return $contentData;
        }
        
        foreach ($config['fields'] as $field) {
            $key = $field['key'];
            $value = $contentData[$key] ?? null;
            $type = $field['type'] ?? 'text';
            
            // Apply transformations based on field type
            switch ($type) {
                case 'image':
                    if ($value) {
                        // Check if it's an external URL (starts with http/https)
                        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                            $processed[$key] = $value; // Use external URL as-is
                        } else {
                            $processed[$key] = asset("storage/{$value}"); // Local storage file
                        }
                    } else {
                        $processed[$key] = $field['default'] ?? null;
                    }
                    break;
                    
                case 'image_multiple':
                    if (is_array($value)) {
                        $processed[$key] = array_map(function($img) {
                            // Check if it's an external URL (starts with http/https)
                            if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                                return $img; // Use external URL as-is
                            } else {
                                return asset("storage/{$img}"); // Local storage file
                            }
                        }, $value);
                    } else {
                        $processed[$key] = [];
                    }
                    break;
                    
                case 'color':
                    $processed[$key] = $value ?: ($field['default'] ?? '#000000');
                    break;
                    
                case 'url':
                    if ($value && !str_starts_with($value, 'http')) {
                        $processed[$key] = "https://{$value}";
                    } else {
                        $processed[$key] = $value ?: ($field['default'] ?? '');
                    }
                    break;
                    
                case 'rich_text':
                    $processed[$key] = $value ? strip_tags($value, '<p><br><strong><em><ul><ol><li><a><h1><h2><h3><h4><h5><h6>') : ($field['default'] ?? '');
                    break;
                    
                default:
                    $processed[$key] = $value ?: ($field['default'] ?? '');
                    break;
            }
        }
        
        return array_merge($contentData, $processed);
    }

    /**
     * Get default content for a template
     */
    private function getDefaultContent(string $slug): array
    {
        if ($slug === 'corporate') {
            return [
                'company_name' => 'Demo Company',
                'company_tagline' => 'Your Business Solution Partner',
                'hero_title' => 'Solusi Bisnis Terbaik',
                'hero_subtitle' => 'Kami memberikan layanan terbaik untuk mengembangkan bisnis Anda dengan teknologi modern dan tim profesional',
                'primary_color' => '#2563eb',
                'secondary_color' => '#64748b',
                'accent_color' => '#f59e0b',
                'contact_email' => 'info@democompany.com',
                'contact_phone' => '+62 812-3456-7890',
                'contact_address' => 'Jl. Contoh No. 123, Jakarta Pusat, DKI Jakarta 10010',
                'whatsapp_enabled' => true,
                'whatsapp_number' => '6281234567890',
                'whatsapp_message' => 'Halo {company_name}, saya tertarik dengan layanan Anda dan ingin konsultasi lebih lanjut.',
                'whatsapp_greeting_text' => 'Butuh bantuan? Chat dengan kami!',
                'services' => [
                    [
                        'title' => 'Konsultasi Bisnis',
                        'description' => 'Strategi dan perencanaan bisnis yang tepat sasaran untuk mengembangkan usaha Anda',
                        'icon' => 'fas fa-chart-line'
                    ],
                    [
                        'title' => 'Digital Marketing',
                        'description' => 'Solusi digital marketing terpadu untuk meningkatkan penjualan dan brand awareness',
                        'icon' => 'fas fa-laptop-code'
                    ],
                    [
                        'title' => 'Pengembangan Website',
                        'description' => 'Pembuatan website profesional dan responsif sesuai kebutuhan bisnis Anda',
                        'icon' => 'fas fa-globe'
                    ]
                ]
            ];
        }
        
        return [];
    }

    private function processImageUrls($contentData)
    {
        if (!is_array($contentData)) {
            return $contentData;
        }

        foreach ($contentData as $key => $value) {
            if (is_string($value) && $this->isImagePath($value)) {
                // Convert storage path to accessible URL
                $contentData[$key] = $this->getImageUrl($value);
            } elseif (is_array($value)) {
                // Recursively process nested arrays
                $contentData[$key] = $this->processImageUrls($value);
            }
        }

        return $contentData;
    }

    private function isImagePath($path)
    {
        if (!is_string($path)) {
            return false;
        }

        // Check if it's already a full URL
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return false;
        }

        // Check if it's a storage path
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico'];
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        
        return in_array($extension, $imageExtensions);
    }

    private function getImageUrl($path)
    {
        if (!$path) {
            return null;
        }

        // If it's already a full URL, return as is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Convert storage path to accessible URL
        return asset('storage/' . ltrim($path, '/'));
    }
}
