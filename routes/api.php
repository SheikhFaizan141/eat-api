<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('products', function (Request $request) {
    $products = Product::latest()->paginate(10);

    if ($products->isEmpty()) {
        return response()->json(['message' => 'No products found'], Response::HTTP_NO_CONTENT);
    }

    return response()->json($products, Response::HTTP_OK);
});


Route::get('categories', function (Request $request) {
    $categories = Product::latest()->paginate(10);

    if ($categories->isEmpty()) {
        return response()->json(['message' => 'No categories found'], Response::HTTP_NO_CONTENT);
    }

    return response()->json($categories, Response::HTTP_OK);
});


