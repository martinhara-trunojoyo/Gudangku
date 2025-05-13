<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LowStockAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $productName;
    public $recipientName;
    public $umkmName;
    public $currentStock;
    public $minimumStock;
    public $unit;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $productName,
        $recipientName,
        $umkmName,
        $currentStock,
        $minimumStock,
        $unit = 'unit'
    ) {
        $this->productName = $productName;
        $this->recipientName = $recipientName;
        $this->umkmName = $umkmName;
        $this->currentStock = $currentStock;
        $this->minimumStock = $minimumStock;
        $this->unit = $unit;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "URGENT: Low Stock Alert for {$this->productName} - {$this->umkmName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.low-stock-alert',
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