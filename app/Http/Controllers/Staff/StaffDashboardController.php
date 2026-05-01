<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Feedback;
use App\Models\ProductOrder;
use App\Models\WalkInQueue;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $staffId = auth()->id();
        $today = today('Asia/Kuala_Lumpur');

        $todayAppointments = Appointment::where('barber_id', $staffId)
            ->whereDate('appointment_date', $today)
            ->count();

        $todayWalkIns = WalkInQueue::where('barber_id', $staffId)
            ->whereDate('queue_date', $today)
            ->count();

        $upcomingAppointments = Appointment::where('barber_id', $staffId)
            ->whereDate('appointment_date', '>', $today)
            ->where('status', '!=', 'cancelled')
            ->count();

        $completedAppointments = Appointment::where('barber_id', $staffId)
            ->where('status', 'completed')
            ->count();

        $completedWalkIns = WalkInQueue::where('barber_id', $staffId)
            ->where('status', WalkInQueue::STATUS_COMPLETED)
            ->count();

        $appointmentRevenue = Appointment::where('barber_id', $staffId)
            ->where('status', 'completed')
            ->sum('price');

        $walkInRevenue = WalkInQueue::where('barber_id', $staffId)
            ->where('status', WalkInQueue::STATUS_COMPLETED)
            ->sum('price');

        $productRevenue = ProductOrder::paid()
            ->where('staff_id', $staffId)
            ->sum('total');

        $totalRevenue = $appointmentRevenue + $walkInRevenue + $productRevenue;

        $averageRating = Feedback::where('barber_id', $staffId)
            ->whereNotNull('rating')
            ->avg('rating') ?? 0;

        $totalFeedbacks = Feedback::where('barber_id', $staffId)->count() ?? 0;

        $recentAppointments = Appointment::where('barber_id', $staffId)
            ->with(['customer', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->limit(10)
            ->get();

        $todaySchedule = Appointment::where('barber_id', $staffId)
            ->whereDate('appointment_date', $today)
            ->with(['customer', 'service'])
            ->orderBy('start_time')
            ->get();

        $recentWalkIns = WalkInQueue::where('barber_id', $staffId)
            ->with(['customer', 'service'])
            ->latest('queue_date')
            ->latest('queue_number')
            ->limit(8)
            ->get();

        return view('staff.dashboard', compact(
            'todayAppointments',
            'todayWalkIns',
            'upcomingAppointments',
            'completedAppointments',
            'completedWalkIns',
            'totalRevenue',
            'appointmentRevenue',
            'walkInRevenue',
            'productRevenue',
            'averageRating',
            'totalFeedbacks',
            'recentAppointments',
            'todaySchedule',
            'recentWalkIns'
        ));
    }
}
