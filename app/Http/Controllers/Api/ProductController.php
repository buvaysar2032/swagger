<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;



class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Get list of users",
     *     @OA\Response(response="200", description="List of users")
     * )
     */
    public function index()
    {
        $products = Product::all();

        $products->each(function ($product) {
            $product->image_url = asset('storage/uploads/' . $product->image);
        });

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Store a new product",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="product_id", type="integer", example="1"),
     *                 @OA\Property(property="name", type="string", example="Product Name"),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *                 @OA\Property(property="old_price", type="number", format="float", example="50.00"),
     *                 @OA\Property(property="price", type="number", format="float", example="40.00"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product added successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product category not found"
     *     )
     * )
     */
    public function store(StoreProductRequest $request)
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store("uploads", 'public');
        }
        $requestData = $request->all();
        $requestData['image'] = $path;
        Product::create($requestData);
        return "Ma'lumot qo'shildi";
    }

    /**
     * Display the specified resource.
     */

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Display a specific product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to display",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Product details"),
     *     @OA\Response(response="404", description="Product not found"),
     * )
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        return response()->json(['data' => $product], 200);
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Update a specific product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="product_id", type="integer", example="1"),
     *                 @OA\Property(property="name", type="string", example="Product Name"),
     *                 @OA\Property(property="image", type="file", format="binary"),
     *                 @OA\Property(property="old_price", type="number", format="float", example="50.00"),
     *                 @OA\Property(property="price", type="number", format="float", example="40.00"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Product updated"),
     *     @OA\Response(response="404", description="Product not found"),
     *     @OA\Response(response="422", description="Validation error"),
     * )
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $requestData = $request->all();
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store("uploads", 'public');
            $requestData['image'] = $path;
        }
        $product->update($requestData);

        return response()->json(['message' => 'Product updated', 'data' => $product], 200);
    }

    /**
     * Remove the specified resource from storage.
     */

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Delete a specific product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="No content"),
     *     @OA\Response(response="404", description="Product not found"),
     * )
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $product->delete();
        return response()->json(null, 204);
    }
}
