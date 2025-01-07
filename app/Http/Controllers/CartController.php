<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    function index(Request $request)
    {
        return view('cart.index', [
            'cartItems' => $request->user()->cart_items
        ]);
    }

    function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer'],
        ]);

        $userId = $request->user()->id;

        $cart = Cart::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cart) {
            $cart->quantity += $request->quantity;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return back();
    }

    function update(string $id, Request $request)
    {
        $userId = $request->user()->id;

        $request->validate([
            'quantity' => ['required', 'integer'],
        ]);

        $cart = Cart::where('user_id', $userId)
            ->where('id', $id)
            ->firstOrFail();

        $cart->quantity = $request->quantity;
        $cart->save();

        return redirect()->route('cart.list');
    }

    function destroy(string $id, Request $request)
    {
        $userId = $request->user()->id;

        $cart = Cart::where('user_id', $userId)
            ->where('id', $id)
            ->firstOrFail();

        $cart->delete();

        return redirect()->route('cart.list');
    }
}
