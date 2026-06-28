@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="bg-white rounded-xl border shadow-sm">
    <div class="p-6 border-b flex items-center justify-between">
        <h3 class="font-semibold text-gray-800">All Products</h3>
        <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium">+ New Product</a>
    </div>
    <div class="p-4 border-b">
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="px-4 py-2 border rounded-lg w-64">
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">Search</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-gray-600">Product</th>
                    <th class="px-6 py-3 text-left text-gray-600">Category</th>
                    <th class="px-6 py-3 text-left text-gray-600">Price</th>
                    <th class="px-6 py-3 text-left text-gray-600">Stock</th>
                    <th class="px-6 py-3 text-left text-gray-600">Active</th>
                    <th class="px-6 py-3 text-left text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-10 h-10 rounded object-cover">
                            @endif
                            <div>
                                <p class="font-medium">{{ $product->name }}</p>
                                <p class="text-xs text-gray-400">{{ $product->sku ?: 'No SKU' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $product->category->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">${{ number_format($product->sale_price ?? $product->price, 2) }}</td>
                    <td class="px-6 py-4">{{ $product->stock }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 flex items-center gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="px-3 py-1 rounded bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 rounded bg-red-50 text-red-600 hover:bg-red-100 text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $products->links() }}</div>
</div>
@endsection
