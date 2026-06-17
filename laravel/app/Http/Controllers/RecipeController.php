<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    // 1. GET: Отримати всі рецепти
    public function index()
    {
        return response()->json(Recipe::all());
    }

    // 2. POST: Створити новий рецепт
    public function store(Request $request)
    {
        $recipe = Recipe::create($request->all());
        return response()->json($recipe, 201);
    }

    // 3. GET: Отримати один конкретний рецепт
    public function show(string $id)
    {
        $recipe = Recipe::find($id);
        if (!$recipe) return response()->json(['message' => 'Рецепт не знайдено'], 404);

        return response()->json($recipe);
    }

    // 4. PATCH: Оновити рецепт
    public function update(Request $request, string $id)
    {
        $recipe = Recipe::find($id);
        if (!$recipe) return response()->json(['message' => 'Рецепт не знайдено'], 404);

        $recipe->update($request->all());
        return response()->json($recipe);
    }

    // 5. DELETE: Видалити рецепт
    public function destroy(string $id)
    {
        $recipe = Recipe::find($id);
        if (!$recipe) return response()->json(['message' => 'Рецепт не знайдено'], 404);

        $recipe->delete();
        return response()->json(['message' => 'Рецепт успішно видалено']);
    }
}
