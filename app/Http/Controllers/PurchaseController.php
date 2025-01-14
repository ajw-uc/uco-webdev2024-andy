<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Models\Cart;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Notifications\PurchaseOrdered;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class PurchaseController extends Controller
{
    function index(Request $request)
    {
        $purchases = Purchase::where('user_id', $request->user()->id)->paginate();

        return view('purchase.index', [
            'purchases' => $purchases
        ]);
    }

    function show(string $id, Request $request)
    {
        $purchase = Purchase::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('purchase.show', [
            'purchase' => $purchase
        ]);
    }

    function order(Request $request)
    {
        if (!Gate::allows('checkout', \App\Models\Cart::class)) {
            return redirect()->route('cart.list');
        }

        return view('purchase.order', [
            'cartItems' => $request->user()->cart_items,
            'paymentMethods' => PaymentMethod::cases()
        ]);
    }

    function store(Request $request)
    {
        $request->validate([
            'address' => ['required', 'string'],
            'payment_method' => ['required', Rule::in(array_column(PaymentMethod::cases(), 'value'))],
        ]);

        try {
            DB::beginTransaction();

            // Hitung total dan tampung array untuk diinsert ke purchase_details
            $details = [];
            $total_price = 0;
            foreach ($request->user()->cart_items as $item) {
                $details[] = [
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'description' => $item->product->description,
                    'image' => $item->product->image,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity
                ];
                $total_price += $item->product->price * $item->quantity;
            }

            // Buat purchase
            $purchase = Purchase::create([
                'user_id' => $request->user()->id,
                'total_price' => $total_price,
                'address' => $request->address,
                'payment_method' => $request->payment_method
            ]);

            // Tambahkan kolom purchase_id, created_at, dan updated_at karena tidak bisa otomatis terisi jika menggunakan fungsi insert
            $timestamp = Carbon::now();

            foreach ($details as &$detail) {
                $detail['purchase_id'] = $purchase->id;
                $detail['created_at'] = $timestamp;
                $detail['updated_at'] = $timestamp;
            }

            // Tambahkan detail produk dan qty ke purchase detail
            PurchaseDetail::insert($details);

            // Hapus semua item di cart
            Cart::where('user_id', $request->user()->id)->delete();

            $purchase = Purchase::where('id', $purchase->id)->first();
            $request->user()->notify(new PurchaseOrdered($purchase));

            DB::commit();
            return redirect()->route('purchase.show', ['id' => $purchase->id]);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors([
                'alert' => $e->getMessage()
            ]);
        }
    }
}
