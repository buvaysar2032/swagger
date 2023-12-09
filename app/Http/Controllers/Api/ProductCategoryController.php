<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Info(
 *     title="Your API",
 *     version="1.0.0",
 *     description="Description of your API",
 *     @OA\Contact(
 *         email="contact@example.com",
 *         name="Contact Name"
 *     ),
 *     @OA\License(
 *         name="License Name",
 *         url="https://www.example.com/license"
 *     )
 * )
 */

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/productcategory",
     *     tags={"Product Category"},
     *     summary="Get list of users",
     *     @OA\Response(response="200", description="List of users")
     * )
     */
    public function index()
    {
        return ProductCategory::all();
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * @OA\Post(
     *     path="/api/productcategory",
     *     tags={"Product Category"},
     *     summary="Store a newly created product category",
     *     @OA\RequestBody(
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", example="Category Name"),
     *                 required={"name"}
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Product category created")
     * )
     */
    public function store(StoreProductCategoryRequest $request)
    {
        $requestData = $request->all();
        ProductCategory::create($requestData);
        return "$requestData[name]";
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/api/productcategory/{id}",
     *     tags={"Product Category"},
     *     summary="Display the specified product category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product category",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="The specified product category"),
     *     @OA\Response(response="404", description="Product category not found")
     * )
     */
    public function show($id)
    {
        try {
            $productCategory = ProductCategory::findOrFail($id);
            $products = DB::table('products')->where('product_id', $productCategory->id)->get();
            return response()->json([$productCategory, $products], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            return response()->json(['error' => 'Ma\'lumot topilmadi'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/api/productcategory/{id}",
     *     tags={"Product Category"},
     *     summary="Update a product category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product category to update",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", minLength=3, example="Updated category name")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Product category updated"),
     *     @OA\Response(response="404", description="Product category not found"),
     *     @OA\Response(response="422", description="Validation error")
     * )
     */
    public function update(UpdateProductCategoryRequest $request, $id)
    {
        try {
            $productCategory = ProductCategory::findOrFail($id);
            $productCategory->update($request->all());
            return response()->json(['message' => 'Yangilandi'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            return response()->json(['error' => 'Ma\'lumot topilmadi'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/productcategory/{id}",
     *     tags={"Product Category"},
     *     summary="Delete a product category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product category to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="No content"),
     *     @OA\Response(response="404", description="Product category not found"),
     * )
     */

    public function destroy($id)
    {
        $productCategory = ProductCategory::findOrFail($id);
        $productCategory->delete();
    }
}
