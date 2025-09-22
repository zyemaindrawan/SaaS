<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'website_content_id',
        'midtrans_order_id',
        'snap_token',
        'amount',
        'discount',
        'fee',
        'gross_amount',
        'voucher_code',
        'voucher_discount',
        'final_amount',
        'status',
        'payment_type',
        'transaction_time',
        'midtrans_data',
        'expired_at',
        'paid_at',
    ];

    protected $casts = [
        'midtrans_data' => 'array',
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'gross_amount' => 'decimal:2',
        'voucher_discount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'transaction_time' => 'datetime',
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
            $code = 'DR-' . strtoupper(Str::random(8));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PaymentLog::class)->latest();
    }

    public function chronologicalLogs(): HasMany
    {
        return $this->hasMany(PaymentLog::class)->chronological();
    }

    public function recentLogs(int $days = 7): HasMany
    {
        return $this->hasMany(PaymentLog::class)->recent($days);
    }

    public function createLog(string $action, string $description = null, array $data = []): PaymentLog
    {
        return $this->logs()->create([
            'action' => $action,
            'description' => $description,
            'data' => $data,
        ]);
    }

    public function latestLog(): ?PaymentLog
    {
        return $this->logs()->first();
    }

    public function hasLogAction(string $action): bool
    {
        return $this->logs()->where('action', $action)->exists();
    }

    public function getLogsByAction(string $action)
    {
        return $this->logs()->byAction($action)->get();
    }
}
