<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = QueryBuilder::for(Product::class)
            ->allowedFilters(['name', 'description', 'product_type', 'category', 'price', 'in_stock'])
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
    public function store(StoreProductRequest $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }

        try {
            $validated = $request->validated();


            // $path = $validated['img']->store('public/products');
            $imageName = uniqid() . '.' . $validated['img']->extension();

            $validated['img']->move(public_path('uploads'), $imageName);



            Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'img' => $imageName,
                'product_type' => $validated['product_type'],
                'price' => $validated['price'],
                'in_stock' => $validated['in_stock'],
                'category' => $validated['category'],
            ]);

            // return response()->json(['message' => 'Product created successfully', 'path' => storage_path('app') . "/" . $path], 201);
            return response()->json(['message' => 'Product created successfully'], 201);


        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage()
            ], 500);
        }
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

    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            // Check if the authenticated user has the 'admin' role

            if (auth()->user()->role !== 'admin') {
                return response()->json(['message' => 'You are not authorized to perform this action'], 403);
            }

            $validated = $request->validated();

            // dd($validated);
            // Check if a new image was uploaded
            if (isset($validated['img'])) {
                // Attempt to delete the old product image
                $imagePath = public_path('uploads') . '/' . $product->img;

                if (File::exists($imagePath)) {
                    // Delete the file
                    File::delete($imagePath);
                }

                $imageName = uniqid() . '.' . $validated['img']->extension();

                $validated['img']->move(public_path('uploads'), $imageName);
            } else {
                $imageName = $product->img;
            }

            // Update the product
            $updateData = [
                'img' => $imageName,
                'name' => $validated['name'] ?? $product->name, // Use existing name if not provided in request
                'description' => $validated['description'] ?? $product->description,
                'product_type' => $validated['product_type'] ?? $product->product_type,
                'price' => $validated['price'] ?? $product->price,
                'in_stock' => $validated['in_stock'] ?? $product->in_stock,
                'category' => $validated['category'] ?? $product->category,
            ];

            // Remove null values from the update data
            $updateData = array_filter($updateData, function ($value) {
                return $value !== null;
            });

            $product->update($updateData);

            return response()->json(['message' => 'Product updated successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Request $request, Product $product)
    {
        try {
            // Check if the authenticated user has the 'admin' role
            if (auth()->user()->role !== 'admin') {
                return response()->json(['message' => 'You are not authorized to perform this action'], 403);
            }

            // Attempt to delete the product image
            $imagePath = public_path('uploads') . '/' . $product->img;

            if (File::exists($imagePath)) {
                // Delete the file
                File::delete($imagePath);
            }

            // Delete the product
            $product->delete();

            return response()->json(['message' => 'Product deleted successfully'], 200);
        } catch (\Throwable $th) {
            // If an error occurs, return an error response
            return response()->json([
                "message" => $th->getMessage()
            ], 500);
        }
    }

}