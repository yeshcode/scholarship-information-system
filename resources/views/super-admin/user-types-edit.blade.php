@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit User Type</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.user-types.update', $userType->id) }}">
    @csrf
    @method('PUT')
    
    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" id="name" class="border p-2 w-full" value="{{ $userType->name }}" required>
    </div>
    
    <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" id="description" class="border p-2 w-full" rows="3">{{ $userType->description }}</textarea>
    </div>
    
    <div class="mb-4">
        <label for="dashboard_url" class="block text-sm font-medium text-gray-700">Dashboard URL</label>
        <input type="text" name="dashboard_url" id="dashboard_url" class="border p-2 w-full" value="{{ $userType->dashboard_url }}" placeholder="/newrole/dashboard">
    </div>
    
    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update User Type</button>
    <a href="{{ route('admin.dashboard', ['page' => 'user-type']) }}" class="ml-4 text-gray-500">Cancel</a>
</form>

<form method="POST" action="{{ route('admin.user-types.destroy', $userType->id) }}" class="mt-4">
    @csrf
    @method('DELETE')
    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('Delete?')">Delete User Type</button>
</form>
@endsection