@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="bg-white rounded-xl border shadow-sm">
    <div class="p-6 border-b">
        <h3 class="font-semibold text-gray-800">All Customers</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-gray-600">Name</th>
                    <th class="px-6 py-3 text-left text-gray-600">Email</th>
                    <th class="px-6 py-3 text-left text-gray-600">Orders</th>
                    <th class="px-6 py-3 text-left text-gray-600">Joined</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-4">{{ $user->orders->count() }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $users->links() }}</div>
</div>
@endsection
