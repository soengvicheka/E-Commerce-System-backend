<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::where("is_active", true)->get();
        return response()->json($categories);
    }

    public function show($id): JsonResponse
    {
        $category = Category::where("is_active", true)->findOrFail($id);
        return response()->json($category);
    }

    public function products($id): JsonResponse
    {
        $category = Category::where("is_active", true)->findOrFail($id);
        $products = $category->products()->where("is_active", true)->paginate(12);
        return response()->json($products);
    }
}
