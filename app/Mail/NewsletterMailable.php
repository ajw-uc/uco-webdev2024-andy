<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;


class NewsletterMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New products for you',
            // from: new Address('andy.wicaksono@ciputra.ac.id', 'Andy'),
            // replyTo: [
            //     new Address('andycobacoba@gmail.com', 'Andy Reply'),
            // ]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $products = Product::inRandomOrder()->limit(5)->get();

        return new Content(
            view: 'newsletter.mail',
            with: [
                'products' => $products
            ]
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
