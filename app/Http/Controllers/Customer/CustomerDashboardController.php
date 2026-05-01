<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ProductOrder;
use App\Models\WalkInQueue;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $customerId = auth()->id();

        $stats = [
            'upcoming' => Appointment::where('customer_id', $customerId)
                ->whereIn('status', ['pending_payment', 'confirmed'])
                ->count(),
            'completed' => Appointment::where('customer_id', $customerId)
                ->where('status', 'completed')
                ->count(),
            'cancelled' => Appointment::where('customer_id', $customerId)
                ->where('status', 'cancelled')
                ->count(),
        ];

        $appointmentSpent = Appointment::where('customer_id', $customerId)
            ->where('status', 'completed')
            ->sum('price');

        $productSpent = ProductOrder::paid()
            ->where('customer_id', $customerId)
            ->sum('total');

        $walkInSpent = WalkInQueue::where('customer_id', $customerId)
            ->where('status', WalkInQueue::STATUS_COMPLETED)
            ->sum('price');

        $stats['total_spent'] = $appointmentSpent + $productSpent + $walkInSpent;

        $upcomingAppointments = Appointment::with(['service', 'barber'])
            ->where('customer_id', $customerId)
            ->whereIn('status', ['pending_payment', 'confirmed'])
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        $activeWalkIn = WalkInQueue::with(['service', 'barber'])
            ->where('customer_id', $customerId)
            ->today()
            ->active()
            ->orderBy('queue_number')
            ->first();

        return view('customer.dashboard', compact('stats', 'upcomingAppointments', 'activeWalkIn'));
    }
}
