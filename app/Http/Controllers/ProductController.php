<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::select();
        $searchQueries = [];

        $sortOptions = [
            'name_asc' => 'Name A-Z',
            'name_desc' => 'Name Z-A',
            'price_asc' => 'Lowest price',
            'price_desc' => 'Highest price'
        ];

        if ($request->category) {
            $products->where('category_id', $request->category);
            $category = Category::where('id', $request->category)->first();
            if ($category) {
                $searchQueries[] = $category->name;
            }
        }

        if ($request->search) {
            $products->where(function($query) use ($request) {
                $query->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            });

            $searchQueries[] = '"'.$request->search.'"';
        }

        if ($request->priceFrom) {
            $products->where('price', '>=', $request->priceFrom);
        }

        if ($request->priceTo) {
            $products->where('price', '<=', $request->priceTo);
        }

        if ($request->sort) {
            [$sortColumn, $sortDirection] = explode('_', $request->sort);
            $products->orderBy($sortColumn, $sortDirection ?? 'asc');
        } else {
            $products->orderBy('name', 'asc');
        }

        // $products = $products->get();

        $products = $products->paginate(12)->withQueryString();

        return view('products.index', [
            'products' => $products,
            'searchQueries' => $searchQueries,
            'sortOptions' => $sortOptions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::getOrdered();
        return view('products.form', [
            'title' => 'Add new product',
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255', Rule::unique('products')],
            'description' => ['string'],
            'price' => ['required', 'numeric'],
            'category_id' => ['required', Rule::in(Category::getOrdered()->pluck('id'))]
        ]);

        try {
            DB::beginTransaction();

            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'category_id' => $request->category_id
            ]);

            $this->uploadFile($request, $product);

            DB::commit();
            return redirect()->route('products.show', ['id' => $product->id])
                ->withSuccess('New product has been added!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors([
                'alert' => 'Failed to add new product'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::where('id', $id)->firstOrFail();
        return view('products.show', [
            'product' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::where('id', $id)->firstOrFail();
        $categories = Category::getOrdered();
        return view('products.form', [
            'title' => 'Edit product',
            'product' => $product,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::where('id', $id)->firstOrFail();

        $request->validate([
            'name' => ['required', 'max:255', Rule::unique('products')->ignore($product->id)],
            'description' => ['string'],
            'price' => ['required', 'numeric'],
            'category_id' => ['required', Rule::in(Category::getOrdered()->pluck('id'))],
            'image' => [File::image()->max('2mb')]
        ]);

        try {
            DB::transaction(function () use ($product, $request) {
                $product->name = $request->name;
                $product->description = $request->description;
                $product->price = $request->price;
                $product->category_id = $request->category_id;
                $product->save();

                $this->uploadFile($request, $product);
            });

            return redirect()->route('products.show', ['id' => $product->id])
                ->withSuccess('Product has been edited');
        } catch (\Exception $e) {
            return back()->withErrors([
                'alert' => 'Failed to edit product'
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::where('id', $id)->firstOrFail();
        $product->delete();

        return redirect()->route('products.list');
    }

    private function uploadFile(Request $request, Product $product) {
        if ($request->file('image')) {
            /* Menyimpan file ke folder sesuai dengan FILESYSTEM_DRIVER .env dengan nama random */
            $path = $request->file('image')->store('products');

            /* Menyimpan file ke folder sesuai dengan FILESYSTEM_DRIVER .env dengan nama yang sudah ditentukan */
            $extension = $request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('products', $product->id.'.'.$extension);

            $product->image = 'storage/'.$path;
            return $product->save();
        }

        return false;
    }
}
