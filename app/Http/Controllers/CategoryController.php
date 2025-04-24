<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:100|unique:categories',
            'category_description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = Category::create($validator->validated());

        return response()->json([
            'message' => 'Categoría creada exitosamente',
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'category_name' => 'sometimes|string|max:100|unique:categories,category_name,'.$id.',category_id',
            'category_description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category->update($validator->validated());

        return response()->json([
            'message' => 'Categoría actualizada exitosamente',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        // Verificar si la categoría está siendo usada en alguna meta
        if ($category->goals()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar la categoría porque está asociada a metas existentes'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'Categoría eliminada exitosamente'
        ]);
    }
}
