@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Enrollment</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.enrollments.update', $enrollment->id) }}">
    @csrf @method('PUT')
    <select name="user_id" class="border p-2 w-full mb-4" required>
        <option value="">Select User</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ $enrollment->user_id == $user->id ? 'selected' : '' }}>{{ $user->firstname }} {{ $user->lastname }} ({{ $user->user_id }})</option>
        @endforeach
    </select>
    <select name="semester_id" class="border p-2 w-full mb-4" required>
        <option value="">Select Semester</option>
        @foreach($semesters as $semester)
            <option value="{{ $semester->id }}" {{ $enrollment->semester_id == $semester->id ? 'selected' : '' }}>{{ $semester->term }} {{ $semester->academic_year }}</option>
        @endforeach
    </select>
    <select name="section_id" class="border p-2 w-full mb-4" required>
        <option value="">Select Section</option>
        @foreach($sections as $section)
            <option value="{{ $section->id }}" {{ $enrollment->section_id == $section->id ? 'selected' : '' }}>{{ $section->section_name }} ({{ $section->course->course_name ?? 'N/A' }} - {{ $section->yearLevel->year_level_name ?? 'N/A' }})</option>
        @endforeach
    </select>
    <select name="course_id" class="border p-2 w-full mb-4" required>
        <option value="">Select Course</option>
        @foreach($courses as $course)
            <option value="{{ $course->id }}" {{ $enrollment->course_id == $course->id ? 'selected' : '' }}>{{ $course->course_name }}</option>
        @endforeach
    </select>
    
    <!-- Updated Status Dropdown (replaces the text input) -->
    <label for="status" class="block text-gray-700 mb-2">Status</label>
    <select name="status" id="status" class="border p-2 w-full mb-4" required>
        <option value="">Select Status</option>
        <option value="enrolled" {{ $enrollment->status == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
        <option value="graduated" {{ $enrollment->status == 'graduated' ? 'selected' : '' }}>Graduated</option>
        <option value="not_enrolled" {{ $enrollment->status == 'not_enrolled' ? 'selected' : '' }}>Not Enrolled</option>
    </select>
    
    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update Enrollment</button>
    <a href="{{ route('admin.dashboard', ['page' => 'enrollments']) }}" class="ml-4 text-gray-500">Cancel</a>
</form>

<form method="POST" action="{{ route('admin.enrollments.destroy', $enrollment->id) }}" class="mt-4">
    @csrf @method('DELETE')
    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('Delete?')">Delete Enrollment</button>
</form>
@endsection