<x-template title="Daftar produk">
    <div class="container-fluid py-3">
        <div class="row">
            {{-- @for($i=0; $i<20; $i++)
            <div class="col-md-2 mb-3">
                <x-product-display name="Produk {{ $i }}" price="5000" id="{{ $i }}" image="https://picsum.photos/200/200"></x-product-display>
            </div>
            @endfor --}}
            @foreach($products as $product)
            <div class="col-md-2 mb-3">
                <x-product-display :name="$product['name']" :price="$product['price']" :id="$product['id']" :image="$product['image']"></x-product-display>
            </div>
            @endforeach
        </div>
    </div>

    <a href="{{ route('products.create') }}" class="btn btn-success position-fixed bottom-0 end-0 m-3">
        Add new product
    </a>
</x-template>
