<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'website_content_id',
        'amount',
        'fee',
        'gross_amount',
        'status',
        'payment_type',
        'midtrans_data',
        'expired_at',
        'paid_at',
    ];

    protected $casts = [
        'midtrans_data' => 'array',
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'gross_amount' => 'decimal:2',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function websiteContent()
    {
        return $this->belongsTo(WebsiteContent::class);
    }

    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public static function generateCode(): string
    {
        do {
            $code = 'DR-' . strtoupper(\Str::random(10));
        } while (self::where('code', $code)->exists());
        
        return $code;
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PaymentLog::class)->latest();
    }

    /**
     * Get payment logs in chronological order.
     */
    public function chronologicalLogs(): HasMany
    {
        return $this->hasMany(PaymentLog::class)->chronological();
    }

    /**
     * Get recent payment logs.
     */
    public function recentLogs(int $days = 7): HasMany
    {
        return $this->hasMany(PaymentLog::class)->recent($days);
    }

    /**
     * Create a payment log.
     */
    public function createLog(string $action, string $description = null, array $data = []): PaymentLog
    {
        return $this->logs()->create([
            'action' => $action,
            'description' => $description,
            'data' => $data,
        ]);
    }

    /**
     * Get the latest log entry.
     */
    public function latestLog(): ?PaymentLog
    {
        return $this->logs()->first();
    }

    /**
     * Check if payment has specific log action.
     */
    public function hasLogAction(string $action): bool
    {
        return $this->logs()->where('action', $action)->exists();
    }

    /**
     * Get logs by specific action.
     */
    public function getLogsByAction(string $action)
    {
        return $this->logs()->byAction($action)->get();
    }

}
