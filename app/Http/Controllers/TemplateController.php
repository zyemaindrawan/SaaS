<?php

namespace App\Http\Controllers;

use App\Services\TemplateService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TemplateController extends Controller
{
    public function __construct(
        private TemplateService $templateService
    ) {}

    public function index(Request $request)
    {
        $category = $request->query('category');
        $templates = $this->templateService->getAllTemplates($category);
        $categories = $this->templateService->getCategories();
        
        return Inertia::render('Templates/Index', [
            'templates' => $templates,
            'categories' => $categories,
            'selectedCategory' => $category
        ]);
    }

    public function show(string $slug)
    {
        $template = $this->templateService->getTemplateBySlug($slug);
        
        if (!$template) {
            abort(404, 'Template not found');
        }
        
        $config = $this->templateService->getTemplateConfig($slug);
        
        return Inertia::render('Templates/Show', [
            'template' => $template,
            'config' => $config
        ]);
    }
}
