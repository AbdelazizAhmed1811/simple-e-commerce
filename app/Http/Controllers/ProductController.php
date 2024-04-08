<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = QueryBuilder::for(Product::class)
            ->allowedFilters(['name', 'description', 'product_type', 'categories'])
            ->paginate();
        return new ProductCollection($products);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with([
            'comments' => function ($query) {
                $query->orderBy('created_at', 'desc'); // Sort comments by created_at date in descending order
            }
        ])->findOrFail($id);

        return new ProductResource($product);
    }

    // public function show($id)
    // {
    //     $product = Product::findOrFail($id);

    //     $comments = $product->comments()->latest('created_at')->paginate(5); // Paginate comments and sort them by newest

    //     return (new ProductResource($product))->additional(['comments' => $comments]);
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
