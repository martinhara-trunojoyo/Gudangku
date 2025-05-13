<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StockReduced extends Mailable
{
    use Queueable, SerializesModels;

    public $productName;
    public $staffName;
    public $recipientName;
    public $umkmName;
    public $quantity;
    public $currentStock;
    public $unit;
    public $destination;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $productName,
        $staffName,
        $recipientName,
        $umkmName,
        $quantity,
        $currentStock,
        $unit = 'unit',
        $destination = 'Unknown'
    ) {
        $this->productName = $productName;
        $this->staffName = $staffName;
        $this->recipientName = $recipientName;
        $this->umkmName = $umkmName;
        $this->quantity = $quantity;
        $this->currentStock = $currentStock;
        $this->unit = $unit;
        $this->destination = $destination;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Stock Reduced: {$this->productName} - {$this->umkmName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.stock-reduced',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}