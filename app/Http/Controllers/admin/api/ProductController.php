<?php

namespace App\Http\Controllers\admin\api;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;



class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants', 'images']);

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by gender
        if ($request->has('gender')) {
            $query->where('gender', $request->gender);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Sort products
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        return response()->json([
            'products' => $query->paginate(12)
        ]);
    }

    public function show($id)
    {
        $product = Product::with([
            'category',
            'variants' => function($query) {
                $query->where('stock_quantity', '>', 0);
            },
            'images'
        ])->findOrFail($id);

        return response()->json([
            'product' => $product
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'brand' => 'required|string|max:100',
            'gender' => 'required|in:men,women,unisex'
        ]);

        $product = Product::create($validated);

        // Handle variants
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                $product->variants()->create($variant);
            }
        }

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product->load('variants')
        ], 201);
    }
}
