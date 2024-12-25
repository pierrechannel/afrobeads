<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
    use App\Models\Category;



class CategoryController extends Controller
{

        public function index()
        {
            return Category::all();
        }

        public function show($id)
        {
            return Category::findOrFail($id);
        }

        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|string',
            ]);

            $category = Category::create($request->all());
            return response()->json($category, 201);
        }

        public function update(Request $request, $id)
        {
            $category = Category::findOrFail($id);
            $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'image' => 'nullable|string',
            ]);

            $category->update($request->all());
            return response()->json($category, 200);
        }

        public function destroy($id)
        {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(null, 204);
        }

}
