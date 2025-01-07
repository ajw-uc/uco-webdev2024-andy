<x-template title="Purchase #{{ $purchase->id }}">
    @if($errors->has('alert'))
        <div class="alert alert-danger alert-dismissible fade show rounded-0 mb-0" role="alert">
            {{ $errors->first('alert') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container-lg py-3">
        <h1>Purchase #{{ $purchase->id }}</h1>
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
                    <tbody class="table-group-divider">
                        @foreach($purchase->details as $item)
                        <tr>
                            <td style="width:100px">
                                <img src="{{ asset($item->image) }}" style="max-width:100px;max-height:100px;">
                            </td>
                            <td>
                                {{ $item->name }}
                            </td>
                            <td class="text-end">
                                {{ $item->quantity }}
                            </td>
                            <td class="text-end">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td class="text-end">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr>
                            <td colspan="4" class="fw-semibold">Total</td>
                            <td class="text-end fw-bold">Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="row">

            <div class="col-md-4">
                <label for="name" class="form-label">Shipping address</label>
                <div class="form-text">
                    {{ $purchase->address }}
                </div>
            </div>
            <div class="col-md-4">
                <label for="name" class="form-label">Payment method</label>
                <div class="form-text">
                    {{ $purchase->payment_method->label() }}
                </div>
            </div>
            <div class="col-md-4">
                <label for="name" class="form-label">Status</label>
                <div class="form-text">
                    {{ $purchase->status->label() }}
                </div>
            </div>
        </div>
    </div>
</x-template>
