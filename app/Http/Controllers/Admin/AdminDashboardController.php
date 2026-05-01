<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ProductOrder;
use App\Models\ProductOrderItem;
use App\Models\Service;
use App\Models\User;
use App\Models\WalkInQueue;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = today('Asia/Kuala_Lumpur');
        $now = now('Asia/Kuala_Lumpur');
        $weekStart = $now->copy()->subDays(6)->startOfDay();
        $weekEnd = $now->copy()->endOfDay();
        $monthStart = $now->copy()->startOfMonth()->startOfDay();
        $monthEnd = $now->copy()->endOfMonth()->endOfDay();
        $monthStartDate = $monthStart->toDateString();
        $monthEndDate = $monthEnd->toDateString();

        // Today's appointments
        $todayAppointments = Appointment::whereDate('appointment_date', $today)->count();
        $todayWalkIns = WalkInQueue::whereDate('queue_date', $today)->count();

        // Sales include completed appointments, completed walk-ins, and paid product orders.
        $appointmentRevenueToday = Appointment::whereDate('appointment_date', $today)
            ->where('status', 'completed')
            ->sum('price');

        $walkInRevenueToday = WalkInQueue::whereDate('queue_date', $today)
            ->where('status', WalkInQueue::STATUS_COMPLETED)
            ->sum('price');

        $productRevenueToday = ProductOrder::paid()
            ->whereDate('paid_at', $today)
            ->sum('total');

        $revenueToday = $appointmentRevenueToday + $walkInRevenueToday + $productRevenueToday;

        // Active clients
        $activeClients = User::where('role', 'customer')->count();
        $newCustomersThisMonth = User::where('role', 'customer')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->count();

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

        $walkInWeeklyRevenue = WalkInQueue::where('status', WalkInQueue::STATUS_COMPLETED)
            ->whereBetween('queue_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->selectRaw('DATE(queue_date) as date, SUM(price) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $productWeeklyRevenue = ProductOrder::paid()
            ->whereBetween('paid_at', [$weekStart, $weekEnd])
            ->selectRaw('DATE(paid_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $weeklyRevenue = collect(range(0, 6))->map(function ($dayOffset) use ($weekStart, $appointmentWeeklyRevenue, $walkInWeeklyRevenue, $productWeeklyRevenue) {
            $date = $weekStart->copy()->addDays($dayOffset)->toDateString();

            return (object) [
                'date' => $date,
                'total' => (float) ($appointmentWeeklyRevenue[$date] ?? 0)
                    + (float) ($walkInWeeklyRevenue[$date] ?? 0)
                    + (float) ($productWeeklyRevenue[$date] ?? 0),
            ];
        });

        // Monthly revenue (current month)
        $appointmentMonthlyRevenue = Appointment::where('status', 'completed')
            ->whereBetween('appointment_date', [$monthStartDate, $monthEndDate])
            ->sum('price');

        $walkInMonthlyRevenue = WalkInQueue::where('status', WalkInQueue::STATUS_COMPLETED)
            ->whereBetween('queue_date', [$monthStartDate, $monthEndDate])
            ->sum('price');

        $productMonthlyRevenue = ProductOrder::paid()
            ->whereBetween('paid_at', [$monthStart, $monthEnd])
            ->sum('total');

        $monthlyRevenue = $appointmentMonthlyRevenue + $walkInMonthlyRevenue + $productMonthlyRevenue;

        $salesBreakdownToday = [
            'Appointments' => $appointmentRevenueToday,
            'Walk-ins' => $walkInRevenueToday,
            'Products' => $productRevenueToday,
        ];

        $salesBreakdownMonth = [
            'Appointments' => $appointmentMonthlyRevenue,
            'Walk-ins' => $walkInMonthlyRevenue,
            'Products' => $productMonthlyRevenue,
        ];

        $appointmentStatusCounts = [
            'Pending' => Appointment::whereBetween('appointment_date', [$monthStartDate, $monthEndDate])
                ->whereIn('status', ['pending', 'pending_payment'])
                ->count(),
            'Approved' => Appointment::whereBetween('appointment_date', [$monthStartDate, $monthEndDate])
                ->where('status', 'confirmed')
                ->count(),
            'Completed' => Appointment::whereBetween('appointment_date', [$monthStartDate, $monthEndDate])
                ->where('status', 'completed')
                ->count(),
            'Cancelled' => Appointment::whereBetween('appointment_date', [$monthStartDate, $monthEndDate])
                ->where('status', 'cancelled')
                ->count(),
        ];

        $topSellingProducts = ProductOrderItem::query()
            ->select('product_id', 'product_name', DB::raw('SUM(quantity) as quantity'), DB::raw('SUM(subtotal) as revenue'))
            ->whereHas('order', function ($query) use ($monthStart, $monthEnd) {
                $query->paid()->whereBetween('paid_at', [$monthStart, $monthEnd]);
            })
            ->groupBy('product_id', 'product_name')
            ->orderByRaw('SUM(quantity) DESC')
            ->limit(5)
            ->get();

        $revenueByProduct = ProductOrderItem::query()
            ->select('product_id', 'product_name', DB::raw('SUM(quantity) as quantity'), DB::raw('SUM(subtotal) as revenue'))
            ->whereHas('order', function ($query) use ($monthStart, $monthEnd) {
                $query->paid()->whereBetween('paid_at', [$monthStart, $monthEnd]);
            })
            ->groupBy('product_id', 'product_name')
            ->orderByRaw('SUM(subtotal) DESC')
            ->limit(6)
            ->get();

        [$topServices, $revenueByService] = $this->serviceReports($monthStartDate, $monthEndDate);
        $staffPerformance = $this->staffPerformance($monthStart, $monthEnd, $monthStartDate, $monthEndDate);

        return view('admin.dashboard', compact(
            'todayAppointments',
            'todayWalkIns',
            'revenueToday',
            'activeClients',
            'newCustomersThisMonth',
            'availableBarbers',
            'recentAppointments',
            'weeklyRevenue',
            'monthlyRevenue',
            'salesBreakdownToday',
            'salesBreakdownMonth',
            'appointmentStatusCounts',
            'topSellingProducts',
            'topServices',
            'staffPerformance',
            'revenueByService',
            'revenueByProduct'
        ));
    }

    private function serviceReports(string $monthStartDate, string $monthEndDate): array
    {
        $appointmentCounts = Appointment::select('service_id', DB::raw('COUNT(*) as total'))
            ->whereBetween('appointment_date', [$monthStartDate, $monthEndDate])
            ->where('status', '!=', 'cancelled')
            ->groupBy('service_id')
            ->pluck('total', 'service_id');

        $walkInCounts = WalkInQueue::select('service_id', DB::raw('COUNT(*) as total'))
            ->whereBetween('queue_date', [$monthStartDate, $monthEndDate])
            ->whereNotIn('status', [WalkInQueue::STATUS_SKIPPED])
            ->whereNotNull('service_id')
            ->groupBy('service_id')
            ->pluck('total', 'service_id');

        $appointmentRevenue = Appointment::select('service_id', DB::raw('SUM(price) as total'))
            ->whereBetween('appointment_date', [$monthStartDate, $monthEndDate])
            ->where('status', 'completed')
            ->groupBy('service_id')
            ->pluck('total', 'service_id');

        $walkInRevenue = WalkInQueue::select('service_id', DB::raw('SUM(price) as total'))
            ->whereBetween('queue_date', [$monthStartDate, $monthEndDate])
            ->where('status', WalkInQueue::STATUS_COMPLETED)
            ->whereNotNull('service_id')
            ->groupBy('service_id')
            ->pluck('total', 'service_id');

        $serviceIds = $appointmentCounts->keys()
            ->merge($walkInCounts->keys())
            ->merge($appointmentRevenue->keys())
            ->merge($walkInRevenue->keys())
            ->unique()
            ->values();

        $serviceNames = Service::whereIn('id', $serviceIds)->pluck('name', 'id');

        $topServices = $serviceIds->map(function ($serviceId) use ($serviceNames, $appointmentCounts, $walkInCounts) {
            return (object) [
                'name' => $serviceNames[$serviceId] ?? 'Unknown service',
                'total_bookings' => (int) ($appointmentCounts[$serviceId] ?? 0) + (int) ($walkInCounts[$serviceId] ?? 0),
            ];
        })->filter(fn ($service) => $service->total_bookings > 0)
            ->sortByDesc('total_bookings')
            ->take(5)
            ->values();

        $revenueByService = $serviceIds->map(function ($serviceId) use ($serviceNames, $appointmentRevenue, $walkInRevenue) {
            return (object) [
                'name' => $serviceNames[$serviceId] ?? 'Unknown service',
                'appointment_revenue' => (float) ($appointmentRevenue[$serviceId] ?? 0),
                'walk_in_revenue' => (float) ($walkInRevenue[$serviceId] ?? 0),
                'total_revenue' => (float) ($appointmentRevenue[$serviceId] ?? 0) + (float) ($walkInRevenue[$serviceId] ?? 0),
            ];
        })->filter(fn ($service) => $service->total_revenue > 0)
            ->sortByDesc('total_revenue')
            ->take(6)
            ->values();

        return [$topServices, $revenueByService];
    }

    private function staffPerformance($monthStart, $monthEnd, string $monthStartDate, string $monthEndDate)
    {
        $appointmentStats = Appointment::select(
                'barber_id',
                DB::raw('COUNT(*) as jobs'),
                DB::raw('SUM(price) as revenue')
            )
            ->whereBetween('appointment_date', [$monthStartDate, $monthEndDate])
            ->where('status', 'completed')
            ->groupBy('barber_id')
            ->get()
            ->keyBy('barber_id');

        $walkInStats = WalkInQueue::select(
                'barber_id',
                DB::raw('COUNT(*) as jobs'),
                DB::raw('SUM(price) as revenue')
            )
            ->whereBetween('queue_date', [$monthStartDate, $monthEndDate])
            ->where('status', WalkInQueue::STATUS_COMPLETED)
            ->whereNotNull('barber_id')
            ->groupBy('barber_id')
            ->get()
            ->keyBy('barber_id');

        $productStats = ProductOrder::select('staff_id', DB::raw('SUM(total) as revenue'))
            ->paid()
            ->whereBetween('paid_at', [$monthStart, $monthEnd])
            ->whereNotNull('staff_id')
            ->groupBy('staff_id')
            ->get()
            ->keyBy('staff_id');

        return User::where('role', 'staff')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(function ($staff) use ($appointmentStats, $walkInStats, $productStats) {
                $appointmentJobs = (int) ($appointmentStats[$staff->id]->jobs ?? 0);
                $walkInJobs = (int) ($walkInStats[$staff->id]->jobs ?? 0);
                $appointmentRevenue = (float) ($appointmentStats[$staff->id]->revenue ?? 0);
                $walkInRevenue = (float) ($walkInStats[$staff->id]->revenue ?? 0);
                $productRevenue = (float) ($productStats[$staff->id]->revenue ?? 0);

                return (object) [
                    'name' => $staff->name,
                    'appointment_jobs' => $appointmentJobs,
                    'walk_in_jobs' => $walkInJobs,
                    'service_revenue' => $appointmentRevenue + $walkInRevenue,
                    'product_revenue' => $productRevenue,
                    'total_revenue' => $appointmentRevenue + $walkInRevenue + $productRevenue,
                ];
            })
            ->filter(fn ($staff) => $staff->appointment_jobs + $staff->walk_in_jobs > 0 || $staff->total_revenue > 0)
            ->sortByDesc('total_revenue')
            ->take(8)
            ->values();
    }
}
