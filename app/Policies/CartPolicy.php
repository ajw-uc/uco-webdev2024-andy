<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;

class CartPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function checkout(User $user): bool
    {
        return $user->cart_items->isNotEmpty();
    }

    public function delete_cart_item(User $user, Cart $cart): bool
    {
        return $cart->quantity == 1;
    }
}
