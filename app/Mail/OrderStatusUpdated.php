<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $newStatus;

    public function __construct($order, string $newStatus)
    {
        $this->order     = $order;
        $this->newStatus = $newStatus;
    }

    public function envelope(): Envelope
    {
        $label = ucfirst(str_replace('_', ' ', $this->newStatus));
        return new Envelope(
            subject: 'Your Order ' . $this->order->order_ref . ' – Status Update: ' . $label,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status',
        );
    }
}
