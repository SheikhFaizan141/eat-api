<?php

use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('products', [ProductController::class, 'index']);

Route::get('products/{id}', [ProductController::class, 'show']);

Route::post('products', [ProductController::class, 'store']);

Route::get('categories', function (Request $request) {
    $categories = Product::latest()->paginate(10);

    if ($categories->isEmpty()) {
        return response()->json(['message' => 'No categories found'], Response::HTTP_NO_CONTENT);
    }

    return response()->json($categories, Response::HTTP_OK);
});


Route::get('menu', function() {
    $products = Product::with('categories')->with(['variants' => function ($query) {
        $query->orderBy('name');
    }])->get();


    // dd($products);
    return response()->json($products);
});

