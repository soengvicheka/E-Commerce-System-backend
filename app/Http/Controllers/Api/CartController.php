<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(): JsonResponse
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product.category')
            ->get();
        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        return response()->json(['items' => $cartItems, 'total' => $total]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < ($request->quantity ?? 1)) {
            return response()->json(['message' => 'Not enough stock'], 422);
        }

        $cart = Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $request->product_id],
            ['quantity' => $request->quantity ?? 1]
        );

        $cart->load('product.category');
        return response()->json($cart, 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:99']);

        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);

        if ($cart->product->stock < $request->quantity) {
            return response()->json(['message' => 'Not enough stock'], 422);
        }

        $cart->update(['quantity' => $request->quantity]);
        $cart->load('product.category');
        return response()->json($cart);
    }

    public function destroy($id): JsonResponse
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->delete();
        return response()->json(['message' => 'Item removed']);
    }

    public function clear(): JsonResponse
    {
        Cart::where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'Cart cleared']);
    }
}
