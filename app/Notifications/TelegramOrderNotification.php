<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class TelegramOrderNotification extends Notification
{
    protected array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function via($notifiable): array
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable): ?string
    {
        $chatId = config('services.telegram.chat_id');
        $token  = config('services.telegram.bot_token');

        if (!$chatId || !$token) {
            return null;
        }

        $text = $this->buildText();

        $response = Http::timeout(10)->post(
            "https://api.telegram.org/bot{$token}/sendMessage",
            [
                'chat_id' => $chatId,
                'text'    => $text,
                'parse_mode' => 'HTML',
            ]
        );

        return $response->successful() ? $response->body() : null;
    }

    protected function buildText(): string
    {
        $p = $this->payload;

        $lines = [
            '<b>New Order Received</b>',
            '',
            "Order: <b>{$p['order_number']}</b>",
            "Customer: {$p['customer_name']}",
            "Phone: {$p['phone']}",
            "Payment: {$p['payment_method']}",
            "Status: {$p['status']}",
            "Total: {$p['currency']}{$p['total']}",
            '',
            '<b>Items:</b>',
        ];

        foreach ($p['items'] as $item) {
            $lines[] = "• {$item['name']} x{$item['qty']} = {$p['currency']}{$item['subtotal']}";
        }

        $lines[] = '';
        $lines[] = "Shipping to: {$p['shipping_address']}";

        if (!empty($p['notes'])) {
            $lines[] = "Notes: {$p['notes']}";
        }

        return implode("\n", $lines);
    }
}
