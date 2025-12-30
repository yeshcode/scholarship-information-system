{{-- resources/views/super-admin/users-delete.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">  {{-- Centers the content like a small modal --}}
    <div class="max-w-sm mx-auto bg-white shadow-lg rounded-lg p-6">  {{-- Smaller width for compactness --}}
        <h1 class="text-xl font-bold mb-4 text-red-600">Confirm Deletion</h1>
        <p class="text-gray-700 mb-4">Are you sure you want to delete this user? This action cannot be undone.</p>

        <div class="bg-gray-50 p-4 rounded-lg mb-4 border">
            <h2 class="font-semibold text-gray-800">{{ $user->firstname }} {{ $user->lastname }}</h2>
            <p class="text-sm text-gray-600 mt-2">User ID: {{ $user->user_id }}</p>
            <p class="text-sm text-gray-600">Email: {{ $user->bisu_email }}</p>
            <p class="text-sm text-gray-600">User Type: {{ $user->userType->name ?? 'N/A' }}</p>
            <p class="text-sm text-gray-600">Status: {{ $user->status }}</p>
        </div>

        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
            @csrf
            @method('DELETE')
            <div class="flex space-x-3">
                <button type="submit" class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-200 text-sm">
                    <span class="mr-1">🗑️</span> Yes, Delete
                </button>
                <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 font-medium py-2 px-4 transition duration-200 text-sm">
                    <span class="mr-1">❌</span> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection