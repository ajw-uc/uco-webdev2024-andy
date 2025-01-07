<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case BankTransfer = 'banktransfer';
    case CreditCard = 'creditcard';
    case CashOnDelivery = 'cashondelivery';

    function label(): string
    {
        return match($this)
        {
            self::BankTransfer => 'Bank Transfer',
            self::CreditCard => 'Credit Card',
            self::CashOnDelivery => 'Cash On Delivery'
        };
    }
}
