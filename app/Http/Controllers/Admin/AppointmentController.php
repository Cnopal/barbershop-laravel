<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments with calendar events.
     */
    public function index()
    {
        $appointments = Appointment::with(['customer', 'barber', 'service'])
            ->latest()
            ->paginate(20);

        $barbers = User::where('role', 'staff')->get();

        $calendarEvents = $appointments->getCollection()->map(function ($appointment) {

            $start = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $appointment->appointment_date->format('Y-m-d') . ' ' . $appointment->start_time
            )->toIso8601String();

            $end = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $appointment->appointment_date->format('Y-m-d') . ' ' . $appointment->end_time
            )->toIso8601String();

            $color = match ($appointment->status) {
                'completed' => '#48bb78',
                'cancelled' => '#f56565',
                'pending' => '#ed8936',
                default => '#4299e1',
            };

            return [
                'id' => $appointment->id,
                'title' => $appointment->customer->name . ' - ' . $appointment->service->name,
                'start' => $start,
                'end' => $end,
                'color' => $color,
                'extendedProps' => [
                    'customer' => $appointment->customer->name,
                    'barber' => $appointment->barber->name,
                    'service' => $appointment->service->name,
                    'status' => $appointment->status,
                    'price' => 'RM' . number_format($appointment->price, 2),
                ],
            ];
        });

        return view(
            'admin.appointments.index',
            compact('appointments', 'calendarEvents', 'barbers')
        );
    }


    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        $barbers = User::where('role', 'staff')->where('status', 'active')->get();
        $services = Service::where('status', 'active')->get();
        $customers = User::where('role', 'customer')->get();

        return view('admin.appointments.create', compact('barbers', 'services', 'customers'));
    }

    /**
     * Store a newly created appointment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'barber_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
        ]);

        $service = Service::findOrFail($request->service_id);

        $start = Carbon::parse($request->appointment_date . ' ' . $request->start_time);
        $end = $start->copy()->addMinutes($service->duration);

        // Check barber availability - only check for statuses that block the slot
        // Completed and cancelled appointments don't block availability
        $conflict = Appointment::where('barber_id', $request->barber_id)
            ->where('appointment_date', $request->appointment_date)
            ->whereIn('status', ['pending', 'confirmed', 'pending_payment'])
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end->format('H:i:s'))
                    ->where('end_time', '>', $start->format('H:i:s'));
            })
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'Barber is not available at this time']);
        }

        Appointment::create([
            'customer_id' => $request->customer_id,
            'barber_id' => $request->barber_id,
            'service_id' => $request->service_id,
            'appointment_date' => $request->appointment_date,
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'price' => $service->price,
            'status' => $request->status, // set pending by default for payment later
        ]);

        return redirect()
            ->route('admin.appointments.index')
            ->with('success', 'Appointment created successfully. Awaiting payment.');
    }

    /**
     * Show a single appointment.
     */
    public function show($id)
    {
        $appointment = Appointment::with(['customer', 'barber', 'service'])->findOrFail($id);
        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Show form for editing appointment.
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $barbers = User::where('role', 'staff')->where('status', 'active')->get();
        $services = Service::where('status', 'active')->get();
        $customers = User::where('role', 'customer')->get();

        return view('admin.appointments.edit', compact('appointment', 'barbers', 'services', 'customers'));
    }

    /**
     * Update appointment.
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'barber_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required',
            'start_time' => 'required|date_format:H:i',
            'status' => 'required|in:pending_payment,confirmed,completed,cancelled',
        ]);

        $service = Service::findOrFail($request->service_id);

        // 🔥 FIX: normalize appointment_date (remove time if exists)
        $dateOnly = Carbon::parse($request->appointment_date)->format('Y-m-d');

        $start = Carbon::createFromFormat(
            'Y-m-d H:i',
            $dateOnly . ' ' . $request->start_time
        );

        $end = $start->copy()->addMinutes($service->duration);

        // Check barber availability (exclude current appointment)
        // Only check for statuses that block the slot (not completed or cancelled)
        $conflict = Appointment::where('barber_id', $request->barber_id)
            ->where('appointment_date', $dateOnly)
            ->whereIn('status', ['pending', 'confirmed', 'pending_payment'])
            ->where('id', '!=', $appointment->id)
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end->format('H:i:s'))
                    ->where('end_time', '>', $start->format('H:i:s'));
            })
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'Barber is not available at this time']);
        }

        $appointment->update([
            'customer_id' => $request->customer_id,
            'barber_id' => $request->barber_id,
            'service_id' => $request->service_id,
            'appointment_date' => $dateOnly,
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'price' => $service->price,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()
            ->route('admin.appointments.show', $appointment->id)
            ->with('success', 'Appointment updated successfully');
    }


    /**
     * Delete appointment.
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()
            ->route('admin.appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }


    /**
     * Get available time slots for the admin appointment creation
     */
    public function availableSlots(Request $request)
    {
        $request->validate([
            'barber_id' => 'required|integer|exists:users,id',
            'date' => 'required|date_format:Y-m-d',
            'service_id' => 'required|integer|exists:services,id',
        ]);

        try {
            $barberId = $request->barber_id;
            $date = $request->date;
            $serviceId = $request->service_id;

            // Get service duration
            $service = Service::findOrFail($serviceId);
            $duration = $service->duration;

            // Get current time in Malaysia timezone
            $now = Carbon::now('Asia/Kuala_Lumpur');
            $selectedDate = Carbon::parse($date);

            // Check if date is in the past
            if ($selectedDate->isPast() && !$selectedDate->isToday()) {
                return response()->json([
                    'error' => 'Selected date has already passed'
                ], 400);
            }

            $slots = [];
            // Business hours: 9 AM to 6 PM (18:00)
            for ($hour = 9; $hour < 18; $hour++) {
                for ($minute = 0; $minute < 60; $minute += 30) {
                    $start = sprintf('%02d:%02d', $hour, $minute); // H:i format without seconds
                    $startWithSeconds = $start . ':00'; // For calculations
                    $end = Carbon::createFromFormat('H:i:s', $startWithSeconds)
                        ->addMinutes($duration)
                        ->format('H:i'); // Return H:i format without seconds

                    // Check if slot ends before business closing time
                    if ($end <= '18:00') {
                        // Create full datetime for this slot
                        $slotDateTime = Carbon::parse($date . ' ' . $start);

                        // Check if slot is in the past (for today only)
                        $isPast = false;
                        if ($selectedDate->isToday() && $slotDateTime->lt($now)) {
                            $isPast = true;
                        }

                        $slots[] = [
                            'start' => $start, // H:i format
                            'end' => $end, // H:i format
                            'display' => date('h:i A', strtotime($start)) . ' - ' . date('h:i A', strtotime($end)),
                            'past' => $isPast,
                        ];
                    }
                }
            }

            // Get barber's existing appointments for that day
            // Only count pending/confirmed/pending_payment status (not completed or cancelled)
            $existing = Appointment::where('barber_id', $barberId)
                ->where('appointment_date', $date)
                ->whereIn('status', ['pending', 'confirmed', 'pending_payment'])
                ->get(['start_time', 'end_time']);

            // Filter out unavailable slots
            $available = array_filter($slots, function ($slot) use ($existing) {
                // Filter out past slots
                if ($slot['past']) {
                    return false;
                }

                // Filter out overlapping slots
                foreach ($existing as $appointment) {
                    // Convert to comparable format (H:i)
                    $slotStart = $slot['start']; // H:i format
                    $slotEnd = $slot['end'];     // H:i format
                    $apptStart = substr($appointment->start_time, 0, 5); // H:i format
                    $apptEnd = substr($appointment->end_time, 0, 5);    // H:i format
                    
                    if ($slotStart < $apptEnd && $slotEnd > $apptStart) {
                        return false;
                    }
                }
                return true;
            });

            return response()->json([
                'available_slots' => array_values($available),
                'selected_date' => $date,
                'current_time' => $now->format('H:i:s'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error fetching available slots: ' . $e->getMessage()
            ], 500);
        }
    }
}