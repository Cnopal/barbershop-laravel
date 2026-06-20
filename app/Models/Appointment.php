<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    public const BOOKING_FOR_SELF = 'self';
    public const BOOKING_FOR_OTHER = 'other';
    public const CHILD_RATE_AGE_LIMIT = 12;
    public const CHILD_RATE_PRICE = 15.00;
    public const PAYMENT_RETRY_MINUTES = 2;
    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

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
        'stripe_session_id',
        'payment_expires_at',
        'paid_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'price' => 'decimal:2',
        'recipient_age' => 'integer',
        'payment_expires_at' => 'datetime',
        'paid_at' => 'datetime',
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

    public function startPaymentWindow(?CarbonInterface $now = null): CarbonInterface
    {
        $deadline = ($now ?: now('Asia/Kuala_Lumpur'))->copy()->addMinutes(self::PAYMENT_RETRY_MINUTES);

        $this->forceFill([
            'status' => self::STATUS_PENDING_PAYMENT,
            'payment_expires_at' => $deadline,
            'paid_at' => null,
        ])->save();

        return $deadline;
    }

    public function ensurePaymentWindow(): ?CarbonInterface
    {
        if ($this->status !== self::STATUS_PENDING_PAYMENT) {
            return null;
        }

        $deadline = $this->paymentDeadline();

        if (!$deadline) {
            $deadline = now('Asia/Kuala_Lumpur')->addMinutes(self::PAYMENT_RETRY_MINUTES);
        }

        if (!$this->payment_expires_at) {
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
        if ($this->status !== self::STATUS_PENDING_PAYMENT) {
            return false;
        }

        $deadline = $this->paymentDeadline();

        return $deadline !== null && $deadline->lte($now ?: now('Asia/Kuala_Lumpur'));
    }

    public function canRetryPayment(?CarbonInterface $now = null): bool
    {
        return $this->status === self::STATUS_PENDING_PAYMENT
            && !$this->isPaymentExpired($now);
    }

    public function paymentMinutesRemaining(?CarbonInterface $now = null): int
    {
        $deadline = $this->paymentDeadline();

        if (!$deadline || $this->status !== self::STATUS_PENDING_PAYMENT) {
            return 0;
        }

        $now = $now ?: now('Asia/Kuala_Lumpur');

        return max(0, (int) ceil($now->diffInSeconds($deadline, false) / 60));
    }

    public function cancelIfPaymentExpired(?CarbonInterface $now = null): bool
    {
        if (!$this->isPaymentExpired($now)) {
            return false;
        }

        $this->forceFill(['status' => self::STATUS_CANCELLED])->save();

        return true;
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
                     ->whereIn('status', [self::STATUS_PENDING_PAYMENT, self::STATUS_CONFIRMED]);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBetweenDates($query, $start, $end)
    {
        return $query->whereBetween('appointment_date', [$start, $end]);
    }

    public function scopeExpiredPendingPayment($query, ?CarbonInterface $now = null)
    {
        $now = $now ?: now('Asia/Kuala_Lumpur');

        return $query->where('status', self::STATUS_PENDING_PAYMENT)
            ->whereNotNull('payment_expires_at')
            ->where('payment_expires_at', '<=', $now);
    }

    public static function cancelExpiredPendingPayments(?CarbonInterface $now = null): int
    {
        $now = $now ?: now('Asia/Kuala_Lumpur');

        return static::expiredPendingPayment($now)->update([
            'status' => self::STATUS_CANCELLED,
            'updated_at' => $now,
        ]);
    }
}
