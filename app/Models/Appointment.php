<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    public const BOOKING_FOR_SELF = 'self';
    public const BOOKING_FOR_OTHER = 'other';
    public const CHILD_RATE_AGE_LIMIT = 12;
    public const CHILD_RATE_PRICE = 15.00;

    protected $fillable = [
        'customer_id',
        'booking_for',
        'recipient_name',
        'recipient_age',
        'barber_id',
        'service_id',
        'appointment_date',
        'start_time',
        'end_time',
        'price',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'price' => 'decimal:2',
        'recipient_age' => 'integer',
    ];

    /* =========================
        RELATIONSHIPS
    ========================== */

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

    public static function priceForRecipient(Service $service, ?int $recipientAge): float
    {
        if ($recipientAge !== null && $recipientAge < self::CHILD_RATE_AGE_LIMIT) {
            return self::CHILD_RATE_PRICE;
        }

        return (float) $service->price;
    }

    public static function recipientPayload(User $customer, array $input, ?self $appointment = null): array
    {
        $bookingFor = $input['booking_for'] ?? $appointment?->booking_for ?? self::BOOKING_FOR_SELF;
        $bookingFor = $bookingFor === self::BOOKING_FOR_OTHER ? self::BOOKING_FOR_OTHER : self::BOOKING_FOR_SELF;

        if ($bookingFor === self::BOOKING_FOR_OTHER) {
            return [
                'booking_for' => self::BOOKING_FOR_OTHER,
                'recipient_name' => trim((string) ($input['recipient_name'] ?? $appointment?->recipient_name ?? '')),
                'recipient_age' => isset($input['recipient_age'])
                    ? (int) $input['recipient_age']
                    : $appointment?->recipient_age,
            ];
        }

        return [
            'booking_for' => self::BOOKING_FOR_SELF,
            'recipient_name' => $customer->name,
            'recipient_age' => null,
        ];
    }

    public function getRecipientDisplayNameAttribute(): string
    {
        return $this->recipient_name ?: ($this->customer->name ?? 'Customer');
    }

    public function hasChildRate(): bool
    {
        return $this->recipient_age !== null && $this->recipient_age < self::CHILD_RATE_AGE_LIMIT;
    }

    /* =========================
        QUERY SCOPES
    ========================== */

    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('appointment_date', '>=', today())
                     ->whereIn('status', ['pending', 'confirmed']);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBetweenDates($query, $start, $end)
    {
        return $query->whereBetween('appointment_date', [$start, $end]);
    }



    
}
