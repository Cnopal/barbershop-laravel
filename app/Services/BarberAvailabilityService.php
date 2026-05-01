<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\WalkInQueue;
use Carbon\Carbon;

class BarberAvailabilityService
{
    private const DEFAULT_SERVICE_MINUTES = 30;

    public const BLOCKING_APPOINTMENT_STATUSES = [
        'pending',
        'pending_payment',
        'confirmed',
    ];

    public function findAppointmentConflict(
        int $barberId,
        string $date,
        Carbon $start,
        Carbon $end,
        ?int $excludeAppointmentId = null
    ): ?Appointment {
        return Appointment::with(['customer', 'service'])
            ->where('barber_id', $barberId)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', self::BLOCKING_APPOINTMENT_STATUSES)
            ->when($excludeAppointmentId, fn ($query) => $query->where('id', '!=', $excludeAppointmentId))
            ->where(function ($query) use ($start, $end) {
                $query->where('start_time', '<', $end->format('H:i:s'))
                    ->where('end_time', '>', $start->format('H:i:s'));
            })
            ->orderBy('start_time')
            ->first();
    }

    public function findServingWalkInConflict(
        int $barberId,
        string $date,
        Carbon $start,
        Carbon $end,
        ?int $excludeWalkInId = null
    ): ?WalkInQueue {
        return WalkInQueue::with(['customer', 'service'])
            ->where('barber_id', $barberId)
            ->whereDate('queue_date', $date)
            ->where('status', WalkInQueue::STATUS_SERVING)
            ->when($excludeWalkInId, fn ($query) => $query->where('id', '!=', $excludeWalkInId))
            ->get()
            ->first(function (WalkInQueue $queue) use ($start, $end) {
                [$walkInStart, $walkInEnd] = $this->walkInWindow($queue);

                return $walkInStart->lt($end) && $walkInEnd->gt($start);
            });
    }

    public function slotHasConflict(
        int $barberId,
        string $date,
        string $startTime,
        string $endTime,
        ?int $excludeAppointmentId = null
    ): bool {
        $start = Carbon::parse($date . ' ' . $startTime, 'Asia/Kuala_Lumpur');
        $end = Carbon::parse($date . ' ' . $endTime, 'Asia/Kuala_Lumpur');

        return (bool) $this->findAppointmentConflict($barberId, $date, $start, $end, $excludeAppointmentId)
            || (bool) $this->findServingWalkInConflict($barberId, $date, $start, $end);
    }

    public function nextAvailableStartAroundAppointments(?int $barberId, Carbon $desiredStart, int $durationMinutes): Carbon
    {
        if (!$barberId) {
            return $desiredStart->copy();
        }

        $candidate = $desiredStart->copy();

        for ($attempt = 0; $attempt < 8; $attempt++) {
            $candidateEnd = $candidate->copy()->addMinutes($durationMinutes);
            $conflict = $this->findAppointmentConflict(
                $barberId,
                $candidate->toDateString(),
                $candidate,
                $candidateEnd
            );

            if (!$conflict) {
                return $candidate;
            }

            $candidate = Carbon::parse(
                $conflict->appointment_date->format('Y-m-d') . ' ' . $conflict->end_time,
                'Asia/Kuala_Lumpur'
            );
        }

        return $candidate;
    }

    public function appointmentConflictMessage(Appointment $appointment): string
    {
        $start = Carbon::parse($appointment->start_time)->format('h:i A');
        $end = Carbon::parse($appointment->end_time)->format('h:i A');
        $customer = $appointment->recipient_display_name ?? $appointment->customer?->name ?? 'a customer';
        $service = $appointment->service?->name ?? 'appointment';

        return "This barber has {$service} for {$customer} from {$start} to {$end}.";
    }

    public function walkInConflictMessage(WalkInQueue $queue): string
    {
        [$start, $end] = $this->walkInWindow($queue);
        $customer = $queue->display_customer_name;

        return "This barber is serving walk-in {$queue->queue_code} for {$customer} until {$end->format('h:i A')}.";
    }

    public function walkInWindow(WalkInQueue $queue): array
    {
        $startedAt = $queue->started_at ?: $queue->updated_at ?: now('Asia/Kuala_Lumpur');
        $start = $startedAt->copy()->timezone('Asia/Kuala_Lumpur');
        $duration = $queue->service?->duration ?: self::DEFAULT_SERVICE_MINUTES;

        return [$start, $start->copy()->addMinutes($duration)];
    }
}
