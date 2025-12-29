@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Manage Users</h1>
<p class="mb-6">Manage users here. Data is loaded from your Supabase database.</p>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="bg-red-100 text-red-800 p-4 mb-4 rounded">{{ session('error') }}</div>
@endif

@if(isset($error))
    <div class="bg-red-100 text-red-800 p-4 mb-4 rounded">{{ $error }}</div>
@endif

<!-- Buttons -->
<a href="{{ route('admin.users.create') }}" class="bg-gray-800 text-white hover:bg-gray-900 font-bold py-2 px-4 rounded mb-4 inline-block mr-4 border border-gray-800" style="background-color: gray; color: white; padding: 10px; border: 1px solid black;">
    Add User
</a>
<a href="{{ route('admin.users.bulk-upload-form') }}" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded mb-4 border border-blue-600" style="background-color: blue; color: white; padding: 10px; border: 1px solid blue;">
    Bulk Upload Students
</a>

<!-- Table with Data (Scrollable for responsiveness) -->
<div class="overflow-x-auto">
    <table class="table-auto w-full border-collapse border border-gray-300 text-center min-w-max">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">User ID</th>
                <th class="border border-gray-300 px-4 py-2">Name</th>
                <th class="border border-gray-300 px-4 py-2">Email</th>
                <th class="border border-gray-300 px-4 py-2">User Type</th>
                <th class="border border-gray-300 px-4 py-2">College</th>
                <th class="border border-gray-300 px-4 py-2">Year Level</th>
                <th class="border border-gray-300 px-4 py-2">Section</th>
                <th class="border border-gray-300 px-4 py-2">Status</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users ?? [] as $user)
                <tr class="hover:bg-gray-100">
                    <td class="border border-gray-300 px-4 py-2">{{ $user->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->user_id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->firstname }} {{ $user->lastname }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->bisu_email }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->userType->name ?? 'N/A' }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->college->college_name ?? 'N/A' }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->yearLevel->year_level_name ?? 'N/A' }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->section->section_name ?? 'N/A' }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->status }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-500 mr-2">Edit</a>
                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection