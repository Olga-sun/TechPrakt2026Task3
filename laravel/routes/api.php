<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;

Route::apiResource('recipes', RecipeController::class);
