<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_content_id',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'amount',
        'currency',
        'status',
        'payment_type',
        'payment_method',
        'paid_at',
        'expires_at',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    // Relationships
    public function websiteContent()
    {
        return $this->belongsTo(WebsiteContent::class);
    }

    // Methods
    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
