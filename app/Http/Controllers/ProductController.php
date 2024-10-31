<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Define allowed sort fields
        $allowedSortFields = ['title', 'id', 'created_at'];

        // Retrieve sorting parameters
        $sortField = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('order', 'desc');

        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        // Ensure valid sort order
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'desc';
        $products = Product::sorted($sortField, $sortOrder)->paginate(10);

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found'], Response::HTTP_NO_CONTENT);
        }

        return response()->json($products, Response::HTTP_OK);
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
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'price' => 'required|numeric',
            'discount_price' => 'numeric',
            'description' => 'nullable|string',
        ]);

        // $product = Product::create($validatedData);

        return response()->json(['message' => 'data created'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('categories')->find($id);

        // Check if the product exists
        if (!$product) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        // Return the product with its associated categories
        return response()->json($product, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
