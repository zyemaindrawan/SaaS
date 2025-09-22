<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWebsiteContentRequest;
use App\Http\Requests\UpdateWebsiteContentRequest;
use App\Models\WebsiteContent;
use App\Models\User;
use App\Services\WebsiteContentService;
use App\Services\TemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WebsiteBuilderController extends Controller
{
    protected WebsiteContentService $websiteContentService;
    protected TemplateService $templateService;

    public function __construct(
        WebsiteContentService $websiteContentService,
        TemplateService $templateService
    ) {
        $this->websiteContentService = $websiteContentService;
        $this->templateService = $templateService;
    }

    public function create(Request $request)
    {
        $templateSlug = $request->query('template');

        if (!$templateSlug) {
            return redirect()->route('templates.index')
                ->with('error', 'Please select a template first');
        }

        $template = $this->templateService->getTemplateBySlug($templateSlug);

        if (!$template) {
            abort(404, 'Template not found');
        }

        $config = $this->templateService->getTemplateConfig($templateSlug);

        return Inertia::render('WebsiteBuilder/Create', [
            'template' => $template,
            'config' => $config
        ]);
    }

    public function store(StoreWebsiteContentRequest $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login');
            }

            $websiteContent = $this->websiteContentService->createWebsiteContent(
                $user,
                $request->validated()
            );

            return redirect()->route('website-builder.edit', $websiteContent)
                ->with('success', 'Website content created successfully');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(WebsiteContent $websiteContent)
    {
        $this->authorize('update', $websiteContent);

        $template = $this->templateService->getTemplateBySlug($websiteContent->template_slug);
        $config = $this->templateService->getTemplateConfig($websiteContent->template_slug);

        return Inertia::render('WebsiteBuilder/Edit', [
            'websiteContent' => $websiteContent,
            'template' => $template,
            'config' => $config
        ]);
    }

    public function update(UpdateWebsiteContentRequest $request, WebsiteContent $websiteContent)
    {
        $this->authorize('update', $websiteContent);

        try {
            $updatedWebsiteContent = $this->websiteContentService->updateWebsiteContent(
                $websiteContent,
                $request->validated()
            );

            return back()->with('success', 'Website content updated successfully');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function preview(WebsiteContent $websiteContent)
    {
        $this->authorize('view', $websiteContent);

        try {
            $previewData = $this->websiteContentService->generatePreview($websiteContent);

            return Inertia::render('WebsiteBuilder/Preview', $previewData);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
