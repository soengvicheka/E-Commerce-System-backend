@extends('layouts.admin')

@section('title', 'Create Category')

@section('content')
<div class="bg-white rounded-xl border shadow-sm p-6">
    <h3 class="font-semibold text-gray-800 mb-6">Create Category</h3>
    <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4 max-w-lg">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full px-4 py-2 border rounded-lg">{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
            <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" checked>
            <label for="is_active" class="text-sm text-gray-700">Active</label>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium">Save</button>
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Cancel</a>
        </div>
    </form>
</div>
@endsection
