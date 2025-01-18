<?php
namespace App\Http\Controllers\admin\api;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'gender' => 'required|in:men,women,unisex',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate images
            'variants' => 'nullable|array',
        ]);

        // Create product
        $product = Product::create($validated);

        // Handle product images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('images/products', 'public');
                $product->images()->create([
                    'image_url' => $imagePath,
                    'is_primary' => false // Set primary logic as needed
                ]);
            }
        }

        // Handle variants
        if ($request->has('variants')) {
            foreach (json_decode($request->variants) as $variant) {
                $product->variants()->create((array)$variant);
            }
        }

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product->load('variants', 'images') // Load variants and images
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'brand' => 'required|string|max:100',
            'gender' => 'required|in:men,women,unisex',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate images
            'variants' => 'nullable|array',
            'variants.*.size' => 'required|string|max:50',
            'variants.*.color' => 'required|string|max:50',
            'variants.*.sku' => 'required|string|unique:product_variants,sku,' . $id,
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.price_adjustment' => 'nullable|numeric'
        ]);

        $product->update($validated);

        // Handle product images
        if ($request->hasFile('images')) {
            // Clear existing images if needed
            $product->images()->each(function ($image) {
                Storage::disk('public')->delete($image->image_url);
                $image->delete();
            });

            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('images/products', 'public');
                $product->images()->create([
                    'image_url' => $imagePath,
                    'is_primary' => false // Set primary logic as needed
                ]);
            }
        }

        // Handle variants
        if ($request->has('variants')) {
            // Clear existing variants and create new ones
            $product->variants()->delete();
            foreach ($request->variants as $variant) {
                $product->variants()->create($variant);
            }
        }

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product->load('variants', 'images') // Load variants and images
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->images()->each(function ($image) {
            Storage::disk('public')->delete($image->image_url); // Delete image from storage
            $image->delete(); // Delete image record from database
        });

        $product->variants()->delete(); // Delete all variants
        $product->delete(); // Delete the product

        return response()->json(['message' => 'Product deleted successfully'], 204);
    }
}
