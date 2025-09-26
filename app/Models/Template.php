<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    // Relationships
    public function websiteContents()
    {
        return $this->hasMany(WebsiteContent::class, 'template_slug', 'slug');
    }

    // Scopes
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

    // Methods
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
        // If preview_image is already a full URL, return it
        if ($this->preview_image && (str_starts_with($this->preview_image, 'http://') || str_starts_with($this->preview_image, 'https://'))) {
            return $this->preview_image;
        }

        // Check if preview_image exists
        if ($this->preview_image) {
            $storagePath = public_path('storage/template-previews/' . $this->preview_image);
            $publicPath = public_path('template-previews/' . $this->preview_image);
            
            // Log paths for debugging
            \Illuminate\Support\Facades\Log::debug('Checking paths:', [
                'storage' => $storagePath,
                'public' => $publicPath,
                'filename' => $this->preview_image
            ]);
            
            // Check in public/storage/template-previews first
            if (file_exists($storagePath)) {
                return url('storage/template-previews/' . $this->preview_image);
            }
            
            // Then check in public/template-previews
            if (file_exists($publicPath)) {
                return url('template-previews/' . $this->preview_image);
            }
        }

        // Fallback to default image
        return url('default-avatar.png');
    }
}
