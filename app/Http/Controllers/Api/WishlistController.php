<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index(): JsonResponse
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('product.category')
            ->latest()
            ->get();
        return response()->json($wishlists);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $wishlist = Wishlist::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $request->product_id]
        );

        $wishlist->load('product.category');
        return response()->json($wishlist, 201);
    }

    public function destroy($id): JsonResponse
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->findOrFail($id);
        $wishlist->delete();
        return response()->json(['message' => 'Removed from wishlist']);
    }

    public function toggle(Request $request): JsonResponse
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['in_wishlist' => false, 'message' => 'Removed']);
        }

        $wishlist = Wishlist::create(['user_id' => Auth::id(), 'product_id' => $request->product_id]);
        $wishlist->load('product.category');
        return response()->json(['in_wishlist' => true, 'wishlist' => $wishlist], 201);
    }

    public function check($productId): JsonResponse
    {
        $inWishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();
        return response()->json(['in_wishlist' => $inWishlist]);
    }
}
