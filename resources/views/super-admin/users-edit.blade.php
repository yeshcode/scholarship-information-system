@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit User</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.users.update', $user->id) }}">
    @csrf @method('PUT')
    <input type="text" name="user_id" value="{{ $user->user_id }}" placeholder="User ID" class="border p-2 w-full mb-4" required>
    <input type="email" name="bisu_email" value="{{ $user->bisu_email }}" placeholder="BISU Email" class="border p-2 w-full mb-4" required>
    <input type="text" name="firstname" value="{{ $user->firstname }}" placeholder="First Name" class="border p-2 w-full mb-4" required>
    <input type="text" name="lastname" value="{{ $user->lastname }}" placeholder="Last Name" class="border p-2 w-full mb-4" required>
    <input type="text" name="contact_no" value="{{ $user->contact_no }}" placeholder="Contact No" class="border p-2 w-full mb-4" required>
    <input type="text" name="student_id" value="{{ $user->student_id }}" placeholder="Student ID (optional)" class="border p-2 w-full mb-4">
    <select name="user_type_id" class="border p-2 w-full mb-4" required>
        <option value="">Select User Type</option>
        @foreach($userTypes as $type)
            <option value="{{ $type->id }}" {{ $user->user_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
        @endforeach
    </select>
    <select name="college_id" class="border p-2 w-full mb-4">
        <option value="">Select College (optional)</option>
        @foreach($colleges as $college)
            <option value="{{ $college->id }}" {{ $user->college_id == $college->id ? 'selected' : '' }}>{{ $college->college_name }}</option>
        @endforeach
    </select>
    <select name="year_level_id" class="border p-2 w-full mb-4">
        <option value="">Select Year Level (optional)</option>
        @foreach($yearLevels as $level)
            <option value="{{ $level->id }}" {{ $user->year_level_id == $level->id ? 'selected' : '' }}>{{ $level->year_level_name }}</option>
        @endforeach
    </select>
    <select name="section_id" class="border p-2 w-full mb-4">
        <option value="">Select Section (optional)</option>
        @foreach($sections as $section)
            <option value="{{ $section->id }}" {{ $user->section_id == $section->id ? 'selected' : '' }}>{{ $section->section_name }}</option>
        @endforeach
    </select>
    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update User</button>
    <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}" class="ml-4 text-gray-500">Cancel</a>
</form>
@endsection