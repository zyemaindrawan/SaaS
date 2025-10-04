<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'preview_image',
        'config_path',
        'price',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function websiteContents()
    {
        return $this->hasMany(WebsiteContent::class, 'template_slug', 'slug');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getConfigData(): array
    {
        $configPath = resource_path("views/templates/{$this->slug}/config.json");

        if (file_exists($configPath)) {
            return json_decode(file_get_contents($configPath), true);
        }

        return [];
    }

    public function getPreviewUrl(): string
    {
        if ($this->preview_image && (str_starts_with($this->preview_image, 'http://') || str_starts_with($this->preview_image, 'https://'))) {
            return $this->preview_image;
        }

        if ($this->preview_image) {
            $normalizedPath = ltrim($this->preview_image, '/');

            if (str_starts_with($normalizedPath, 'storage/')) {
                $normalizedPath = substr($normalizedPath, strlen('storage/'));
            }

            $relativePaths = [$normalizedPath];

            if (!str_contains($normalizedPath, '/')) {
                $relativePaths[] = "template-previews/{$this->slug}/{$normalizedPath}";
                $relativePaths[] = "templates/{$this->slug}/{$normalizedPath}";
            }

            foreach ($relativePaths as $relativePath) {
                if (Storage::disk('public')->exists($relativePath)) {
                    return Storage::disk('public')->url($relativePath);
                }

                $publicPath = public_path($relativePath);
                if (file_exists($publicPath)) {
                    return asset($relativePath);
                }

                $publicStoragePath = public_path('storage/' . $relativePath);
                if (file_exists($publicStoragePath)) {
                    return asset('storage/' . $relativePath);
                }
            }
        }

        $imageFormats = ['.webp', '.png', '.jpg', '.jpeg', '.gif'];
        foreach ($imageFormats as $format) {
            $legacyPath = "template-previews/{$this->slug}/preview{$format}";

            if (Storage::disk('public')->exists($legacyPath)) {
                return Storage::disk('public')->url($legacyPath);
            }

            $publicLegacyPath = public_path('storage/' . $legacyPath);
            if (file_exists($publicLegacyPath)) {
                return asset('storage/' . $legacyPath);
            }
        }

        return asset('default-avatar.png');
    }
}
