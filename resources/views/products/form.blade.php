<x-template title="Add new product">
    <div class="container py-3">
        <h1>{{ $title }}</h1>
        <form class="was-validated" method="post" action="{{ route('products.store') }}">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" name="name" id="name">
              </div>
              <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" name="description" id="description"></textarea>
              </div>
              <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" name="price" id="price">
              </div>
              <div class="mb-3">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
        </form>
    </div>
</x-template>
