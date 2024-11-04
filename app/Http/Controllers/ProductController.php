<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
        $categoryIds = $request->has('category_ids') ? json_decode($request->input('category_ids'), true) : null;
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON format for category_ids must be json array with ids'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $variants = $request->has('variants') ? json_decode($request->input('variants'), true) : null;
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON format for variants'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validator = Validator::make(array_merge($request->all(), ['variants' => $variants, 'category_ids' => $categoryIds]), [
            'title' => ['required', 'max:255'],
            'description' => ['nullable', 'max:280'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['numeric', 'min:0'],


            'category_ids' => [
                'array',
                'distinct',
            ],
            'category_ids.*' => [
                'integer',
                Rule::exists('categories', 'id'),
            ],


            'variant_name' => ['string', 'max:255', Rule::requiredIf($request->has('variants'))],
            'variants' => ['nullable', 'array', Rule::requiredIf($request->has('variant_name'))],
            'variants.*.name' => [
                Rule::requiredIf(fn() => $request->has('variants')),
                'string',
                'max:100'
            ],
            'variants.*.price' => [
                Rule::requiredIf(fn() => $request->has('variants')),
                'numeric'
            ]
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validatedData = $validator->validate();
        $product = Product::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'image_path' => $request->hasFile('image') ? $request->file('image')->store('product_images', 'public') : null,
            'price' => $validatedData['price'],
            'sale_price' => $validatedData['sale_price'] ?? null,
        ]);

        if (isset($validatedData['category_ids']) && !empty($validatedData['category_ids'])) {
            $product->categories()->attach($categoryIds);
        }

        if (isset($validatedData['variant_name']) && isset($validatedData['variants']) && !empty($validatedData['variants'])) {
            foreach ($variants as $variant) {
                Variant::create([
                    'product_id' => $product->id,
                    'name' => $variant['name'],
                    'price' => $variant['price'],
                    'type' => $validatedData['variant_name'],
                ]);
            }
        }

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
