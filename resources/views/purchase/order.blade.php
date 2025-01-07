<x-template title="Checkout">
    @if($errors->has('alert'))
        <div class="alert alert-danger alert-dismissible fade show rounded-0 mb-0" role="alert">
            {{ $errors->first('alert') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container-lg py-3">
        <h1>Checkout</h1>
        <div class="mb-3">
            <label for="name" class="form-label">Items</label>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th colspan="2" style="min-width:300px">Product</th>
                            <th class="text-end" style="width:100px">Quantity</th>
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
                                {{ $item->product->name }}
                            </td>
                            <td class="text-end">
                                {{ $item->quantity }}
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
        </div>
        <form class="was-validated" method="post" action="{{ route('purchase.store') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Shipping address</label>
                <textarea class="form-control" name="address" id="address" required>{{ old('address') ?? '' }}</textarea>
                @if($errors->has('name'))
                    <div class="text-danger">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Payment method</label>
                <select class="form-select" name="payment_method" required>
                    <option value="" disabled selected>Please select payment method</option>
                    @foreach($paymentMethods as $paymentMethod)
                    <option value="{{ $paymentMethod->value }}" @if(old('payment_method') == $paymentMethod->value) selected @endif>{{ $paymentMethod->label() }}</option>
                    @endforeach
                </select>
                @if($errors->has('name'))
                    <div class="text-danger">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-success btn-lg">Order Now</button>
            </div>
        </form>
    </div>
</x-template>
