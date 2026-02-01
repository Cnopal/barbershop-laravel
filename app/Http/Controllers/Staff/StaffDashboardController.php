<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Feedback;
use Carbon\Carbon;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $staffId = auth()->id();

        $todayAppointments = Appointment::where('barber_id', $staffId)
            ->whereDate('appointment_date', today())
            ->count();

        $upcomingAppointments = Appointment::where('barber_id', $staffId)
            ->whereDate('appointment_date', '>', today())
            ->where('status', '!=', 'cancelled')
            ->count();

        $completedAppointments = Appointment::where('barber_id', $staffId)
            ->where('status', 'completed')
            ->count();

        $totalRevenue = Appointment::where('barber_id', $staffId)
            ->where('status', 'completed')
            ->sum('price');

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
            ->whereDate('appointment_date', today())
            ->with(['customer', 'service'])
            ->orderBy('start_time')
            ->get();

        return view('staff.dashboard', compact(
            'todayAppointments',
            'upcomingAppointments',
            'completedAppointments',
            'totalRevenue',
            'averageRating',
            'totalFeedbacks',
            'recentAppointments',
            'todaySchedule'
        ));
    }
}