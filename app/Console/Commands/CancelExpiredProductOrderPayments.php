<?php

namespace App\Console\Commands;

use App\Models\ProductOrder;
use Illuminate\Console\Command;

class CancelExpiredProductOrderPayments extends Command
{
    protected $signature = 'product-orders:cancel-expired-payments';

    protected $description = 'Cancel pending product order payments after the retry window expires.';

    public function handle(): int
    {
        $cancelled = ProductOrder::cancelExpiredPendingPayments();

        $this->info("Cancelled {$cancelled} expired pending product order payment(s).");

        return self::SUCCESS;
    }
}
