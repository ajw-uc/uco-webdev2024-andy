<?php

namespace App\Notifications;

use App\Models\Purchase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

// Tambahkan implements ShouldQueue di class untuk menggunakan queue
class PurchaseOrdered extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Purchase $purchase)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $products = '<div style="padding:20px;background-color:#e5e5e5;margin-bottom:15px">
            <table border="1" style="width:100%" cellpadding="5" cellspacing="0">
            <thead>
            <tr>
                <th style="text-align:left">Product</th>
                <th style="text-align:right">Qty</th>
                <th style="text-align:right">Price</th>
                <th style="text-align:right">Subtotal</th>
            </tr>
            </thead>
            <tbody>';
        foreach ($this->purchase->details as $detail) {
            $products .= '<tr>
                <td style="text-align:left">'.$detail->name.'</td>
                <td style="text-align:right">'.$detail->quantity.'</td>
                <td style="text-align:right">Rp '.number_format($detail->price, 0, ',', '.').'</td>
                <td style="text-align:right">Rp '.number_format($detail->price * $detail->quantity, 0, ',', '.').'</td>
                </tr>';
        }
        $products .= '</tbody>
            </table>
            </div>';

        return (new MailMessage)
                    ->subject('New purchase order #'.$this->purchase->id)
                    ->greeting('Hello '.$this->purchase->user->name)
                    ->line('You have made a purchase order')
                    ->line('Your total amount is Rp '.number_format($this->purchase->total_price, 0, ',', '.'))
                    ->line(new HtmlString($products))
                    ->line('Payment method: '.$this->purchase->payment_method->label())
                    ->line('Shipping address: '. $this->purchase->address)
                    ->action('View purchase', route('purchase.show', ['id' => $this->purchase->id]))
                    ->line('Thank you for shopping with us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'text' => 'Purchase order #'.$this->purchase->id.' has been made',
            'route' => 'purchase.show',
            'routeParam' => ['id' => $this->purchase->id]
        ];
    }
}
