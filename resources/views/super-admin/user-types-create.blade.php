@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Add User Type</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.user-types.store') }}">
    @csrf
    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" id="name" class="border p-2 w-full" placeholder="Name" required>
    </div>
    <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" id="description" class="border p-2 w-full" rows="3" placeholder="Description"></textarea>
    </div>
    <div class="mb-4">
        <label for="dashboard_url" class="block text-sm font-medium text-gray-700">Dashboard URL</label>
        <input type="text" name="dashboard_url" id="dashboard_url" class="border p-2 w-full" placeholder="/newrole/dashboard">
    </div>
    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Add User Type</button>
    <a href="{{ route('admin.dashboard', ['page' => 'user-type']) }}" class="ml-4 text-gray-500">Cancel</a>
</form>
@endsection