<?php

use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('menu', [MenuController::class, 'index']);

Route::get('menu/{id}', [MenuController::class, 'show']);

Route::middleware(['auth:sanctum', 'can:admin'])->group(function () {
    Route::get('products', [ProductController::class, 'index']);

    Route::get('products/{id}', [ProductController::class, 'show']);

    Route::post('products', [ProductController::class, 'store']);

    Route::patch('products/{product}', [ProductController::class, 'update']);

    Route::delete('products/{product}', [ProductController::class, 'destroy']);

    Route::resource('admin/categories', AdminCategoryController::class)->except(['create', 'edit']);
});


Route::get('test', function () {
    $s = config('sanctum.stateful');
    return response()->json(['url', parse_url('http://localhost:3000', PHP_URL_HOST), 'data' => $s]);
});
