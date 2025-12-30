@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6 text-gray-800">Add User Type</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded-lg shadow-sm">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.user-types.store') }}">
    @csrf
    <div class="mb-6">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
        <input type="text" name="name" id="name" class="border border-gray-300 rounded-lg p-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Name" required>
    </div>
    <div class="mb-6">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
        <textarea name="description" id="description" class="border border-gray-300 rounded-lg p-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent" rows="3" placeholder="Description"></textarea>
    </div>
    <div class="mb-6">
        <label for="dashboard_url" class="block text-sm font-medium text-gray-700 mb-2">Dashboard URL</label>
        <input type="text" name="dashboard_url" id="dashboard_url" class="border border-gray-300 rounded-lg p-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="/newrole/dashboard">
    </div>
    <div class="flex space-x-4">
        <button type="submit" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-black font-bold py-3 px-6 rounded-lg shadow-md transition duration-200">
            <span class="mr-2">✅</span> Add User Type
        </button>
        <a href="{{ route('admin.dashboard', ['page' => 'user-type']) }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 font-medium py-3 px-6 transition duration-200">
            <span class="mr-2">❌</span> Cancel
        </a>
    </div>
</form>
@endsection