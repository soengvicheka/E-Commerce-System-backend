<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index($productId): JsonResponse
    {
        $reviews = Review::where('product_id', $productId)
            ->with('user:id,name')
            ->latest()
            ->get();
        return response()->json($reviews);
    }

    public function store(Request $request, $productId): JsonResponse
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($productId);

        $review = Review::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $productId],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        $review->load('user:id,name');
        return response()->json($review, 201);
    }

    public function destroy($productId, $reviewId): JsonResponse
    {
        $review = Review::where('product_id', $productId)->findOrFail($reviewId);

        if ($review->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->delete();
        return response()->json(['message' => 'Review deleted']);
    }
}
