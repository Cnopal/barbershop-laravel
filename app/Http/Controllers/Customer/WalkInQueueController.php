<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\WalkInQueue;

class WalkInQueueController extends Controller
{
    public function index()
    {
        $activeQueues = WalkInQueue::with(['barber', 'service'])
            ->today()
            ->active()
            ->orderBy('queue_number')
            ->get();

        $servingQueues = $activeQueues->where('status', WalkInQueue::STATUS_SERVING)->values();
        $waitingQueues = $activeQueues->where('status', WalkInQueue::STATUS_WAITING)->values();
        $lanePositions = [];

        $servingQueues->each(function (WalkInQueue $queue) {
            $queue->setAttribute('lane_position', 0);
        });

        $waitingQueues->each(function (WalkInQueue $queue) use (&$lanePositions) {
            $laneKey = $queue->barber_id ? 'barber_' . $queue->barber_id : 'any';
            $lanePositions[$laneKey] = ($lanePositions[$laneKey] ?? 0) + 1;
            $queue->setAttribute('lane_position', $lanePositions[$laneKey]);
        });

        $activeQueue = $activeQueues->firstWhere('customer_id', auth()->id());

        $history = WalkInQueue::with(['barber', 'service'])
            ->where('customer_id', auth()->id())
            ->latest('queue_date')
            ->latest('queue_number')
            ->limit(10)
            ->get();

        return view('customer.walk_ins.index', compact('activeQueue', 'servingQueues', 'waitingQueues', 'history'));
    }
}
