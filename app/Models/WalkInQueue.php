<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalkInQueue extends Model
{
    use HasFactory;

    public const STATUS_WAITING = 'waiting';
    public const STATUS_SERVING = 'serving';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_SKIPPED = 'skipped';

    public const ACTIVE_STATUSES = [
        self::STATUS_WAITING,
        self::STATUS_SERVING,
    ];

    protected $fillable = [
        'queue_date',
        'queue_number',
        'queue_code',
        'customer_id',
        'customer_name',
        'customer_phone',
        'recipient_age',
        'barber_id',
        'service_id',
        'price',
        'estimated_wait_minutes',
        'status',
        'notes',
        'called_at',
        'started_at',
        'completed_at',
        'skipped_at',
    ];

    protected $casts = [
        'queue_date' => 'date',
        'recipient_age' => 'integer',
        'price' => 'decimal:2',
        'estimated_wait_minutes' => 'integer',
        'called_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'skipped_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function barber()
    {
        return $this->belongsTo(User::class, 'barber_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('queue_date', today('Asia/Kuala_Lumpur'));
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', self::ACTIVE_STATUSES);
    }

    public function getDisplayCustomerNameAttribute(): string
    {
        return $this->customer_name ?: ($this->customer->name ?? 'Walk-in Customer');
    }

    public function getStatusLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }

    public function getFormattedWaitAttribute(): string
    {
        if ($this->status === self::STATUS_SERVING) {
            return 'Serving now';
        }

        if ($this->status !== self::STATUS_WAITING) {
            return '-';
        }

        if ($this->estimated_wait_minutes <= 0) {
            return 'Next';
        }

        return $this->estimated_wait_minutes . ' min';
    }

    public function hasChildRate(): bool
    {
        return $this->recipient_age !== null
            && $this->recipient_age < Appointment::CHILD_RATE_AGE_LIMIT
            && (float) $this->price === Appointment::CHILD_RATE_PRICE;
    }
}
