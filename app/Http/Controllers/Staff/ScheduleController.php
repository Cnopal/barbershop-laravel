<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $staffId = auth()->id();
        $filters = $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:all,pending_payment,confirmed',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'sort' => 'nullable|in:date_asc,date_desc,customer,service,status',
        ]);

        $query = Appointment::where('barber_id', $staffId)
            ->with(['customer', 'service'])
            ->whereIn('status', ['pending_payment', 'confirmed']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($appointmentQuery) use ($search) {
                $appointmentQuery->where('recipient_name', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('service', function ($serviceQuery) use ($search) {
                        $serviceQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['from'])) {
            $query->whereDate('appointment_date', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->whereDate('appointment_date', '<=', $filters['to']);
        }

        match ($filters['sort'] ?? 'date_asc') {
            'date_desc' => $query->orderByDesc('appointment_date')->orderByDesc('start_time'),
            'customer' => $query->join('users as customers', 'appointments.customer_id', '=', 'customers.id')
                ->orderBy('customers.name')
                ->select('appointments.*'),
            'service' => $query->join('services', 'appointments.service_id', '=', 'services.id')
                ->orderBy('services.name')
                ->orderBy('appointment_date')
                ->select('appointments.*'),
            'status' => $query->orderBy('status')->orderBy('appointment_date')->orderBy('start_time'),
            default => $query->orderBy('appointment_date')->orderBy('start_time'),
        };

        $appointments = $query->get();

        $summary = [
            'total' => $appointments->count(),
            'confirmed' => $appointments->where('status', 'confirmed')->count(),
            'pending_payment' => $appointments->where('status', 'pending_payment')->count(),
            'today' => $appointments
                ->filter(fn ($appointment) => $appointment->appointment_date->isSameDay(today('Asia/Kuala_Lumpur')))
                ->count(),
        ];

        $calendarEvents = $appointments->map(function ($appointment) {
            $start = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $appointment->appointment_date->format('Y-m-d') . ' ' . $appointment->start_time
            )->toIso8601String();

            $end = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $appointment->appointment_date->format('Y-m-d') . ' ' . $appointment->end_time
            )->toIso8601String();

            return [
                'id' => $appointment->id,
                'title' => $appointment->customer->name . ' - ' . $appointment->service->name,
                'start' => $start,
                'end' => $end,
                'color' => $appointment->status === 'confirmed' ? '#48bb78' : '#ed8936',
                'extendedProps' => [
                    'customer' => $appointment->customer->name,
                    'service' => $appointment->service->name,
                    'status' => $appointment->status,
                ],
            ];
        });

        return view('staff.schedule.index', compact('appointments', 'calendarEvents', 'filters', 'summary'));
    }
}
