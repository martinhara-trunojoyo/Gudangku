<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductAdded extends Mailable
{
    use Queueable, SerializesModels;

    public $productName;
    public $quantity;
    public $newStock;
    public $unit;
    public $supplierName;
    public $addedBy;
    public $recipientName;
    public $umkmName;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $productName,
        string $addedBy,
        string $recipientName, 
        string $umkmName,
        int $quantity = 0,
        int $newStock = 0,
        string $unit = 'unit',
        string $supplierName = 'Unknown'
    ) {
        $this->productName = $productName;
        $this->addedBy = $addedBy;
        $this->recipientName = $recipientName;
        $this->umkmName = $umkmName;
        $this->quantity = $quantity;
        $this->newStock = $newStock;
        $this->unit = $unit;
        $this->supplierName = $supplierName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Stock Added: {$this->productName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.product-added',
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
