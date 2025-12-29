@extends('layouts.super-admin')

@section('page-content')
<h1 class="text-2xl font-bold mb-4">Edit Section</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.sections.update', $section->id) }}">
    @csrf @method('PUT')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <select name="course_id" class="border p-2 w-full" required>
            <option value="">Select Course</option>
            @foreach($courses ?? [] as $course)
                <option value="{{ $course->id }}" {{ $section->course_id == $course->id ? 'selected' : '' }}>{{ $course->course_name }}</option>
            @endforeach
        </select>
        <select name="year_level_id" class="border p-2 w-full" required>
            <option value="">Select Year Level</option>
            @foreach($yearLevels ?? [] as $level)
                <option value="{{ $level->id }}" {{ $section->year_level_id == $level->id ? 'selected' : '' }}>{{ $level->year_level_name }}</option>
            @endforeach
        </select>
        <input type="text" name="section_name" value="{{ $section->section_name }}" class="border p-2 w-full" required>
    </div>
    <button type="submit" class="bg-yellow-500 text-black px-4 py-2 rounded">Update Section</button>
    <a href="{{ route('admin.dashboard', ['page' => 'sections']) }}" class="ml-4 text-gray-500">Cancel</a>
</form>

<!-- Delete Option -->
<form method="POST" action="{{ route('admin.sections.destroy', $section->id) }}" class="mt-4">
    @csrf @method('DELETE')
    <button type="submit" class="bg-red-500 text-black px-2 py-1 rounded" onclick="return confirm('Are you sure you want to delete this section?')">Delete Section</button>
</form>
@endsection