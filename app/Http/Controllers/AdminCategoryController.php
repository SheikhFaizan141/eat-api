<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Validation\Rule;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $sortField = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('order', 'desc');

        $categories = Category::sorted($sortField, $sortOrder)->paginate(10);

        if ($categories->isEmpty()) {
            return response()->json(['message' => 'No categories found'], Response::HTTP_NO_CONTENT);
        }

        return response()->json($categories, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
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
    public function show(Category $category): JsonResponse
    {
        return response()->json(['data' => $category], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $attributes = $request->validate([
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
    public function destroy(Category $category): Response
    {
        $category->delete();

        // return response()->json(['message' => 'Category deleted successfully'], Response::HTTP_NO_CONTENT);
        return response()->noContent();
    }
}
