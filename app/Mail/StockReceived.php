<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StockReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $productName;
    public $quantity;
    public $newStock;
    public $unit;
    public $supplierName;
    public $addedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $productName,
        int $quantity,
        int $newStock,
        string $unit,
        string $supplierName,
        string $addedBy
    ) {
        $this->productName = $productName;
        $this->quantity = $quantity;
        $this->newStock = $newStock;
        $this->unit = $unit;
        $this->supplierName = $supplierName;
        $this->addedBy = $addedBy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Stock Received: {$this->productName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.stock.received',
            with: [
                'productName' => $this->productName,
                'quantity' => $this->quantity,
                'newStock' => $this->newStock,
                'unit' => $this->unit,
                'supplierName' => $this->supplierName,
                'addedBy' => $this->addedBy,
                'url' => url('/dashboard/inventory'),
            ],
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
