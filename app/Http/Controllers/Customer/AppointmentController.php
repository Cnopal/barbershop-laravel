<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Checkout\Session;



class AppointmentController extends Controller
{
    // Index - List all appointments
    public function index()
    {
        $appointments = Appointment::with(['service', 'barber'])
            ->where('customer_id', Auth::id())
            ->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('customer.appointments.index', compact('appointments'));
    }

    // Show - Single appointment details
    public function show(Appointment $appointment)
    {
        if ($appointment->customer_id !== Auth::id()) {
            abort(403);
        }

        $appointment->load(['service', 'barber']);

        return view('customer.appointments.show', compact('appointment'));
    }

    // Create - Show booking form
    public function create()
    {
        $services = Service::where('status', 'active')->get();
        $barbers = User::where('role', 'staff')
            ->where('status', 'active')
            ->get();

        return view('customer.appointments.create', compact('services', 'barbers'));
    }

    // Store - Save new appointment
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'barber_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i:s', // ✅ FIX
            'notes' => 'nullable|string|max:500',
        ]);

        $service = Service::findOrFail($request->service_id);

        $start_time = $request->start_time;
        $end_time = Carbon::createFromFormat('H:i:s', $start_time)
            ->addMinutes($service->duration)
            ->format('H:i:s');

        // Malaysia datetime
        $appointmentDateTime = Carbon::parse($request->appointment_date)
            ->setTimeFromTimeString($start_time)
            ->timezone('Asia/Kuala_Lumpur');

        $now = Carbon::now('Asia/Kuala_Lumpur');

        // Customer conflict
        $hasCustomerConflict = Appointment::where('customer_id', Auth::id())
            ->where('appointment_date', $request->appointment_date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($start_time, $end_time) {
                $q->where('start_time', '<', $end_time)
                    ->where('end_time', '>', $start_time);
            })
            ->exists();

        if ($hasCustomerConflict) {
            return back()->withInput()->with('error', 'You already have an appointment during this time.');
        }

        // Barber conflict
        $hasBarberConflict = Appointment::where('barber_id', $request->barber_id)
            ->where('appointment_date', $request->appointment_date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($start_time, $end_time) {
                $q->where('start_time', '<', $end_time)
                    ->where('end_time', '>', $start_time);
            })
            ->exists();

        if ($hasBarberConflict) {
            return back()->withInput()->with('error', 'Selected barber is already booked.');
        }

        // At least 2 hours in advance
        if ($appointmentDateTime->diffInHours($now) < 2) {
            return back()->withInput()->with('error', 'Book at least 2 hours in advance.');
        }

        // Business hours (9AM - 10PM)
        if ($appointmentDateTime->hour < 9 || $appointmentDateTime->hour >= 22) {
            return back()->withInput()->with('error', 'Booking time must be between 9:00 AM and 10:00 PM.');
        }

        // No weekend
        if ($appointmentDateTime->dayOfWeek === Carbon::WEDNESDAY) {
            return back()->withInput()->with('error', 'Appointments are not available on Wednesdays.');
        }

        // CREATE APPOINTMENT (PENDING PAYMENT)
        $appointment = Appointment::create([
            'customer_id' => Auth::id(),
            'service_id' => $request->service_id,
            'barber_id' => $request->barber_id,
            'appointment_date' => $request->appointment_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'price' => $service->price,
            'status' => 'pending_payment',
            'notes' => $request->notes,
        ]);

        // 🔥 REDIRECT TO STRIPE
        return redirect()->route('customer.appointments.pay', $appointment->id);
    }


    // =========================
// PAY VIA STRIPE
// =========================
    public function pay(Appointment $appointment)
    {
        abort_if($appointment->customer_id !== Auth::id(), 403);

        // Set Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'myr',
                        'product_data' => [
                            'name' => $appointment->service->name,
                        ],
                        'unit_amount' => $appointment->price * 100, // in sen
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('customer.appointments.payment.success', $appointment->id),
            'cancel_url' => route('customer.appointments.payment.cancel', $appointment->id),
        ]);

        // Update status ke pending_payment
        $appointment->update(['status' => 'pending_payment']);

        // Redirect user to Stripe checkout page
        return redirect($session->url);
    }


    // =========================
    // PAYMENT SUCCESS
    // =========================
    public function paymentSuccess(Appointment $appointment)
    {
        abort_if($appointment->customer_id !== Auth::id(), 403);

        $appointment->update([
            'status' => 'confirmed',
            'paid_at' => now(),
        ]);

        return redirect()
            ->route('customer.appointments.show', $appointment->id)
            ->with('success', 'Payment successful! Appointment confirmed.');
    }

    // =========================
    // PAYMENT CANCEL
    // =========================
    public function paymentCancel(Appointment $appointment)
    {
        abort_if($appointment->customer_id !== Auth::id(), 403);

        return redirect()
            ->route('customer.appointments.show', $appointment->id)
            ->with('error', 'Payment cancelled.');
    }



    // Cancel appointment
    public function cancel(Appointment $appointment)
    {
        if ($appointment->customer_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This appointment cannot be cancelled.');
        }

        // ✅ FIX DOUBLE TIME ISSUE
        $appointmentDateTime = Carbon::parse($appointment->appointment_date)
            ->setTimeFromTimeString($appointment->start_time)
            ->timezone('Asia/Kuala_Lumpur');

        // $now = Carbon::now('Asia/Kuala_Lumpur');

        // if ($appointmentDateTime->diffInHours($now) < 1) {
        //     return back()->with('error', 'Cancel at least 1 hour before appointment.');
        // }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Appointment cancelled successfully!');
    }

    // Add this to your controller (getAvailableSlots method):
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'barber_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
        ]);

        $service = Service::findOrFail($request->service_id);
        $duration = $service->duration;

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
                $start = sprintf('%02d:%02d:00', $hour, $minute);
                $end = Carbon::createFromFormat('H:i:s', $start)
                    ->addMinutes($duration)
                    ->format('H:i:s');

                // Check if slot ends before business closing time
                if ($end <= '22:00:00') {
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

        // Get barber's existing appointments for that day
        $existing = Appointment::where('barber_id', $request->barber_id)
            ->where('appointment_date', $request->date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get(['start_time', 'end_time']);

        $available = array_filter($slots, function ($slot) use ($existing) {
            // Filter out past slots
            if ($slot['past']) {
                return false;
            }

            // Filter out overlapping slots
            foreach ($existing as $appointment) {
                if (
                    strtotime($slot['start']) < strtotime($appointment->end_time) &&
                    strtotime($slot['end']) > strtotime($appointment->start_time)
                ) {
                    return false;
                }
            }
            return true;
        });

        return response()->json([
            'available_slots' => array_values($available),
            'selected_date' => $request->date,
            'current_time' => $now->format('H:i:s'),
        ]);
    }
}
