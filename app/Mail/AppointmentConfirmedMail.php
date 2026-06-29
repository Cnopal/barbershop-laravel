<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Appointment $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment->loadMissing(['customer', 'service', 'barber']);
    }

    public function build(): self
    {
        return $this
            ->subject('Your Men\'s Club Appointment Is Confirmed')
            ->view('emails.appointments.confirmed');
    }
}