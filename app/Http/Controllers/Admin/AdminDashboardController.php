<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ProductOrder;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = today();
        $now = now();
        $weekStart = $now->copy()->subDays(6)->startOfDay();
        $weekEnd = $now->copy()->endOfDay();
        $monthStart = $now->copy()->startOfMonth()->startOfDay();
        $monthEnd = $now->copy()->endOfMonth()->endOfDay();

        // Today's appointments
        $todayAppointments = Appointment::whereDate('appointment_date', $today)->count();

        // Revenue includes completed appointments and paid product orders (online + POS).
        $appointmentRevenueToday = Appointment::whereDate('appointment_date', $today)
            ->where('status', 'completed')
            ->sum('price');

        $productRevenueToday = ProductOrder::paid()
            ->whereDate('paid_at', $today)
            ->sum('total');

        $revenueToday = $appointmentRevenueToday + $productRevenueToday;

        // Active clients
        $activeClients = User::where('role', 'customer')->count();

        // Available barbers
        $availableBarbers = User::where('role', 'staff')->where('status', 'active')->count();

        // Recent appointments
        $recentAppointments = Appointment::with(['customer', 'barber'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->limit(5)
            ->get();

        // Weekly revenue (last 7 days)
        $appointmentWeeklyRevenue = Appointment::where('status', 'completed')
            ->whereBetween('appointment_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->selectRaw('DATE(appointment_date) as date, SUM(price) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $productWeeklyRevenue = ProductOrder::paid()
            ->whereBetween('paid_at', [$weekStart, $weekEnd])
            ->selectRaw('DATE(paid_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $weeklyRevenue = collect(range(0, 6))->map(function ($dayOffset) use ($weekStart, $appointmentWeeklyRevenue, $productWeeklyRevenue) {
            $date = $weekStart->copy()->addDays($dayOffset)->toDateString();

            return (object) [
                'date' => $date,
                'total' => (float) ($appointmentWeeklyRevenue[$date] ?? 0) + (float) ($productWeeklyRevenue[$date] ?? 0),
            ];
        });

        // Monthly revenue (current month)
        $appointmentMonthlyRevenue = Appointment::where('status', 'completed')
            ->whereBetween('appointment_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->sum('price');

        $productMonthlyRevenue = ProductOrder::paid()
            ->whereBetween('paid_at', [$monthStart, $monthEnd])
            ->sum('total');

        $monthlyRevenue = $appointmentMonthlyRevenue + $productMonthlyRevenue;

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
