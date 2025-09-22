<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type', // 'percentage' or 'fixed'
        'discount_value', // discount value (percentage or fixed amount)
        'min_purchase', // minimum purchase amount
        'max_discount', // maximum discount amount (for percentage)
        'usage_limit', // total usage limit
        'used_count', // current usage count
        'is_active',
        'starts_at',
        'expires_at'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    /**
     * Check if voucher is valid
     */
    public function isValid(): bool
    {
        return $this->is_active
            && (!$this->starts_at || $this->starts_at->isPast())
            && (!$this->expires_at || $this->expires_at->isFuture())
            && (!$this->usage_limit || $this->used_count < $this->usage_limit);
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount(float $subtotal): float
    {
        if (!$this->isValid()) {
            return 0.0;
        }

        if ($this->min_purchase && $subtotal < $this->min_purchase) {
            return 0.0;
        }

        $discount = 0.0;

        if ($this->type === 'percentage') {
            $discount = ($subtotal * (float)$this->value) / 100;
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = (float)$this->max_discount;
            }
        } elseif ($this->type === 'fixed') {
            $discount = (float)$this->value;
        }

        return min($discount, $subtotal); // Don't exceed subtotal
    }

    /**
     * Apply voucher to payment
     */
    public function applyToPayment(Payment $payment): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        $discount = $this->calculateDiscount($payment->amount);

        if ($discount > 0) {
            $payment->update([
                'voucher_code' => $this->code,
                'voucher_discount' => $discount,
                'final_amount' => $payment->gross_amount - $discount
            ]);

            $this->increment('used_count');
            return true;
        }

        return false;
    }

    /**
     * Scope for active vouchers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('starts_at')
                          ->orWhere('starts_at', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', now());
                    });
    }
}
