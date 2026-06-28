@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <div class="card-stat bg-white rounded-xl p-5 border border-slate-200/80 shadow-sm ring-1 ring-slate-900/5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Users</p>
                    <p class="text-2xl font-extrabold text-slate-900 mt-1 tracking-tight">{{ $totalUsers }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-blue-50 flex items-center justify-center text-xl ring-1 ring-blue-100">👥</div>
            </div>
        </div>
        <div class="card-stat bg-white rounded-xl p-5 border border-slate-200/80 shadow-sm ring-1 ring-slate-900/5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Products</p>
                    <p class="text-2xl font-extrabold text-slate-900 mt-1 tracking-tight">{{ $totalProducts }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center text-xl ring-1 ring-emerald-100">📦</div>
            </div>
        </div>
        <div class="card-stat bg-white rounded-xl p-5 border border-slate-200/80 shadow-sm ring-1 ring-slate-900/5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Categories</p>
                    <p class="text-2xl font-extrabold text-slate-900 mt-1 tracking-tight">{{ $totalCategories }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center text-xl ring-1 ring-amber-100">🗂️</div>
            </div>
        </div>
        <div class="card-stat bg-white rounded-xl p-5 border border-slate-200/80 shadow-sm ring-1 ring-slate-900/5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Revenue</p>
                    <p class="text-2xl font-extrabold text-primary-700 mt-1 tracking-tight">${{ number_format($revenue, 2) }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-violet-50 flex items-center justify-center text-xl ring-1 ring-violet-100">💰</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200/80 shadow-sm ring-1 ring-slate-900/5">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-primary-700 hover:text-primary-800 transition-colors">View all →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50/80 text-slate-500">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-xs uppercase tracking-wider">Order #</th>
                            <th class="px-6 py-3 text-left font-semibold text-xs uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left font-semibold text-xs uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left font-semibold text-xs uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentOrders as $order)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3.5 font-mono text-xs text-slate-700">{{ $order->order_number }}</td>
                            <td class="px-6 py-3.5 text-slate-700">{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-3.5 font-semibold text-slate-900">${{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-semibold tracking-wide
                                    {{ $order->status == 'pending' ? 'bg-amber-50 text-amber-700 ring-1 ring-amber-100' :
                                       ($order->status == 'completed' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100' : 'bg-slate-100 text-slate-700 ring-1 ring-slate-200') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(!$recentOrders->count())
            <div class="px-6 py-10 text-center text-slate-500 text-sm">No recent orders found.</div>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm ring-1 ring-slate-900/5 p-5">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide mb-4">Quick Stats</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3.5 rounded-lg bg-amber-50/60 border border-amber-100">
                    <span class="text-sm font-medium text-slate-700">Pending Orders</span>
                    <span class="font-extrabold text-amber-700 text-lg">{{ $pendingOrders }}</span>
                </div>
                <div class="flex items-center justify-between p-3.5 rounded-lg bg-blue-50/60 border border-blue-100">
                    <span class="text-sm font-medium text-slate-700">Total Orders</span>
                    <span class="font-extrabold text-blue-700 text-lg">{{ $totalOrders }}</span>
                </div>
                <div class="flex items-center justify-between p-3.5 rounded-lg bg-emerald-50/60 border border-emerald-100">
                    <span class="text-sm font-medium text-slate-700">Completed</span>
                    <span class="font-extrabold text-emerald-700 text-lg">{{ $completedOrders }}</span>
                </div>
            </div>

            <div class="mt-5 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.products.create') }}" class="flex w-full items-center justify-center gap-2 py-2.5 text-center bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-semibold text-sm transition-colors shadow-sm shadow-primary-700/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Product
                </a>
            </div>
        </div>
    </div>

    <!-- Latest Products -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm ring-1 ring-slate-900/5">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Latest Products</h3>
            <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-primary-700 hover:text-primary-800 transition-colors">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80 text-slate-500">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-xs uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left font-semibold text-xs uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left font-semibold text-xs uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left font-semibold text-xs uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left font-semibold text-xs uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($latestProducts as $product)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3.5 font-semibold text-slate-900">{{ $product->name }}</td>
                        <td class="px-6 py-3.5 text-slate-600">{{ $product->category->name ?? 'Uncategorized' }}</td>
                        <td class="px-6 py-3.5">${{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-3.5 text-slate-700">{{ $product->stock }}</td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-semibold tracking-wide
                                {{ $product->is_active ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100' : 'bg-slate-100 text-slate-700 ring-1 ring-slate-200' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500 text-sm">No products found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
