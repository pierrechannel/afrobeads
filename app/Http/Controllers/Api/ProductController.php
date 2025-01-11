<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('category')->get();
    }

    public function show($id)
    {
        return Product::with('category')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'stock' => 'required|integer',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = 'images/' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public', $imagePath);
        }

        // Create the product with or without an image
        $productData = $request->all();
        $productData['image'] = $imagePath;

        $product = Product::create($productData);

        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'sometimes|required|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'stock' => 'sometimes|required|integer',
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            // Check if an image currently exists and delete it
            if ($imagePath) {
                Storage::delete('public/' . $imagePath);
            }

            $image = $request->file('image');
            $imagePath = 'images/' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public', $imagePath);
        }

        $productData = $request->all();
        $productData['image'] = $imagePath;

        $product->update($productData);

        return response()->json($product, 200);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete the existing image
        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }

        $product->delete();

        return response()->json(null, 204);
    }
}
