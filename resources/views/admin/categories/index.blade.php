@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="bg-white rounded-xl border shadow-sm">
    <div class="p-6 border-b flex items-center justify-between">
        <h3 class="font-semibold text-gray-800">All Categories</h3>
        <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium">+ New Category</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-gray-600">Name</th>
                    <th class="px-6 py-3 text-left text-gray-600">Slug</th>
                    <th class="px-6 py-3 text-left text-gray-600">Active</th>
                    <th class="px-6 py-3 text-left text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($categories as $cat)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">{{ $cat->name }}</td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ $cat->slug }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $cat->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $cat->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 flex items-center gap-2">
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="px-3 py-1 rounded bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 rounded bg-red-50 text-red-600 hover:bg-red-100 text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $categories->links() }}</div>
</div>
@endsection
