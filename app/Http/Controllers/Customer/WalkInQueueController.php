<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\WalkInQueue;

class WalkInQueueController extends Controller
{
    public function index()
    {
        $activeQueue = WalkInQueue::with(['barber', 'service'])
            ->where('customer_id', auth()->id())
            ->today()
            ->active()
            ->orderBy('queue_number')
            ->first();

        $history = WalkInQueue::with(['barber', 'service'])
            ->where('customer_id', auth()->id())
            ->latest('queue_date')
            ->latest('queue_number')
            ->limit(10)
            ->get();

        return view('customer.walk_ins.index', compact('activeQueue', 'history'));
    }
}
