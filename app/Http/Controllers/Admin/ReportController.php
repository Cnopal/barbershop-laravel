<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ProductOrder;
use App\Models\ProductOrderItem;
use App\Models\Service;
use App\Models\User;
use App\Models\WalkInQueue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    private const REPORTS = [
        'sales' => [
            'title' => 'Sales Detail',
            'description' => 'Daily sales breakdown from appointments, walk-ins, and product orders.',
        ],
        'appointment-status' => [
            'title' => 'Appointment Status Detail',
            'description' => 'Appointment counts by status for the selected period.',
        ],
        'top-products' => [
            'title' => 'Top Selling Products',
            'description' => 'Products ranked by quantity sold from paid orders.',
        ],
        'top-services' => [
            'title' => 'Top Services',
            'description' => 'Services ranked by appointment and walk-in booking volume.',
        ],
        'staff-performance' => [
            'title' => 'Staff Performance',
            'description' => 'Completed service work and sales contribution by staff.',
        ],
        'revenue-by-service' => [
            'title' => 'Revenue By Service',
            'description' => 'Completed appointment and walk-in revenue grouped by service.',
        ],
        'revenue-by-product' => [
            'title' => 'Revenue By Product',
            'description' => 'Paid product order revenue grouped by product.',
        ],
        'new-customers' => [
            'title' => 'New Customers',
            'description' => 'Customer accounts registered during the selected period.',
        ],
    ];

    public function show(Request $request, string $report)
    {
        abort_unless(array_key_exists($report, self::REPORTS), 404);

        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        [$start, $end] = $this->period($validated);
        $startDate = $start->toDateString();
        $endDate = $end->toDateString();

        $data = match ($report) {
            'sales' => $this->salesReport($start, $end),
            'appointment-status' => $this->appointmentStatusReport($startDate, $endDate),
            'top-products' => $this->productReport($start, $end, 'quantity'),
            'top-services' => $this->serviceReport($startDate, $endDate, 'bookings'),
            'staff-performance' => $this->staffPerformanceReport($start, $end, $startDate, $endDate),
            'revenue-by-service' => $this->serviceReport($startDate, $endDate, 'revenue'),
            'revenue-by-product' => $this->productReport($start, $end, 'revenue'),
            'new-customers' => $this->newCustomersReport($start, $end),
        };

        return view('admin.reports.show', [
            'reportKey' => $report,
            'title' => self::REPORTS[$report]['title'],
            'description' => self::REPORTS[$report]['description'],
            'columns' => $data['columns'],
            'rows' => $data['rows'],
            'summary' => $data['summary'] ?? [],
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    private function period(array $validated): array
    {
        $now = now('Asia/Kuala_Lumpur');
        $start = isset($validated['start_date'])
            ? Carbon::parse($validated['start_date'], 'Asia/Kuala_Lumpur')->startOfDay()
            : $now->copy()->startOfMonth();
        $end = isset($validated['end_date'])
            ? Carbon::parse($validated['end_date'], 'Asia/Kuala_Lumpur')->endOfDay()
            : $now->copy()->endOfMonth();

        return [$start, $end];
    }

    private function salesReport(Carbon $start, Carbon $end): array
    {
        $appointmentRevenue = Appointment::where('status', 'completed')
            ->whereBetween('appointment_date', [$start->toDateString(), $end->toDateString()])
            ->selectRaw('DATE(appointment_date) as date, SUM(price) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $walkInRevenue = WalkInQueue::where('status', WalkInQueue::STATUS_COMPLETED)
            ->whereBetween('queue_date', [$start->toDateString(), $end->toDateString()])
            ->selectRaw('DATE(queue_date) as date, SUM(price) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $productRevenue = ProductOrder::paid()
            ->whereBetween('paid_at', [$start, $end])
            ->selectRaw('DATE(paid_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $rows = collect();
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $date = $cursor->toDateString();
            $appointments = (float) ($appointmentRevenue[$date] ?? 0);
            $walkIns = (float) ($walkInRevenue[$date] ?? 0);
            $products = (float) ($productRevenue[$date] ?? 0);

            $rows->push([
                'date' => $date,
                'appointments' => $this->money($appointments),
                'walk_ins' => $this->money($walkIns),
                'products' => $this->money($products),
                'total' => $this->money($appointments + $walkIns + $products),
            ]);

            $cursor->addDay();
        }

        return [
            'columns' => [
                ['key' => 'date', 'label' => 'Date'],
                ['key' => 'appointments', 'label' => 'Appointments', 'align' => 'right'],
                ['key' => 'walk_ins', 'label' => 'Walk-ins', 'align' => 'right'],
                ['key' => 'products', 'label' => 'Products', 'align' => 'right'],
                ['key' => 'total', 'label' => 'Total', 'align' => 'right'],
            ],
            'rows' => $rows->sortByDesc('date')->values(),
            'summary' => [
                'Total Sales' => $this->money($rows->sum(fn ($row) => (float) str_replace(['RM', ','], '', $row['total']))),
            ],
        ];
    }

    private function appointmentStatusReport(string $startDate, string $endDate): array
    {
        $groups = [
            'Pending' => ['pending', 'pending_payment'],
            'Approved' => ['confirmed'],
            'Completed' => ['completed'],
            'Cancelled' => ['cancelled'],
        ];

        $rows = collect($groups)->map(function ($statuses, $label) use ($startDate, $endDate) {
            $query = Appointment::whereBetween('appointment_date', [$startDate, $endDate])
                ->whereIn('status', $statuses);

            return [
                'status' => $label,
                'count' => $query->count(),
                'value' => $this->money((clone $query)->sum('price')),
            ];
        })->values();

        return [
            'columns' => [
                ['key' => 'status', 'label' => 'Status'],
                ['key' => 'count', 'label' => 'Appointments', 'align' => 'right'],
                ['key' => 'value', 'label' => 'Value', 'align' => 'right'],
            ],
            'rows' => $rows,
            'summary' => [
                'Total Appointments' => $rows->sum('count'),
            ],
        ];
    }

    private function productReport(Carbon $start, Carbon $end, string $sortBy): array
    {
        $products = ProductOrderItem::query()
            ->select(
                'product_id',
                'product_name',
                DB::raw('SUM(quantity) as quantity'),
                DB::raw('SUM(subtotal) as revenue'),
                DB::raw('COUNT(DISTINCT product_order_id) as orders')
            )
            ->whereHas('order', function ($query) use ($start, $end) {
                $query->paid()->whereBetween('paid_at', [$start, $end]);
            })
            ->groupBy('product_id', 'product_name')
            ->orderByRaw($sortBy === 'quantity' ? 'SUM(quantity) DESC' : 'SUM(subtotal) DESC')
            ->get();

        return [
            'columns' => [
                ['key' => 'product', 'label' => 'Product'],
                ['key' => 'orders', 'label' => 'Orders', 'align' => 'right'],
                ['key' => 'quantity', 'label' => 'Qty Sold', 'align' => 'right'],
                ['key' => 'revenue', 'label' => 'Revenue', 'align' => 'right'],
            ],
            'rows' => $products->map(fn ($product) => [
                'product' => $product->product_name,
                'orders' => (int) $product->orders,
                'quantity' => (int) $product->quantity,
                'revenue' => $this->money((float) $product->revenue),
                'action_url' => $product->product_id ? route('admin.products.show', $product->product_id) : null,
                'action_label' => 'View Product',
            ]),
            'summary' => [
                'Products Sold' => (int) $products->sum('quantity'),
                'Product Revenue' => $this->money((float) $products->sum('revenue')),
            ],
        ];
    }

    private function serviceReport(string $startDate, string $endDate, string $sortBy): array
    {
        $services = $this->serviceActivity($startDate, $endDate)
            ->sortByDesc($sortBy === 'bookings' ? 'total_bookings' : 'total_revenue')
            ->values();

        return [
            'columns' => [
                ['key' => 'service', 'label' => 'Service'],
                ['key' => 'appointment_bookings', 'label' => 'Appointments', 'align' => 'right'],
                ['key' => 'walk_in_bookings', 'label' => 'Walk-ins', 'align' => 'right'],
                ['key' => 'total_bookings', 'label' => 'Total Bookings', 'align' => 'right'],
                ['key' => 'appointment_revenue', 'label' => 'Appointment Revenue', 'align' => 'right'],
                ['key' => 'walk_in_revenue', 'label' => 'Walk-in Revenue', 'align' => 'right'],
                ['key' => 'total_revenue', 'label' => 'Total Revenue', 'align' => 'right'],
            ],
            'rows' => $services->map(fn ($service) => [
                'service' => $service->name,
                'appointment_bookings' => $service->appointment_bookings,
                'walk_in_bookings' => $service->walk_in_bookings,
                'total_bookings' => $service->total_bookings,
                'appointment_revenue' => $this->money($service->appointment_revenue),
                'walk_in_revenue' => $this->money($service->walk_in_revenue),
                'total_revenue' => $this->money($service->total_revenue),
                'action_url' => $service->id ? route('admin.services.show', $service->id) : null,
                'action_label' => 'View Service',
            ]),
            'summary' => [
                'Service Bookings' => (int) $services->sum('total_bookings'),
                'Service Revenue' => $this->money((float) $services->sum('total_revenue')),
            ],
        ];
    }

    private function staffPerformanceReport(Carbon $start, Carbon $end, string $startDate, string $endDate): array
    {
        $staff = $this->staffPerformance($start, $end, $startDate, $endDate);

        return [
            'columns' => [
                ['key' => 'staff', 'label' => 'Staff'],
                ['key' => 'appointments', 'label' => 'Appointments', 'align' => 'right'],
                ['key' => 'walk_ins', 'label' => 'Walk-ins', 'align' => 'right'],
                ['key' => 'service_revenue', 'label' => 'Service Revenue', 'align' => 'right'],
                ['key' => 'product_revenue', 'label' => 'Product Revenue', 'align' => 'right'],
                ['key' => 'total_revenue', 'label' => 'Total Revenue', 'align' => 'right'],
            ],
            'rows' => $staff->map(fn ($member) => [
                'staff' => $member->name,
                'appointments' => $member->appointment_jobs,
                'walk_ins' => $member->walk_in_jobs,
                'service_revenue' => $this->money($member->service_revenue),
                'product_revenue' => $this->money($member->product_revenue),
                'total_revenue' => $this->money($member->total_revenue),
                'action_url' => route('admin.staffs.show', $member->id),
                'action_label' => 'View Staff',
            ]),
            'summary' => [
                'Completed Services' => (int) $staff->sum(fn ($member) => $member->appointment_jobs + $member->walk_in_jobs),
                'Total Revenue' => $this->money((float) $staff->sum('total_revenue')),
            ],
        ];
    }

    private function newCustomersReport(Carbon $start, Carbon $end): array
    {
        $customers = User::where('role', 'customer')
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        return [
            'columns' => [
                ['key' => 'name', 'label' => 'Customer'],
                ['key' => 'email', 'label' => 'Email'],
                ['key' => 'phone', 'label' => 'Phone'],
                ['key' => 'joined', 'label' => 'Joined'],
            ],
            'rows' => $customers->map(fn ($customer) => [
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone ?: '-',
                'joined' => $customer->created_at->format('d M Y'),
                'action_url' => route('admin.customers.show', $customer),
                'action_label' => 'View Customer',
            ]),
            'summary' => [
                'New Customers' => $customers->count(),
            ],
        ];
    }

    private function serviceActivity(string $startDate, string $endDate)
    {
        $appointmentCounts = Appointment::select('service_id', DB::raw('COUNT(*) as total'))
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->groupBy('service_id')
            ->pluck('total', 'service_id');

        $walkInCounts = WalkInQueue::select('service_id', DB::raw('COUNT(*) as total'))
            ->whereBetween('queue_date', [$startDate, $endDate])
            ->whereNotIn('status', [WalkInQueue::STATUS_SKIPPED])
            ->whereNotNull('service_id')
            ->groupBy('service_id')
            ->pluck('total', 'service_id');

        $appointmentRevenue = Appointment::select('service_id', DB::raw('SUM(price) as total'))
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->groupBy('service_id')
            ->pluck('total', 'service_id');

        $walkInRevenue = WalkInQueue::select('service_id', DB::raw('SUM(price) as total'))
            ->whereBetween('queue_date', [$startDate, $endDate])
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

        return $serviceIds->map(function ($serviceId) use ($serviceNames, $appointmentCounts, $walkInCounts, $appointmentRevenue, $walkInRevenue) {
            $appointmentBookings = (int) ($appointmentCounts[$serviceId] ?? 0);
            $walkInBookings = (int) ($walkInCounts[$serviceId] ?? 0);
            $appointmentTotal = (float) ($appointmentRevenue[$serviceId] ?? 0);
            $walkInTotal = (float) ($walkInRevenue[$serviceId] ?? 0);

            return (object) [
                'id' => $serviceId,
                'name' => $serviceNames[$serviceId] ?? 'Unknown service',
                'appointment_bookings' => $appointmentBookings,
                'walk_in_bookings' => $walkInBookings,
                'total_bookings' => $appointmentBookings + $walkInBookings,
                'appointment_revenue' => $appointmentTotal,
                'walk_in_revenue' => $walkInTotal,
                'total_revenue' => $appointmentTotal + $walkInTotal,
            ];
        });
    }

    private function staffPerformance(Carbon $start, Carbon $end, string $startDate, string $endDate)
    {
        $appointmentStats = Appointment::select('barber_id', DB::raw('COUNT(*) as jobs'), DB::raw('SUM(price) as revenue'))
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->groupBy('barber_id')
            ->get()
            ->keyBy('barber_id');

        $walkInStats = WalkInQueue::select('barber_id', DB::raw('COUNT(*) as jobs'), DB::raw('SUM(price) as revenue'))
            ->whereBetween('queue_date', [$startDate, $endDate])
            ->where('status', WalkInQueue::STATUS_COMPLETED)
            ->whereNotNull('barber_id')
            ->groupBy('barber_id')
            ->get()
            ->keyBy('barber_id');

        $productStats = ProductOrder::select('staff_id', DB::raw('SUM(total) as revenue'))
            ->paid()
            ->whereBetween('paid_at', [$start, $end])
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
                    'id' => $staff->id,
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
            ->values();
    }

    private function money(float $amount): string
    {
        return 'RM' . number_format($amount, 2);
    }
}
