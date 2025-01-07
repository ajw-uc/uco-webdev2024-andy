<x-template title="Shopping cart">
    <div class="container-lg py-3">
        <h1>Shopping cart</h1>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th colspan="2" style="min-width:300px">Product</th>
                        <th class="text-center" style="width:200px">Quantity</th>
                        <th class="text-end" style="width:150px">Price</th>
                        <th class="text-end" style="width:150px">Subtotal</th>
                    </tr>
                </thead>
                @php $total = 0 @endphp
                <tbody class="table-group-divider">
                    @foreach($cartItems as $item)
                    <tr>
                        <td style="width:100px">
                            <img src="{{ asset($item->product->image) }}" style="max-width:100px;max-height:100px;">
                        </td>
                        <td>
                            <a href="{{ route('products.show', ['id' => $item->product_id]) }}">
                                {{ $item->product->name }}
                            </a>
                        </td>
                        <td>
                            <div class="d-flex gap-2" style="width:200px">
                                <form method="post" action="{{ route('cart.update', ['id' => $item->id]) }}">
                                    @csrf
                                    <div class="input-group">
                                        <input type="number" class="form-control" onchange="this.form.submit()" name="quantity" value="{{ $item->quantity }}">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                    </div>
                                </form>
                                <form method="post" action="{{ route('cart.destroy', ['id' => $item->id]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                        <td class="text-end">
                            Rp {{ number_format($item->product->price, 0, ',', '.') }}
                        </td>
                        <td class="text-end">
                            Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                            @php $total += $item->product->price * $item->quantity @endphp
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="4" class="fw-semibold">Total</td>
                        <td class="text-end fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @if($cartItems->isNotEmpty())
        <a href="{{ route('purchase.order') }}" class="btn btn-success btn-lg">Checkout</a>
        @endif
    </div>
</x-template>
