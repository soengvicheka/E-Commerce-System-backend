<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            'customer_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:1000',
            'phone' => 'required|string|max:30',
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
            'customer_name' => $request->customer_name,
            'phone' => $request->phone,
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

        $this->sendTelegramOrderAlert($order);

        $order->load('items.product');
        return response()->json($order, 201);
    }

    protected function sendTelegramOrderAlert(Order $order): void
    {
        $chatId = config('services.telegram.chat_id');
        $token  = config('services.telegram.bot_token');

        if (! $chatId || ! $token) {
            Log::warning('Telegram alert skipped: missing chat_id or bot_token.');
            return;
        }

        $text = $this->buildTelegramText($order);

        try {
            $response = Http::timeout(10)
                ->retry(2, 250)
                ->withOptions(['verify' => false])
                ->post(
                    "https://api.telegram.org/bot{$token}/sendMessage",
                    [
                        'chat_id' => $chatId,
                        'text' => $text,
                        'parse_mode' => 'HTML',
                    ]
                );

            if (! $response->successful()) {
                Log::warning('Telegram alert failed: ' . $response->body());
            }
        } catch (\Throwable $e) {
            Log::warning('Telegram alert failed: ' . $e->getMessage());
        }
    }

    protected function buildTelegramText(Order $order): string
    {
        $lines = [
            '<b>New Order Received</b>',
            '',
            "Order: <b>{$order->order_number}</b>",
            "Customer: {$order->customer_name}",
            "Phone: {$order->phone}",
            "Payment: {$order->payment_method}",
            "Status: {$order->status}",
            "Total: \$" . number_format((float) $order->total, 2),
            '',
            '<b>Items:</b>',
        ];

        foreach ($order->items as $item) {
            $lines[] = "• {$item->product_name} x{$item->quantity} = \$" . number_format((float) $item->subtotal, 2);
        }

        $lines[] = '';
        $lines[] = "Shipping to: {$order->shipping_address}";

        if (! empty($order->notes)) {
            $lines[] = "Notes: {$order->notes}";
        }

        return implode("\n", $lines);
    }
}
