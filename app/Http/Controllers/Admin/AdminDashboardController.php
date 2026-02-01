<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    //
    public function index()
    {
        // Today's appointments
        $todayAppointments = \App\Models\Appointment::whereDate('appointment_date', today())->count();

        // Revenue today (RM)
        $revenueToday = \App\Models\Appointment::whereDate('appointment_date', today())
            ->where('status', 'completed')
            ->sum('price');

        // Active clients
        $activeClients = \App\Models\User::where('role', 'customer')->count();

        // Available barbers
        $availableBarbers = \App\Models\User::where('role', 'staff')->where('status', 'active')->count();

        // Recent appointments
        $recentAppointments = \App\Models\Appointment::with(['customer', 'barber'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->limit(5)
            ->get();

        // Weekly revenue (last 7 days)
        $weeklyRevenue = \App\Models\Appointment::where('status', 'completed')
            ->whereBetween('appointment_date', [now()->subDays(6)->toDateString(), now()->toDateString()])
            ->selectRaw('DATE(appointment_date) as date, SUM(price) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly revenue (current month)
        $monthlyRevenue = \App\Models\Appointment::where('status', 'completed')
            ->whereMonth('appointment_date', now()->month)
            ->whereYear('appointment_date', now()->year)
            ->sum('price');

        return view('admin.dashboard', compact(
            'todayAppointments',
            'revenueToday',
            'activeClients',
            'availableBarbers',
            'recentAppointments',
            'weeklyRevenue',
            'monthlyRevenue'
        ));
    }
}
