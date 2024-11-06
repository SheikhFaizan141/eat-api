<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Define allowed sort fields
        $allowedSortFields = ['id', 'name', 'created_at'];

        // Retrieve sorting parameters
        $sortField = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('order', 'desc');

        // Default sorting order
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        // Ensure valid sort order
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'desc';
        $products = Category::sorted($sortField, $sortOrder)->paginate(10);

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found'], Response::HTTP_NO_CONTENT);
        }

        return response()->json($products, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => ['required', Rule::unique('categories', 'name'), 'max:255'],
            'description' => ['nullable', 'string', 'max:1000']
        ]);

        $category = Category::create($attributes);

        return response()->json([
            'message' => 'Category created successfully',
            'data' => $category
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json(['data' => $category], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $attributes = request()->validate([
            'name' => ['required', Rule::unique('categories', 'name')->ignore($category->id), 'max:255'],
            'description' => ['nullable', 'string', 'max:1000']
        ]);

        $category->update($attributes);

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
