{{-- resources/views/super-admin/user-types-delete.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-lg rounded-lg p-8">
    <h1 class="text-2xl font-bold mb-6 text-red-600">Confirm Deletion</h1>
    <p class="text-gray-700 mb-4">Are you sure you want to delete this user type? This action cannot be undone and may affect related users or roles.</p>

    <div class="bg-gray-50 p-6 rounded-lg mb-6 border">
        <h2 class="font-semibold text-lg text-gray-800">{{ $userType->name }}</h2>
        <p class="text-sm text-gray-600 mt-2">{{ $userType->description ?? 'No description' }}</p>
        <p class="text-sm text-gray-600">Dashboard URL: {{ $userType->dashboard_url ?? 'N/A' }}</p>
    </div>

    <form method="POST" action="{{ route('admin.user-types.destroy', $userType->id) }}">
        @csrf
        @method('DELETE')
        <div class="flex space-x-4">
            <button type="submit" class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-200">
                <span class="mr-2">🗑️</span> Yes, Delete
            </button>
            <a href="{{ route('admin.dashboard', ['page' => 'user-type']) }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 font-medium py-3 px-6 transition duration-200">
                <span class="mr-2">❌</span> Cancel
            </a>
        </div>
    </form>
</div>
@endsection