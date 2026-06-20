<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use HasFactory;

    public const PAYMENT_RETRY_MINUTES = 5;

    public const ORDER_PENDING = 'pending';
    public const ORDER_PROCESSING = 'processing';
    public const ORDER_READY = 'ready_for_pickup';
    public const ORDER_RECEIVED = 'received';
    public const ORDER_NEEDS_REVIEW = 'needs_review';
    public const ORDER_CANCELLED = 'cancelled';

    public const ORDER_STATUS_LABELS = [
        self::ORDER_PENDING => 'Waiting payment',
        self::ORDER_PROCESSING => 'Processing',
        self::ORDER_READY => 'Ready for pickup',
        self::ORDER_RECEIVED => 'Received',
        self::ORDER_NEEDS_REVIEW => 'Needs review',
        self::ORDER_CANCELLED => 'Cancelled',
    ];

    protected $fillable = [
        'order_number',
        'customer_id',
        'staff_id',
        'customer_name',
        'customer_phone',
        'order_type',
        'payment_method',
        'payment_status',
        'order_status',
        'total',
        'stripe_session_id',
        'payment_expires_at',
        'paid_at',
        'stock_reduced_at',
        'received_at',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'payment_expires_at' => 'datetime',
        'paid_at' => 'datetime',
        'stock_reduced_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function items()
    {
        return $this->hasMany(ProductOrderItem::class);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeOnline($query)
    {
        return $query->where('order_type', 'online');
    }

    public function scopeNotReceived($query)
    {
        return $query->whereNotIn('order_status', [self::ORDER_RECEIVED, self::ORDER_CANCELLED]);
    }

    public function scopeExpiredPendingPayment($query, ?CarbonInterface $now = null)
    {
        $now = $now ?: now('Asia/Kuala_Lumpur');

        return $query->where('order_type', 'online')
            ->where('payment_status', 'pending_payment')
            ->where('order_status', self::ORDER_PENDING)
            ->whereNotNull('payment_expires_at')
            ->where('payment_expires_at', '<=', $now);
    }

    public function getOrderStatusLabelAttribute(): string
    {
        return self::ORDER_STATUS_LABELS[$this->order_status] ?? ucfirst(str_replace('_', ' ', (string) $this->order_status));
    }

    public static function trackableStatuses(): array
    {
        return self::ORDER_STATUS_LABELS;
    }

    public static function fulfilmentStatuses(): array
    {
        return [
            self::ORDER_PROCESSING => self::ORDER_STATUS_LABELS[self::ORDER_PROCESSING],
            self::ORDER_READY => self::ORDER_STATUS_LABELS[self::ORDER_READY],
            self::ORDER_RECEIVED => self::ORDER_STATUS_LABELS[self::ORDER_RECEIVED],
            self::ORDER_NEEDS_REVIEW => self::ORDER_STATUS_LABELS[self::ORDER_NEEDS_REVIEW],
            self::ORDER_CANCELLED => self::ORDER_STATUS_LABELS[self::ORDER_CANCELLED],
        ];
    }

    public static function generateOrderNumber(string $prefix = 'ORD'): string
    {
        do {
            $number = $prefix . now('Asia/Kuala_Lumpur')->format('YmdHis') . random_int(100, 999);
        } while (self::where('order_number', $number)->exists());

        return $number;
    }

    public function startPaymentWindow(?CarbonInterface $now = null): CarbonInterface
    {
        $deadline = ($now ?: now('Asia/Kuala_Lumpur'))->copy()->addMinutes(self::PAYMENT_RETRY_MINUTES);

        $this->forceFill([
            'payment_status' => 'pending_payment',
            'order_status' => self::ORDER_PENDING,
            'payment_expires_at' => $deadline,
            'paid_at' => null,
        ])->save();

        return $deadline;
    }

    public function ensurePaymentWindow(): ?CarbonInterface
    {
        if ($this->order_type !== 'online' || $this->payment_status !== 'pending_payment') {
            return null;
        }

        $deadline = $this->paymentDeadline();

        if (!$deadline) {
            $deadline = now('Asia/Kuala_Lumpur')->addMinutes(self::PAYMENT_RETRY_MINUTES);
            $this->forceFill(['payment_expires_at' => $deadline])->save();
        }

        return $deadline;
    }

    public function paymentDeadline(): ?CarbonInterface
    {
        if ($this->payment_expires_at) {
            return $this->payment_expires_at->copy()->timezone('Asia/Kuala_Lumpur');
        }

        return null;
    }

    public function isPaymentExpired(?CarbonInterface $now = null): bool
    {
        if ($this->order_type !== 'online' || $this->payment_status !== 'pending_payment') {
            return false;
        }

        $deadline = $this->paymentDeadline();

        return $deadline !== null && $deadline->lte($now ?: now('Asia/Kuala_Lumpur'));
    }

    public function canRetryPayment(?CarbonInterface $now = null): bool
    {
        return $this->payment_status === 'pending_payment'
            && $this->order_status === self::ORDER_PENDING
            && !$this->isPaymentExpired($now);
    }

    public function paymentMinutesRemaining(?CarbonInterface $now = null): int
    {
        $deadline = $this->paymentDeadline();

        if (!$deadline || $this->payment_status !== 'pending_payment') {
            return 0;
        }

        $now = $now ?: now('Asia/Kuala_Lumpur');

        return max(0, (int) ceil($now->diffInSeconds($deadline, false) / 60));
    }

    public function cancelIfPaymentExpired(?CarbonInterface $now = null): bool
    {
        $now = $now ?: now('Asia/Kuala_Lumpur');

        if (!$this->isPaymentExpired($now)) {
            return false;
        }

        $this->forceFill([
            'payment_status' => 'cancelled',
            'order_status' => self::ORDER_CANCELLED,
            'updated_at' => $now,
        ])->save();

        return true;
    }

    public static function cancelExpiredPendingPayments(?CarbonInterface $now = null): int
    {
        $now = $now ?: now('Asia/Kuala_Lumpur');

        return static::expiredPendingPayment($now)->update([
            'payment_status' => 'cancelled',
            'order_status' => self::ORDER_CANCELLED,
            'updated_at' => $now,
        ]);
    }
}
