<?php

namespace App\Enums;

enum PurchaseStatus: string
{
    case Unpaid = 'unpaid';
    case Paid = 'paid';
    case Delivering = 'delivering';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    function label(): string
    {
        return match($this)
        {
            self::Unpaid => 'Waiting for payment',
            self::Paid => 'Payment confirmed',
            self::Delivering => 'On delivery',
            self::Delivered => 'Delivered',
            self::Cancelled => 'Cancelled',
        };
    }
}
