@extends('layouts.super-admin')

@section('page-content')
<h1 class="text-2xl font-bold mb-4">Add New Section</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.sections.store') }}">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <select name="course_id" class="border p-2 w-full" required>
            <option value="">Select Course</option>
            @foreach($courses ?? [] as $course)
                <option value="{{ $course->id }}">{{ $course->course_name }}</option>
            @endforeach
        </select>
        <select name="year_level_id" class="border p-2 w-full" required>
            <option value="">Select Year Level</option>
            @foreach($yearLevels ?? [] as $level)
                <option value="{{ $level->id }}">{{ $level->year_level_name }}</option>
            @endforeach
        </select>
        <input type="text" name="section_name" placeholder="Section Name" class="border p-2 w-full" required>
    </div>
    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Add Section</button>
    <a href="{{ route('admin.dashboard', ['page' => 'sections']) }}" class="ml-4 text-gray-500">Cancel</a>
</form>
@endsection