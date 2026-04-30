<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use HasFactory;

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
        'paid_at',
        'stock_reduced_at',
        'received_at',
    ];

    protected $casts = [
        'total' => 'decimal:2',
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
}
