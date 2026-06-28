@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="bg-white rounded-xl border shadow-sm">
    <div class="p-6 border-b">
        <h3 class="font-semibold text-gray-800">All Orders</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-gray-600">Order #</th>
                    <th class="px-6 py-3 text-left text-gray-600">Customer</th>
                    <th class="px-6 py-3 text-left text-gray-600">Total</th>
                    <th class="px-6 py-3 text-left text-gray-600">Status</th>
                    <th class="px-6 py-3 text-left text-gray-600">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono text-xs">{{ $order->order_number }}</td>
                    <td class="px-6 py-4">{{ $order->user->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">${{ number_format($order->total, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-700' :
                               ($order->status == 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $orders->links() }}</div>
</div>
@endsection
