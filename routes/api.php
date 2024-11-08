<?php

use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\MenuController;
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

Route::patch('products/{product}', [ProductController::class, 'update']);

Route::delete('products/{product}', [ProductController::class, 'destroy']);

Route::resource('admin/categories', AdminCategoryController::class)->except(['create', 'edit']);

Route::get('menu', [MenuController::class, 'index']);

Route::get('menu/{id}', [MenuController::class, 'show']);


Route::middleware(['auth:sanctum', 'can:admin'])->group(function() {
    Route::get('/admin/p', fn() => response()->json('nice tits'));
});



// Route::get('menu', function () {
//     $products = Product::with('categories')->with(['variants' => function ($query) {
//         $query->orderBy('name');
//     }])->get();


//     // dd($products);
//     return response()->json($products);
// });
