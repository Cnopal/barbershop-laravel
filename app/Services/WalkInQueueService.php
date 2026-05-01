<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\WalkInQueue;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WalkInQueueService
{
    private const DEFAULT_SERVICE_MINUTES = 30;

    public function __construct(private BarberAvailabilityService $availability)
    {
    }

    public function create(array $data): WalkInQueue
    {
        return DB::transaction(function () use ($data) {
            $date = $data['queue_date'] ?? today('Asia/Kuala_Lumpur')->toDateString();
            $nextNumber = ((int) WalkInQueue::whereDate('queue_date', $date)->lockForUpdate()->max('queue_number')) + 1;
            $service = Service::findOrFail($data['service_id']);
            $recipientAge = isset($data['recipient_age']) ? (int) $data['recipient_age'] : null;

            $queue = WalkInQueue::create([
                'queue_date' => $date,
                'queue_number' => $nextNumber,
                'queue_code' => $this->makeQueueCode($date, $nextNumber),
                'customer_id' => $data['customer_id'] ?? null,
                'customer_name' => $data['customer_name'] ?? null,
                'customer_phone' => $data['customer_phone'] ?? null,
                'recipient_age' => $recipientAge,
                'barber_id' => $data['barber_id'] ?? null,
                'service_id' => $service->id,
                'price' => Appointment::priceForRecipient($service, $recipientAge),
                'estimated_wait_minutes' => 0,
                'status' => WalkInQueue::STATUS_WAITING,
                'notes' => $data['notes'] ?? null,
            ]);

            $this->recalculateEstimates($date);

            return $queue->fresh(['customer', 'barber', 'service']);
        });
    }

    public function updateStatus(WalkInQueue $queue, string $status): WalkInQueue
    {
        return DB::transaction(function () use ($queue, $status) {
            $updates = ['status' => $status];

            if ($status === WalkInQueue::STATUS_SERVING) {
                $this->ensureCanStartServing($queue);

                $now = now('Asia/Kuala_Lumpur');
                $updates['called_at'] = $queue->called_at ?? $now;
                $updates['started_at'] = $queue->started_at ?? $now;
                $updates['completed_at'] = null;
                $updates['skipped_at'] = null;
            }

            if ($status === WalkInQueue::STATUS_COMPLETED) {
                $updates['completed_at'] = now('Asia/Kuala_Lumpur');
            }

            if ($status === WalkInQueue::STATUS_SKIPPED) {
                $updates['skipped_at'] = now('Asia/Kuala_Lumpur');
            }

            if ($status === WalkInQueue::STATUS_WAITING) {
                $updates['started_at'] = null;
                $updates['completed_at'] = null;
                $updates['skipped_at'] = null;
            }

            $queue->update($updates);
            $this->recalculateEstimates($queue->queue_date->toDateString());

            return $queue->fresh(['customer', 'barber', 'service']);
        });
    }

    public function recalculateEstimates(string $date): void
    {
        $runningWait = 0;
        $calculationStart = Carbon::parse($date, 'Asia/Kuala_Lumpur')->isToday()
            ? now('Asia/Kuala_Lumpur')
            : Carbon::parse($date . ' 09:00:00', 'Asia/Kuala_Lumpur');

        $queues = WalkInQueue::with('service')
            ->whereDate('queue_date', $date)
            ->whereIn('status', WalkInQueue::ACTIVE_STATUSES)
            ->orderBy('queue_number')
            ->get();

        foreach ($queues as $queue) {
            $duration = $queue->service?->duration ?: self::DEFAULT_SERVICE_MINUTES;

            if ($queue->status === WalkInQueue::STATUS_SERVING) {
                $queue->updateQuietly(['estimated_wait_minutes' => 0]);
                $startedAt = $queue->started_at ?: now('Asia/Kuala_Lumpur');
                $elapsed = max(0, $startedAt->diffInMinutes(now('Asia/Kuala_Lumpur')));
                $runningWait += max(5, $duration - $elapsed);
                continue;
            }

            $projectedStart = $calculationStart->copy()->addMinutes($runningWait);
            $availableStart = $this->availability->nextAvailableStartAroundAppointments(
                $queue->barber_id,
                $projectedStart,
                $duration
            );
            $runningWait = max(0, (int) ceil(($availableStart->getTimestamp() - $calculationStart->getTimestamp()) / 60));

            $queue->updateQuietly(['estimated_wait_minutes' => $runningWait]);
            $runningWait += $duration;
        }
    }

    private function ensureCanStartServing(WalkInQueue $queue): void
    {
        $queue->loadMissing(['barber', 'service']);

        if (!$queue->barber_id) {
            throw ValidationException::withMessages([
                'barber_id' => 'Assign a barber before starting this walk-in so appointment clashes can be checked.',
            ]);
        }

        $duration = $queue->service?->duration ?: self::DEFAULT_SERVICE_MINUTES;
        $start = now('Asia/Kuala_Lumpur');
        $end = $start->copy()->addMinutes($duration);
        $conflict = $this->availability->findAppointmentConflict(
            $queue->barber_id,
            $queue->queue_date->toDateString(),
            $start,
            $end
        );

        if ($conflict) {
            throw ValidationException::withMessages([
                'status' => 'Cannot start this walk-in. ' . $this->availability->appointmentConflictMessage($conflict) . ' Choose another barber or wait until the appointment slot is clear.',
            ]);
        }
    }

    private function makeQueueCode(string $date, int $number): string
    {
        return 'W' . Carbon::parse($date)->format('ymd') . '-' . str_pad((string) $number, 3, '0', STR_PAD_LEFT);
    }
}
