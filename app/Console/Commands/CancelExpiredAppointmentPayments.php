<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;

class CancelExpiredAppointmentPayments extends Command
{
    protected $signature = 'appointments:cancel-expired-payments';

    protected $description = 'Cancel pending appointment payments after the retry window expires.';

    public function handle(): int
    {
        $cancelled = Appointment::cancelExpiredPendingPayments();

        $this->info("Cancelled {$cancelled} expired pending appointment payment(s).");

        return self::SUCCESS;
    }
}
