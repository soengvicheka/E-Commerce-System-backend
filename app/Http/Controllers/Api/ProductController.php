<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::where("is_active", true)
            ->with(['category', 'reviews'])
            ->withCount('reviews');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('featured')) {
            $query->where("is_featured", true);
        }

        $products = $query->paginate(12);
        return response()->json($products);
    }

    public function show($id): JsonResponse
    {
        $product = Product::where("is_active", true)
            ->with(['category', 'reviews.user'])
            ->withCount('reviews')
            ->findOrFail($id);
        return response()->json($product);
    }

    public function featured(): JsonResponse
    {
        $products = Product::where("is_active", true)
            ->where("is_featured", true)
            ->with(['category', 'reviews'])
            ->withCount('reviews')
            ->take(8)
            ->get();
        return response()->json($products);
    }

    public function latest(): JsonResponse
    {
        $products = Product::where("is_active", true)
            ->with(['category', 'reviews'])
            ->withCount('reviews')
            ->latest()
            ->take(8)
            ->get();
        return response()->json($products);
    }
}
