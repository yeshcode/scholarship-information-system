@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Add Course</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.courses.store') }}">
    @csrf
    <input type="text" name="course_name" placeholder="Course Name" class="border p-2 w-full mb-4" required>
    <textarea name="course_description" placeholder="Course Description (optional)" class="border p-2 w-full mb-4" rows="3"></textarea>  <!-- Added field -->
    <select name="college_id" class="border p-2 w-full mb-4" required>
        <option value="">Select College</option>
        @foreach($colleges as $college)
            <option value="{{ $college->id }}">{{ $college->college_name }}</option>
        @endforeach
    </select>
    <button type="submit" class="bg-green-500 text-black px-4 py-2 rounded">Add Course</button>
    <a href="{{ route('admin.dashboard', ['page' => 'courses']) }}" class="ml-4 text-gray-500">Cancel</a>
</form>
@endsection