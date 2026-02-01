<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        $staffId = auth()->id();
        $appointments = Appointment::where('barber_id', $staffId)
            ->with(['customer', 'service'])
            ->whereIn('status', ['pending_payment', 'confirmed'])
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->get();

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

        return view('staff.schedule.index', compact('appointments', 'calendarEvents'));
    }
}