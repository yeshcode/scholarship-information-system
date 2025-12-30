@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Add Enrollment</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.enrollments.store') }}">
    @csrf
    <select name="user_id" class="border p-2 w-full mb-4" required>
        <option value="">Select User</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }} ({{ $user->user_id }})</option>
        @endforeach
    </select>
    <select name="semester_id" class="border p-2 w-full mb-4" required>
        <option value="">Select Semester</option>
        @foreach($semesters as $semester)
            <option value="{{ $semester->id }}">{{ $semester->term }} {{ $semester->academic_year }}</option>
        @endforeach
    </select>
    <select name="section_id" class="border p-2 w-full mb-4" required>
        <option value="">Select Section</option>
        @foreach($sections as $section)
            <option value="{{ $section->id }}">{{ $section->section_name }} ({{ $section->course->course_name ?? 'N/A' }} - {{ $section->yearLevel->year_level_name ?? 'N/A' }})</option>
        @endforeach
    </select>
    <input type="text" name="status" placeholder="Status (e.g., active)" class="border p-2 w-full mb-4" required>
    <button type="submit" class="bg-green-500 text-black px-4 py-2 rounded">Add Enrollment</button>
    <a href="{{ route('admin.dashboard', ['page' => 'enrollments']) }}" class="ml-4 text-gray-500">Cancel</a>
</form>
@endsection