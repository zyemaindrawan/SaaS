<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WebsiteContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'template_slug',
        'website_name',
        'content_data',
        'custom_domain',
        'subdomain',
        'status',
        'activated_at',
        'expires_at'
    ];

    protected $casts = [
        'content_data' => 'array',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_slug', 'slug');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function getPublicUrl(): string
    {
        if ($this->custom_domain) {
            return "https://{$this->custom_domain}";
        }
        
        if ($this->subdomain) {
            return "https://{$this->subdomain}." . config('app.domain');
        }
        
        return route('website.view', $this->id);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function canPreview(): bool
    {
        return in_array($this->status, ['draft', 'previewed', 'paid', 'active']);
    }
}
