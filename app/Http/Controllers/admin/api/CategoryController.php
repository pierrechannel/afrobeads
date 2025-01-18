<?php

namespace App\Http\Controllers\admin\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return response()->json(['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Validation de l'image
        ]);

        // Gestion de l'upload de l'image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/categories', 'public');
            $validated['image'] = $imagePath; // Enregistrez le chemin de l'image dans les données validées
        }

        // Créez la catégorie
        $category = Category::create($validated);
        return response()->json(['category' => $category], 201);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Validation de l'image
        ]);

        // Gestion de l'upload de l'image
        if ($request->hasFile('image')) {
            // Supprimez l'ancienne image si elle existe
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $imagePath = $request->file('image')->store('images/categories', 'public');
            $validated['image'] = $imagePath; // Mettez à jour avec le nouveau chemin d'image
        }

        // Mettez à jour la catégorie
        $category->update($validated);
        return response()->json(['category' => $category]);
    }

    public function show(Category $category)
    {
        return response()->json([
            'category' => $category->load(['products', 'children'])
        ]);
    }

    public function destroy(Category $category)
    {
        // Supprimez la catégorie
        $category->delete();
        return response()->json([], 204);
    }
}
