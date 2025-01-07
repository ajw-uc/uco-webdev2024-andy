<x-template title="Purchase History">
    <div class="container py-3">
        <h1>Purchase history</h1>
        <table class="table table-hover table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($purchases as $purchase)
                <tr>
                    <td>
                        <a href="{{ route('purchase.show', ['id' => $purchase->id]) }}">
                            Purchase #{{ $purchase->id }}
                        </a>
                    </td>
                    <td>{{ $purchase->created_at }}</td>
                    <td>{{ $purchase->status->label() }}</td>
                    <td class="text-end">Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $purchases->links() }}
    </div>
</x-template>
