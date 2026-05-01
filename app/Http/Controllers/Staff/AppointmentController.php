<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use App\Services\BarberAvailabilityService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:all,pending_payment,confirmed,completed,cancelled',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'sort' => 'nullable|in:latest,date_asc,date_desc,customer,service,price_desc',
        ]);

        $query = Appointment::where('barber_id', auth()->id())
            ->with(['customer', 'service']);

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

        match ($filters['sort'] ?? 'latest') {
            'date_asc' => $query->orderBy('appointment_date')->orderBy('start_time'),
            'date_desc' => $query->orderByDesc('appointment_date')->orderByDesc('start_time'),
            'customer' => $query->join('users as customers', 'appointments.customer_id', '=', 'customers.id')
                ->orderBy('customers.name')
                ->select('appointments.*'),
            'service' => $query->join('services', 'appointments.service_id', '=', 'services.id')
                ->orderBy('services.name')
                ->orderByDesc('appointment_date')
                ->select('appointments.*'),
            'price_desc' => $query->orderByDesc('price')->orderByDesc('appointment_date'),
            default => $query->latest(),
        };

        $appointments = $query->paginate(12)->withQueryString();

        $summary = [
            'shown' => $appointments->total(),
            'confirmed' => Appointment::where('barber_id', auth()->id())->where('status', 'confirmed')->count(),
            'completed' => Appointment::where('barber_id', auth()->id())->where('status', 'completed')->count(),
            'pending_payment' => Appointment::where('barber_id', auth()->id())->where('status', 'pending_payment')->count(),
        ];

        return view('staff.appointments.index', compact('appointments', 'filters', 'summary'));
    }

    public function create()
    {
        $customers = User::where('role', 'customer')->get();
        $services = Service::where('status', 'active')->get();

        return view('staff.appointments.create', compact('customers', 'services'));
    }

    /**
     * Create a new customer if they don't exist
     */
    public function createCustomer(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|unique:users,email',
            'customer_phone' => 'required|string|max:20',
        ]);

        $customer = User::create([
            'name' => $request->customer_name,
            'email' => $request->customer_email,
            'phone' => $request->customer_phone,
            'password' => Hash::make('password123'), // Default password
            'role' => 'customer',
        ]);

        return response()->json([
            'success' => true,
            'customer' => $customer,
            'message' => 'Customer created successfully',
        ]);
    }

    /**
     * Check if customer exists by email
     */
    public function checkCustomer(Request $request)
    {
        $customer = User::where('role', 'customer')
            ->where('email', $request->email)
            ->first();

        return response()->json([
            'exists' => $customer !== null,
            'customer' => $customer,
        ]);
    }

    public function store(Request $request, BarberAvailabilityService $availability)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'booking_for' => 'required|in:self,other',
            'recipient_name' => 'nullable|required_if:booking_for,other|string|max:255',
            'recipient_age' => 'nullable|required_if:booking_for,other|integer|min:0|max:120',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        $service = Service::findOrFail($request->service_id);
        $customer = User::findOrFail($request->customer_id);
        $recipient = Appointment::recipientPayload($customer, $request->all());
        $price = Appointment::priceForRecipient($service, $recipient['recipient_age']);
        $staffId = auth()->id();

        $start = Carbon::parse($request->appointment_date . ' ' . $request->start_time);
        $end = $start->copy()->addMinutes($service->duration);

        $appointmentConflict = $availability->findAppointmentConflict(
            $staffId,
            $request->appointment_date,
            $start,
            $end
        );

        if ($appointmentConflict) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'You are not available at this time. ' . $availability->appointmentConflictMessage($appointmentConflict)]);
        }

        $walkInConflict = $availability->findServingWalkInConflict(
            $staffId,
            $request->appointment_date,
            $start,
            $end
        );

        if ($walkInConflict) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'You are not available at this time. ' . $availability->walkInConflictMessage($walkInConflict)]);
        }

        Appointment::create([
            'customer_id' => $request->customer_id,
            'booking_for' => $recipient['booking_for'],
            'recipient_name' => $recipient['recipient_name'],
            'recipient_age' => $recipient['recipient_age'],
            'barber_id' => $staffId,
            'service_id' => $request->service_id,
            'appointment_date' => $request->appointment_date,
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'price' => $price,
            'status' => 'pending_payment',
            'notes' => $request->notes,
        ]);

        return redirect()
            ->route('staff.appointments.index')
            ->with('success', 'Appointment created successfully.');
    }

    public function show($id)
    {
        $appointment = Appointment::where('barber_id', auth()->id())
            ->with(['customer', 'service'])
            ->findOrFail($id);

        return view('staff.appointments.show', compact('appointment'));
    }

    public function edit($id)
    {
        $appointment = Appointment::where('barber_id', auth()->id())->findOrFail($id);

        // Prevent editing completed or cancelled appointments
        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return redirect()
                ->route('staff.appointments.show', $appointment->id)
                ->with('error', 'Cannot update a ' . $appointment->status . ' appointment.');
        }

        return view('staff.appointments.edit', compact('appointment'));
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::where('barber_id', auth()->id())->findOrFail($id);

        // Prevent updating completed or cancelled appointments
        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return redirect()
                ->route('staff.appointments.show', $appointment->id)
                ->with('error', 'Cannot update a ' . $appointment->status . ' appointment.');
        }

        // Staff can update status from the show page, but only specific fields
        $request->validate([
            'status' => 'required|in:pending_payment,confirmed,completed,cancelled',
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        // Update the appointment
        $appointment->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()
            ->route('staff.appointments.show', $appointment->id)
            ->with('success', 'Appointment status updated successfully');
    }

    public function destroy($id)
    {
        $appointment = Appointment::where('barber_id', auth()->id())->findOrFail($id);
        $appointment->delete();

        return redirect()
            ->route('staff.appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Get available slots for staff creating appointments
     */
    public function getAvailableSlots(Request $request, BarberAvailabilityService $availability)
    {
        $request->validate([
            'date' => 'required|date',
            'service_id' => 'required|exists:services,id',
        ]);

        $service = Service::findOrFail($request->service_id);
        $duration = $service->duration;
        $staffId = auth()->id();

        // Get current time in Malaysia timezone
        $now = Carbon::now('Asia/Kuala_Lumpur');
        $selectedDate = Carbon::parse($request->date);

        // Check if selected date is in the past
        if ($selectedDate->isPast() && !$selectedDate->isToday()) {
            return response()->json([
                'available_slots' => [],
                'error' => 'Selected date has already passed',
            ]);
        }

        $slots = [];
        for ($hour = 9; $hour < 22; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 30) {
                $start = sprintf('%02d:%02d', $hour, $minute);
                $end = Carbon::createFromFormat('H:i', $start)
                    ->addMinutes($duration)
                    ->format('H:i');

                // Check if slot ends before business closing time
                if ($end <= '22:00') {
                    // Create full datetime for this slot
                    $slotDateTime = Carbon::parse($request->date . ' ' . $start);

                    // Check if slot is in the past (for today only)
                    $isPast = false;
                    if ($selectedDate->isToday() && $slotDateTime->lt($now)) {
                        $isPast = true;
                    }

                    $slots[] = [
                        'start' => $start,
                        'end' => $end,
                        'display' => date('h:i A', strtotime($start)) . ' - ' . date('h:i A', strtotime($end)),
                        'past' => $isPast,
                    ];
                }
            }
        }

        $available = array_filter($slots, function ($slot) use ($availability, $staffId, $request) {
            // Filter out past slots
            if ($slot['past']) {
                return false;
            }

            return !$availability->slotHasConflict($staffId, $request->date, $slot['start'], $slot['end']);
        });

        return response()->json([
            'available_slots' => array_values($available),
            'selected_date' => $request->date,
            'current_time' => $now->format('H:i:s'),
        ]);
    }
}
