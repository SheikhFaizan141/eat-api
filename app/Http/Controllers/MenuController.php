<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $categories = Category::with(['products.variants'])->get();

        return response()->json(["data" => $categories], Response::HTTP_OK);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $product = Product::with(['categories', 'variants'])->findOrFail($id);

        // Hide the pivot data for each category in the categories relationship
        $product->categories->each(fn($category) => $category->makeHidden('pivot'));

        return response()->json([
            'message' => 'Product retrieved successfully',
            'data' => $product
        ], Response::HTTP_OK);
    }
}
