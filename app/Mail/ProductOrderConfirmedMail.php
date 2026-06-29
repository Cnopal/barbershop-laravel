<?php

namespace App\Mail;

use App\Models\ProductOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductOrderConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public ProductOrder $order;

    public function __construct(ProductOrder $order)
    {
        $this->order = $order->loadMissing(['customer', 'items']);
    }

    public function build(): self
    {
        return $this
            ->subject("Your Men's Club Product Order Is Confirmed")
            ->view('emails.product_orders.confirmed');
    }
}