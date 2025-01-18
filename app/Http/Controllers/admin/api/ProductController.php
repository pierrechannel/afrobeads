<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
    protected $validationRules = [
        'category_id' => 'required|exists:categories,id',
        'name' => 'required|string|min:3|max:255',
        'description' => 'required|string|min:20',
        'base_price' => 'required|numeric|min:0',
        'brand' => 'required|string|max:100',
        'gender' => 'required|in:men,women,unisex',
        'images' => 'required|array|min:1',
        'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
        'variants' => 'required|json',
    ];

    public function index(Request $request)
    {
        try {
            $query = Product::with(['category', 'variants', 'images']);

            // Apply filters
            $this->applyFilters($query, $request);

            // Apply search
            if ($search = $request->input('search')) {
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                          ->orWhere('description', 'LIKE', "%{$search}%")
                          ->orWhere('brand', 'LIKE', "%{$search}%")
                          ->orWhereHas('variants', function ($q) use ($search) {
                              $q->where('sku', 'LIKE', "%{$search}%");
                          });
                });
            }

            // Apply sorting
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');
            $allowedSortFields = ['name', 'created_at', 'base_price', 'brand'];

            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            $perPage = $request->input('per_page', 12);
            $products = $query->paginate($perPage);

            return response()->json([
                'products' => $products,
                'filters' => $this->getAvailableFilters()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch products',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::with([
                'category',
                'variants' => function($query) {
                    $query->orderBy('stock_quantity', 'desc');
                },
                'images' => function($query) {
                    $query->orderBy('is_primary', 'desc');
                }
            ])->findOrFail($id);

            return response()->json([
                'product' => $product,
                'related_products' => $this->getRelatedProducts($product)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Product not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate basic product data
            $validated = $request->validate($this->validationRules);

            // Create product
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'description' => $validated['description'],
                'base_price' => $validated['base_price'],
                'brand' => $validated['brand'],
                'gender' => $validated['gender']
            ]);

            // Handle image uploads
            $this->handleImageUploads($product, $request->file('images'));

            // Handle variants
            $this->handleVariants($product, $request->variants);

            DB::commit();

            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product->load(['variants', 'images', 'category'])
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to create product',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            // Modify validation rules for update
            $rules = $this->validationRules;
            $rules['images'] = 'nullable|array'; // Images not required for update
            $rules['variants.*.sku'] = "required|string|unique:product_variants,sku,NULL,id,product_id,{$id}";

            $validated = $request->validate($rules);

            // Update basic product info
            $product->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'description' => $validated['description'],
                'base_price' => $validated['base_price'],
                'brand' => $validated['brand'],
                'gender' => $validated['gender']
            ]);

            // Handle image updates if new images provided
            if ($request->hasFile('images')) {
                $this->handleImageUploads($product, $request->file('images'), true);
            }

            // Update variants
            if ($request->has('variants')) {
                $this->handleVariants($product, $request->variants, true);
            }

            DB::commit();

            return response()->json([
                'message' => 'Product updated successfully',
                'product' => $product->load(['variants', 'images', 'category'])
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to update product',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            // Delete images from storage and database
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_url);
                $image->delete();
            }

            // Delete variants
            $product->variants()->delete();

            // Delete product
            $product->delete();

            DB::commit();

            return response()->json([
                'message' => 'Product deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to delete product',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    protected function handleImageUploads($product, $images, $deleteExisting = false)
    {
        // Delete existing images if requested
        if ($deleteExisting) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_url);
                $image->delete();
            }
        }

        // Upload and save new images
        foreach ($images as $index => $image) {
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('images/products', $filename, 'public');

            $product->images()->create([
                'image_url' => $path,
                'is_primary' => $index === 0
            ]);
        }
    }

    protected function handleVariants($product, $variantsJson, $deleteExisting = false)
    {
        $variants = json_decode($variantsJson, true);

        if ($deleteExisting) {
            $product->variants()->delete();
        }

        foreach ($variants as $variant) {
            $product->variants()->create([
                'size' => $variant['size'],
                'color' => $variant['color'],
                'sku' => $variant['sku'],
                'stock_quantity' => $variant['stock_quantity'],
                'price_adjustment' => $variant['price_adjustment'] ?? 0
            ]);
        }
    }

    protected function applyFilters(Builder $query, Request $request)
    {
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->filled('price_min')) {
            $query->where('base_price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('base_price', '<=', $request->price_max);
        }

        if ($request->filled('in_stock')) {
            $query->whereHas('variants', function($q) {
                $q->where('stock_quantity', '>', 0);
            });
        }
    }

    protected function getAvailableFilters()
    {
        return [
            'categories' => Category::select('id', 'name')->get(),
            'brands' => Product::distinct()->pluck('brand'),
            'price_range' => [
                'min' => Product::min('base_price'),
                'max' => Product::max('base_price')
            ]
        ];
    }

    protected function getRelatedProducts(Product $product)
    {
        return Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['images'])
            ->take(4)
            ->get();
    }
}
