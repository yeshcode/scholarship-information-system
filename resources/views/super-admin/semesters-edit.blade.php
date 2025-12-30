@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Semester</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.semesters.update', $semester->id) }}">
    @csrf @method('PUT')
    <input type="text" name="term" value="{{ $semester->term }}" class="border p-2 w-full mb-4" required>
    <input type="text" name="academic_year" value="{{ $semester->academic_year }}" class="border p-2 w-full mb-4" required>
    <input type="date" name="start_date" value="{{ $semester->start_date }}" class="border p-2 w-full mb-4" required>
    <input type="date" name="end_date" value="{{ $semester->end_date }}" class="border p-2 w-full mb-4" required>
    <label class="block mb-4">
        <input type="checkbox" name="is_current" value="1" {{ $semester->is_current ? 'checked' : '' }}> Is Current Semester?
    </label>
    <button type="submit" class="bg-yellow-500 text-black px-4 py-2 rounded">Update Semester</button>
    <a href="{{ route('admin.dashboard', ['page' => 'semesters']) }}" class="ml-4 text-gray-500">Cancel</a>
</form>

<form method="POST" action="{{ route('admin.semesters.destroy', $semester->id) }}" class="mt-4">
    @csrf @method('DELETE')
    <button type="submit" class="bg-red-500 text-black px-4 py-2 rounded" onclick="return confirm('Delete?')">Delete Semester</button>
</form>
@endsection