{{-- resources/views/super-admin/users-delete.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-sm mx-auto bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-xl font-bold mb-4 text-red-600">Confirm Deletion</h1>
        <p class="text-gray-700 mb-4 text-sm">
            Are you sure you want to delete this user? This action cannot be undone.
        </p>

        @php
            $courseName = $user->section->course->course_name ?? 'N/A';
            $yearName   = $user->yearLevel->year_level_name ?? 'N/A';
            $courseYear = ($courseName !== 'N/A' || $yearName !== 'N/A')
                            ? trim($courseName . ' - ' . $yearName, ' -')
                            : 'N/A';
        @endphp

        <div class="bg-gray-50 p-4 rounded-lg mb-4 border">
            <h2 class="font-semibold text-gray-800">
                {{ $user->lastname }}, {{ $user->firstname }}
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                Email: <span class="font-medium">{{ $user->bisu_email }}</span>
            </p>
            <p class="text-sm text-gray-600">
                Student ID: <span class="font-medium">{{ $user->student_id ?? 'N/A' }}</span>
            </p>
            <p class="text-sm text-gray-600">
                Course &amp; Year: <span class="font-medium">{{ $courseYear }}</span>
            </p>
            <p class="text-sm text-gray-600">
                College: <span class="font-medium">{{ $user->college->college_name ?? 'N/A' }}</span>
            </p>
            <p class="text-sm text-gray-600">
                Status: <span class="font-medium capitalize">{{ $user->status }}</span>
            </p>
        </div>

        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
            @csrf
            @method('DELETE')
            <div class="flex space-x-3">
                <button type="submit"
                        class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-200 text-sm">
                    🗑️ <span class="ml-1">Yes, Delete</span>
                </button>
                <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}"
                   class="inline-flex items-center text-gray-600 hover:text-gray-800 font-medium py-2 px-4 transition duration-200 text-sm">
                    ❌ <span class="ml-1">Cancel</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
