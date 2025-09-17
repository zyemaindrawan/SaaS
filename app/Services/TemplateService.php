<?php

namespace App\Services;

use App\Models\Template;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class TemplateService
{
    public function getAllTemplates(string $category = null): Collection
    {
        $cacheKey = 'templates' . ($category ? "_category_{$category}" : '');
        
        return Cache::remember($cacheKey, 3600, function () use ($category) {
            $query = Template::active()->ordered();
            
            if ($category) {
                $query->byCategory($category);
            }
            
            return $query->get();
        });
    }

    public function getTemplateBySlug(string $slug): ?Template
    {
        return Cache::remember("template_{$slug}", 3600, function () use ($slug) {
            return Template::where('slug', $slug)
                ->where('is_active', true)
                ->first();
        });
    }

    public function getTemplateConfig(string $slug): array
    {
        $template = $this->getTemplateBySlug($slug);
        
        if (!$template) {
            return [];
        }
        
        return $template->getConfigData();
    }

    public function validateContentData(string $templateSlug, array $contentData): array
    {
        $config = $this->getTemplateConfig($templateSlug);
        $errors = [];
        
        if (empty($config['fields'])) {
            return $errors;
        }
        
        foreach ($config['fields'] as $field) {
            $key = $field['key'];
            $value = $contentData[$key] ?? null;
            
            // Required field validation
            if ($field['required'] ?? false) {
                if (empty($value)) {
                    $errors[$key] = "Field {$field['label']} is required";
                }
            }
            
            // Type validation
            if ($value && isset($field['type'])) {
                switch ($field['type']) {
                    case 'email':
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$key] = "Invalid email format";
                        }
                        break;
                    case 'url':
                        if (!filter_var($value, FILTER_VALIDATE_URL)) {
                            $errors[$key] = "Invalid URL format";
                        }
                        break;
                    case 'image_multiple':
                        $maxImages = $field['max'] ?? 10;
                        if (is_array($value) && count($value) > $maxImages) {
                            $errors[$key] = "Maximum {$maxImages} images allowed";
                        }
                        break;
                }
            }
        }
        
        return $errors;
    }

    public function getCategories(): array
    {
        return Cache::remember('template_categories', 3600, function () {
            return Template::active()
                ->whereNotNull('category')
                ->distinct()
                ->pluck('category')
                ->sort()
                ->values()
                ->toArray();
        });
    }
}
