<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->latest()
            ->paginate(10);
        return response()->json($orders);
    }

    public function show($id): JsonResponse
    {
        $order = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->findOrFail($id);
        return response()->json($order);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'shipping_address' => 'required|string|max:1000',
            'payment_method' => 'required|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 422);
        }

        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $tax = 0;
        $shipping = $subtotal > 500 ? 0 : 50;
        $total = $subtotal + $tax + $shipping;

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'ORD-' . time() . '-' . rand(1000, 9999),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'shipping_address' => $request->shipping_address,
            'billing_address' => $request->shipping_address,
            'notes' => $request->notes,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'product_image' => $item->product->image,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
                'subtotal' => $item->product->price * $item->quantity,
            ]);
        }

        Cart::where('user_id', Auth::id())->delete();

        $order->load('items.product');
        return response()->json($order, 201);
    }
}
