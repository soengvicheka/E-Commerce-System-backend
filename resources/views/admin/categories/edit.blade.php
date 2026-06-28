@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="bg-white rounded-xl border shadow-sm p-6">
    <h3 class="font-semibold text-gray-800 mb-6">Edit Category</h3>
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-4 max-w-lg">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full px-4 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full px-4 py-2 border rounded-lg">{{ old('description', $category->description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Current Image</label>
            @if($category->image)
            <img src="{{ asset('storage/' . $category->image) }}" class="w-24 h-24 object-cover rounded-lg mb-2">
            @endif
            <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
            <label for="is_active" class="text-sm text-gray-700">Active</label>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium">Update</button>
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Cancel</a>
        </div>
    </form>
</div>
@endsection
