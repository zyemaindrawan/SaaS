<?php
// app/Models/PaymentLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentLog extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     * We only use created_at, not updated_at
     */
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     */
    protected $dates = ['created_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'payment_id',
        'action',
        'description',
        'data',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically set created_at if not provided
        static::creating(function ($model) {
            if (!$model->created_at) {
                $model->created_at = now();
            }
        });
    }

    /**
     * Get the payment that owns the log.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Scope for filtering by action.
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for filtering by payment.
     */
    public function scopeByPayment($query, int $paymentId)
    {
        return $query->where('payment_id', $paymentId);
    }

    /**
     * Scope for recent logs.
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for ordering by latest.
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope for chronological order.
     */
    public function scopeChronological($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Get formatted action name.
     */
    public function getFormattedActionAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->action));
    }

    /**
     * Get human readable time.
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Check if log has additional data.
     */
    public function hasData(): bool
    {
        return !empty($this->data);
    }

    /**
     * Get specific data value.
     */
    public function getData(string $key, $default = null)
    {
        return data_get($this->data, $key, $default);
    }

    /**
     * Create log entry (static method for convenience).
     */
    public static function createLog(int $paymentId, string $action, string $description = null, array $data = []): self
    {
        return self::create([
            'payment_id' => $paymentId,
            'action' => $action,
            'description' => $description,
            'data' => $data,
        ]);
    }

    /**
     * Common log actions constants.
     */
    const ACTION_CREATED = 'created';
    const ACTION_INITIATED = 'initiated';
    const ACTION_PROCESSING = 'processing';
    const ACTION_SUCCESS = 'success';
    const ACTION_PAID = 'paid';
    const ACTION_FAILED = 'failed';
    const ACTION_CANCELLED = 'cancelled';
    const ACTION_EXPIRED = 'expired';
    const ACTION_REFUNDED = 'refunded';
    const ACTION_ACTIVATION_QUEUED = 'activation_queued';
    const ACTION_ACTIVATION_COMPLETED = 'activation_completed';
    const ACTION_NOTIFICATION_RECEIVED = 'notification_received';
    const ACTION_SNAP_TOKEN_GENERATED = 'snap_token_generated';
    const ACTION_SNAP_TOKEN_ERROR = 'snap_token_error';

    /**
     * Get all available actions.
     */
    public static function getActions(): array
    {
        return [
            self::ACTION_CREATED => 'Payment Created',
            self::ACTION_INITIATED => 'Payment Initiated',
            self::ACTION_PROCESSING => 'Payment Processing',
            self::ACTION_SUCCESS => 'Payment Success',
            self::ACTION_PAID => 'Payment Paid',
            self::ACTION_FAILED => 'Payment Failed',
            self::ACTION_CANCELLED => 'Payment Cancelled',
            self::ACTION_EXPIRED => 'Payment Expired',
            self::ACTION_REFUNDED => 'Payment Refunded',
            self::ACTION_ACTIVATION_QUEUED => 'Activation Queued',
            self::ACTION_ACTIVATION_COMPLETED => 'Activation Completed',
            self::ACTION_NOTIFICATION_RECEIVED => 'Notification Received',
            self::ACTION_SNAP_TOKEN_GENERATED => 'Snap Token Generated',
            self::ACTION_SNAP_TOKEN_ERROR => 'Snap Token Error',
        ];
    }

    /**
     * Get action color for UI display.
     */
    public function getActionColorAttribute(): string
    {
        return match($this->action) {
            self::ACTION_CREATED, self::ACTION_INITIATED => 'blue',
            self::ACTION_PROCESSING => 'yellow',
            self::ACTION_SUCCESS, self::ACTION_PAID, self::ACTION_ACTIVATION_COMPLETED => 'green',
            self::ACTION_FAILED, self::ACTION_CANCELLED, self::ACTION_EXPIRED, self::ACTION_SNAP_TOKEN_ERROR => 'red',
            self::ACTION_REFUNDED => 'orange',
            self::ACTION_ACTIVATION_QUEUED, self::ACTION_NOTIFICATION_RECEIVED, self::ACTION_SNAP_TOKEN_GENERATED => 'indigo',
            default => 'gray',
        };
    }

    /**
     * Get action icon for UI display.
     */
    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            self::ACTION_CREATED => 'plus-circle',
            self::ACTION_INITIATED => 'play-circle',
            self::ACTION_PROCESSING => 'clock',
            self::ACTION_SUCCESS, self::ACTION_PAID => 'check-circle',
            self::ACTION_FAILED, self::ACTION_CANCELLED => 'x-circle',
            self::ACTION_EXPIRED => 'exclamation-triangle',
            self::ACTION_REFUNDED => 'arrow-left',
            self::ACTION_ACTIVATION_QUEUED => 'queue-list',
            self::ACTION_ACTIVATION_COMPLETED => 'rocket-launch',
            self::ACTION_NOTIFICATION_RECEIVED => 'bell',
            self::ACTION_SNAP_TOKEN_GENERATED => 'key',
            self::ACTION_SNAP_TOKEN_ERROR => 'exclamation-circle',
            default => 'information-circle',
        };
    }
}
