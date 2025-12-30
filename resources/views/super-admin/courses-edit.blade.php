@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Course</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.courses.update', $course->id) }}">
    @csrf @method('PUT')
    <input type="text" name="course_name" value="{{ $course->course_name }}" class="border p-2 w-full mb-4" required>
    <textarea name="course_description" placeholder="Course Description (optional)" class="border p-2 w-full mb-4" rows="3">{{ $course->course_description }}</textarea>  <!-- Added field -->
    <select name="college_id" class="border p-2 w-full mb-4" required>
        <option value="">Select College</option>
        @foreach($colleges as $college)
            <option value="{{ $college->id }}" {{ $college->id == $course->college_id ? 'selected' : '' }}>{{ $college->college_name }}</option>
        @endforeach
    </select>
    <button type="submit" class="bg-yellow-500 text-black px-4 py-2 rounded">Update Course</button>
    <a href="{{ route('admin.dashboard', ['page' => 'courses']) }}" class="ml-4 text-gray-500">Cancel</a>
</form>

<form method="POST" action="{{ route('admin.courses.destroy', $course->id) }}" class="mt-4">
    @csrf @method('DELETE')
    <button type="submit" class="bg-red-500 text-black px-4 py-2 rounded" onclick="return confirm('Delete?')">Delete Course</button>
</form>
@endsection