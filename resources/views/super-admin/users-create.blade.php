@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Add User</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="bg-red-100 text-red-800 p-4 mb-4 rounded">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    <div class="mb-4">
        <label for="user_id" class="block text-sm font-medium text-gray-700">User ID</label>
        <input type="text" name="user_id" id="user_id" class="border p-2 w-full" required>
    </div>
    <div class="mb-4">
        <label for="bisu_email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="bisu_email" id="bisu_email" class="border p-2 w-full" required>
    </div>
    <div class="mb-4">
        <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
        <input type="text" name="firstname" id="firstname" class="border p-2 w-full" required>
    </div>
    <div class="mb-4">
        <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
        <input type="text" name="lastname" id="lastname" class="border p-2 w-full" required>
    </div>
    <div class="mb-4">
        <label for="contact_no" class="block text-sm font-medium text-gray-700">Contact No</label>
        <input type="text" name="contact_no" id="contact_no" class="border p-2 w-full" required>
    </div>
    <div class="mb-4">
        <label for="student_id" class="block text-sm font-medium text-gray-700">Student ID</label>
        <input type="text" name="student_id" id="student_id" class="border p-2 w-full">
    </div>
    <div class="mb-4">
        <label for="user_type_id" class="block text-sm font-medium text-gray-700">User Type</label>
        <select name="user_type_id" id="user_type_id" class="border p-2 w-full" required>
            <option value="">Select User Type</option>
            @foreach($userTypes as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label for="college_id" class="block text-sm font-medium text-gray-700">College</label>
        <select name="college_id" id="college_id" class="border p-2 w-full">
            <option value="">Select College</option>
            @foreach($colleges as $college)
                <option value="{{ $college->id }}">{{ $college->college_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label for="year_level_id" class="block text-sm font-medium text-gray-700">Year Level</label>
        <select name="year_level_id" id="year_level_id" class="border p-2 w-full">
            <option value="">Select Year Level</option>
            @foreach($yearLevels as $level)
                <option value="{{ $level->id }}">{{ $level->year_level_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label for="section_id" class="block text-sm font-medium text-gray-700">Section</label>
        <select name="section_id" id="section_id" class="border p-2 w-full">
            <option value="">Select Section</option>
            @foreach($sections as $section)
                <option value="{{ $section->id }}">{{ $section->section_name }} ({{ $section->course->course_name ?? 'N/A' }})</option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" id="password" class="border p-2 w-full" required>
    </div>
    <button type="submit" class="bg-green-500 text-black px-4 py-2 rounded hover:bg-green-600">Add User</button>
    <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}" class="ml-4 text-gray-500">Cancel</a>
</form>
@endsection