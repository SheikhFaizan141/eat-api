<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
            'description' => 'nullable|max:280',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'category_ids' => 'required|json', // Validate as JSON string
            'variants' => 'json', // Validate as JSON string
        ]);

        $product = new Product([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'sale_price' => $validatedData['sale_price'] ?? null,
        ]);


        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');

            // dd($imagePath);
            $product->image_path = $imagePath;
        }

        $product->save();
        
        if (isset($validatedData['category_ids']) && !empty($validatedData['category_ids'])) {
            $categoryIds = json_decode($validatedData['category_ids'], true);

            if (!is_array($categoryIds)) {
                return response()->json(["message" => "categories id's must be of type array"], Response::HTTP_BAD_REQUEST);
            }

            // Retrieve existing category IDs from the database
            $existingCategoryIds = Category::whereIn('id', $categoryIds)->pluck('id')->toArray();

            // Find any missing category IDs
            $missingCategoryIds = array_diff($categoryIds, $existingCategoryIds);
            if (!empty($missingCategoryIds)) {
                return response()->json([
                    "message" => "Some category IDs do not exist",
                    "missing_ids" => $missingCategoryIds
                ], Response::HTTP_BAD_REQUEST);
            }
            
            $product->categories()->attach($categoryIds);
        }

        if (isset($validatedData['variants']) && !empty($validatedData['variants'])) {
            $variants = json_decode($validatedData['variants'], true);
            
        }

        // $result = Product;

        $data = Product::with(['categories', 'variants'])->find($product->id)->append('image_url')->makeHidden('pivot');

        return response()
            ->json([
                'message' => 'data created',
                'data' => $data
            ], Response::HTTP_CREATED);
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
