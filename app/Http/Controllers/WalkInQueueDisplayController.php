<?php

namespace App\Http\Controllers;

use App\Models\WalkInQueue;

class WalkInQueueDisplayController extends Controller
{
    public function index()
    {
        $queues = WalkInQueue::with(['barber', 'service'])
            ->today()
            ->whereIn('status', [WalkInQueue::STATUS_SERVING, WalkInQueue::STATUS_WAITING])
            ->orderBy('queue_number')
            ->get();

        $serving = $queues->where('status', WalkInQueue::STATUS_SERVING);
        $waiting = $queues->where('status', WalkInQueue::STATUS_WAITING)->values();

        $recent = WalkInQueue::with(['barber', 'service'])
            ->today()
            ->whereIn('status', [WalkInQueue::STATUS_COMPLETED, WalkInQueue::STATUS_SKIPPED])
            ->latest('updated_at')
            ->limit(8)
            ->get();

        return view('walk_ins.display', compact('serving', 'waiting', 'recent'));
    }
}
