<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use App\Services\BarberAvailabilityService;
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
        Appointment::cancelExpiredPendingPayments();

        $appointments = Appointment::with(['service', 'barber'])
            ->where('customer_id', Auth::id())
            ->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        $appointments->getCollection()->each(function (Appointment $appointment) {
            $appointment->ensurePaymentWindow();
        });

        return view('customer.appointments.index', compact('appointments'));
    }

    // Show - Single appointment details
    public function show(Appointment $appointment)
    {
        if ($appointment->customer_id !== Auth::id()) {
            abort(403);
        }

        if ($appointment->cancelIfPaymentExpired()) {
            return redirect()
                ->route('customer.appointments.show', $appointment->id)
                ->with('error', 'Payment window expired. Your appointment has been cancelled.');
        }

        $appointment->ensurePaymentWindow();

        $appointment->load(['service', 'barber']);

        return view('customer.appointments.show', compact('appointment'));
    }

    // Create - Show booking form
    public function create()
    {
        Appointment::cancelExpiredPendingPayments();

        $services = Service::where('status', 'active')->get();
        $barbers = User::where('role', 'staff')
            ->where('status', 'active')
            ->get();

        return view('customer.appointments.create', compact('services', 'barbers'));
    }

    // Store - Save new appointment
    public function store(Request $request, BarberAvailabilityService $availability)
    {
        Appointment::cancelExpiredPendingPayments();

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'barber_id' => 'required|exists:users,id',
            'booking_for' => 'required|in:self,other',
            'recipient_name' => 'nullable|required_if:booking_for,other|string|max:255',
            'recipient_age' => 'nullable|required_if:booking_for,other|integer|min:0|max:120',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i:s', 
            'notes' => 'nullable|string|max:500',
        ]);

        $service = Service::findOrFail($request->service_id);
        $recipient = Appointment::recipientPayload(Auth::user(), $request->all());
        $price = Appointment::priceForRecipient($service, $recipient['recipient_age']);

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
            ->whereIn('status', ['pending', 'pending_payment', 'confirmed'])
            ->where(function ($q) use ($start_time, $end_time) {
                $q->where('start_time', '<', $end_time)
                    ->where('end_time', '>', $start_time);
            })
            ->exists();

        if ($hasCustomerConflict) {
            return back()->withInput()->with('error', 'You already have an appointment during this time.');
        }

        $appointmentConflict = $availability->findAppointmentConflict(
            (int) $request->barber_id,
            $request->appointment_date,
            Carbon::parse($request->appointment_date . ' ' . $start_time, 'Asia/Kuala_Lumpur'),
            Carbon::parse($request->appointment_date . ' ' . $end_time, 'Asia/Kuala_Lumpur')
        );

        if ($appointmentConflict) {
            return back()->withInput()->with('error', 'Selected barber is already booked. ' . $availability->appointmentConflictMessage($appointmentConflict));
        }

        $walkInConflict = $availability->findServingWalkInConflict(
            (int) $request->barber_id,
            $request->appointment_date,
            Carbon::parse($request->appointment_date . ' ' . $start_time, 'Asia/Kuala_Lumpur'),
            Carbon::parse($request->appointment_date . ' ' . $end_time, 'Asia/Kuala_Lumpur')
        );

        if ($walkInConflict) {
            return back()->withInput()->with('error', 'Selected barber is not available. ' . $availability->walkInConflictMessage($walkInConflict));
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
            'booking_for' => $recipient['booking_for'],
            'recipient_name' => $recipient['recipient_name'],
            'recipient_age' => $recipient['recipient_age'],
            'service_id' => $request->service_id,
            'barber_id' => $request->barber_id,
            'appointment_date' => $request->appointment_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'price' => $price,
            'status' => 'pending_payment',
            'payment_expires_at' => now('Asia/Kuala_Lumpur')->addMinutes(Appointment::PAYMENT_RETRY_MINUTES),
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

        $appointment->loadMissing('service', 'customer');

        if ($appointment->cancelIfPaymentExpired()) {
            return redirect()
                ->route('customer.appointments.show', $appointment->id)
                ->with('error', 'Payment window expired. Your appointment has been cancelled.');
        }

        if ($appointment->status === Appointment::STATUS_CONFIRMED) {
            return redirect()
                ->route('customer.appointments.show', $appointment->id)
                ->with('success', 'Payment already completed.');
        }

        if ($appointment->status !== Appointment::STATUS_PENDING_PAYMENT) {
            return redirect()
                ->route('customer.appointments.show', $appointment->id)
                ->with('error', 'This appointment is not available for payment.');
        }

        $appointment->ensurePaymentWindow();

        if ($appointment->isPaymentExpired()) {
            $appointment->cancelIfPaymentExpired();

            return redirect()
                ->route('customer.appointments.show', $appointment->id)
                ->with('error', 'Payment window expired. Your appointment has been cancelled.');
        }

        if (!env('STRIPE_SECRET')) {
            return redirect()
                ->route('customer.appointments.show', $appointment->id)
                ->with('error', 'Stripe payment is not configured yet.');
        }

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'myr',
                            'product_data' => [
                                'name' => $appointment->service->name . ' for ' . $appointment->recipient_display_name,
                            ],
                            'unit_amount' => (int) round($appointment->price * 100), // in sen
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
                'success_url' => route('customer.appointments.payment.success', $appointment->id) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('customer.appointments.payment.cancel', $appointment->id),
                'metadata' => [
                    'appointment_id' => $appointment->id,
                    'customer_id' => $appointment->customer_id,
                ],
            ]);
        } catch (\Throwable $exception) {
            return redirect()
                ->route('customer.appointments.show', $appointment->id)
                ->with('error', 'Unable to start Stripe checkout. Please try again before the payment window expires.');
        }

        $appointment->update(['stripe_session_id' => $session->id]);

        // Redirect user to Stripe checkout page
        return redirect($session->url);
    }


    // =========================
    // PAYMENT SUCCESS
    // =========================
    public function paymentSuccess(Request $request, Appointment $appointment)
    {
        abort_if($appointment->customer_id !== Auth::id(), 403);

        if ($appointment->status === Appointment::STATUS_CONFIRMED) {
            return redirect()
                ->route('customer.appointments.show', $appointment->id)
                ->with('success', 'Payment already completed.');
        }

        if (!$request->filled('session_id')) {
            return redirect()
                ->route('customer.appointments.show', $appointment->id)
                ->with('error', 'Payment session was missing. Please try again.');
        }

        if ($appointment->stripe_session_id && $appointment->stripe_session_id !== $request->session_id) {
            return redirect()
                ->route('customer.appointments.show', $appointment->id)
                ->with('error', 'Payment session did not match this appointment.');
        }

        if ($appointment->cancelIfPaymentExpired()) {
            return redirect()
                ->route('customer.appointments.show', $appointment->id)
                ->with('error', 'Payment was not completed within 15 minutes. Your appointment has been cancelled.');
        }

        if ($appointment->status !== Appointment::STATUS_PENDING_PAYMENT) {
            return redirect()
                ->route('customer.appointments.show', $appointment->id)
                ->with('error', 'This appointment is not available for payment.');
        }

        $appointment->update([
            'status' => Appointment::STATUS_CONFIRMED,
            'paid_at' => now('Asia/Kuala_Lumpur'),
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

        if ($appointment->cancelIfPaymentExpired()) {
            return redirect()
                ->route('customer.appointments.show', $appointment->id)
                ->with('error', 'Payment window expired. Your appointment has been cancelled.');
        }

        $minutesRemaining = $appointment->paymentMinutesRemaining();
        $message = $minutesRemaining > 0
            ? "Payment cancelled. You can retry payment within {$minutesRemaining} minute(s)."
            : 'Payment cancelled.';

        return redirect()
            ->route('customer.appointments.show', $appointment->id)
            ->with('error', $message);
    }



    // Cancel appointment
    public function cancel(Appointment $appointment)
    {
        if ($appointment->customer_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($appointment->status, ['pending', 'pending_payment', 'confirmed'])) {
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
    public function getAvailableSlots(Request $request, BarberAvailabilityService $availability)
    {
        Appointment::cancelExpiredPendingPayments();

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

        $available = array_filter($slots, function ($slot) use ($availability, $request) {
            // Filter out past slots
            if ($slot['past']) {
                return false;
            }

            return !$availability->slotHasConflict(
                (int) $request->barber_id,
                $request->date,
                $slot['start'],
                $slot['end']
            );
        });

        return response()->json([
            'available_slots' => array_values($available),
            'selected_date' => $request->date,
            'current_time' => $now->format('H:i:s'),
        ]);
    }
}
